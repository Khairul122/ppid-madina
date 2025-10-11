<?php

class PermohonanAdminModel
{
    private $conn;
    private $table_permohonan = "permohonan";
    private $table_users = "users";
    private $table_biodata = "biodata_pengguna";
    private $table_skpd = "skpd";
    private $table_petugas = "petugas";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // ============ PUBLIC METHODS - PERMOHONAN QUERIES ============

    public function getAllPermohonan($limit = 10, $offset = 0, $status = 'all', $search = '')
    {
        $whereClause = "WHERE 1=1";
        $params = [];

        $this->addStatusFilter($whereClause, $params, $status);
        $this->addSearchFilter($whereClause, $params, $search);

        $query = $this->buildPermohonanQuery($whereClause);
        return $this->executePermohonanQuery($query, $params, $limit, $offset);
    }

    public function countAllPermohonan($status = 'all', $search = '')
    {
        $whereClause = "WHERE 1=1";
        $params = [];

        $this->addStatusFilter($whereClause, $params, $status);
        $this->addSearchFilter($whereClause, $params, $search);

        return $this->countPermohonan($whereClause, $params);
    }

    public function getPermohonanById($id)
    {
        $query = "SELECT p.*, u.username, u.email as user_email, u.role,
                         bp.nama_lengkap, bp.nik, bp.alamat, bp.provinsi, bp.city,
                         bp.jenis_kelamin, bp.usia, bp.pendidikan, bp.pekerjaan,
                         bp.no_kontak, bp.email, bp.foto_profile, bp.status_pengguna,
                         bp.nama_lembaga, bp.upload_ktp, bp.upload_akta
                  FROM {$this->table_permohonan} p
                  JOIN {$this->table_users} u ON p.id_user = u.id_user
                  LEFT JOIN {$this->table_biodata} bp ON u.id_biodata = bp.id_biodata
                  WHERE p.id_permohonan = :id
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getPermohonanBySKPD($nama_skpd, $limit = 10, $offset = 0, $status = 'all', $search = '')
    {
        $whereClause = "WHERE p.komponen_tujuan = :nama_skpd";
        $params = [':nama_skpd' => $nama_skpd];

        $this->addStatusFilter($whereClause, $params, $status);
        $this->addSearchFilter($whereClause, $params, $search);

        $query = $this->buildPermohonanQuery($whereClause);
        return $this->executePermohonanQuery($query, $params, $limit, $offset);
    }

    public function countPermohonanBySKPD($nama_skpd, $status = 'all', $search = '')
    {
        $whereClause = "WHERE p.komponen_tujuan = :nama_skpd";
        $params = [':nama_skpd' => $nama_skpd];

        $this->addStatusFilter($whereClause, $params, $status);
        $this->addSearchFilter($whereClause, $params, $search);

        return $this->countPermohonan($whereClause, $params);
    }

    public function getDisposisiBySKPD($nama_skpd, $limit = 10, $offset = 0, $search = '')
    {
        $whereClause = "WHERE p.komponen_tujuan = :nama_skpd AND p.status = 'Disposisi'";
        $params = [':nama_skpd' => $nama_skpd];

        $this->addSearchFilter($whereClause, $params, $search);

        $query = $this->buildPermohonanQuery($whereClause);
        return $this->executePermohonanQuery($query, $params, $limit, $offset);
    }

    public function countDisposisiBySKPD($nama_skpd, $search = '')
    {
        $whereClause = "WHERE p.komponen_tujuan = :nama_skpd AND p.status = 'Disposisi'";
        $params = [':nama_skpd' => $nama_skpd];

        $this->addSearchFilter($whereClause, $params, $search);

        return $this->countPermohonan($whereClause, $params);
    }

    public function getPermohonanDiprosesBySKPD($nama_skpd, $limit = 10, $offset = 0, $status = 'all', $search = '')
    {
        $whereClause = "WHERE p.komponen_tujuan = :nama_skpd";
        $params = [':nama_skpd' => $nama_skpd];

        $this->addStatusFilter($whereClause, $params, $status);
        $this->addSearchFilter($whereClause, $params, $search);

        $query = $this->buildPermohonanQuery($whereClause);
        return $this->executePermohonanQuery($query, $params, $limit, $offset);
    }

    public function countPermohonanDiprosesBySKPD($nama_skpd, $status = 'all', $search = '')
    {
        $whereClause = "WHERE p.komponen_tujuan = :nama_skpd";
        $params = [':nama_skpd' => $nama_skpd];

        $this->addStatusFilter($whereClause, $params, $status);
        $this->addSearchFilter($whereClause, $params, $search);

        return $this->countPermohonan($whereClause, $params);
    }

    // ============ PUBLIC METHODS - STATISTICS ============

    public function getPetugasStats()
    {
        $query = "SELECT
                    COUNT(*) as total,
                    SUM(CASE WHEN status = 'Masuk' THEN 1 ELSE 0 END) as masuk,
                    SUM(CASE WHEN status = 'Diproses' THEN 1 ELSE 0 END) as diproses,
                    SUM(CASE WHEN status = 'Disposisi' THEN 1 ELSE 0 END) as disposisi,
                    SUM(CASE WHEN status = 'Selesai' THEN 1 ELSE 0 END) as selesai,
                    SUM(CASE WHEN status = 'Ditolak' THEN 1 ELSE 0 END) as ditolak
                  FROM {$this->table_permohonan}";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getPetugasStatsBySKPD($nama_skpd)
    {
        $query = "SELECT
                    COUNT(*) as total,
                    SUM(CASE WHEN status = 'Diproses' THEN 1 ELSE 0 END) as diproses,
                    SUM(CASE WHEN status = 'Disposisi' THEN 1 ELSE 0 END) as disposisi,
                    SUM(CASE WHEN status = 'Selesai' THEN 1 ELSE 0 END) as selesai,
                    SUM(CASE WHEN status = 'Ditolak' THEN 1 ELSE 0 END) as ditolak
                  FROM {$this->table_permohonan}
                  WHERE komponen_tujuan = :nama_skpd";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nama_skpd', $nama_skpd);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ?: [
            'total' => 0,
            'diproses' => 0,
            'disposisi' => 0,
            'selesai' => 0,
            'ditolak' => 0
        ];
    }

    // ============ PUBLIC METHODS - UPDATES ============

    public function updatePermohonanStatus($id, $status)
    {
        $query = "UPDATE {$this->table_permohonan}
                  SET status = :status, updated_at = NOW()
                  WHERE id_permohonan = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function updateStatusWithCatatan($id, $status, $catatan_petugas)
    {
        $query = "UPDATE {$this->table_permohonan}
                  SET status = :status,
                      catatan_petugas = :catatan_petugas,
                      updated_at = NOW()
                  WHERE id_permohonan = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':catatan_petugas', $catatan_petugas);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function updatePermohonanWithDisposisi($data)
    {
        try {
            $query = "UPDATE {$this->table_permohonan}
                      SET status = :status,
                          tujuan_permohonan = :tujuan_permohonan,
                          komponen_tujuan = :komponen_tujuan,
                          catatan_petugas = :catatan_petugas,
                          updated_at = NOW()
                      WHERE id_permohonan = :id_permohonan";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':status', $data['status']);
            $stmt->bindParam(':tujuan_permohonan', $data['tujuan_permohonan']);
            $stmt->bindParam(':komponen_tujuan', $data['komponen_tujuan']);
            $stmt->bindParam(':catatan_petugas', $data['catatan_petugas']);
            $stmt->bindParam(':id_permohonan', $data['id_permohonan'], PDO::PARAM_INT);

            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error in updatePermohonanWithDisposisi: " . $e->getMessage());
            return false;
        }
    }

    public function updatePermohonanWithPenolakan($data)
    {
        try {
            $query = "UPDATE {$this->table_permohonan}
                      SET status = :status,
                          alasan_penolakan = :alasan_penolakan,
                          catatan_petugas = :catatan_petugas,
                          updated_at = NOW()
                      WHERE id_permohonan = :id_permohonan";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':status', $data['status']);
            $stmt->bindParam(':alasan_penolakan', $data['alasan_penolakan']);
            $stmt->bindParam(':catatan_petugas', $data['catatan_petugas']);
            $stmt->bindParam(':id_permohonan', $data['id_permohonan'], PDO::PARAM_INT);

            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error in updatePermohonanWithPenolakan: " . $e->getMessage());
            return false;
        }
    }

    public function updateSisaJatuhTempo($id, $sisa_jatuh_tempo)
    {
        try {
            $query = "UPDATE {$this->table_permohonan}
                      SET sisa_jatuh_tempo = :sisa_jatuh_tempo,
                          updated_at = NOW()
                      WHERE id_permohonan = :id";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':sisa_jatuh_tempo', $sisa_jatuh_tempo, PDO::PARAM_INT);

            if ($stmt->execute()) {
                return [
                    'success' => true,
                    'message' => 'Sisa jatuh tempo berhasil diperbarui'
                ];
            }

            return [
                'success' => false,
                'message' => 'Gagal mengupdate sisa jatuh tempo'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal mengupdate sisa jatuh tempo: ' . $e->getMessage()
            ];
        }
    }

    // ============ PUBLIC METHODS - RELATED DATA ============

    public function getSKPDDataByName($skpd_name)
    {
        if (empty($skpd_name)) {
            return null;
        }

        $query = "SELECT * FROM {$this->table_skpd} WHERE nama_skpd = :nama_skpd LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nama_skpd', $skpd_name);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getPetugasSKPDByUserId($user_id)
    {
        $query = "SELECT s.nama_skpd, s.id_skpd, p.nama_petugas
                  FROM {$this->table_petugas} p
                  JOIN {$this->table_skpd} s ON p.id_skpd = s.id_skpd
                  WHERE p.id_users = :user_id
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ============ CUSTOM METHODS FOR 7 HARI KERJA ============

    /**
     * Fungsi untuk menghitung 7 hari kerja dari tanggal tertentu
     * Hari kerja adalah Senin-Jumat (Sabtu dan Minggu dikecualikan)
     */
    public function hitung7HariKerja($tanggal_mulai = null)
    {
        if ($tanggal_mulai === null) {
            $tanggal_mulai = date('Y-m-d');
        } else {
            $tanggal_mulai = date('Y-m-d', strtotime($tanggal_mulai));
        }

        $tanggal = new DateTime($tanggal_mulai);
        $hari_kerja = 0;
        
        while ($hari_kerja < 7) {
            $tanggal->modify('+1 day');
            
            // Cek apakah hari ini bukan Sabtu (6) atau Minggu (0)
            $hari = $tanggal->format('w'); // 0 = Minggu, 1 = Senin, ..., 6 = Sabtu
            if ($hari != 0 && $hari != 6) { // Bukan Minggu atau Sabtu
                $hari_kerja++;
            }
        }

        // Kembalikan jumlah hari antara tanggal_mulai dan tanggal selesai
        $tanggal_mulai_obj = new DateTime($tanggal_mulai);
        $selisih = $tanggal->diff($tanggal_mulai_obj);
        
        return $selisih->days;
    }

    /**
     * Fungsi untuk membuat permohonan baru dengan sisa_jatuh_tempo yang dihitung
     */
    public function createPermohonan($data)
    {
        try {
            $sisa_jatuh_tempo = $this->hitung7HariKerja(); // Hitung 7 hari kerja
            
            // Jika sumber_media tidak disediakan, gunakan default "Website"
            $sumber_media = isset($data['sumber_media']) ? $data['sumber_media'] : 'Website';
            
            $query = "INSERT INTO {$this->table_permohonan} 
                      (id_user, no_permohonan, sisa_jatuh_tempo, judul_dokumen, tujuan_penggunaan_informasi,
                       tujuan_permohonan, komponen_tujuan, kandungan_informasi, sumber_media,
                       upload_foto_identitas, upload_data_pedukung, status, created_at)
                      VALUES 
                      (:id_user, :no_permohonan, :sisa_jatuh_tempo, :judul_dokumen, :tujuan_penggunaan_informasi,
                       :tujuan_permohonan, :komponen_tujuan, :kandungan_informasi, :sumber_media,
                       :upload_foto_identitas, :upload_data_pedukung, :status, NOW())";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_user', $data['id_user']);
            $stmt->bindParam(':no_permohonan', $data['no_permohonan']);
            $stmt->bindParam(':sisa_jatuh_tempo', $sisa_jatuh_tempo);
            $stmt->bindParam(':judul_dokumen', $data['judul_dokumen']);
            $stmt->bindParam(':tujuan_penggunaan_informasi', $data['tujuan_penggunaan_informasi']);
            $stmt->bindParam(':tujuan_permohonan', $data['tujuan_permohonan']);
            $stmt->bindParam(':komponen_tujuan', $data['komponen_tujuan']);
            $stmt->bindParam(':kandungan_informasi', $data['kandungan_informasi']);
            $stmt->bindParam(':sumber_media', $sumber_media);
            $stmt->bindParam(':upload_foto_identitas', $data['upload_foto_identitas']);
            $stmt->bindParam(':upload_data_pedukung', $data['upload_data_pedukung']);
            $stmt->bindParam(':status', $data['status']);

            if ($stmt->execute()) {
                return [
                    'success' => true,
                    'id' => $this->conn->lastInsertId(),
                    'sisa_jatuh_tempo' => $sisa_jatuh_tempo
                ];
            }

            return [
                'success' => false,
                'message' => 'Gagal membuat permohonan'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Fungsi untuk mendapatkan semua data pemohon
     */
    public function getAllPemohon($limit = 10, $offset = 0, $search = '')
    {
        $whereClause = "WHERE u.role = 'masyarakat'";
        $params = [];

        if (!empty($search)) {
            $whereClause .= " AND (u.username LIKE :search
                             OR bp.nama_lengkap LIKE :search
                             OR bp.nik LIKE :search
                             OR bp.email LIKE :search
                             OR bp.no_kontak LIKE :search)";
            $params[':search'] = '%' . $search . '%';
        }

        $query = "SELECT u.id_user, u.username, u.email as user_email, u.created_at as register_date,
                         bp.nama_lengkap, bp.nik, bp.alamat, bp.no_kontak, bp.email,
                         bp.status_pengguna, bp.provinsi, bp.city,
                         (SELECT COUNT(*) FROM permohonan WHERE id_user = u.id_user) as total_permohonan
                  FROM users u
                  LEFT JOIN biodata_pengguna bp ON u.id_biodata = bp.id_biodata
                  " . $whereClause . "
                  ORDER BY u.created_at DESC
                  LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Count total pemohon
     */
    public function countAllPemohon($search = '')
    {
        $whereClause = "WHERE u.role = 'masyarakat'";
        $params = [];

        if (!empty($search)) {
            $whereClause .= " AND (u.username LIKE :search
                             OR bp.nama_lengkap LIKE :search
                             OR bp.nik LIKE :search
                             OR bp.email LIKE :search
                             OR bp.no_kontak LIKE :search)";
            $params[':search'] = '%' . $search . '%';
        }

        $query = "SELECT COUNT(*) as total
                  FROM users u
                  LEFT JOIN biodata_pengguna bp ON u.id_biodata = bp.id_biodata
                  " . $whereClause;

        $stmt = $this->conn->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int)$result['total'];
    }

    // ============ PRIVATE HELPER METHODS ============

    private function buildPermohonanQuery($whereClause)
    {
        return "SELECT p.*, u.username, u.email as user_email,
                       bp.nama_lengkap, bp.nik, bp.alamat, bp.provinsi, bp.city,
                       bp.jenis_kelamin, bp.usia, bp.pendidikan, bp.pekerjaan,
                       bp.no_kontak, bp.email, bp.foto_profile, bp.status_pengguna,
                       bp.nama_lembaga, bp.upload_ktp, bp.upload_akta
                FROM {$this->table_permohonan} p
                JOIN {$this->table_users} u ON p.id_user = u.id_user
                LEFT JOIN {$this->table_biodata} bp ON u.id_biodata = bp.id_biodata
                {$whereClause}
                ORDER BY p.created_at DESC
                LIMIT :limit OFFSET :offset";
    }

    private function executePermohonanQuery($query, $params, $limit, $offset)
    {
        $stmt = $this->conn->prepare($query);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function countPermohonan($whereClause, $params)
    {
        $query = "SELECT COUNT(*) as total
                  FROM {$this->table_permohonan} p
                  JOIN {$this->table_users} u ON p.id_user = u.id_user
                  LEFT JOIN {$this->table_biodata} bp ON u.id_biodata = bp.id_biodata
                  {$whereClause}";

        $stmt = $this->conn->prepare($query);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int)$result['total'];
    }

    private function addStatusFilter(&$whereClause, &$params, $status)
    {
        if ($status && $status !== 'all') {
            $whereClause .= " AND p.status = :status";
            $params[':status'] = $status;
        }
    }

    private function addSearchFilter(&$whereClause, &$params, $search)
    {
        if (!empty($search)) {
            $whereClause .= " AND (p.no_permohonan LIKE :search
                             OR p.tujuan_permohonan LIKE :search
                             OR p.judul_dokumen LIKE :search
                             OR p.kandungan_informasi LIKE :search
                             OR bp.nama_lengkap LIKE :search
                             OR bp.nik LIKE :search)";
            $params[':search'] = '%' . $search . '%';
        }
    }
    
    // Get permohonan selesai by SKPD
    public function getPermohonanSelesaiBySKPD($nama_skpd, $limit = 10, $offset = 0, $status = 'all', $search = '')
    {
        $query = "SELECT
                    p.id_permohonan,
                    p.no_permohonan,
                    p.judul_dokumen,
                    p.status,
                    p.created_at,
                    p.sisa_jatuh_tempo,
                    u.username,
                    u.email,
                    bp.nama_lengkap,
                    bp.no_kontak
                  FROM permohonan p
                  JOIN users u ON p.id_user = u.id_user
                  LEFT JOIN biodata_pengguna bp ON u.id_biodata = bp.id_biodata
                  WHERE p.komponen_tujuan = :nama_skpd AND p.status = 'Selesai'";

        // Add status filter
        if ($status !== 'all') {
            $query .= " AND p.status = :status";
        }

        // Add search filter
        if (!empty($search)) {
            $query .= " AND (
                p.no_permohonan LIKE :search OR 
                p.judul_dokumen LIKE :search OR 
                u.username LIKE :search OR 
                bp.nama_lengkap LIKE :search OR 
                u.email LIKE :search
            )";
        }

        $query .= " ORDER BY p.created_at DESC LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nama_skpd', $nama_skpd);
        
        if ($status !== 'all') {
            $stmt->bindParam(':status', $status);
        }
        
        if (!empty($search)) {
            $searchParam = '%' . $search . '%';
            $stmt->bindParam(':search', $searchParam);
        }
        
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Count permohonan selesai by SKPD
    public function countPermohonanSelesaiBySKPD($nama_skpd, $status = 'all', $search = '')
    {
        $query = "SELECT COUNT(*) as total
                  FROM permohonan p
                  JOIN users u ON p.id_user = u.id_user
                  LEFT JOIN biodata_pengguna bp ON u.id_biodata = bp.id_biodata
                  WHERE p.komponen_tujuan = :nama_skpd AND p.status = 'Selesai'";

        // Add status filter
        if ($status !== 'all') {
            $query .= " AND p.status = :status";
        }

        // Add search filter
        if (!empty($search)) {
            $query .= " AND (
                p.no_permohonan LIKE :search OR 
                p.judul_dokumen LIKE :search OR 
                u.username LIKE :search OR 
                bp.nama_lengkap LIKE :search OR 
                u.email LIKE :search
            )";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nama_skpd', $nama_skpd);
        
        if ($status !== 'all') {
            $stmt->bindParam(':status', $status);
        }
        
        if (!empty($search)) {
            $searchParam = '%' . $search . '%';
            $stmt->bindParam(':search', $searchParam);
        }
        
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['total'];
    }
    
    // Get permohonan ditolak by SKPD
    public function getPermohonanDitolakBySKPD($nama_skpd, $limit = 10, $offset = 0, $status = 'all', $search = '')
    {
        $query = "SELECT
                    p.id_permohonan,
                    p.no_permohonan,
                    p.judul_dokumen,
                    p.status,
                    p.alasan_penolakan,
                    p.catatan_petugas,
                    p.created_at,
                    p.sisa_jatuh_tempo,
                    u.username,
                    u.email,
                    bp.nama_lengkap,
                    bp.no_kontak,
                    bp.nik
                  FROM permohonan p
                  JOIN users u ON p.id_user = u.id_user
                  LEFT JOIN biodata_pengguna bp ON u.id_biodata = bp.id_biodata
                  WHERE p.komponen_tujuan = :nama_skpd AND p.status = 'Ditolak'";

        // Add status filter
        if ($status !== 'all') {
            $query .= " AND p.status = :status";
        }

        // Add search filter
        if (!empty($search)) {
            $query .= " AND (
                p.no_permohonan LIKE :search OR 
                p.judul_dokumen LIKE :search OR 
                u.username LIKE :search OR 
                bp.nama_lengkap LIKE :search OR 
                u.email LIKE :search
            )";
        }

        $query .= " ORDER BY p.created_at DESC LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nama_skpd', $nama_skpd);
        
        if ($status !== 'all') {
            $stmt->bindParam(':status', $status);
        }
        
        if (!empty($search)) {
            $searchParam = '%' . $search . '%';
            $stmt->bindParam(':search', $searchParam);
        }
        
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Count permohonan ditolak by SKPD
    public function countPermohonanDitolakBySKPD($nama_skpd, $status = 'all', $search = '')
    {
        $query = "SELECT COUNT(*) as total
                  FROM permohonan p
                  JOIN users u ON p.id_user = u.id_user
                  LEFT JOIN biodata_pengguna bp ON u.id_biodata = bp.id_biodata
                  WHERE p.komponen_tujuan = :nama_skpd AND p.status = 'Ditolak'";

        // Add status filter
        if ($status !== 'all') {
            $query .= " AND p.status = :status";
        }

        // Add search filter
        if (!empty($search)) {
            $query .= " AND (
                p.no_permohonan LIKE :search OR 
                p.judul_dokumen LIKE :search OR 
                u.username LIKE :search OR 
                bp.nama_lengkap LIKE :search OR 
                u.email LIKE :search
            )";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nama_skpd', $nama_skpd);
        
        if ($status !== 'all') {
            $stmt->bindParam(':status', $status);
        }
        
        if (!empty($search)) {
            $searchParam = '%' . $search . '%';
            $stmt->bindParam(':search', $searchParam);
        }
        
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['total'];
    }

    // ============ LAYANAN KEPUASAN METHODS ============

    // Get all layanan kepuasan filtered by SKPD
    public function getLayananKepuasanBySKPD($nama_skpd, $limit = 10, $offset = 0, $search = '')
    {
        $whereClause = "WHERE p.komponen_tujuan = :nama_skpd";
        $params = [':nama_skpd' => $nama_skpd];

        if (!empty($search)) {
            $whereClause .= " AND (lk.nama LIKE :search
                             OR p.no_permohonan LIKE :search
                             OR p.judul_dokumen LIKE :search
                             OR lk.provinsi LIKE :search
                             OR lk.kota LIKE :search)";
            $params[':search'] = '%' . $search . '%';
        }

        $query = "SELECT lk.*, p.no_permohonan, p.judul_dokumen, p.created_at as tanggal_permohonan,
                         p.komponen_tujuan
                  FROM layanan_kepuasan lk
                  JOIN permohonan p ON lk.id_permohonan = p.id_permohonan
                  " . $whereClause . "
                  ORDER BY lk.created_at DESC
                  LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($query);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Count layanan kepuasan filtered by SKPD
    public function countLayananKepuasanBySKPD($nama_skpd, $search = '')
    {
        $whereClause = "WHERE p.komponen_tujuan = :nama_skpd";
        $params = [':nama_skpd' => $nama_skpd];

        if (!empty($search)) {
            $whereClause .= " AND (lk.nama LIKE :search
                             OR p.no_permohonan LIKE :search
                             OR p.judul_dokumen LIKE :search
                             OR lk.provinsi LIKE :search
                             OR lk.kota LIKE :search)";
            $params[':search'] = '%' . $search . '%';
        }

        $query = "SELECT COUNT(*) as total
                  FROM layanan_kepuasan lk
                  JOIN permohonan p ON lk.id_permohonan = p.id_permohonan
                  " . $whereClause;

        $stmt = $this->conn->prepare($query);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    // Get layanan kepuasan by ID (with SKPD check)
    public function getLayananKepuasanById($id)
    {
        $query = "SELECT lk.*, p.no_permohonan, p.judul_dokumen, p.created_at as tanggal_permohonan,
                         p.komponen_tujuan,
                         bp.nama_lengkap as pemohon_nama, bp.email as pemohon_email, bp.no_kontak as pemohon_kontak
                  FROM layanan_kepuasan lk
                  JOIN permohonan p ON lk.id_permohonan = p.id_permohonan
                  JOIN users u ON p.id_user = u.id_user
                  LEFT JOIN biodata_pengguna bp ON u.id_biodata = bp.id_biodata
                  WHERE lk.id_layanan_kepuasan = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Delete layanan kepuasan
    public function deleteLayananKepuasan($id)
    {
        try {
            $query = "DELETE FROM layanan_kepuasan WHERE id_layanan_kepuasan = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                return [
                    'success' => true,
                    'message' => 'Layanan kepuasan berhasil dihapus'
                ];
            }

            return [
                'success' => false,
                'message' => 'Gagal menghapus layanan kepuasan'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }

    // Get layanan kepuasan stats for SKPD
    public function getLayananKepuasanStatsBySKPD($nama_skpd)
    {
        try {
            $query = "SELECT
                        COUNT(*) as total,
                        AVG(lk.rating) as avg_rating,
                        SUM(CASE WHEN lk.rating >= 4 THEN 1 ELSE 0 END) as satisfied,
                        SUM(CASE WHEN lk.rating <= 2 THEN 1 ELSE 0 END) as unsatisfied
                      FROM layanan_kepuasan lk
                      JOIN permohonan p ON lk.id_permohonan = p.id_permohonan
                      WHERE p.komponen_tujuan = :nama_skpd";

            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':nama_skpd', $nama_skpd);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result ? $result : ['total' => 0, 'avg_rating' => 0, 'satisfied' => 0, 'unsatisfied' => 0];
        } catch (Exception $e) {
            error_log("Error in getLayananKepuasanStatsBySKPD: " . $e->getMessage());
            return ['total' => 0, 'avg_rating' => 0, 'satisfied' => 0, 'unsatisfied' => 0];
        }
    }
    
    /**
     * Get permohonan for disposisi view by ID
     */
    public function getPermohonanForDisposisiView($id)
    {
        $query = "SELECT p.*, u.username, u.email as user_email, u.role,
                         bp.nama_lengkap, bp.nik, bp.alamat, bp.provinsi, bp.city,
                         bp.jenis_kelamin, bp.usia, bp.pendidikan, bp.pekerjaan,
                         bp.no_kontak, bp.email, bp.foto_profile, bp.status_pengguna,
                         bp.nama_lembaga, bp.upload_ktp, bp.upload_akta
                  FROM {$this->table_permohonan} p
                  JOIN {$this->table_users} u ON p.id_user = u.id_user
                  LEFT JOIN {$this->table_biodata} bp ON u.id_biodata = bp.id_biodata
                  WHERE p.id_permohonan = :id AND p.status = 'Disposisi'
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}