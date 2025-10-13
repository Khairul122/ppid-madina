<?php

class KategoriSertaMertaModel {
    private $db;

    public function __construct($database) {
        $this->db = $database;
    }
    
    // Method to get database connection
    public function getConnection() {
        return $this->db;
    }

    // Get all documents with category Serta Merta (id_kategori = 2) EXCEPT drafts
    public function getAllDokumenSertaMerta($limit = 10, $offset = 0) {
        try {
            $stmt = $this->db->prepare("
                SELECT d.*, k.nama_kategori, dp.nama_jenis as nama_dokumen_pemda
                FROM dokumen d
                LEFT JOIN kategori k ON d.id_kategori = k.id_kategori
                LEFT JOIN dokumen_pemda dp ON d.id_dokumen_pemda = dp.id_dokumen_pemda
                WHERE d.id_kategori = 2 AND d.status != 'draft'
                ORDER BY d.created_at DESC
                LIMIT :limit OFFSET :offset
            ");
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error in getAllDokumenSertaMerta: " . $e->getMessage());
            return [];
        }
    }

    // Get total count of Serta Merta documents EXCEPT drafts
    public function getTotalCount() {
        try {
            $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM dokumen WHERE id_kategori = 2 AND status != 'draft'");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (Exception $e) {
            error_log("Error in getTotalCount: " . $e->getMessage());
            return 0;
        }
    }

    // Search documents in Serta Merta category EXCEPT drafts
    public function searchDokumenSertaMerta($search, $limit = 10, $offset = 0) {
        try {
            $searchTerm = "%{$search}%";
            $stmt = $this->db->prepare("
                SELECT d.*, k.nama_kategori, dp.nama_jenis as nama_dokumen_pemda
                FROM dokumen d
                LEFT JOIN kategori k ON d.id_kategori = k.id_kategori
                LEFT JOIN dokumen_pemda dp ON d.id_dokumen_pemda = dp.id_dokumen_pemda
                WHERE d.id_kategori = 2 AND d.status != 'draft'
                AND (d.judul LIKE ? OR d.kandungan_informasi LIKE ? OR d.terbitkan_sebagai LIKE ?)
                ORDER BY d.created_at DESC
                LIMIT :limit OFFSET :offset
            ");
            $stmt->bindParam(1, $searchTerm, PDO::PARAM_STR);
            $stmt->bindParam(2, $searchTerm, PDO::PARAM_STR);
            $stmt->bindParam(3, $searchTerm, PDO::PARAM_STR);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error in searchDokumenSertaMerta: " . $e->getMessage());
            return [];
        }
    }

    // Get all documents with category Serta Merta (id_kategori = 2) - ALL statuses
    public function getAllDokumenSertaMertaAllStatus($limit = 10, $offset = 0) {
        try {
            $stmt = $this->db->prepare("
                SELECT d.*, k.nama_kategori, dp.nama_jenis as nama_dokumen_pemda
                FROM dokumen d
                LEFT JOIN kategori k ON d.id_kategori = k.id_kategori
                LEFT JOIN dokumen_pemda dp ON d.id_dokumen_pemda = dp.id_dokumen_pemda
                WHERE d.id_kategori = 2
                ORDER BY d.created_at DESC
                LIMIT :limit OFFSET :offset
            ");
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error in getAllDokumenSertaMertaAllStatus: " . $e->getMessage());
            return [];
        }
    }

    // Get total count of Serta Merta documents - ALL statuses
    public function getTotalCountAllStatus() {
        try {
            $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM dokumen WHERE id_kategori = 2");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (Exception $e) {
            error_log("Error in getTotalCountAllStatus: " . $e->getMessage());
            return 0;
        }
    }

    // Search documents in Serta Merta category - ALL statuses
    public function searchDokumenSertaMertaAllStatus($search, $limit = 10, $offset = 0) {
        try {
            $searchTerm = "%{$search}%";
            $stmt = $this->db->prepare("
                SELECT d.*, k.nama_kategori, dp.nama_jenis as nama_dokumen_pemda
                FROM dokumen d
                LEFT JOIN kategori k ON d.id_kategori = k.id_kategori
                LEFT JOIN dokumen_pemda dp ON d.id_dokumen_pemda = dp.id_dokumen_pemda
                WHERE d.id_kategori = 2
                AND (d.judul LIKE ? OR d.kandungan_informasi LIKE ? OR d.terbitkan_sebagai LIKE ?)
                ORDER BY d.created_at DESC
                LIMIT :limit OFFSET :offset
            ");
            $stmt->bindParam(1, $searchTerm, PDO::PARAM_STR);
            $stmt->bindParam(2, $searchTerm, PDO::PARAM_STR);
            $stmt->bindParam(3, $searchTerm, PDO::PARAM_STR);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error in searchDokumenSertaMertaAllStatus: " . $e->getMessage());
            return [];
        }
    }

    // Get document by ID
    public function getDokumenById($id) {
        try {
            $stmt = $this->db->prepare("
                SELECT d.*, k.nama_kategori, dp.nama_jenis as nama_dokumen_pemda
                FROM dokumen d
                LEFT JOIN kategori k ON d.id_kategori = k.id_kategori
                LEFT JOIN dokumen_pemda dp ON d.id_dokumen_pemda = dp.id_dokumen_pemda
                WHERE d.id_dokumen = ? AND d.id_kategori = 2
            ");
            $stmt->bindParam(1, $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error in getDokumenById: " . $e->getMessage());
            return null;
        }
    }

    // Create new document in Serta Merta category
    public function createDokumen($data) {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO dokumen (id_kategori, judul, kandungan_informasi, terbitkan_sebagai,
                                   id_dokumen_pemda, tipe_file, upload_file, status)
                VALUES (2, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->bindParam(1, $data['judul'], PDO::PARAM_STR);
            $stmt->bindParam(2, $data['kandungan_informasi'], PDO::PARAM_STR);
            $stmt->bindParam(3, $data['terbitkan_sebagai'], PDO::PARAM_STR);
            $stmt->bindParam(4, $data['id_dokumen_pemda'], PDO::PARAM_INT);
            $stmt->bindParam(5, $data['tipe_file'], PDO::PARAM_STR);
            $stmt->bindParam(6, $data['upload_file'], PDO::PARAM_STR);
            $stmt->bindParam(7, $data['status'], PDO::PARAM_STR);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error in createDokumen: " . $e->getMessage());
            return false;
        }
    }

    // Update document
    public function updateDokumen($id, $data) {
        try {
            $stmt = $this->db->prepare("
                UPDATE dokumen
                SET judul = ?, kandungan_informasi = ?, terbitkan_sebagai = ?,
                    id_dokumen_pemda = ?, tipe_file = ?, upload_file = ?, status = ?
                WHERE id_dokumen = ? AND id_kategori = 2
            ");
            $stmt->bindParam(1, $data['judul'], PDO::PARAM_STR);
            $stmt->bindParam(2, $data['kandungan_informasi'], PDO::PARAM_STR);
            $stmt->bindParam(3, $data['terbitkan_sebagai'], PDO::PARAM_STR);
            $stmt->bindParam(4, $data['id_dokumen_pemda'], PDO::PARAM_INT);
            $stmt->bindParam(5, $data['tipe_file'], PDO::PARAM_STR);
            $stmt->bindParam(6, $data['upload_file'], PDO::PARAM_STR);
            $stmt->bindParam(7, $data['status'], PDO::PARAM_STR);
            $stmt->bindParam(8, $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error in updateDokumen: " . $e->getMessage());
            return false;
        }
    }

    // Delete document
    public function deleteDokumen($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM dokumen WHERE id_dokumen = ? AND id_kategori = 2");
            $stmt->bindParam(1, $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error in deleteDokumen: " . $e->getMessage());
            return false;
        }
    }

    // Update status to draft
    public function updateStatusToDraft($id) {
        try {
            $stmt = $this->db->prepare("
                UPDATE dokumen
                SET status = 'draft'
                WHERE id_dokumen = ? AND id_kategori = 2
            ");
            $stmt->bindParam(1, $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error in updateStatusToDraft: " . $e->getMessage());
            return false;
        }
    }

    // Update status to publikasi
    public function updateStatusToPublikasi($id) {
        try {
            $stmt = $this->db->prepare("
                UPDATE dokumen
                SET status = 'publikasi'
                WHERE id_dokumen = ? AND id_kategori = 2
            ");
            $stmt->bindParam(1, $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error in updateStatusToPublikasi: " . $e->getMessage());
            return false;
        }
    }

    // Get draft documents only
    public function getDraftDokumen($limit = 10, $offset = 0) {
        try {
            $stmt = $this->db->prepare("
                SELECT d.*, k.nama_kategori, dp.nama_jenis as nama_dokumen_pemda
                FROM dokumen d
                LEFT JOIN kategori k ON d.id_kategori = k.id_kategori
                LEFT JOIN dokumen_pemda dp ON d.id_dokumen_pemda = dp.id_dokumen_pemda
                WHERE d.id_kategori = 2 AND d.status = 'draft'
                ORDER BY d.created_at DESC
                LIMIT :limit OFFSET :offset
            ");
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error in getDraftDokumen: " . $e->getMessage());
            return [];
        }
    }

    // Get total count of draft documents
    public function getTotalDraftCount() {
        try {
            $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM dokumen WHERE id_kategori = 2 AND status = 'draft'");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (Exception $e) {
            error_log("Error in getTotalDraftCount: " . $e->getMessage());
            return 0;
        }
    }

    // Get all dokumen_pemda for dropdown
    public function getAllDokumenPemda() {
        try {
            $stmt = $this->db->prepare("
                SELECT id_dokumen_pemda, nama_jenis
                FROM dokumen_pemda
                ORDER BY nama_jenis ASC
            ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error in getAllDokumenPemda: " . $e->getMessage());
            return [];
        }
    }

    // Search draft documents
    public function searchDraftDokumen($search, $limit = 10, $offset = 0) {
        try {
            $searchTerm = "%{$search}%";
            $stmt = $this->db->prepare("
                SELECT d.*, k.nama_kategori, dp.nama_jenis as nama_dokumen_pemda
                FROM dokumen d
                LEFT JOIN kategori k ON d.id_kategori = k.id_kategori
                LEFT JOIN dokumen_pemda dp ON d.id_dokumen_pemda = dp.id_dokumen_pemda
                WHERE d.id_kategori = 2 AND d.status = 'draft'
                AND (d.judul LIKE ? OR d.kandungan_informasi LIKE ? OR d.terbitkan_sebagai LIKE ?)
                ORDER BY d.created_at DESC
                LIMIT :limit OFFSET :offset
            ");
            $stmt->bindParam(1, $searchTerm, PDO::PARAM_STR);
            $stmt->bindParam(2, $searchTerm, PDO::PARAM_STR);
            $stmt->bindParam(3, $searchTerm, PDO::PARAM_STR);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error in searchDraftDokumen: " . $e->getMessage());
            return [];
        }
    }
}
?>