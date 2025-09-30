<?php
class KeberatanModel {
    private $conn;
    private $table_name = "keberatan";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function createKeberatan($data) {
        try {
            $this->conn->beginTransaction();

            $query = "INSERT INTO " . $this->table_name . "
                      (id_permohonan, id_users, alasan_keberatan, keterangan)
                      VALUES (:id_permohonan, :id_users, :alasan_keberatan, :keterangan)";

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':id_permohonan', $data['id_permohonan']);
            $stmt->bindParam(':id_users', $data['id_users']);
            $stmt->bindParam(':alasan_keberatan', $data['alasan_keberatan']);
            $stmt->bindParam(':keterangan', $data['keterangan']);

            $result = $stmt->execute();

            if ($result) {
                $keberatan_id = $this->conn->lastInsertId();
                $this->conn->commit();
                return [
                    'success' => true,
                    'id' => $keberatan_id,
                    'message' => 'Keberatan berhasil diajukan'
                ];
            } else {
                $this->conn->rollBack();
                return ['success' => false, 'message' => 'Gagal mengajukan keberatan'];
            }

        } catch (Exception $e) {
            $this->conn->rollBack();
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }

    public function getKeberatanByUserId($userId, $limit = 10, $offset = 0) {
        $query = "SELECT k.*, p.no_permohonan, p.judul_dokumen, p.tujuan_permohonan, p.status as status_permohonan,
                         u.username, bp.nama_lengkap, bp.nik
                  FROM " . $this->table_name . " k
                  JOIN permohonan p ON k.id_permohonan = p.id_permohonan
                  JOIN users u ON k.id_users = u.id_user
                  LEFT JOIN biodata_pengguna bp ON u.id_biodata = bp.id_biodata
                  WHERE k.id_users = :user_id
                  ORDER BY k.id_keberatan DESC
                  LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getKeberatanById($id, $userId = null) {
        $query = "SELECT k.*, p.no_permohonan, p.judul_dokumen, p.tujuan_permohonan, p.komponen_tujuan, p.status as status_permohonan,
                         u.username, bp.nama_lengkap, bp.nik
                  FROM " . $this->table_name . " k
                  JOIN permohonan p ON k.id_permohonan = p.id_permohonan
                  JOIN users u ON k.id_users = u.id_user
                  LEFT JOIN biodata_pengguna bp ON u.id_biodata = bp.id_biodata
                  WHERE k.id_keberatan = :id";

        $params = [':id' => $id];

        if ($userId) {
            $query .= " AND k.id_users = :user_id";
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

    public function countKeberatanByUserId($userId) {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " WHERE id_users = :user_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    public function getKeberatanStats($userId) {
        try {
            $query = "SELECT
                        COUNT(*) as total,
                        SUM(CASE WHEN k.status = 'pending' OR k.status IS NULL OR k.status = '' THEN 1 ELSE 0 END) as pending,
                        SUM(CASE WHEN k.status = 'approved' THEN 1 ELSE 0 END) as approved,
                        SUM(CASE WHEN k.status = 'rejected' THEN 1 ELSE 0 END) as rejected,
                        SUM(CASE WHEN k.status = 'process' THEN 1 ELSE 0 END) as process
                      FROM " . $this->table_name . " k
                      WHERE k.id_users = :user_id";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':user_id', $userId);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " WHERE id_users = :user_id";
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

    public function searchKeberatan($userId, $searchTerm, $limit = 10, $offset = 0) {
        $query = "SELECT k.*, p.no_permohonan, p.judul_dokumen, p.tujuan_permohonan, p.status as status_permohonan,
                         u.username, bp.nama_lengkap, bp.nik
                  FROM " . $this->table_name . " k
                  JOIN permohonan p ON k.id_permohonan = p.id_permohonan
                  JOIN users u ON k.id_users = u.id_user
                  LEFT JOIN biodata_pengguna bp ON u.id_biodata = bp.id_biodata
                  WHERE k.id_users = :user_id
                  AND (
                    p.no_permohonan LIKE :search
                    OR p.judul_dokumen LIKE :search
                    OR k.alasan_keberatan LIKE :search
                    OR k.keterangan LIKE :search
                  )
                  ORDER BY k.id_keberatan DESC
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
                  FROM " . $this->table_name . " k
                  JOIN permohonan p ON k.id_permohonan = p.id_permohonan
                  WHERE k.id_users = :user_id
                  AND (
                    p.no_permohonan LIKE :search
                    OR p.judul_dokumen LIKE :search
                    OR k.alasan_keberatan LIKE :search
                    OR k.keterangan LIKE :search
                  )";

        $stmt = $this->conn->prepare($query);
        $searchParam = '%' . $searchTerm . '%';

        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':search', $searchParam);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    public function getRecentKeberatan($userId, $limit = 5) {
        $query = "SELECT k.*, p.no_permohonan, p.judul_dokumen
                  FROM " . $this->table_name . " k
                  JOIN permohonan p ON k.id_permohonan = p.id_permohonan
                  WHERE k.id_users = :user_id
                  ORDER BY k.id_keberatan DESC
                  LIMIT :limit";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function checkExistingKeberatan($userId, $permohonanId) {
        $query = "SELECT COUNT(*) as count
                  FROM " . $this->table_name . "
                  WHERE id_users = :user_id AND id_permohonan = :permohonan_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':permohonan_id', $permohonanId);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }

    public function getAllKeberatan($limit = 50, $offset = 0) {
        $query = "SELECT k.*, p.no_permohonan, p.judul_dokumen, p.tujuan_permohonan, p.status as status_permohonan,
                         u.username, bp.nama_lengkap, bp.nik
                  FROM " . $this->table_name . " k
                  JOIN permohonan p ON k.id_permohonan = p.id_permohonan
                  JOIN users u ON k.id_users = u.id_user
                  LEFT JOIN biodata_pengguna bp ON u.id_biodata = bp.id_biodata
                  ORDER BY k.id_keberatan DESC
                  LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateStatus($id, $status) {
        $query = "UPDATE " . $this->table_name . "
                  SET status = :status
                  WHERE id_keberatan = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }
}
?>