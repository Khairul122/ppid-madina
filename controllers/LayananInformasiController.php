<?php
require_once 'models/LayananInformasiModel.php';

class LayananInformasiController
{
    private $layananModel;

    public function __construct()
    {
        global $database;
        $db = $database->getConnection();
        $this->layananModel = new LayananInformasiModel($db);
    }

    // Method untuk menampilkan halaman index layanan informasi
    public function index()
    {
        // Check if user is admin
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?controller=auth&action=login');
            exit();
        }

        // Get all layanan grouped by nama_layanan
        $groupedLayanan = $this->layananModel->getGroupedLayanan();
        $namaLayananList = $this->layananModel->getUniqueNamaLayanan();

        // Prepare categories for tabs
        $categories = [];
        foreach ($namaLayananList as $item) {
            $categories[] = $item['nama_layanan'];
        }

        $data = [
            'title' => 'Layanan Informasi Publik',
            'groupedLayanan' => $groupedLayanan,
            'categories' => $categories
        ];

        include 'views/layanan_informasi/index.php';
    }

    // Method untuk menampilkan detail layanan publik
    public function viewDetail()
    {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

        if ($id <= 0) {
            header('Location: index.php');
            exit();
        }

        $layanan = $this->layananModel->getLayananById($id);

        if (!$layanan) {
            header('Location: index.php');
            exit();
        }

        $data = [
            'title' => $layanan['nama_layanan'],
            'layanan' => $layanan
        ];

        include 'views/layanan_informasi/detail_public.php';
    }

    // Method untuk menambah layanan baru
    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama_layanan = trim($_POST['nama_layanan']);
            $has_sub_layanan = isset($_POST['has_sub_layanan']) ? true : false;
            $sub_layanan = $has_sub_layanan && !empty($_POST['sub_layanan']) ? trim($_POST['sub_layanan']) : null;
            $has_sub_layanan_2 = isset($_POST['has_sub_layanan_2']) ? true : false;
            $sub_layanan_2 = $has_sub_layanan_2 && !empty($_POST['sub_layanan_2']) ? trim($_POST['sub_layanan_2']) : null;
            $content_type = $_POST['content_type'];

            $isi = '';

            // Handle content type
            if ($content_type === 'file' && isset($_FILES['isi']) && $_FILES['isi']['error'] === UPLOAD_ERR_OK) {
                // Handle file upload
                $uploadResult = $this->layananModel->handleFileUpload($_FILES['isi'], $nama_layanan);

                if ($uploadResult['success']) {
                    $isi = $uploadResult['filepath'];
                } else {
                    $_SESSION['error'] = $uploadResult['message'];
                    header('Location: index.php?controller=layananInformasi&action=index');
                    exit();
                }
            } else {
                // Handle text content
                $isi = isset($_POST['isi_text']) ? trim($_POST['isi_text']) : '';
            }

            // Insert new layanan
            if ($this->layananModel->insertLayanan($nama_layanan, $sub_layanan, $sub_layanan_2, $isi)) {
                $_SESSION['success'] = 'Data layanan informasi berhasil ditambahkan!';
            } else {
                $_SESSION['error'] = 'Gagal menambahkan data layanan informasi.';
            }

            header('Location: index.php?controller=layananInformasi&action=index');
            exit();
        }
    }

    // Method untuk update layanan
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_layanan = $_POST['id_layanan'];
            $has_sub_layanan = isset($_POST['has_sub_layanan']) ? true : false;
            $sub_layanan = $has_sub_layanan && !empty($_POST['sub_layanan']) ? trim($_POST['sub_layanan']) : null;
            $has_sub_layanan_2 = isset($_POST['has_sub_layanan_2']) ? true : false;
            $sub_layanan_2 = $has_sub_layanan_2 && !empty($_POST['sub_layanan_2']) ? trim($_POST['sub_layanan_2']) : null;

            // Update sub_layanan dan sub_layanan_2
            $this->layananModel->updateSubLayanan($id_layanan, $sub_layanan, $sub_layanan_2);

            // Check if file is uploaded
            if (isset($_FILES['isi']) && $_FILES['isi']['error'] === UPLOAD_ERR_OK) {
                $layanan = $this->layananModel->getLayananById($id_layanan);
                $uploadResult = $this->layananModel->handleFileUpload($_FILES['isi'], $layanan['nama_layanan']);

                if ($uploadResult['success']) {
                    // Delete old file if exists
                    if (!empty($layanan['isi']) && file_exists($layanan['isi'])) {
                        unlink($layanan['isi']);
                    }

                    // Update with new file
                    if ($this->layananModel->updateLayananFile($id_layanan, $uploadResult['filepath'])) {
                        $_SESSION['success'] = 'Data layanan informasi berhasil diupdate!';
                    } else {
                        $_SESSION['error'] = 'Gagal mengupdate data layanan informasi.';
                    }
                } else {
                    $_SESSION['error'] = $uploadResult['message'];
                }
            } else if (isset($_POST['isi_text'])) {
                // Update text content
                $isi_text = trim($_POST['isi_text']);
                if ($this->layananModel->updateLayananText($id_layanan, $isi_text)) {
                    $_SESSION['success'] = 'Data layanan informasi berhasil diupdate!';
                } else {
                    $_SESSION['error'] = 'Gagal mengupdate data layanan informasi.';
                }
            } else {
                $_SESSION['success'] = 'Sub layanan berhasil diupdate!';
            }

            header('Location: index.php?controller=layananInformasi&action=index');
            exit();
        }
    }

    // Method untuk hapus layanan
    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_layanan = $_POST['id_layanan'];

            if ($this->layananModel->deleteLayanan($id_layanan)) {
                $_SESSION['success'] = 'Data layanan informasi berhasil dihapus!';
            } else {
                $_SESSION['error'] = 'Gagal menghapus data layanan informasi.';
            }

            header('Location: index.php?controller=layananInformasi&action=index');
            exit();
        }
    }

    // Method untuk handle upload image dan file dari TinyMCE
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
            $uploadDir = 'uploads/layanan_documents/';
        } else {
            $uploadDir = 'uploads/layanan_images/';
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
