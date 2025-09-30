<?php
require_once 'models/SosialMediaModel.php';

class SosialMediaController {
    private $sosialMediaModel;

    public function __construct() {
        global $database;
        $db = $database->getConnection();
        $this->sosialMediaModel = new SosialMediaModel($db);
    }

    // Method untuk menampilkan halaman index sosial media
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

        // Ambil data sosial media
        $sosialMedia = $this->sosialMediaModel->getAllSosialMedia($search, $limit, $offset);
        $totalCount = $this->sosialMediaModel->getTotalCount($search);
        $totalPages = ceil($totalCount / $limit);

        // Data untuk view
        $data = [
            'sosialMedia' => $sosialMedia,
            'search' => $search,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalCount' => $totalCount,
            'limit' => $limit
        ];

        include 'views/sosial_media/index.php';
    }

    // Method untuk menampilkan form tambah sosial media
    public function create() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?controller=auth&action=login');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->store();
        } else {
            $data = ['action' => 'create'];
            include 'views/sosial_media/form.php';
        }
    }

    // Method untuk menyimpan sosial media baru
    public function store() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?controller=auth&action=login');
            exit();
        }

        $site = $_POST['site'] ?? '';
        $facebook_link = $_POST['facebook_link'] ?? '';
        $instagram_link = $_POST['instagram_link'] ?? '';
        $instagram_post = $_POST['instagram_post'] ?? '';

        // Validasi
        if (empty($site)) {
            $_SESSION['error'] = 'Site tidak boleh kosong!';
            header('Location: index.php?controller=sosialmedia&action=create');
            exit();
        }

        if ($this->sosialMediaModel->siteExists($site)) {
            $_SESSION['error'] = 'Site sudah ada!';
            header('Location: index.php?controller=sosialmedia&action=create');
            exit();
        }

        // Simpan ke database
        if ($this->sosialMediaModel->create($site, $facebook_link, $instagram_link, $instagram_post)) {
            $_SESSION['success'] = 'Sosial media berhasil ditambahkan!';
        } else {
            $_SESSION['error'] = 'Gagal menambah sosial media!';
        }

        header('Location: index.php?controller=sosialmedia');
        exit();
    }

    // Method untuk menampilkan form edit sosial media
    public function edit() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?controller=auth&action=login');
            exit();
        }

        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: index.php?controller=sosialmedia');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->update($id);
        } else {
            $sosialMedia = $this->sosialMediaModel->getSosialMediaById($id);
            if (!$sosialMedia) {
                $_SESSION['error'] = 'Sosial media tidak ditemukan!';
                header('Location: index.php?controller=sosialmedia');
                exit();
            }

            $data = [
                'action' => 'edit',
                'sosialMedia' => $sosialMedia
            ];
            include 'views/sosial_media/form.php';
        }
    }

    // Method untuk update sosial media
    public function update($id) {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?controller=auth&action=login');
            exit();
        }

        $site = $_POST['site'] ?? '';
        $facebook_link = $_POST['facebook_link'] ?? '';
        $instagram_link = $_POST['instagram_link'] ?? '';
        $instagram_post = $_POST['instagram_post'] ?? '';

        // Validasi
        if (empty($site)) {
            $_SESSION['error'] = 'Site tidak boleh kosong!';
            header('Location: index.php?controller=sosialmedia&action=edit&id=' . $id);
            exit();
        }

        if ($this->sosialMediaModel->siteExists($site, $id)) {
            $_SESSION['error'] = 'Site sudah ada!';
            header('Location: index.php?controller=sosialmedia&action=edit&id=' . $id);
            exit();
        }

        // Update ke database
        if ($this->sosialMediaModel->update($id, $site, $facebook_link, $instagram_link, $instagram_post)) {
            $_SESSION['success'] = 'Sosial media berhasil diupdate!';
        } else {
            $_SESSION['error'] = 'Gagal update sosial media!';
        }

        header('Location: index.php?controller=sosialmedia');
        exit();
    }

    // Method untuk hapus sosial media
    public function delete() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?controller=auth&action=login');
            exit();
        }

        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: index.php?controller=sosialmedia');
            exit();
        }

        if ($this->sosialMediaModel->delete($id)) {
            $_SESSION['success'] = 'Sosial media berhasil dihapus!';
        } else {
            $_SESSION['error'] = 'Gagal hapus sosial media!';
        }

        header('Location: index.php?controller=sosialmedia');
        exit();
    }

    // Method untuk export ke Excel
    public function export() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?controller=auth&action=login');
            exit();
        }

        $sosialMedia = $this->sosialMediaModel->getAllSosialMedia();

        // Set header untuk download Excel
        $filename = "data_sosial_media_" . date('Y-m-d_H-i-s') . ".xls";
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");

        // Output Excel content
        echo "<table border='1'>";
        echo "<tr>";
        echo "<th>ID</th>";
        echo "<th>Site</th>";
        echo "<th>Facebook Link</th>";
        echo "<th>Instagram Link</th>";
        echo "<th>Instagram Post</th>";
        echo "<th>Created At</th>";
        echo "<th>Updated At</th>";
        echo "</tr>";

        foreach ($sosialMedia as $row) {
            echo "<tr>";
            echo "<td>{$row['id_sosial_media']}</td>";
            echo "<td>{$row['site']}</td>";
            echo "<td>{$row['facebook_link']}</td>";
            echo "<td>{$row['instagram_link']}</td>";
            echo "<td>{$row['instagram_post']}</td>";
            echo "<td>{$row['created_at']}</td>";
            echo "<td>{$row['updated_at']}</td>";
            echo "</tr>";
        }

        echo "</table>";
        exit();
    }
}