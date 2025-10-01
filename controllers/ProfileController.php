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
                // Handle PDF file upload
                $uploadResult = $this->profileModel->handleFileUpload($_FILES['isi'], $keterangan);
                
                if ($uploadResult['success']) {
                    // Update with new file path
                    $updateSuccess = $this->profileModel->updateProfileImage($id, $uploadResult['filepath']);
                    
                    // Delete old file if exists
                    if (!empty($profile['isi']) && file_exists($profile['isi']) && $profile['isi'] !== $uploadResult['filepath']) {
                        unlink($profile['isi']);
                    }
                } else {
                    $_SESSION['error'] = $uploadResult['message'];
                    header('Location: index.php?controller=profile');
                    exit();
                }
            } else {
                // Update with text content
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
            $content_type = $_POST['content_type'] ?? 'text'; // Default to text
            $isi = '';

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

            // Handle content based on type
            if ($content_type === 'pdf') {
                // Handle PDF upload
                if (!empty($_FILES['isi']['name'])) {
                    $uploadResult = $this->profileModel->handleFileUpload($_FILES['isi'], $keterangan);
                    
                    if ($uploadResult['success']) {
                        $isi = $uploadResult['filepath'];
                    } else {
                        $_SESSION['error'] = $uploadResult['message'];
                        header('Location: index.php?controller=profile');
                        exit();
                    }
                } else {
                    $_SESSION['error'] = 'File PDF harus diupload!';
                    header('Location: index.php?controller=profile');
                    exit();
                }
            } else {
                // Handle text content
                $isi = trim($_POST['isi_text'] ?? '');
                if (empty($isi)) {
                    $_SESSION['error'] = 'Isi konten harus diisi!';
                    header('Location: index.php?controller=profile');
                    exit();
                }
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

    /**
     * Menampilkan detail profil berdasarkan ID
     */
    public function viewDetail() {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            header('Location: index.php');
            exit();
        }

        // Get profile by ID
        $profile = $this->profileModel->getProfileById($id);

        if (!$profile) {
            $_SESSION['error'] = 'Profil tidak ditemukan';
            header('Location: index.php');
            exit();
        }

        // Siapkan data untuk view
        $data = [
            'title' => $profile['keterangan'],
            'profile' => $profile
        ];

        // Load view
        include 'views/profile/detail_public.php';
    }

    // Method untuk upload gambar dari TinyMCE
    public function upload_image() {
        // Clear any output buffers to prevent extra output
        while (ob_get_level()) {
            ob_end_clean();
        }

        // Disable error display to prevent it from breaking JSON
        ini_set('display_errors', 0);
        error_reporting(0);

        // Set header
        header('Content-Type: application/json; charset=utf-8');

        try {
            // Validasi session
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
                throw new Exception('Unauthorized access');
            }

            if (!isset($_FILES['file'])) {
                throw new Exception('No file uploaded. FILES: ' . json_encode($_FILES));
            }

            $file = $_FILES['file'];

            // Cek error upload
            if ($file['error'] !== UPLOAD_ERR_OK) {
                $errorMessages = [
                    UPLOAD_ERR_INI_SIZE => 'File exceeds upload_max_filesize',
                    UPLOAD_ERR_FORM_SIZE => 'File exceeds MAX_FILE_SIZE',
                    UPLOAD_ERR_PARTIAL => 'File only partially uploaded',
                    UPLOAD_ERR_NO_FILE => 'No file uploaded',
                    UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
                    UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
                    UPLOAD_ERR_EXTENSION => 'Upload stopped by extension'
                ];
                $errorMsg = $errorMessages[$file['error']] ?? 'Unknown upload error: ' . $file['error'];
                throw new Exception($errorMsg);
            }

            // Get file info
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);

            // Validasi file type berdasarkan MIME type
            $allowedImageTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
            $allowedDocTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
            $allowedVideoTypes = ['video/mp4', 'video/mpeg', 'video/quicktime', 'video/x-msvideo', 'video/x-flv', 'video/webm'];
            $allowedAudioTypes = ['audio/mpeg', 'audio/wav', 'audio/ogg', 'audio/mp4', 'audio/webm'];

            $allAllowedTypes = array_merge($allowedImageTypes, $allowedDocTypes, $allowedVideoTypes, $allowedAudioTypes);

            if (!in_array($mimeType, $allAllowedTypes) && !in_array($file['type'], $allAllowedTypes)) {
                throw new Exception('Invalid file type: ' . $mimeType . '. Allowed: images (JPG, PNG, GIF, WEBP), documents (PDF, DOC, DOCX), videos (MP4, MPEG, MOV, AVI, FLV, WEBM), audio (MP3, WAV, OGG, M4A, WEBM).');
            }

            // Validasi ukuran file (maksimal 10MB untuk dokumen/video, 5MB untuk gambar/audio)
            $maxSize = in_array($mimeType, array_merge($allowedDocTypes, $allowedVideoTypes)) ? 10 * 1024 * 1024 : 5 * 1024 * 1024;
            if ($file['size'] > $maxSize) {
                throw new Exception('File size too large: ' . round($file['size'] / 1024 / 1024, 2) . 'MB. Maximum ' . ($maxSize / 1024 / 1024) . 'MB allowed.');
            }

            // Setup direktori upload berdasarkan tipe file
            if (in_array($mimeType, $allowedImageTypes)) {
                $uploadDir = 'uploads/profile_images/';
            } elseif (in_array($mimeType, $allowedDocTypes)) {
                $uploadDir = 'uploads/profile_documents/';
            } elseif (in_array($mimeType, $allowedVideoTypes)) {
                $uploadDir = 'uploads/profile_videos/';
            } else {
                $uploadDir = 'uploads/profile_media/';
            }

            if (!is_dir($uploadDir)) {
                if (!mkdir($uploadDir, 0755, true)) {
                    throw new Exception('Failed to create upload directory');
                }
            }

            // Generate nama file unik
            $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (empty($extension)) {
                // Determine extension based on MIME type
                $mimeToExt = [
                    'image/jpeg' => 'jpg',
                    'image/png' => 'png',
                    'image/gif' => 'gif',
                    'image/webp' => 'webp',
                    'application/pdf' => 'pdf',
                    'application/msword' => 'doc',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
                    'video/mp4' => 'mp4',
                    'video/mpeg' => 'mpeg',
                    'audio/mpeg' => 'mp3',
                    'audio/wav' => 'wav'
                ];
                $extension = $mimeToExt[$mimeType] ?? 'bin';
            }
            $filename = time() . '_' . uniqid() . '.' . $extension;
            $filepath = $uploadDir . $filename;

            // Upload file
            if (!move_uploaded_file($file['tmp_name'], $filepath)) {
                throw new Exception('Failed to move uploaded file to: ' . $filepath);
            }

            // Return URL relatif untuk ditampilkan di editor
            // Gunakan absolute URL atau path dari root
            $baseUrl = '';
            if (isset($_SERVER['HTTP_HOST'])) {
                $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
                $baseUrl = $protocol . '://' . $_SERVER['HTTP_HOST'];

                // Add base path if exists
                $scriptName = $_SERVER['SCRIPT_NAME'];
                $basePath = dirname($scriptName);
                if ($basePath !== '/' && $basePath !== '\\') {
                    $baseUrl .= $basePath;
                }
                $baseUrl = rtrim($baseUrl, '/') . '/';
            }

            $response = [
                'success' => true,
                'url' => $baseUrl . $filepath,
                'message' => 'File uploaded successfully',
                'filename' => $filename,
                'location' => $baseUrl . $filepath  // TinyMCE juga mendukung 'location'
            ];

            echo json_encode($response, JSON_UNESCAPED_SLASHES);

        } catch (Exception $e) {
            $response = [
                'success' => false,
                'message' => $e->getMessage(),
                'url' => '',
                'location' => $e->getFile() . ':' . $e->getLine()
            ];

            echo json_encode($response, JSON_UNESCAPED_SLASHES);
        }

        exit();
    }
}