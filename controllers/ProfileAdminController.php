<?php
require_once 'models/ProfileAdminModel.php';

class ProfileAdminController
{
    private $profileModel;

    public function __construct()
    {
        global $database;
        $db = $database->getConnection();
        $this->profileModel = new ProfileAdminModel($db);
    }

    public function index()
    {
        // Check if user is logged in and has admin role
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?controller=auth&action=login');
            exit();
        }

        // Get user data
        $user_id = $_SESSION['user_id'];
        $user = $this->profileModel->findById($user_id);

        $email = $user['email'] ?? '';
        $username = $user['username'] ?? '';
        $foto_profile = $user['foto_profile'] ?? '';

        include 'views/profile_admin/index.php';
    }

    public function updateProfile()
    {
        // Check if user is logged in and has admin role
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?controller=auth&action=login');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=profileadmin&action=index');
            exit();
        }

        $user_id = $_SESSION['user_id'];
        $email = trim($_POST['email'] ?? '');
        $username = trim($_POST['username'] ?? '');

        // Validation
        if (empty($email) || empty($username)) {
            $_SESSION['error_message'] = 'Email dan username wajib diisi';
            header('Location: index.php?controller=profileadmin&action=index');
            exit();
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error_message'] = 'Format email tidak valid';
            header('Location: index.php?controller=profileadmin&action=index');
            exit();
        }

        // Update profile
        $result = $this->profileModel->updateProfile($user_id, $email, $username);

        if ($result['success']) {
            $_SESSION['success_message'] = $result['message'];
        } else {
            $_SESSION['error_message'] = $result['message'];
        }

        header('Location: index.php?controller=profileadmin&action=index');
        exit();
    }

    public function updatePassword()
    {
        // Check if user is logged in and has admin role
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?controller=auth&action=login');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=profileadmin&action=index');
            exit();
        }

        $user_id = $_SESSION['user_id'];
        $current_password = $_POST['current_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        // Validation
        if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
            $_SESSION['error_message'] = 'Semua field password wajib diisi';
            header('Location: index.php?controller=profileadmin&action=index');
            exit();
        }

        if ($new_password !== $confirm_password) {
            $_SESSION['error_message'] = 'Password baru dan konfirmasi password tidak cocok';
            header('Location: index.php?controller=profileadmin&action=index');
            exit();
        }

        if (strlen($new_password) < 8) {
            $_SESSION['error_message'] = 'Password baru minimal 8 karakter';
            header('Location: index.php?controller=profileadmin&action=index');
            exit();
        }

        // Update password
        $result = $this->profileModel->updatePasswordWithVerification($user_id, $current_password, $new_password);

        if ($result['success']) {
            $_SESSION['success_message'] = $result['message'];
        } else {
            $_SESSION['error_message'] = $result['message'];
        }

        header('Location: index.php?controller=profileadmin&action=index');
        exit();
    }

    public function updatePhoto()
    {
        // Check if user is logged in and has admin role
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?controller=auth&action=login');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=profileadmin&action=index');
            exit();
        }

        $user_id = $_SESSION['user_id'];
        $file = $_FILES['foto_profile'] ?? null;

        if (!$file || $file['error'] === UPLOAD_ERR_NO_FILE) {
            $_SESSION['error_message'] = 'Silakan pilih file foto terlebih dahulu';
            header('Location: index.php?controller=profileadmin&action=index');
            exit();
        }

        // Update photo
        $result = $this->profileModel->updateProfilePhoto($user_id, $file);

        if ($result['success']) {
            $_SESSION['success_message'] = $result['message'];
        } else {
            $_SESSION['error_message'] = $result['message'];
        }

        header('Location: index.php?controller=profileadmin&action=index');
        exit();
    }
}