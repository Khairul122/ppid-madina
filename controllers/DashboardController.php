<?php
require_once 'models/DashboardModel.php';
require_once 'config/koneksi.php';

class DashboardController
{
    private $dashboardModel;

    public function __construct()
    {
        // Buat instance database
        $database = new Database();
        $db = $database->getConnection();
        
        if ($db) {
            $this->dashboardModel = new DashboardModel($db);
        } else {
            throw new Exception("Koneksi database gagal");
        }
    }

    public function index()
    {
        // Cek apakah user sudah login dan memiliki role admin atau petugas
        if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'petugas')) {
            header('Location: index.php?controller=auth&action=login');
            exit();
        }

        // Ambil data dari model
        $data = [];
        $data['stats'] = $this->dashboardModel->getStats();
        $data['permohonan_stats'] = $this->dashboardModel->getPermohonanStats();
        $data['kategori_data'] = $this->dashboardModel->getKategoriData();
        $data['status_data'] = $this->dashboardModel->getStatusData();
        $data['recent_permohonan'] = $this->dashboardModel->getRecentPermohonan();
        $data['recent_berita'] = $this->dashboardModel->getRecentBerita();

        // Debug log
        error_log("Dashboard Data Sent to View:");
        error_log("- Stats: " . json_encode($data['stats']));
        error_log("- Permohonan Stats: " . json_encode($data['permohonan_stats']));
        error_log("- Kategori Data Count: " . count($data['kategori_data']));
        error_log("- Status Data Count: " . count($data['status_data']));

        // Load view berdasarkan role
        if ($_SESSION['role'] === 'admin') {
            include 'views/dashboard/admin/index.php';
        } elseif ($_SESSION['role'] === 'petugas') {
            include 'views/dashboard/petugas/index.php';
        }
    }
}