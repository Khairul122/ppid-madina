<?php
class DownloadModel {
    private $conn;
    private $table_name = "downloads";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Method untuk mencatat download
    public function recordDownload($userId, $fileName) {
        try {
            $query = "INSERT INTO download_logs (user_id, file_name, download_date) 
                      VALUES (:user_id, :file_name, NOW())";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":user_id", $userId);
            $stmt->bindParam(":file_name", $fileName);
            
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error recording download: " . $e->getMessage());
            return false;
        }
    }

    // Method untuk mendapatkan informasi file
    public function getFileInfo($fileName) {
        // Untuk saat ini, kita akan mengembalikan informasi statis
        // Anda bisa mengembangkan ini lebih lanjut untuk menyimpan informasi file di database
        $filePath = 'ppid_assets/' . $fileName;
        
        if (file_exists($filePath)) {
            return [
                'name' => $fileName,
                'size' => filesize($filePath),
                'modified' => date('Y-m-d H:i:s', filemtime($filePath)),
                'path' => $filePath
            ];
        }
        
        return false;
    }
}
?>