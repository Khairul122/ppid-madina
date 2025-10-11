<?php

class PermohonanAdminModel {
    private $conn;
    
    public function __construct($database) {
        $this->conn = $database;
    }
    
    /**
     * Fungsi untuk mendapatkan semua data pemohon
     * Mengambil data dari tabel biodata_pengguna
     */
    public function getAllPemohon() {
        $query = "SELECT 
                    id_biodata,
                    nama_lengkap,
                    alamat,
                    city,
                    email,
                    no_kontak
                  FROM biodata_pengguna
                  ORDER BY nama_lengkap ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    /**
     * Fungsi untuk mendapatkan data pemohon berdasarkan ID
     */
    public function getPemohonById($id) {
        $query = "SELECT 
                    id_biodata,
                    nama_lengkap,
                    alamat,
                    city,
                    email,
                    no_kontak
                  FROM biodata_pengguna
                  WHERE id_biodata = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch();
    }
    
    /**
     * Fungsi untuk mendapatkan jumlah total pemohon
     */
    public function getTotalPemohon() {
        $query = "SELECT COUNT(*) as total FROM biodata_pengguna";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        $result = $stmt->fetch();
        return $result['total'];
    }
}