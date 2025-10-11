<?php
require_once 'models/PermohonanPetugasModel.php';
require_once 'models/SKPDModel.php';
require_once 'models/UserModel.php';

class PermohonanAdminController
{
    private $permohonanAdminModel;
    private $skpdModel;
    private $userModel;
    private $conn;

    public function __construct()
    {
        // Validasi session admin
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            $_SESSION['error_message'] = 'Akses ditolak. Hanya admin yang dapat mengakses halaman ini.';
            header('Location: index.php?controller=auth&action=login');
            exit();
        }

        global $database;
        $db = $database->getConnection();
        $this->conn = $db;
        $this->permohonanAdminModel = new PermohonanPetugasModel($db);
        $this->skpdModel = new SKPDModel($db);
        $this->userModel = new UserModel($db);
    }

    // ============ MAIN INDEX - MEJA LAYANAN ============

    /**
     * Halaman utama meja layanan dengan statistik semua status
     */
    public function index()
    {
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;
        $status = isset($_GET['status']) ? $_GET['status'] : 'all';
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';

        // Get permohonan list
        $permohonan_list = $this->permohonanAdminModel->getAllPermohonan($limit, $offset, $status, $search);
        $total_records = $this->permohonanAdminModel->countAllPermohonan($status, $search);
        $total_pages = ceil($total_records / $limit);

        // Get statistics
        $stats = $this->permohonanAdminModel->getPetugasStats();

        // Extract session messages
        $success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
        $error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
        unset($_SESSION['success_message']);
        unset($_SESSION['error_message']);

        include 'views/permohonan_admin/index.php';
    }

    // ============ VIEW DETAIL ============

    /**
     * Lihat detail permohonan dengan validasi session admin
     */
    public function view()
    {
        if (!isset($_GET['id'])) {
            $_SESSION['error_message'] = 'ID permohonan tidak ditemukan';
            header('Location: index.php?controller=permohonanadmin&action=index');
            exit();
        }

        $id = intval($_GET['id']);
        $permohonan = $this->permohonanAdminModel->getPermohonanById($id);

        if (!$permohonan) {
            $_SESSION['error_message'] = 'Permohonan tidak ditemukan';
            header('Location: index.php?controller=permohonanadmin&action=index');
            exit();
        }

        $skpd_data = $this->getSKPDData($permohonan['komponen_tujuan']);

        $success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
        $error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
        unset($_SESSION['success_message']);
        unset($_SESSION['error_message']);

        include 'views/permohonan_admin/view.php';
    }

    // ============ CREATE & STORE ============

    /**
     * Form create permohonan baru (muat SKPD dan tujuan permohonan)
     */
    public function create()
    {
        // Get all SKPD for dropdown
        $skpd_list = $this->skpdModel->getAllSKPD();

        // Get all users for pemohon dropdown
        $query = "SELECT u.id_user, u.username, bp.nama_lengkap, bp.nik
                  FROM users u
                  LEFT JOIN biodata_pengguna bp ON u.id_biodata = bp.id_biodata
                  WHERE u.role = 'user'
                  ORDER BY u.username ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $users_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Get old input for form repopulation
        $old_input = isset($_SESSION['old_input']) ? $_SESSION['old_input'] : [];
        unset($_SESSION['old_input']); // Clear after retrieving

        $success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
        $error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
        unset($_SESSION['success_message']);
        unset($_SESSION['error_message']);

        include 'views/permohonan_admin/create.php';
    }

    /**
     * Proses simpan permohonan baru dengan validasi dan file upload
     */
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error_message'] = 'Metode tidak diizinkan';
            header('Location: index.php?controller=permohonanadmin&action=create');
            exit();
        }

        // Save POST data to session for form repopulation on error
        $_SESSION['old_input'] = $_POST;

        // Validasi input untuk user baru
        $required_fields = ['nama_lengkap', 'nik', 'email', 'password', 'judul_dokumen', 'tujuan_penggunaan_informasi', 'tujuan_permohonan', 'komponen_tujuan'];
        foreach ($required_fields as $field) {
            if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
                $_SESSION['error_message'] = 'Field ' . $field . ' wajib diisi';
                header('Location: index.php?controller=permohonanadmin&action=create');
                exit();
            }
        }

        // Validasi NIK 16 digit
        $nik = trim($_POST['nik']);
        if (!preg_match('/^\d{16}$/', $nik)) {
            $_SESSION['error_message'] = 'NIK harus 16 digit angka';
            header('Location: index.php?controller=permohonanadmin&action=create');
            exit();
        }

        // Validasi email format
        $email = trim($_POST['email']);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error_message'] = 'Format email tidak valid';
            header('Location: index.php?controller=permohonanadmin&action=create');
            exit();
        }

        // Check if email already exists
        $checkEmailQuery = "SELECT id_user FROM users WHERE email = :email LIMIT 1";
        $checkStmt = $this->conn->prepare($checkEmailQuery);
        $checkStmt->bindParam(':email', $email);
        $checkStmt->execute();
        if ($checkStmt->fetch()) {
            $_SESSION['error_message'] = 'Email sudah terdaftar';
            header('Location: index.php?controller=permohonanadmin&action=create');
            exit();
        }

        // Check if NIK already exists
        $checkNikQuery = "SELECT id_biodata FROM biodata_pengguna WHERE nik = :nik LIMIT 1";
        $checkNikStmt = $this->conn->prepare($checkNikQuery);
        $checkNikStmt->bindParam(':nik', $nik);
        $checkNikStmt->execute();
        if ($checkNikStmt->fetch()) {
            $_SESSION['error_message'] = 'NIK sudah terdaftar';
            header('Location: index.php?controller=permohonanadmin&action=create');
            exit();
        }

        // Begin transaction
        try {
            $this->conn->beginTransaction();

            // 1. Create biodata_pengguna first
            $biodataQuery = "INSERT INTO biodata_pengguna
                            (nama_lengkap, nik, alamat, provinsi, city, no_kontak, email, jenis_kelamin,
                             usia, pendidikan, pekerjaan, status_pengguna, nama_lembaga)
                            VALUES
                            (:nama_lengkap, :nik, :alamat, :provinsi, :city, :no_kontak, :email, :jenis_kelamin,
                             :usia, :pendidikan, :pekerjaan, :status_pengguna, :nama_lembaga)";

            $biodataStmt = $this->conn->prepare($biodataQuery);
            $biodataStmt->bindValue(':nama_lengkap', trim($_POST['nama_lengkap']));
            $biodataStmt->bindValue(':nik', $nik);
            $biodataStmt->bindValue(':alamat', trim($_POST['alamat'] ?? ''));
            $biodataStmt->bindValue(':provinsi', trim($_POST['provinsi'] ?? ''));
            $biodataStmt->bindValue(':city', trim($_POST['city'] ?? ''));
            $biodataStmt->bindValue(':no_kontak', trim($_POST['no_kontak'] ?? ''));
            $biodataStmt->bindValue(':email', $email);
            $biodataStmt->bindValue(':jenis_kelamin', trim($_POST['jenis_kelamin'] ?? ''));
            $biodataStmt->bindValue(':usia', !empty($_POST['usia']) ? intval($_POST['usia']) : null, PDO::PARAM_INT);
            $biodataStmt->bindValue(':pendidikan', trim($_POST['pendidikan'] ?? ''));
            $biodataStmt->bindValue(':pekerjaan', trim($_POST['pekerjaan'] ?? ''));
            $biodataStmt->bindValue(':status_pengguna', trim($_POST['status_pengguna'] ?? 'pribadi'));
            $biodataStmt->bindValue(':nama_lembaga', trim($_POST['nama_lembaga'] ?? ''));

            if (!$biodataStmt->execute()) {
                throw new Exception('Gagal menyimpan data biodata');
            }

            $id_biodata = $this->conn->lastInsertId();

            // 2. Create user account
            $username = trim($_POST['username'] ?? $_POST['email']);
            $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);

            $userQuery = "INSERT INTO users (username, email, password, role, id_biodata, created_at)
                         VALUES (:username, :email, :password, 'user', :id_biodata, NOW())";

            $userStmt = $this->conn->prepare($userQuery);
            $userStmt->bindValue(':username', $username);
            $userStmt->bindValue(':email', $email);
            $userStmt->bindValue(':password', $password);
            $userStmt->bindValue(':id_biodata', $id_biodata);

            if (!$userStmt->execute()) {
                throw new Exception('Gagal membuat akun user');
            }

            $id_user = $this->conn->lastInsertId();

            // Continue with permohonan creation
            // (existing code will use $id_user that was just created)
        $judul_dokumen = trim($_POST['judul_dokumen']);
        $tujuan_penggunaan_informasi = trim($_POST['tujuan_penggunaan_informasi']);
        $tujuan_permohonan = trim($_POST['tujuan_permohonan']);
        $komponen_tujuan = trim($_POST['komponen_tujuan']);
        $kandungan_informasi = isset($_POST['kandungan_informasi']) ? trim($_POST['kandungan_informasi']) : '';
        $sumber_media = isset($_POST['sumber_media']) ? trim($_POST['sumber_media']) : '';

        // Generate nomor permohonan
        $no_permohonan = $this->generateNoPermohonan();

        // Handle file uploads
        $upload_foto_identitas = '';
        $upload_data_pedukung = '';

        if (isset($_FILES['upload_foto_identitas']) && $_FILES['upload_foto_identitas']['error'] === UPLOAD_ERR_OK) {
            $upload_foto_identitas = $this->handleFileUpload($_FILES['upload_foto_identitas'], 'identitas');
        }

        if (isset($_FILES['upload_data_pedukung']) && $_FILES['upload_data_pedukung']['error'] === UPLOAD_ERR_OK) {
            $upload_data_pedukung = $this->handleFileUpload($_FILES['upload_data_pedukung'], 'pendukung');
        }

        // Insert permohonan
        try {
            $query = "INSERT INTO permohonan
                      (id_user, no_permohonan, judul_dokumen, tujuan_penggunaan_informasi,
                       tujuan_permohonan, komponen_tujuan, kandungan_informasi, sumber_media,
                       upload_foto_identitas, upload_data_pedukung, status, created_at)
                      VALUES
                      (:id_user, :no_permohonan, :judul_dokumen, :tujuan_penggunaan_informasi,
                       :tujuan_permohonan, :komponen_tujuan, :kandungan_informasi, :sumber_media,
                       :upload_foto_identitas, :upload_data_pedukung, 'Masuk', NOW())";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_user', $id_user);
            $stmt->bindParam(':no_permohonan', $no_permohonan);
            $stmt->bindParam(':judul_dokumen', $judul_dokumen);
            $stmt->bindParam(':tujuan_penggunaan_informasi', $tujuan_penggunaan_informasi);
            $stmt->bindParam(':tujuan_permohonan', $tujuan_permohonan);
            $stmt->bindParam(':komponen_tujuan', $komponen_tujuan);
            $stmt->bindParam(':kandungan_informasi', $kandungan_informasi);
            $stmt->bindParam(':sumber_media', $sumber_media);
            $stmt->bindParam(':upload_foto_identitas', $upload_foto_identitas);
            $stmt->bindParam(':upload_data_pedukung', $upload_data_pedukung);

            if ($stmt->execute()) {
                // Clear old input on success
                unset($_SESSION['old_input']);
                $_SESSION['success_message'] = 'Permohonan berhasil ditambahkan';
                header('Location: index.php?controller=permohonanadmin&action=index');
            } else {
                $_SESSION['error_message'] = 'Gagal menambahkan permohonan';
                header('Location: index.php?controller=permohonanadmin&action=create');
            }
        } catch (Exception $e) {
            $_SESSION['error_message'] = 'Error: ' . $e->getMessage();
            header('Location: index.php?controller=permohonanadmin&action=create');
        }
        exit();
        }

    // ============ EDIT & UPDATE ============

    /**
     * Form edit permohonan
     */
    public function edit()
    {
        if (!isset($_GET['id'])) {
            $_SESSION['error_message'] = 'ID permohonan tidak ditemukan';
            header('Location: index.php?controller=permohonanadmin&action=index');
            exit();
        }

        $id = intval($_GET['id']);
        $permohonan = $this->permohonanAdminModel->getPermohonanById($id);

        if (!$permohonan) {
            $_SESSION['error_message'] = 'Permohonan tidak ditemukan';
            header('Location: index.php?controller=permohonanadmin&action=index');
            exit();
        }

        $skpd_list = $this->skpdModel->getAllSKPD();
        $skpd_data = $this->getSKPDData($permohonan['komponen_tujuan']);

        $success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
        $error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
        unset($_SESSION['success_message']);
        unset($_SESSION['error_message']);

        include 'views/permohonan_admin/edit.php';
    }

    /**
     * Proses update permohonan
     */
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error_message'] = 'Metode tidak diizinkan';
            header('Location: index.php?controller=permohonanadmin&action=index');
            exit();
        }

        if (!isset($_POST['id_permohonan']) || empty($_POST['id_permohonan'])) {
            $_SESSION['error_message'] = 'ID permohonan tidak ditemukan';
            header('Location: index.php?controller=permohonanadmin&action=index');
            exit();
        }

        $id = intval($_POST['id_permohonan']);
        $judul_dokumen = trim($_POST['judul_dokumen'] ?? '');
        $tujuan_penggunaan_informasi = trim($_POST['tujuan_penggunaan_informasi'] ?? '');
        $tujuan_permohonan = trim($_POST['tujuan_permohonan'] ?? '');
        $komponen_tujuan = trim($_POST['komponen_tujuan'] ?? '');
        $kandungan_informasi = trim($_POST['kandungan_informasi'] ?? '');
        $sumber_media = trim($_POST['sumber_media'] ?? '');

        if (empty($judul_dokumen) || empty($tujuan_penggunaan_informasi)) {
            $_SESSION['error_message'] = 'Judul dokumen dan tujuan penggunaan informasi wajib diisi';
            header("Location: index.php?controller=permohonanadmin&action=edit&id=$id");
            exit();
        }

        try {
            $query = "UPDATE permohonan
                      SET judul_dokumen = :judul_dokumen,
                          tujuan_penggunaan_informasi = :tujuan_penggunaan_informasi,
                          tujuan_permohonan = :tujuan_permohonan,
                          komponen_tujuan = :komponen_tujuan,
                          kandungan_informasi = :kandungan_informasi,
                          sumber_media = :sumber_media,
                          updated_at = NOW()
                      WHERE id_permohonan = :id_permohonan";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':judul_dokumen', $judul_dokumen);
            $stmt->bindParam(':tujuan_penggunaan_informasi', $tujuan_penggunaan_informasi);
            $stmt->bindParam(':tujuan_permohonan', $tujuan_permohonan);
            $stmt->bindParam(':komponen_tujuan', $komponen_tujuan);
            $stmt->bindParam(':kandungan_informasi', $kandungan_informasi);
            $stmt->bindParam(':sumber_media', $sumber_media);
            $stmt->bindParam(':id_permohonan', $id);

            if ($stmt->execute()) {
                $_SESSION['success_message'] = 'Permohonan berhasil diperbarui';
                header("Location: index.php?controller=permohonanadmin&action=view&id=$id");
            } else {
                $_SESSION['error_message'] = 'Gagal memperbarui permohonan';
                header("Location: index.php?controller=permohonanadmin&action=edit&id=$id");
            }
        } catch (Exception $e) {
            $_SESSION['error_message'] = 'Error: ' . $e->getMessage();
            header("Location: index.php?controller=permohonanadmin&action=edit&id=$id");
        }
        exit();
    }

    // ============ DELETE ============

    /**
     * Hapus permohonan
     */
    public function delete()
    {
        if (!isset($_GET['id'])) {
            $_SESSION['error_message'] = 'ID permohonan tidak ditemukan';
            header('Location: index.php?controller=permohonanadmin&action=index');
            exit();
        }

        $id = intval($_GET['id']);
        $permohonan = $this->permohonanAdminModel->getPermohonanById($id);

        if (!$permohonan) {
            $_SESSION['error_message'] = 'Permohonan tidak ditemukan';
            header('Location: index.php?controller=permohonanadmin&action=index');
            exit();
        }

        try {
            $this->conn->beginTransaction();

            // Delete related keberatan first
            $deleteKeberatanQuery = "DELETE FROM keberatan WHERE id_permohonan = :id";
            $keberatanStmt = $this->conn->prepare($deleteKeberatanQuery);
            $keberatanStmt->bindParam(':id', $id);
            $keberatanStmt->execute();

            // Delete files
            if (!empty($permohonan['upload_foto_identitas']) && file_exists($permohonan['upload_foto_identitas'])) {
                unlink($permohonan['upload_foto_identitas']);
            }
            if (!empty($permohonan['upload_data_pedukung']) && file_exists($permohonan['upload_data_pedukung'])) {
                unlink($permohonan['upload_data_pedukung']);
            }

            // Delete permohonan
            $query = "DELETE FROM permohonan WHERE id_permohonan = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);

            if ($stmt->execute()) {
                $this->conn->commit();
                $_SESSION['success_message'] = 'Permohonan berhasil dihapus';
            } else {
                $this->conn->rollBack();
                $_SESSION['error_message'] = 'Gagal menghapus permohonan';
            }
        } catch (Exception $e) {
            $this->conn->rollBack();
            $_SESSION['error_message'] = 'Error: ' . $e->getMessage();
        }

        header('Location: index.php?controller=permohonanadmin&action=index');
        exit();
    }

    // ============ UPDATE STATUS ============

    /**
     * Update status permohonan (handle berbagai status)
     */
    public function updateStatus()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Metode tidak diizinkan']);
            exit();
        }

        $id = isset($_POST['id']) ? intval($_POST['id']) : (isset($_POST['id_permohonan']) ? intval($_POST['id_permohonan']) : 0);
        $status = trim($_POST['status'] ?? '');

        if (!$id || empty($status)) {
            echo json_encode(['success' => false, 'message' => 'Data tidak lengkap']);
            exit();
        }

        $allowed_statuses = ['Masuk', 'Diproses', 'Disposisi', 'Selesai', 'Ditolak', 'Keberatan', 'Sengketa'];
        if (!in_array($status, $allowed_statuses)) {
            echo json_encode(['success' => false, 'message' => 'Status tidak valid']);
            exit();
        }

        // Process based on status
        $result = false;
        switch ($status) {
            case 'Diproses':
                $catatan_petugas = trim($_POST['catatan_petugas'] ?? '');
                if (empty($catatan_petugas)) {
                    echo json_encode(['success' => false, 'message' => 'Catatan petugas harus diisi']);
                    exit();
                }
                $result = $this->permohonanAdminModel->updateStatusWithCatatan($id, $status, $catatan_petugas);
                break;

            case 'Disposisi':
                if (empty(trim($_POST['tujuan_permohonan'] ?? '')) || empty(trim($_POST['komponen_tujuan'] ?? ''))) {
                    echo json_encode(['success' => false, 'message' => 'Tujuan permohonan dan komponen tujuan harus diisi']);
                    exit();
                }
                $updateData = [
                    'id_permohonan' => $id,
                    'status' => $status,
                    'tujuan_permohonan' => trim($_POST['tujuan_permohonan']),
                    'komponen_tujuan' => trim($_POST['komponen_tujuan']),
                    'catatan_petugas' => trim($_POST['catatan_petugas'] ?? '')
                ];
                $result = $this->permohonanAdminModel->updatePermohonanWithDisposisi($updateData);
                break;

            case 'Ditolak':
                if (empty(trim($_POST['alasan_penolakan'] ?? '')) || empty(trim($_POST['catatan_petugas'] ?? ''))) {
                    echo json_encode(['success' => false, 'message' => 'Alasan penolakan dan catatan petugas harus diisi']);
                    exit();
                }
                $updateData = [
                    'id_permohonan' => $id,
                    'status' => $status,
                    'alasan_penolakan' => trim($_POST['alasan_penolakan']),
                    'catatan_petugas' => trim($_POST['catatan_petugas'])
                ];
                $result = $this->permohonanAdminModel->updatePermohonanWithPenolakan($updateData);
                break;

            case 'Selesai':
                $catatan_petugas = trim($_POST['catatan_petugas'] ?? '');
                if (empty($catatan_petugas)) {
                    echo json_encode(['success' => false, 'message' => 'Catatan petugas harus diisi']);
                    exit();
                }
                $result = $this->permohonanAdminModel->updateStatusWithCatatan($id, $status, $catatan_petugas);
                break;

            default:
                $result = $this->permohonanAdminModel->updatePermohonanStatus($id, $status);
                break;
        }

        $message = $result ? 'Status berhasil diperbarui' : 'Gagal memperbarui status';
        echo json_encode(['success' => $result, 'message' => $message]);
        exit();
    }

    // ============ STATUS-SPECIFIC INDEX METHODS ============

    /**
     * Halaman daftar permohonan disposisi dengan pagination dan search
     */
    public function disposisiIndex()
    {
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';

        $permohonan_list = $this->permohonanAdminModel->getAllPermohonan($limit, $offset, 'Disposisi', $search);
        $total_records = $this->permohonanAdminModel->countAllPermohonan('Disposisi', $search);
        $total_pages = ceil($total_records / $limit);
        $stats = $this->permohonanAdminModel->getPetugasStats();

        $success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
        $error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
        unset($_SESSION['success_message']);
        unset($_SESSION['error_message']);

        include 'views/permohonan_admin/disposisi/index.php';
    }

    /**
     * Halaman daftar permohonan keberatan
     */
    public function keberatanIndex()
    {
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';

        $permohonan_list = $this->permohonanAdminModel->getAllPermohonan($limit, $offset, 'Keberatan', $search);
        $total_records = $this->permohonanAdminModel->countAllPermohonan('Keberatan', $search);
        $total_pages = ceil($total_records / $limit);
        $stats = $this->permohonanAdminModel->getPetugasStats();

        $success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
        $error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
        unset($_SESSION['success_message']);
        unset($_SESSION['error_message']);

        include 'views/permohonan_admin/keberatan/index.php';
    }

    /**
     * Detail permohonan keberatan
     */
    public function keberatanView()
    {
        if (!isset($_GET['id'])) {
            $_SESSION['error_message'] = 'ID permohonan tidak ditemukan';
            header('Location: index.php?controller=permohonanadmin&action=keberatanIndex');
            exit();
        }

        $id = intval($_GET['id']);
        $permohonan = $this->permohonanAdminModel->getPermohonanById($id);

        if (!$permohonan || $permohonan['status'] !== 'Keberatan') {
            $_SESSION['error_message'] = 'Permohonan keberatan tidak ditemukan';
            header('Location: index.php?controller=permohonanadmin&action=keberatanIndex');
            exit();
        }

        // Get keberatan data
        $query = "SELECT * FROM keberatan WHERE id_permohonan = :id_permohonan ORDER BY created_at DESC LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_permohonan', $id);
        $stmt->execute();
        $keberatan = $stmt->fetch(PDO::FETCH_ASSOC);

        $skpd_data = $this->getSKPDData($permohonan['komponen_tujuan']);

        $success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
        $error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
        unset($_SESSION['success_message']);
        unset($_SESSION['error_message']);

        include 'views/permohonan_admin/keberatan/view.php';
    }

    /**
     * Halaman daftar permohonan sengketa
     */
    public function sengketaIndex()
    {
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';

        $permohonan_list = $this->permohonanAdminModel->getAllPermohonan($limit, $offset, 'Sengketa', $search);
        $total_records = $this->permohonanAdminModel->countAllPermohonan('Sengketa', $search);
        $total_pages = ceil($total_records / $limit);
        $stats = $this->permohonanAdminModel->getPetugasStats();

        $success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
        $error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
        unset($_SESSION['success_message']);
        unset($_SESSION['error_message']);

        include 'views/permohonan_admin/sengketa/index.php';
    }

    /**
     * Detail permohonan sengketa
     */
    public function sengketaView()
    {
        if (!isset($_GET['id'])) {
            $_SESSION['error_message'] = 'ID permohonan tidak ditemukan';
            header('Location: index.php?controller=permohonanadmin&action=sengketaIndex');
            exit();
        }

        $id = intval($_GET['id']);
        $permohonan = $this->permohonanAdminModel->getPermohonanById($id);

        if (!$permohonan || $permohonan['status'] !== 'Sengketa') {
            $_SESSION['error_message'] = 'Permohonan sengketa tidak ditemukan';
            header('Location: index.php?controller=permohonanadmin&action=sengketaIndex');
            exit();
        }

        $skpd_data = $this->getSKPDData($permohonan['komponen_tujuan']);

        $success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
        $error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
        unset($_SESSION['success_message']);
        unset($_SESSION['error_message']);

        include 'views/permohonan_admin/sengketa/view.php';
    }

    /**
     * Halaman daftar permohonan diproses
     */
    public function diprosesIndex()
    {
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';

        $permohonan_list = $this->permohonanAdminModel->getAllPermohonan($limit, $offset, 'Diproses', $search);
        $total_records = $this->permohonanAdminModel->countAllPermohonan('Diproses', $search);
        $total_pages = ceil($total_records / $limit);
        $stats = $this->permohonanAdminModel->getPetugasStats();

        $success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
        $error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
        unset($_SESSION['success_message']);
        unset($_SESSION['error_message']);

        include 'views/permohonan_admin/proses/index.php';
    }

    /**
     * Halaman daftar permohonan selesai
     */
    public function selesaiIndex()
    {
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';

        $permohonan_list = $this->permohonanAdminModel->getAllPermohonan($limit, $offset, 'Selesai', $search);
        $total_records = $this->permohonanAdminModel->countAllPermohonan('Selesai', $search);
        $total_pages = ceil($total_records / $limit);
        $stats = $this->permohonanAdminModel->getPetugasStats();

        $success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
        $error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
        unset($_SESSION['success_message']);
        unset($_SESSION['error_message']);

        include 'views/permohonan_admin/selesai/index.php';
    }

    /**
     * Halaman daftar permohonan ditolak
     */
    public function ditolakIndex()
    {
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';

        $permohonan_list = $this->permohonanAdminModel->getAllPermohonan($limit, $offset, 'Ditolak', $search);
        $total_records = $this->permohonanAdminModel->countAllPermohonan('Ditolak', $search);
        $total_pages = ceil($total_records / $limit);
        $stats = $this->permohonanAdminModel->getPetugasStats();

        $success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
        $error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
        unset($_SESSION['success_message']);
        unset($_SESSION['error_message']);

        include 'views/permohonan_admin/ditolak/index.php';
    }

    // ============ OTHER METHODS ============

    /**
     * Halaman semua permohonan dengan filter status dan komponen, chart statistik
     */
    public function semuaIndex()
    {
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;
        $status_filter = isset($_GET['status']) ? $_GET['status'] : 'all';
        $komponen_filter = isset($_GET['komponen']) ? trim($_GET['komponen']) : '';
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';

        // Build query with filters
        $whereClause = "WHERE 1=1";
        $params = [];

        if ($status_filter !== 'all') {
            $whereClause .= " AND p.status = :status";
            $params[':status'] = $status_filter;
        }

        if (!empty($komponen_filter)) {
            $whereClause .= " AND p.komponen_tujuan = :komponen";
            $params[':komponen'] = $komponen_filter;
        }

        if (!empty($search)) {
            $whereClause .= " AND (p.no_permohonan LIKE :search
                             OR p.tujuan_permohonan LIKE :search
                             OR p.judul_dokumen LIKE :search
                             OR bp.nama_lengkap LIKE :search
                             OR bp.nik LIKE :search)";
            $params[':search'] = '%' . $search . '%';
        }

        // Get permohonan list
        $query = "SELECT p.*, u.username, u.email as user_email,
                         bp.nama_lengkap, bp.nik, bp.alamat, bp.no_kontak, bp.email
                  FROM permohonan p
                  JOIN users u ON p.id_user = u.id_user
                  LEFT JOIN biodata_pengguna bp ON u.id_biodata = bp.id_biodata
                  " . $whereClause . "
                  ORDER BY p.created_at DESC
                  LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $permohonan_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Count total
        $countQuery = "SELECT COUNT(*) as total
                       FROM permohonan p
                       JOIN users u ON p.id_user = u.id_user
                       LEFT JOIN biodata_pengguna bp ON u.id_biodata = bp.id_biodata
                       " . $whereClause;

        $countStmt = $this->conn->prepare($countQuery);
        foreach ($params as $key => $value) {
            $countStmt->bindValue($key, $value);
        }
        $countStmt->execute();
        $total_records = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
        $total_pages = ceil($total_records / $limit);

        // Get statistics
        $stats = $this->permohonanAdminModel->getPetugasStats();

        // Get all SKPD for filter
        $skpd_list = $this->skpdModel->getAllSKPD();

        // Chart statistics by status
        $chartQuery = "SELECT status, COUNT(*) as count FROM permohonan GROUP BY status";
        $chartStmt = $this->conn->prepare($chartQuery);
        $chartStmt->execute();
        $chart_data = $chartStmt->fetchAll(PDO::FETCH_ASSOC);

        $success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
        $error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
        unset($_SESSION['success_message']);
        unset($_SESSION['error_message']);

        include 'views/permohonan_admin/semua/index.php';
    }

    /**
     * Halaman daftar data pemohon dengan tabel
     */
    public function dataPemohonIndex()
    {
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';

        // Get pemohon data with permohonan count
        $whereClause = "WHERE u.role = 'user'";
        $params = [];

        if (!empty($search)) {
            $whereClause .= " AND (u.username LIKE :search
                             OR bp.nama_lengkap LIKE :search
                             OR bp.nik LIKE :search
                             OR bp.email LIKE :search
                             OR bp.no_kontak LIKE :search)";
            $params[':search'] = '%' . $search . '%';
        }

        $query = "SELECT u.id_user, u.username, u.email as user_email, u.created_at as register_date,
                         bp.nama_lengkap, bp.nik, bp.alamat, bp.no_kontak, bp.email,
                         bp.status_pengguna, bp.provinsi, bp.city,
                         (SELECT COUNT(*) FROM permohonan WHERE id_user = u.id_user) as total_permohonan
                  FROM users u
                  LEFT JOIN biodata_pengguna bp ON u.id_biodata = bp.id_biodata
                  " . $whereClause . "
                  ORDER BY u.created_at DESC
                  LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $pemohon_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Count total
        $countQuery = "SELECT COUNT(*) as total
                       FROM users u
                       LEFT JOIN biodata_pengguna bp ON u.id_biodata = bp.id_biodata
                       " . $whereClause;

        $countStmt = $this->conn->prepare($countQuery);
        foreach ($params as $key => $value) {
            $countStmt->bindValue($key, $value);
        }
        $countStmt->execute();
        $total_records = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
        $total_pages = ceil($total_records / $limit);

        $success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
        $error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
        unset($_SESSION['success_message']);
        unset($_SESSION['error_message']);

        include 'views/permohonan_admin/data_pemohon/index.php';
    }

    // ============ AJAX METHODS ============

    /**
     * Get komponen (SKPD) berdasarkan kategori tujuan permohonan
     * Dipanggil via AJAX dari form create
     */
    public function getKomponen()
    {
        header('Content-Type: application/json');

        if (!isset($_GET['tujuan_permohonan']) || empty(trim($_GET['tujuan_permohonan']))) {
            echo json_encode([
                'success' => false,
                'message' => 'Kategori tujuan permohonan tidak ditemukan',
                'data' => []
            ]);
            exit();
        }

        $kategori = trim($_GET['tujuan_permohonan']);

        try {
            // Get SKPD by category
            $skpd_list = $this->skpdModel->getSKPDByCategory($kategori);

            if (empty($skpd_list)) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Tidak ada SKPD untuk kategori ini',
                    'data' => []
                ]);
                exit();
            }

            // Map nama_skpd to nama_tujuan_permohonan for compatibility with frontend
            $data = array_map(function($skpd) {
                return [
                    'id_skpd' => $skpd['id_skpd'],
                    'nama_tujuan_permohonan' => $skpd['nama_skpd'],
                    'kategori' => $skpd['kategori']
                ];
            }, $skpd_list);

            echo json_encode([
                'success' => true,
                'message' => 'Data SKPD berhasil dimuat',
                'data' => $data
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'data' => []
            ]);
        }
        exit();
    }

    // ============ HELPER METHODS ============

    /**
     * Get SKPD data by name
     */
    private function getSKPDData($nama_skpd)
    {
        if (empty($nama_skpd)) {
            return null;
        }

        $query = "SELECT * FROM skpd WHERE nama_skpd = :nama_skpd LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nama_skpd', $nama_skpd);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Generate nomor permohonan unik
     */
    private function generateNoPermohonan()
    {
        $prefix = 'PRM';
        $date = date('Ymd');

        // Get last number for today
        $query = "SELECT no_permohonan FROM permohonan
                  WHERE no_permohonan LIKE :prefix
                  ORDER BY id_permohonan DESC LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $searchPrefix = $prefix . $date . '%';
        $stmt->bindParam(':prefix', $searchPrefix);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $lastNumber = intval(substr($result['no_permohonan'], -4));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . $date . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Handle file upload
     */
    private function handleFileUpload($file, $type)
    {
        $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
        $max_size = 5 * 1024 * 1024; // 5MB

        if (!in_array($file['type'], $allowed_types)) {
            $_SESSION['error_message'] = 'Tipe file tidak diizinkan. Hanya JPG, PNG, dan PDF yang diperbolehkan.';
            return '';
        }

        if ($file['size'] > $max_size) {
            $_SESSION['error_message'] = 'Ukuran file terlalu besar. Maksimal 5MB.';
            return '';
        }

        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '_' . $type . '_' . time() . '.' . $extension;
        $filepath = $upload_dir . $filename;

        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            return $filepath;
        }

        $_SESSION['error_message'] = 'Gagal mengupload file';
        return '';
    }
}
?>
