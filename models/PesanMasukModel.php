<?php
class PesanMasukModel {
    private $conn;
    private $table_name = "pesan_masuk";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Get all messages
    public function getAllPesan() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get message by ID
    public function getPesanById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_pesan = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create new message
    public function create($nama, $email, $no_telp, $subjek, $pesan) {
        $query = "INSERT INTO " . $this->table_name . "
                  (nama, email, no_telp, subjek, pesan, is_read, created_at)
                  VALUES (:nama, :email, :no_telp, :subjek, :pesan, 0, NOW())";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nama', $nama);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':no_telp', $no_telp);
        $stmt->bindParam(':subjek', $subjek);
        $stmt->bindParam(':pesan', $pesan);

        return $stmt->execute();
    }

    // Mark message as read
    public function markAsRead($id) {
        $query = "UPDATE " . $this->table_name . "
                  SET is_read = 1
                  WHERE id_pesan = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Mark message as unread
    public function markAsUnread($id) {
        $query = "UPDATE " . $this->table_name . "
                  SET is_read = 0
                  WHERE id_pesan = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Delete message
    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_pesan = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Get total unread messages
    public function getUnreadCount() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " WHERE is_read = 0";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    // Get total messages count
    public function getTotalCount() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    // Get messages with pagination
    public function getPesanWithPagination($limit = 10, $offset = 0) {
        $query = "SELECT * FROM " . $this->table_name . "
                  ORDER BY created_at DESC
                  LIMIT $limit OFFSET $offset";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Search messages
    public function search($keyword) {
        $query = "SELECT * FROM " . $this->table_name . "
                  WHERE nama LIKE :keyword
                  OR email LIKE :keyword
                  OR subjek LIKE :keyword
                  OR pesan LIKE :keyword
                  ORDER BY created_at DESC";

        $stmt = $this->conn->prepare($query);
        $searchKeyword = "%$keyword%";
        $stmt->bindParam(':keyword', $searchKeyword);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
