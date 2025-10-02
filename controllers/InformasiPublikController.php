<?php
require_once 'models/InformasiPublikModel.php';

class InformasiPublikController
{
    private $informasiModel;

    public function __construct()
    {
        global $database;
        $db = $database->getConnection();
        $this->informasiModel = new InformasiPublikModel($db);
    }

    // Method untuk menampilkan halaman index informasi publik
    public function index()
    {
        // Check if user is admin
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?controller=auth&action=login');
            exit();
        }

        // Get all informasi grouped by nama_informasi_publik
        $groupedInformasi = $this->informasiModel->getGroupedInformasi();
        $namaInformasiList = $this->informasiModel->getUniqueNamaInformasi();

        // Prepare categories for tabs
        $categories = [];
        foreach ($namaInformasiList as $item) {
            $categories[] = $item['nama_informasi_publik'];
        }

        $data = [
            'title' => 'Daftar Informasi Publik',
            'groupedInformasi' => $groupedInformasi,
            'categories' => $categories
        ];

        include 'views/informasi_publik/index.php';
    }

    // Method untuk menampilkan detail informasi publik
    public function viewDetail()
    {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

        if ($id <= 0) {
            header('Location: index.php');
            exit();
        }

        $informasi = $this->informasiModel->getInformasiById($id);

        if (!$informasi) {
            header('Location: index.php');
            exit();
        }

        $data = [
            'title' => $informasi['nama_informasi_publik'],
            'informasi' => $informasi
        ];

        include 'views/informasi_publik/detail_public.php';
    }

    // Method untuk menambah informasi baru
    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama_informasi_publik = trim($_POST['nama_informasi_publik']);
            $has_sub_informasi = isset($_POST['has_sub_informasi']) ? true : false;
            $sub_informasi_publik = $has_sub_informasi && !empty($_POST['sub_informasi_publik']) ? trim($_POST['sub_informasi_publik']) : null;
            $tags = isset($_POST['tags']) && !empty($_POST['tags']) ? trim($_POST['tags']) : null;
            $content_type = $_POST['content_type'];

            $isi = '';

            // Handle content type
            if ($content_type === 'file' && isset($_FILES['isi']) && $_FILES['isi']['error'] === UPLOAD_ERR_OK) {
                // Handle file upload
                $uploadResult = $this->informasiModel->handleFileUpload($_FILES['isi'], $nama_informasi_publik);

                if ($uploadResult['success']) {
                    $isi = $uploadResult['filepath'];
                } else {
                    $_SESSION['error'] = $uploadResult['message'];
                    header('Location: index.php?controller=informasiPublik&action=index');
                    exit();
                }
            } else {
                // Handle text content
                $isi = isset($_POST['isi_text']) ? trim($_POST['isi_text']) : '';
            }

            // Insert new informasi
            if ($this->informasiModel->insertInformasi($nama_informasi_publik, $sub_informasi_publik, $isi, $tags)) {
                $_SESSION['success'] = 'Data informasi publik berhasil ditambahkan!';
            } else {
                $_SESSION['error'] = 'Gagal menambahkan data informasi publik.';
            }

            header('Location: index.php?controller=informasiPublik&action=index');
            exit();
        }
    }

    // Method untuk update informasi
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_informasi = $_POST['id_informasi_publik'];
            $has_sub_informasi = isset($_POST['has_sub_informasi']) ? true : false;
            $sub_informasi_publik = $has_sub_informasi && !empty($_POST['sub_informasi_publik']) ? trim($_POST['sub_informasi_publik']) : null;
            $tags = isset($_POST['tags']) && !empty($_POST['tags']) ? trim($_POST['tags']) : null;

            // Update sub_informasi_publik
            $this->informasiModel->updateSubInformasi($id_informasi, $sub_informasi_publik);

            // Update tags
            $this->informasiModel->updateTags($id_informasi, $tags);

            // Check if file is uploaded
            if (isset($_FILES['isi']) && $_FILES['isi']['error'] === UPLOAD_ERR_OK) {
                $informasi = $this->informasiModel->getInformasiById($id_informasi);
                $uploadResult = $this->informasiModel->handleFileUpload($_FILES['isi'], $informasi['nama_informasi_publik']);

                if ($uploadResult['success']) {
                    // Delete old file if exists
                    if (!empty($informasi['isi']) && file_exists($informasi['isi'])) {
                        unlink($informasi['isi']);
                    }

                    // Update with new file
                    if ($this->informasiModel->updateInformasiFile($id_informasi, $uploadResult['filepath'])) {
                        $_SESSION['success'] = 'Data informasi publik berhasil diupdate!';
                    } else {
                        $_SESSION['error'] = 'Gagal mengupdate data informasi publik.';
                    }
                } else {
                    $_SESSION['error'] = $uploadResult['message'];
                }
            } else if (isset($_POST['isi_text'])) {
                // Update text content
                $isi_text = trim($_POST['isi_text']);
                if ($this->informasiModel->updateInformasiText($id_informasi, $isi_text)) {
                    $_SESSION['success'] = 'Data informasi publik berhasil diupdate!';
                } else {
                    $_SESSION['error'] = 'Gagal mengupdate data informasi publik.';
                }
            } else {
                $_SESSION['success'] = 'Sub informasi dan tags berhasil diupdate!';
            }

            header('Location: index.php?controller=informasiPublik&action=index');
            exit();
        }
    }

    // Method untuk hapus informasi
    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_informasi = $_POST['id_informasi_publik'];

            if ($this->informasiModel->deleteInformasi($id_informasi)) {
                $_SESSION['success'] = 'Data informasi publik berhasil dihapus!';
            } else {
                $_SESSION['error'] = 'Gagal menghapus data informasi publik.';
            }

            header('Location: index.php?controller=informasiPublik&action=index');
            exit();
        }
    }

    // Method untuk handle upload image dari TinyMCE
    public function upload_image()
    {
        // Security: Check if user is logged in and is admin
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
            exit();
        }

        // Check if file was uploaded
        if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            echo json_encode(['success' => false, 'message' => 'No file uploaded or upload error']);
            exit();
        }

        $file = $_FILES['file'];

        // Validate file type - Allow images and PDFs
        $allowedTypes = [
            'image/jpeg',
            'image/jpg',
            'image/png',
            'image/gif',
            'image/bmp',
            'image/webp',
            'application/pdf'
        ];
        $fileType = mime_content_type($file['tmp_name']);

        if (!in_array($fileType, $allowedTypes)) {
            echo json_encode(['success' => false, 'message' => 'Invalid file type. Only images and PDF are allowed.']);
            exit();
        }

        // Validate file size (max 10MB for PDF, 5MB for images)
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $maxSize = ($extension === 'pdf') ? 10 * 1024 * 1024 : 5 * 1024 * 1024;

        if ($file['size'] > $maxSize) {
            $maxSizeMB = $maxSize / 1024 / 1024;
            echo json_encode(['success' => false, 'message' => 'File too large. Maximum size is ' . $maxSizeMB . 'MB.']);
            exit();
        }

        // Create upload directory based on file type
        if ($extension === 'pdf') {
            $uploadDir = 'uploads/informasi_documents/';
        } else {
            $uploadDir = 'uploads/informasi_images/';
        }

        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Generate unique filename
        $filename = time() . '_' . uniqid() . '.' . $extension;
        $targetPath = $uploadDir . $filename;

        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            echo json_encode([
                'success' => true,
                'url' => $targetPath,
                'location' => $targetPath
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to save file']);
        }
        exit();
    }
}
