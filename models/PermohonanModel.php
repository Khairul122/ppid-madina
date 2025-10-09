<?php
class PermohonanModel {
    private $conn;
    private $table_name = "permohonan";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getPermohonanByUserId($userId, $limit = 10, $offset = 0, $status = null) {
        $whereClause = "WHERE p.id_user = :user_id";
        $params = [':user_id' => $userId];

        if ($status && $status !== 'all') {
            $whereClause .= " AND p.status = :status";
            $params[':status'] = $status;
        }

        $query = "SELECT p.*, u.username, u.email as user_email,
                         bp.nama_lengkap, bp.nik, bp.alamat, bp.provinsi, bp.city,
                         bp.no_kontak, bp.email, bp.foto_profile, bp.status_pengguna
                  FROM " . $this->table_name . " p
                  JOIN users u ON p.id_user = u.id_user
                  LEFT JOIN biodata_pengguna bp ON u.id_biodata = bp.id_biodata
                  " . $whereClause . "
                  ORDER BY p.id_permohonan DESC
                  LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($query);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPermohonanById($id, $userId = null) {
        $query = "SELECT p.*, u.username, u.email as user_email,
                         bp.nama_lengkap, bp.nik, bp.alamat, bp.provinsi, bp.city,
                         bp.no_kontak, bp.email, bp.foto_profile, bp.status_pengguna,
                         bp.jenis_kelamin, bp.usia, bp.pendidikan, bp.pekerjaan,
                         bp.nama_lembaga, bp.upload_ktp, bp.upload_akta
                  FROM " . $this->table_name . " p
                  JOIN users u ON p.id_user = u.id_user
                  LEFT JOIN biodata_pengguna bp ON u.id_biodata = bp.id_biodata
                  WHERE p.id_permohonan = :id";

        $params = [':id' => $id];

        if ($userId) {
            $query .= " AND p.id_user = :user_id";
            $params[':user_id'] = $userId;
        }

        $query .= " LIMIT 1";

        $stmt = $this->conn->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function countPermohonanByUserId($userId, $status = null) {
        $whereClause = "WHERE id_user = :user_id";
        $params = [':user_id' => $userId];

        if ($status && $status !== 'all') {
            $whereClause .= " AND status = :status";
            $params[':status'] = $status;
        }

        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " " . $whereClause;

        $stmt = $this->conn->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    public function getPermohonanStats($userId) {
        try {
            $query = "SELECT
                        COUNT(*) as total,
                        SUM(CASE WHEN status = 'pending' OR status IS NULL OR status = '' THEN 1 ELSE 0 END) as pending,
                        SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved,
                        SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected,
                        SUM(CASE WHEN status = 'process' THEN 1 ELSE 0 END) as process
                      FROM " . $this->table_name . "
                      WHERE id_user = :user_id";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':user_id', $userId);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " WHERE id_user = :user_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':user_id', $userId);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return [
                'total' => $result['total'],
                'pending' => $result['total'],
                'approved' => 0,
                'rejected' => 0,
                'process' => 0
            ];
        }
    }

    public function searchPermohonan($userId, $searchTerm, $limit = 10, $offset = 0) {
        $query = "SELECT p.*, u.username, bp.nama_lengkap, bp.nik
                  FROM " . $this->table_name . " p
                  JOIN users u ON p.id_user = u.id_user
                  LEFT JOIN biodata_pengguna bp ON u.id_biodata = bp.id_biodata
                  WHERE p.id_user = :user_id
                  AND (
                    p.no_permohonan LIKE :search
                    OR p.tujuan_permohonan LIKE :search
                    OR p.judul_dokumen LIKE :search
                    OR p.komponen_tujuan LIKE :search
                  )
                  ORDER BY p.id_permohonan DESC
                  LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($query);
        $searchParam = '%' . $searchTerm . '%';

        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':search', $searchParam);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countSearchResults($userId, $searchTerm) {
        $query = "SELECT COUNT(*) as total
                  FROM " . $this->table_name . "
                  WHERE id_user = :user_id
                  AND (
                    no_permohonan LIKE :search
                    OR tujuan_permohonan LIKE :search
                    OR judul_dokumen LIKE :search
                    OR komponen_tujuan LIKE :search
                  )";

        $stmt = $this->conn->prepare($query);
        $searchParam = '%' . $searchTerm . '%';

        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':search', $searchParam);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    public function getRecentPermohonan($userId, $limit = 5) {
        $query = "SELECT p.*, u.username
                  FROM " . $this->table_name . " p
                  JOIN users u ON p.id_user = u.id_user
                  WHERE p.id_user = :user_id
                  ORDER BY p.id_permohonan DESC
                  LIMIT :limit";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deletePermohonan($id, $userId) {
        try {
            $this->conn->beginTransaction();

            $permohonan = $this->getPermohonanById($id, $userId);

            if ($permohonan) {
                if (isset($permohonan['status']) && $permohonan['status'] !== 'pending' && $permohonan['status'] !== '') {
                    $this->conn->rollBack();
                    return [
                        'success' => false,
                        'message' => 'Permohonan yang sudah diproses tidak dapat dihapus'
                    ];
                }

                if ($permohonan['upload_foto_identitas'] && file_exists($permohonan['upload_foto_identitas'])) {
                    unlink($permohonan['upload_foto_identitas']);
                }
                if ($permohonan['upload_data_pedukung'] && file_exists($permohonan['upload_data_pedukung'])) {
                    unlink($permohonan['upload_data_pedukung']);
                }

                // Delete related keberatan first (foreign key constraint)
                $deleteKeberatanQuery = "DELETE FROM keberatan WHERE id_permohonan = :id";
                $keberatanStmt = $this->conn->prepare($deleteKeberatanQuery);
                $keberatanStmt->bindParam(':id', $id);
                $keberatanStmt->execute();

                $query = "DELETE FROM " . $this->table_name . " WHERE id_permohonan = :id AND id_user = :user_id";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':id', $id);
                $stmt->bindParam(':user_id', $userId);

                if ($stmt->execute()) {
                    $this->conn->commit();
                    return [
                        'success' => true,
                        'message' => 'Permohonan berhasil dihapus'
                    ];
                } else {
                    $this->conn->rollBack();
                    return [
                        'success' => false,
                        'message' => 'Gagal menghapus permohonan'
                    ];
                }
            }

        } catch (Exception $e) {
            $this->conn->rollBack();
            return [
                'success' => false,
                'message' => 'Gagal menghapus permohonan: ' . $e->getMessage()
            ];
        }

        return [
            'success' => false,
            'message' => 'Permohonan tidak ditemukan'
        ];
    }

    public function saveLayananKepuasan($data) {
        try {
            $query = "INSERT INTO layanan_kepuasan
                      (id_permohonan, nama, umur, provinsi, kota, permohonan_informasi, rating, created_at)
                      VALUES
                      (:id_permohonan, :nama, :umur, :provinsi, :kota, :permohonan_informasi, :rating, NOW())";

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':id_permohonan', $data['id_permohonan']);
            $stmt->bindParam(':nama', $data['nama']);
            $stmt->bindParam(':umur', $data['umur']);
            $stmt->bindParam(':provinsi', $data['provinsi']);
            $stmt->bindParam(':kota', $data['kota']);
            $stmt->bindParam(':permohonan_informasi', $data['permohonan_informasi']);
            $stmt->bindParam(':rating', $data['rating']);

            if ($stmt->execute()) {
                return [
                    'success' => true,
                    'message' => 'Penilaian layanan kepuasan berhasil disimpan'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Gagal menyimpan penilaian layanan kepuasan'
                ];
            }
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }

    public function updateStatus($id, $status) {
        try {
            $query = "UPDATE " . $this->table_name . " 
                      SET status = :status 
                      WHERE id_permohonan = :id";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':status', $status);

            if ($stmt->execute()) {
                return [
                    'success' => true,
                    'message' => 'Status permohonan berhasil diperbarui'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Gagal memperbarui status permohonan'
                ];
            }
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }
}
?>