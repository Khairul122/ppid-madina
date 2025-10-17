<?php
require_once 'models/AuthModel.php';

class AuthController {
    private $authModel;

    public function __construct() {
        global $database;
        $db = $database->getConnection();
        $this->authModel = new AuthModel($db);
    }

    public function login() {

        // Jika user sudah login, redirect ke dashboard
        if (isset($_SESSION['user_id'])) {
            if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'petugas') {
                header('Location: index.php?controller=dashboard&action=index');
            } else {
                header('Location: index.php?controller=user&action=index');
            }
            exit();
        }

        $error = '';

        if ($_POST) {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $user = $this->authModel->login($email, $password);

            if ($user) {
                // Set session
                $_SESSION['user_id'] = $user['id_user'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['jabatan'] = $user['jabatan'];
                $_SESSION['role'] = $user['role'];

                // Redirect berdasarkan role
                if ($user['role'] === 'admin' || $user['role'] === 'petugas') {
                    header('Location: index.php?controller=dashboard&action=index');
                } else {
                    header('Location: index.php?controller=user&action=index');
                }
                exit();
            } else {
                $error = 'Email atau password salah!';
            }
        }

        include 'views/auth/login.php';
    }

    public function logout() {
        $this->authModel->logout();
        header('Location: index.php?controller=auth&action=login');
        exit();
    }

    public function register() {
        // Jika user sudah login, redirect ke dashboard
        if (isset($_SESSION['user_id'])) {
            if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'petugas') {
                header('Location: index.php?controller=dashboard&action=index');
            } else {
                header('Location: index.php?controller=user&action=index');
            }
            exit();
        }

        $error = '';
        $success = '';

        if ($_POST) {
            // Validasi input
            $requiredFields = ['nama_lengkap', 'nik', 'alamat', 'provinsi', 'city', 'jenis_kelamin', 'email', 'password', 'confirm_password', 'status_pengguna'];
            $missingFields = [];

            foreach ($requiredFields as $field) {
                if (empty($_POST[$field])) {
                    $missingFields[] = $field;
                }
            }

            // Additional validation for lembaga
            if ($_POST['status_pengguna'] === 'lembaga' && empty($_POST['nama_lembaga'])) {
                $missingFields[] = 'nama_lembaga';
            }

            if (!empty($missingFields)) {
                $error = 'Semua field wajib diisi!';
            } elseif ($_POST['password'] !== $_POST['confirm_password']) {
                $error = 'Password dan konfirmasi password tidak cocok!';
            } elseif (strlen($_POST['password']) < 6) {
                $error = 'Password minimal 6 karakter!';
            } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $error = 'Format email tidak valid!';
            } elseif (strlen($_POST['nik']) !== 16) {
                $error = 'NIK harus 16 digit!';
            } elseif ($this->authModel->emailExists($_POST['email'])) {
                $error = 'Email sudah terdaftar!';
            } elseif ($this->authModel->nikExists($_POST['nik'])) {
                $error = 'NIK sudah terdaftar!';
            } else {
                // Additional validation for file uploads
                $uploadErrors = [];
                
                // Check if KTP was uploaded
                $hasKtp = isset($_FILES['upload_ktp']) && $_FILES['upload_ktp']['error'] === UPLOAD_ERR_OK;
                
                // Require KTP for all users
                if (!$hasKtp) {
                    $uploadErrors[] = 'Upload KTP wajib dilakukan untuk semua pengguna';
                }
                
                // Check if AKTA was uploaded
                $hasAkta = isset($_FILES['upload_akta']) && $_FILES['upload_akta']['error'] === UPLOAD_ERR_OK;
                
                // Require AKTA for lembaga only
                if ($_POST['status_pengguna'] === 'lembaga' && !$hasAkta) {
                    $uploadErrors[] = 'Upload AKTA wajib dilakukan untuk pengguna lembaga';
                }
                
                if (!empty($uploadErrors)) {
                    $error = implode('<br>', $uploadErrors);
                } else {
                    // Proses registrasi
                    $namaLembaga = $_POST['status_pengguna'] === 'lembaga' ? $_POST['nama_lembaga'] : null;
                    $data = [
                        'nama_lengkap' => $_POST['nama_lengkap'],
                        'nik' => $_POST['nik'],
                        'alamat' => $_POST['alamat'],
                        'provinsi' => $_POST['provinsi'],
                        'city' => $_POST['city'],
                        'jenis_kelamin' => $_POST['jenis_kelamin'],
                        'usia' => !empty($_POST['usia']) ? (int)$_POST['usia'] : null,
                        'pendidikan' => $_POST['pendidikan'] ?? null,
                        'pekerjaan' => $_POST['pekerjaan'] ?? null,
                        'no_kontak' => $_POST['no_kontak'] ?? null,
                        'email' => $_POST['email'],
                        'password' => $_POST['password'],
                        'status_pengguna' => $_POST['status_pengguna'],
                        'nama_lembaga' => $namaLembaga
                    ];

                    if ($this->authModel->register($data)) {
                        $success = 'Registrasi berhasil! Silahkan login dengan akun Anda.';
                    } else {
                        $error = 'Terjadi kesalahan saat registrasi. Silahkan coba lagi.';
                    }
                }
            }
        }

        include 'views/auth/register.php';
    }
}
?>