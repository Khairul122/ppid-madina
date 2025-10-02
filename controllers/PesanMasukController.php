<?php
require_once 'models/PesanMasukModel.php';

class PesanMasukController {
    private $pesanMasukModel;

    public function __construct() {
        global $database;
        $db = $database->getConnection();
        $this->pesanMasukModel = new PesanMasukModel($db);
    }

    // Method untuk menampilkan halaman admin (Lihat Pesan Masuk)
    public function index() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?controller=auth&action=login');
            exit();
        }

        // Get all messages
        $pesanList = $this->pesanMasukModel->getAllPesan();
        $unreadCount = $this->pesanMasukModel->getUnreadCount();
        $totalCount = $this->pesanMasukModel->getTotalCount();

        include 'views/pesan_masuk/index.php';
    }

    // Method untuk menampilkan form publik
    public function public() {
        // Data untuk view
        $pageInfo = [
            'title' => 'Hubungi Kami - PPID Mandailing Natal',
            'description' => 'Kirim pesan, pertanyaan, atau pengaduan kepada PPID Kabupaten Mandailing Natal'
        ];

        include 'views/pesan_masuk/public.php';
    }

    // Method untuk submit pesan dari publik
    public function submit() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama = trim($_POST['nama'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $no_telp = trim($_POST['no_telp'] ?? '');
            $subjek = trim($_POST['subjek'] ?? '');
            $pesan = trim($_POST['pesan'] ?? '');

            // Validasi
            $errors = [];

            if (empty($nama)) {
                $errors[] = 'Nama tidak boleh kosong!';
            }

            if (empty($email)) {
                $errors[] = 'Email tidak boleh kosong!';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Format email tidak valid!';
            }

            if (empty($subjek)) {
                $errors[] = 'Subjek tidak boleh kosong!';
            }

            if (empty($pesan)) {
                $errors[] = 'Pesan tidak boleh kosong!';
            }

            if (!empty($errors)) {
                $_SESSION['error'] = implode('<br>', $errors);
                header('Location: index.php?controller=pesan_masuk&action=public');
                exit();
            }

            // Simpan ke database
            if ($this->pesanMasukModel->create($nama, $email, $no_telp, $subjek, $pesan)) {
                $_SESSION['success'] = 'Pesan berhasil dikirim! Kami akan segera merespon pesan Anda.';
            } else {
                $_SESSION['error'] = 'Gagal mengirim pesan! Silakan coba lagi.';
            }

            header('Location: index.php?controller=pesan_masuk&action=public');
            exit();
        }
    }

    // Method untuk mark as read
    public function mark_read() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?controller=auth&action=login');
            exit();
        }

        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            if ($this->pesanMasukModel->markAsRead($id)) {
                $_SESSION['success'] = 'Pesan berhasil ditandai sebagai sudah dibaca!';
            } else {
                $_SESSION['error'] = 'Gagal menandai pesan!';
            }
        }

        header('Location: index.php?controller=pesan_masuk&action=index');
        exit();
    }

    // Method untuk mark as unread
    public function mark_unread() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?controller=auth&action=login');
            exit();
        }

        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            if ($this->pesanMasukModel->markAsUnread($id)) {
                $_SESSION['success'] = 'Pesan berhasil ditandai sebagai belum dibaca!';
            } else {
                $_SESSION['error'] = 'Gagal menandai pesan!';
            }
        }

        header('Location: index.php?controller=pesan_masuk&action=index');
        exit();
    }

    // Method untuk delete
    public function delete() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?controller=auth&action=login');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id_pesan'] ?? '';

            if (!empty($id)) {
                if ($this->pesanMasukModel->delete($id)) {
                    $_SESSION['success'] = 'Pesan berhasil dihapus!';
                } else {
                    $_SESSION['error'] = 'Gagal menghapus pesan!';
                }
            }
        }

        header('Location: index.php?controller=pesan_masuk&action=index');
        exit();
    }
}
?>
