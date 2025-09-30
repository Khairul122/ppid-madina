<?php
require_once 'models/DownloadModel.php';

class DownloadController {
    private $downloadModel;

    public function __construct() {
        global $database;
        $db = $database->getConnection();
        $this->downloadModel = new DownloadModel($db);
    }

    // Method untuk menampilkan file dalam browser (preview) secara fullscreen
    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?controller=auth&action=login');
            exit();
        }

        $fileName = 'panduan-pengguna-ppid.pdf';
        $filePath = 'ppid_assets/' . $fileName;

        if (file_exists($filePath)) {
            // Catat akses ke log (opsional)
            $this->downloadModel->recordDownload($_SESSION['user_id'], $fileName);
            
            // Set headers untuk menampilkan PDF di browser secara fullscreen
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="' . $fileName . '"');
            header('Content-Length: ' . filesize($filePath));
            
            // Bersihkan buffer output
            ob_clean();
            flush();
            
            // Baca dan kirim file
            readfile($filePath);
            exit;
        } else {
            // File tidak ditemukan
            http_response_code(404);
            echo "<!DOCTYPE html>
            <html>
            <head>
                <title>File Tidak Ditemukan</title>
                <style>
                    body { 
                        font-family: Arial, sans-serif; 
                        text-align: center; 
                        padding: 50px; 
                        background-color: #f8f9fa;
                    }
                    .error { 
                        color: #dc3545; 
                        font-size: 24px; 
                        margin-bottom: 20px;
                    }
                    .message { 
                        color: #6c757d; 
                        font-size: 18px;
                    }
                </style>
            </head>
            <body>
                <div class='error'>File Tidak Ditemukan</div>
                <div class='message'>File panduan pengguna tidak ditemukan. Silakan hubungi administrator.</div>
            </body>
            </html>";
            exit;
        }
    }
    
    // Method untuk download file
    public function download() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?controller=auth&action=login');
            exit();
        }

        $fileName = 'panduan-pengguna-ppid.pdf';
        $filePath = 'ppid_assets/' . $fileName;

        if (file_exists($filePath)) {
            // Catat download ke log (opsional)
            $this->downloadModel->recordDownload($_SESSION['user_id'], $fileName);
            
            // Set headers untuk download
            header('Content-Description: File Transfer');
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . $fileName . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filePath));
            
            // Bersihkan buffer output
            ob_clean();
            flush();
            
            // Baca dan kirim file
            readfile($filePath);
            exit;
        } else {
            // File tidak ditemukan
            http_response_code(404);
            echo "<!DOCTYPE html>
            <html>
            <head>
                <title>File Tidak Ditemukan</title>
                <style>
                    body { 
                        font-family: Arial, sans-serif; 
                        text-align: center; 
                        padding: 50px; 
                        background-color: #f8f9fa;
                    }
                    .error { 
                        color: #dc3545; 
                        font-size: 24px; 
                        margin-bottom: 20px;
                    }
                    .message { 
                        color: #6c757d; 
                        font-size: 18px;
                    }
                </style>
            </head>
            <body>
                <div class='error'>File Tidak Ditemukan</div>
                <div class='message'>File panduan pengguna tidak ditemukan. Silakan hubungi administrator.</div>
            </body>
            </html>";
            exit;
        }
    }
}
?>