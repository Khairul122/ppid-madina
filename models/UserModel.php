<?php
class UserModel {
    private $conn;
    private $table_name = "Users";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Method untuk mencari user berdasarkan email
    public function findByEmail($email) {
        $query = "SELECT id_user, email, username, password, jabatan, role FROM " . $this->table_name . " WHERE email = :email LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Method untuk mencari user berdasarkan ID
    public function findById($id) {
        $query = "SELECT id_user, email, username, jabatan, role FROM " . $this->table_name . " WHERE id_user = :id LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Method untuk mendapatkan biodata lengkap user berdasarkan ID
    public function getUserBiodata($userId) {
        $query = "SELECT bp.*, u.username, u.role, u.email, u.password
                  FROM biodata_pengguna bp
                  JOIN users u ON u.id_biodata = bp.id_biodata
                  WHERE u.id_user = :user_id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Method untuk update password user
    public function updatePassword($userId, $newPassword) {
        try {
            $query = "UPDATE " . $this->table_name . " SET password = :password WHERE id_user = :user_id";
            $stmt = $this->conn->prepare($query);

            // Hash password dengan MD5 (sesuai dengan sistem yang sudah ada)
            $hashedPassword = md5($newPassword);

            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':user_id', $userId);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error updating password: " . $e->getMessage());
            return false;
        }
    }

    // Method untuk update foto profile
    public function updateProfilePhoto($userId, $filename) {
        try {
            // Update foto_profile di tabel biodata_pengguna
            $query = "UPDATE biodata_pengguna bp
                      JOIN users u ON u.id_biodata = bp.id_biodata
                      SET bp.foto_profile = :filename
                      WHERE u.id_user = :user_id";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':filename', $filename);
            $stmt->bindParam(':user_id', $userId);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error updating profile photo: " . $e->getMessage());
            return false;
        }
    }

    // Method untuk handle upload foto profile
    public function handleProfilePhotoUpload($userId, $file) {
        try {
            // Validate file error
            if ($file['error'] !== UPLOAD_ERR_OK) {
                throw new Exception("Terjadi error saat mengupload file");
            }

            // Get user biodata untuk NIK dan nama
            $userData = $this->getUserBiodata($userId);
            if (!$userData) {
                throw new Exception("Data pengguna tidak ditemukan");
            }

            $targetDir = "uploads/profiles/";

            // Create directory if it doesn't exist
            if (!file_exists($targetDir)) {
                if (!mkdir($targetDir, 0755, true)) {
                    throw new Exception("Gagal membuat direktori upload");
                }
            }

            // Validate that it's an actual uploaded file
            if (!is_uploaded_file($file['tmp_name'])) {
                throw new Exception("File yang diupload tidak valid");
            }

            // Get file extension
            $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

            // Validate file type
            $allowedTypes = ['jpg', 'jpeg', 'png'];
            if (!in_array($extension, $allowedTypes)) {
                throw new Exception("Tipe file tidak diizinkan. Hanya JPG, JPEG, dan PNG yang diperbolehkan");
            }

            // Validate file size (max 2MB)
            if ($file['size'] > 2097152) {
                throw new Exception("Ukuran file terlalu besar. Maksimal 2MB");
            }

            // Validate file dimensions (optional, but recommended)
            $imageInfo = getimagesize($file['tmp_name']);
            if ($imageInfo === false) {
                throw new Exception("File yang diupload bukan gambar yang valid");
            }

            // Validate image dimensions (minimum 100x100, maximum 5000x5000)
            $width = $imageInfo[0];
            $height = $imageInfo[1];
            if ($width < 100 || $height < 100) {
                throw new Exception("Resolusi gambar terlalu kecil. Minimal 100x100 pixel");
            }
            if ($width > 5000 || $height > 5000) {
                throw new Exception("Resolusi gambar terlalu besar. Maksimal 5000x5000 pixel");
            }

            // Generate filename: nik_nama_profile.extension
            $nik = $userData['nik'] ?? 'user' . $userId;
            $nama = str_replace(' ', '_', strtolower($userData['nama_lengkap'] ?? 'user'));
            // Sanitize filename to prevent directory traversal
            $nik = preg_replace('/[^a-zA-Z0-9_-]/', '', $nik);
            $nama = preg_replace('/[^a-zA-Z0-9_-]/', '', $nama);
            $newFilename = $nik . '_' . $nama . '_profile.' . $extension;
            $targetFile = $targetDir . $newFilename;

            // Delete old profile photo if exists
            if (!empty($userData['foto_profile']) && file_exists($userData['foto_profile'])) {
                @unlink($userData['foto_profile']); // Use @ to suppress errors if file is locked
            }

            // Upload file
            if (move_uploaded_file($file['tmp_name'], $targetFile)) {
                // Set proper file permissions
                chmod($targetFile, 0644);

                // Update database
                if ($this->updateProfilePhoto($userId, $targetFile)) {
                    return [
                        'success' => true,
                        'filename' => $newFilename,
                        'filepath' => $targetFile,
                        'message' => 'Foto profil berhasil diupload!'
                    ];
                } else {
                    // Delete uploaded file if database update failed
                    if (file_exists($targetFile)) {
                        @unlink($targetFile);
                    }
                    throw new Exception("Gagal menyimpan data ke database");
                }
            } else {
                throw new Exception("Gagal memindahkan file ke lokasi tujuan");
            }

        } catch (Exception $e) {
            error_log("Profile photo upload error for user $userId: " . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}
?>