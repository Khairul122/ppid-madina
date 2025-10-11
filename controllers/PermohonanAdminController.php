<?php
require_once 'models/PermohonanAdminModel.php';
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
        $this->permohonanAdminModel = new PermohonanAdminModel($db);
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

    /**
     * Lihat detail permohonan disposisi dengan validasi session admin
     */
    public function disposisiView()
    {
        if (!isset($_GET['id'])) {
            $_SESSION['error_message'] = 'ID permohonan tidak ditemukan';
            header('Location: index.php?controller=permohonanadmin&action=disposisiIndex');
            exit();
        }

        $id = intval($_GET['id']);
        $permohonan = $this->permohonanAdminModel->getPermohonanForDisposisiView($id);

        if (!$permohonan) {
            $_SESSION['error_message'] = 'Permohonan disposisi tidak ditemukan';
            header('Location: index.php?controller=permohonanadmin&action=disposisiIndex');
            exit();
        }

        $skpd_data = $this->getSKPDData($permohonan['komponen_tujuan']);

        $success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
        $error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
        unset($_SESSION['success_message']);
        unset($_SESSION['error_message']);

        include 'views/permohonan_admin/disposisi/view.php';
    }

    /**
     * Lihat detail permohonan diproses dengan validasi session admin
     */
    public function diprosesDetail()
    {
        if (!isset($_GET['id'])) {
            $_SESSION['error_message'] = 'ID permohonan tidak ditemukan';
            header('Location: index.php?controller=permohonanadmin&action=diprosesIndex');
            exit();
        }

        $id = intval($_GET['id']);
        $permohonan = $this->permohonanAdminModel->getPermohonanForDiprosesView($id);

        if (!$permohonan) {
            $_SESSION['error_message'] = 'Permohonan diproses tidak ditemukan';
            header('Location: index.php?controller=permohonanadmin&action=diprosesIndex');
            exit();
        }

        $skpd_data = $this->getSKPDData($permohonan['komponen_tujuan']);

        $success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
        $error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
        unset($_SESSION['success_message']);
        unset($_SESSION['error_message']);

        include 'views/permohonan_admin/proses/detail.php';
    }

    /**
     * Lihat detail permohonan selesai dengan validasi session admin
     */
    public function selesaiDetail()
    {
        if (!isset($_GET['id'])) {
            $_SESSION['error_message'] = 'ID permohonan tidak ditemukan';
            header('Location: index.php?controller=permohonanadmin&action=selesaiIndex');
            exit();
        }

        $id = intval($_GET['id']);
        $permohonan = $this->permohonanAdminModel->getPermohonanForSelesaiView($id);

        if (!$permohonan) {
            $_SESSION['error_message'] = 'Permohonan selesai tidak ditemukan';
            header('Location: index.php?controller=permohonanadmin&action=selesaiIndex');
            exit();
        }

        $skpd_data = $this->getSKPDData($permohonan['komponen_tujuan']);

        $success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
        $error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
        unset($_SESSION['success_message']);
        unset($_SESSION['error_message']);

        include 'views/permohonan_admin/selesai/detail.php';
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

            $userQuery = "INSERT INTO users (username, email, password, role, id_biodata)
                         VALUES (:username, :email, :password, 'masyarakat', :id_biodata)";

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

            // Insert permohonan using model method with 7 hari kerja calculation
            $permohonan_data = [
                'id_user' => $id_user,
                'no_permohonan' => $no_permohonan,
                'judul_dokumen' => $judul_dokumen,
                'tujuan_penggunaan_informasi' => $tujuan_penggunaan_informasi,
                'tujuan_permohonan' => $tujuan_permohonan,
                'komponen_tujuan' => $komponen_tujuan,
                'kandungan_informasi' => $kandungan_informasi,
                'sumber_media' => $sumber_media,
                'upload_foto_identitas' => $upload_foto_identitas,
                'upload_data_pedukung' => $upload_data_pedukung,
                'status' => 'Masuk'
            ];

            $result = $this->permohonanAdminModel->createPermohonan($permohonan_data);

            if ($result['success']) {
                // Clear old input on success
                unset($_SESSION['old_input']);
                $_SESSION['success_message'] = 'Permohonan berhasil ditambahkan dengan sisa jatuh tempo ' . $result['sisa_jatuh_tempo'] . ' hari';
                header('Location: index.php?controller=permohonanadmin&action=index');
            } else {
                $_SESSION['error_message'] = $result['message'];
                header('Location: index.php?controller=permohonanadmin&action=create');
            }
            $this->conn->commit();
        } catch (Exception $e) {
            $this->conn->rollBack();
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
                $tujuan_permohonan = trim($_POST['tujuan_permohonan'] ?? '');
                $komponen_tujuan = trim($_POST['komponen_tujuan'] ?? '');
                
                if (empty($komponen_tujuan)) {
                    echo json_encode(['success' => false, 'message' => 'Komponen tujuan harus diisi']);
                    exit();
                }
                
                // Jika tujuan_permohonan tidak diisi, gunakan komponen_tujuan sebagai tujuan_permohonan
                if (empty($tujuan_permohonan)) {
                    $tujuan_permohonan = $komponen_tujuan;
                }
                
                $updateData = [
                    'id_permohonan' => $id,
                    'status' => $status,
                    'tujuan_permohonan' => $tujuan_permohonan,
                    'komponen_tujuan' => $komponen_tujuan,
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

            default:
                $result = $this->permohonanAdminModel->updatePermohonanStatus($id, $status);
                break;
        }

        $message = $result ? 'Status berhasil diperbarui' : 'Gagal memperbarui status';
        echo json_encode(['success' => $result, 'message' => $message]);
        exit();
    }

    /**
     * Update status permohonan dengan catatan petugas (untuk status selain disposisi, ditolak)
     */
    public function updateStatusWithCatatan()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Metode tidak diizinkan']);
            exit();
        }

        $id = isset($_POST['id']) ? intval($_POST['id']) : (isset($_POST['id_permohonan']) ? intval($_POST['id_permohonan']) : 0);
        $status = trim($_POST['status'] ?? '');
        $catatan_petugas = trim($_POST['catatan_petugas'] ?? '');

        if (!$id || empty($status)) {
            echo json_encode(['success' => false, 'message' => 'Data tidak lengkap']);
            exit();
        }

        if (empty($catatan_petugas)) {
            echo json_encode(['success' => false, 'message' => 'Catatan petugas harus diisi']);
            exit();
        }

        // Update status with catatan
        $result = $this->permohonanAdminModel->updateStatusWithCatatan($id, $status, $catatan_petugas);

        $message = $result ? 'Status dan catatan berhasil diperbarui' : 'Gagal memperbarui status dan catatan';
        echo json_encode(['success' => $result, 'message' => $message]);
        exit();
    }

    /**
     * Perpanjang jatuh tempo permohonan sebanyak 7 hari kerja
     */
    public function perpanjangJatuhTempo()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Metode tidak diizinkan']);
            exit();
        }

        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'ID permohonan tidak valid']);
            exit();
        }

        // Ambil permohonan untuk mendapatkan nilai sisa_jatuh_tempo saat ini
        $permohonan = $this->permohonanAdminModel->getPermohonanById($id);

        if (!$permohonan) {
            echo json_encode(['success' => false, 'message' => 'Permohonan tidak ditemukan']);
            exit();
        }

        // Hitung 7 hari kerja tambahan
        $sisa_jatuh_tempo_baru = $permohonan['sisa_jatuh_tempo'] + $this->permohonanAdminModel->hitung7HariKerja();

        // Update sisa_jatuh_tempo
        $result = $this->permohonanAdminModel->updateSisaJatuhTempo($id, $sisa_jatuh_tempo_baru);

        echo json_encode($result);
        exit();
    }

    /**
     * Update catatan petugas tanpa mengganti status
     */
    public function updateCatatanPetugas()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Metode tidak diizinkan']);
            exit();
        }

        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $catatan_petugas = trim($_POST['catatan_petugas'] ?? '');

        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'ID permohonan tidak valid']);
            exit();
        }

        if (empty($catatan_petugas)) {
            echo json_encode(['success' => false, 'message' => 'Catatan petugas harus diisi']);
            exit();
        }

        try {
            $query = "UPDATE {$this->permohonanAdminModel->table_permohonan}
                      SET catatan_petugas = :catatan_petugas, updated_at = NOW()
                      WHERE id_permohonan = :id";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':catatan_petugas', $catatan_petugas);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Catatan petugas berhasil diperbarui'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Gagal memperbarui catatan petugas'
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
        exit();
    }

    /**
     * Generate PDF for permohonan (bukti permohonan)
     */
    public function generatePDF()
    {
        $id = $this->getRequiredId('index');
        $permohonan = $this->getPermohonanOrFail($id, 'index');
        $skpd_data = $this->getSKPDData($permohonan['komponen_tujuan']);

        $this->generateTCPDF($permohonan, $skpd_data);
    }

    /**
     * Generate Bukti Proses PDF
     */
    public function generateBuktiProsesPDF()
    {
        $id = $this->getRequiredId('diprosesIndex');
        $permohonan = $this->getPermohonanOrFail($id, 'diprosesIndex');
        $skpd_data = $this->getSKPDData($permohonan['komponen_tujuan']);

        $this->generateBuktiProsesTCPDF($permohonan, $skpd_data);
    }

    /**
     * Generate Bukti Selesai PDF
     */
    public function generateBuktiSelesaiPDF()
    {
        $id = $this->getRequiredId('selesaiIndex');
        $permohonan = $this->getPermohonanOrFail($id, 'selesaiIndex');
        $skpd_data = $this->getSKPDData($permohonan['komponen_tujuan']);

        $this->generateBuktiSelesaiTCPDF($permohonan, $skpd_data);
    }

    /**
     * Generate Bukti Ditolak PDF
     */
    public function generateBuktiDitolakPDF()
    {
        $id = $this->getRequiredId('ditolakIndex');
        $permohonan = $this->getPermohonanOrFail($id, 'ditolakIndex');
        $skpd_data = $this->getSKPDData($permohonan['komponen_tujuan']);

        $this->generateBuktiDitolakTCPDF($permohonan, $skpd_data);
    }

    /**
     * Generate Bukti Disposisi PDF
     */
    public function generateBuktiDisposisiPDF()
    {
        $id = $this->getRequiredId('disposisiIndex');
        $permohonan = $this->getPermohonanOrFail($id, 'disposisiIndex');
        $skpd_data = $this->getSKPDData($permohonan['komponen_tujuan']);

        $this->generateBuktiDisposisiTCPDF($permohonan, $skpd_data);
    }

    /**
     * Get required ID parameter from GET request
     */
    private function getRequiredId($redirect_action)
    {
        if (!isset($_GET['id']) || empty($_GET['id'])) {
            $_SESSION['error_message'] = 'ID permohonan tidak ditemukan';
            header("Location: index.php?controller=permohonanadmin&action=$redirect_action");
            exit();
        }
        return intval($_GET['id']);
    }

    /**
     * Get permohonan by ID or fail with redirect
     */
    private function getPermohonanOrFail($id, $redirect_action)
    {
        $permohonan = $this->permohonanAdminModel->getPermohonanById($id);

        if (!$permohonan) {
            $_SESSION['error_message'] = 'Data permohonan tidak ditemukan';
            header("Location: index.php?controller=permohonanadmin&action=$redirect_action");
            exit();
        }

        return $permohonan;
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
        $query = "SELECT * FROM keberatan WHERE id_permohonan = :id_permohonan ORDER BY id_keberatan DESC LIMIT 1";
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

        $pemohon_list = $this->permohonanAdminModel->getAllPemohon($limit, $offset, $search);
        $total_records = $this->permohonanAdminModel->countAllPemohon($search);
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


    public function getAllSKPDList()
    {
        header('Content-Type: application/json');

        try {
            // Get all SKPD
            $skpd_list = $this->skpdModel->getAllSKPD();

            if (empty($skpd_list)) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Tidak ada SKPD tersedia',
                    'data' => [
                        'skpd_list' => []
                    ]
                ]);
                exit();
            }

            echo json_encode([
                'success' => true,
                'message' => 'Data SKPD berhasil dimuat',
                'data' => [
                    'skpd_list' => $skpd_list
                ]
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'data' => [
                    'skpd_list' => []
                ]
            ]);
        }
        exit();
    }


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
     * Get SKPD data by name
     */


    // ============ PDF GENERATION METHODS ============

    private function generateTCPDF($data, $skpd_data = null)
    {
        // Include TCPDF library
        require_once 'vendor/tecnickcom/tcpdf/tcpdf.php';
        
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetCreator('PPID Mandailing Natal');
        $pdf->SetAuthor('PPID Mandailing Natal');
        $pdf->SetTitle('Bukti Permohonan Informasi');
        $pdf->SetSubject('Bukti Permohonan Informasi');

        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetAutoPageBreak(true, 15);

        $pdf->AddPage();
        $pdf->SetFont('times', '', 12);
        $pdf->SetCellHeightRatio(1.15);

        $this->addPDFHeader($pdf, $data, $skpd_data);
        $this->addPDFTitle($pdf, $data);
        $this->addPDFDataSection($pdf, $data);
        $this->addPDFSignature($pdf, $data);
        $this->addPDFFooter($pdf);

        $filename = 'Bukti_Permohonan_' . ($data['no_permohonan'] ?? $data['id_permohonan']) . '.pdf';
        $pdf->Output($filename, 'I');
    }

    private function addPDFHeader($pdf, $data, $skpd_data)
    {
        $pdf->SetFont('times', 'B', 16);
        $pdf->SetTextColor(0, 0, 0);

        $logo_path = __DIR__ . '/../ppid_assets/logo-resmi.png';
        $logo_x = 15;
        $logo_y = 15;
        $text_x = $logo_x + 30;

        if (file_exists($logo_path)) {
            $pdf->Image($logo_path, $logo_x, $logo_y, 25, 25, 'PNG', '', 'T', false, 300);
            $pdf->SetXY($text_x, $logo_y);
        } else {
            $pdf->SetXY($logo_x, $logo_y);
        }

        // Nama SKPD dengan ukuran 16 bold
        $skpd_name = $data['komponen_tujuan'] ?? '';
        $pdf->Cell(0, 7, $skpd_name, 0, 1, 'L');

        $pdf->SetFont('times', '', 12);

        // Alamat dari tabel SKPD
        $alamat = ($skpd_data && !empty($skpd_data['alamat'])) ? $skpd_data['alamat'] : '';
        if (!empty($alamat)) {
            $pdf->SetX($text_x);
            $pdf->Cell(0, 5, $alamat, 0, 1, 'L');
        }

        // Email dari tabel SKPD
        $email = ($skpd_data && !empty($skpd_data['email'])) ? 'Email : ' . $skpd_data['email'] : '';
        if (!empty($email)) {
            $pdf->SetX($text_x);
            $pdf->Cell(0, 5, $email, 0, 1, 'L');
        }

        // Telp dari tabel SKPD
        $telp = ($skpd_data && !empty($skpd_data['telepon'])) ? 'Telp : ' . $skpd_data['telepon'] : '';
        if (!empty($telp)) {
            $pdf->SetX($text_x);
            $pdf->Cell(0, 5, $telp, 0, 1, 'L');
        }

        $pdf->Ln(12);
        $pdf->SetLineWidth(0.1);
        $pdf->Line(11, $pdf->GetY(), $pdf->getPageWidth() - 11, $pdf->GetY());
    }

    private function addPDFTitle($pdf, $data)
    {
        $pdf->Ln(3);
        $pdf->SetFont('times', 'B', 12);
        $pdf->Cell(0, 5, 'BUKTI PERMOHONAN INFORMASI', 0, 0, 'C');
        $pdf->SetY($pdf->GetY() + 5);
        $pdf->SetFont('times', '', 12);
        $pdf->Cell(0, 5, 'Nomor Permohonan : ' . ($data['no_permohonan'] ?? $data['id_permohonan']), 0, 1, 'C');
    }

    private function addPDFDataSection($pdf, $data)
    {
        $pdf->Ln(5);
        $pdf->SetFont('times', '', 12);

        $items = [
            ['Nama Pemohon', $data['nama_lengkap'] ?? $data['username']],
            ['Alamat', $data['alamat'] ?? ''],
            ['Telepon', $data['no_kontak'] ?? ''],
            ['Email', $data['email'] ?? ''],
            ['Informasi Dimohon', $data['judul_dokumen'] ?? ''],
            ['Provinsi Tujuan', $data['provinsi'] ?? ''],
            ['Kab/Kota Tujuan', $data['city'] ?? ''],
            ['OPD Tujuan', $data['komponen_tujuan'] ?? '']
        ];

        foreach ($items as $item) {
            $pdf->Cell(50, 6, $item[0], 0, 0, 'L');
            $pdf->Cell(5, 6, ':', 0, 0, 'L');
            $pdf->Cell(0, 6, $item[1], 0, 1, 'L');
        }

        $pdf->Cell(50, 6, 'Kandungan Informasi', 0, 0, 'L');
        $pdf->Cell(5, 6, ':', 0, 0, 'L');
        $pdf->SetFillColor(211, 211, 211);
        $pdf->Cell(0, 6, $data['kandungan_informasi'] ?? $data['tujuan_permohonan'] ?? '', 1, 1, 'L', true);
        $pdf->Ln(1);

        $pdf->Cell(50, 6, 'Tujuan Penggunaan', 0, 0, 'L');
        $pdf->Cell(5, 6, ':', 0, 0, 'L');
        $pdf->Cell(0, 6, $data['tujuan_penggunaan_informasi'] ?? '', 1, 1, 'L', true);
        $pdf->SetFillColor(255, 255, 255);

        $pdf->Cell(50, 6, 'Cara Memperoleh Informasi', 0, 0, 'L');
        $pdf->Cell(5, 6, ':', 0, 0, 'L');
        $pdf->SetFont('dejavusans', '', 12);
        $pdf->Cell(10, 6, '☐', 0, 0, 'L');
        $pdf->SetFont('times', '', 12);
        $pdf->Cell(0, 6, 'Melihat/Membaca/Mendengarkan/Mencatat', 0, 1, 'L');

        $pdf->Cell(55, 6, '', 0, 0, 'L');
        $pdf->SetFont('dejavusans', '', 12);
        $pdf->Cell(10, 6, '☑', 0, 0, 'L');
        $pdf->SetFont('times', '', 12);
        $pdf->Cell(0, 6, 'Mendapatkan Salinan Informasi (Hard Copy / Soft Copy)', 0, 1, 'L');
        $pdf->Ln(10);
    }

    private function addPDFSignature($pdf, $data)
    {
        $y = $pdf->GetY();

        $pdf->SetXY(20, $y);
        $pdf->Cell(80, 6, 'Petugas Pelayanan Informasi', 0, 1, 'C');
        $pdf->SetY($y + 26);
        $pdf->SetX(20);
        $pdf->Cell(80, 6, 'Pemerintah Kabupaten Mandailing Natal', 0, 1, 'C');

        $pdf->SetXY(120, $y);
        $pdf->Cell(80, 6, 'Pemohon', 0, 1, 'C');
        $pdf->SetY($y + 26);
        $pdf->SetX(120);
        $pdf->Cell(80, 6, strtoupper($data['nama_lengkap'] ?? $data['username']), 0, 1, 'C');

        $pdf->SetY($y + 42);
        $pdf->SetLineWidth(0.1);
        $pdf->Line(10, $pdf->GetY(), $pdf->getPageWidth() - 10, $pdf->GetY());
        $pdf->Ln(10);
    }

    private function addPDFFooter($pdf)
    {
        $pdf->SetFont('times', '', 10);
        $pdf->Cell(0, 5, 'Berdasarkan Undang-Undang No 14 Tahun 2008 Tentang Keterbukaan Informasi Publik, maka :', 0, 1, 'L');

        $notes = [
            'Bukti Permohonan Ini merupakan hak pemohon yang wajib diterbitkan oleh Badan Publik. (Pasal 22 Ayat 3 dan 4)',
            'Pemohon dapat menerima pemberitahuan atas permohonannya dalam waktu 10 (sepuluh) hari. (Pasal 22 Ayat 7)',
            'Bukti Permohonan ini merupakan bukti sah atas permohonan informasi yang diajukan ke daerah tujuan.',
            'Badan Publik dapat memperpanjang waktu pemberitahuan / jawaban permohonan hingga 7 (tujuh) hari. (Pasal 22 Ayat 8)',
            'Informasi Publik yang dapat diberikan diatur dalam Pasal 9 s.d 16',
            'Dalam hal terjadi sengketa, Pemohon dapat mengajukan gugatan ke pengadilan apabila dalam mendapatkan Informasi Publik mendapatkan hambatan / kegagalan. (Pasal 4 Ayat 4)'
        ];

        foreach ($notes as $note) {
            $pdf->Cell(5, 4, '', 0, 0);
            $pdf->Cell(5, 4, '•', 0, 0, 'L');
            $pdf->MultiCell(0, 4, $note, 0, 'L');
        }

        $pdf->Ln(5);
        $pdf->SetFont('times', 'I', 10);
        $pdf->Cell(0, 4, 'Lembaran ini diterbitkan oleh PPID Kemendagri dan dicetak pada ' . $this->getIndonesianDate(), 0, 1, 'R');
    }

    private function getIndonesianDate()
    {
        $hari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        $bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        return $hari[date('N') - 1] . ', ' . date('d') . ' ' . $bulan[date('n') - 1] . ' ' . date('Y');
    }

    // ============ BUKTI PROSES PDF GENERATION ============

    private function generateBuktiProsesTCPDF($data, $skpd_data = null)
    {
        // Include TCPDF library
        require_once 'vendor/tecnickcom/tcpdf/tcpdf.php';
        
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // Document settings
        $pdf->SetCreator('PPID Mandailing Natal');
        $pdf->SetAuthor('PPID Mandailing Natal');
        $pdf->SetTitle('Bukti Proses Permohonan Informasi');
        $pdf->SetSubject('Bukti Proses Permohonan Informasi');

        // Remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // Set margins
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetAutoPageBreak(true, 15);

        $pdf->AddPage();
        // Using Times New Roman font with size 12 and 1.15 line spacing
        $pdf->SetFont('times', '', 12);
        $pdf->SetCellHeightRatio(1.15);

        // Header with logo and SKPD information
        $this->addBuktiProsesHeader($pdf, $data, $skpd_data);

        // Title section
        $this->addBuktiProsesTitle($pdf, $data);

        // Data section
        $this->addBuktiProsesDataSection($pdf, $data);

        // Signature section
        $this->addBuktiProsesSignature($pdf, $data);

        // Footer notes
        $this->addBuktiProsesFooter($pdf);

        // Output PDF
        $filename = 'Bukti_Proses_' . ($data['no_permohonan'] ?? $data['id_permohonan']) . '.pdf';
        $pdf->Output($filename, 'I');
        exit();
    }

    private function addBuktiProsesHeader($pdf, $data, $skpd_data = null)
    {
        $pdf->SetFont('times', 'B', 16);
        $pdf->SetTextColor(0, 0, 0);

        $logo_path = __DIR__ . '/../ppid_assets/logo-resmi.png';
        $start_x = 15;
        $start_y = 15;
        $logo_width = 25;
        $logo_height = 25;
        $text_start_x = $start_x + $logo_width + 5;
        $text_y = $start_y;

        if (file_exists($logo_path)) {
            $pdf->Image($logo_path, $start_x, $start_y, $logo_width, $logo_height, 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
            $pdf->SetXY($text_start_x, $text_y);
        } else {
            $pdf->SetXY($start_x, $start_y);
        }

        // Nama SKPD dengan ukuran 16 bold
        $skpd_name = !empty($data['komponen_tujuan']) ? $data['komponen_tujuan'] : '';
        $pdf->Cell(0, 7, $skpd_name, 0, 1, 'L');

        $pdf->SetFont('times', '', 12);

        // Alamat dari tabel SKPD
        $alamat = ($skpd_data && !empty($skpd_data['alamat'])) ? $skpd_data['alamat'] : '';
        if (!empty($alamat)) {
            $pdf->SetX($text_start_x);
            $pdf->Cell(0, 5, $alamat, 0, 1, 'L');
        }

        // Email dari tabel SKPD
        $email = ($skpd_data && !empty($skpd_data['email'])) ? 'Email : ' . $skpd_data['email'] : '';
        if (!empty($email)) {
            $pdf->SetX($text_start_x);
            $pdf->Cell(0, 5, $email, 0, 1, 'L');
        }

        // Telp dari tabel SKPD
        $telp = ($skpd_data && !empty($skpd_data['telepon'])) ? 'Telp : ' . $skpd_data['telepon'] : '';
        if (!empty($telp)) {
            $pdf->SetX($text_start_x);
            $pdf->Cell(0, 5, $telp, 0, 1, 'L');
        }

        $pdf->Ln(12);

        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetLineWidth(0.1);
        $pdf->Line(11, $pdf->GetY(), $pdf->getPageWidth() - 11, $pdf->GetY());
        $pdf->SetLineWidth(0.1);
    }

    private function addBuktiProsesTitle($pdf, $data)
    {
        $pdf->Ln(3);
        $default_height = 5;
        $line_spacing_factor = 1.0;
        $next_line_y_jump = $default_height * $line_spacing_factor;

        $pdf->SetFont('times', 'B', 12);

        $pdf->Cell(0, $default_height, 'BUKTI PERMOHONAN INFORMASI', 0, 0, 'C');

        $pdf->SetY($pdf->GetY() + $next_line_y_jump);

        $pdf->SetFont('times', '', 12);

        $pdf->Cell(0, $default_height, 'Nomor Permohonan : ' . ($data['no_permohonan'] ?? $data['id_permohonan']), 0, 1, 'C');
    }

    private function addBuktiProsesDataSection($pdf, $data)
    {
        $pdf->Ln(5);
        $pdf->SetFont('times', '', 12);

        $items = [
            ['Nama Pemohon', $data['nama_lengkap'] ?? $data['username']],
            ['Alamat', $data['alamat'] ?? ''],
            ['Telepon', $data['no_kontak'] ?? ''],
            ['Email', $data['email'] ?? ''],
            ['Informasi Dimohon', $data['judul_dokumen'] ?? ''],
            ['Provinsi Tujuan', $data['provinsi'] ?? 'Kabupaten Mandailing Natal'],
            ['Kab/Kota Tujuan', $data['city'] ?? 'Panyabungan'],
            ['OPD Tujuan', $data['komponen_tujuan'] ?? 'DINAS KOMUNIKASI DAN INFORMATIKA']
        ];

        foreach ($items as $item) {
            $pdf->Cell(50, 6, $item[0], 0, 0, 'L');
            $pdf->Cell(5, 6, ':', 0, 0, 'L');
            $pdf->Cell(0, 6, $item[1], 0, 1, 'L');
        }

        // Kandungan Informasi dengan background
        $kandungan_info = $data['kandungan_informasi'] ?? $data['tujuan_permohonan'] ?? 'permintaan informasi perbup tentang spbe';
        $pdf->Cell(50, 6, 'Kandungan Informasi', 0, 0, 'L');
        $pdf->Cell(5, 6, ':', 0, 0, 'L');
        $pdf->SetFillColor(211, 211, 211);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(0, 6, $kandungan_info, 1, 1, 'L', true);

        $pdf->Ln(1);

        // Tujuan Penggunaan dengan background
        $tujuan_penggunaan = $data['tujuan_penggunaan_informasi'] ?? 'permintaan informasi perbup tentang spbe';
        $pdf->Cell(50, 6, 'Tujuan Penggunaan', 0, 0, 'L');
        $pdf->Cell(5, 6, ':', 0, 0, 'L');
        $pdf->SetFillColor(211, 211, 211);
        $pdf->Cell(0, 6, $tujuan_penggunaan, 1, 1, 'L', true);

        $pdf->SetFillColor(255, 255, 255);

        $pdf->Ln(2);

        // Keputusan PPID section
        $pdf->SetFont('times', '', 12);
        $pdf->Cell(0, 6, 'Keputusan PPID', 0, 1, 'C');

        $pdf->SetFont('times', 'B', 12);
        $pdf->Cell(0, 6, 'PERMOHONAN TERPENUHI', 0, 1, 'C');

        $pdf->SetFont('times', '', 12);
        $pdf->Ln(2);

        // Cara Memperoleh Informasi
        $lebar_indentasi = 55;
        $pdf->Cell(50, 6, 'Cara Memperoleh Informasi', 0, 0, 'L');
        $pdf->Cell(5, 6, ':', 0, 0, 'L');

        $pdf->SetFont('dejavusans', '', 12);
        $pdf->Cell(10, 6, '☐', 0, 0, 'L');

        $pdf->SetFont('times', '', 12);
        $pdf->Cell(0, 6, 'Melihat/Membaca/Mendengarkan/Mencatat', 0, 1, 'L');

        $pdf->Cell($lebar_indentasi, 6, '', 0, 0, 'L');

        $pdf->SetFont('dejavusans', '', 12);
        $pdf->Cell(10, 6, '☑', 0, 0, 'L');

        $pdf->SetFont('times', '', 12);
        $pdf->Cell(0, 6, 'Mendapatkan Salinan Informasi (Hard Copy / Soft Copy)', 0, 1, 'L');

        $pdf->Ln(2);

        // Catatan Petugas dengan background seperti kandungan informasi
        $catatan_petugas = !empty($data['catatan_petugas']) ? $data['catatan_petugas'] : '-';
        $pdf->SetFont('times', '', 12);
        $pdf->Cell(50, 6, 'Catatan Petugas', 0, 0, 'L');
        $pdf->Cell(5, 6, ':', 0, 0, 'L');
        $pdf->SetFillColor(211, 211, 211);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(0, 6, $catatan_petugas, 1, 1, 'L', true);

        $pdf->SetFillColor(255, 255, 255);
        $pdf->Ln(2);

        $pdf->Ln(10);
    }

    private function addBuktiProsesSignature($pdf, $data)
    {
        $y = $pdf->GetY();
        $vertical_space = 20;
        $line_gap = 10;

        // Petugas Pelayanan Informasi
        $pdf->SetXY(20, $y);
        $pdf->Cell(80, 6, 'Petugas Pelayanan Informasi', 0, 1, 'C');
        $pdf->SetY($y + 6 + $vertical_space);
        $pdf->SetX(20);
        $pdf->Cell(80, 6, strtoupper($data['komponen_tujuan'] ?? 'DINAS KOMUNIKASI DAN INFORMATIKA'), 0, 1, 'C');

        // Pemohon
        $pdf->SetXY(120, $y);
        $pdf->Cell(80, 6, 'Pemohon', 0, 1, 'C');
        $pdf->SetY($y + 6 + $vertical_space);
        $pdf->SetX(120);
        $pdf->Cell(80, 6, strtoupper($data['nama_lengkap'] ?? $data['username']), 0, 1, 'C');

        $pdf->SetY($y + 6 + $vertical_space + 6 + $line_gap);

        $pdf->SetLineWidth(0.1);

        $page_width = $pdf->GetPageWidth();
        $line_y = $pdf->GetY();
        $pdf->Line(10, $line_y, $page_width - 10, $line_y);

        $pdf->SetLineWidth(0.2);
        $pdf->Ln(10);
    }

    private function addBuktiProsesFooter($pdf)
    {
        $pdf->SetFont('times', '', 10);
        $pdf->Cell(0, 5, 'Berdasarkan Undang-Undang No 14 Tahun 2008 Tentang Keterbukaan Informasi Publik, maka :', 0, 1, 'L');

        $notes = [
            'Bukti Permohonan Ini merupakan hak pemohon yang wajib diterbitkan oleh Badan Publik. (Pasal 22 Ayat 3 dan 4)',
            'Pemohon dapat menerima pemberitahuan atas permohonannya dalam waktu 10 (sepuluh) hari. (Pasal 22 Ayat 7)',
            'Bukti Permohonan ini merupakan bukti sah atas permohonan informasi yang diajukan ke daerah tujuan.',
            'Badan Publik dapat memperpanjang waktu pemberitahuan / jawaban permohonan hingga 7 (tujuh) hari. (Pasal 22 Ayat 8)',
            'Informasi Publik yang dapat diberikan diatur dalam Pasal 9 s.d 16',
            'Dalam hal terjadi sengketa, Pemohon dapat mengajukan gugatan ke pengadilan apabila dalam mendapatkan Informasi Publik mendapatkan hambatan / kegagalan. (Pasal 4 Ayat 4)'
        ];

        foreach ($notes as $note) {
            $pdf->Cell(5, 4, '', 0, 0);
            $pdf->Cell(5, 4, '•', 0, 0, 'L');
            $pdf->MultiCell(0, 4, $note, 0, 'L');
        }

        $pdf->Ln(5);

        $hari = array(1 => 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu');
        $bulan = array(1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');

        $tgl = date('d');
        $bln = date('n');
        $thn = date('Y');
        $hr = date('N');

        $tanggal_indonesia = $hari[$hr] . ', ' . $tgl . ' ' . $bulan[$bln] . ' ' . $thn;

        $pdf->SetFont('times', 'I', 10);
        $pdf->Cell(0, 4, 'Lembaran ini diterbitkan oleh PPID Kemendagri dan dicetak pada ' . $tanggal_indonesia, 0, 1, 'R');
    }

    // ============ BUKTI SELESAI PDF GENERATION ============

    private function generateBuktiSelesaiTCPDF($data, $skpd_data = null)
    {
        // Include TCPDF library
        require_once 'vendor/tecnickcom/tcpdf/tcpdf.php';
        
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // Document settings
        $pdf->SetCreator('PPID Mandailing Natal');
        $pdf->SetAuthor('PPID Mandailing Natal');
        $pdf->SetTitle('Bukti Selesai Permohonan Informasi');
        $pdf->SetSubject('Bukti Selesai Permohonan Informasi');

        // Remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // Set margins
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetAutoPageBreak(true, 15);

        $pdf->AddPage();
        $pdf->SetFont('times', '', 12);
        $pdf->SetCellHeightRatio(1.15);

        // Use same header as bukti proses
        $this->addBuktiProsesHeader($pdf, $data, $skpd_data);

        // Title section
        $this->addBuktiProsesTitle($pdf, $data);

        // Data section with PERMOHONAN SELESAI
        $this->addBuktiSelesaiDataSection($pdf, $data);

        // Signature section
        $this->addBuktiProsesSignature($pdf, $data);

        // Footer notes
        $this->addBuktiProsesFooter($pdf);

        // Output PDF
        $filename = 'Bukti_Selesai_' . ($data['no_permohonan'] ?? $data['id_permohonan']) . '.pdf';
        $pdf->Output($filename, 'I');
        exit();
    }

    private function addBuktiSelesaiDataSection($pdf, $data)
    {
        $pdf->Ln(5);
        $pdf->SetFont('times', '', 12);

        $items = [
            ['Nama Pemohon', $data['nama_lengkap'] ?? $data['username']],
            ['Alamat', $data['alamat'] ?? ''],
            ['Telepon', $data['no_kontak'] ?? ''],
            ['Email', $data['email'] ?? ''],
            ['Informasi Dimohon', $data['judul_dokumen'] ?? ''],
            ['Provinsi Tujuan', $data['provinsi'] ?? 'Kabupaten Mandailing Natal'],
            ['Kab/Kota Tujuan', $data['city'] ?? 'Panyabungan'],
            ['OPD Tujuan', $data['komponen_tujuan'] ?? 'DINAS KOMUNIKASI DAN INFORMATIKA']
        ];

        foreach ($items as $item) {
            $pdf->Cell(50, 6, $item[0], 0, 0, 'L');
            $pdf->Cell(5, 6, ':', 0, 0, 'L');
            $pdf->Cell(0, 6, $item[1], 0, 1, 'L');
        }

        // Kandungan Informasi dengan background
        $kandungan_info = $data['kandungan_informasi'] ?? $data['tujuan_permohonan'] ?? 'permintaan informasi perbup tentang spbe';
        $pdf->Cell(50, 6, 'Kandungan Informasi', 0, 0, 'L');
        $pdf->Cell(5, 6, ':', 0, 0, 'L');
        $pdf->SetFillColor(211, 211, 211);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(0, 6, $kandungan_info, 1, 1, 'L', true);

        $pdf->Ln(1);

        // Tujuan Penggunaan dengan background
        $tujuan_penggunaan = $data['tujuan_penggunaan_informasi'] ?? 'permintaan informasi perbup tentang spbe';
        $pdf->Cell(50, 6, 'Tujuan Penggunaan', 0, 0, 'L');
        $pdf->Cell(5, 6, ':', 0, 0, 'L');
        $pdf->SetFillColor(211, 211, 211);
        $pdf->Cell(0, 6, $tujuan_penggunaan, 1, 1, 'L', true);

        $pdf->SetFillColor(255, 255, 255);

        $pdf->Ln(2);

        // Keputusan PPID section - PERMOHONAN SELESAI
        $pdf->SetFont('times', '', 12);
        $pdf->Cell(0, 6, 'Keputusan PPID', 0, 1, 'C');

        $pdf->SetFont('times', 'B', 12);
        $pdf->Cell(0, 6, 'PERMOHONAN SELESAI', 0, 1, 'C');

        $pdf->SetFont('times', '', 12);
        $pdf->Ln(2);

        // Cara Memperoleh Informasi
        $lebar_indentasi = 55;
        $pdf->Cell(50, 6, 'Cara Memperoleh Informasi', 0, 0, 'L');
        $pdf->Cell(5, 6, ':', 0, 0, 'L');

        $pdf->SetFont('dejavusans', '', 12);
        $pdf->Cell(10, 6, '☐', 0, 0, 'L');

        $pdf->SetFont('times', '', 12);
        $pdf->Cell(0, 6, 'Melihat/Membaca/Mendengarkan/Mencatat', 0, 1, 'L');

        $pdf->Cell($lebar_indentasi, 6, '', 0, 0, 'L');

        $pdf->SetFont('dejavusans', '', 12);
        $pdf->Cell(10, 6, '☑', 0, 0, 'L');

        $pdf->SetFont('times', '', 12);
        $pdf->Cell(0, 6, 'Mendapatkan Salinan Informasi (Hard Copy / Soft Copy)', 0, 1, 'L');

        $pdf->Ln(2);

        // Catatan Petugas dengan background seperti kandungan informasi
        $catatan_petugas = !empty($data['catatan_petugas']) ? $data['catatan_petugas'] : '-';
        $pdf->SetFont('times', '', 12);
        $pdf->Cell(50, 6, 'Catatan Petugas', 0, 0, 'L');
        $pdf->Cell(5, 6, ':', 0, 0, 'L');
        $pdf->SetFillColor(211, 211, 211);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(0, 6, $catatan_petugas, 1, 1, 'L', true);

        $pdf->SetFillColor(255, 255, 255);
        $pdf->Ln(2);

        $pdf->Ln(10);
    }

    // ============ BUKTI DITOLAK PDF GENERATION ============

    private function generateBuktiDitolakTCPDF($data, $skpd_data = null)
    {
        // Include TCPDF library
        require_once 'vendor/tecnickcom/tcpdf/tcpdf.php';
        
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // Document settings
        $pdf->SetCreator('PPID Mandailing Natal');
        $pdf->SetAuthor('PPID Mandailing Natal');
        $pdf->SetTitle('Bukti Ditolak Permohonan Informasi');
        $pdf->SetSubject('Bukti Ditolak Permohonan Informasi');

        // Remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // Set margins
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetAutoPageBreak(true, 15);

        $pdf->AddPage();
        $pdf->SetFont('times', '', 12);
        $pdf->SetCellHeightRatio(1.15);

        // Use same header as bukti proses
        $this->addBuktiProsesHeader($pdf, $data, $skpd_data);

        // Title section
        $this->addBuktiProsesTitle($pdf, $data);

        // Data section with PERMOHONAN DITOLAK
        $this->addBuktiDitolakDataSection($pdf, $data);

        // Signature section
        $this->addBuktiProsesSignature($pdf, $data);

        // Footer notes
        $this->addBuktiProsesFooter($pdf);

        // Output PDF
        $filename = 'Bukti_Ditolak_' . ($data['no_permohonan'] ?? $data['id_permohonan']) . '.pdf';
        $pdf->Output($filename, 'I');
        exit();
    }

    // Add data section for Bukti Ditolak with PERMOHONAN DITOLAK
    private function addBuktiDitolakDataSection($pdf, $data)
    {
        $pdf->Ln(5);
        $pdf->SetFont('times', '', 12);

        $items = [
            ['Nama Pemohon', $data['nama_lengkap'] ?? $data['username']],
            ['Alamat', $data['alamat'] ?? ''],
            ['Telepon', $data['no_kontak'] ?? ''],
            ['Email', $data['email'] ?? ''],
            ['Informasi Dimohon', $data['judul_dokumen'] ?? ''],
            ['Provinsi Tujuan', $data['provinsi'] ?? 'Kabupaten Mandailing Natal'],
            ['Kab/Kota Tujuan', $data['city'] ?? 'Panyabungan'],
            ['OPD Tujuan', $data['komponen_tujuan'] ?? 'DINAS KOMUNIKASI DAN INFORMATIKA']
        ];

        foreach ($items as $item) {
            $pdf->Cell(50, 6, $item[0], 0, 0, 'L');
            $pdf->Cell(5, 6, ':', 0, 0, 'L');
            $pdf->Cell(0, 6, $item[1], 0, 1, 'L');
        }

        // Kandungan Informasi dengan background
        $kandungan_info = $data['kandungan_informasi'] ?? $data['tujuan_permohonan'] ?? 'permintaan informasi perbup tentang spbe';
        $pdf->Cell(50, 6, 'Kandungan Informasi', 0, 0, 'L');
        $pdf->Cell(5, 6, ':', 0, 0, 'L');
        $pdf->SetFillColor(211, 211, 211);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(0, 6, $kandungan_info, 1, 1, 'L', true);

        $pdf->Ln(1);

        // Tujuan Penggunaan dengan background
        $tujuan_penggunaan = $data['tujuan_penggunaan_informasi'] ?? 'permintaan informasi perbup tentang spbe';
        $pdf->Cell(50, 6, 'Tujuan Penggunaan', 0, 0, 'L');
        $pdf->Cell(5, 6, ':', 0, 0, 'L');
        $pdf->SetFillColor(211, 211, 211);
        $pdf->Cell(0, 6, $tujuan_penggunaan, 1, 1, 'L', true);

        $pdf->SetFillColor(255, 255, 255);

        $pdf->Ln(2);

        // Keputusan PPID section - PERMOHONAN DITOLAK
        $pdf->SetFont('times', '', 12);
        $pdf->Cell(0, 6, 'Keputusan PPID', 0, 1, 'C');

        $pdf->SetFont('times', 'B', 12);
        $pdf->Cell(0, 6, 'PERMOHONAN DITOLAK', 0, 1, 'C');

        $pdf->SetFont('times', '', 12);
        $pdf->Ln(2);

        // Alasan Penolakan
        $alasan_penolakan = !empty($data['alasan_penolakan']) ? $data['alasan_penolakan'] : 'Belum dikuasai informasi';
        $pdf->Cell(50, 6, 'Alasan Penolakan', 0, 0, 'L');
        $pdf->Cell(5, 6, ':', 0, 0, 'L');
        $pdf->SetFillColor(255, 255, 204);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(0, 6, $alasan_penolakan, 1, 1, 'L', true);

        $pdf->SetFillColor(255, 255, 255);

        $pdf->Ln(1);

        // Catatan Petugas dengan background seperti kandungan informasi
        $catatan_petugas = !empty($data['catatan_petugas']) ? $data['catatan_petugas'] : '-';
        $pdf->SetFont('times', '', 12);
        $pdf->Cell(50, 6, 'Catatan Petugas', 0, 0, 'L');
        $pdf->Cell(5, 6, ':', 0, 0, 'L');
        $pdf->SetFillColor(211, 211, 211);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(0, 6, $catatan_petugas, 1, 1, 'L', true);

        $pdf->SetFillColor(255, 255, 255);

        $pdf->Ln(2);

        // Cara Memperoleh Informasi
        $lebar_indentasi = 55;
        $pdf->Cell(50, 6, 'Cara Memperoleh Informasi', 0, 0, 'L');
        $pdf->Cell(5, 6, ':', 0, 0, 'L');

        $pdf->SetFont('dejavusans', '', 12);
        $pdf->Cell(10, 6, '☐', 0, 0, 'L');

        $pdf->SetFont('times', '', 12);
        $pdf->Cell(0, 6, 'Melihat/Membaca/Mendengarkan/Mencatat', 0, 1, 'L');

        $pdf->Cell($lebar_indentasi, 6, '', 0, 0, 'L');

        $pdf->SetFont('dejavusans', '', 12);
        $pdf->Cell(10, 6, '☑', 0, 0, 'L');

        $pdf->SetFont('times', '', 12);
        $pdf->Cell(0, 6, 'Mendapatkan Salinan Informasi (Hard Copy / Soft Copy)', 0, 1, 'L');

        $pdf->Ln(10);
    }

    // ============ BUKTI DISPOSISI PDF GENERATION ============

    private function generateBuktiDisposisiTCPDF($data, $skpd_data = null)
    {
        // Include TCPDF library
        require_once 'vendor/tecnickcom/tcpdf/tcpdf.php';
        
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // Document settings
        $pdf->SetCreator('PPID Mandailing Natal');
        $pdf->SetAuthor('PPID Mandailing Natal');
        $pdf->SetTitle('Bukti Disposisi Permohonan Informasi');
        $pdf->SetSubject('Bukti Disposisi Permohonan Informasi');

        // Remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // Set margins
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetAutoPageBreak(true, 15);

        $pdf->AddPage();
        $pdf->SetFont('times', '', 12);
        $pdf->SetCellHeightRatio(1.15);

        // Use same header as bukti proses
        $this->addBuktiProsesHeader($pdf, $data, $skpd_data);

        // Title section
        $this->addBuktiProsesTitle($pdf, $data);

        // Data section with PERMOHONAN DITOLAK
        $this->addBuktiDisposisiDataSection($pdf, $data);

        // Signature section
        $this->addBuktiProsesSignature($pdf, $data);

        // Footer notes
        $this->addBuktiProsesFooter($pdf);

        // Output PDF
        $filename = 'Bukti_Disposisi_' . ($data['no_permohonan'] ?? $data['id_permohonan']) . '.pdf';
        $pdf->Output($filename, 'I');
        exit();
    }

    // Add data section for Bukti Disposisi with PERMOHONAN DITOLAK
    private function addBuktiDisposisiDataSection($pdf, $data)
    {
        $pdf->Ln(5);
        $pdf->SetFont('times', '', 12);

        $items = [
            ['Nama Pemohon', $data['nama_lengkap'] ?? $data['username']],
            ['Alamat', $data['alamat'] ?? ''],
            ['Telepon', $data['no_kontak'] ?? ''],
            ['Email', $data['email'] ?? ''],
            ['Informasi Dimohon', $data['judul_dokumen'] ?? ''],
            ['Provinsi Tujuan', $data['provinsi'] ?? 'Kabupaten Mandailing Natal'],
            ['Kab/Kota Tujuan', $data['city'] ?? 'Panyabungan'],
            ['OPD Tujuan', $data['komponen_tujuan'] ?? 'DINAS KOMUNIKASI DAN INFORMATIKA']
        ];

        foreach ($items as $item) {
            $pdf->Cell(50, 6, $item[0], 0, 0, 'L');
            $pdf->Cell(5, 6, ':', 0, 0, 'L');
            $pdf->Cell(0, 6, $item[1], 0, 1, 'L');
        }

        // Kandungan Informasi dengan background
        $kandungan_info = $data['kandungan_informasi'] ?? $data['tujuan_permohonan'] ?? 'permintaan informasi perbup tentang spbe';
        $pdf->Cell(50, 6, 'Kandungan Informasi', 0, 0, 'L');
        $pdf->Cell(5, 6, ':', 0, 0, 'L');
        $pdf->SetFillColor(211, 211, 211);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(0, 6, $kandungan_info, 1, 1, 'L', true);

        $pdf->Ln(1);

        // Tujuan Penggunaan dengan background
        $tujuan_penggunaan = $data['tujuan_penggunaan_informasi'] ?? 'permintaan informasi perbup tentang spbe';
        $pdf->Cell(50, 6, 'Tujuan Penggunaan', 0, 0, 'L');
        $pdf->Cell(5, 6, ':', 0, 0, 'L');
        $pdf->SetFillColor(211, 211, 211);
        $pdf->Cell(0, 6, $tujuan_penggunaan, 1, 1, 'L', true);

        $pdf->SetFillColor(255, 255, 255);

        $pdf->Ln(2);

        // Keputusan PPID section - PERMOHONAN DITOLAK
        $pdf->SetFont('times', '', 12);
        $pdf->Cell(0, 6, 'Keputusan PPID', 0, 1, 'C');

        $pdf->SetFont('times', 'B', 12);
        $pdf->Cell(0, 6, 'PERMOHONAN DITOLAK', 0, 1, 'C');

        $pdf->SetFont('times', '', 12);
        $pdf->Ln(2);

        // Alasan Penolakan
        $alasan_penolakan = !empty($data['alasan_penolakan']) ? $data['alasan_penolakan'] : 'Belum dikuasai informasi';
        $pdf->Cell(50, 6, 'Alasan Penolakan', 0, 0, 'L');
        $pdf->Cell(5, 6, ':', 0, 0, 'L');
        $pdf->SetFillColor(255, 255, 204);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(0, 6, $alasan_penolakan, 1, 1, 'L', true);

        $pdf->SetFillColor(255, 255, 255);

        $pdf->Ln(1);

        // Catatan Petugas dengan background seperti kandungan informasi
        $catatan_petugas = !empty($data['catatan_petugas']) ? $data['catatan_petugas'] : '-';
        $pdf->SetFont('times', '', 12);
        $pdf->Cell(50, 6, 'Catatan Petugas', 0, 0, 'L');
        $pdf->Cell(5, 6, ':', 0, 0, 'L');
        $pdf->SetFillColor(211, 211, 211);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(0, 6, $catatan_petugas, 1, 1, 'L', true);

        $pdf->SetFillColor(255, 255, 255);

        $pdf->Ln(2);

        // Cara Memperoleh Informasi
        $lebar_indentasi = 55;
        $pdf->Cell(50, 6, 'Cara Memperoleh Informasi', 0, 0, 'L');
        $pdf->Cell(5, 6, ':', 0, 0, 'L');

        $pdf->SetFont('dejavusans', '', 12);
        $pdf->Cell(10, 6, '☐', 0, 0, 'L');

        $pdf->SetFont('times', '', 12);
        $pdf->Cell(0, 6, 'Melihat/Membaca/Mendengarkan/Mencatat', 0, 1, 'L');

        $pdf->Cell($lebar_indentasi, 6, '', 0, 0, 'L');

        $pdf->SetFont('dejavusans', '', 12);
        $pdf->Cell(10, 6, '☑', 0, 0, 'L');

        $pdf->SetFont('times', '', 12);
        $pdf->Cell(0, 6, 'Mendapatkan Salinan Informasi (Hard Copy / Soft Copy)', 0, 1, 'L');

        $pdf->Ln(10);
    }
}
?>
