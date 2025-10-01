<?php
class ProfileModel
{
    private $conn;
    private $table_name = "profile";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Method untuk mendapatkan semua data profile
    public function getAllProfile()
    {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY keterangan ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Method untuk mendapatkan data profile berdasarkan ID
    public function getProfileById($id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_profile = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Method untuk mendapatkan data profile berdasarkan keterangan
    public function getProfileByKeterangan($keterangan)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE keterangan = :keterangan LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':keterangan', $keterangan);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Method untuk update data profile (text)
    public function updateProfileText($id, $isi)
    {
        $query = "UPDATE " . $this->table_name . " SET isi = :isi, updated_at = NOW() WHERE id_profile = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':isi', $isi);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Method untuk update data profile (image)
    public function updateProfileImage($id, $filename)
    {
        $query = "UPDATE " . $this->table_name . " SET isi = :isi, updated_at = NOW() WHERE id_profile = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':isi', $filename);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Method untuk handle upload file PDF dan Gambar
    public function handleFileUpload($file, $keterangan)
    {
        try {
            // Get file extension
            $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

            // Define allowed file types
            $allowedPdfTypes = ['pdf'];
            $allowedImageTypes = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
            $allowedTypes = array_merge($allowedPdfTypes, $allowedImageTypes);

            // Validate file type
            if (!in_array($extension, $allowedTypes)) {
                throw new Exception("Tipe file tidak diizinkan. Tipe yang diterima: " . implode(', ', $allowedTypes));
            }

            // Verify MIME type
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);

            // Determine upload directory based on file type
            if (in_array($extension, $allowedPdfTypes)) {
                $allowedMimeTypes = ['application/pdf'];
                $targetDir = "uploads/profile_pdfs/";
                $maxSize = 10000000; // 10MB for PDF
                $fileTypeLabel = 'PDF';
            } else {
                $allowedMimeTypes = [
                    'image/jpeg', 
                    'image/jpg', 
                    'image/png', 
                    'image/gif', 
                    'image/bmp',
                    'image/webp'
                ];
                $targetDir = "uploads/profile_images/";
                $maxSize = 5000000; // 5MB for images
                $fileTypeLabel = 'gambar';
            }

            // Check if MIME type is valid for the file type
            if (!in_array($mimeType, $allowedMimeTypes)) {
                throw new Exception("File bukan " . $fileTypeLabel . " yang valid. Tipe file: " . $mimeType);
            }

            // Create directory if it doesn't exist
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            // Validate file size
            if ($file['size'] > $maxSize) {
                $maxSizeMB = $maxSize / 1024 / 1024;
                throw new Exception("Ukuran file terlalu besar. Maksimal " . $maxSizeMB . "MB untuk " . $fileTypeLabel);
            }

            // Generate filename: keterangan_timestamp.extension
            $newFilename = $keterangan . '_' . time() . '_' . uniqid() . '.' . $extension;
            $targetFile = $targetDir . $newFilename;

            // Upload file
            if (move_uploaded_file($file['tmp_name'], $targetFile)) {
                return [
                    'success' => true,
                    'filename' => $newFilename,
                    'filepath' => $targetFile,
                    'message' => 'File ' . $fileTypeLabel . ' berhasil diupload'
                ];
            } else {
                throw new Exception("Gagal mengupload file");
            }

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    // Method untuk mendapatkan kategori unik dari tabel profile
    public function getUniqueCategories()
    {
        $query = "SELECT DISTINCT nama_kategori FROM " . $this->table_name . " ORDER BY nama_kategori ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Method untuk mendapatkan profile yang dikelompokkan berdasarkan nama_kategori
    public function getGroupedProfiles()
    {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY nama_kategori ASC, id_profile ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $profiles = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Group profiles by 'nama_kategori'
        $groupedProfiles = [];
        foreach ($profiles as $profile) {
            $category = $profile['nama_kategori'];
            if (!isset($groupedProfiles[$category])) {
                $groupedProfiles[$category] = [];
            }
            $groupedProfiles[$category][] = $profile;
        }

        return $groupedProfiles;
    }

    // Method untuk mendapatkan profile yang dikelompokkan berdasarkan nama_kategori dan keterangan untuk navbar
    public function getProfilesForNavbar()
    {
        $query = "SELECT nama_kategori, keterangan, id_profile FROM " . $this->table_name . " ORDER BY id_profile ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $profiles = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Group by kategori and keterangan
        $grouped = [];
        foreach ($profiles as $profile) {
            $kategori = $profile['nama_kategori'];
            if (!isset($grouped[$kategori])) {
                $grouped[$kategori] = [];
            }
            $grouped[$kategori][] = [
                'keterangan' => $profile['keterangan'],
                'id_profile' => $profile['id_profile']
            ];
        }

        return $grouped;
    }

    // Method untuk mendapatkan profile berdasarkan kategori
    public function getProfilesByCategory($category)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE nama_kategori = :nama_kategori ORDER BY id_profile ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nama_kategori', $category);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Method untuk menambah profile baru
    public function insertProfile($nama_kategori, $keterangan, $isi)
    {
        $query = "INSERT INTO " . $this->table_name . " (nama_kategori, keterangan, isi, created_at, updated_at) VALUES (:nama_kategori, :keterangan, :isi, NOW(), NOW())";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nama_kategori', $nama_kategori);
        $stmt->bindParam(':keterangan', $keterangan);
        $stmt->bindParam(':isi', $isi);
        return $stmt->execute();
    }
}