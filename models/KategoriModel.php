<?php
class KategoriModel
{
    private $conn;
    private $table_name = "kategori";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Method untuk memeriksa apakah tabel kategori ada
    public function tableExists()
    {
        try {
            $stmt = $this->conn->prepare("SELECT 1 FROM " . $this->table_name . " LIMIT 1");
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            // Tabel tidak ada
            return false;
        }
    }

    // Method untuk membuat tabel kategori jika belum ada
    public function createTable()
    {
        $sql = "CREATE TABLE IF NOT EXISTS " . $this->table_name . " (
            id_kategori INT AUTO_INCREMENT PRIMARY KEY,
            nama_kategori VARCHAR(255) NOT NULL UNIQUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";
        
        try {
            $this->conn->exec($sql);
            return true;
        } catch (PDOException $e) {
            error_log("Error creating table: " . $e->getMessage());
            return false;
        }
    }

    // Method untuk mendapatkan semua kategori
    public function getAllKategori($search = '', $limit = 10, $offset = 0)
    {
        if (!$this->tableExists()) {
            $this->createTable();
        }
        
        $query = "SELECT * FROM " . $this->table_name;
        $params = [];
        
        if (!empty($search)) {
            $query .= " WHERE nama_kategori LIKE :search";
            $params[':search'] = '%' . $search . '%';
        }
        
        $query .= " ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
        
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
        if (!$this->tableExists()) {
            $this->createTable();
        }
        
        $query = "SELECT COUNT(*) FROM " . $this->table_name;
        $params = [];
        
        if (!empty($search)) {
            $query .= " WHERE nama_kategori LIKE :search";
            $params[':search'] = '%' . $search . '%';
        }
        
        $stmt = $this->conn->prepare($query);
        
        foreach ($params as $key => $value) {
            $stmt->bindParam($key, $value);
        }
        
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    // Method untuk mendapatkan kategori berdasarkan ID
    public function getKategoriById($id)
    {
        if (!$this->tableExists()) {
            $this->createTable();
        }
        
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_kategori = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Method untuk membuat kategori baru
    public function create($nama_kategori)
    {
        if (!$this->tableExists()) {
            $this->createTable();
        }
        
        $query = "INSERT INTO " . $this->table_name . " (nama_kategori, created_at) VALUES (:nama_kategori, NOW())";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':nama_kategori', $nama_kategori);
        
        return $stmt->execute();
    }

    // Method untuk update kategori
    public function update($id, $nama_kategori)
    {
        if (!$this->tableExists()) {
            $this->createTable();
        }
        
        $query = "UPDATE " . $this->table_name . " SET nama_kategori = :nama_kategori, updated_at = NOW() WHERE id_kategori = :id";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':nama_kategori', $nama_kategori);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }

    // Method untuk menghapus kategori
    public function delete($id)
    {
        if (!$this->tableExists()) {
            $this->createTable();
        }
        
        $query = "DELETE FROM " . $this->table_name . " WHERE id_kategori = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->rowCount();
    }

    // Method untuk memeriksa apakah nama kategori sudah ada
    public function namaKategoriExists($nama_kategori, $id = null)
    {
        if (!$this->tableExists()) {
            $this->createTable();
        }
        
        $query = "SELECT id_kategori FROM " . $this->table_name . " WHERE nama_kategori = :nama_kategori";
        $params = [':nama_kategori' => $nama_kategori];
        
        if ($id !== null) {
            $query .= " AND id_kategori != :id";
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
?>