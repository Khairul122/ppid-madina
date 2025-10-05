<?php
class AjukanPermohonanModel {
    public $conn;
    private $table_name = "permohonan";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Method untuk membuat permohonan baru
    public function createPermohonan($data) {
        try {
            $this->conn->beginTransaction();

            // Generate nomor permohonan unik
            $no_permohonan = $this->generateNoPermohonan();

            // Hitung 7 hari kerja dan konversi ke jumlah hari kalender
            $sisa_jatuh_tempo = $this->calculateWorkingDaysAsCalendarDays(7);

            // Handle file uploads
            $upload_foto_identitas = null;
            $upload_data_pendukung = null;

            if (isset($_FILES['upload_foto_identitas']) && $_FILES['upload_foto_identitas']['error'] == 0) {
                $upload_foto_identitas = $this->handleFileUpload($_FILES['upload_foto_identitas'], 'identitas');
            }

            if (isset($_FILES['upload_data_pendukung']) && $_FILES['upload_data_pendukung']['error'] == 0) {
                $upload_data_pendukung = $this->handleFileUpload($_FILES['upload_data_pendukung'], 'pendukung');
            }

            // Insert permohonan
            $query = "INSERT INTO " . $this->table_name . "
                      (id_user, no_permohonan, sisa_jatuh_tempo, tujuan_permohonan, komponen_tujuan, judul_dokumen,
                       tujuan_penggunaan_informasi, upload_foto_identitas, upload_data_pedukung, status)
                      VALUES (:id_user, :no_permohonan, :sisa_jatuh_tempo, :tujuan_permohonan, :komponen_tujuan, :judul_dokumen,
                              :tujuan_penggunaan_informasi, :upload_foto_identitas, :upload_data_pendukung, :status)";

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':id_user', $data['id_user']);
            $stmt->bindParam(':no_permohonan', $no_permohonan);
            $stmt->bindParam(':sisa_jatuh_tempo', $sisa_jatuh_tempo);
            $stmt->bindParam(':tujuan_permohonan', $data['tujuan_permohonan']);
            $stmt->bindParam(':komponen_tujuan', $data['komponen_tujuan']);
            $stmt->bindParam(':judul_dokumen', $data['judul_dokumen']);
            $stmt->bindParam(':tujuan_penggunaan_informasi', $data['tujuan_penggunaan_informasi']);
            $stmt->bindParam(':upload_foto_identitas', $upload_foto_identitas);
            $stmt->bindParam(':upload_data_pendukung', $upload_data_pendukung);
            $stmt->bindValue(':status', 'Masuk');

            $result = $stmt->execute();

            if ($result) {
                $permohonan_id = $this->conn->lastInsertId();
                $this->conn->commit();
                return [
                    'success' => true,
                    'id' => $permohonan_id,
                    'no_permohonan' => $no_permohonan
                ];
            } else {
                $this->conn->rollBack();
                return ['success' => false, 'message' => 'Gagal menyimpan permohonan'];
            }

        } catch (Exception $e) {
            $this->conn->rollBack();
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }

    // Method untuk generate nomor permohonan unik
    private function generateNoPermohonan() {
        $prefix = 'PMH';
        $year = date('Y');
        $month = date('m');

        // Get last number for this month
        $query = "SELECT no_permohonan FROM " . $this->table_name . "
                  WHERE no_permohonan LIKE :pattern
                  ORDER BY id_permohonan DESC LIMIT 1";

        $pattern = $prefix . $year . $month . '%';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':pattern', $pattern);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            // Extract last number and increment
            $lastNumber = substr($result['no_permohonan'], -4);
            $nextNumber = intval($lastNumber) + 1;
        } else {
            $nextNumber = 1;
        }

        return $prefix . $year . $month . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    // Method untuk menangani upload file
    private function handleFileUpload($file, $type) {
        $targetDir = "uploads/";

        // Create directory if it doesn't exist
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        // Get file extension
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        // Generate new filename with NIK pattern
        $nik = $_SESSION['user_nik'] ?? 'unknown';
        $timestamp = time();
        $newFilename = $nik . '_' . $type . '_' . $timestamp . '.' . $extension;
        $targetFile = $targetDir . $newFilename;

        // Validate file type
        $allowedTypes = ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx'];
        if (!in_array($extension, $allowedTypes)) {
            throw new Exception("Tipe file tidak diizinkan. Hanya: " . implode(', ', $allowedTypes));
        }

        // Validate file size (max 5MB)
        if ($file['size'] > 5000000) {
            throw new Exception("Ukuran file terlalu besar. Maksimal 5MB");
        }

        // Attempt to upload the file
        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            return $targetFile;
        } else {
            throw new Exception("Gagal mengupload file");
        }
    }

    // Method untuk mendapatkan permohonan berdasarkan user ID
    public function getPermohonanByUserId($userId) {
        $query = "SELECT p.*, u.username
                  FROM " . $this->table_name . " p
                  JOIN users u ON p.id_user = u.id_user
                  WHERE p.id_user = :user_id
                  ORDER BY p.id_permohonan DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Method untuk mendapatkan permohonan berdasarkan ID
    public function getPermohonanById($id) {
        $query = "SELECT p.*, u.username
                  FROM " . $this->table_name . " p
                  JOIN users u ON p.id_user = u.id_user
                  WHERE p.id_permohonan = :id LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Method untuk mendapatkan semua permohonan (untuk admin)
    public function getAllPermohonan() {
        $query = "SELECT p.*, u.username
                  FROM " . $this->table_name . " p
                  JOIN users u ON p.id_user = u.id_user
                  ORDER BY p.id_permohonan DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Method untuk update status permohonan (untuk admin)
    public function updateStatus($id, $status) {
        $query = "UPDATE " . $this->table_name . "
                  SET status = :status
                  WHERE id_permohonan = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }

    // Method untuk menghapus permohonan
    public function deletePermohonan($id) {
        try {
            $this->conn->beginTransaction();

            // First get the file paths to delete
            $permohonan = $this->getPermohonanById($id);

            if ($permohonan) {
                // Delete files if they exist
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

                // Delete from database
                $query = "DELETE FROM " . $this->table_name . " WHERE id_permohonan = :id";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':id', $id);
                $result = $stmt->execute();

                $this->conn->commit();
                return $result;
            }

            $this->conn->rollBack();
            return false;

        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    // Method untuk mendapatkan statistik permohonan
    public function getPermohonanStats($userId = null) {
        $whereClause = $userId ? "WHERE id_user = :user_id" : "";

        // Check if status column exists, if not use default values
        try {
            $query = "SELECT
                        COUNT(*) as total,
                        SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                        SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved,
                        SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected
                      FROM " . $this->table_name . " " . $whereClause;

            $stmt = $this->conn->prepare($query);

            if ($userId) {
                $stmt->bindParam(':user_id', $userId);
            }

            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // If status column doesn't exist, return basic count
            $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " " . $whereClause;
            $stmt = $this->conn->prepare($query);

            if ($userId) {
                $stmt->bindParam(':user_id', $userId);
            }

            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return [
                'total' => $result['total'],
                'pending' => $result['total'], // Assume all are pending if no status column
                'approved' => 0,
                'rejected' => 0
            ];
        }
    }

    // Method untuk menghitung 7 hari kerja (Senin-Jumat) dan mengembalikan jumlah hari kalender
    private function calculateWorkingDaysAsCalendarDays($workingDaysToAdd) {
        $startDate = new \DateTime();
        $currentDate = clone $startDate;
        $addedWorkingDays = 0;
        
        // Hitung maju sampai mendapatkan jumlah hari kerja yang dibutuhkan
        while ($addedWorkingDays < $workingDaysToAdd) {
            $currentDate->modify('+1 day');
            // Cek apakah hari ini weekday (Senin=1, Selasa=2, ..., Jumat=5)
            $dayOfWeek = $currentDate->format('N');
            if ($dayOfWeek < 6) { // 1=Monday, 5=Friday
                $addedWorkingDays++;
            }
        }
        
        // Hitung selisih hari kalender antara tanggal awal dan tanggal akhir
        $interval = $startDate->diff($currentDate);
        return $interval->days;
    }
}
?>