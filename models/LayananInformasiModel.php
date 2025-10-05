<?php
class LayananInformasiModel
{
    private $conn;
    private $table_name = "layanan_informasi_publik";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Method untuk mendapatkan semua data layanan informasi
    public function getAllLayanan()
    {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id_layanan DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Method untuk mendapatkan data layanan berdasarkan ID
    public function getLayananById($id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_layanan = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Method untuk mendapatkan layanan yang dikelompokkan berdasarkan nama_layanan
    public function getGroupedLayanan()
    {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY nama_layanan ASC, id_layanan ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $layanan = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Group layanan by 'nama_layanan'
        $groupedLayanan = [];
        foreach ($layanan as $item) {
            $namaLayanan = $item['nama_layanan'];
            if (!isset($groupedLayanan[$namaLayanan])) {
                $groupedLayanan[$namaLayanan] = [];
            }
            $groupedLayanan[$namaLayanan][] = $item;
        }

        return $groupedLayanan;
    }

    // Method untuk mendapatkan nama layanan unik
    public function getUniqueNamaLayanan()
    {
        $query = "SELECT DISTINCT nama_layanan FROM " . $this->table_name . " ORDER BY nama_layanan ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Method untuk menambah layanan baru
    public function insertLayanan($nama_layanan, $sub_layanan, $sub_layanan_2, $isi)
    {
        $query = "INSERT INTO " . $this->table_name . " (nama_layanan, sub_layanan, sub_layanan_2, isi, created_at, updated_at) VALUES (:nama_layanan, :sub_layanan, :sub_layanan_2, :isi, NOW(), NOW())";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nama_layanan', $nama_layanan);
        $stmt->bindParam(':sub_layanan', $sub_layanan);
        $stmt->bindParam(':sub_layanan_2', $sub_layanan_2);
        $stmt->bindParam(':isi', $isi);
        return $stmt->execute();
    }

    // Method untuk update data layanan (text)
    public function updateLayananText($id, $isi)
    {
        $query = "UPDATE " . $this->table_name . " SET isi = :isi, updated_at = NOW() WHERE id_layanan = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':isi', $isi);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Method untuk update data layanan (file)
    public function updateLayananFile($id, $filename)
    {
        $query = "UPDATE " . $this->table_name . " SET isi = :isi, updated_at = NOW() WHERE id_layanan = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':isi', $filename);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Method untuk update sub_layanan dan sub_layanan_2
    public function updateSubLayanan($id, $sub_layanan, $sub_layanan_2 = null)
    {
        $query = "UPDATE " . $this->table_name . " SET sub_layanan = :sub_layanan, sub_layanan_2 = :sub_layanan_2, updated_at = NOW() WHERE id_layanan = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':sub_layanan', $sub_layanan);
        $stmt->bindParam(':sub_layanan_2', $sub_layanan_2);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Method untuk hapus layanan
    public function deleteLayanan($id)
    {
        // Get file path before delete
        $layanan = $this->getLayananById($id);

        $query = "DELETE FROM " . $this->table_name . " WHERE id_layanan = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            // Delete file if exists
            if ($layanan && !empty($layanan['isi'])) {
                $extension = pathinfo($layanan['isi'], PATHINFO_EXTENSION);
                if (in_array(strtolower($extension), ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp']) && file_exists($layanan['isi'])) {
                    unlink($layanan['isi']);
                }
            }
            return true;
        }
        return false;
    }

    // Method untuk handle upload file PDF dan Gambar
    public function handleFileUpload($file, $nama_layanan)
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
                $targetDir = "uploads/layanan_documents/";
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
                $targetDir = "uploads/layanan_images/";
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

            // Generate filename: nama_layanan_timestamp.extension
            $newFilename = str_replace(' ', '_', $nama_layanan) . '_' . time() . '_' . uniqid() . '.' . $extension;
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
