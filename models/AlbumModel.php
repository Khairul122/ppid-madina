<?php
class AlbumModel {
    private $conn;

    public function __construct($db = null) {
        $this->conn = $db;
    }

    // Method untuk mendapatkan semua data album
    public function getAllAlbum() {
        if (!$this->conn) {
            return [];
        }

        $query = "SELECT id_album, kategori, nama_album, upload, created_at, updated_at
                  FROM album
                  ORDER BY created_at DESC";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $results;
        } catch (PDOException $e) {
            error_log("Database error in getAllAlbum: " . $e->getMessage());
            return [];
        }
    }

    // Method untuk mendapatkan album berdasarkan kategori
    public function getAlbumByKategori($kategori) {
        if (!$this->conn) {
            return [];
        }

        $query = "SELECT id_album, kategori, nama_album, upload, created_at, updated_at
                  FROM album
                  WHERE kategori = :kategori
                  ORDER BY created_at DESC";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':kategori', $kategori);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $results;
        } catch (PDOException $e) {
            error_log("Database error in getAlbumByKategori: " . $e->getMessage());
            return [];
        }
    }

    // Method untuk mendapatkan satu data album berdasarkan ID
    public function getAlbumById($id) {
        if (!$this->conn) {
            return null;
        }

        $query = "SELECT id_album, kategori, nama_album, upload, created_at, updated_at
                  FROM album
                  WHERE id_album = :id";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result;
        } catch (PDOException $e) {
            error_log("Database error in getAlbumById: " . $e->getMessage());
            return null;
        }
    }

    // Method untuk menyimpan data album baru
    public function createAlbum($kategori, $nama_album, $upload = null) {
        if (!$this->conn) {
            return false;
        }

        $query = "INSERT INTO album (kategori, nama_album, upload)
                  VALUES (:kategori, :nama_album, :upload)";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':kategori', $kategori);
            $stmt->bindParam(':nama_album', $nama_album);
            $stmt->bindParam(':upload', $upload);
            $result = $stmt->execute();

            return $result;
        } catch (PDOException $e) {
            error_log("Database error in createAlbum: " . $e->getMessage());
            return false;
        }
    }

    // Method untuk memperbarui data album
    public function updateAlbum($id, $kategori, $nama_album, $upload = null) {
        if (!$this->conn) {
            return false;
        }

        $query = "UPDATE album
                  SET kategori = :kategori, nama_album = :nama_album, upload = :upload, updated_at = CURRENT_TIMESTAMP
                  WHERE id_album = :id";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':kategori', $kategori);
            $stmt->bindParam(':nama_album', $nama_album);
            $stmt->bindParam(':upload', $upload);
            $result = $stmt->execute();

            return $result;
        } catch (PDOException $e) {
            error_log("Database error in updateAlbum: " . $e->getMessage());
            return false;
        }
    }

    // Method untuk menghapus data album
    public function deleteAlbum($id) {
        if (!$this->conn) {
            return false;
        }

        $query = "DELETE FROM album WHERE id_album = :id";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $result = $stmt->execute();

            if ($result) {
                return $stmt->rowCount() > 0;
            }

            return false;
        } catch (PDOException $e) {
            error_log("Database error in deleteAlbum: " . $e->getMessage());
            return false;
        }
    }
}
?>
