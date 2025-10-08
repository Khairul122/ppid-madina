<?php
require_once 'models/PermohonanPetugasModel.php';
require_once 'models/SKPDModel.php';
require_once 'vendor/tecnickcom/tcpdf/tcpdf.php';

class PermohonanPetugasController
{
    private $permohonanPetugasModel;
    private $skpdModel;
    private const ITEMS_PER_PAGE = 10;
    private const ALLOWED_STATUSES = ['all', 'Masuk', 'Diproses', 'Disposisi', 'Selesai', 'Ditolak'];

    public function __construct()
    {
        $this->checkPetugasAccess();

        global $database;
        $db = $database->getConnection();
        $this->permohonanPetugasModel = new PermohonanPetugasModel($db);
        $this->skpdModel = new SKPDModel($db);
    }

    // Display meja layanan for petugas
    public function mejaLayanan()
    {
        $params = $this->getPaginationParams();

        $permohonan_list = $this->permohonanPetugasModel->getAllPermohonan(
            $params['limit'],
            $params['offset'],
            $params['status'],
            $params['search']
        );

        $total_records = $this->permohonanPetugasModel->countAllPermohonan($params['status'], $params['search']);
        $total_pages = ceil($total_records / $params['limit']);
        $stats = $this->permohonanPetugasModel->getPetugasStats();

        extract($this->getSessionMessages());
        include 'views/permohonan_petugas/meja_layanan/index.php';
    }

    // Display permohonan detail for petugas
    public function view()
    {
        $id = $this->getRequiredId('mejaLayanan');
        $permohonan = $this->getPermohonanOrFail($id, 'mejaLayanan');
        $skpd_data = $this->getSKPDData($permohonan['komponen_tujuan']);

        extract($this->getSessionMessages());

        // Load view based on status
        $view = ($permohonan['status'] === 'Disposisi')
            ? 'views/permohonan_petugas/permohonan_disposisi/view.php'
            : 'views/permohonan_petugas/permohonan_masuk/view.php';

        include $view;
    }

    // Update permohonan status
    public function updateStatus()
    {
        $this->setJsonHeader();
        $this->validatePostRequest();

        $id = $this->getPermohonanId();
        $status = trim($_POST['status'] ?? '');

        if (!$this->validateStatus($status, ['Masuk', 'Diproses', 'Disposisi', 'Selesai', 'Ditolak'])) {
            $this->sendJsonResponse(['success' => false, 'message' => 'Status tidak valid']);
        }

        $result = $this->processStatusUpdate($id, $status);

        $message = $result ? 'Status berhasil diperbarui' : 'Gagal memperbarui status';
        $this->sendJsonResponse(['success' => $result, 'message' => $message]);
    }

    // Update sisa jatuh tempo
    public function updateJatuhTempo()
    {
        $this->setJsonHeader();
        $this->validatePostRequest();

        if (!isset($_POST['id_permohonan']) || !isset($_POST['sisa_jatuh_tempo'])) {
            $this->sendJsonResponse(['success' => false, 'message' => 'Data tidak lengkap']);
        }

        $id = intval($_POST['id_permohonan']);
        $sisa_jatuh_tempo = intval($_POST['sisa_jatuh_tempo']);

        if ($sisa_jatuh_tempo < 0) {
            $this->sendJsonResponse(['success' => false, 'message' => 'Sisa jatuh tempo tidak boleh negatif']);
        }

        $result = $this->permohonanPetugasModel->updateSisaJatuhTempo($id, $sisa_jatuh_tempo);
        $this->sendJsonResponse($result);
    }

    // Display permohonan masuk for petugas SKPD
    // Display permohonan masuk for petugas SKPD
    public function permohonanMasuk()
    {
        // Get petugas SKPD data
        $user_id = $_SESSION['user_id'];
        $petugas_skpd = $this->permohonanPetugasModel->getPetugasSKPDByUserId($user_id);

        if (!$petugas_skpd) {
            $_SESSION['error_message'] = 'Data SKPD petugas tidak ditemukan';
            header('Location: index.php?controller=user&action=index');
            exit();
        }

        $nama_skpd = $petugas_skpd['nama_skpd'];
        $params = $this->getPaginationParams();

        // Override status parameter to only show 'Masuk' requests
        $params['status'] = 'Masuk';

        $permohonan_list = $this->permohonanPetugasModel->getPermohonanBySKPD(
            $nama_skpd,
            $params['limit'],
            $params['offset'],
            $params['status'],
            $params['search']
        );

        $total_records = $this->permohonanPetugasModel->countPermohonanBySKPD($nama_skpd, $params['status'], $params['search']);
        $total_pages = ceil($total_records / $params['limit']);
        $stats = $this->permohonanPetugasModel->getPetugasStatsBySKPD($nama_skpd);

        // Extract params for view
        $page = $params['page'];
        $limit = $params['limit'];
        $offset = $params['offset'];
        $status = $params['status'];
        $search = $params['search'];

        extract($this->getSessionMessages());
        include 'views/permohonan_petugas/permohonan_masuk/index.php';
    }

    public function permohonanMasukView()
    {
        $this->renderSKPDDetailView('permohonanMasuk', 'permohonan_masuk');
    }

    public function editPermohonanMasuk()
    {
        $id = $this->getRequiredId('permohonanMasuk');
        $permohonan = $this->getPermohonanOrFail($id, 'permohonanMasuk');
        $skpd_data = $this->getSKPDData($permohonan['komponen_tujuan']);

        extract($this->getSessionMessages());
        include 'views/permohonan_petugas/permohonan_masuk/edit.php';
    }

    public function updatePermohonanMasuk()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error_message'] = 'Metode tidak diizinkan';
            header('Location: index.php?controller=permohonanpetugas&action=permohonanMasuk');
            exit();
        }

        if (!isset($_POST['id_permohonan']) || empty($_POST['id_permohonan'])) {
            $_SESSION['error_message'] = 'ID permohonan tidak ditemukan';
            header('Location: index.php?controller=permohonanpetugas&action=permohonanMasuk');
            exit();
        }

        // Validasi input
        $id = intval($_POST['id_permohonan']);
        $judul_dokumen = trim($_POST['judul_dokumen'] ?? '');
        $tujuan_penggunaan_informasi = trim($_POST['tujuan_penggunaan_informasi'] ?? '');
        $tujuan_permohonan = trim($_POST['tujuan_permohonan'] ?? '');
        $komponen_tujuan = trim($_POST['komponen_tujuan'] ?? '');
        $sumber_media = trim($_POST['sumber_media'] ?? '');

        // Validasi wajib
        if (empty($judul_dokumen) || empty($tujuan_penggunaan_informasi)) {
            $_SESSION['error_message'] = 'Judul dokumen dan tujuan penggunaan informasi wajib diisi';
            header("Location: index.php?controller=permohonanpetugas&action=editPermohonanMasuk&id=$id");
            exit();
        }

        // Update data permohonan
        $query = "UPDATE {$this->permohonanPetugasModel->table_permohonan} 
                  SET judul_dokumen = :judul_dokumen,
                      tujuan_penggunaan_informasi = :tujuan_penggunaan_informasi,
                      tujuan_permohonan = :tujuan_permohonan,
                      komponen_tujuan = :komponen_tujuan,
                      sumber_media = :sumber_media,
                      updated_at = NOW()
                  WHERE id_permohonan = :id_permohonan";

        $stmt = $this->permohonanPetugasModel->conn->prepare($query);
        $stmt->bindParam(':judul_dokumen', $judul_dokumen);
        $stmt->bindParam(':tujuan_penggunaan_informasi', $tujuan_penggunaan_informasi);
        $stmt->bindParam(':tujuan_permohonan', $tujuan_permohonan);
        $stmt->bindParam(':komponen_tujuan', $komponen_tujuan);
        $stmt->bindParam(':sumber_media', $sumber_media);
        $stmt->bindParam(':id_permohonan', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = 'Data permohonan berhasil diperbarui';
        } else {
            $_SESSION['error_message'] = 'Gagal memperbarui data permohonan';
        }

        header("Location: index.php?controller=permohonanpetugas&action=permohonanMasukView&id=$id");
        exit();
    }

    public function deletePermohonanMasuk()
    {
        if (!isset($_GET['id']) || empty($_GET['id'])) {
            $_SESSION['error_message'] = 'ID permohonan tidak ditemukan';
            header('Location: index.php?controller=permohonanpetugas&action=permohonanMasuk');
            exit();
        }

        $id = intval($_GET['id']);

        $permohonan = $this->permohonanPetugasModel->getPermohonanById($id);
        if (!$permohonan) {
            $_SESSION['error_message'] = 'Data permohonan tidak ditemukan';
            header('Location: index.php?controller=permohonanpetugas&action=permohonanMasuk');
            exit();
        }

        // Hanya permohonan dengan status 'Masuk' yang bisa dihapus
        if ($permohonan['status'] !== 'Masuk') {
            $_SESSION['error_message'] = 'Hanya permohonan dengan status Masuk yang bisa dihapus';
            header('Location: index.php?controller=permohonanpetugas&action=permohonanMasuk');
            exit();
        }

        $result = $this->permohonanPetugasModel->deletePermohonan($id);

        if ($result) {
            $_SESSION['success_message'] = 'Data permohonan berhasil dihapus';
        } else {
            $_SESSION['error_message'] = 'Gagal menghapus data permohonan';
        }

        header('Location: index.php?controller=permohonanpetugas&action=permohonanMasuk');
        exit();
    }

    // Function to change status from Disposisi to Masuk
    public function ubahStatusKeMasuk()
    {
        $this->validatePostRequest();

        if (!isset($_POST['id']) || empty($_POST['id'])) {
            $_SESSION['error_message'] = 'ID permohonan tidak ditemukan';
            header('Location: index.php?controller=permohonanpetugas&action=disposisiIndex');
            exit();
        }

        $id = intval($_POST['id']);
        $permohonan = $this->permohonanPetugasModel->getPermohonanById($id);

        if (!$permohonan) {
            $_SESSION['error_message'] = 'Permohonan tidak ditemukan';
            header('Location: index.php?controller=permohonanpetugas&action=disposisiIndex');
            exit();
        }

        // Hanya permohonan dengan status 'Disposisi' yang bisa diubah ke 'Masuk'
        if ($permohonan['status'] !== 'Disposisi') {
            $_SESSION['error_message'] = 'Hanya permohonan dengan status Disposisi yang bisa dikembalikan ke Masuk';
            header('Location: index.php?controller=permohonanpetugas&action=disposisiIndex');
            exit();
        }

        // Update status ke 'Masuk'
        $result = $this->permohonanPetugasModel->updatePermohonanStatus($id, 'Masuk');

        if ($result) {
            $_SESSION['success_message'] = 'Status permohonan berhasil diubah ke Masuk';
        } else {
            $_SESSION['error_message'] = 'Gagal mengubah status permohonan';
        }

        header('Location: index.php?controller=permohonanpetugas&action=disposisiIndex');
        exit();
    }

    // Display permohonan disposisi for petugas SKPD
    public function disposisiIndex()
    {
        $nama_skpd = $this->getPetugasSKPD();
        $params = $this->getPaginationParams();

        $permohonan_list = $this->permohonanPetugasModel->getDisposisiBySKPD(
            $nama_skpd,
            $params['limit'],
            $params['offset'],
            $params['search']
        );

        $total_records = $this->permohonanPetugasModel->countDisposisiBySKPD($nama_skpd, $params['search']);
        $total_pages = ceil($total_records / $params['limit']);
        $stats = $this->permohonanPetugasModel->getPetugasStatsBySKPD($nama_skpd);

        // Extract params for view
        $page = $params['page'];
        $limit = $params['limit'];
        $offset = $params['offset'];
        $search = $params['search'];

        extract($this->getSessionMessages());
        include 'views/permohonan_petugas/permohonan_disposisi/index.php';
    }

    // Display permohonan disposisi detail view
    public function disposisiView()
    {
        $this->renderSKPDDetailView('disposisiIndex', 'permohonan_disposisi');
    }

    // Display permohonan sedang diproses index
    public function permohonanDiproses()
    {
        // Get petugas SKPD data
        $user_id = $_SESSION['user_id'];
        $petugas_skpd = $this->permohonanPetugasModel->getPetugasSKPDByUserId($user_id);

        if (!$petugas_skpd) {
            $_SESSION['error_message'] = 'Data SKPD petugas tidak ditemukan';
            header('Location: index.php?controller=user&action=index');
            exit();
        }

        $nama_skpd = $petugas_skpd['nama_skpd'];
        $params = $this->getPaginationParams();

        // Override status parameter to only show 'Diproses' requests
        $params['status'] = 'Diproses';

        $permohonan_list = $this->permohonanPetugasModel->getPermohonanDiprosesBySKPD(
            $nama_skpd,
            $params['limit'],
            $params['offset'],
            $params['status'],
            $params['search']
        );

        $total_records = $this->permohonanPetugasModel->countPermohonanDiprosesBySKPD($nama_skpd, $params['status'], $params['search']);
        $total_pages = ceil($total_records / $params['limit']);
        $stats = $this->permohonanPetugasModel->getPetugasStatsBySKPD($nama_skpd);

        // Extract params for view
        $page = $params['page'];
        $limit = $params['limit'];
        $offset = $params['offset'];
        $status = $params['status'];
        $search = $params['search'];

        extract($this->getSessionMessages());
        include 'views/permohonan_petugas/permohonan_diproses/index.php';
    }

    // Alias for permohonanDiproses (backward compatibility)
    public function diprosesIndex()
    {
        // Logging untuk debugging
        error_log("diprosesIndex called");
        error_log("Session user_id: " . ($_SESSION['user_id'] ?? 'NOT SET'));
        error_log("Session role: " . ($_SESSION['role'] ?? 'NOT SET'));
        
        // Pastikan pengguna adalah petugas
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'petugas') {
            error_log("Access denied - not a petugas");
            $_SESSION['error_message'] = 'Akses ditolak. Hanya petugas yang dapat mengakses halaman ini.';
            header('Location: index.php?controller=auth&action=login');
            exit();
        }
        
        $this->permohonanDiproses();
    }

    // Display permohonan sedang diproses detail view
    public function permohonanDiprosesView()
    {
        $this->renderSKPDDetailView('permohonanDiproses', 'permohonan_diproses');
    }

    // Alias for permohonanDiprosesView (backward compatibility)
    public function diprosesView()
    {
        $this->permohonanDiprosesView();
    }


    // Generate PDF for surat permohonan
    public function generatePDF()
    {
        $id = $this->getRequiredId('mejaLayanan');
        $permohonan = $this->getPermohonanOrFail($id, 'mejaLayanan');
        $skpd_data = $this->getSKPDData($permohonan['komponen_tujuan']);

        $this->generateTCPDF($permohonan, $skpd_data);
    }

    // Generate Bukti Proses PDF
    public function generateBuktiProsesPDF()
    {
        $id = $this->getRequiredId('diprosesIndex');
        $permohonan = $this->getPermohonanOrFail($id, 'diprosesIndex');
        $skpd_data = $this->getSKPDData($permohonan['komponen_tujuan']);

        $this->generateBuktiProsesTCPDF($permohonan, $skpd_data);
    }

    // Generate Bukti Selesai PDF
    public function generateBuktiSelesaiPDF()
    {
        $id = $this->getRequiredId('selesaiIndex');
        $permohonan = $this->getPermohonanOrFail($id, 'selesaiIndex');
        $skpd_data = $this->getSKPDData($permohonan['komponen_tujuan']);

        $this->generateBuktiSelesaiTCPDF($permohonan, $skpd_data);
    }

    // Download file
    public function downloadFile()
    {
        if (!isset($_GET['file']) || !isset($_GET['id'])) {
            $_SESSION['error_message'] = 'Parameter tidak lengkap';
            header('Location: index.php?controller=permohonanpetugas&action=diprosesIndex');
            exit();
        }

        $file = basename($_GET['file']);
        $id = intval($_GET['id']);

        // Verify permohonan exists and user has access
        $permohonan = $this->permohonanPetugasModel->getPermohonanById($id);
        if (!$permohonan) {
            $_SESSION['error_message'] = 'Permohonan tidak ditemukan';
            header('Location: index.php?controller=permohonanpetugas&action=diprosesIndex');
            exit();
        }

        $upload_dir = __DIR__ . '/../uploads/';
        $file_path = $upload_dir . $file;

        if (!file_exists($file_path)) {
            $_SESSION['error_message'] = 'File tidak ditemukan';
            header('Location: index.php?controller=permohonanpetugas&action=diprosesIndex');
            exit();
        }

        // Force download
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file_path));
        readfile($file_path);
        exit();
    }

    // AJAX endpoint to get SKPD names based on selected category
    public function getKomponen()
    {
        header('Content-Type: application/json');

        if (!isset($_GET['tujuan_permohonan']) || empty($_GET['tujuan_permohonan'])) {
            echo json_encode(['success' => false, 'message' => 'Kategori SKPD tidak diberikan']);
            exit();
        }

        $kategori = $_GET['tujuan_permohonan'];
        $skpd_list = $this->skpdModel->getSKPDByKategori($kategori);

        $formatted_list = array_map(function ($skpd) {
            return [
                'value' => $skpd['nama_skpd'],
                'label' => $skpd['nama_skpd']
            ];
        }, $skpd_list);

        echo json_encode(['success' => true, 'data' => $formatted_list]);
        exit();
    }

    // ============ PRIVATE HELPER METHODS ============

    private function checkPetugasAccess($required_skpd = null)
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'petugas') {
            $_SESSION['error_message'] = 'Akses ditolak. Hanya petugas yang dapat mengakses halaman ini.';
            header('Location: index.php?controller=auth&action=login');
            exit();
        }

        if ($required_skpd !== null) {
            $petugas_skpd = $this->permohonanPetugasModel->getPetugasSKPDByUserId($_SESSION['user_id']);
            return $petugas_skpd && $petugas_skpd['nama_skpd'] === $required_skpd;
        }

        return true;
    }

    private function getPaginationParams()
    {
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $limit = self::ITEMS_PER_PAGE;
        $offset = ($page - 1) * $limit;
        $status = $this->validateStatus($_GET['status'] ?? 'all') ? $_GET['status'] : 'all';
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';

        return compact('page', 'limit', 'offset', 'status', 'search');
    }

    private function validateStatus($status, $allowed = null)
    {
        $allowed = $allowed ?? self::ALLOWED_STATUSES;
        return in_array($status, $allowed);
    }

    private function getSessionMessages()
    {
        $success_message = $_SESSION['success_message'] ?? '';
        $error_message = $_SESSION['error_message'] ?? '';

        unset($_SESSION['success_message'], $_SESSION['error_message']);

        return compact('success_message', 'error_message');
    }

    private function getRequiredId($redirect_action)
    {
        if (!isset($_GET['id']) || empty($_GET['id'])) {
            $_SESSION['error_message'] = 'ID permohonan tidak ditemukan';
            header("Location: index.php?controller=permohonanpetugas&action=$redirect_action");
            exit();
        }
        return intval($_GET['id']);
    }

    private function getPermohonanOrFail($id, $redirect_action)
    {
        $permohonan = $this->permohonanPetugasModel->getPermohonanById($id);

        if (!$permohonan) {
            $_SESSION['error_message'] = 'Data permohonan tidak ditemukan';
            header("Location: index.php?controller=permohonanpetugas&action=$redirect_action");
            exit();
        }

        return $permohonan;
    }

    private function getSKPDData($komponen_tujuan)
    {
        return !empty($komponen_tujuan)
            ? $this->permohonanPetugasModel->getSKPDDataByName($komponen_tujuan)
            : null;
    }

    private function getPetugasSKPD()
    {
        $user_id = $_SESSION['user_id'];
        $petugas_skpd = $this->permohonanPetugasModel->getPetugasSKPDByUserId($user_id);

        if (!$petugas_skpd) {
            $_SESSION['error_message'] = 'Data SKPD petugas tidak ditemukan';
            header('Location: index.php?controller=user&action=index');
            exit();
        }

        return $petugas_skpd['nama_skpd'];
    }

    private function renderSKPDView($view_folder, $get_method, $count_method)
    {
        // Get petugas SKPD data
        $user_id = $_SESSION['user_id'];
        $petugas_skpd = $this->permohonanPetugasModel->getPetugasSKPDByUserId($user_id);

        if (!$petugas_skpd) {
            $_SESSION['error_message'] = 'Data SKPD petugas tidak ditemukan';
            header('Location: index.php?controller=user&action=index');
            exit();
        }

        $nama_skpd = $petugas_skpd['nama_skpd'];
        $params = $this->getPaginationParams();

        $permohonan_list = $this->permohonanPetugasModel->$get_method(
            $nama_skpd,
            $params['limit'],
            $params['offset'],
            $params['status'],
            $params['search']
        );

        $total_records = $this->permohonanPetugasModel->$count_method($nama_skpd, $params['status'], $params['search']);
        $total_pages = ceil($total_records / $params['limit']);
        $stats = $this->permohonanPetugasModel->getPetugasStatsBySKPD($nama_skpd);

        // Extract params for view
        $page = $params['page'];
        $limit = $params['limit'];
        $offset = $params['offset'];
        $status = $params['status'];
        $search = $params['search'];

        extract($this->getSessionMessages());
        include "views/permohonan_petugas/$view_folder/index.php";
    }

    private function renderSKPDDetailView($redirect_action, $view_folder)
    {
        $id = $this->getRequiredId($redirect_action);
        $permohonan = $this->getPermohonanOrFail($id, $redirect_action);

        // Validate SKPD access
        $user_id = $_SESSION['user_id'];
        $petugas_skpd = $this->permohonanPetugasModel->getPetugasSKPDByUserId($user_id);

        if (!$petugas_skpd || $petugas_skpd['nama_skpd'] !== $permohonan['komponen_tujuan']) {
            $_SESSION['error_message'] = 'Anda tidak memiliki akses untuk melihat permohonan ini';
            header("Location: index.php?controller=permohonanpetugas&action=$redirect_action");
            exit();
        }

        $skpd_data = $this->getSKPDData($permohonan['komponen_tujuan']);
        extract($this->getSessionMessages());

        include "views/permohonan_petugas/$view_folder/view.php";
    }

    private function setJsonHeader()
    {
        header('Content-Type: application/json');
    }

    private function validatePostRequest()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendJsonResponse(['success' => false, 'message' => 'Method tidak diizinkan']);
        }
    }

    private function getPermohonanId()
    {
        if (!isset($_POST['id_permohonan']) && !isset($_POST['id'])) {
            $this->sendJsonResponse(['success' => false, 'message' => 'Data tidak lengkap']);
        }
        return isset($_POST['id']) ? intval($_POST['id']) : intval($_POST['id_permohonan']);
    }

    private function processStatusUpdate($id, $status)
    {
        switch ($status) {
            case 'Diproses':
                return $this->handleDiprosesUpdate($id, $status);
            case 'Disposisi':
                return $this->handleDisposisiUpdate($id, $status);
            case 'Ditolak':
                return $this->handlePenolakanUpdate($id, $status);
            default:
                return $this->permohonanPetugasModel->updatePermohonanStatus($id, $status);
        }
    }

    private function handleDiprosesUpdate($id, $status)
    {
        $catatan_petugas = trim($_POST['catatan_petugas'] ?? '');

        if (empty($catatan_petugas)) {
            $this->sendJsonResponse(['success' => false, 'message' => 'Catatan petugas harus diisi']);
        }

        return $this->permohonanPetugasModel->updateStatusWithCatatan($id, $status, $catatan_petugas);
    }

    private function handleDisposisiUpdate($id, $status)
    {
        if (empty(trim($_POST['tujuan_permohonan'] ?? ''))) {
            $this->sendJsonResponse(['success' => false, 'message' => 'Tujuan permohonan harus dipilih']);
        }
        if (empty(trim($_POST['komponen_tujuan'] ?? ''))) {
            $this->sendJsonResponse(['success' => false, 'message' => 'Komponen tujuan harus dipilih']);
        }

        $updateData = [
            'id_permohonan' => $id,
            'status' => $status,
            'tujuan_permohonan' => trim($_POST['tujuan_permohonan']),
            'komponen_tujuan' => trim($_POST['komponen_tujuan']),
            'catatan_petugas' => trim($_POST['catatan_petugas'] ?? '')
        ];

        return $this->permohonanPetugasModel->updatePermohonanWithDisposisi($updateData);
    }

    private function handlePenolakanUpdate($id, $status)
    {
        if (empty(trim($_POST['alasan_penolakan'] ?? ''))) {
            $this->sendJsonResponse(['success' => false, 'message' => 'Alasan penolakan harus diisi']);
        }
        if (empty(trim($_POST['catatan_petugas'] ?? ''))) {
            $this->sendJsonResponse(['success' => false, 'message' => 'Catatan petugas harus diisi']);
        }

        $updateData = [
            'id_permohonan' => $id,
            'status' => $status,
            'alasan_penolakan' => trim($_POST['alasan_penolakan']),
            'catatan_petugas' => trim($_POST['catatan_petugas'])
        ];

        return $this->permohonanPetugasModel->updatePermohonanWithPenolakan($updateData);
    }

    private function sendJsonResponse($data)
    {
        echo json_encode($data);
        exit();
    }

    // ============ PDF GENERATION METHODS ============

    private function generateTCPDF($data, $skpd_data = null)
    {
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

    // Display permohonan selesai index
    public function permohonanSelesai()
    {
        // Get current petugas SKPD data
        $user_id = $_SESSION['user_id'];
        $petugas_skpd = $this->permohonanPetugasModel->getPetugasSKPDByUserId($user_id);

        if (!$petugas_skpd) {
            $_SESSION['error_message'] = 'Data SKPD petugas tidak ditemukan';
            header('Location: index.php?controller=user&action=index');
            exit();
        }

        $nama_skpd = $petugas_skpd['nama_skpd'];

        // Get pagination and filter parameters
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $search = isset($_GET['search']) ? trim($_GET['search']) : '';

        // Override status to only show 'Selesai' requests
        $status = 'Selesai';

        // Get permohonan list with pagination
        $permohonan_list = $this->permohonanPetugasModel->getPermohonanSelesaiBySKPD($nama_skpd, $limit, $offset, $status, $search);
        $total_records = $this->permohonanPetugasModel->countPermohonanSelesaiBySKPD($nama_skpd, $status, $search);
        $total_pages = ceil($total_records / $limit);

        // Get stats for the dashboard
        $stats = $this->permohonanPetugasModel->getPetugasStatsBySKPD($nama_skpd);

        $success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
        $error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';

        unset($_SESSION['success_message']);
        unset($_SESSION['error_message']);

        include 'views/permohonan_petugas/permohonan_selesai/index.php';
    }

    // Display permohonan selesai detail view
    public function permohonanSelesaiView()
    {
        if (!isset($_GET['id']) || empty($_GET['id'])) {
            $_SESSION['error_message'] = 'ID permohonan tidak ditemukan';
            header('Location: index.php?controller=permohonanpetugas&action=permohonanSelesai');
            exit();
        }

        $id = intval($_GET['id']);
        $permohonan = $this->permohonanPetugasModel->getPermohonanById($id);

        if (!$permohonan) {
            $_SESSION['error_message'] = 'Data permohonan tidak ditemukan';
            header('Location: index.php?controller=permohonanpetugas&action=permohonanSelesai');
            exit();
        }

        // Check if petugas has access to this permohonan
        $user_id = $_SESSION['user_id'];
        $petugas_skpd = $this->permohonanPetugasModel->getPetugasSKPDByUserId($user_id);

        if (!$petugas_skpd || $petugas_skpd['nama_skpd'] !== $permohonan['komponen_tujuan']) {
            $_SESSION['error_message'] = 'Anda tidak memiliki akses untuk melihat permohonan ini';
            header('Location: index.php?controller=permohonanpetugas&action=permohonanSelesai');
            exit();
        }

        // Get SKPD data if available
        $skpd_data = null;
        if (!empty($permohonan['komponen_tujuan'])) {
            $skpd_data = $this->permohonanPetugasModel->getSKPDDataByName($permohonan['komponen_tujuan']);
        }

        $success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
        $error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';

        unset($_SESSION['success_message']);
        unset($_SESSION['error_message']);

        include 'views/permohonan_petugas/permohonan_selesai/view.php';
    }

    // Alias for permohonanSelesai (to match the pattern of diprosesIndex)
    public function selesaiIndex()
    {
        $this->permohonanSelesai();
    }

    // Alias for permohonanSelesaiView (backward compatibility)
    public function selesaiView()
    {
        $this->permohonanSelesaiView();
    }

    // Display permohonan ditolak index
    public function permohonanDitolak()
    {
        // Get current petugas SKPD data
        $user_id = $_SESSION['user_id'];
        $petugas_skpd = $this->permohonanPetugasModel->getPetugasSKPDByUserId($user_id);

        if (!$petugas_skpd) {
            $_SESSION['error_message'] = 'Data SKPD petugas tidak ditemukan';
            header('Location: index.php?controller=user&action=index');
            exit();
        }

        $nama_skpd = $petugas_skpd['nama_skpd'];
        
        // Get pagination and filter parameters
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $search = isset($_GET['search']) ? trim($_GET['search']) : '';

        // Override status to only show 'Ditolak' requests
        $status = 'Ditolak';

        // Get permohonan list with pagination
        $permohonan_list = $this->permohonanPetugasModel->getPermohonanDitolakBySKPD($nama_skpd, $limit, $offset, $status, $search);
        $total_records = $this->permohonanPetugasModel->countPermohonanDitolakBySKPD($nama_skpd, $status, $search);
        $total_pages = ceil($total_records / $limit);

        // Get stats for the dashboard
        $stats = $this->permohonanPetugasModel->getPetugasStatsBySKPD($nama_skpd);

        $success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
        $error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';

        unset($_SESSION['success_message']);
        unset($_SESSION['error_message']);

        include 'views/permohonan_petugas/permohonan_ditolak/index.php';
    }

    // Alias for permohonanDitolak (to match the pattern of other index functions)
    public function ditolakIndex()
    {
        // Logging untuk debugging
        error_log("ditolakIndex called");
        error_log("Session user_id: " . ($_SESSION['user_id'] ?? 'NOT SET'));
        error_log("Session role: " . ($_SESSION['role'] ?? 'NOT SET'));
        
        // Pastikan pengguna adalah petugas
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'petugas') {
            error_log("Access denied - not a petugas");
            $_SESSION['error_message'] = 'Akses ditolak. Hanya petugas yang dapat mengakses halaman ini.';
            header('Location: index.php?controller=auth&action=login');
            exit();
        }
        
        $this->permohonanDitolak();
    }

    // Alias for permohonanDitolakView (to match the pattern of diprosesView and selesaiView)
    public function ditolakView()
    {
        // Logging untuk debugging
        error_log("ditolakView called");
        error_log("Session user_id: " . ($_SESSION['user_id'] ?? 'NOT SET'));
        error_log("Session role: " . ($_SESSION['role'] ?? 'NOT SET'));
        
        // Pastikan pengguna adalah petugas
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'petugas') {
            error_log("Access denied - not a petugas");
            $_SESSION['error_message'] = 'Akses ditolak. Hanya petugas yang dapat mengakses halaman ini.';
            header('Location: index.php?controller=auth&action=login');
            exit();
        }
        
        $this->permohonanDitolakView();
    }

    // Display permohonan ditolak detail view
    public function permohonanDitolakView()
    {
        if (!isset($_GET['id']) || empty($_GET['id'])) {
            $_SESSION['error_message'] = 'ID permohonan tidak ditemukan';
            header('Location: index.php?controller=permohonanpetugas&action=permohonanDitolak');
            exit();
        }

        $id = intval($_GET['id']);
        $permohonan = $this->permohonanPetugasModel->getPermohonanById($id);

        if (!$permohonan) {
            $_SESSION['error_message'] = 'Data permohonan tidak ditemukan';
            header('Location: index.php?controller=permohonanpetugas&action=permohonanDitolak');
            exit();
        }

        // Check if petugas has access to this permohonan
        $user_id = $_SESSION['user_id'];
        $petugas_skpd = $this->permohonanPetugasModel->getPetugasSKPDByUserId($user_id);

        if (!$petugas_skpd || $petugas_skpd['nama_skpd'] !== $permohonan['komponen_tujuan']) {
            $_SESSION['error_message'] = 'Anda tidak memiliki akses untuk melihat permohonan ini';
            header('Location: index.php?controller=permohonanpetugas&action=permohonanDitolak');
            exit();
        }

        // Get SKPD data if available
        $skpd_data = null;
        if (!empty($permohonan['komponen_tujuan'])) {
            $skpd_data = $this->permohonanPetugasModel->getSKPDDataByName($permohonan['komponen_tujuan']);
        }

        $success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
        $error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';

        unset($_SESSION['success_message']);
        unset($_SESSION['error_message']);

        include 'views/permohonan_petugas/permohonan_ditolak/view.php';
    }

    private function generateBuktiSelesaiTCPDF($data, $skpd_data = null)
    {
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

    // Generate Bukti Ditolak PDF
    public function generateBuktiDitolakPDF()
    {
        if (!isset($_GET['id'])) {
            $_SESSION['error_message'] = 'ID permohonan tidak ditemukan';
            header('Location: index.php?controller=permohonanpetugas&action=permohonanDitolak');
            exit();
        }

        $id = intval($_GET['id']);
        $permohonan = $this->permohonanPetugasModel->getPermohonanById($id);

        if (!$permohonan) {
            $_SESSION['error_message'] = 'Permohonan tidak ditemukan';
            header('Location: index.php?controller=permohonanpetugas&action=permohonanDitolak');
            exit();
        }

        // Check if petugas has access to this permohonan
        $user_id = $_SESSION['user_id'];
        $petugas_skpd = $this->permohonanPetugasModel->getPetugasSKPDByUserId($user_id);

        if (!$petugas_skpd || $petugas_skpd['nama_skpd'] !== $permohonan['komponen_tujuan']) {
            $_SESSION['error_message'] = 'Anda tidak memiliki akses untuk melihat permohonan ini';
            header('Location: index.php?controller=permohonanpetugas&action=permohonanDitolak');
            exit();
        }

        // Get SKPD data for dynamic address
        $skpd_data = null;
        if (!empty($permohonan['komponen_tujuan'])) {
            $skpd_data = $this->permohonanPetugasModel->getSKPDDataByName($permohonan['komponen_tujuan']);
        }

        // Generate PDF
        $this->generateBuktiDitolakTCPDF($permohonan, $skpd_data);
    }

    // Generate Bukti Ditolak using TCPDF
    private function generateBuktiDitolakTCPDF($data, $skpd_data = null)
    {
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
}
