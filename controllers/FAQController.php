<?php
require_once 'models/FAQModel.php';

class FAQController {
    private $faqModel;

    public function __construct() {
        global $database;
        $db = $database->getConnection();
        $this->faqModel = new FAQModel($db);
    }

    // Method untuk menampilkan halaman admin FAQ (Edit Only)
    public function index() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?controller=auth&action=login');
            exit();
        }

        include 'views/faq/index.php';
    }

    // Method untuk menampilkan FAQ untuk publik (Single View)
    public function public() {
        // Data untuk view
        $pageInfo = [
            'title' => 'FAQ - PPID Mandailing Natal',
            'description' => 'Frequently Asked Questions (FAQ) - Pertanyaan yang Sering Diajukan'
        ];

        include 'views/faq/public.php';
    }

    // Method untuk update FAQ (Single FAQ Edit)
    public function update() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?controller=auth&action=login');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $isi = $_POST['isi_text'] ?? '';
            $penulis = $_POST['penulis'] ?? '';
            $tags = $_POST['tags'] ?? '';

            // Validasi
            if (empty($isi) || empty($penulis)) {
                $_SESSION['error'] = 'Isi dan Penulis tidak boleh kosong!';
                header('Location: index.php?controller=faq&action=index');
                exit();
            }

            // Update ke database
            if ($this->faqModel->updateSingle($isi, $penulis, $tags)) {
                $_SESSION['success'] = 'FAQ berhasil diupdate!';
            } else {
                $_SESSION['error'] = 'Gagal update FAQ!';
            }

            header('Location: index.php?controller=faq&action=index');
            exit();
        }
    }

    // Method untuk upload image (untuk TinyMCE)
    public function upload_image() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit();
        }

        if (!isset($_FILES['file'])) {
            echo json_encode(['success' => false, 'message' => 'No file uploaded']);
            exit();
        }

        $file = $_FILES['file'];
        $uploadDir = 'uploads/faq_images/';

        // Create directory if not exists
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Validate file type
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($file['type'], $allowedTypes)) {
            echo json_encode(['success' => false, 'message' => 'Invalid file type. Only images are allowed.']);
            exit();
        }

        // Validate file size (max 5MB)
        if ($file['size'] > 5 * 1024 * 1024) {
            echo json_encode(['success' => false, 'message' => 'File size exceeds 5MB limit.']);
            exit();
        }

        // Generate unique filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '_' . time() . '.' . $extension;
        $filePath = $uploadDir . $filename;

        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            echo json_encode([
                'success' => true,
                'url' => $filePath,
                'location' => $filePath
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to move uploaded file.']);
        }
        exit();
    }
}
?>
