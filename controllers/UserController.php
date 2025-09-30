<?php
require_once 'models/UserModel.php';

class UserController {
    private $userModel;

    public function __construct() {
        global $database;
        $db = $database->getConnection();
        $this->userModel = new UserModel($db);
    }

    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?controller=auth&action=login');
            exit();
        }

        // Get user biodata
        $user_data = $this->userModel->getUserBiodata($_SESSION['user_id']);

        // Set default values if data not found
        $nama_lengkap = $user_data['nama_lengkap'] ?? $_SESSION['username'] ?? 'Pengguna';
        $status_pengguna = $user_data['status_pengguna'] ?? 'publik';
        $current_datetime = date('d F Y H:i');

        // Tampilkan halaman dashboard sesuai role
        $role = $_SESSION['role'];

        switch ($role) {
            case 'admin':
                include 'views/dashboard/admin/index.php';
                break;
            case 'petugas':
                include 'views/dashboard/petugas/index.php';
                break;
            case 'masyarakat':
                include 'views/dashboard/masyarakat/index.php';
                break;
            default:
                include 'views/dashboard/masyarakat/index.php';
                break;
        }
    }

    public function profile() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?controller=auth&action=login');
            exit();
        }

        // Get user biodata
        $user_data = $this->userModel->getUserBiodata($_SESSION['user_id']);

        // Set default values if data not found
        $nama_lengkap = $user_data['nama_lengkap'] ?? $_SESSION['username'] ?? 'Pengguna';
        $status_pengguna = $user_data['status_pengguna'] ?? 'publik';
        $current_datetime = date('d F Y H:i');

        // Route to appropriate profile view based on role
        $role = $_SESSION['role'] ?? 'masyarakat';

        switch ($role) {
            case 'admin':
                include 'views/user/profile.php'; // Keep existing admin profile
                break;
            case 'petugas':
                include 'views/user/profile.php'; // Keep existing petugas profile
                break;
            case 'masyarakat':
                include 'views/profile/masyarakat.php';
                break;
            default:
                include 'views/profile/masyarakat.php';
                break;
        }
    }

    public function changePassword() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?controller=auth&action=login');
            exit();
        }

        $error = '';
        $success = '';

        if ($_POST) {
            $current_password = $_POST['current_password'];
            $new_password = $_POST['new_password'];
            $confirm_password = $_POST['confirm_password'];

            // Validation
            if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
                $error = 'Semua field wajib diisi!';
            } elseif ($new_password !== $confirm_password) {
                $error = 'Password baru dan konfirmasi password tidak cocok!';
            } elseif (strlen($new_password) < 6) {
                $error = 'Password baru minimal 6 karakter!';
            } else {
                // Verify current password
                $user = $this->userModel->findById($_SESSION['user_id']);
                if (!$user || md5($current_password) !== $user['password']) {
                    $error = 'Password saat ini salah!';
                } else {
                    // Update password
                    if ($this->userModel->updatePassword($_SESSION['user_id'], $new_password)) {
                        $success = 'Password berhasil diubah!';
                    } else {
                        $error = 'Gagal mengubah password. Silahkan coba lagi.';
                    }
                }
            }
        }

        // Set session messages
        if ($error) {
            $_SESSION['error_message'] = $error;
        }
        if ($success) {
            $_SESSION['success_message'] = $success;
        }

        // Redirect back to profile
        header('Location: index.php?controller=user&action=profile');
        exit();
    }

    public function uploadProfilePhoto() {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error_message'] = 'Anda harus login terlebih dahulu';
            header('Location: index.php?controller=auth&action=login');
            exit();
        }

        if (!isset($_FILES['profile_photo']) || $_FILES['profile_photo']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['error_message'] = 'Tidak ada file yang diupload atau terjadi error';
            header('Location: index.php?controller=user&action=profile');
            exit();
        }

        $result = $this->userModel->handleProfilePhotoUpload($_SESSION['user_id'], $_FILES['profile_photo']);

        if ($result['success']) {
            $_SESSION['success_message'] = $result['message'];
        } else {
            $_SESSION['error_message'] = $result['message'];
        }

        header('Location: index.php?controller=user&action=profile');
        exit();
    }

    public function downloadPanduan() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?controller=auth&action=login');
            exit();
        }

        // Path ke file panduan
        $filePath = 'ppid_assets/panduan-pengguna-ppid.pdf';

        // Jika parameter download ada, kirim file untuk diunduh
        if (isset($_GET['download'])) {
            if (file_exists($filePath)) {
                // Set headers untuk download
                header('Content-Description: File Transfer');
                header('Content-Type: application/pdf');
                header('Content-Disposition: attachment; filename="panduan-pengguna-ppid.pdf"');
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
                echo "File tidak ditemukan.";
                exit;
            }
        }

        // Jika tidak, tampilkan halaman preview
        include 'views/user/download_panduan.php';
    }
}
?>