<?php
require_once 'models/AjukanPermohonanModel.php';
require_once 'models/UserModel.php';

class AjukanPermohonanController {
    private $permohonanModel;
    private $userModel;

    public function __construct() {
        global $database;
        $db = $database->getConnection();
        $this->permohonanModel = new AjukanPermohonanModel($db);
        $this->userModel = new UserModel($db);
    }

    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?controller=auth&action=login');
            exit();
        }

        // Get user biodata for NIK (needed for file naming)
        $user_data = $this->userModel->getUserBiodata($_SESSION['user_id']);

        // Store NIK in session for file upload naming
        if (isset($user_data['nik'])) {
            $_SESSION['user_nik'] = $user_data['nik'];
        }

        // Get SKPD data for dropdown
        require_once 'models/SKPDModel.php';
        global $database;
        $db = $database->getConnection();
        $skpdModel = new SKPDModel($db);
        $skpd_list = $skpdModel->getAllSKPD();

        // Handle form submission
        $error = '';
        $success = '';

        if ($_POST) {
            $result = $this->createPermohonan();

            if ($result['success']) {
                $success = 'Permohonan berhasil diajukan dengan nomor: ' . $result['no_permohonan'];
            } else {
                $error = $result['message'];
            }
        }

        // Get user's existing permohonan for display
        $permohonan_list = $this->permohonanModel->getPermohonanByUserId($_SESSION['user_id']);

        // Pass data to view
        $nama_lengkap = $user_data['nama_lengkap'] ?? $_SESSION['username'] ?? 'Pengguna';
        $status_pengguna = $user_data['status_pengguna'] ?? 'publik';
        $current_datetime = date('d F Y H:i');
        $skpd_list = $skpd_list; // Pastikan $skpd_list tersedia untuk view

        include 'views/ajukan_permohonan/index.php';
    }

    public function create() {
        // Alias for index method
        $this->index();
    }

    private function createPermohonan() {
        // Validate required fields
        $required_fields = [
            'tujuan_permohonan',
            'komponen_tujuan',
            'judul_dokumen',
            'tujuan_penggunaan_informasi'
        ];

        $missing_fields = [];
        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                $missing_fields[] = $field;
            }
        }

        if (!empty($missing_fields)) {
            return [
                'success' => false,
                'message' => 'Field berikut wajib diisi: ' . implode(', ', $missing_fields)
            ];
        }

        // Validate file uploads
        $upload_errors = [];

        // Check foto identitas (required)
        if (!isset($_FILES['upload_foto_identitas']) || $_FILES['upload_foto_identitas']['error'] !== UPLOAD_ERR_OK) {
            $upload_errors[] = 'Upload foto identitas wajib dilakukan';
        }

        // Check data pendukung (optional)
        if (isset($_FILES['upload_data_pendukung']) && $_FILES['upload_data_pendukung']['error'] !== UPLOAD_ERR_OK && $_FILES['upload_data_pendukung']['error'] !== UPLOAD_ERR_NO_FILE) {
            $upload_errors[] = 'Error upload data pendukung';
        }

        if (!empty($upload_errors)) {
            return [
                'success' => false,
                'message' => implode('. ', $upload_errors)
            ];
        }

        // Prepare data for insertion
        $data = [
            'id_user' => $_SESSION['user_id'],
            'tujuan_permohonan' => trim($_POST['tujuan_permohonan']),
            'komponen_tujuan' => trim($_POST['komponen_tujuan']),
            'judul_dokumen' => trim($_POST['judul_dokumen']),
            'tujuan_penggunaan_informasi' => trim($_POST['tujuan_penggunaan_informasi'])
        ];

        // Create permohonan
        return $this->permohonanModel->createPermohonan($data);
    }

    public function view() {
        if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
            header('Location: index.php?controller=ajukan_permohonan&action=index');
            exit();
        }

        $id = $_GET['id'];
        $permohonan = $this->permohonanModel->getPermohonanById($id);

        // Check if permohonan exists and belongs to current user
        if (!$permohonan || $permohonan['id_user'] != $_SESSION['user_id']) {
            header('Location: index.php?controller=ajukan_permohonan&action=index');
            exit();
        }

        // Get user data for consistent styling
        $user_data = $this->userModel->getUserBiodata($_SESSION['user_id']);
        $nama_lengkap = $user_data['nama_lengkap'] ?? $_SESSION['username'] ?? 'Pengguna';
        $status_pengguna = $user_data['status_pengguna'] ?? 'publik';
        $current_datetime = date('d F Y H:i');

        include 'views/ajukan_permohonan/view.php';
    }

    public function delete() {
        if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
            header('Location: index.php?controller=ajukan_permohonan&action=index');
            exit();
        }

        $id = $_GET['id'];
        $permohonan = $this->permohonanModel->getPermohonanById($id);

        // Check if permohonan exists and belongs to current user
        if (!$permohonan || $permohonan['id_user'] != $_SESSION['user_id']) {
            $_SESSION['error_message'] = 'Permohonan tidak ditemukan atau tidak dapat dihapus';
            header('Location: index.php?controller=ajukan_permohonan&action=index');
            exit();
        }

        // Only allow deletion if status is pending (or if status column doesn't exist, allow deletion)
        if (isset($permohonan['status']) && $permohonan['status'] !== 'pending' && $permohonan['status'] !== '') {
            $_SESSION['error_message'] = 'Permohonan yang sudah diproses tidak dapat dihapus';
            header('Location: index.php?controller=ajukan_permohonan&action=index');
            exit();
        }

        // Delete permohonan
        if ($this->permohonanModel->deletePermohonan($id)) {
            $_SESSION['success_message'] = 'Permohonan berhasil dihapus';
        } else {
            $_SESSION['error_message'] = 'Gagal menghapus permohonan';
        }

        header('Location: index.php?controller=ajukan_permohonan&action=index');
        exit();
    }

    public function downloadFile() {
        if (!isset($_SESSION['user_id']) || !isset($_GET['file'])) {
            header('HTTP/1.0 404 Not Found');
            exit();
        }

        $filename = $_GET['file'];
        $filepath = 'uploads/' . $filename;

        // Security check - make sure file exists and is in uploads directory
        if (!file_exists($filepath) || strpos($filepath, 'uploads/') !== 0) {
            header('HTTP/1.0 404 Not Found');
            exit();
        }

        // Additional security - check if user owns this file
        // Extract NIK from filename and compare with user's NIK
        $user_data = $this->userModel->getUserBiodata($_SESSION['user_id']);
        $user_nik = $user_data['nik'] ?? '';

        if (!$user_nik || strpos($filename, $user_nik) !== 0) {
            header('HTTP/1.0 403 Forbidden');
            exit();
        }

        // Set headers for file download
        $file_extension = pathinfo($filepath, PATHINFO_EXTENSION);
        $content_types = [
            'pdf' => 'application/pdf',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        ];

        $content_type = $content_types[$file_extension] ?? 'application/octet-stream';

        header('Content-Type: ' . $content_type);
        header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
        header('Content-Length: ' . filesize($filepath));
        header('Cache-Control: must-revalidate');
        header('Pragma: public');

        // Output file
        ob_clean();
        flush();
        readfile($filepath);
        exit();
    }

    public function getStats() {
        if (!isset($_SESSION['user_id'])) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Unauthorized']);
            exit();
        }

        $stats = $this->permohonanModel->getPermohonanStats($_SESSION['user_id']);

        header('Content-Type: application/json');
        echo json_encode($stats);
        exit();
    }
}
?>