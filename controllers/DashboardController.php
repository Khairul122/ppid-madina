<?php
require_once 'models/DashboardModel.php';
require_once 'config/koneksi.php';

class DashboardController
{
    private $dashboardModel;

    public function __construct()
    {
         global $database;
        $db = $database->getConnection();
        $this->dashboardModel = new DashboardModel($db);
    }

    public function index()
    {
        // Cek apakah user sudah login dan memiliki role admin
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php');
            exit();
        }

        // Ambil data dari model
        $data = [];
        $data['stats'] = $this->dashboardModel->getStats();
        $data['kategori_data'] = $this->dashboardModel->getKategoriData();
        $data['status_data'] = $this->dashboardModel->getStatusData();
        $data['recent_permohonan'] = $this->dashboardModel->getRecentPermohonan();
        $data['recent_berita'] = $this->dashboardModel->getRecentBerita();

        // Load view dengan data
        include 'views/dashboard/admin/index.php';
    }
}