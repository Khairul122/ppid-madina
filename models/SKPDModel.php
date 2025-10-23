<?php
class SKPDModel {
    private $conn;
    private $table_name = "skpd";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Method untuk mendapatkan semua data SKPD
    public function getAllSKPD() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY nama_skpd ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Method untuk mendapatkan data SKPD dengan pagination
    public function getSKPDWithPagination($limit = 10, $offset = 0) {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY nama_skpd ASC LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Method untuk mendapatkan SKPD berdasarkan ID
    public function getSKPDById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_skpd = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Method untuk menambah SKPD baru
    public function createSKPD($data) {
        try {
            $query = "INSERT INTO " . $this->table_name . "
                      (nama_skpd, alamat, telepon, email, kategori)
                      VALUES (:nama_skpd, :alamat, :telepon, :email, :kategori)";

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':nama_skpd', $data['nama_skpd']);
            $stmt->bindParam(':alamat', $data['alamat']);
            $stmt->bindParam(':telepon', $data['telepon']);
            $stmt->bindParam(':email', $data['email']);
            $stmt->bindParam(':kategori', $data['kategori']);

            if ($stmt->execute()) {
                return [
                    'success' => true,
                    'message' => 'Data SKPD berhasil ditambahkan',
                    'id' => $this->conn->lastInsertId()
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Gagal menambahkan data SKPD'
                ];
            }
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                return [
                    'success' => false,
                    'message' => 'Email sudah digunakan oleh SKPD lain'
                ];
            }
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }

    // Method untuk mengupdate SKPD
    public function updateSKPD($id, $data) {
        try {
            $query = "UPDATE " . $this->table_name . "
                      SET nama_skpd = :nama_skpd,
                          alamat = :alamat,
                          telepon = :telepon,
                          email = :email,
                          kategori = :kategori
                      WHERE id_skpd = :id";

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':nama_skpd', $data['nama_skpd']);
            $stmt->bindParam(':alamat', $data['alamat']);
            $stmt->bindParam(':telepon', $data['telepon']);
            $stmt->bindParam(':email', $data['email']);
            $stmt->bindParam(':kategori', $data['kategori']);

            if ($stmt->execute()) {
                return [
                    'success' => true,
                    'message' => 'Data SKPD berhasil diperbarui'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Gagal memperbarui data SKPD'
                ];
            }
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                return [
                    'success' => false,
                    'message' => 'Email sudah digunakan oleh SKPD lain'
                ];
            }
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }

    // Method untuk menghapus SKPD
    public function deleteSKPD($id) {
        try {
            $query = "DELETE FROM " . $this->table_name . " WHERE id_skpd = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);

            if ($stmt->execute()) {
                if ($stmt->rowCount() > 0) {
                    return [
                        'success' => true,
                        'message' => 'Data SKPD berhasil dihapus'
                    ];
                } else {
                    return [
                        'success' => false,
                        'message' => 'Data SKPD tidak ditemukan'
                    ];
                }
            } else {
                return [
                    'success' => false,
                    'message' => 'Gagal menghapus data SKPD'
                ];
            }
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }

    // Method untuk pencarian SKPD
    public function searchSKPD($keyword) {
        $query = "SELECT * FROM " . $this->table_name . "
                  WHERE nama_skpd LIKE :keyword
                     OR alamat LIKE :keyword
                     OR telepon LIKE :keyword
                     OR email LIKE :keyword
                     OR kategori LIKE :keyword
                  ORDER BY nama_skpd ASC";

        $stmt = $this->conn->prepare($query);
        $searchTerm = '%' . $keyword . '%';
        $stmt->bindParam(':keyword', $searchTerm);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Method untuk pencarian SKPD dengan pagination
    public function searchSKPDWithPagination($keyword, $limit = 10, $offset = 0) {
        $query = "SELECT * FROM " . $this->table_name . "
                  WHERE nama_skpd LIKE :keyword
                     OR alamat LIKE :keyword
                     OR telepon LIKE :keyword
                     OR email LIKE :keyword
                     OR kategori LIKE :keyword
                  ORDER BY nama_skpd ASC
                  LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($query);
        $searchTerm = '%' . $keyword . '%';
        $stmt->bindParam(':keyword', $searchTerm);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Method untuk mendapatkan total hasil pencarian
    public function getSearchResultCount($keyword) {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . "
                  WHERE nama_skpd LIKE :keyword
                     OR alamat LIKE :keyword
                     OR telepon LIKE :keyword
                     OR email LIKE :keyword
                     OR kategori LIKE :keyword";

        $stmt = $this->conn->prepare($query);
        $searchTerm = '%' . $keyword . '%';
        $stmt->bindParam(':keyword', $searchTerm);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    // Method untuk mendapatkan total data SKPD
    public function getTotalSKPD() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    // Method untuk validasi email unique
    public function isEmailUnique($email, $excludeId = null) {
        $query = "SELECT COUNT(*) as count FROM " . $this->table_name . " WHERE email = :email";
        if ($excludeId) {
            $query .= " AND id_skpd != :exclude_id";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        if ($excludeId) {
            $stmt->bindParam(':exclude_id', $excludeId);
        }
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] == 0;
    }

    // Method untuk mendapatkan kategori unik
    public function getUniqueCategories() {
        $query = "SELECT DISTINCT kategori FROM " . $this->table_name . "
                  WHERE kategori IS NOT NULL AND kategori != ''
                  ORDER BY kategori ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    // Method untuk mendapatkan SKPD berdasarkan kategori
    public function getSKPDByKategori($kategori) {
        try {
            $query = "SELECT id_skpd, nama_skpd, kategori, email, telepon, alamat
                      FROM " . $this->table_name . "
                      WHERE kategori = :kategori
                      ORDER BY nama_skpd ASC";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':kategori', $kategori);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getSKPDByKategori: " . $e->getMessage());
            return [];
        }
    }

    // Method untuk mendapatkan SKPD berdasarkan kategori (untuk disposisi)
    public function getSKPDForDisposisi() {
        $query = "SELECT id_skpd, nama_skpd, kategori FROM " . $this->table_name . "
                  ORDER BY kategori ASC, nama_skpd ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Method untuk mendapatkan SKPD berdasarkan kategori tertentu
    public function getSKPDByCategory($category) {
        $query = "SELECT id_skpd, nama_skpd, kategori FROM " . $this->table_name . "
                  WHERE kategori = :category
                  ORDER BY nama_skpd ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':category', $category);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>