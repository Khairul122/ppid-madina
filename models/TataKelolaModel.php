<?php
class TataKelolaModel {
    private $conn;

    public function __construct($db = null) {
        $this->conn = $db;
    }

    // Method untuk mendapatkan semua data tata kelola
    public function getAllTataKelola() {
        if (!$this->conn) {
            return [];
        }

        $query = "SELECT id_tata_kelola, nama_tata_kelola, link, created_at, updated_at 
                  FROM tata_kelola 
                  ORDER BY created_at DESC";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $results;
        } catch (PDOException $e) {
            error_log("Database error in getAllTataKelola: " . $e->getMessage());
            return [];
        }
    }

    // Method untuk mendapatkan satu data tata kelola berdasarkan ID
    public function getTataKelolaById($id) {
        if (!$this->conn) {
            return null;
        }

        $query = "SELECT id_tata_kelola, nama_tata_kelola, link, created_at, updated_at 
                  FROM tata_kelola 
                  WHERE id_tata_kelola = :id";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result;
        } catch (PDOException $e) {
            error_log("Database error in getTataKelolaById: " . $e->getMessage());
            return null;
        }
    }

    // Method untuk menyimpan data tata kelola baru
    public function createTataKelola($nama_tata_kelola, $link) {
        if (!$this->conn) {
            return false;
        }

        $query = "INSERT INTO tata_kelola (nama_tata_kelola, link) 
                  VALUES (:nama_tata_kelola, :link)";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':nama_tata_kelola', $nama_tata_kelola);
            $stmt->bindParam(':link', $link);
            $result = $stmt->execute();

            return $result;
        } catch (PDOException $e) {
            error_log("Database error in createTataKelola: " . $e->getMessage());
            return false;
        }
    }

    // Method untuk memperbarui data tata kelola
    public function updateTataKelola($id, $nama_tata_kelola, $link) {
        if (!$this->conn) {
            return false;
        }

        $query = "UPDATE tata_kelola 
                  SET nama_tata_kelola = :nama_tata_kelola, link = :link, updated_at = CURRENT_TIMESTAMP
                  WHERE id_tata_kelola = :id";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':nama_tata_kelola', $nama_tata_kelola);
            $stmt->bindParam(':link', $link);
            $result = $stmt->execute();

            return $result;
        } catch (PDOException $e) {
            error_log("Database error in updateTataKelola: " . $e->getMessage());
            return false;
        }
    }

    // Method untuk menghapus data tata kelola
    public function deleteTataKelola($id) {
        if (!$this->conn) {
            return false;
        }

        $query = "DELETE FROM tata_kelola WHERE id_tata_kelola = :id";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $result = $stmt->execute();

            return $result;
        } catch (PDOException $e) {
            error_log("Database error in deleteTataKelola: " . $e->getMessage());
            return false;
        }
    }
}
?>