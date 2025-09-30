<?php
require_once 'models/KategoriModel.php';

class KategoriController {
    private $kategoriModel;

    public function __construct() {
        global $database;
        $db = $database->getConnection();
        $this->kategoriModel = new KategoriModel($db);
    }

    // Method untuk menampilkan halaman index kategori
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

        // Ambil data kategori
        $kategoris = $this->kategoriModel->getAllKategori($search, $limit, $offset);
        $totalCount = $this->kategoriModel->getTotalCount($search);
        $totalPages = ceil($totalCount / $limit);

        // Data untuk view
        $data = [
            'kategoris' => $kategoris,
            'search' => $search,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalCount' => $totalCount,
            'limit' => $limit
        ];

        include 'views/kategori/index.php';
    }

    // Method untuk menampilkan form tambah kategori
    public function create() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?controller=auth&action=login');
            exit();
        }

        $data = [
            'kategori' => null,
            'errors' => []
        ];

        include 'views/kategori/form.php';
    }

    // Method untuk menyimpan kategori baru
    public function store() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?controller=auth&action=login');
            exit();
        }

        $nama_kategori = trim($_POST['nama_kategori'] ?? '');

        // Validasi
        $errors = [];
        if (empty($nama_kategori)) {
            $errors['nama_kategori'] = 'Nama kategori wajib diisi';
        } elseif (strlen($nama_kategori) < 3) {
            $errors['nama_kategori'] = 'Nama kategori minimal 3 karakter';
        } elseif ($this->kategoriModel->namaKategoriExists($nama_kategori)) {
            $errors['nama_kategori'] = 'Nama kategori sudah digunakan';
        }

        if (!empty($errors)) {
            $data = [
                'kategori' => [
                    'id_kategori' => null,
                    'nama_kategori' => $nama_kategori,
                    'created_at' => null,
                    'updated_at' => null
                ],
                'errors' => $errors
            ];
            include 'views/kategori/form.php';
            return;
        }

        // Simpan ke database
        if ($this->kategoriModel->create($nama_kategori)) {
            $_SESSION['success'] = 'Kategori berhasil ditambahkan';
        } else {
            $_SESSION['error'] = 'Kategori gagal ditambahkan';
        }

        header('Location: index.php?controller=kategori&action=index');
        exit();
    }

    // Method untuk menampilkan form edit kategori
    public function edit() {
        // Dapatkan ID dari parameter GET
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?controller=auth&action=login');
            exit();
        }

        $kategori = $this->kategoriModel->getKategoriById($id);
        if (!$kategori) {
            $_SESSION['error'] = 'Kategori tidak ditemukan';
            header('Location: index.php?controller=kategori&action=index');
            exit();
        }

        $data = [
            'kategori' => $kategori,
            'errors' => []
        ];

        include 'views/kategori/form.php';
    }

    // Method untuk update kategori
    public function update() {
        // Dapatkan ID dari parameter GET
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?controller=auth&action=login');
            exit();
        }

        $kategori = $this->kategoriModel->getKategoriById($id);
        if (!$kategori) {
            $_SESSION['error'] = 'Kategori tidak ditemukan';
            header('Location: index.php?controller=kategori&action=index');
            exit();
        }

        $nama_kategori = trim($_POST['nama_kategori'] ?? '');

        // Validasi
        $errors = [];
        if (empty($nama_kategori)) {
            $errors['nama_kategori'] = 'Nama kategori wajib diisi';
        } elseif (strlen($nama_kategori) < 3) {
            $errors['nama_kategori'] = 'Nama kategori minimal 3 karakter';
        } elseif ($this->kategoriModel->namaKategoriExists($nama_kategori, $id)) {
            $errors['nama_kategori'] = 'Nama kategori sudah digunakan';
        }

        if (!empty($errors)) {
            $data = [
                'kategori' => [
                    'id_kategori' => $id,
                    'nama_kategori' => $nama_kategori,
                    'created_at' => $kategori['created_at'],
                    'updated_at' => $kategori['updated_at']
                ],
                'errors' => $errors
            ];
            include 'views/kategori/form.php';
            return;
        }

        // Update ke database
        if ($this->kategoriModel->update($id, $nama_kategori)) {
            $_SESSION['success'] = 'Kategori berhasil diupdate';
        } else {
            $_SESSION['error'] = 'Kategori gagal diupdate';
        }

        header('Location: index.php?controller=kategori&action=index');
        exit();
    }

    // Method untuk menghapus kategori
    public function delete() {
        // Dapatkan ID dari parameter GET
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?controller=auth&action=login');
            exit();
        }

        $kategori = $this->kategoriModel->getKategoriById($id);
        if (!$kategori) {
            $_SESSION['error'] = 'Kategori tidak ditemukan';
        } else {
            if ($this->kategoriModel->delete($id)) {
                $_SESSION['success'] = 'Kategori berhasil dihapus';
            } else {
                $_SESSION['error'] = 'Kategori gagal dihapus';
            }
        }

        header('Location: index.php?controller=kategori&action=index');
        exit();
    }
}
?>