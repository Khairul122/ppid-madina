<?php
require_once 'models/PermohonanModel.php';
require_once 'models/UserModel.php';

class PermohonanController {
    private $permohonanModel;
    private $userModel;

    public function __construct() {
        global $database;
        $db = $database->getConnection();
        $this->permohonanModel = new PermohonanModel($db);
        $this->userModel = new UserModel($db);
    }

    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?controller=auth&action=login');
            exit();
        }

        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $status = isset($_GET['status']) ? $_GET['status'] : 'all';
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';

        $user_data = $this->userModel->getUserBiodata($_SESSION['user_id']);

        if (!empty($search)) {
            $permohonan_list = $this->permohonanModel->searchPermohonan($_SESSION['user_id'], $search, $limit, $offset);
            $total_records = $this->permohonanModel->countSearchResults($_SESSION['user_id'], $search);
        } else {
            $permohonan_list = $this->permohonanModel->getPermohonanByUserId($_SESSION['user_id'], $limit, $offset, $status);
            $total_records = $this->permohonanModel->countPermohonanByUserId($_SESSION['user_id'], $status);
        }

        $total_pages = ceil($total_records / $limit);
        $stats = $this->permohonanModel->getPermohonanStats($_SESSION['user_id']);

        $nama_lengkap = $user_data['nama_lengkap'] ?? $_SESSION['username'] ?? 'Pengguna';
        $status_pengguna = $user_data['status_pengguna'] ?? 'publik';
        $current_datetime = date('d F Y H:i');

        $success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
        $error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';

        unset($_SESSION['success_message']);
        unset($_SESSION['error_message']);

        include 'views/permohonan/index.php';
    }

    public function view() {
        if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
            header('Location: index.php?controller=permohonan&action=index');
            exit();
        }

        $id = $_GET['id'];
        $permohonan = $this->permohonanModel->getPermohonanById($id, $_SESSION['user_id']);

        if (!$permohonan) {
            $_SESSION['error_message'] = 'Permohonan tidak ditemukan';
            header('Location: index.php?controller=permohonan&action=index');
            exit();
        }

        $user_data = $this->userModel->getUserBiodata($_SESSION['user_id']);
        $nama_lengkap = $user_data['nama_lengkap'] ?? $_SESSION['username'] ?? 'Pengguna';
        $status_pengguna = $user_data['status_pengguna'] ?? 'publik';
        $current_datetime = date('d F Y H:i');

        include 'views/permohonan/view.php';
    }

    public function delete() {
        if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
            $_SESSION['error_message'] = 'Parameter tidak valid';
            header('Location: index.php?controller=permohonan&action=index');
            exit();
        }

        $id = $_GET['id'];
        $result = $this->permohonanModel->deletePermohonan($id, $_SESSION['user_id']);

        if ($result['success']) {
            $_SESSION['success_message'] = $result['message'];
        } else {
            $_SESSION['error_message'] = $result['message'];
        }

        header('Location: index.php?controller=permohonan&action=index');
        exit();
    }

    public function downloadFile() {
        if (!isset($_SESSION['user_id']) || !isset($_GET['file']) || !isset($_GET['id'])) {
            header('HTTP/1.0 404 Not Found');
            exit();
        }

        $permohonan_id = $_GET['id'];
        $filename = $_GET['file'];

        $permohonan = $this->permohonanModel->getPermohonanById($permohonan_id, $_SESSION['user_id']);

        if (!$permohonan) {
            header('HTTP/1.0 404 Not Found');
            exit();
        }

        $filepath = 'uploads/' . $filename;

        if (!file_exists($filepath) || strpos($filepath, 'uploads/') !== 0) {
            header('HTTP/1.0 404 Not Found');
            exit();
        }

        $user_data = $this->userModel->getUserBiodata($_SESSION['user_id']);
        $user_nik = $user_data['nik'] ?? '';

        if (!$user_nik || strpos($filename, $user_nik) !== 0) {
            header('HTTP/1.0 403 Forbidden');
            exit();
        }

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

    public function export() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?controller=auth&action=login');
            exit();
        }

        $format = isset($_GET['format']) ? $_GET['format'] : 'pdf';
        $status = isset($_GET['status']) ? $_GET['status'] : 'all';

        $permohonan_list = $this->permohonanModel->getPermohonanByUserId($_SESSION['user_id'], 1000, 0, $status);
        $user_data = $this->userModel->getUserBiodata($_SESSION['user_id']);

        if ($format === 'csv') {
            $this->exportCSV($permohonan_list, $user_data);
        } else {
            $_SESSION['error_message'] = 'Format export tidak didukung';
            header('Location: index.php?controller=permohonan&action=index');
            exit();
        }
    }

    private function exportCSV($data, $user_data) {
        $filename = 'permohonan_' . ($user_data['nik'] ?? $_SESSION['user_id']) . '_' . date('Y-m-d') . '.csv';

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: must-revalidate');

        $output = fopen('php://output', 'w');

        fputcsv($output, [
            'No Permohonan',
            'Tanggal Ajuan',
            'Tujuan Permohonan',
            'Unit Tujuan',
            'Judul Dokumen',
            'Status'
        ]);

        foreach ($data as $row) {
            fputcsv($output, [
                $row['no_permohonan'],
                date('d/m/Y', strtotime($row['created_at'] ?? 'now')),
                $row['tujuan_permohonan'],
                $row['komponen_tujuan'],
                $row['judul_dokumen'],
                $row['status'] ?? 'pending'
            ]);
        }

        fclose($output);
        exit();
    }

    public function submitLayananKepuasan() {
        if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error_message'] = 'Akses tidak valid';
            header('Location: index.php?controller=permohonan&action=index');
            exit();
        }

        // Validasi input
        $required_fields = ['id_permohonan', 'nama', 'umur', 'provinsi', 'kota', 'permohonan_informasi', 'rating'];
        foreach ($required_fields as $field) {
            if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
                $_SESSION['error_message'] = 'Semua field wajib diisi';
                header('Location: index.php?controller=permohonan&action=index');
                exit();
            }
        }

        // Validasi permohonan milik user
        $permohonan = $this->permohonanModel->getPermohonanById($_POST['id_permohonan'], $_SESSION['user_id']);
        if (!$permohonan) {
            $_SESSION['error_message'] = 'Permohonan tidak ditemukan';
            header('Location: index.php?controller=permohonan&action=index');
            exit();
        }

        // Validasi umur dan rating
        $umur = intval($_POST['umur']);
        $rating = intval($_POST['rating']);

        if ($umur < 17 || $umur > 100) {
            $_SESSION['error_message'] = 'Umur harus antara 17-100 tahun';
            header('Location: index.php?controller=permohonan&action=index');
            exit();
        }

        if ($rating < 1 || $rating > 5) {
            $_SESSION['error_message'] = 'Rating harus antara 1-5';
            header('Location: index.php?controller=permohonan&action=index');
            exit();
        }

        // Siapkan data untuk disimpan
        $data = [
            'id_permohonan' => $_POST['id_permohonan'],
            'nama' => trim($_POST['nama']),
            'umur' => $umur,
            'provinsi' => trim($_POST['provinsi']),
            'kota' => trim($_POST['kota']),
            'permohonan_informasi' => trim($_POST['permohonan_informasi']),
            'rating' => $rating
        ];

        // Simpan ke database
        $result = $this->permohonanModel->saveLayananKepuasan($data);

        if ($result['success']) {
            $_SESSION['success_message'] = $result['message'];
        } else {
            $_SESSION['error_message'] = $result['message'];
        }

        header('Location: index.php?controller=permohonan&action=index');
        exit();
    }
}
?>