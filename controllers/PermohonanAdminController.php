<?php

// Pastikan file ini diakses melalui index.php dengan controller
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class PermohonanAdminController {
    private $model;
    
    public function __construct() {
        // Memuat koneksi database
        require_once 'config/koneksi.php';
        $database = new Database();
        $this->model = new PermohonanAdminModel($database->getConnection());
    }
    
    /**
     * Fungsi untuk menampilkan halaman data pemohon
     */
    public function data_pemohon() {
        $data['title'] = 'Data Pemohon';
        $data['pemohon_list'] = $this->model->getAllPemohon();
        $data['total_pemohon'] = $this->model->getTotalPemohon();
        
        // Include view untuk halaman data pemohon
        include 'views/permohonan_admin/data_pemohon/index.php';
    }
    
    /**
     * Fungsi untuk menampilkan detail pemohon
     */
    public function detail_pemohon() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        if ($id <= 0) {
            $_SESSION['error'] = 'ID pemohon tidak valid.';
            header('Location: index.php?controller=permohonan_admin&action=data_pemohon');
            exit;
        }
        
        $data['title'] = 'Detail Pemohon';
        $data['pemohon'] = $this->model->getPemohonById($id);
        
        if (!$data['pemohon']) {
            $_SESSION['error'] = 'Data pemohon tidak ditemukan.';
            header('Location: index.php?controller=permohonan_admin&action=data_pemohon');
            exit;
        }
        
        // Include view untuk halaman detail pemohon
        include 'views/permohonan_admin/data_pemohon/detail.php';
    }
}