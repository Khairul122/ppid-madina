<?php
require_once 'models/UserModel.php';

class ProfilePetugasModel extends UserModel
{
    public function updateProfile($user_id, $email, $username)
    {
        try {
            // Check if email already exists (excluding current user)
            $check_query = "SELECT id_user FROM users WHERE email = :email AND id_user != :user_id";
            $check_stmt = $this->conn->prepare($check_query);
            $check_stmt->bindParam(':email', $email);
            $check_stmt->bindParam(':user_id', $user_id);
            $check_stmt->execute();
            
            if ($check_stmt->rowCount() > 0) {
                return ['success' => false, 'message' => 'Email sudah digunakan oleh pengguna lain'];
            }
            
            // Check if username already exists (excluding current user)
            $check_query = "SELECT id_user FROM users WHERE username = :username AND id_user != :user_id";
            $check_stmt = $this->conn->prepare($check_query);
            $check_stmt->bindParam(':username', $username);
            $check_stmt->bindParam(':user_id', $user_id);
            $check_stmt->execute();
            
            if ($check_stmt->rowCount() > 0) {
                return ['success' => false, 'message' => 'Username sudah digunakan oleh pengguna lain'];
            }
            
            // Update email and username
            $query = "UPDATE users SET email = :email, username = :username WHERE id_user = :user_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':user_id', $user_id);
            
            if ($stmt->execute()) {
                // Update session data
                $_SESSION['email'] = $email;
                $_SESSION['username'] = $username;
                
                return ['success' => true, 'message' => 'Profil berhasil diperbarui'];
            } else {
                return ['success' => false, 'message' => 'Gagal memperbarui profil'];
            }
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }
    
    // Method untuk update password dengan verifikasi current password (tidak mengikuti parent signature)
    public function updatePasswordWithVerification($user_id, $current_password, $new_password)
    {
        try {
            // Get current user data
            $user = $this->findById($user_id);
            if (!$user || md5($current_password) !== $user['password']) {
                return ['success' => false, 'message' => 'Password saat ini salah'];
            }
            
            // Update password
            $hashed_password = md5($new_password);
            $query = "UPDATE users SET password = :password WHERE id_user = :user_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':password', $hashed_password);
            $stmt->bindParam(':user_id', $user_id);
            
            if ($stmt->execute()) {
                return ['success' => true, 'message' => 'Password berhasil diperbarui'];
            } else {
                return ['success' => false, 'message' => 'Gagal memperbarui password'];
            }
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }
    
    // Override parent updatePassword to match signature
    public function updatePassword($userId, $newPassword)
    {
        try {
            // Update password
            $hashed_password = md5($newPassword);
            $query = "UPDATE users SET password = :password WHERE id_user = :user_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':password', $hashed_password);
            $stmt->bindParam(':user_id', $userId);
            
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            error_log("Error updating password: " . $e->getMessage());
            return false;
        }
    }
    
    public function updateProfilePhoto($user_id, $file)
    {
        try {
            // Validate file
            if ($file['error'] !== UPLOAD_ERR_OK) {
                return ['success' => false, 'message' => 'Error saat mengupload file'];
            }
            
            // File validation
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            $max_size = 5 * 1024 * 1024; // 5MB
            
            if (!in_array($file['type'], $allowed_types)) {
                return ['success' => false, 'message' => 'Tipe file tidak diizinkan'];
            }
            
            if ($file['size'] > $max_size) {
                return ['success' => false, 'message' => 'Ukuran file terlalu besar (maksimal 5MB)'];
            }
            
            // Create upload directory if not exists
            $upload_dir = 'uploads/profiles/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            
            // Create unique filename
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = $user_id . '_' . time() . '_' . uniqid() . '.' . $extension;
            $filepath = $upload_dir . $filename;
            
            // Move uploaded file
            if (move_uploaded_file($file['tmp_name'], $filepath)) {
                // Get current user data to get old photo path
                $user = $this->findById($user_id);
                if ($user && !empty($user['foto_profile']) && file_exists($user['foto_profile'])) {
                    // Delete old photo
                    unlink($user['foto_profile']);
                }
                
                // Update database with new photo path
                $query = "UPDATE users SET foto_profile = :foto_profile WHERE id_user = :user_id";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':foto_profile', $filepath);
                $stmt->bindParam(':user_id', $user_id);
                
                if ($stmt->execute()) {
                    // Update session with new photo path
                    $_SESSION['profile_photo'] = $filepath;
                    
                    return [
                        'success' => true, 
                        'message' => 'Foto profil berhasil diperbarui',
                        'filepath' => $filepath
                    ];
                } else {
                    // If DB update fails, delete the uploaded file
                    if (file_exists($filepath)) {
                        unlink($filepath);
                    }
                    return ['success' => false, 'message' => 'Gagal menyimpan data ke database'];
                }
            } else {
                return ['success' => false, 'message' => 'Gagal mengupload file'];
            }
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }
}