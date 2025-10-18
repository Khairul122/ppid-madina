<?php
class ProfileAdminModel
{
    private $conn;
    private $table_name = "users";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Method untuk mendapatkan data user profile berdasarkan ID
    public function getProfileById($user_id)
    {
        $query = "SELECT id_user, email, username, password FROM " . $this->table_name . " WHERE id_user = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $user_id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Method untuk update profile user
    public function updateProfile($user_id, $email, $username)
    {
        try {
            // Check if email already exists (excluding current user)
            $check_query = "SELECT id_user FROM " . $this->table_name . " WHERE email = :email AND id_user != :user_id";
            $check_stmt = $this->conn->prepare($check_query);
            $check_stmt->bindParam(':email', $email);
            $check_stmt->bindParam(':user_id', $user_id);
            $check_stmt->execute();
            
            if ($check_stmt->rowCount() > 0) {
                return [
                    'success' => false,
                    'message' => 'Email sudah digunakan oleh pengguna lain'
                ];
            }
            
            // Check if username already exists (excluding current user)
            $check_query = "SELECT id_user FROM " . $this->table_name . " WHERE username = :username AND id_user != :user_id";
            $check_stmt = $this->conn->prepare($check_query);
            $check_stmt->bindParam(':username', $username);
            $check_stmt->bindParam(':user_id', $user_id);
            $check_stmt->execute();
            
            if ($check_stmt->rowCount() > 0) {
                return [
                    'success' => false,
                    'message' => 'Username sudah digunakan oleh pengguna lain'
                ];
            }
            
            // Update email and username
            $query = "UPDATE " . $this->table_name . " SET email = :email, username = :username WHERE id_user = :user_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':user_id', $user_id);
            
            if ($stmt->execute()) {
                // Update session data
                $_SESSION['email'] = $email;
                $_SESSION['username'] = $username;
                
                return [
                    'success' => true,
                    'message' => 'Profil berhasil diperbarui'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Gagal memperbarui profil'
                ];
            }
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }

    // Method untuk update password dengan verifikasi current password
    public function updatePasswordWithVerification($user_id, $current_password, $new_password)
    {
        try {
            // Get current user data
            $user = $this->getProfileById($user_id);
            if (!$user || md5($current_password) !== $user['password']) {
                return [
                    'success' => false,
                    'message' => 'Password saat ini salah'
                ];
            }
            
            // Update password
            $hashed_password = md5($new_password);
            $query = "UPDATE " . $this->table_name . " SET password = :password WHERE id_user = :user_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':password', $hashed_password);
            $stmt->bindParam(':user_id', $user_id);
            
            if ($stmt->execute()) {
                return [
                    'success' => true,
                    'message' => 'Password berhasil diperbarui'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Gagal memperbarui password'
                ];
            }
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }

    // Method untuk update password (tanpa verifikasi current password - untuk kasus admin)
    public function updatePassword($user_id, $new_password)
    {
        try {
            // Update password
            $hashed_password = md5($new_password);
            $query = "UPDATE " . $this->table_name . " SET password = :password WHERE id_user = :user_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':password', $hashed_password);
            $stmt->bindParam(':user_id', $user_id);

            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            error_log("Error updating password: " . $e->getMessage());
            return false;
        }
    }

    // Method untuk mendapatkan jumlah total user (jika diperlukan)
    public function getTotalUsers()
    {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    // Method untuk validasi apakah email unik
    public function isEmailUnique($email, $excludeId = null)
    {
        $query = "SELECT COUNT(*) as count FROM " . $this->table_name . " WHERE email = :email";
        if ($excludeId) {
            $query .= " AND id_user != :exclude_id";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        if ($excludeId) {
            $stmt->bindParam(':exclude_id', $excludeId);
        }
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] == 0;
    }

    // Method untuk validasi apakah username unik
    public function isUsernameUnique($username, $excludeId = null)
    {
        $query = "SELECT COUNT(*) as count FROM " . $this->table_name . " WHERE username = :username";
        if ($excludeId) {
            $query .= " AND id_user != :exclude_id";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        if ($excludeId) {
            $stmt->bindParam(':exclude_id', $excludeId);
        }
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] == 0;
    }
}