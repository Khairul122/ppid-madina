<?php
class PermohonanAdminModel
{
    private $conn;
    private $table_permohonan = "permohonan";
    private $table_users = "users";
    private $table_biodata = "biodata_pengguna";
    private $table_skpd = "skpd";
    private $table_tujuan_permohonan = "tujuan_permohonan";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Get all permohonan for admin with pagination
    public function getAllPermohonan($limit = 10, $offset = 0, $status = 'all', $search = '')
    {
        $whereClause = "WHERE 1=1";
        $params = [];

        if ($status && $status !== 'all') {
            $whereClause .= " AND p.status = :status";
            $params[':status'] = $status;
        }

        if (!empty($search)) {
            $whereClause .= " AND (p.no_permohonan LIKE :search
                             OR p.tujuan_permohonan LIKE :search
                             OR p.judul_dokumen LIKE :search
                             OR p.kandungan_informasi LIKE :search
                             OR bp.nama_lengkap LIKE :search
                             OR bp.nik LIKE :search)";
            $params[':search'] = '%' . $search . '%';
        }

        $query = "SELECT p.*, u.username, u.email as user_email,
                         bp.nama_lengkap, bp.nik, bp.alamat, bp.provinsi, bp.city,
                         bp.jenis_kelamin, bp.usia, bp.pendidikan, bp.pekerjaan,
                         bp.no_kontak, bp.email, bp.foto_profile, bp.status_pengguna,
                         bp.nama_lembaga, bp.upload_ktp, bp.upload_akta
                  FROM " . $this->table_permohonan . " p
                  JOIN " . $this->table_users . " u ON p.id_user = u.id_user
                  LEFT JOIN " . $this->table_biodata . " bp ON u.id_biodata = bp.id_biodata
                  " . $whereClause . "
                  ORDER BY p.created_at DESC
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

    // Count total permohonan for pagination
    public function countAllPermohonan($status = 'all', $search = '')
    {
        $whereClause = "WHERE 1=1";
        $params = [];

        if ($status && $status !== 'all') {
            $whereClause .= " AND p.status = :status";
            $params[':status'] = $status;
        }

        if (!empty($search)) {
            $whereClause .= " AND (p.no_permohonan LIKE :search
                             OR p.tujuan_permohonan LIKE :search
                             OR p.judul_dokumen LIKE :search
                             OR bp.nama_lengkap LIKE :search
                             OR bp.nik LIKE :search)";
            $params[':search'] = '%' . $search . '%';
        }

        $query = "SELECT COUNT(*) as total
                  FROM " . $this->table_permohonan . " p
                  JOIN " . $this->table_users . " u ON p.id_user = u.id_user
                  LEFT JOIN " . $this->table_biodata . " bp ON u.id_biodata = bp.id_biodata
                  " . $whereClause;

        $stmt = $this->conn->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    // Get permohonan by ID for admin
    public function getPermohonanById($id)
    {
        $query = "SELECT p.*, u.username, u.email as user_email, u.role,
                         bp.nama_lengkap, bp.nik, bp.alamat, bp.provinsi, bp.city,
                         bp.jenis_kelamin, bp.usia, bp.pendidikan, bp.pekerjaan,
                         bp.no_kontak, bp.email, bp.foto_profile, bp.status_pengguna,
                         bp.nama_lembaga, bp.upload_ktp, bp.upload_akta
                  FROM " . $this->table_permohonan . " p
                  JOIN " . $this->table_users . " u ON p.id_user = u.id_user
                  LEFT JOIN " . $this->table_biodata . " bp ON u.id_biodata = bp.id_biodata
                  WHERE p.id_permohonan = :id
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Get all SKPD for dropdown
    public function getAllSKPD()
    {
        $query = "SELECT * FROM " . $this->table_skpd . " ORDER BY nama_skpd ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Generate new permohonan number
    public function generateNoPermohonan()
    {
        // Get total count of all permohonan to generate sequential number
        $query = "SELECT COUNT(*) as count FROM " . $this->table_permohonan;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $sequence = str_pad($result['count'] + 1, 6, '0', STR_PAD_LEFT);
        return "PMH{$sequence}";
    }

    // Create comprehensive permohonan (users + biodata + permohonan)
    public function createComprehensivePermohonan($userData, $biodataData, $permohonanData)
    {
        try {
            $this->conn->beginTransaction();

            // 1. Create biodata first
            $biodataQuery = "INSERT INTO " . $this->table_biodata . "
                           (nama_lengkap, nik, alamat, provinsi, city, jenis_kelamin,
                            usia, pendidikan, pekerjaan, no_kontak, email, foto_profile,
                            status_pengguna, nama_lembaga, upload_ktp, upload_akta)
                           VALUES (:nama_lengkap, :nik, :alamat, :provinsi, :city, :jenis_kelamin,
                                   :usia, :pendidikan, :pekerjaan, :no_kontak, :email, :foto_profile,
                                   :status_pengguna, :nama_lembaga, :upload_ktp, :upload_akta)";

            $biodataStmt = $this->conn->prepare($biodataQuery);
            $biodataStmt->execute($biodataData);
            $biodataId = $this->conn->lastInsertId();

            // 2. Create user
            $userQuery = "INSERT INTO " . $this->table_users . "
                         (email, username, password, role, id_biodata)
                         VALUES (:email, :username, :password, :role, :id_biodata)";

            $userStmt = $this->conn->prepare($userQuery);
            $userData['id_biodata'] = $biodataId;
            $userStmt->execute($userData);
            $userId = $this->conn->lastInsertId();

            // 3. Create permohonan
            $permohonanQuery = "INSERT INTO " . $this->table_permohonan . "
                              (id_user, no_permohonan, tujuan_permohonan, komponen_tujuan,
                               judul_dokumen, tujuan_penggunaan_informasi, upload_foto_identitas,
                               upload_data_pedukung, status)
                              VALUES (:id_user, :no_permohonan, :tujuan_permohonan, :komponen_tujuan,
                                      :judul_dokumen, :tujuan_penggunaan_informasi, :upload_foto_identitas,
                                      :upload_data_pedukung, :status)";

            $permohonanStmt = $this->conn->prepare($permohonanQuery);
            $permohonanData['id_user'] = $userId;
            $permohonanData['no_permohonan'] = $this->generateNoPermohonan();
            $permohonanStmt->execute($permohonanData);

            $this->conn->commit();
            return [
                'success' => true,
                'message' => 'Permohonan berhasil dibuat',
                'permohonan_id' => $this->conn->lastInsertId(),
                'user_id' => $userId,
                'biodata_id' => $biodataId
            ];
        } catch (Exception $e) {
            $this->conn->rollBack();
            return [
                'success' => false,
                'message' => 'Gagal membuat permohonan: ' . $e->getMessage()
            ];
        }
    }

    // Update permohonan status
    public function updatePermohonanStatus($id, $status)
    {
        $query = "UPDATE " . $this->table_permohonan . " SET status = :status WHERE id_permohonan = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }

    // Update comprehensive permohonan
    public function updateComprehensivePermohonan($permohonanId, $userData, $biodataData, $permohonanData)
    {
        try {
            $this->conn->beginTransaction();

            // Get current permohonan data
            $currentData = $this->getPermohonanById($permohonanId);
            if (!$currentData) {
                throw new Exception('Permohonan tidak ditemukan');
            }

            $userId = $currentData['id_user'];
            $biodataId = $currentData['id_biodata'];

            // 1. Update biodata
            if ($biodataId) {
                $biodataQuery = "UPDATE " . $this->table_biodata . " SET
                               nama_lengkap = :nama_lengkap, nik = :nik, alamat = :alamat,
                               provinsi = :provinsi, city = :city, jenis_kelamin = :jenis_kelamin,
                               usia = :usia, pendidikan = :pendidikan, pekerjaan = :pekerjaan,
                               no_kontak = :no_kontak, email = :email, foto_profile = :foto_profile,
                               status_pengguna = :status_pengguna, nama_lembaga = :nama_lembaga,
                               upload_ktp = :upload_ktp, upload_akta = :upload_akta
                               WHERE id_biodata = :id_biodata";

                $biodataStmt = $this->conn->prepare($biodataQuery);
                $biodataData['id_biodata'] = $biodataId;
                $biodataStmt->execute($biodataData);
            }

            // 2. Update user
            $userQuery = "UPDATE " . $this->table_users . " SET
                         email = :email, username = :username";

            if (!empty($userData['password'])) {
                $userQuery .= ", password = :password";
            }

            $userQuery .= " WHERE id_user = :id_user";

            $userStmt = $this->conn->prepare($userQuery);
            $userData['id_user'] = $userId;
            $userStmt->execute($userData);

            // 3. Update permohonan
            $permohonanQuery = "UPDATE " . $this->table_permohonan . " SET
                              tujuan_permohonan = :tujuan_permohonan, komponen_tujuan = :komponen_tujuan,
                              judul_dokumen = :judul_dokumen, tujuan_penggunaan_informasi = :tujuan_penggunaan_informasi,
                              upload_foto_identitas = :upload_foto_identitas, upload_data_pedukung = :upload_data_pedukung,
                              status = :status
                              WHERE id_permohonan = :id_permohonan";

            $permohonanStmt = $this->conn->prepare($permohonanQuery);
            $permohonanData['id_permohonan'] = $permohonanId;
            $permohonanStmt->execute($permohonanData);

            $this->conn->commit();
            return [
                'success' => true,
                'message' => 'Permohonan berhasil diupdate'
            ];
        } catch (Exception $e) {
            $this->conn->rollBack();
            return [
                'success' => false,
                'message' => 'Gagal mengupdate permohonan: ' . $e->getMessage()
            ];
        }
    }

    // Delete permohonan and related data
    public function deleteComprehensivePermohonan($id)
    {
        try {
            $this->conn->beginTransaction();

            // Get current data
            $permohonan = $this->getPermohonanById($id);
            if (!$permohonan) {
                throw new Exception('Permohonan tidak ditemukan');
            }

            // Delete uploaded files
            if ($permohonan['upload_foto_identitas'] && file_exists($permohonan['upload_foto_identitas'])) {
                unlink($permohonan['upload_foto_identitas']);
            }
            if ($permohonan['upload_data_pedukung'] && file_exists($permohonan['upload_data_pedukung'])) {
                unlink($permohonan['upload_data_pedukung']);
            }
            if ($permohonan['foto_profile'] && file_exists($permohonan['foto_profile'])) {
                unlink($permohonan['foto_profile']);
            }
            if ($permohonan['upload_ktp'] && file_exists($permohonan['upload_ktp'])) {
                unlink($permohonan['upload_ktp']);
            }
            if ($permohonan['upload_akta'] && file_exists($permohonan['upload_akta'])) {
                unlink($permohonan['upload_akta']);
            }

            // 1. Delete related keberatan first (foreign key constraint)
            $deleteKeberatanQuery = "DELETE FROM keberatan WHERE id_permohonan = :id";
            $keberatanStmt = $this->conn->prepare($deleteKeberatanQuery);
            $keberatanStmt->bindParam(':id', $id);
            $keberatanStmt->execute();

            // 2. Delete permohonan
            $deletePermohonanQuery = "DELETE FROM " . $this->table_permohonan . " WHERE id_permohonan = :id";
            $stmt = $this->conn->prepare($deletePermohonanQuery);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            // 3. Delete user (if no other permohonan exists)
            $checkPermohonanQuery = "SELECT COUNT(*) as count FROM " . $this->table_permohonan . " WHERE id_user = :user_id";
            $checkStmt = $this->conn->prepare($checkPermohonanQuery);
            $checkStmt->bindParam(':user_id', $permohonan['id_user']);
            $checkStmt->execute();
            $checkResult = $checkStmt->fetch(PDO::FETCH_ASSOC);

            if ($checkResult['count'] == 0) {
                // Delete user
                $deleteUserQuery = "DELETE FROM " . $this->table_users . " WHERE id_user = :id";
                $userStmt = $this->conn->prepare($deleteUserQuery);
                $userStmt->bindParam(':id', $permohonan['id_user']);
                $userStmt->execute();

                // Delete biodata
                if ($permohonan['id_biodata']) {
                    $deleteBiodataQuery = "DELETE FROM " . $this->table_biodata . " WHERE id_biodata = :id";
                    $biodataStmt = $this->conn->prepare($deleteBiodataQuery);
                    $biodataStmt->bindParam(':id', $permohonan['id_biodata']);
                    $biodataStmt->execute();
                }
            }

            $this->conn->commit();
            return [
                'success' => true,
                'message' => 'Permohonan berhasil dihapus'
            ];
        } catch (Exception $e) {
            $this->conn->rollBack();
            return [
                'success' => false,
                'message' => 'Gagal menghapus permohonan: ' . $e->getMessage()
            ];
        }
    }
    public function getDistinctTujuanPermohonan()
    {
        $query = "SELECT DISTINCT kategori as tujuan_permohonan, kategori as nama_kategori
                FROM " . $this->table_skpd . "
                WHERE kategori IS NOT NULL AND kategori != ''
                ORDER BY kategori ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // Get statistics for admin dashboard
    public function getAdminStats()
    {
        $query = "SELECT
                    COUNT(*) as total,
                    SUM(CASE WHEN status = 'Diproses' OR status = 'pending' OR status IS NULL OR status = '' THEN 1 ELSE 0 END) as diproses,
                    SUM(CASE WHEN status = 'approved' OR status = 'Selesai' THEN 1 ELSE 0 END) as approved,
                    SUM(CASE WHEN status = 'rejected' OR status = 'Ditolak' THEN 1 ELSE 0 END) as rejected
                  FROM " . $this->table_permohonan;

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Get komponen tujuan based on selected tujuan permohonan
    public function getKomponenByTujuanPermohonan($tujuan_permohonan)
    {
        $query = "SELECT id_tujuan_permohonan, nama_tujuan_permohonan
                  FROM " . $this->table_tujuan_permohonan . "
                  WHERE tujuan_permohonan = :tujuan_permohonan
                  ORDER BY nama_tujuan_permohonan ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':tujuan_permohonan', $tujuan_permohonan);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get SKPD by category
    public function getSKPDByKategori($kategori)
    {
        $query = "SELECT id_skpd, nama_skpd, alamat, telepon, email, kategori
                  FROM " . $this->table_skpd . "
                  WHERE kategori = :kategori
                  ORDER BY nama_skpd ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':kategori', $kategori);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get all tujuan permohonan for dropdown (backward compatibility)
    public function getAllTujuanPermohonan()
    {
        $query = "SELECT id_tujuan_permohonan, nama_tujuan_permohonan, tujuan_permohonan
                  FROM " . $this->table_tujuan_permohonan . "
                  ORDER BY tujuan_permohonan ASC, nama_tujuan_permohonan ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Create new user with biodata for masyarakat
    public function createNewUser($userData, $biodataData)
    {
        try {
            $this->conn->beginTransaction();

            // 1. Create biodata first - explicitly binding parameters to avoid any mismatch
            $biodataQuery = "INSERT INTO " . $this->table_biodata . "
                            (nama_lengkap, nik, alamat, provinsi, city, jenis_kelamin,
                             usia, pendidikan, pekerjaan, no_kontak, email, foto_profile,
                             status_pengguna, nama_lembaga, upload_ktp, upload_akta)
                            VALUES (:nama_lengkap, :nik, :alamat, :provinsi, :city, :jenis_kelamin,
                                    :usia, :pendidikan, :pekerjaan, :no_kontak, :email, :foto_profile,
                                    :status_pengguna, :nama_lembaga, :upload_ktp, :upload_akta)";

            $biodataStmt = $this->conn->prepare($biodataQuery);

            // Explicitly bind each parameter to ensure matching
            $biodataStmt->bindParam(':nama_lengkap', $biodataData['nama_lengkap']);
            $biodataStmt->bindParam(':nik', $biodataData['nik']);
            $biodataStmt->bindParam(':alamat', $biodataData['alamat']);
            $biodataStmt->bindParam(':provinsi', $biodataData['provinsi']);
            $biodataStmt->bindParam(':city', $biodataData['city']);
            $biodataStmt->bindParam(':jenis_kelamin', $biodataData['jenis_kelamin']);
            $biodataStmt->bindParam(':usia', $biodataData['usia']);
            $biodataStmt->bindParam(':pendidikan', $biodataData['pendidikan']);
            $biodataStmt->bindParam(':pekerjaan', $biodataData['pekerjaan']);
            $biodataStmt->bindParam(':no_kontak', $biodataData['no_kontak']);
            $biodataStmt->bindParam(':email', $biodataData['email']);
            $biodataStmt->bindParam(':foto_profile', $biodataData['foto_profile']);
            $biodataStmt->bindParam(':status_pengguna', $biodataData['status_pengguna']);
            $biodataStmt->bindParam(':nama_lembaga', $biodataData['nama_lembaga']);
            $biodataStmt->bindParam(':upload_ktp', $biodataData['upload_ktp']);
            $biodataStmt->bindParam(':upload_akta', $biodataData['upload_akta']);

            $biodataStmt->execute();
            $biodataId = $this->conn->lastInsertId();

            // 2. Create user with biodata reference
            $userQuery = "INSERT INTO " . $this->table_users . "
                         (username, email, password, role, id_biodata)
                         VALUES (:username, :email, :password, 'masyarakat', :id_biodata)";

            $userStmt = $this->conn->prepare($userQuery);
            $userStmt->bindParam(':username', $userData['username']);
            $userStmt->bindParam(':email', $userData['email']);
            $userStmt->bindParam(':password', $userData['password']);
            $userStmt->bindParam(':id_biodata', $biodataId);

            $userStmt->execute();
            $userId = $this->conn->lastInsertId();

            $this->conn->commit();
            return [
                'success' => true,
                'user_id' => $userId,
                'biodata_id' => $biodataId,
                'message' => 'User berhasil dibuat'
            ];
        } catch (Exception $e) {
            $this->conn->rollBack();
            return [
                'success' => false,
                'message' => 'Gagal membuat user: ' . $e->getMessage()
            ];
        }
    }

    // Create new permohonan with updated fields
    public function createPermohonan($data)
    {
        try {
            $this->conn->beginTransaction();

            // Generate nomor permohonan
            $noPermohonan = $this->generateNoPermohonan();

            $query = "INSERT INTO " . $this->table_permohonan . "
                      (id_user, no_permohonan, sisa_jatuh_tempo, tujuan_permohonan, komponen_tujuan,
                       judul_dokumen, kandungan_informasi, tujuan_penggunaan_informasi,
                       upload_foto_identitas, upload_data_pedukung, status, sumber_media, catatan_petugas, created_at)
                      VALUES (:id_user, :no_permohonan, :sisa_jatuh_tempo, :tujuan_permohonan, :komponen_tujuan,
                              :judul_dokumen, :kandungan_informasi, :tujuan_penggunaan_informasi,
                              :upload_foto_identitas, :upload_data_pedukung, :status, :sumber_media, :catatan_petugas, NOW())";

            $stmt = $this->conn->prepare($query);

            // Explicitly bind each parameter to avoid mismatch errors
            $stmt->bindParam(':id_user', $data['id_user']);
            $stmt->bindParam(':no_permohonan', $noPermohonan);
            $stmt->bindParam(':sisa_jatuh_tempo', $data['sisa_jatuh_tempo']);
            $stmt->bindParam(':tujuan_permohonan', $data['tujuan_permohonan']);
            $stmt->bindParam(':komponen_tujuan', $data['komponen_tujuan']);
            $stmt->bindParam(':judul_dokumen', $data['judul_dokumen']);
            $stmt->bindParam(':kandungan_informasi', $data['kandungan_informasi']);
            $stmt->bindParam(':tujuan_penggunaan_informasi', $data['tujuan_penggunaan_informasi']);
            $stmt->bindParam(':upload_foto_identitas', $data['upload_foto_identitas']);
            $stmt->bindParam(':upload_data_pedukung', $data['upload_data_pedukung']);
            $stmt->bindParam(':status', $data['status']);
            $stmt->bindParam(':sumber_media', $data['sumber_media']);
            $stmt->bindParam(':catatan_petugas', $data['catatan_petugas']);

            if ($stmt->execute()) {
                $permohonanId = $this->conn->lastInsertId();
                $this->conn->commit();
                return [
                    'success' => true,
                    'id_permohonan' => $permohonanId,
                    'no_permohonan' => $noPermohonan,
                    'message' => 'Permohonan berhasil dibuat'
                ];
            } else {
                $this->conn->rollBack();
                return [
                    'success' => false,
                    'message' => 'Gagal membuat permohonan'
                ];
            }
        } catch (Exception $e) {
            $this->conn->rollBack();
            return [
                'success' => false,
                'message' => 'Gagal membuat permohonan: ' . $e->getMessage()
            ];
        }
    }

    // Update permohonan with new fields
    public function updatePermohonan($id, $data)
    {
        try {
            $this->conn->beginTransaction();

            $query = "UPDATE " . $this->table_permohonan . " SET
                      sisa_jatuh_tempo = :sisa_jatuh_tempo,
                      tujuan_permohonan = :tujuan_permohonan,
                      komponen_tujuan = :komponen_tujuan,
                      judul_dokumen = :judul_dokumen,
                      kandungan_informasi = :kandungan_informasi,
                      tujuan_penggunaan_informasi = :tujuan_penggunaan_informasi,
                      upload_foto_identitas = :upload_foto_identitas,
                      upload_data_pedukung = :upload_data_pedukung,
                      status = :status,
                      sumber_media = :sumber_media,
                      catatan_petugas = :catatan_petugas,
                      updated_at = NOW()
                      WHERE id_permohonan = :id_permohonan";

            $stmt = $this->conn->prepare($query);
            $data['id_permohonan'] = $id;

            if ($stmt->execute($data)) {
                $this->conn->commit();
                return [
                    'success' => true,
                    'message' => 'Permohonan berhasil diupdate'
                ];
            } else {
                $this->conn->rollBack();
                return [
                    'success' => false,
                    'message' => 'Gagal mengupdate permohonan'
                ];
            }
        } catch (Exception $e) {
            $this->conn->rollBack();
            return [
                'success' => false,
                'message' => 'Gagal mengupdate permohonan: ' . $e->getMessage()
            ];
        }
    }

    // Update sisa jatuh tempo only
    public function updateSisaJatuhTempo($id, $sisa_jatuh_tempo)
    {
        try {
            $query = "UPDATE " . $this->table_permohonan . " SET
                      sisa_jatuh_tempo = :sisa_jatuh_tempo,
                      updated_at = NOW()
                      WHERE id_permohonan = :id";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':sisa_jatuh_tempo', $sisa_jatuh_tempo);

            if ($stmt->execute()) {
                return [
                    'success' => true,
                    'message' => 'Sisa jatuh tempo berhasil diperbarui'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Gagal mengupdate sisa jatuh tempo'
                ];
            }
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal mengupdate sisa jatuh tempo: ' . $e->getMessage()
            ];
        }
    }


    // Check if email exists
    public function checkEmailExists($email, $excludeUserId = null)
    {
        $query = "SELECT COUNT(*) as count FROM " . $this->table_users . " WHERE email = :email";

        if ($excludeUserId) {
            $query .= " AND id_user != :user_id";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);

        if ($excludeUserId) {
            $stmt->bindParam(':user_id', $excludeUserId);
        }

        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }

    // Check if username exists
    public function checkUsernameExists($username, $excludeUserId = null)
    {
        $query = "SELECT COUNT(*) as count FROM " . $this->table_users . " WHERE username = :username";

        if ($excludeUserId) {
            $query .= " AND id_user != :user_id";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);

        if ($excludeUserId) {
            $stmt->bindParam(':user_id', $excludeUserId);
        }

        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }

    // Check if NIK exists
    public function checkNikExists($nik, $excludeBiodataId = null)
    {
        $query = "SELECT COUNT(*) as count FROM " . $this->table_biodata . " WHERE nik = :nik";

        if ($excludeBiodataId) {
            $query .= " AND id_biodata != :biodata_id";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nik', $nik);

        if ($excludeBiodataId) {
            $stmt->bindParam(':biodata_id', $excludeBiodataId);
        }

        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }

    // Get permohonan with specific disposisi status for admin with pagination
    public function getDisposisiPermohonan($limit = 10, $offset = 0, $status = 'all', $search = '', $disposisi_status = [])
    {
        $whereClause = "WHERE 1=1";
        $params = [];
        
        // Add disposisi status filtering
        if (!empty($disposisi_status)) {
            $status_placeholders = [];
            for ($i = 0; $i < count($disposisi_status); $i++) {
                $placeholder = ':disposisi_status_' . $i;
                $status_placeholders[] = $placeholder;
                $params[$placeholder] = $disposisi_status[$i];
            }
            $whereClause .= " AND p.status IN (" . implode(',', $status_placeholders) . ")";
        }

        if ($status && $status !== 'all') {
            $whereClause .= " AND p.status = :status";
            $params[':status'] = $status;
        }

        if (!empty($search)) {
            $whereClause .= " AND (p.no_permohonan LIKE :search
                             OR p.tujuan_permohonan LIKE :search
                             OR p.judul_dokumen LIKE :search
                             OR p.kandungan_informasi LIKE :search
                             OR bp.nama_lengkap LIKE :search
                             OR bp.nik LIKE :search)";
            $params[':search'] = '%' . $search . '%';
        }

        $query = "SELECT p.*, u.username, u.email as user_email,
                         bp.nama_lengkap, bp.nik, bp.alamat, bp.provinsi, bp.city,
                         bp.jenis_kelamin, bp.usia, bp.pendidikan, bp.pekerjaan,
                         bp.no_kontak, bp.email, bp.foto_profile, bp.status_pengguna,
                         bp.nama_lembaga, bp.upload_ktp, bp.upload_akta
                  FROM " . $this->table_permohonan . " p
                  JOIN " . $this->table_users . " u ON p.id_user = u.id_user
                  LEFT JOIN " . $this->table_biodata . " bp ON u.id_biodata = bp.id_biodata
                  " . $whereClause . "
                  ORDER BY p.created_at DESC
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

    // Count total permohonan with specific disposisi status for pagination
    public function countDisposisiPermohonan($status = 'all', $search = '', $disposisi_status = [])
    {
        $whereClause = "WHERE 1=1";
        $params = [];
        
        // Add disposisi status filtering
        if (!empty($disposisi_status)) {
            $status_placeholders = [];
            for ($i = 0; $i < count($disposisi_status); $i++) {
                $placeholder = ':disposisi_status_' . $i;
                $status_placeholders[] = $placeholder;
                $params[$placeholder] = $disposisi_status[$i];
            }
            $whereClause .= " AND p.status IN (" . implode(',', $status_placeholders) . ")";
        }

        if ($status && $status !== 'all') {
            $whereClause .= " AND p.status = :status";
            $params[':status'] = $status;
        }

        if (!empty($search)) {
            $whereClause .= " AND (p.no_permohonan LIKE :search
                             OR p.tujuan_permohonan LIKE :search
                             OR p.judul_dokumen LIKE :search
                             OR bp.nama_lengkap LIKE :search
                             OR bp.nik LIKE :search)";
            $params[':search'] = '%' . $search . '%';
        }

        $query = "SELECT COUNT(*) as total
                  FROM " . $this->table_permohonan . " p
                  JOIN " . $this->table_users . " u ON p.id_user = u.id_user
                  LEFT JOIN " . $this->table_biodata . " bp ON u.id_biodata = bp.id_biodata
                  " . $whereClause;

        $stmt = $this->conn->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    // Get statistics for disposisi dashboard
    public function getDisposisiStats()
    {
        $query = "SELECT
                    COUNT(*) as total,
                    SUM(CASE WHEN status = 'Disposisi' THEN 1 ELSE 0 END) as disposisi,
                    SUM(CASE WHEN status = 'Selesai' THEN 1 ELSE 0 END) as selesai,
                    SUM(CASE WHEN status = 'Ditolak' THEN 1 ELSE 0 END) as ditolak
                  FROM " . $this->table_permohonan . "
                  WHERE status IN ('Disposisi', 'Selesai', 'Ditolak')";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }    // Get SKPD data by name
    public function getSKPDDataByName($skpd_name)
    {
        if (empty($skpd_name)) {
            return null;
        }

        $query = "SELECT * FROM " . $this->table_skpd . " WHERE nama_skpd = :nama_skpd LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nama_skpd', $skpd_name);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

?>
