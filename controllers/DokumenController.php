<?php
require_once 'models/DokumenModel.php';

class DokumenController
{
    private $dokumenModel;

    public function __construct()
    {
        global $database;
        $db = $database->getConnection();
        $this->dokumenModel = new DokumenModel($db);
    }

    // Method untuk menampilkan halaman dokumen
    public function index()
    {
        $id_kategori = isset($_GET['kategori']) ? intval($_GET['kategori']) : 0;
        $nama_jenis = isset($_GET['nama_jenis']) ? $_GET['nama_jenis'] : null;
        $keyword = isset($_GET['search']) ? trim($_GET['search']) : '';

        // Get kategori name
        $nama_kategori = '';
        if ($id_kategori > 0) {
            $nama_kategori = $this->dokumenModel->getKategoriById($id_kategori);
        }

        // Get dokumen data
        if (!empty($keyword)) {
            // Search mode
            $dokumenList = $this->dokumenModel->searchDokumen($keyword, $id_kategori);
        } else {
            // Filter by kategori and nama_jenis
            $dokumenList = $this->dokumenModel->getDokumenByKategoriAndJenis($id_kategori, $nama_jenis);
        }

        // Get all kategori for filter
        $kategoriList = $this->dokumenModel->getAllKategori();

        $data = [
            'title' => 'Daftar Dokumen',
            'dokumenList' => $dokumenList,
            'kategoriList' => $kategoriList,
            'nama_kategori' => $nama_kategori,
            'nama_jenis' => $nama_jenis,
            'id_kategori' => $id_kategori,
            'keyword' => $keyword
        ];

        include 'views/layanan_informasi/dokumen.php';
    }
}
