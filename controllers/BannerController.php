<?php
require_once 'models/BannerModel.php';

class BannerController {
    private $bannerModel;

    public function __construct() {
        global $database;
        $db = $database->getConnection();
        $this->bannerModel = new BannerModel($db);
    }

    // Method untuk menampilkan halaman index banner
    public function index() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?controller=auth&action=login');
            exit();
        }

        // Parameter untuk search dan pagination
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10; // Data per halaman
        $offset = ($page - 1) * $limit;

        // Ambil data banner
        $banners = $this->bannerModel->getAllBanner($search, $limit, $offset);
        $totalCount = $this->bannerModel->getTotalCount($search);
        $totalPages = ceil($totalCount / $limit);

        // Data untuk view
        $data = [
            'banners' => $banners,
            'search' => $search,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalCount' => $totalCount,
            'limit' => $limit
        ];

        include 'views/banner/index.php';
    }

    // Method untuk menampilkan form tambah banner
    public function create() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?controller=auth&action=login');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->store();
        } else {
            $data = ['action' => 'create'];
            include 'views/banner/form.php';
        }
    }

    // Method untuk menyimpan banner baru
    public function store() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?controller=auth&action=login');
            exit();
        }

        $judul = $_POST['judul'] ?? '';
        $teks = $_POST['teks'] ?? '';
        $urutan = $_POST['urutan'] ?? 0;
        $upload = '';

        // Handle upload file
        if (!empty($_FILES['upload']['name'])) {
            $upload = $this->handleFileUpload($_FILES['upload']);
        }

        // Simpan ke database
        if ($this->bannerModel->create($judul, $teks, $urutan, $upload)) {
            $_SESSION['success'] = 'Banner berhasil ditambahkan!';
        } else {
            $_SESSION['error'] = 'Gagal menambah banner!';
        }

        header('Location: index.php?controller=banner');
        exit();
    }

    // Method untuk menampilkan form edit banner
    public function edit() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?controller=auth&action=login');
            exit();
        }

        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: index.php?controller=banner');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->update($id);
        } else {
            $banner = $this->bannerModel->getBannerById($id);
            if (!$banner) {
                $_SESSION['error'] = 'Banner tidak ditemukan!';
                header('Location: index.php?controller=banner');
                exit();
            }

            $data = [
                'action' => 'edit',
                'banner' => $banner
            ];
            include 'views/banner/form.php';
        }
    }

    // Method untuk update banner
    public function update($id) {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?controller=auth&action=login');
            exit();
        }

        $judul = $_POST['judul'] ?? '';
        $teks = $_POST['teks'] ?? '';
        $urutan = $_POST['urutan'] ?? 0;

        // Ambil data banner lama
        $oldBanner = $this->bannerModel->getBannerById($id);
        $upload = $oldBanner['upload']; // Default gunakan upload lama

        // Handle upload file baru
        $updateFile = false;
        if (!empty($_FILES['upload']['name'])) {
            $newUpload = $this->handleFileUpload($_FILES['upload']);
            if ($newUpload !== '') {
                $upload = $newUpload;
                $updateFile = true;
                
                // Hapus file lama jika ada
                if (!empty($oldBanner['upload']) && file_exists($oldBanner['upload'])) {
                    unlink($oldBanner['upload']);
                }
            }
        }

        // Validasi
        if (empty($judul) || empty($teks)) {
            $_SESSION['error'] = 'Judul dan teks tidak boleh kosong!';
            header('Location: index.php?controller=banner&action=edit&id=' . $id);
            exit();
        }


        // Update ke database
        $result = $this->bannerModel->update($id, $judul, $teks, $urutan, $updateFile ? $upload : null);
        if ($result) {
            $_SESSION['success'] = 'Banner berhasil diupdate!';
        } else {
            $_SESSION['error'] = 'Gagal update banner!';
        }

        header('Location: index.php?controller=banner');
        exit();
    }

    // Method untuk hapus banner
    public function delete() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?controller=auth&action=login');
            exit();
        }

        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: index.php?controller=banner');
            exit();
        }

        $banner = $this->bannerModel->getBannerById($id);
        if ($banner && !empty($banner['upload']) && file_exists($banner['upload'])) {
            unlink($banner['upload']);
        }

        if ($this->bannerModel->delete($id)) {
            $_SESSION['success'] = 'Banner berhasil dihapus!';
        } else {
            $_SESSION['error'] = 'Gagal hapus banner!';
        }

        header('Location: index.php?controller=banner');
        exit();
    }

    // Method untuk export ke Excel
    public function export() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?controller=auth&action=login');
            exit();
        }

        $banners = $this->bannerModel->getAllBanner();

        // Set header untuk download Excel
        $filename = "data_banner_" . date('Y-m-d_H-i-s') . ".xls";
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");

        // Output Excel content
        echo "<table border='1'>";
        echo "<tr>";
        echo "<th>ID</th>";
        echo "<th>Upload</th>";
        echo "<th>Judul</th>";
        echo "<th>Teks</th>";
        echo "<th>Urutan</th>";
        echo "<th>Created At</th>";
        echo "<th>Updated At</th>";
        echo "</tr>";

        foreach ($banners as $row) {
            echo "<tr>";
            echo "<td>{$row['id_benner']}</td>";
            echo "<td>{$row['upload']}</td>";
            echo "<td>{$row['judul']}</td>";
            echo "<td>" . strip_tags($row['teks']) . "</td>";
            echo "<td>{$row['urutan']}</td>";
            echo "<td>{$row['created_at']}</td>";
            echo "<td>{$row['updated_at']}</td>";
            echo "</tr>";
        }

        echo "</table>";
        exit();
    }

    // Method untuk handle upload file
    private function handleFileUpload($file) {
        $uploadDir = 'uploads/banner/';

        // Buat direktori jika belum ada
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'mp4', 'avi', 'mov', 'wmv', 'webm'];
        $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($fileExtension, $allowedTypes)) {
            $_SESSION['error'] = 'Format file tidak diizinkan. Gunakan JPG, PNG, GIF, atau format video.';
            return '';
        }

        // Validasi ukuran file (max 10MB)
        if ($file['size'] > 10000000) {
            $_SESSION['error'] = 'Ukuran file terlalu besar. Maksimal 10MB.';
            return '';
        }

        $fileName = time() . '_' . uniqid() . '.' . $fileExtension;
        $filePath = $uploadDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            return $filePath;
        } else {
            $_SESSION['error'] = 'Gagal upload file.';
            return '';
        }
    }
}