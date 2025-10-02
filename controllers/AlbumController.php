<?php
require_once 'models/AlbumModel.php';

class AlbumController {
    private $albumModel;
    private $uploadPath = 'uploads/album/';

    public function __construct() {
        global $database;
        $db = null;

        if (isset($database)) {
            $db = $database->getConnection();
        }

        $this->albumModel = new AlbumModel($db);

        // Buat folder upload jika belum ada
        if (!file_exists($this->uploadPath)) {
            mkdir($this->uploadPath, 0777, true);
        }
    }

    // Method untuk menampilkan halaman index album
    public function index() {
        // Cek apakah pengguna adalah admin atau petugas
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'petugas'])) {
            header('Location: index.php?controller=auth&action=login');
            exit();
        }

        try {
            $kategoriFilter = $_GET['kategori'] ?? 'all';
            
            // Ensure $kategoriFilter is a string to prevent TypeError
            if (is_array($kategoriFilter)) {
                $kategoriFilter = 'all'; // default value if it's an array
            }

            if ($kategoriFilter === 'all') {
                $albumList = $this->albumModel->getAllAlbum();
            } else {
                $albumList = $this->albumModel->getAlbumByKategori($kategoriFilter);
            }

            // Set page info
            $pageInfo = [
                'title' => 'Album - PPID Mandailing Natal',
                'description' => 'Pengelolaan album foto dan video PPID Mandailing Natal',
                'keywords' => 'PPID, Mandailing Natal, Album, Foto, Video'
            ];

            // Include view
            include 'views/album/index.php';

        } catch (Exception $e) {
            error_log("Error in AlbumController::index: " . $e->getMessage());

            $albumList = [];
            $kategoriFilter = 'all';

            $pageInfo = [
                'title' => 'Album - PPID Mandailing Natal',
                'description' => 'Pengelolaan album foto dan video PPID Mandailing Natal',
                'keywords' => 'PPID, Mandailing Natal, Album, Foto, Video'
            ];

            include 'views/album/index.php';
        }
    }

    // Method untuk menampilkan form
    public function form() {
        // Cek apakah pengguna adalah admin atau petugas
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'petugas'])) {
            header('Location: index.php?controller=auth&action=login');
            exit();
        }

        $album = null;
        $action = 'create';

        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            $album = $this->albumModel->getAlbumById($id);
            if (!$album) {
                header('Location: index.php?controller=album&action=index');
                exit();
            }
            $action = 'update';
        }

        // Set page info
        $pageInfo = [
            'title' => ($action === 'create' ? 'Tambah' : 'Edit') . ' Album - PPID Mandailing Natal',
            'description' => 'Form ' . ($action === 'create' ? 'tambah' : 'edit') . ' album',
            'keywords' => 'PPID, Mandailing Natal, Album, Form'
        ];

        // Include view
        include 'views/album/form.php';
    }

    // Method untuk menyimpan data album
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

            $kategori = $_POST['kategori'] ?? '';
            $nama_album = trim($_POST['nama_album'] ?? '');
            $id = $_POST['id'] ?? null;

            // Validasi input
            if (empty($kategori) || !in_array($kategori, ['foto', 'video'])) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Kategori tidak valid.'
                ]);
                exit();
            }

            if (empty($nama_album)) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Nama album tidak boleh kosong.'
                ]);
                exit();
            }

            $uploadedFile = null;

            // Handle file upload
            if (isset($_FILES['upload']) && $_FILES['upload']['error'] === UPLOAD_ERR_OK) {
                $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'video/mp4', 'video/avi', 'video/mov'];
                $maxFileSize = 50 * 1024 * 1024; // 50MB

                if (!in_array($_FILES['upload']['type'], $allowedTypes)) {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Tipe file tidak diizinkan. Hanya file gambar (JPG, PNG, GIF) atau video (MP4, AVI, MOV) yang diperbolehkan.'
                    ]);
                    exit();
                }

                if ($_FILES['upload']['size'] > $maxFileSize) {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Ukuran file terlalu besar. Maksimal 50MB.'
                    ]);
                    exit();
                }

                $fileExtension = pathinfo($_FILES['upload']['name'], PATHINFO_EXTENSION);
                $fileName = uniqid() . '_' . time() . '.' . $fileExtension;
                $targetPath = $this->uploadPath . $fileName;

                if (move_uploaded_file($_FILES['upload']['tmp_name'], $targetPath)) {
                    $uploadedFile = $targetPath;

                    // Hapus file lama jika update
                    if ($id) {
                        $existingAlbum = $this->albumModel->getAlbumById($id);
                        if ($existingAlbum && !empty($existingAlbum['upload']) && file_exists($existingAlbum['upload'])) {
                            unlink($existingAlbum['upload']);
                        }
                    }
                } else {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Gagal mengupload file.'
                    ]);
                    exit();
                }
            }

            $result = false;
            if ($id) {
                // Update
                $existingAlbum = $this->albumModel->getAlbumById($id);
                $finalUpload = $uploadedFile ?? ($existingAlbum['upload'] ?? null);
                $result = $this->albumModel->updateAlbum($id, $kategori, $nama_album, $finalUpload);
            } else {
                // Create
                $result = $this->albumModel->createAlbum($kategori, $nama_album, $uploadedFile);
            }

            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Data album berhasil ' . ($id ? 'diperbarui' : 'ditambahkan') . '.',
                    'redirect' => 'index.php?controller=album&action=index'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Gagal menyimpan data album.'
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

    // Method untuk menghapus data album (AJAX)
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
            $existingRecord = $this->albumModel->getAlbumById($id);
            if (!$existingRecord) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Data album tidak ditemukan.'
                ]);
                exit();
            }

            // Hapus file jika ada
            if (!empty($existingRecord['upload']) && file_exists($existingRecord['upload'])) {
                unlink($existingRecord['upload']);
            }

            $result = $this->albumModel->deleteAlbum($id);

            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Data album berhasil dihapus.',
                    'redirect' => 'index.php?controller=album&action=index'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Gagal menghapus data album. Silakan coba lagi.'
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

    // Method untuk menampilkan galeri publik
    public function public() {
        try {
            $kategori = $_GET['kategori'] ?? 'foto';
            
            // Ensure $kategori is a string to prevent TypeError
            if (is_array($kategori)) {
                $kategori = 'foto'; // default value if it's an array
            }

            // Validasi kategori
            if (!in_array($kategori, ['foto', 'video'])) {
                $kategori = 'foto';
            }

            // Ambil data album berdasarkan kategori
            $albumList = $this->albumModel->getAlbumByKategori($kategori);

            // Set page info
            $pageInfo = [
                'title' => 'Galeri ' . ucfirst($kategori) . ' - PPID Mandailing Natal',
                'description' => 'Galeri ' . $kategori . ' kegiatan PPID Mandailing Natal',
                'keywords' => 'PPID, Mandailing Natal, Galeri, ' . ucfirst($kategori)
            ];

            // Include view
            include 'views/album/public.php';

        } catch (Exception $e) {
            error_log("Error in AlbumController::public: " . $e->getMessage());

            $albumList = [];
            $kategori = 'foto';

            $pageInfo = [
                'title' => 'Galeri - PPID Mandailing Natal',
                'description' => 'Galeri kegiatan PPID Mandailing Natal',
                'keywords' => 'PPID, Mandailing Natal, Galeri'
            ];

            include 'views/album/public.php';
        }
    }
}
?>
