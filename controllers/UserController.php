<?php
require_once 'models/UserModel.php';
require_once 'models/ProfileModel.php';

class UserController {
    private $userModel;
    private $profileModel;

    public function __construct() {
        global $database;
        $db = $database->getConnection();
        $this->userModel = new UserModel($db);
        $this->profileModel = new ProfileModel($db);
    }

    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?controller=auth&action=login');
            exit();
        }

        // Get user biodata
        $user_data = $this->userModel->getUserBiodata($_SESSION['user_id']);

        // Get profile categories for navbar
        $profile_categories = $this->profileModel->getUniqueCategories();

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
                // Get petugas-specific data
                require_once 'models/PermohonanPetugasModel.php';
                global $database;
                $db = $database->getConnection();
                $permohonanPetugasModel = new PermohonanPetugasModel($db);
                
                // Get the petugas's SKPD
                $petugas_skpd = $permohonanPetugasModel->getPetugasSKPDByUserId($_SESSION['user_id']);
                
                if (!$petugas_skpd) {
                    $_SESSION['error_message'] = 'Data SKPD petugas tidak ditemukan';
                    $data = [
                        'permohonan_stats' => [
                            'diproses' => 0,
                            'disposisi' => 0,
                            'selesai' => 0,
                            'ditolak' => 0
                        ],
                        'recent_permohonan' => []
                    ];
                    include 'views/dashboard/petugas/index.php';
                    return;
                }
                
                $nama_skpd = $petugas_skpd['nama_skpd'];
                
                // Get petugas-specific statistics
                $data = [];
                $data['permohonan_stats'] = $permohonanPetugasModel->getPetugasStatsBySKPD($nama_skpd);
                
                // Map the stats to match the dashboard expectations
                $data['permohonan_stats'] = [
                    'permohonan_baru' => $data['permohonan_stats']['diproses'] ?? 0,
                    'permohonan_proses' => $data['permohonan_stats']['disposisi'] ?? 0,
                    'permohonan_selesai' => $data['permohonan_stats']['selesai'] ?? 0,
                    'permohonan_ditolak' => $data['permohonan_stats']['ditolak'] ?? 0
                ];
                
                // Get recent permohonan for this SKPD (limit to 5)
                $recent_permohonan = $permohonanPetugasModel->getPermohonanBySKPD($nama_skpd, 5, 0, 'all', '');
                
                // Format the recent permohonan data to match the expected structure
                $data['recent_permohonan'] = [];
                foreach ($recent_permohonan as $permohonan) {
                    $data['recent_permohonan'][] = [
                        'id_permohonan' => $permohonan['id_permohonan'] ?? '',
                        'judul_dokumen' => $permohonan['judul_dokumen'] ?? 'Permohonan Informasi',
                        'status' => $permohonan['status'] ?? 'Pending',
                        'username' => $permohonan['nama_lengkap'] ?? $permohonan['username'] ?? 'Pengguna',
                        'created_at' => $permohonan['created_at'] ?? date('Y-m-d H:i:s')
                    ];
                }
                
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

        // Get profile categories for navbar
        $profile_categories = $this->profileModel->getUniqueCategories();

        // Set default values if data not found
        $nama_lengkap = $user_data['nama_lengkap'] ?? $_SESSION['username'] ?? 'Pengguna';
        $status_pengguna = $user_data['status_pengguna'] ?? 'publik';
        $current_datetime = date('d F Y H:i');

        // Update session with user data for consistent display
        if ($user_data) {
            $_SESSION['username'] = $user_data['username'] ?? $_SESSION['username'];
            $_SESSION['email'] = $user_data['email'] ?? $_SESSION['email'] ?? '';
            $_SESSION['created_at'] = $user_data['created_at'] ?? date('Y-m-d');

            // Update profile photo in session if it exists in database
            if (!empty($user_data['foto_profile']) && file_exists($user_data['foto_profile'])) {
                $_SESSION['profile_photo'] = $user_data['foto_profile'];
            }
        }

        // Route to appropriate profile view based on role
        $role = $_SESSION['role'] ?? 'masyarakat';

        switch ($role) {
            case 'admin':
                header('Location: index.php?controller=profileadmin&action=index');
                exit();
            case 'petugas':
                header('Location: index.php?controller=profilepetugas&action=index');
                exit();
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

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=user&action=profile');
            exit();
        }

        $current_password = $_POST['current_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        // Validation
        if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
            $_SESSION['error'] = 'Semua field wajib diisi!';
        } elseif ($new_password !== $confirm_password) {
            $_SESSION['error'] = 'Password baru dan konfirmasi password tidak cocok!';
        } elseif (strlen($new_password) < 8) {
            $_SESSION['error'] = 'Password baru minimal 8 karakter!';
        } elseif (!preg_match('/[A-Za-z]/', $new_password) || !preg_match('/[0-9]/', $new_password)) {
            $_SESSION['error'] = 'Password harus mengandung kombinasi huruf dan angka!';
        } else {
            // Verify current password
            $user = $this->userModel->findById($_SESSION['user_id']);
            if (!$user) {
                $_SESSION['error'] = 'User tidak ditemukan!';
            } elseif (md5($current_password) !== $user['password']) {
                $_SESSION['error'] = 'Password saat ini salah!';
            } else {
                // Update password
                if ($this->userModel->updatePassword($_SESSION['user_id'], $new_password)) {
                    $_SESSION['success'] = 'Password berhasil diubah! Silakan gunakan password baru untuk login berikutnya.';
                } else {
                    $_SESSION['error'] = 'Gagal mengubah password. Silakan coba lagi.';
                }
            }
        }

        // Redirect back to profile
        header('Location: index.php?controller=user&action=profile');
        exit();
    }

    public function uploadProfilePhoto() {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Anda harus login terlebih dahulu';
            header('Location: index.php?controller=auth&action=login');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=user&action=profile');
            exit();
        }

        if (!isset($_FILES['profile_photo']) || $_FILES['profile_photo']['error'] !== UPLOAD_ERR_OK) {
            if ($_FILES['profile_photo']['error'] === UPLOAD_ERR_NO_FILE) {
                $_SESSION['error'] = 'Silakan pilih file foto terlebih dahulu';
            } elseif ($_FILES['profile_photo']['error'] === UPLOAD_ERR_INI_SIZE || $_FILES['profile_photo']['error'] === UPLOAD_ERR_FORM_SIZE) {
                $_SESSION['error'] = 'Ukuran file terlalu besar. Maksimal 2MB';
            } else {
                $_SESSION['error'] = 'Terjadi error saat mengupload file';
            }
            header('Location: index.php?controller=user&action=profile');
            exit();
        }

        $result = $this->userModel->handleProfilePhotoUpload($_SESSION['user_id'], $_FILES['profile_photo']);

        if ($result['success']) {
            $_SESSION['success'] = $result['message'];
            // Update session with new profile photo path
            $_SESSION['profile_photo'] = $result['filepath'];
        } else {
            $_SESSION['error'] = $result['message'];
        }

        header('Location: index.php?controller=user&action=profile');
        exit();
    }

    public function downloadPanduan() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?controller=auth&action=login');
            exit();
        }

        // Get profile categories for navbar
        $profile_categories = $this->profileModel->getUniqueCategories();

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