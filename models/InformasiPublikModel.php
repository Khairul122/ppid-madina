<?php
class InformasiPublikModel
{
    private $conn;
    private $table_name = "informasi_publik";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Method untuk mendapatkan semua data informasi publik
    public function getAllInformasi()
    {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id_informasi_publik DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Method untuk mendapatkan data informasi berdasarkan ID
    public function getInformasiById($id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_informasi_publik = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Method untuk mendapatkan informasi yang dikelompokkan berdasarkan nama_informasi_publik
    public function getGroupedInformasi()
    {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY nama_informasi_publik ASC, id_informasi_publik ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $informasi = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Group informasi by 'nama_informasi_publik'
        $groupedInformasi = [];
        foreach ($informasi as $item) {
            $namaInformasi = $item['nama_informasi_publik'];
            if (!isset($groupedInformasi[$namaInformasi])) {
                $groupedInformasi[$namaInformasi] = [];
            }
            $groupedInformasi[$namaInformasi][] = $item;
        }

        return $groupedInformasi;
    }

    // Method untuk mendapatkan nama informasi unik
    public function getUniqueNamaInformasi()
    {
        $query = "SELECT DISTINCT nama_informasi_publik FROM " . $this->table_name . " ORDER BY nama_informasi_publik ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Method untuk menambah informasi baru
    public function insertInformasi($nama_informasi_publik, $sub_informasi_publik, $isi, $tags)
    {
        $query = "INSERT INTO " . $this->table_name . " (nama_informasi_publik, sub_informasi_publik, isi, tags, created_at, updated_at) VALUES (:nama_informasi_publik, :sub_informasi_publik, :isi, :tags, NOW(), NOW())";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nama_informasi_publik', $nama_informasi_publik);
        $stmt->bindParam(':sub_informasi_publik', $sub_informasi_publik);
        $stmt->bindParam(':isi', $isi);
        $stmt->bindParam(':tags', $tags);
        return $stmt->execute();
    }

    // Method untuk update data informasi (text)
    public function updateInformasiText($id, $isi)
    {
        $query = "UPDATE " . $this->table_name . " SET isi = :isi, updated_at = NOW() WHERE id_informasi_publik = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':isi', $isi);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Method untuk update data informasi (file)
    public function updateInformasiFile($id, $filename)
    {
        $query = "UPDATE " . $this->table_name . " SET isi = :isi, updated_at = NOW() WHERE id_informasi_publik = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':isi', $filename);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Method untuk update sub_informasi_publik
    public function updateSubInformasi($id, $sub_informasi_publik)
    {
        $query = "UPDATE " . $this->table_name . " SET sub_informasi_publik = :sub_informasi_publik, updated_at = NOW() WHERE id_informasi_publik = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':sub_informasi_publik', $sub_informasi_publik);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Method untuk update tags
    public function updateTags($id, $tags)
    {
        $query = "UPDATE " . $this->table_name . " SET tags = :tags, updated_at = NOW() WHERE id_informasi_publik = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':tags', $tags);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Method untuk hapus informasi
    public function deleteInformasi($id)
    {
        // Get file path before delete
        $informasi = $this->getInformasiById($id);

        $query = "DELETE FROM " . $this->table_name . " WHERE id_informasi_publik = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            // Delete file if exists
            if ($informasi && !empty($informasi['isi'])) {
                $extension = pathinfo($informasi['isi'], PATHINFO_EXTENSION);
                if (in_array(strtolower($extension), ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp']) && file_exists($informasi['isi'])) {
                    unlink($informasi['isi']);
                }
            }
            return true;
        }
        return false;
    }

    // Method untuk handle upload file PDF dan Gambar
    public function handleFileUpload($file, $nama_informasi_publik)
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
                $targetDir = "uploads/informasi_documents/";
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
                $targetDir = "uploads/informasi_images/";
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

            // Generate filename: nama_informasi_publik_timestamp.extension
            $newFilename = str_replace(' ', '_', $nama_informasi_publik) . '_' . time() . '_' . uniqid() . '.' . $extension;
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
}
