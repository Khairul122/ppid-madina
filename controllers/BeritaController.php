<?php
require_once 'models/BeritaModel.php';

class BeritaController {
    private $beritaModel;

    public function __construct() {
        global $database;
        $db = $database->getConnection();
        $this->beritaModel = new BeritaModel($db);
    }

    // Method untuk menampilkan halaman index berita
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

        // Ambil data berita
        $berita = $this->beritaModel->getAllBerita($search, $limit, $offset);
        $totalCount = $this->beritaModel->getTotalCount($search);
        $totalPages = ceil($totalCount / $limit);

        // Data untuk view
        $data = [
            'berita' => $berita,
            'search' => $search,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalCount' => $totalCount,
            'limit' => $limit
        ];

        include 'views/berita/index.php';
    }

    // Method untuk menampilkan form tambah berita
    public function create() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?controller=auth&action=login');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->store();
        } else {
            $data = ['action' => 'create'];
            include 'views/berita/form.php';
        }
    }

    // Method untuk menyimpan berita baru
    public function store() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?controller=auth&action=login');
            exit();
        }

        $judul = $_POST['judul'] ?? '';
        $url = $_POST['url'] ?? '';
        $summary = $_POST['summary'] ?? '';
        $image = '';

        // Handle upload gambar atau URL
        if (!empty($_FILES['image_file']['name'])) {
            $image = $this->handleImageUpload($_FILES['image_file']);
        } elseif (!empty($_POST['image_url'])) {
            $image = $_POST['image_url'];
        }

        // Validasi
        if (empty($judul) || empty($summary)) {
            $_SESSION['error'] = 'Judul dan Summary tidak boleh kosong!';
            header('Location: index.php?controller=berita&action=create');
            exit();
        }

        // Cek URL duplikat
        if (!empty($url) && $this->beritaModel->isUrlExists($url)) {
            $_SESSION['error'] = 'URL berita sudah ada!';
            header('Location: index.php?controller=berita&action=create');
            exit();
        }

        // Simpan ke database
        if ($this->beritaModel->create($judul, $url, $summary, $image)) {
            $_SESSION['success'] = 'Berita berhasil ditambahkan!';
        } else {
            $_SESSION['error'] = 'Gagal menambah berita!';
        }

        header('Location: index.php?controller=berita');
        exit();
    }

    // Method untuk menampilkan form edit berita
    public function edit() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?controller=auth&action=login');
            exit();
        }

        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: index.php?controller=berita');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->update($id);
        } else {
            $berita = $this->beritaModel->getBeritaById($id);
            if (!$berita) {
                $_SESSION['error'] = 'Berita tidak ditemukan!';
                header('Location: index.php?controller=berita');
                exit();
            }

            $data = [
                'action' => 'edit',
                'berita' => $berita
            ];
            include 'views/berita/form.php';
        }
    }

    // Method untuk update berita
    public function update($id) {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?controller=auth&action=login');
            exit();
        }

        $judul = $_POST['judul'] ?? '';
        $url = $_POST['url'] ?? '';
        $summary = $_POST['summary'] ?? '';

        // Ambil data berita lama
        $oldBerita = $this->beritaModel->getBeritaById($id);
        $image = $oldBerita['image']; // Default gunakan gambar lama

        // Handle upload gambar atau URL baru
        if (!empty($_FILES['image_file']['name'])) {
            $image = $this->handleImageUpload($_FILES['image_file']);
        } elseif (!empty($_POST['image_url'])) {
            $image = $_POST['image_url'];
        }

        // Validasi
        if (empty($judul) || empty($summary)) {
            $_SESSION['error'] = 'Judul dan Summary tidak boleh kosong!';
            header('Location: index.php?controller=berita&action=edit&id=' . $id);
            exit();
        }

        // Cek URL duplikat (kecuali URL yang sama)
        if (!empty($url) && $this->beritaModel->isUrlExists($url, $id)) {
            $_SESSION['error'] = 'URL berita sudah ada!';
            header('Location: index.php?controller=berita&action=edit&id=' . $id);
            exit();
        }

        // Update ke database
        if ($this->beritaModel->update($id, $judul, $url, $summary, $image)) {
            $_SESSION['success'] = 'Berita berhasil diupdate!';
        } else {
            $_SESSION['error'] = 'Gagal update berita!';
        }

        header('Location: index.php?controller=berita');
        exit();
    }

    // Method untuk hapus berita
    public function delete() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?controller=auth&action=login');
            exit();
        }

        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: index.php?controller=berita');
            exit();
        }

        if ($this->beritaModel->delete($id)) {
            $_SESSION['success'] = 'Berita berhasil dihapus!';
        } else {
            $_SESSION['error'] = 'Gagal hapus berita!';
        }

        header('Location: index.php?controller=berita');
        exit();
    }

    // Method untuk export ke Excel
    public function export() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?controller=auth&action=login');
            exit();
        }

        $berita = $this->beritaModel->getAllBeritaForExport();

        // Set header untuk download Excel
        $filename = "data_berita_" . date('Y-m-d_H-i-s') . ".xls";
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");

        // Output Excel content
        echo "<table border='1'>";
        echo "<tr>";
        echo "<th>ID</th>";
        echo "<th>Judul</th>";
        echo "<th>URL</th>";
        echo "<th>Summary</th>";
        echo "<th>Image</th>";
        echo "<th>Created At</th>";
        echo "<th>Updated At</th>";
        echo "</tr>";

        foreach ($berita as $row) {
            echo "<tr>";
            echo "<td>{$row['id_berita']}</td>";
            echo "<td>{$row['judul']}</td>";
            echo "<td>{$row['url']}</td>";
            echo "<td>" . strip_tags($row['summary']) . "</td>";
            echo "<td>{$row['image']}</td>";
            echo "<td>{$row['created_at']}</td>";
            echo "<td>{$row['updated_at']}</td>";
            echo "</tr>";
        }

        echo "</table>";
        exit();
    }

    // Method untuk menampilkan berita untuk publik
    public function public() {
        // Parameter untuk search dan pagination
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 12; // Data per halaman untuk publik
        $offset = ($page - 1) * $limit;

        // Ambil data berita untuk publik
        $beritaList = $this->beritaModel->getPublicBerita($search, $limit, $offset);
        $totalCount = $this->beritaModel->getTotalCount($search);
        $totalPages = ceil($totalCount / $limit);

        // Data untuk view
        $pageInfo = [
            'title' => 'Berita - PPID Mandailing Natal',
            'description' => 'Informasi terbaru dan terpercaya seputar Kabupaten Mandailing Natal'
        ];

        include 'views/berita/public.php';
    }

    // Method untuk menampilkan detail berita
    public function detail() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if (!$id) {
            header('Location: index.php?controller=berita&action=public');
            exit();
        }

        // Ambil data berita
        $berita = $this->beritaModel->getBeritaById($id);

        if (!$berita) {
            $_SESSION['error'] = 'Berita tidak ditemukan!';
            header('Location: index.php?controller=berita&action=public');
            exit();
        }

        // Ambil berita terkait (berita terbaru lainnya)
        $relatedNews = $this->beritaModel->getRelatedBerita($id, 4);

        // Data untuk view
        $pageInfo = [
            'title' => $berita['judul'] . ' - PPID Mandailing Natal',
            'description' => substr(strip_tags($berita['summary']), 0, 160)
        ];

        include 'views/berita/detail.php';
    }

    // Method untuk handle upload gambar
    private function handleImageUpload($file) {
        $uploadDir = 'uploads/berita/';

        // Buat direktori jika belum ada
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($fileExtension, $allowedTypes)) {
            $_SESSION['error'] = 'Format file tidak diizinkan. Gunakan JPG, PNG, atau GIF.';
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
?>