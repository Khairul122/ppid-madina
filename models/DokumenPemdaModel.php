<?php
class DokumenPemdaModel {
    private $conn;
    private $table_name = "dokumen_pemda";

    public function __construct($db)
    {
        $this->conn = $db;

        // Debug: cek apakah koneksi berhasil
        if (!$this->conn) {
            error_log("Koneksi database gagal untuk DokumenPemdaModel");
        }
    }

    public function getAllDokumenPemda($limit = null, $offset = null) {
        if (!$this->conn) {
            return [];
        }

        $query = "SELECT dp.*, k.nama_kategori
                  FROM " . $this->table_name . " dp
                  LEFT JOIN kategori k ON dp.id_kategori = k.id_kategori
                  ORDER BY dp.created_at DESC";

        if ($limit) {
            $query .= " LIMIT :limit";
            if ($offset) {
                $query .= " OFFSET :offset";
            }
        }

        try {
            $stmt = $this->conn->prepare($query);

            if ($limit) {
                $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
                if ($offset) {
                    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
                }
            }

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting dokumen pemda: " . $e->getMessage());
            return [];
        }
    }

    public function getDokumenPemdaById($id) {
        if (!$this->conn) {
            return null;
        }

        $query = "SELECT dp.*, k.nama_kategori
                  FROM " . $this->table_name . " dp
                  LEFT JOIN kategori k ON dp.id_kategori = k.id_kategori
                  WHERE dp.id_dokumen_pemda = :id";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting dokumen pemda by ID: " . $e->getMessage());
            return null;
        }
    }

    public function createDokumenPemda($data) {
        if (!$this->conn) {
            return false;
        }

        $query = "INSERT INTO " . $this->table_name . " (nama_jenis, id_kategori, area)
                  VALUES (:nama_jenis, :id_kategori, :area)";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':nama_jenis', $data['nama_jenis']);
            $stmt->bindParam(':id_kategori', $data['id_kategori'], PDO::PARAM_INT);
            $stmt->bindParam(':area', $data['area']);

            if ($stmt->execute()) {
                return $this->conn->lastInsertId();
            }
            return false;
        } catch (PDOException $e) {
            error_log("Error creating dokumen pemda: " . $e->getMessage());
            return false;
        }
    }

    public function updateDokumenPemda($id, $data) {
        if (!$this->conn) {
            return false;
        }

        $query = "UPDATE " . $this->table_name . "
                  SET nama_jenis = :nama_jenis,
                      id_kategori = :id_kategori,
                      area = :area,
                      updated_at = CURRENT_TIMESTAMP
                  WHERE id_dokumen_pemda = :id";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':nama_jenis', $data['nama_jenis']);
            $stmt->bindParam(':id_kategori', $data['id_kategori'], PDO::PARAM_INT);
            $stmt->bindParam(':area', $data['area']);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error updating dokumen pemda: " . $e->getMessage());
            return false;
        }
    }

    public function deleteDokumenPemda($id) {
        if (!$this->conn) {
            return false;
        }

        $query = "DELETE FROM " . $this->table_name . " WHERE id_dokumen_pemda = :id";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error deleting dokumen pemda: " . $e->getMessage());
            return false;
        }
    }

    public function getTotalCount() {
        if (!$this->conn) {
            return 0;
        }

        $query = "SELECT COUNT(*) as total FROM " . $this->table_name;

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (PDOException $e) {
            error_log("Error getting total count: " . $e->getMessage());
            return 0;
        }
    }

    public function getTotalDokumenPemda() {
        return $this->getTotalCount();
    }

    public function getSearchResultCount($search) {
        if (!$this->conn) {
            return 0;
        }

        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " dp
                  LEFT JOIN kategori k ON dp.id_kategori = k.id_kategori
                  WHERE dp.nama_jenis LIKE :keyword
                  OR k.nama_kategori LIKE :keyword
                  OR dp.area LIKE :keyword";

        try {
            $stmt = $this->conn->prepare($query);
            $searchKeyword = "%$search%";
            $stmt->bindParam(':keyword', $searchKeyword);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (PDOException $e) {
            error_log("Error getting search result count: " . $e->getMessage());
            return 0;
        }
    }

    public function searchDokumenPemda($keyword, $limit = null, $offset = null) {
        if (!$this->conn) {
            return [];
        }

        $query = "SELECT dp.*, k.nama_kategori
                  FROM " . $this->table_name . " dp
                  LEFT JOIN kategori k ON dp.id_kategori = k.id_kategori
                  WHERE dp.nama_jenis LIKE :keyword
                  OR k.nama_kategori LIKE :keyword
                  OR dp.area LIKE :keyword
                  ORDER BY dp.created_at DESC";

        if ($limit) {
            $query .= " LIMIT :limit";
            if ($offset) {
                $query .= " OFFSET :offset";
            }
        }

        try {
            $stmt = $this->conn->prepare($query);
            $searchKeyword = "%$keyword%";
            $stmt->bindParam(':keyword', $searchKeyword);

            if ($limit) {
                $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
                if ($offset) {
                    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
                }
            }

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error searching dokumen pemda: " . $e->getMessage());
            return [];
        }
    }

    public function getAllKategori() {
        if (!$this->conn) {
            return [];
        }

        $query = "SELECT * FROM kategori ORDER BY nama_kategori ASC";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting kategori: " . $e->getMessage());
            return [];
        }
    }

    public function getKategoriOptions() {
        if (!$this->conn) {
            return [];
        }

        $query = "SELECT id_kategori, nama_kategori FROM kategori ORDER BY nama_kategori ASC";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting kategori options: " . $e->getMessage());
            return [];
        }
    }
}
?>