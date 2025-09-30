<?php
require_once 'models/KeberatanModel.php';
require_once 'models/PermohonanModel.php';
require_once 'models/UserModel.php';

class KeberatanController {
    private $keberatanModel;
    private $permohonanModel;
    private $userModel;

    public function __construct() {
        global $database;
        $db = $database->getConnection();
        $this->keberatanModel = new KeberatanModel($db);
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

        $search = isset($_GET['search']) ? trim($_GET['search']) : '';

        $user_data = $this->userModel->getUserBiodata($_SESSION['user_id']);

        if (!empty($search)) {
            $keberatan_list = $this->keberatanModel->searchKeberatan($_SESSION['user_id'], $search, $limit, $offset);
            $total_records = $this->keberatanModel->countSearchResults($_SESSION['user_id'], $search);
        } else {
            $keberatan_list = $this->keberatanModel->getKeberatanByUserId($_SESSION['user_id'], $limit, $offset);
            $total_records = $this->keberatanModel->countKeberatanByUserId($_SESSION['user_id']);
        }

        $total_pages = ceil($total_records / $limit);
        $stats = $this->keberatanModel->getKeberatanStats($_SESSION['user_id']);

        $nama_lengkap = $user_data['nama_lengkap'] ?? $_SESSION['username'] ?? 'Pengguna';
        $status_pengguna = $user_data['status_pengguna'] ?? 'publik';
        $current_datetime = date('d F Y H:i');

        $success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
        $error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';

        unset($_SESSION['success_message']);
        unset($_SESSION['error_message']);

        include 'views/keberatan/index.php';
    }

    public function create() {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error_message'] = 'Anda harus login terlebih dahulu';
            header('Location: index.php?controller=auth&action=login');
            exit();
        }

        if ($_POST) {
            $result = $this->createKeberatan();

            if ($result['success']) {
                $_SESSION['success_message'] = $result['message'];
                header('Location: index.php?controller=keberatan&action=index');
            } else {
                $_SESSION['error_message'] = $result['message'];

                if (isset($_POST['id_permohonan']) && !empty($_POST['id_permohonan'])) {
                    header('Location: index.php?controller=permohonan&action=view&id=' . $_POST['id_permohonan']);
                } else {
                    header('Location: index.php?controller=permohonan&action=index');
                }
            }
            exit();
        }

        header('Location: index.php?controller=permohonan&action=index');
        exit();
    }

    private function createKeberatan() {
        $required_fields = [
            'id_permohonan',
            'alasan_keberatan'
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
                'message' => 'Field wajib tidak boleh kosong: ' . implode(', ', $missing_fields)
            ];
        }

        $permohonan_id = intval($_POST['id_permohonan']);
        $user_id = $_SESSION['user_id'];

        $permohonan = $this->permohonanModel->getPermohonanById($permohonan_id, $user_id);
        if (!$permohonan) {
            return [
                'success' => false,
                'message' => 'Permohonan tidak ditemukan atau tidak dapat diakses'
            ];
        }


        if ($this->keberatanModel->checkExistingKeberatan($user_id, $permohonan_id)) {
            return [
                'success' => false,
                'message' => 'Anda sudah mengajukan keberatan untuk permohonan ini'
            ];
        }

        if (strlen(trim($_POST['alasan_keberatan'])) < 20) {
            return [
                'success' => false,
                'message' => 'Alasan keberatan minimal 20 karakter'
            ];
        }

        $data = [
            'id_permohonan' => $permohonan_id,
            'id_users' => $user_id,
            'alasan_keberatan' => trim($_POST['alasan_keberatan']),
            'keterangan' => trim($_POST['keterangan'] ?? '')
        ];

        return $this->keberatanModel->createKeberatan($data);
    }

    public function view() {
        if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
            header('Location: index.php?controller=keberatan&action=index');
            exit();
        }

        $id = $_GET['id'];
        $keberatan = $this->keberatanModel->getKeberatanById($id, $_SESSION['user_id']);

        if (!$keberatan) {
            $_SESSION['error_message'] = 'Keberatan tidak ditemukan';
            header('Location: index.php?controller=keberatan&action=index');
            exit();
        }

        $user_data = $this->userModel->getUserBiodata($_SESSION['user_id']);
        $nama_lengkap = $user_data['nama_lengkap'] ?? $_SESSION['username'] ?? 'Pengguna';
        $status_pengguna = $user_data['status_pengguna'] ?? 'publik';
        $current_datetime = date('d F Y H:i');

        include 'views/keberatan/view.php';
    }

    public function getStats() {
        if (!isset($_SESSION['user_id'])) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Unauthorized']);
            exit();
        }

        $stats = $this->keberatanModel->getKeberatanStats($_SESSION['user_id']);

        header('Content-Type: application/json');
        echo json_encode($stats);
        exit();
    }

    public function export() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?controller=auth&action=login');
            exit();
        }

        $format = isset($_GET['format']) ? $_GET['format'] : 'csv';

        $keberatan_list = $this->keberatanModel->getKeberatanByUserId($_SESSION['user_id'], 1000, 0);
        $user_data = $this->userModel->getUserBiodata($_SESSION['user_id']);

        if ($format === 'csv') {
            $this->exportCSV($keberatan_list, $user_data);
        } else {
            $_SESSION['error_message'] = 'Format export tidak didukung';
            header('Location: index.php?controller=keberatan&action=index');
            exit();
        }
    }

    private function exportCSV($data, $user_data) {
        $filename = 'keberatan_' . ($user_data['nik'] ?? $_SESSION['user_id']) . '_' . date('Y-m-d') . '.csv';

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: must-revalidate');

        $output = fopen('php://output', 'w');

        fputcsv($output, [
            'No Permohonan',
            'Judul Dokumen',
            'Tanggal Keberatan',
            'Alasan Keberatan',
            'Keterangan',
            'Status'
        ]);

        foreach ($data as $row) {
            fputcsv($output, [
                $row['no_permohonan'],
                $row['judul_dokumen'],
                date('d/m/Y', strtotime($row['created_at'] ?? 'now')),
                $row['alasan_keberatan'],
                $row['keterangan'] ?? '',
                $row['status'] ?? 'pending'
            ]);
        }

        fclose($output);
        exit();
    }
}
?>