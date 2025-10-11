<?php
class PermohonanAdminModel {
    private $conn;
    private $table_permohonan = 'permohonan';
    private $table_users = 'users';
    private $table_biodata = 'biodata_pengguna';
    private $table_skpd = 'skpd';
    private $table_tujuan = 'tujuan_permohonan';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Core Methods
    public function getAllPermohonan($limit, $offset, $status = null, $search = null) {
        try {
            $query = "SELECT p.*,
                             u.nama_lengkap as nama_pemohon,
                             u.email as email_pemohon,
                             b.nik,
                             b.no_hp,
                             tp.nama_tujuan,
                             tp.komponen_tujuan
                      FROM " . $this->table_permohonan . " p
                      LEFT JOIN " . $this->table_users . " u ON p.id_user = u.id_user
                      LEFT JOIN " . $this->table_biodata . " b ON u.id_biodata = b.id_biodata
                      LEFT JOIN " . $this->table_tujuan . " tp ON p.id_tujuan_permohonan = tp.id_tujuan_permohonan
                      WHERE 1=1";

            $params = [];

            if ($status !== null && $status !== '') {
                $query .= " AND p.status = :status";
                $params[':status'] = $status;
            }

            if ($search !== null && $search !== '') {
                $query .= " AND (p.no_permohonan LIKE :search
                           OR u.nama_lengkap LIKE :search
                           OR b.nik LIKE :search
                           OR p.judul_permohonan LIKE :search
                           OR tp.nama_tujuan LIKE :search)";
                $params[':search'] = "%{$search}%";
            }

            $query .= " ORDER BY p.tanggal_permohonan DESC LIMIT :limit OFFSET :offset";

            $stmt = $this->conn->prepare($query);

            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }

            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getAllPermohonan: " . $e->getMessage());
            return [];
        }
    }

    public function countAllPermohonan($status = null, $search = null) {
        try {
            $query = "SELECT COUNT(*) as total
                      FROM " . $this->table_permohonan . " p
                      LEFT JOIN " . $this->table_users . " u ON p.id_user = u.id_user
                      LEFT JOIN " . $this->table_biodata . " b ON u.id_biodata = b.id_biodata
                      LEFT JOIN " . $this->table_tujuan . " tp ON p.id_tujuan_permohonan = tp.id_tujuan_permohonan
                      WHERE 1=1";

            $params = [];

            if ($status !== null && $status !== '') {
                $query .= " AND p.status = :status";
                $params[':status'] = $status;
            }

            if ($search !== null && $search !== '') {
                $query .= " AND (p.no_permohonan LIKE :search
                           OR u.nama_lengkap LIKE :search
                           OR b.nik LIKE :search
                           OR p.judul_permohonan LIKE :search
                           OR tp.nama_tujuan LIKE :search)";
                $params[':search'] = "%{$search}%";
            }

            $stmt = $this->conn->prepare($query);

            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }

            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (PDOException $e) {
            error_log("Error in countAllPermohonan: " . $e->getMessage());
            return 0;
        }
    }

    public function getPermohonanById($id) {
        try {
            $query = "SELECT p.*,
                             u.nama_lengkap as nama_pemohon,
                             u.email as email_pemohon,
                             u.username,
                             b.id_biodata,
                             b.nik,
                             b.no_hp,
                             b.alamat,
                             b.pekerjaan,
                             b.pendidikan,
                             tp.nama_tujuan,
                             tp.komponen_tujuan,
                             s.nama_skpd,
                             s.kategori as kategori_skpd
                      FROM " . $this->table_permohonan . " p
                      LEFT JOIN " . $this->table_users . " u ON p.id_user = u.id_user
                      LEFT JOIN " . $this->table_biodata . " b ON u.id_biodata = b.id_biodata
                      LEFT JOIN " . $this->table_tujuan . " tp ON p.id_tujuan_permohonan = tp.id_tujuan_permohonan
                      LEFT JOIN " . $this->table_skpd . " s ON p.id_skpd = s.id_skpd
                      WHERE p.id_permohonan = :id";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getPermohonanById: " . $e->getMessage());
            return null;
        }
    }

    public function getAllSKPD() {
        try {
            $query = "SELECT * FROM " . $this->table_skpd . " ORDER BY nama_skpd ASC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getAllSKPD: " . $e->getMessage());
            return [];
        }
    }

    public function generateNoPermohonan() {
        try {
            $query = "SELECT no_permohonan FROM " . $this->table_permohonan . "
                      WHERE no_permohonan LIKE 'PMH%'
                      ORDER BY no_permohonan DESC LIMIT 1";

            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                $lastNo = intval(substr($result['no_permohonan'], 3));
                $newNo = $lastNo + 1;
            } else {
                $newNo = 1;
            }

            return 'PMH' . str_pad($newNo, 6, '0', STR_PAD_LEFT);
        } catch (PDOException $e) {
            error_log("Error in generateNoPermohonan: " . $e->getMessage());
            return 'PMH000001';
        }
    }

    public function createPermohonan($data) {
        try {
            $query = "INSERT INTO " . $this->table_permohonan . "
                      (id_user, no_permohonan, id_tujuan_permohonan, id_skpd,
                       judul_permohonan, isi_permohonan, file_identitas, file_pendukung,
                       tanggal_permohonan, status, catatan_petugas)
                      VALUES (:id_user, :no_permohonan, :id_tujuan_permohonan, :id_skpd,
                              :judul_permohonan, :isi_permohonan, :file_identitas, :file_pendukung,
                              :tanggal_permohonan, :status, :catatan_petugas)";

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':id_user', $data['id_user']);
            $stmt->bindParam(':no_permohonan', $data['no_permohonan']);
            $stmt->bindParam(':id_tujuan_permohonan', $data['id_tujuan_permohonan']);
            $stmt->bindParam(':id_skpd', $data['id_skpd']);
            $stmt->bindParam(':judul_permohonan', $data['judul_permohonan']);
            $stmt->bindParam(':isi_permohonan', $data['isi_permohonan']);
            $stmt->bindParam(':file_identitas', $data['file_identitas']);
            $stmt->bindParam(':file_pendukung', $data['file_pendukung']);
            $stmt->bindParam(':tanggal_permohonan', $data['tanggal_permohonan']);
            $stmt->bindParam(':status', $data['status']);
            $stmt->bindParam(':catatan_petugas', $data['catatan_petugas']);

            if ($stmt->execute()) {
                return [
                    'success' => true,
                    'message' => 'Permohonan berhasil dibuat',
                    'id' => $this->conn->lastInsertId()
                ];
            }

            return [
                'success' => false,
                'message' => 'Gagal membuat permohonan'
            ];
        } catch (PDOException $e) {
            error_log("Error in createPermohonan: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }

    public function updatePermohonan($id, $data) {
        try {
            $query = "UPDATE " . $this->table_permohonan . "
                      SET id_tujuan_permohonan = :id_tujuan_permohonan,
                          id_skpd = :id_skpd,
                          judul_permohonan = :judul_permohonan,
                          isi_permohonan = :isi_permohonan";

            $params = [
                ':id' => $id,
                ':id_tujuan_permohonan' => $data['id_tujuan_permohonan'],
                ':id_skpd' => $data['id_skpd'],
                ':judul_permohonan' => $data['judul_permohonan'],
                ':isi_permohonan' => $data['isi_permohonan']
            ];

            if (isset($data['file_identitas']) && !empty($data['file_identitas'])) {
                $query .= ", file_identitas = :file_identitas";
                $params[':file_identitas'] = $data['file_identitas'];
            }

            if (isset($data['file_pendukung']) && !empty($data['file_pendukung'])) {
                $query .= ", file_pendukung = :file_pendukung";
                $params[':file_pendukung'] = $data['file_pendukung'];
            }

            if (isset($data['status'])) {
                $query .= ", status = :status";
                $params[':status'] = $data['status'];
            }

            if (isset($data['catatan_petugas'])) {
                $query .= ", catatan_petugas = :catatan_petugas";
                $params[':catatan_petugas'] = $data['catatan_petugas'];
            }

            $query .= " WHERE id_permohonan = :id";

            $stmt = $this->conn->prepare($query);

            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }

            if ($stmt->execute()) {
                return [
                    'success' => true,
                    'message' => 'Permohonan berhasil diupdate'
                ];
            }

            return [
                'success' => false,
                'message' => 'Gagal mengupdate permohonan'
            ];
        } catch (PDOException $e) {
            error_log("Error in updatePermohonan: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }

    public function updatePermohonanStatus($id, $status) {
        try {
            $query = "UPDATE " . $this->table_permohonan . "
                      SET status = :status
                      WHERE id_permohonan = :id";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                return [
                    'success' => true,
                    'message' => 'Status permohonan berhasil diupdate'
                ];
            }

            return [
                'success' => false,
                'message' => 'Gagal mengupdate status permohonan'
            ];
        } catch (PDOException $e) {
            error_log("Error in updatePermohonanStatus: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }

    public function updatePermohonanWithDisposisi($data) {
        try {
            $query = "UPDATE " . $this->table_permohonan . "
                      SET status = :status,
                          id_skpd = :id_skpd,
                          catatan_petugas = :catatan_petugas,
                          disposisi_status = :disposisi_status,
                          disposisi_tanggal = :disposisi_tanggal
                      WHERE id_permohonan = :id";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':status', $data['status']);
            $stmt->bindParam(':id_skpd', $data['id_skpd']);
            $stmt->bindParam(':catatan_petugas', $data['catatan_petugas']);
            $stmt->bindParam(':disposisi_status', $data['disposisi_status']);
            $stmt->bindParam(':disposisi_tanggal', $data['disposisi_tanggal']);
            $stmt->bindParam(':id', $data['id_permohonan'], PDO::PARAM_INT);

            if ($stmt->execute()) {
                return [
                    'success' => true,
                    'message' => 'Disposisi berhasil disimpan'
                ];
            }

            return [
                'success' => false,
                'message' => 'Gagal menyimpan disposisi'
            ];
        } catch (PDOException $e) {
            error_log("Error in updatePermohonanWithDisposisi: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }

    public function updatePermohonanWithPenolakan($data) {
        try {
            $query = "UPDATE " . $this->table_permohonan . "
                      SET status = :status,
                          catatan_petugas = :catatan_petugas,
                          alasan_penolakan = :alasan_penolakan,
                          tanggal_penolakan = :tanggal_penolakan
                      WHERE id_permohonan = :id";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':status', $data['status']);
            $stmt->bindParam(':catatan_petugas', $data['catatan_petugas']);
            $stmt->bindParam(':alasan_penolakan', $data['alasan_penolakan']);
            $stmt->bindParam(':tanggal_penolakan', $data['tanggal_penolakan']);
            $stmt->bindParam(':id', $data['id_permohonan'], PDO::PARAM_INT);

            if ($stmt->execute()) {
                return [
                    'success' => true,
                    'message' => 'Penolakan berhasil disimpan'
                ];
            }

            return [
                'success' => false,
                'message' => 'Gagal menyimpan penolakan'
            ];
        } catch (PDOException $e) {
            error_log("Error in updatePermohonanWithPenolakan: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }

    public function updateStatusWithCatatan($id, $status, $catatan_petugas) {
        try {
            $query = "UPDATE " . $this->table_permohonan . "
                      SET status = :status,
                          catatan_petugas = :catatan_petugas
                      WHERE id_permohonan = :id";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':catatan_petugas', $catatan_petugas);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                return [
                    'success' => true,
                    'message' => 'Status dan catatan berhasil diupdate'
                ];
            }

            return [
                'success' => false,
                'message' => 'Gagal mengupdate status dan catatan'
            ];
        } catch (PDOException $e) {
            error_log("Error in updateStatusWithCatatan: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }

    public function deleteComprehensivePermohonan($id) {
        try {
            // Get file paths before deleting
            $permohonan = $this->getPermohonanById($id);

            if (!$permohonan) {
                return [
                    'success' => false,
                    'message' => 'Permohonan tidak ditemukan'
                ];
            }

            // Delete the permohonan
            $query = "DELETE FROM " . $this->table_permohonan . " WHERE id_permohonan = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                // Delete files if they exist
                if (!empty($permohonan['file_identitas'])) {
                    $file_path = __DIR__ . '/../uploads/' . $permohonan['file_identitas'];
                    if (file_exists($file_path)) {
                        unlink($file_path);
                    }
                }

                if (!empty($permohonan['file_pendukung'])) {
                    $file_path = __DIR__ . '/../uploads/' . $permohonan['file_pendukung'];
                    if (file_exists($file_path)) {
                        unlink($file_path);
                    }
                }

                return [
                    'success' => true,
                    'message' => 'Permohonan berhasil dihapus'
                ];
            }

            return [
                'success' => false,
                'message' => 'Gagal menghapus permohonan'
            ];
        } catch (PDOException $e) {
            error_log("Error in deleteComprehensivePermohonan: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }

    public function getDistinctTujuanPermohonan() {
        try {
            $query = "SELECT DISTINCT nama_tujuan, komponen_tujuan
                      FROM " . $this->table_tujuan . "
                      ORDER BY komponen_tujuan, nama_tujuan";

            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getDistinctTujuanPermohonan: " . $e->getMessage());
            return [];
        }
    }

    public function getAllTujuanPermohonan() {
        try {
            $query = "SELECT * FROM " . $this->table_tujuan . "
                      ORDER BY komponen_tujuan, nama_tujuan";

            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getAllTujuanPermohonan: " . $e->getMessage());
            return [];
        }
    }

    public function getSKPDByKategori($kategori) {
        try {
            $query = "SELECT * FROM " . $this->table_skpd . "
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

    // Status-specific Methods - Disposisi
    public function getDisposisiPermohonan($limit, $offset, $status = null, $search = null, $disposisi_status = null) {
        try {
            $query = "SELECT p.*,
                             u.nama_lengkap as nama_pemohon,
                             u.email as email_pemohon,
                             b.nik,
                             b.no_hp,
                             tp.nama_tujuan,
                             tp.komponen_tujuan,
                             s.nama_skpd
                      FROM " . $this->table_permohonan . " p
                      LEFT JOIN " . $this->table_users . " u ON p.id_user = u.id_user
                      LEFT JOIN " . $this->table_biodata . " b ON u.id_biodata = b.id_biodata
                      LEFT JOIN " . $this->table_tujuan . " tp ON p.id_tujuan_permohonan = tp.id_tujuan_permohonan
                      LEFT JOIN " . $this->table_skpd . " s ON p.id_skpd = s.id_skpd
                      WHERE p.status = 'Disposisi'";

            $params = [];

            if ($disposisi_status !== null && $disposisi_status !== '') {
                $query .= " AND p.disposisi_status = :disposisi_status";
                $params[':disposisi_status'] = $disposisi_status;
            }

            if ($search !== null && $search !== '') {
                $query .= " AND (p.no_permohonan LIKE :search
                           OR u.nama_lengkap LIKE :search
                           OR b.nik LIKE :search
                           OR p.judul_permohonan LIKE :search
                           OR s.nama_skpd LIKE :search)";
                $params[':search'] = "%{$search}%";
            }

            $query .= " ORDER BY p.disposisi_tanggal DESC LIMIT :limit OFFSET :offset";

            $stmt = $this->conn->prepare($query);

            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }

            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getDisposisiPermohonan: " . $e->getMessage());
            return [];
        }
    }

    public function countDisposisiPermohonan($status = null, $search = null, $disposisi_status = null) {
        try {
            $query = "SELECT COUNT(*) as total
                      FROM " . $this->table_permohonan . " p
                      LEFT JOIN " . $this->table_users . " u ON p.id_user = u.id_user
                      LEFT JOIN " . $this->table_biodata . " b ON u.id_biodata = b.id_biodata
                      LEFT JOIN " . $this->table_skpd . " s ON p.id_skpd = s.id_skpd
                      WHERE p.status = 'Disposisi'";

            $params = [];

            if ($disposisi_status !== null && $disposisi_status !== '') {
                $query .= " AND p.disposisi_status = :disposisi_status";
                $params[':disposisi_status'] = $disposisi_status;
            }

            if ($search !== null && $search !== '') {
                $query .= " AND (p.no_permohonan LIKE :search
                           OR u.nama_lengkap LIKE :search
                           OR b.nik LIKE :search
                           OR p.judul_permohonan LIKE :search
                           OR s.nama_skpd LIKE :search)";
                $params[':search'] = "%{$search}%";
            }

            $stmt = $this->conn->prepare($query);

            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }

            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (PDOException $e) {
            error_log("Error in countDisposisiPermohonan: " . $e->getMessage());
            return 0;
        }
    }

    public function getDisposisiStats() {
        try {
            $query = "SELECT
                        COUNT(*) as total,
                        SUM(CASE WHEN disposisi_status = 'Menunggu' THEN 1 ELSE 0 END) as menunggu,
                        SUM(CASE WHEN disposisi_status = 'Diterima' THEN 1 ELSE 0 END) as diterima,
                        SUM(CASE WHEN disposisi_status = 'Ditindaklanjuti' THEN 1 ELSE 0 END) as ditindaklanjuti
                      FROM " . $this->table_permohonan . "
                      WHERE status = 'Disposisi'";

            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getDisposisiStats: " . $e->getMessage());
            return [
                'total' => 0,
                'menunggu' => 0,
                'diterima' => 0,
                'ditindaklanjuti' => 0
            ];
        }
    }

    // Status-specific Methods - Keberatan
    public function getKeberatanPermohonan($limit, $offset, $status = null, $search = null) {
        try {
            $query = "SELECT p.*,
                             u.nama_lengkap as nama_pemohon,
                             u.email as email_pemohon,
                             b.nik,
                             b.no_hp,
                             tp.nama_tujuan,
                             tp.komponen_tujuan,
                             s.nama_skpd
                      FROM " . $this->table_permohonan . " p
                      LEFT JOIN " . $this->table_users . " u ON p.id_user = u.id_user
                      LEFT JOIN " . $this->table_biodata . " b ON u.id_biodata = b.id_biodata
                      LEFT JOIN " . $this->table_tujuan . " tp ON p.id_tujuan_permohonan = tp.id_tujuan_permohonan
                      LEFT JOIN " . $this->table_skpd . " s ON p.id_skpd = s.id_skpd
                      WHERE p.status = 'Keberatan'";

            $params = [];

            if ($search !== null && $search !== '') {
                $query .= " AND (p.no_permohonan LIKE :search
                           OR u.nama_lengkap LIKE :search
                           OR b.nik LIKE :search
                           OR p.judul_permohonan LIKE :search)";
                $params[':search'] = "%{$search}%";
            }

            $query .= " ORDER BY p.tanggal_keberatan DESC LIMIT :limit OFFSET :offset";

            $stmt = $this->conn->prepare($query);

            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }

            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getKeberatanPermohonan: " . $e->getMessage());
            return [];
        }
    }

    public function countKeberatanPermohonan($status = null, $search = null) {
        try {
            $query = "SELECT COUNT(*) as total
                      FROM " . $this->table_permohonan . " p
                      LEFT JOIN " . $this->table_users . " u ON p.id_user = u.id_user
                      LEFT JOIN " . $this->table_biodata . " b ON u.id_biodata = b.id_biodata
                      WHERE p.status = 'Keberatan'";

            $params = [];

            if ($search !== null && $search !== '') {
                $query .= " AND (p.no_permohonan LIKE :search
                           OR u.nama_lengkap LIKE :search
                           OR b.nik LIKE :search
                           OR p.judul_permohonan LIKE :search)";
                $params[':search'] = "%{$search}%";
            }

            $stmt = $this->conn->prepare($query);

            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }

            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (PDOException $e) {
            error_log("Error in countKeberatanPermohonan: " . $e->getMessage());
            return 0;
        }
    }

    public function getKeberatanStats() {
        try {
            $query = "SELECT COUNT(*) as total
                      FROM " . $this->table_permohonan . "
                      WHERE status = 'Keberatan'";

            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getKeberatanStats: " . $e->getMessage());
            return ['total' => 0];
        }
    }

    // Status-specific Methods - Sengketa
    public function getSengketaPermohonan($limit, $offset, $status = null, $search = null) {
        try {
            $query = "SELECT p.*,
                             u.nama_lengkap as nama_pemohon,
                             u.email as email_pemohon,
                             b.nik,
                             b.no_hp,
                             tp.nama_tujuan,
                             tp.komponen_tujuan,
                             s.nama_skpd
                      FROM " . $this->table_permohonan . " p
                      LEFT JOIN " . $this->table_users . " u ON p.id_user = u.id_user
                      LEFT JOIN " . $this->table_biodata . " b ON u.id_biodata = b.id_biodata
                      LEFT JOIN " . $this->table_tujuan . " tp ON p.id_tujuan_permohonan = tp.id_tujuan_permohonan
                      LEFT JOIN " . $this->table_skpd . " s ON p.id_skpd = s.id_skpd
                      WHERE p.status = 'Sengketa'";

            $params = [];

            if ($search !== null && $search !== '') {
                $query .= " AND (p.no_permohonan LIKE :search
                           OR u.nama_lengkap LIKE :search
                           OR b.nik LIKE :search
                           OR p.judul_permohonan LIKE :search)";
                $params[':search'] = "%{$search}%";
            }

            $query .= " ORDER BY p.tanggal_sengketa DESC LIMIT :limit OFFSET :offset";

            $stmt = $this->conn->prepare($query);

            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }

            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getSengketaPermohonan: " . $e->getMessage());
            return [];
        }
    }

    public function countSengketaPermohonan($status = null, $search = null) {
        try {
            $query = "SELECT COUNT(*) as total
                      FROM " . $this->table_permohonan . " p
                      LEFT JOIN " . $this->table_users . " u ON p.id_user = u.id_user
                      LEFT JOIN " . $this->table_biodata . " b ON u.id_biodata = b.id_biodata
                      WHERE p.status = 'Sengketa'";

            $params = [];

            if ($search !== null && $search !== '') {
                $query .= " AND (p.no_permohonan LIKE :search
                           OR u.nama_lengkap LIKE :search
                           OR b.nik LIKE :search
                           OR p.judul_permohonan LIKE :search)";
                $params[':search'] = "%{$search}%";
            }

            $stmt = $this->conn->prepare($query);

            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }

            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (PDOException $e) {
            error_log("Error in countSengketaPermohonan: " . $e->getMessage());
            return 0;
        }
    }

    public function getSengketaStats() {
        try {
            $query = "SELECT COUNT(*) as total
                      FROM " . $this->table_permohonan . "
                      WHERE status = 'Sengketa'";

            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getSengketaStats: " . $e->getMessage());
            return ['total' => 0];
        }
    }

    // Status-specific Methods - Diproses
    public function getProsesPermohonan($limit, $offset, $search = null, $proses_status = null) {
        try {
            $query = "SELECT p.*,
                             u.nama_lengkap as nama_pemohon,
                             u.email as email_pemohon,
                             b.nik,
                             b.no_hp,
                             tp.nama_tujuan,
                             tp.komponen_tujuan,
                             s.nama_skpd
                      FROM " . $this->table_permohonan . " p
                      LEFT JOIN " . $this->table_users . " u ON p.id_user = u.id_user
                      LEFT JOIN " . $this->table_biodata . " b ON u.id_biodata = b.id_biodata
                      LEFT JOIN " . $this->table_tujuan . " tp ON p.id_tujuan_permohonan = tp.id_tujuan_permohonan
                      LEFT JOIN " . $this->table_skpd . " s ON p.id_skpd = s.id_skpd
                      WHERE p.status = 'Diproses'";

            $params = [];

            if ($proses_status !== null && $proses_status !== '') {
                $query .= " AND p.proses_status = :proses_status";
                $params[':proses_status'] = $proses_status;
            }

            if ($search !== null && $search !== '') {
                $query .= " AND (p.no_permohonan LIKE :search
                           OR u.nama_lengkap LIKE :search
                           OR b.nik LIKE :search
                           OR p.judul_permohonan LIKE :search
                           OR s.nama_skpd LIKE :search)";
                $params[':search'] = "%{$search}%";
            }

            $query .= " ORDER BY p.tanggal_permohonan DESC LIMIT :limit OFFSET :offset";

            $stmt = $this->conn->prepare($query);

            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }

            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getProsesPermohonan: " . $e->getMessage());
            return [];
        }
    }

    public function countProsesPermohonan($search = null, $proses_status = null) {
        try {
            $query = "SELECT COUNT(*) as total
                      FROM " . $this->table_permohonan . " p
                      LEFT JOIN " . $this->table_users . " u ON p.id_user = u.id_user
                      LEFT JOIN " . $this->table_biodata . " b ON u.id_biodata = b.id_biodata
                      LEFT JOIN " . $this->table_skpd . " s ON p.id_skpd = s.id_skpd
                      WHERE p.status = 'Diproses'";

            $params = [];

            if ($proses_status !== null && $proses_status !== '') {
                $query .= " AND p.proses_status = :proses_status";
                $params[':proses_status'] = $proses_status;
            }

            if ($search !== null && $search !== '') {
                $query .= " AND (p.no_permohonan LIKE :search
                           OR u.nama_lengkap LIKE :search
                           OR b.nik LIKE :search
                           OR p.judul_permohonan LIKE :search
                           OR s.nama_skpd LIKE :search)";
                $params[':search'] = "%{$search}%";
            }

            $stmt = $this->conn->prepare($query);

            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }

            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (PDOException $e) {
            error_log("Error in countProsesPermohonan: " . $e->getMessage());
            return 0;
        }
    }

    public function getProsesStats() {
        try {
            $query = "SELECT
                        COUNT(*) as total,
                        SUM(CASE WHEN proses_status = 'Dalam Proses' THEN 1 ELSE 0 END) as dalam_proses,
                        SUM(CASE WHEN proses_status = 'Verifikasi' THEN 1 ELSE 0 END) as verifikasi,
                        SUM(CASE WHEN proses_status = 'Siap Diambil' THEN 1 ELSE 0 END) as siap_diambil
                      FROM " . $this->table_permohonan . "
                      WHERE status = 'Diproses'";

            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getProsesStats: " . $e->getMessage());
            return [
                'total' => 0,
                'dalam_proses' => 0,
                'verifikasi' => 0,
                'siap_diambil' => 0
            ];
        }
    }

    // Status-specific Methods - Selesai
    public function getSelesaiPermohonan($limit, $offset, $search = null, $selesai_status = null) {
        try {
            $query = "SELECT p.*,
                             u.nama_lengkap as nama_pemohon,
                             u.email as email_pemohon,
                             b.nik,
                             b.no_hp,
                             tp.nama_tujuan,
                             tp.komponen_tujuan,
                             s.nama_skpd
                      FROM " . $this->table_permohonan . " p
                      LEFT JOIN " . $this->table_users . " u ON p.id_user = u.id_user
                      LEFT JOIN " . $this->table_biodata . " b ON u.id_biodata = b.id_biodata
                      LEFT JOIN " . $this->table_tujuan . " tp ON p.id_tujuan_permohonan = tp.id_tujuan_permohonan
                      LEFT JOIN " . $this->table_skpd . " s ON p.id_skpd = s.id_skpd
                      WHERE p.status = 'Selesai'";

            $params = [];

            if ($selesai_status !== null && $selesai_status !== '') {
                $query .= " AND p.selesai_status = :selesai_status";
                $params[':selesai_status'] = $selesai_status;
            }

            if ($search !== null && $search !== '') {
                $query .= " AND (p.no_permohonan LIKE :search
                           OR u.nama_lengkap LIKE :search
                           OR b.nik LIKE :search
                           OR p.judul_permohonan LIKE :search
                           OR s.nama_skpd LIKE :search)";
                $params[':search'] = "%{$search}%";
            }

            $query .= " ORDER BY p.tanggal_selesai DESC LIMIT :limit OFFSET :offset";

            $stmt = $this->conn->prepare($query);

            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }

            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getSelesaiPermohonan: " . $e->getMessage());
            return [];
        }
    }

    public function countSelesaiPermohonan($search = null, $selesai_status = null) {
        try {
            $query = "SELECT COUNT(*) as total
                      FROM " . $this->table_permohonan . " p
                      LEFT JOIN " . $this->table_users . " u ON p.id_user = u.id_user
                      LEFT JOIN " . $this->table_biodata . " b ON u.id_biodata = b.id_biodata
                      LEFT JOIN " . $this->table_skpd . " s ON p.id_skpd = s.id_skpd
                      WHERE p.status = 'Selesai'";

            $params = [];

            if ($selesai_status !== null && $selesai_status !== '') {
                $query .= " AND p.selesai_status = :selesai_status";
                $params[':selesai_status'] = $selesai_status;
            }

            if ($search !== null && $search !== '') {
                $query .= " AND (p.no_permohonan LIKE :search
                           OR u.nama_lengkap LIKE :search
                           OR b.nik LIKE :search
                           OR p.judul_permohonan LIKE :search
                           OR s.nama_skpd LIKE :search)";
                $params[':search'] = "%{$search}%";
            }

            $stmt = $this->conn->prepare($query);

            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }

            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (PDOException $e) {
            error_log("Error in countSelesaiPermohonan: " . $e->getMessage());
            return 0;
        }
    }

    public function getSelesaiStats() {
        try {
            $query = "SELECT
                        COUNT(*) as total,
                        SUM(CASE WHEN selesai_status = 'Dikabulkan' THEN 1 ELSE 0 END) as dikabulkan,
                        SUM(CASE WHEN selesai_status = 'Dikabulkan Sebagian' THEN 1 ELSE 0 END) as dikabulkan_sebagian
                      FROM " . $this->table_permohonan . "
                      WHERE status = 'Selesai'";

            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getSelesaiStats: " . $e->getMessage());
            return [
                'total' => 0,
                'dikabulkan' => 0,
                'dikabulkan_sebagian' => 0
            ];
        }
    }

    // Status-specific Methods - Ditolak
    public function getDitolakPermohonan($limit, $offset, $search = null, $ditolak_status = null) {
        try {
            $query = "SELECT p.*,
                             u.nama_lengkap as nama_pemohon,
                             u.email as email_pemohon,
                             b.nik,
                             b.no_hp,
                             tp.nama_tujuan,
                             tp.komponen_tujuan,
                             s.nama_skpd
                      FROM " . $this->table_permohonan . " p
                      LEFT JOIN " . $this->table_users . " u ON p.id_user = u.id_user
                      LEFT JOIN " . $this->table_biodata . " b ON u.id_biodata = b.id_biodata
                      LEFT JOIN " . $this->table_tujuan . " tp ON p.id_tujuan_permohonan = tp.id_tujuan_permohonan
                      LEFT JOIN " . $this->table_skpd . " s ON p.id_skpd = s.id_skpd
                      WHERE p.status = 'Ditolak'";

            $params = [];

            if ($ditolak_status !== null && $ditolak_status !== '') {
                $query .= " AND p.ditolak_status = :ditolak_status";
                $params[':ditolak_status'] = $ditolak_status;
            }

            if ($search !== null && $search !== '') {
                $query .= " AND (p.no_permohonan LIKE :search
                           OR u.nama_lengkap LIKE :search
                           OR b.nik LIKE :search
                           OR p.judul_permohonan LIKE :search
                           OR s.nama_skpd LIKE :search)";
                $params[':search'] = "%{$search}%";
            }

            $query .= " ORDER BY p.tanggal_penolakan DESC LIMIT :limit OFFSET :offset";

            $stmt = $this->conn->prepare($query);

            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }

            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getDitolakPermohonan: " . $e->getMessage());
            return [];
        }
    }

    public function countDitolakPermohonan($search = null, $ditolak_status = null) {
        try {
            $query = "SELECT COUNT(*) as total
                      FROM " . $this->table_permohonan . " p
                      LEFT JOIN " . $this->table_users . " u ON p.id_user = u.id_user
                      LEFT JOIN " . $this->table_biodata . " b ON u.id_biodata = b.id_biodata
                      LEFT JOIN " . $this->table_skpd . " s ON p.id_skpd = s.id_skpd
                      WHERE p.status = 'Ditolak'";

            $params = [];

            if ($ditolak_status !== null && $ditolak_status !== '') {
                $query .= " AND p.ditolak_status = :ditolak_status";
                $params[':ditolak_status'] = $ditolak_status;
            }

            if ($search !== null && $search !== '') {
                $query .= " AND (p.no_permohonan LIKE :search
                           OR u.nama_lengkap LIKE :search
                           OR b.nik LIKE :search
                           OR p.judul_permohonan LIKE :search
                           OR s.nama_skpd LIKE :search)";
                $params[':search'] = "%{$search}%";
            }

            $stmt = $this->conn->prepare($query);

            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }

            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (PDOException $e) {
            error_log("Error in countDitolakPermohonan: " . $e->getMessage());
            return 0;
        }
    }

    public function getDitolakStats() {
        try {
            $query = "SELECT
                        COUNT(*) as total,
                        SUM(CASE WHEN ditolak_status = 'Informasi Tidak Dapat Diberikan' THEN 1 ELSE 0 END) as tidak_dapat_diberikan,
                        SUM(CASE WHEN ditolak_status = 'Dokumen Tidak Lengkap' THEN 1 ELSE 0 END) as dokumen_tidak_lengkap,
                        SUM(CASE WHEN ditolak_status = 'Permohonan Tidak Sesuai' THEN 1 ELSE 0 END) as tidak_sesuai
                      FROM " . $this->table_permohonan . "
                      WHERE status = 'Ditolak'";

            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getDitolakStats: " . $e->getMessage());
            return [
                'total' => 0,
                'tidak_dapat_diberikan' => 0,
                'dokumen_tidak_lengkap' => 0,
                'tidak_sesuai' => 0
            ];
        }
    }

    // Semua Permohonan Methods
    public function getAllPermohonanWithFilters($limit, $offset, $status_filter = null, $komponen_filter = null, $search = null) {
        try {
            $query = "SELECT p.*,
                             u.nama_lengkap as nama_pemohon,
                             u.email as email_pemohon,
                             b.nik,
                             b.no_hp,
                             tp.nama_tujuan,
                             tp.komponen_tujuan,
                             s.nama_skpd
                      FROM " . $this->table_permohonan . " p
                      LEFT JOIN " . $this->table_users . " u ON p.id_user = u.id_user
                      LEFT JOIN " . $this->table_biodata . " b ON u.id_biodata = b.id_biodata
                      LEFT JOIN " . $this->table_tujuan . " tp ON p.id_tujuan_permohonan = tp.id_tujuan_permohonan
                      LEFT JOIN " . $this->table_skpd . " s ON p.id_skpd = s.id_skpd
                      WHERE 1=1";

            $params = [];

            if ($status_filter !== null && $status_filter !== '') {
                $query .= " AND p.status = :status_filter";
                $params[':status_filter'] = $status_filter;
            }

            if ($komponen_filter !== null && $komponen_filter !== '') {
                $query .= " AND tp.komponen_tujuan = :komponen_filter";
                $params[':komponen_filter'] = $komponen_filter;
            }

            if ($search !== null && $search !== '') {
                $query .= " AND (p.no_permohonan LIKE :search
                           OR u.nama_lengkap LIKE :search
                           OR b.nik LIKE :search
                           OR p.judul_permohonan LIKE :search
                           OR tp.nama_tujuan LIKE :search
                           OR s.nama_skpd LIKE :search)";
                $params[':search'] = "%{$search}%";
            }

            $query .= " ORDER BY p.tanggal_permohonan DESC LIMIT :limit OFFSET :offset";

            $stmt = $this->conn->prepare($query);

            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }

            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getAllPermohonanWithFilters: " . $e->getMessage());
            return [];
        }
    }

    public function countAllPermohonanWithFilters($status_filter = null, $komponen_filter = null, $search = null) {
        try {
            $query = "SELECT COUNT(*) as total
                      FROM " . $this->table_permohonan . " p
                      LEFT JOIN " . $this->table_users . " u ON p.id_user = u.id_user
                      LEFT JOIN " . $this->table_biodata . " b ON u.id_biodata = b.id_biodata
                      LEFT JOIN " . $this->table_tujuan . " tp ON p.id_tujuan_permohonan = tp.id_tujuan_permohonan
                      LEFT JOIN " . $this->table_skpd . " s ON p.id_skpd = s.id_skpd
                      WHERE 1=1";

            $params = [];

            if ($status_filter !== null && $status_filter !== '') {
                $query .= " AND p.status = :status_filter";
                $params[':status_filter'] = $status_filter;
            }

            if ($komponen_filter !== null && $komponen_filter !== '') {
                $query .= " AND tp.komponen_tujuan = :komponen_filter";
                $params[':komponen_filter'] = $komponen_filter;
            }

            if ($search !== null && $search !== '') {
                $query .= " AND (p.no_permohonan LIKE :search
                           OR u.nama_lengkap LIKE :search
                           OR b.nik LIKE :search
                           OR p.judul_permohonan LIKE :search
                           OR tp.nama_tujuan LIKE :search
                           OR s.nama_skpd LIKE :search)";
                $params[':search'] = "%{$search}%";
            }

            $stmt = $this->conn->prepare($query);

            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }

            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (PDOException $e) {
            error_log("Error in countAllPermohonanWithFilters: " . $e->getMessage());
            return 0;
        }
    }

    public function getStatusStatistics() {
        try {
            $query = "SELECT
                        SUM(CASE WHEN status = 'Masuk' THEN 1 ELSE 0 END) as masuk,
                        SUM(CASE WHEN status = 'Disposisi' THEN 1 ELSE 0 END) as disposisi,
                        SUM(CASE WHEN status = 'Diproses' THEN 1 ELSE 0 END) as diproses,
                        SUM(CASE WHEN status = 'Selesai' THEN 1 ELSE 0 END) as selesai,
                        SUM(CASE WHEN status = 'Ditolak' THEN 1 ELSE 0 END) as ditolak,
                        SUM(CASE WHEN status = 'Keberatan' THEN 1 ELSE 0 END) as keberatan,
                        SUM(CASE WHEN status = 'Sengketa' THEN 1 ELSE 0 END) as sengketa,
                        COUNT(*) as total
                      FROM " . $this->table_permohonan;

            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getStatusStatistics: " . $e->getMessage());
            return [
                'masuk' => 0,
                'disposisi' => 0,
                'diproses' => 0,
                'selesai' => 0,
                'ditolak' => 0,
                'keberatan' => 0,
                'sengketa' => 0,
                'total' => 0
            ];
        }
    }

    public function getStatisticsByKomponenTujuan() {
        try {
            $query = "SELECT
                        tp.komponen_tujuan,
                        COUNT(*) as total,
                        SUM(CASE WHEN p.status = 'Masuk' THEN 1 ELSE 0 END) as masuk,
                        SUM(CASE WHEN p.status = 'Disposisi' THEN 1 ELSE 0 END) as disposisi,
                        SUM(CASE WHEN p.status = 'Diproses' THEN 1 ELSE 0 END) as diproses,
                        SUM(CASE WHEN p.status = 'Selesai' THEN 1 ELSE 0 END) as selesai,
                        SUM(CASE WHEN p.status = 'Ditolak' THEN 1 ELSE 0 END) as ditolak,
                        SUM(CASE WHEN p.status = 'Keberatan' THEN 1 ELSE 0 END) as keberatan,
                        SUM(CASE WHEN p.status = 'Sengketa' THEN 1 ELSE 0 END) as sengketa
                      FROM " . $this->table_permohonan . " p
                      LEFT JOIN " . $this->table_tujuan . " tp ON p.id_tujuan_permohonan = tp.id_tujuan_permohonan
                      GROUP BY tp.komponen_tujuan
                      ORDER BY total DESC";

            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getStatisticsByKomponenTujuan: " . $e->getMessage());
            return [];
        }
    }

    public function getAllKomponenTujuan() {
        try {
            $query = "SELECT DISTINCT komponen_tujuan
                      FROM " . $this->table_tujuan . "
                      WHERE komponen_tujuan IS NOT NULL
                      ORDER BY komponen_tujuan";

            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (PDOException $e) {
            error_log("Error in getAllKomponenTujuan: " . $e->getMessage());
            return [];
        }
    }

    // Data Pemohon Methods
    public function getDataPemohon($limit, $offset, $search = null) {
        try {
            $query = "SELECT
                        b.id_biodata,
                        b.nik,
                        b.no_hp,
                        b.alamat,
                        b.pekerjaan,
                        b.pendidikan,
                        u.id_user,
                        u.nama_lengkap,
                        u.email,
                        u.username,
                        COUNT(p.id_permohonan) as jumlah_permohonan,
                        MAX(p.tanggal_permohonan) as permohonan_terakhir
                      FROM " . $this->table_biodata . " b
                      LEFT JOIN " . $this->table_users . " u ON b.id_biodata = u.id_biodata
                      LEFT JOIN " . $this->table_permohonan . " p ON u.id_user = p.id_user
                      WHERE u.role = 'pemohon'";

            $params = [];

            if ($search !== null && $search !== '') {
                $query .= " AND (b.nik LIKE :search
                           OR u.nama_lengkap LIKE :search
                           OR u.email LIKE :search
                           OR b.no_hp LIKE :search
                           OR b.pekerjaan LIKE :search)";
                $params[':search'] = "%{$search}%";
            }

            $query .= " GROUP BY b.id_biodata, b.nik, b.no_hp, b.alamat, b.pekerjaan, b.pendidikan,
                               u.id_user, u.nama_lengkap, u.email, u.username
                       ORDER BY permohonan_terakhir DESC, u.nama_lengkap ASC
                       LIMIT :limit OFFSET :offset";

            $stmt = $this->conn->prepare($query);

            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }

            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getDataPemohon: " . $e->getMessage());
            return [];
        }
    }

    public function countDataPemohon($search = null) {
        try {
            $query = "SELECT COUNT(DISTINCT b.id_biodata) as total
                      FROM " . $this->table_biodata . " b
                      LEFT JOIN " . $this->table_users . " u ON b.id_biodata = u.id_biodata
                      WHERE u.role = 'pemohon'";

            $params = [];

            if ($search !== null && $search !== '') {
                $query .= " AND (b.nik LIKE :search
                           OR u.nama_lengkap LIKE :search
                           OR u.email LIKE :search
                           OR b.no_hp LIKE :search
                           OR b.pekerjaan LIKE :search)";
                $params[':search'] = "%{$search}%";
            }

            $stmt = $this->conn->prepare($query);

            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }

            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (PDOException $e) {
            error_log("Error in countDataPemohon: " . $e->getMessage());
            return 0;
        }
    }

    // Validation Methods
    public function checkEmailExists($email, $excludeUserId = null) {
        try {
            $query = "SELECT COUNT(*) as count FROM " . $this->table_users . " WHERE email = :email";

            if ($excludeUserId !== null) {
                $query .= " AND id_user != :exclude_id";
            }

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':email', $email);

            if ($excludeUserId !== null) {
                $stmt->bindParam(':exclude_id', $excludeUserId, PDO::PARAM_INT);
            }

            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result['count'] > 0;
        } catch (PDOException $e) {
            error_log("Error in checkEmailExists: " . $e->getMessage());
            return false;
        }
    }

    public function checkUsernameExists($username, $excludeUserId = null) {
        try {
            $query = "SELECT COUNT(*) as count FROM " . $this->table_users . " WHERE username = :username";

            if ($excludeUserId !== null) {
                $query .= " AND id_user != :exclude_id";
            }

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':username', $username);

            if ($excludeUserId !== null) {
                $stmt->bindParam(':exclude_id', $excludeUserId, PDO::PARAM_INT);
            }

            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result['count'] > 0;
        } catch (PDOException $e) {
            error_log("Error in checkUsernameExists: " . $e->getMessage());
            return false;
        }
    }

    public function checkNikExists($nik, $excludeBiodataId = null) {
        try {
            $query = "SELECT COUNT(*) as count FROM " . $this->table_biodata . " WHERE nik = :nik";

            if ($excludeBiodataId !== null) {
                $query .= " AND id_biodata != :exclude_id";
            }

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':nik', $nik);

            if ($excludeBiodataId !== null) {
                $stmt->bindParam(':exclude_id', $excludeBiodataId, PDO::PARAM_INT);
            }

            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result['count'] > 0;
        } catch (PDOException $e) {
            error_log("Error in checkNikExists: " . $e->getMessage());
            return false;
        }
    }
}
?>