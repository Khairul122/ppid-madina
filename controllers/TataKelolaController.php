<?php
require_once 'models/TataKelolaModel.php';

class TataKelolaController {
    private $tataKelolaModel;

    public function __construct() {
        global $database;
        $db = null;

        if (isset($database)) {
            $db = $database->getConnection();
        }

        $this->tataKelolaModel = new TataKelolaModel($db);
    }

    // Method untuk menampilkan halaman index tata kelola
    public function index() {
        // Cek apakah pengguna adalah admin atau petugas
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'petugas'])) {
            header('Location: index.php?controller=auth&action=login');
            exit();
        }

        try {
            $tataKelolaList = $this->tataKelolaModel->getAllTataKelola();

            // Set page info
            $pageInfo = [
                'title' => 'Tata Kelola - PPID Mandailing Natal',
                'description' => 'Pengelolaan data tata kelola PPID Mandailing Natal',
                'keywords' => 'PPID, Mandailing Natal, Tata Kelola, Pengelolaan'
            ];

            // Include view
            include 'views/tata_kelola/index.php';

        } catch (Exception $e) {
            // Log error jika terjadi masalah
            error_log("Error in TataKelolaController::index: " . $e->getMessage());

            // Fallback ke data minimal jika terjadi error
            $tataKelolaList = [];

            $pageInfo = [
                'title' => 'Tata Kelola - PPID Mandailing Natal',
                'description' => 'Pengelolaan data tata kelola PPID Mandailing Natal',
                'keywords' => 'PPID, Mandailing Natal, Tata Kelola, Pengelolaan'
            ];

            include 'views/tata_kelola/index.php';
        }
    }

    // Method untuk menampilkan form
    public function form() {
        // Cek apakah pengguna adalah admin atau petugas
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'petugas'])) {
            header('Location: index.php?controller=auth&action=login');
            exit();
        }

        $tataKelola = null;
        $action = 'create';
        
        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            $tataKelola = $this->tataKelolaModel->getTataKelolaById($id);
            if (!$tataKelola) {
                header('Location: index.php?controller=tataKelola&action=index');
                exit();
            }
            $action = 'update';
        }

        // Set page info
        $pageInfo = [
            'title' => ($action === 'create' ? 'Tambah' : 'Edit') . ' Tata Kelola - PPID Mandailing Natal',
            'description' => 'Form ' . ($action === 'create' ? 'tambah' : 'edit') . ' tata kelola',
            'keywords' => 'PPID, Mandailing Natal, Tata Kelola, Form'
        ];

        // Include view
        include 'views/tata_kelola/form.php';
    }

    // Method untuk menyimpan data tata kelola
    public function save() {
        header('Content-Type: application/json');

        try {
            // Cek apakah pengguna adalah admin atau petugas
            if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'petugas'])) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Akses ditolak. Anda tidak memiliki izin untuk menyimpan data.'
                ]);
                exit();
            }

            $nama_tata_kelola = trim($_POST['nama_tata_kelola'] ?? '');
            $link = trim($_POST['link'] ?? '');
            $id = $_POST['id'] ?? null;

            // Validasi input
            if (empty($nama_tata_kelola)) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Nama tata kelola tidak boleh kosong.'
                ]);
                exit();
            }

            $result = false;
            if ($id) {
                // Update
                $result = $this->tataKelolaModel->updateTataKelola($id, $nama_tata_kelola, $link);
            } else {
                // Create
                $result = $this->tataKelolaModel->createTataKelola($nama_tata_kelola, $link);
            }

            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Data tata kelola berhasil ' . ($id ? 'diperbarui' : 'ditambahkan') . '.',
                    'redirect' => 'index.php?controller=tataKelola&action=index'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Gagal menyimpan data tata kelola.'
                ]);
            }

        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
        exit();
    }

    // Method untuk menghapus data tata kelola (AJAX)
    public function delete() {
        header('Content-Type: application/json');

        try {
            // Cek apakah pengguna adalah admin atau petugas
            if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'petugas'])) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Akses ditolak. Anda tidak memiliki izin untuk menghapus data.'
                ]);
                exit();
            }

            $id = (int)($_GET['id'] ?? 0);

            if ($id <= 0) {
                echo json_encode([
                    'success' => false,
                    'message' => 'ID tidak valid.'
                ]);
                exit();
            }

            // Check if the record exists before attempting deletion
            $existingRecord = $this->tataKelolaModel->getTataKelolaById($id);
            if (!$existingRecord) {
                error_log("TataKelolaController::delete - Record not found for ID: $id");
                echo json_encode([
                    'success' => false,
                    'message' => 'Data tata kelola tidak ditemukan.'
                ]);
                exit();
            }

            error_log("TataKelolaController::delete - Attempting to delete ID: $id");
            $result = $this->tataKelolaModel->deleteTataKelola($id);
            error_log("TataKelolaController::delete - Deletion result for ID $id: " . ($result ? 'true' : 'false'));

            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Data tata kelola berhasil dihapus.',
                    'redirect' => 'index.php?controller=tataKelola&action=index'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Gagal menghapus data tata kelola. Silakan coba lagi.'
                ]);
            }

        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
        exit();
    }
}
?>