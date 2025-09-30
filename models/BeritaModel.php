<?php
class BeritaModel {
    private $conn;
    private $table_name = "berita";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Method untuk mendapatkan semua berita dengan search dan pagination
    public function getAllBerita($search = '', $limit = 10, $offset = 0) {
        $whereClause = '';
        $params = [];

        if (!empty($search)) {
            $whereClause = "WHERE judul LIKE :search OR summary LIKE :search OR url LIKE :search";
            $params[':search'] = "%$search%";
        }

        $query = "SELECT * FROM " . $this->table_name . " $whereClause ORDER BY created_at DESC LIMIT $limit OFFSET $offset";
        $stmt = $this->conn->prepare($query);

        foreach ($params as $key => $value) {
            $stmt->bindParam($key, $value);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Method untuk menghitung total berita (untuk pagination)
    public function getTotalCount($search = '') {
        $whereClause = '';
        $params = [];

        if (!empty($search)) {
            $whereClause = "WHERE judul LIKE :search OR summary LIKE :search OR url LIKE :search";
            $params[':search'] = "%$search%";
        }

        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " $whereClause";
        $stmt = $this->conn->prepare($query);

        foreach ($params as $key => $value) {
            $stmt->bindParam($key, $value);
        }

        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    // Method untuk mendapatkan berita berdasarkan ID
    public function getBeritaById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_berita = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Method untuk menambah berita baru
    public function create($judul, $url, $summary, $image) {
        $query = "INSERT INTO " . $this->table_name . " (judul, url, summary, image, created_at, updated_at)
                  VALUES (:judul, :url, :summary, :image, NOW(), NOW())";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':judul', $judul);
        $stmt->bindParam(':url', $url);
        $stmt->bindParam(':summary', $summary);
        $stmt->bindParam(':image', $image);

        return $stmt->execute();
    }

    // Method untuk update berita
    public function update($id, $judul, $url, $summary, $image) {
        $query = "UPDATE " . $this->table_name . "
                  SET judul = :judul, url = :url, summary = :summary, image = :image, updated_at = NOW()
                  WHERE id_berita = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':judul', $judul);
        $stmt->bindParam(':url', $url);
        $stmt->bindParam(':summary', $summary);
        $stmt->bindParam(':image', $image);

        return $stmt->execute();
    }

    // Method untuk menghapus berita
    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_berita = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }

    // Method untuk export data ke Excel (mendapatkan semua data)
    public function getAllBeritaForExport() {
        $query = "SELECT id_berita, judul, url, summary, image,
                         DATE_FORMAT(created_at, '%Y-%m-%d %H:%i:%s') as created_at,
                         DATE_FORMAT(updated_at, '%Y-%m-%d %H:%i:%s') as updated_at
                  FROM " . $this->table_name . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Method untuk validasi URL berita (opsional)
    public function isUrlExists($url, $excludeId = null) {
        $query = "SELECT COUNT(*) as count FROM " . $this->table_name . " WHERE url = :url";

        if ($excludeId) {
            $query .= " AND id_berita != :exclude_id";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':url', $url);

        if ($excludeId) {
            $stmt->bindParam(':exclude_id', $excludeId);
        }

        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['count'] > 0;
    }
}
?>