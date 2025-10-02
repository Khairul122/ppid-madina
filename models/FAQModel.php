<?php
class FAQModel {
    private $conn;
    private $table_name = "faq";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Get all FAQ
    public function getAllFAQ() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get FAQ by ID
    public function getFAQById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_faq = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create new FAQ
    public function create($isi, $penulis, $tags) {
        $query = "INSERT INTO " . $this->table_name . "
                  (isi, penulis, tags, created_at, updated_at)
                  VALUES (:isi, :penulis, :tags, NOW(), NOW())";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':isi', $isi);
        $stmt->bindParam(':penulis', $penulis);
        $stmt->bindParam(':tags', $tags);

        return $stmt->execute();
    }

    // Update FAQ
    public function update($id, $isi, $penulis, $tags) {
        $query = "UPDATE " . $this->table_name . "
                  SET isi = :isi, penulis = :penulis, tags = :tags, updated_at = NOW()
                  WHERE id_faq = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':isi', $isi);
        $stmt->bindParam(':penulis', $penulis);
        $stmt->bindParam(':tags', $tags);

        return $stmt->execute();
    }

    // Delete FAQ
    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_faq = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Get FAQ for public view with search and pagination
    public function getPublicFAQ($search = '', $limit = 10, $offset = 0) {
        $whereClause = '';
        $params = [];

        if (!empty($search)) {
            $whereClause = "WHERE isi LIKE :search OR penulis LIKE :search OR tags LIKE :search";
            $params[':search'] = "%$search%";
        }

        $query = "SELECT * FROM " . $this->table_name . " $whereClause ORDER BY created_at DESC LIMIT $limit OFFSET $offset";
        $stmt = $this->conn->prepare($query);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get total count for pagination
    public function getTotalCount($search = '') {
        $whereClause = '';
        $params = [];

        if (!empty($search)) {
            $whereClause = "WHERE isi LIKE :search OR penulis LIKE :search OR tags LIKE :search";
            $params[':search'] = "%$search%";
        }

        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " $whereClause";
        $stmt = $this->conn->prepare($query);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    // Get FAQ by tags
    public function getFAQByTags($tags) {
        $query = "SELECT * FROM " . $this->table_name . "
                  WHERE tags LIKE :tags
                  ORDER BY created_at DESC";

        $stmt = $this->conn->prepare($query);
        $searchTags = "%$tags%";
        $stmt->bindParam(':tags', $searchTags);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get single FAQ (for edit-only mode)
    public function getSingleFAQ() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id_faq ASC LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // If no FAQ exists, create a default one
        if (!$result) {
            $this->createDefaultFAQ();
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
        }

        return $result;
    }

    // Create default FAQ if not exists
    private function createDefaultFAQ() {
        $defaultIsi = '<h2>Selamat Datang di FAQ PPID Mandailing Natal</h2><p>Silakan edit konten FAQ ini melalui halaman admin.</p>';
        $defaultPenulis = 'Admin PPID';
        $defaultTags = 'umum';

        $query = "INSERT INTO " . $this->table_name . "
                  (isi, penulis, tags, created_at, updated_at)
                  VALUES (:isi, :penulis, :tags, NOW(), NOW())";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':isi', $defaultIsi);
        $stmt->bindParam(':penulis', $defaultPenulis);
        $stmt->bindParam(':tags', $defaultTags);

        return $stmt->execute();
    }

    // Update single FAQ
    public function updateSingle($isi, $penulis, $tags) {
        // Get the first FAQ ID
        $faq = $this->getSingleFAQ();

        if ($faq) {
            return $this->update($faq['id_faq'], $isi, $penulis, $tags);
        }

        return false;
    }
}
?>
