<?php
class AuthModel
{
    private $conn;
    private $table_name = "users";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Method untuk memverifikasi kredensial pengguna
    public function login($email, $password)
    {
        $query = "SELECT id_user, email, username, password, jabatan, role FROM " . $this->table_name . " WHERE email = :email LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && md5($password) === $user['password']) {
            return $user;
        }

        return false;
    }

    // Method untuk memeriksa apakah user sudah login
    public function isLoggedIn()
    {
        return isset($_SESSION['user_id']);
    }

    // Method untuk logout
    public function logout()
    {
        session_destroy();
    }

    // Method untuk registrasi pengguna baru
    public function register($data)
    {
        try {
            $this->conn->beginTransaction();

            // Handle file uploads
            $uploadKtp = null;
            $uploadAkta = null;
            
            if (isset($_FILES['upload_ktp']) && $_FILES['upload_ktp']['error'] == 0) {
                $uploadKtp = $this->handleFileUpload($_FILES['upload_ktp'], 'ktp');
            }
            
            if (isset($_FILES['upload_akta']) && $_FILES['upload_akta']['error'] == 0) {
                $uploadAkta = $this->handleFileUpload($_FILES['upload_akta'], 'akta');
            }

            // Hash password dengan MD5
            $hashedPassword = md5($data['password']);

            // Insert ke tabel biodata_pengguna
            $queryBiodata = "INSERT INTO biodata_pengguna (nama_lengkap, nik, alamat, provinsi, city, jenis_kelamin, usia, pendidikan, pekerjaan, no_kontak, email, status_pengguna, nama_lembaga, upload_ktp, upload_akta)
                             VALUES (:nama_lengkap, :nik, :alamat, :provinsi, :city, :jenis_kelamin, :usia, :pendidikan, :pekerjaan, :no_kontak, :email, :status_pengguna, :nama_lembaga, :upload_ktp, :upload_akta)";

            $stmtBiodata = $this->conn->prepare($queryBiodata);
            $stmtBiodata->bindParam(":nama_lengkap", $data['nama_lengkap']);
            $stmtBiodata->bindParam(":nik", $data['nik']);
            $stmtBiodata->bindParam(":alamat", $data['alamat']);
            $stmtBiodata->bindParam(":provinsi", $data['provinsi']);
            $stmtBiodata->bindParam(":city", $data['city']);
            $stmtBiodata->bindParam(":jenis_kelamin", $data['jenis_kelamin']);
            $stmtBiodata->bindParam(":usia", $data['usia']);
            $stmtBiodata->bindParam(":pendidikan", $data['pendidikan']);
            $stmtBiodata->bindParam(":pekerjaan", $data['pekerjaan']);
            $stmtBiodata->bindParam(":no_kontak", $data['no_kontak']);
            $stmtBiodata->bindParam(":email", $data['email']);
            $stmtBiodata->bindParam(":status_pengguna", $data['status_pengguna']);
            
            // Handle nama_lembaga which might not exist in the data array
            $namaLembaga = $data['nama_lembaga'] ?? null;
            $stmtBiodata->bindParam(":nama_lembaga", $namaLembaga);
            
            $stmtBiodata->bindParam(":upload_ktp", $uploadKtp);
            $stmtBiodata->bindParam(":upload_akta", $uploadAkta);

            $stmtBiodata->execute();
            $biodataId = $this->conn->lastInsertId();

            // Insert ke tabel users
            $queryUsers = "INSERT INTO users (email, username, password, role, id_biodata)
                          VALUES (:email, :username, :password, 'masyarakat', :id_biodata)";

            $stmtUsers = $this->conn->prepare($queryUsers);
            $stmtUsers->bindParam(":email", $data['email']);
            $stmtUsers->bindParam(":username", $data['nama_lengkap']);
            $stmtUsers->bindParam(":password", $hashedPassword);
            $stmtUsers->bindParam(":id_biodata", $biodataId);

            $stmtUsers->execute();

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    // Method untuk menangani upload file
    private function handleFileUpload($file, $type)
    {
        $targetDir = "uploads/";
        
        // Create directory if it doesn't exist
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        // Generate unique filename
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $newFilename = $type . '_' . time() . '_' . uniqid() . '.' . $extension;
        $targetFile = $targetDir . $newFilename;

        // Validate file type
        $allowedTypes = ['jpg', 'jpeg', 'png', 'pdf'];
        if (!in_array($extension, $allowedTypes)) {
            return null;
        }

        // Validate file size (max 5MB)
        if ($file['size'] > 5000000) {
            return null;
        }

        // Attempt to upload the file
        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            return $targetFile;
        }

        return null;
    }

    // Method untuk mengecek apakah email sudah terdaftar
    public function emailExists($email)
    {
        $query = "SELECT id_biodata FROM biodata_pengguna WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    // Method untuk mengecek apakah NIK sudah terdaftar
    public function nikExists($nik)
    {
        $query = "SELECT id_biodata FROM biodata_pengguna WHERE nik = :nik LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":nik", $nik);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }
}
