<?php
class KategoriSetiapSaatModel {
    private $conn;
    private $table_name = "dokumen";
    private $kategori_id = 3; // Setiap Saat

    public function __construct($db) {
        $this->conn = $db;

        // Debug: cek apakah koneksi berhasil
        if (!$this->conn) {
            error_log("Koneksi database gagal untuk KategoriSetiapSaatModel");
        }
    }

    public function getAllDokumenSetiapSaat($limit = null, $offset = null) {
        if (!$this->conn) {
            return [];
        }

        $query = "SELECT d.*, k.nama_kategori, dp.nama_jenis as nama_dokumen_pemda
                  FROM " . $this->table_name . " d
                  LEFT JOIN kategori k ON d.id_kategori = k.id_kategori
                  LEFT JOIN dokumen_pemda dp ON d.id_dokumen_pemda = dp.id_dokumen_pemda
                  WHERE d.id_kategori = :kategori_id AND d.status != 'draft'
                  ORDER BY d.created_at DESC";

        if ($limit) {
            $query .= " LIMIT :limit";
            if ($offset) {
                $query .= " OFFSET :offset";
            }
        }

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':kategori_id', $this->kategori_id, PDO::PARAM_INT);

            if ($limit) {
                $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
                if ($offset) {
                    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
                }
            }

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting dokumen setiap saat: " . $e->getMessage());
            return [];
        }
    }

    public function getDokumenById($id) {
        if (!$this->conn) {
            return null;
        }

        $query = "SELECT d.*, k.nama_kategori, dp.nama_jenis as nama_dokumen_pemda
                  FROM " . $this->table_name . " d
                  LEFT JOIN kategori k ON d.id_kategori = k.id_kategori
                  LEFT JOIN dokumen_pemda dp ON d.id_dokumen_pemda = dp.id_dokumen_pemda
                  WHERE d.id_dokumen = :id AND d.id_kategori = :kategori_id";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':kategori_id', $this->kategori_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting dokumen by ID: " . $e->getMessage());
            return null;
        }
    }

    public function createDokumen($data) {
        if (!$this->conn) {
            return false;
        }

        $query = "INSERT INTO " . $this->table_name . "
                  (id_kategori, judul, kandungan_informasi, terbitkan_sebagai, id_dokumen_pemda, tipe_file, upload_file, status)
                  VALUES (:id_kategori, :judul, :kandungan_informasi, :terbitkan_sebagai, :id_dokumen_pemda, :tipe_file, :upload_file, :status)";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_kategori', $this->kategori_id, PDO::PARAM_INT);
            $stmt->bindParam(':judul', $data['judul']);
            $stmt->bindParam(':kandungan_informasi', $data['kandungan_informasi']);
            $stmt->bindParam(':terbitkan_sebagai', $data['terbitkan_sebagai']);
            $stmt->bindParam(':id_dokumen_pemda', $data['id_dokumen_pemda'], PDO::PARAM_INT);
            $stmt->bindParam(':tipe_file', $data['tipe_file']);
            $stmt->bindParam(':upload_file', $data['upload_file']);
            $stmt->bindParam(':status', $data['status']);

            if ($stmt->execute()) {
                return $this->conn->lastInsertId();
            }
            return false;
        } catch (PDOException $e) {
            error_log("Error creating dokumen setiap saat: " . $e->getMessage());
            return false;
        }
    }

    public function updateDokumen($id, $data) {
        if (!$this->conn) {
            return false;
        }

        $query = "UPDATE " . $this->table_name . "
                  SET judul = :judul,
                      kandungan_informasi = :kandungan_informasi,
                      terbitkan_sebagai = :terbitkan_sebagai,
                      id_dokumen_pemda = :id_dokumen_pemda,
                      tipe_file = :tipe_file,
                      upload_file = :upload_file,
                      status = :status,
                      updated_at = CURRENT_TIMESTAMP
                  WHERE id_dokumen = :id AND id_kategori = :kategori_id";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':kategori_id', $this->kategori_id, PDO::PARAM_INT);
            $stmt->bindParam(':judul', $data['judul']);
            $stmt->bindParam(':kandungan_informasi', $data['kandungan_informasi']);
            $stmt->bindParam(':terbitkan_sebagai', $data['terbitkan_sebagai']);
            $stmt->bindParam(':id_dokumen_pemda', $data['id_dokumen_pemda'], PDO::PARAM_INT);
            $stmt->bindParam(':tipe_file', $data['tipe_file']);
            $stmt->bindParam(':upload_file', $data['upload_file']);
            $stmt->bindParam(':status', $data['status']);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error updating dokumen setiap saat: " . $e->getMessage());
            return false;
        }
    }

    public function deleteDokumen($id) {
        if (!$this->conn) {
            return false;
        }

        $query = "DELETE FROM " . $this->table_name . " WHERE id_dokumen = :id AND id_kategori = :kategori_id";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':kategori_id', $this->kategori_id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error deleting dokumen setiap saat: " . $e->getMessage());
            return false;
        }
    }

    public function getTotalCount() {
        if (!$this->conn) {
            return 0;
        }

        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " WHERE id_kategori = :kategori_id AND status != 'draft'";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':kategori_id', $this->kategori_id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (PDOException $e) {
            error_log("Error getting total count: " . $e->getMessage());
            return 0;
        }
    }

    public function searchDokumenSetiapSaat($keyword, $limit = null, $offset = null) {
        if (!$this->conn) {
            return [];
        }

        $query = "SELECT d.*, k.nama_kategori, dp.nama_jenis as nama_dokumen_pemda
                  FROM " . $this->table_name . " d
                  LEFT JOIN kategori k ON d.id_kategori = k.id_kategori
                  LEFT JOIN dokumen_pemda dp ON d.id_dokumen_pemda = dp.id_dokumen_pemda
                  WHERE d.id_kategori = :kategori_id AND d.status != 'draft'
                  AND (d.judul LIKE :keyword
                       OR d.kandungan_informasi LIKE :keyword
                       OR d.terbitkan_sebagai LIKE :keyword
                       OR dp.nama_jenis LIKE :keyword)
                  ORDER BY d.created_at DESC";

        if ($limit) {
            $query .= " LIMIT :limit";
            if ($offset) {
                $query .= " OFFSET :offset";
            }
        }

        try {
            $stmt = $this->conn->prepare($query);
            $searchKeyword = "%$keyword%";
            $stmt->bindParam(':kategori_id', $this->kategori_id, PDO::PARAM_INT);
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
            error_log("Error searching dokumen setiap saat: " . $e->getMessage());
            return [];
        }
    }

    public function getDraftDokumen($limit = null, $offset = null) {
        if (!$this->conn) {
            return [];
        }

        $query = "SELECT d.*, k.nama_kategori, dp.nama_jenis as nama_dokumen_pemda
                  FROM " . $this->table_name . " d
                  LEFT JOIN kategori k ON d.id_kategori = k.id_kategori
                  LEFT JOIN dokumen_pemda dp ON d.id_dokumen_pemda = dp.id_dokumen_pemda
                  WHERE d.id_kategori = :kategori_id AND d.status = 'draft'
                  ORDER BY d.created_at DESC";

        if ($limit) {
            $query .= " LIMIT :limit";
            if ($offset) {
                $query .= " OFFSET :offset";
            }
        }

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':kategori_id', $this->kategori_id, PDO::PARAM_INT);

            if ($limit) {
                $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
                if ($offset) {
                    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
                }
            }

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting draft dokumen setiap saat: " . $e->getMessage());
            return [];
        }
    }

    public function getTotalDraftCount() {
        if (!$this->conn) {
            return 0;
        }

        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " WHERE id_kategori = :kategori_id AND status = 'draft'";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':kategori_id', $this->kategori_id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (PDOException $e) {
            error_log("Error getting total draft count: " . $e->getMessage());
            return 0;
        }
    }

    public function searchDraftDokumen($keyword, $limit = null, $offset = null) {
        if (!$this->conn) {
            return [];
        }

        $query = "SELECT d.*, k.nama_kategori, dp.nama_jenis as nama_dokumen_pemda
                  FROM " . $this->table_name . " d
                  LEFT JOIN kategori k ON d.id_kategori = k.id_kategori
                  LEFT JOIN dokumen_pemda dp ON d.id_dokumen_pemda = dp.id_dokumen_pemda
                  WHERE d.id_kategori = :kategori_id AND d.status = 'draft'
                  AND (d.judul LIKE :keyword
                       OR d.kandungan_informasi LIKE :keyword
                       OR d.terbitkan_sebagai LIKE :keyword
                       OR dp.nama_jenis LIKE :keyword)
                  ORDER BY d.created_at DESC";

        if ($limit) {
            $query .= " LIMIT :limit";
            if ($offset) {
                $query .= " OFFSET :offset";
            }
        }

        try {
            $stmt = $this->conn->prepare($query);
            $searchKeyword = "%$keyword%";
            $stmt->bindParam(':kategori_id', $this->kategori_id, PDO::PARAM_INT);
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
            error_log("Error searching draft dokumen setiap saat: " . $e->getMessage());
            return [];
        }
    }

    public function updateStatusToDraft($id) {
        if (!$this->conn) {
            return false;
        }

        $query = "UPDATE " . $this->table_name . " SET status = 'draft', updated_at = CURRENT_TIMESTAMP
                  WHERE id_dokumen = :id AND id_kategori = :kategori_id";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':kategori_id', $this->kategori_id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error updating status to draft: " . $e->getMessage());
            return false;
        }
    }

    public function updateStatusToPublikasi($id) {
        if (!$this->conn) {
            return false;
        }

        $query = "UPDATE " . $this->table_name . " SET status = 'publikasi', updated_at = CURRENT_TIMESTAMP
                  WHERE id_dokumen = :id AND id_kategori = :kategori_id";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':kategori_id', $this->kategori_id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error updating status to publikasi: " . $e->getMessage());
            return false;
        }
    }

    public function getAllDokumenPemda() {
        if (!$this->conn) {
            return [];
        }

        $query = "SELECT * FROM dokumen_pemda ORDER BY nama_jenis ASC";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting dokumen pemda: " . $e->getMessage());
            return [];
        }
    }

    public function getAllDokumenSetiapSaatAllStatus($limit = null, $offset = null) {
        if (!$this->conn) {
            return [];
        }

        $query = "SELECT d.*, k.nama_kategori, dp.nama_jenis as nama_dokumen_pemda
                  FROM " . $this->table_name . " d
                  LEFT JOIN kategori k ON d.id_kategori = k.id_kategori
                  LEFT JOIN dokumen_pemda dp ON d.id_dokumen_pemda = dp.id_dokumen_pemda
                  WHERE d.id_kategori = :kategori_id
                  ORDER BY d.created_at DESC";

        if ($limit) {
            $query .= " LIMIT :limit";
            if ($offset) {
                $query .= " OFFSET :offset";
            }
        }

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':kategori_id', $this->kategori_id, PDO::PARAM_INT);

            if ($limit) {
                $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
                if ($offset) {
                    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
                }
            }

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting dokumen setiap saat all status: " . $e->getMessage());
            return [];
        }
    }

    public function getTotalCountAllStatus() {
        if (!$this->conn) {
            return 0;
        }

        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " WHERE id_kategori = :kategori_id";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':kategori_id', $this->kategori_id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (PDOException $e) {
            error_log("Error getting total count all status: " . $e->getMessage());
            return 0;
        }
    }

    public function searchDokumenSetiapSaatAllStatus($keyword, $limit = null, $offset = null) {
        if (!$this->conn) {
            return [];
        }

        $query = "SELECT d.*, k.nama_kategori, dp.nama_jenis as nama_dokumen_pemda
                  FROM " . $this->table_name . " d
                  LEFT JOIN kategori k ON d.id_kategori = k.id_kategori
                  LEFT JOIN dokumen_pemda dp ON d.id_dokumen_pemda = dp.id_dokumen_pemda
                  WHERE d.id_kategori = :kategori_id
                  AND (d.judul LIKE :keyword
                       OR d.kandungan_informasi LIKE :keyword
                       OR d.terbitkan_sebagai LIKE :keyword
                       OR dp.nama_jenis LIKE :keyword)
                  ORDER BY d.created_at DESC";

        if ($limit) {
            $query .= " LIMIT :limit";
            if ($offset) {
                $query .= " OFFSET :offset";
            }
        }

        try {
            $stmt = $this->conn->prepare($query);
            $searchKeyword = "%$keyword%";
            $stmt->bindParam(':kategori_id', $this->kategori_id, PDO::PARAM_INT);
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
            error_log("Error searching dokumen setiap saat all status: " . $e->getMessage());
            return [];
        }
    }
}
?>