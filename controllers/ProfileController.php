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

        $groupedProfiles = $this->profileModel->getGroupedProfiles();

        $data = [
            'groupedProfiles' => $groupedProfiles,
            'categories' => array_keys($groupedProfiles) // Daftar kategori untuk ditampilkan di tab
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

    // Method untuk menambah profile baru
    public function create() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?controller=auth&action=login');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama_kategori = trim($_POST['keterangan'] ?? ''); // Kategori yang dipilih (PPID/DAERAH)
            $keterangan = trim($_POST['keterangan_baru'] ?? ''); // Nama keterangan baru
            $isi = trim($_POST['isi_text'] ?? '');

            // Validasi input
            if (empty($nama_kategori)) {
                $_SESSION['error'] = 'Silakan pilih kategori!';
                header('Location: index.php?controller=profile');
                exit();
            }

            if (empty($keterangan)) {
                $_SESSION['error'] = 'Nama keterangan harus diisi!';
                header('Location: index.php?controller=profile');
                exit();
            }

            if (empty($isi)) {
                $_SESSION['error'] = 'Isi konten harus diisi!';
                header('Location: index.php?controller=profile');
                exit();
            }

            // Simpan data profile baru
            $result = $this->profileModel->insertProfile($nama_kategori, $keterangan, $isi);

            if ($result) {
                $_SESSION['success'] = 'Data profile berhasil ditambahkan!';
            } else {
                $_SESSION['error'] = 'Gagal menambahkan data profile!';
            }
        }

        header('Location: index.php?controller=profile');
        exit();
    }

    /**
     * Menampilkan halaman profil berdasarkan kategori.
     * Kategori diambil dari parameter URL.
     *
     * @param string $categorySlug Slug kategori dari URL (e.g., 'profil-pemimpin-daerah')
     */
    public function view($categorySlug = '') {
        // Handle case where category is passed as a query parameter instead of route parameter
        if (empty($categorySlug)) {
            $categorySlug = $_GET['category'] ?? '';
        }

        if (empty($categorySlug)) {
            // Redirect atau tampilkan error jika tidak ada kategori yang dipilih
            header('Location: index.php?controller=user&action=index');
            exit();
        }

        // Ubah slug menjadi nama kategori asli (e.g., 'profil-pemimpin-daerah' -> 'Profil Pemimpin Daerah')
        $categoryName = ucwords(str_replace('-', ' ', $categorySlug));

        // Panggil model untuk mendapatkan data profil
        $profiles = $this->profileModel->getProfilesByCategory($categoryName);

        // Siapkan data untuk dikirim ke view
        $data = [
            'title'    => $categoryName,
            'profiles' => $profiles
        ];

        // Muat view dan kirim data
        include 'views/profile/masyarakat.php';
    }

    // Method untuk upload gambar dari TinyMCE
    public function upload_image() {
        header('Content-Type: application/json');

        try {
            // Validasi request
            if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
                throw new Exception('Unauthorized access');
            }

            if (!isset($_FILES['file'])) {
                throw new Exception('No file uploaded');
            }

            $file = $_FILES['file'];

            // Cek error upload
            if ($file['error'] !== UPLOAD_ERR_OK) {
                throw new Exception('Upload error: ' . $file['error']);
            }

            // Validasi file
            $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
            if (!in_array($file['type'], $allowedTypes)) {
                throw new Exception('Invalid file type. Only JPG, PNG, GIF, and WEBP allowed.');
            }

            // Validasi ukuran file (maksimal 5MB)
            if ($file['size'] > 5 * 1024 * 1024) {
                throw new Exception('File size too large. Maximum 5MB allowed.');
            }

            // Setup direktori upload
            $uploadDir = 'uploads/profile_images/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            // Generate nama file unik
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = time() . '_' . uniqid() . '.' . $extension;
            $filepath = $uploadDir . $filename;

            // Upload file
            if (!move_uploaded_file($file['tmp_name'], $filepath)) {
                throw new Exception('Failed to upload image');
            }

            // Return URL relatif untuk ditampilkan di editor
            echo json_encode([
                'success' => true,
                'url' => $filepath,
                'message' => 'Image uploaded successfully'
            ]);

        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage(),
                'url' => '' // Tambahkan url kosong untuk menghindari error
            ]);
        }
        exit();
    }
}