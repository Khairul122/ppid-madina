<?php
class PetugasModel {
    private $conn;
    private $table_name = "petugas";
    private $users_table = "users";
    private $skpd_table = "skpd";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Method untuk mendapatkan semua data Petugas dengan join
    public function getAllPetugas() {
        $query = "SELECT p.*, u.email, u.username, s.nama_skpd
                  FROM " . $this->table_name . " p
                  JOIN " . $this->users_table . " u ON p.id_users = u.id_user
                  JOIN " . $this->skpd_table . " s ON p.id_skpd = s.id_skpd
                  ORDER BY p.nama_petugas ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Method untuk mendapatkan data Petugas dengan pagination
    public function getPetugasWithPagination($limit = 10, $offset = 0) {
        $query = "SELECT p.*, u.email, u.username, s.nama_skpd
                  FROM " . $this->table_name . " p
                  JOIN " . $this->users_table . " u ON p.id_users = u.id_user
                  JOIN " . $this->skpd_table . " s ON p.id_skpd = s.id_skpd
                  ORDER BY p.nama_petugas ASC LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Method untuk mendapatkan Petugas berdasarkan ID
    public function getPetugasById($id) {
        $query = "SELECT p.*, u.email, u.username, s.nama_skpd
                  FROM " . $this->table_name . " p
                  JOIN " . $this->users_table . " u ON p.id_users = u.id_user
                  JOIN " . $this->skpd_table . " s ON p.id_skpd = s.id_skpd
                  WHERE p.id_petugas = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Method untuk menambah Petugas baru
    public function createPetugas($data) {
        try {
            $this->conn->beginTransaction();

            // 1. Cek apakah email sudah digunakan
            $checkEmailQuery = "SELECT id_user FROM " . $this->users_table . " WHERE email = :email";
            $checkStmt = $this->conn->prepare($checkEmailQuery);
            $checkStmt->bindParam(':email', $data['email']);
            $checkStmt->execute();

            if ($checkStmt->rowCount() > 0) {
                $this->conn->rollBack();
                return [
                    'success' => false,
                    'message' => 'Email sudah digunakan'
                ];
            }

            // 2. Insert ke tabel users
            $userQuery = "INSERT INTO " . $this->users_table . "
                          (username, email, password, role, jabatan)
                          VALUES (:username, :email, :password, 'petugas', 'Petugas')";

            $userStmt = $this->conn->prepare($userQuery);
            $hashedPassword = md5($data['password']);

            $userStmt->bindParam(':username', $data['nama_petugas']);
            $userStmt->bindParam(':email', $data['email']);
            $userStmt->bindParam(':password', $hashedPassword);

            if (!$userStmt->execute()) {
                $this->conn->rollBack();
                return [
                    'success' => false,
                    'message' => 'Gagal membuat akun user'
                ];
            }

            $userId = $this->conn->lastInsertId();

            // 3. Insert ke tabel petugas
            $petugasQuery = "INSERT INTO " . $this->table_name . "
                             (id_users, id_skpd, nama_petugas, no_kontak)
                             VALUES (:id_users, :id_skpd, :nama_petugas, :no_kontak)";

            $petugasStmt = $this->conn->prepare($petugasQuery);

            $petugasStmt->bindParam(':id_users', $userId);
            $petugasStmt->bindParam(':id_skpd', $data['id_skpd']);
            $petugasStmt->bindParam(':nama_petugas', $data['nama_petugas']);
            $petugasStmt->bindParam(':no_kontak', $data['no_kontak']);

            if ($petugasStmt->execute()) {
                $this->conn->commit();
                return [
                    'success' => true,
                    'message' => 'Data petugas berhasil ditambahkan',
                    'id' => $this->conn->lastInsertId()
                ];
            } else {
                $this->conn->rollBack();
                return [
                    'success' => false,
                    'message' => 'Gagal menambahkan data petugas'
                ];
            }

        } catch (PDOException $e) {
            $this->conn->rollBack();
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }

    // Method untuk mengupdate Petugas
    public function updatePetugas($id, $data) {
        try {
            $this->conn->beginTransaction();

            // Get current user data
            $currentData = $this->getPetugasById($id);
            if (!$currentData) {
                $this->conn->rollBack();
                return [
                    'success' => false,
                    'message' => 'Data petugas tidak ditemukan'
                ];
            }

            // Check if email is unique (exclude current user)
            $checkEmailQuery = "SELECT id_user FROM " . $this->users_table . "
                               WHERE email = :email AND id_user != :user_id";
            $checkStmt = $this->conn->prepare($checkEmailQuery);
            $checkStmt->bindParam(':email', $data['email']);
            $checkStmt->bindParam(':user_id', $currentData['id_users']);
            $checkStmt->execute();

            if ($checkStmt->rowCount() > 0) {
                $this->conn->rollBack();
                return [
                    'success' => false,
                    'message' => 'Email sudah digunakan oleh petugas lain'
                ];
            }

            // Update users table
            $userQuery = "UPDATE " . $this->users_table . "
                          SET username = :username,
                              email = :email";

            $userParams = [
                ':username' => $data['nama_petugas'],
                ':email' => $data['email']
            ];

            // Add password update if provided
            if (!empty($data['password'])) {
                $userQuery .= ", password = :password";
                $userParams[':password'] = md5($data['password']);
            }

            $userQuery .= " WHERE id_user = :user_id";
            $userParams[':user_id'] = $currentData['id_users'];

            $userStmt = $this->conn->prepare($userQuery);

            foreach ($userParams as $key => $value) {
                $userStmt->bindValue($key, $value);
            }

            if (!$userStmt->execute()) {
                $this->conn->rollBack();
                return [
                    'success' => false,
                    'message' => 'Gagal mengupdate data user'
                ];
            }

            // Update petugas table
            $petugasQuery = "UPDATE " . $this->table_name . "
                             SET id_skpd = :id_skpd,
                                 nama_petugas = :nama_petugas,
                                 no_kontak = :no_kontak
                             WHERE id_petugas = :id";

            $petugasStmt = $this->conn->prepare($petugasQuery);

            $petugasStmt->bindParam(':id', $id);
            $petugasStmt->bindParam(':id_skpd', $data['id_skpd']);
            $petugasStmt->bindParam(':nama_petugas', $data['nama_petugas']);
            $petugasStmt->bindParam(':no_kontak', $data['no_kontak']);

            if ($petugasStmt->execute()) {
                $this->conn->commit();
                return [
                    'success' => true,
                    'message' => 'Data petugas berhasil diperbarui'
                ];
            } else {
                $this->conn->rollBack();
                return [
                    'success' => false,
                    'message' => 'Gagal memperbarui data petugas'
                ];
            }

        } catch (PDOException $e) {
            $this->conn->rollBack();
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }

    // Method untuk menghapus Petugas
    public function deletePetugas($id) {
        try {
            $this->conn->beginTransaction();

            // Get petugas data to get user_id
            $petugasData = $this->getPetugasById($id);
            if (!$petugasData) {
                $this->conn->rollBack();
                return [
                    'success' => false,
                    'message' => 'Data petugas tidak ditemukan'
                ];
            }

            // Delete from petugas table first (foreign key constraint)
            $deletePetugasQuery = "DELETE FROM " . $this->table_name . " WHERE id_petugas = :id";
            $deletePetugasStmt = $this->conn->prepare($deletePetugasQuery);
            $deletePetugasStmt->bindParam(':id', $id);

            if (!$deletePetugasStmt->execute()) {
                $this->conn->rollBack();
                return [
                    'success' => false,
                    'message' => 'Gagal menghapus data petugas'
                ];
            }

            // Delete from users table
            $deleteUserQuery = "DELETE FROM " . $this->users_table . " WHERE id_user = :user_id";
            $deleteUserStmt = $this->conn->prepare($deleteUserQuery);
            $deleteUserStmt->bindParam(':user_id', $petugasData['id_users']);

            if ($deleteUserStmt->execute()) {
                $this->conn->commit();
                return [
                    'success' => true,
                    'message' => 'Data petugas berhasil dihapus'
                ];
            } else {
                $this->conn->rollBack();
                return [
                    'success' => false,
                    'message' => 'Gagal menghapus akun user'
                ];
            }

        } catch (PDOException $e) {
            $this->conn->rollBack();
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }

    // Method untuk pencarian Petugas dengan pagination
    public function searchPetugasWithPagination($keyword, $limit = 10, $offset = 0) {
        $query = "SELECT p.*, u.email, u.username, s.nama_skpd
                  FROM " . $this->table_name . " p
                  JOIN " . $this->users_table . " u ON p.id_users = u.id_user
                  JOIN " . $this->skpd_table . " s ON p.id_skpd = s.id_skpd
                  WHERE p.nama_petugas LIKE :keyword
                     OR u.email LIKE :keyword
                     OR p.no_kontak LIKE :keyword
                     OR s.nama_skpd LIKE :keyword
                  ORDER BY p.nama_petugas ASC
                  LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($query);
        $searchTerm = '%' . $keyword . '%';
        $stmt->bindParam(':keyword', $searchTerm);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Method untuk mendapatkan total hasil pencarian
    public function getSearchResultCount($keyword) {
        $query = "SELECT COUNT(*) as total
                  FROM " . $this->table_name . " p
                  JOIN " . $this->users_table . " u ON p.id_users = u.id_user
                  JOIN " . $this->skpd_table . " s ON p.id_skpd = s.id_skpd
                  WHERE p.nama_petugas LIKE :keyword
                     OR u.email LIKE :keyword
                     OR p.no_kontak LIKE :keyword
                     OR s.nama_skpd LIKE :keyword";

        $stmt = $this->conn->prepare($query);
        $searchTerm = '%' . $keyword . '%';
        $stmt->bindParam(':keyword', $searchTerm);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    // Method untuk mendapatkan total data Petugas
    public function getTotalPetugas() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    // Method untuk mendapatkan daftar SKPD untuk dropdown
    public function getAllSKPD() {
        $query = "SELECT id_skpd, nama_skpd FROM " . $this->skpd_table . " ORDER BY nama_skpd ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Method untuk validasi email unique
    public function isEmailUnique($email, $excludeUserId = null) {
        $query = "SELECT COUNT(*) as count FROM " . $this->users_table . " WHERE email = :email";
        if ($excludeUserId) {
            $query .= " AND id_user != :exclude_id";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        if ($excludeUserId) {
            $stmt->bindParam(':exclude_id', $excludeUserId);
        }
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] == 0;
    }
}
?>