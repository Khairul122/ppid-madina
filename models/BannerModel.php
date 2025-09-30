<?php
class BannerModel
{
    private $conn;
    private $table_name = "benner";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Method untuk mendapatkan semua banner dengan pagination dan search
    public function getAllBanner($search = '', $limit = 10, $offset = 0)
    {
        $query = "SELECT * FROM " . $this->table_name;
        $params = [];
        
        if (!empty($search)) {
            $query .= " WHERE judul LIKE :search OR teks LIKE :search";
            $params[':search'] = '%' . $search . '%';
        }
        
        $query .= " ORDER BY urutan ASC, created_at DESC LIMIT :limit OFFSET :offset";
        
        $stmt = $this->conn->prepare($query);
        
        foreach ($params as $key => $value) {
            $stmt->bindParam($key, $value);
        }
        
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Method untuk mendapatkan total count dengan search
    public function getTotalCount($search = '')
    {
        $query = "SELECT COUNT(*) FROM " . $this->table_name;
        $params = [];
        
        if (!empty($search)) {
            $query .= " WHERE judul LIKE :search OR teks LIKE :search";
            $params[':search'] = '%' . $search . '%';
        }
        
        $stmt = $this->conn->prepare($query);
        
        foreach ($params as $key => $value) {
            $stmt->bindParam($key, $value);
        }
        
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    // Method untuk mendapatkan banner berdasarkan ID
    public function getBannerById($id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_banner = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Method untuk membuat banner baru
    public function create($judul, $teks, $urutan, $upload)
    {
        $query = "INSERT INTO " . $this->table_name . " (upload, judul, teks, urutan, created_at) VALUES (:upload, :judul, :teks, :urutan, NOW())";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':upload', $upload);
        $stmt->bindParam(':judul', $judul);
        $stmt->bindParam(':teks', $teks);
        $stmt->bindParam(':urutan', $urutan);
        
        return $stmt->execute();
    }

    // Method untuk update banner
    public function update($id, $judul, $teks, $urutan, $upload = null)
    {
        if ($upload !== null) {
            $query = "UPDATE " . $this->table_name . " SET upload = :upload, judul = :judul, teks = :teks, urutan = :urutan, updated_at = NOW() WHERE id_banner = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':upload', $upload);
        } else {
            $query = "UPDATE " . $this->table_name . " SET judul = :judul, teks = :teks, urutan = :urutan, updated_at = NOW() WHERE id_banner = :id";
            $stmt = $this->conn->prepare($query);
        }
        
        $stmt->bindParam(':judul', $judul);
        $stmt->bindParam(':teks', $teks);
        $stmt->bindParam(':urutan', $urutan);
        $stmt->bindParam(':id', $id);
        
        if ($upload !== null) {
            $stmt->execute();
        } else {
            $stmt->execute();
        }
        
        return $stmt->rowCount();
    }

    // Method untuk menghapus banner
    public function delete($id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_banner = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->rowCount();
    }

    // Method untuk memeriksa apakah judul sudah ada
    public function judulExists($judul, $id = null)
    {
        $query = "SELECT id_banner FROM " . $this->table_name . " WHERE judul = :judul";
        $params = [':judul' => $judul];
        
        if ($id !== null) {
            $query .= " AND id_banner != :id";
            $params[':id'] = $id;
        }
        
        $query .= " LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        
        foreach ($params as $key => $value) {
            $stmt->bindParam($key, $value);
        }
        
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }
}