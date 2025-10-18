<?php
require_once 'models/UserModel.php';

class ProfilePetugasModel extends UserModel
{
    // Method untuk mendapatkan data user profile berdasarkan ID
    public function getProfileById($user_id)
    {
        $query = "SELECT id_user, email, username, password FROM users WHERE id_user = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $user_id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

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
            $user = $this->getProfileById($user_id);
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
}