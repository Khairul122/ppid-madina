<?php
require_once 'models/ProfileModel.php';

class ProfileController {
    private $profileModel;

    public function __construct() {
        global $database;
        $db = $database->getConnection();
        $this->profileModel = new ProfileModel($db);
    }

    // Method untuk menampilkan halaman profile
    public function index() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?controller=auth&action=login');
            exit();
        }

        $profiles = $this->profileModel->getAllProfile();

        $data = [
            'profiles' => $profiles
        ];

        include 'views/profile/index.php';
    }

    // Method untuk update profile
    public function update() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?controller=auth&action=login');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id_profile'] ?? null;
            $keterangan = $_POST['keterangan'] ?? '';
            
            if (!$id) {
                $_SESSION['error'] = 'ID profile tidak ditemukan!';
                header('Location: index.php?controller=profile');
                exit();
            }

            $profile = $this->profileModel->getProfileById($id);
            if (!$profile) {
                $_SESSION['error'] = 'Data profile tidak ditemukan!';
                header('Location: index.php?controller=profile');
                exit();
            }

            $isi = $profile['isi'] ?? '';
            $updateSuccess = false;

            if (!empty($_FILES['isi']['name'])) {
                // Handle file upload
                $uploadResult = $this->profileModel->handleFileUpload($_FILES['isi'], $keterangan);
                
                if ($uploadResult['success']) {
                    // Update with new file
                    $updateSuccess = $this->profileModel->updateProfileImage($id, $uploadResult['filepath']);
                    
                    // Delete old file if exists
                    if (!empty($profile['isi']) && file_exists($profile['isi'])) {
                        unlink($profile['isi']);
                    }
                } else {
                    $_SESSION['error'] = $uploadResult['message'];
                }
            } else {
                // Update with text
                $isiText = $_POST['isi_text'] ?? '';
                $updateSuccess = $this->profileModel->updateProfileText($id, $isiText);
            }

            if ($updateSuccess) {
                $_SESSION['success'] = 'Data profile berhasil diperbarui!';
            } else {
                $_SESSION['error'] = 'Gagal memperbarui data profile!';
            }
        }

        header('Location: index.php?controller=profile');
        exit();
    }
}