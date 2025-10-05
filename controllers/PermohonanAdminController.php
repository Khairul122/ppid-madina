<?php
require_once 'models/PermohonanAdminModel.php';
require_once 'models/SKPDModel.php';
require_once 'vendor/tecnickcom/tcpdf/tcpdf.php';

class PermohonanAdminController
{
    private $permohonanAdminModel;
    private $skpdModel;

    public function __construct()
    {
        // Check if user is admin
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            $_SESSION['error_message'] = 'Akses ditolak. Hanya admin yang dapat mengakses halaman ini.';
            header('Location: index.php?controller=auth&action=login');
            exit();
        }

        global $database;
        $db = $database->getConnection();
        $this->permohonanAdminModel = new PermohonanAdminModel($db);
        $this->skpdModel = new SKPDModel($db);
    }

    // Calculate working days (excluding weekends)
    private function calculateWorkingDays($workingDays)
    {
        $currentDate = new DateTime();
        $daysAdded = 0;
        $totalDays = 0;

        while ($daysAdded < $workingDays) {
            $totalDays++;
            $currentDate->add(new DateInterval('P1D'));

            // Check if it's not a weekend (Saturday = 6, Sunday = 0)
            if ($currentDate->format('w') != 0 && $currentDate->format('w') != 6) {
                $daysAdded++;
            }
        }

        return $totalDays;
    }

    // Display all permohonan for admin
    public function index()
    {
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        // Hardcoded status to "Masuk" only
        $status = 'Masuk';
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';

        $permohonan_list = $this->permohonanAdminModel->getAllPermohonan($limit, $offset, $status, $search);
        $total_records = $this->permohonanAdminModel->countAllPermohonan($status, $search);
        $total_pages = ceil($total_records / $limit);

        $stats = $this->permohonanAdminModel->getAdminStats();

        $success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
        $error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';

        unset($_SESSION['success_message']);
        unset($_SESSION['error_message']);

        include 'views/permohonan_admin/index.php';
    }

    // Display create form
    public function create()
    {
        $tujuan_permohonan_list = $this->permohonanAdminModel->getDistinctTujuanPermohonan();
        $skpd_list = $this->permohonanAdminModel->getAllSKPD();

        $success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
        $error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';

        unset($_SESSION['success_message']);
        unset($_SESSION['error_message']);

        include 'views/permohonan_admin/create.php';
    }

    // AJAX endpoint to get SKPD names based on selected category
    public function getKomponen()
    {
        header('Content-Type: application/json');

        if (!isset($_GET['tujuan_permohonan']) || empty($_GET['tujuan_permohonan'])) {
            echo json_encode(['success' => false, 'message' => 'Kategori SKPD tidak diberikan']);
            exit();
        }

        $kategori = $_GET['tujuan_permohonan'];
        $skpd_list = $this->permohonanAdminModel->getSKPDByKategori($kategori);

        // Format data to match the expected structure (nama_skpd as both value and label)
        $result = [];
        foreach ($skpd_list as $skpd) {
            $result[] = [
                'id_skpd' => $skpd['id_skpd'],
                'nama_tujuan_permohonan' => $skpd['nama_skpd']  // Using nama_skpd instead of komponen
            ];
        }

        echo json_encode(['success' => true, 'data' => $result]);
        exit();
    }

    // Process create form
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=permohonanadmin&action=create');
            exit();
        }

        // Validate required fields
        $required_fields = [
            'nama_lengkap',
            'nik',
            'alamat',
            'provinsi',
            'city',
            'no_kontak',
            'email',
            'username',
            'password',
            'komponen_tujuan',
            'judul_dokumen',
            'kandungan_informasi',
            'tujuan_penggunaan_informasi'
        ];

        $errors = [];
        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                $errors[] = "Field " . str_replace('_', ' ', $field) . " wajib diisi";
            }
        }

        // Validate NIK
        if (!empty($_POST['nik']) && strlen($_POST['nik']) !== 16) {
            $errors[] = "NIK harus 16 digit";
        }

        // Validate email
        if (!empty($_POST['email']) && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Format email tidak valid";
        }

        if (!empty($errors)) {
            $_SESSION['error_message'] = implode('<br>', $errors);
            header('Location: index.php?controller=permohonanadmin&action=create');
            exit();
        }

        // Handle file uploads
        $uploaded_files = $this->handleFileUploads();

        // Prepare data arrays
        $biodataData = [
            'nama_lengkap' => $_POST['nama_lengkap'],
            'nik' => $_POST['nik'],
            'alamat' => $_POST['alamat'],
            'provinsi' => $_POST['provinsi'],
            'city' => $_POST['city'],
            'jenis_kelamin' => $_POST['jenis_kelamin'] ?? null,
            'usia' => !empty($_POST['usia']) ? intval($_POST['usia']) : null,
            'pendidikan' => $_POST['pendidikan'] ?? null,
            'pekerjaan' => $_POST['pekerjaan'] ?? null,
            'no_kontak' => $_POST['no_kontak'],
            'email' => $_POST['email'],
            'foto_profile' => $uploaded_files['foto_profile'] ?? null,
            'status_pengguna' => $_POST['status_pengguna'] ?? 'pribadi',
            'nama_lembaga' => $_POST['nama_lembaga'] ?? null,
            'upload_ktp' => $uploaded_files['upload_ktp'] ?? null,
            'upload_akta' => $uploaded_files['upload_akta'] ?? null
        ];

        // Generate username if not provided or if it's duplicate
        $username = !empty($_POST['username']) ? $_POST['username'] : $_POST['nama_lengkap'];

        // If username is still empty or already exists, generate a unique one
        if (empty($username) || $this->permohonanAdminModel->checkUsernameExists($username)) {
            // Generate a unique username using nama_lengkap and appending a number if needed
            $baseUsername = !empty($_POST['nama_lengkap']) ? preg_replace('/[^A-Za-z0-9]/', '', strtolower($_POST['nama_lengkap'])) : 'user';
            $username = $baseUsername;
            $counter = 1;

            while ($this->permohonanAdminModel->checkUsernameExists($username)) {
                $username = $baseUsername . $counter;
                $counter++;
            }
        }

        $userData = [
            'email' => $_POST['email'],
            'username' => $username,
            'password' => md5($_POST['password']),
            'role' => 'masyarakat'
        ];

        // Check if email and NIK already exist
        if ($this->permohonanAdminModel->checkEmailExists($_POST['email'])) {
            $_SESSION['error_message'] = 'Email sudah terdaftar, gunakan email lain';
            header('Location: index.php?controller=permohonanadmin&action=create');
            exit();
        }

        if ($this->permohonanAdminModel->checkNikExists($_POST['nik'])) {
            $_SESSION['error_message'] = 'NIK sudah terdaftar, gunakan NIK lain';
            header('Location: index.php?controller=permohonanadmin&action=create');
            exit();
        }

        // Create user first
        $userResult = $this->permohonanAdminModel->createNewUser($userData, $biodataData);

        if (!$userResult['success']) {
            $_SESSION['error_message'] = $userResult['message'];
            header('Location: index.php?controller=permohonanadmin&action=create');
            exit();
        }

        // Calculate working days (7 working days from now)
        $sisa_jatuh_tempo = $this->calculateWorkingDays(7);

        // Prepare permohonan data with new structure
        $permohonanData = [
            'id_user' => $userResult['user_id'],
            'sisa_jatuh_tempo' => $sisa_jatuh_tempo,
            'tujuan_permohonan' => $_POST['tujuan_permohonan'] ?? null,
            'komponen_tujuan' => $_POST['komponen_tujuan'],
            'judul_dokumen' => $_POST['judul_dokumen'],
            'kandungan_informasi' => $_POST['kandungan_informasi'] ?? '',
            'tujuan_penggunaan_informasi' => $_POST['tujuan_penggunaan_informasi'],
            'upload_foto_identitas' => $uploaded_files['upload_foto_identitas'] ?? null,
            'upload_data_pedukung' => $uploaded_files['upload_data_pedukung'] ?? null,
            'status' => 'Diproses',  // Status otomatis diisi 'Diproses' karena form input dihapus
            'sumber_media' => 'Website',  // Always set to Website for all inputs
            'catatan_petugas' => $_POST['catatan_petugas'] ?? null
        ];

        $result = $this->permohonanAdminModel->createPermohonan($permohonanData);

        if ($result['success']) {
            $_SESSION['success_message'] = $result['message'];
            header('Location: index.php?controller=permohonanadmin&action=index');
        } else {
            $_SESSION['error_message'] = $result['message'];
            header('Location: index.php?controller=permohonanadmin&action=create');
        }
        exit();
    }

    // Display view details
    public function view()
    {
        if (!isset($_GET['id'])) {
            $_SESSION['error_message'] = 'ID permohonan tidak ditemukan';
            header('Location: index.php?controller=permohonanadmin&action=index');
            exit();
        }

        $id = $_GET['id'];
        $permohonan = $this->permohonanAdminModel->getPermohonanById($id);

        if (!$permohonan) {
            $_SESSION['error_message'] = 'Permohonan tidak ditemukan';
            header('Location: index.php?controller=permohonanadmin&action=index');
            exit();
        }

        include 'views/permohonan_admin/view.php';
    }

    // Display edit form
    public function edit()
    {
        if (!isset($_GET['id'])) {
            $_SESSION['error_message'] = 'ID permohonan tidak ditemukan';
            header('Location: index.php?controller=permohonanadmin&action=index');
            exit();
        }

        $id = $_GET['id'];
        $permohonan = $this->permohonanAdminModel->getPermohonanById($id);

        if (!$permohonan) {
            $_SESSION['error_message'] = 'Permohonan tidak ditemukan';
            header('Location: index.php?controller=permohonanadmin&action=index');
            exit();
        }

        $tujuan_permohonan_list = $this->permohonanAdminModel->getDistinctTujuanPermohonan();
        $skpd_list = $this->permohonanAdminModel->getAllSKPD();

        $success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
        $error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';

        unset($_SESSION['success_message']);
        unset($_SESSION['error_message']);

        include 'views/permohonan_admin/edit_simple.php';
    }

    // Process edit form
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id_permohonan'])) {
            header('Location: index.php?controller=permohonanadmin&action=index');
            exit();
        }

        $id = $_POST['id_permohonan'];
        $permohonan = $this->permohonanAdminModel->getPermohonanById($id);

        if (!$permohonan) {
            $_SESSION['error_message'] = 'Permohonan tidak ditemukan';
            header('Location: index.php?controller=permohonanadmin&action=index');
            exit();
        }

        // Validate required fields
        $required_fields = [
            'komponen_tujuan',
            'judul_dokumen',
            'kandungan_informasi',
            'tujuan_penggunaan_informasi'
        ];

        $errors = [];
        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                $errors[] = "Field " . str_replace('_', ' ', $field) . " wajib diisi";
            }
        }

        if (!empty($errors)) {
            $_SESSION['error_message'] = implode('<br>', $errors);
            header('Location: index.php?controller=permohonanadmin&action=edit&id=' . $id);
            exit();
        }

        // Handle file uploads
        $uploaded_files = $this->handleFileUploads($permohonan);

        // Prepare permohonan data only (user/biodata handled separately)
        $permohonanData = [
            'sisa_jatuh_tempo' => $permohonan['sisa_jatuh_tempo'], // Keep existing value, don't change on edit
            'tujuan_permohonan' => $_POST['tujuan_permohonan'] ?? $permohonan['tujuan_permohonan'],
            'komponen_tujuan' => $_POST['komponen_tujuan'],
            'judul_dokumen' => $_POST['judul_dokumen'],
            'kandungan_informasi' => $_POST['kandungan_informasi'],
            'tujuan_penggunaan_informasi' => $_POST['tujuan_penggunaan_informasi'],
            'upload_foto_identitas' => $uploaded_files['upload_foto_identitas'] ?? $permohonan['upload_foto_identitas'],
            'upload_data_pedukung' => $uploaded_files['upload_data_pedukung'] ?? $permohonan['upload_data_pedukung'],
            'status' => $permohonan['status'],  // Status tidak berubah karena input form dihapus
            'sumber_media' => $_POST['sumber_media'] ?? 'Website',
            'catatan_petugas' => $_POST['catatan_petugas'] ?? $permohonan['catatan_petugas']
        ];

        $result = $this->permohonanAdminModel->updatePermohonan($id, $permohonanData);

        if ($result['success']) {
            $_SESSION['success_message'] = $result['message'];
            header('Location: index.php?controller=permohonanadmin&action=view&id=' . $id);
        } else {
            $_SESSION['error_message'] = $result['message'];
            header('Location: index.php?controller=permohonanadmin&action=edit&id=' . $id);
        }
        exit();
    }

    // Delete permohonan
    public function delete()
    {
        if (!isset($_GET['id'])) {
            $_SESSION['error_message'] = 'ID permohonan tidak ditemukan';
            header('Location: index.php?controller=permohonanadmin&action=index');
            exit();
        }

        $id = $_GET['id'];
        $result = $this->permohonanAdminModel->deleteComprehensivePermohonan($id);

        if ($result['success']) {
            $_SESSION['success_message'] = $result['message'];
        } else {
            $_SESSION['error_message'] = $result['message'];
        }

        header('Location: index.php?controller=permohonanadmin&action=index');
        exit();
    }

    // Update status only
    public function updateStatus()
    {
        // Prevent any output before JSON
        while (ob_get_level()) {
            ob_end_clean();
        }

        // Start output buffering to catch any unexpected output
        ob_start();

        // Set headers
        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-cache, must-revalidate');

        try {
            // Validate request method
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                $this->sendJsonResponse(['success' => false, 'message' => 'Method tidak valid']);
                return;
            }

            // Validate parameters
            if (!isset($_POST['id']) || !isset($_POST['status'])) {
                $this->sendJsonResponse(['success' => false, 'message' => 'Parameter ID atau status tidak ditemukan']);
                return;
            }

            $id = intval($_POST['id']);
            $status = trim($_POST['status']);

            if (empty($status)) {
                $this->sendJsonResponse(['success' => false, 'message' => 'Status tidak boleh kosong']);
                return;
            }

            // Check if permohonan exists
            $permohonan = $this->permohonanAdminModel->getPermohonanById($id);
            if (!$permohonan) {
                $this->sendJsonResponse(['success' => false, 'message' => 'Permohonan tidak ditemukan']);
                return;
            }

            // Handle special status updates
            if ($status === 'Disposisi') {
                // Validate disposisi-specific fields
                if (!isset($_POST['tujuan_permohonan']) || !isset($_POST['komponen_tujuan'])) {
                    $this->sendJsonResponse(['success' => false, 'message' => 'Tujuan permohonan dan komponen tujuan harus diisi']);
                    return;
                }

                $updateData = [
                    'id_permohonan' => $id,
                    'status' => $status,
                    'tujuan_permohonan' => trim($_POST['tujuan_permohonan']),
                    'komponen_tujuan' => trim($_POST['komponen_tujuan']),
                    'catatan_petugas' => isset($_POST['catatan_petugas']) ? trim($_POST['catatan_petugas']) : ''
                ];

                $result = $this->permohonanAdminModel->updatePermohonanWithDisposisi($updateData);
            } elseif ($status === 'Ditolak') {
                // Validate penolakan-specific fields
                if (!isset($_POST['alasan_penolakan']) || empty(trim($_POST['alasan_penolakan']))) {
                    $this->sendJsonResponse(['success' => false, 'message' => 'Alasan penolakan harus diisi']);
                    return;
                }

                if (!isset($_POST['catatan_petugas']) || empty(trim($_POST['catatan_petugas']))) {
                    $this->sendJsonResponse(['success' => false, 'message' => 'Catatan petugas harus diisi']);
                    return;
                }

                $updateData = [
                    'id_permohonan' => $id,
                    'status' => $status,
                    'alasan_penolakan' => trim($_POST['alasan_penolakan']),
                    'catatan_petugas' => trim($_POST['catatan_petugas'])
                ];

                $result = $this->permohonanAdminModel->updatePermohonanWithPenolakan($updateData);
            } else {
                // Regular status update
                $result = $this->permohonanAdminModel->updatePermohonanStatus($id, $status);
            }

            if ($result) {
                $this->sendJsonResponse([
                    'success' => true,
                    'message' => 'Status berhasil diupdate',
                    'old_status' => $permohonan['status'],
                    'new_status' => $status
                ]);
            } else {
                $this->sendJsonResponse(['success' => false, 'message' => 'Gagal mengupdate status']);
            }
        } catch (Exception $e) {
            $this->sendJsonResponse([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    // Helper method to send clean JSON response
    private function sendJsonResponse($data)
    {
        // Clean any captured output
        $output = ob_get_clean();

        // If there was unexpected output, log it for debugging
        if (!empty(trim($output))) {
            error_log("Unexpected output before JSON: " . $output);
        }

        // Send JSON response
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Perpanjang jatuh tempo by 7 working days
    public function perpanjangJatuhTempo()
    {
        // Prevent any output before JSON
        while (ob_get_level()) {
            ob_end_clean();
        }

        // Start output buffering to catch any unexpected output
        ob_start();

        // Set headers
        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-cache, must-revalidate');

        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id'])) {
                $this->sendJsonResponse(['success' => false, 'message' => 'Parameter tidak valid']);
                return;
            }

            $id = intval($_POST['id']);
            $permohonan = $this->permohonanAdminModel->getPermohonanById($id);

            if (!$permohonan) {
                $this->sendJsonResponse(['success' => false, 'message' => 'Permohonan tidak ditemukan']);
                return;
            }

            // Calculate additional 7 working days from current sisa_jatuh_tempo
            $currentSisaJatuhTempo = $permohonan['sisa_jatuh_tempo'] ?? 0;
            $additionalWorkingDays = $this->calculateWorkingDays(7);

            // Total new sisa_jatuh_tempo is current + additional working days
            $newSisaJatuhTempo = $currentSisaJatuhTempo + $additionalWorkingDays;

            $result = $this->permohonanAdminModel->updateSisaJatuhTempo($id, $newSisaJatuhTempo);

            if ($result['success']) {
                $this->sendJsonResponse(['success' => true, 'message' => 'Jatuh tempo berhasil diperpanjang']);
            } else {
                $this->sendJsonResponse(['success' => false, 'message' => $result['message']]);
            }
        } catch (Exception $e) {
            $this->sendJsonResponse([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    // Handle file uploads
    private function handleFileUploads($existingData = null)
    {
        $uploadedFiles = [];
        $uploadDir = 'uploads/';

        // Create upload directory if not exists
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileFields = [
            'foto_profile' => ['jpg', 'jpeg', 'png'],
            'upload_ktp' => ['jpg', 'jpeg', 'png', 'pdf'],
            'upload_akta' => ['jpg', 'jpeg', 'png', 'pdf'],
            'upload_foto_identitas' => ['jpg', 'jpeg', 'png', 'pdf'],
            'upload_data_pedukung' => ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx']
        ];

        foreach ($fileFields as $fieldName => $allowedExt) {
            if (isset($_FILES[$fieldName]) && $_FILES[$fieldName]['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES[$fieldName];
                $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

                if (in_array($fileExt, $allowedExt)) {
                    $nik = $_POST['nik'] ?? 'unknown';
                    $timestamp = time();
                    $newFileName = $nik . '_' . $fieldName . '_' . $timestamp . '.' . $fileExt;
                    $targetPath = $uploadDir . $newFileName;

                    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                        // Delete old file if updating and exists
                        if ($existingData && !empty($existingData[$fieldName]) && file_exists($existingData[$fieldName])) {
                            unlink($existingData[$fieldName]);
                        }
                        $uploadedFiles[$fieldName] = $targetPath;
                    }
                }
            }
        }

        return $uploadedFiles;
    }

    // Download file
    public function downloadFile()
    {
        if (!isset($_GET['file']) || !isset($_GET['id'])) {
            header('HTTP/1.0 404 Not Found');
            exit();
        }

        $permohonan_id = $_GET['id'];
        $filename = $_GET['file'];

        $permohonan = $this->permohonanAdminModel->getPermohonanById($permohonan_id);

        if (!$permohonan) {
            header('HTTP/1.0 404 Not Found');
            exit();
        }

        $filepath = 'uploads/' . $filename;

        if (!file_exists($filepath) || strpos($filepath, 'uploads/') !== 0) {
            header('HTTP/1.0 404 Not Found');
            exit();
        }

        $file_extension = pathinfo($filepath, PATHINFO_EXTENSION);
        $content_types = [
            'pdf' => 'application/pdf',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        ];

        $content_type = $content_types[$file_extension] ?? 'application/octet-stream';

        header('Content-Type: ' . $content_type);
        header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
        header('Content-Length: ' . filesize($filepath));
        header('Cache-Control: must-revalidate');
        header('Pragma: public');

        ob_clean();
        flush();
        readfile($filepath);
        exit();
    }

    // Get provinces via API
    public function getProvinces()
    {
        header('Content-Type: application/json');

        $url = 'https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json';
        $response = file_get_contents($url);

        if ($response === false) {
            echo json_encode(['error' => 'Failed to fetch provinces']);
            exit();
        }

        echo $response;
        exit();
    }

    // Get cities by province ID via API
    public function getCities()
    {
        header('Content-Type: application/json');

        if (!isset($_GET['province_id'])) {
            echo json_encode(['error' => 'Province ID is required']);
            exit();
        }

        $province_id = $_GET['province_id'];
        $url = "https://www.emsifa.com/api-wilayah-indonesia/api/regencies/$province_id.json";
        $response = file_get_contents($url);

        if ($response === false) {
            echo json_encode(['error' => 'Failed to fetch cities']);
            exit();
        }

        echo $response;
        exit();
    }

    // Generate PDF for bukti permohonan
    public function generatePDF()
    {
        if (!isset($_GET['id'])) {
            $_SESSION['error_message'] = 'ID permohonan tidak ditemukan';
            header('Location: index.php?controller=permohonanadmin&action=index');
            exit();
        }

        $id = $_GET['id'];
        $permohonan = $this->permohonanAdminModel->getPermohonanById($id);

        if (!$permohonan) {
            $_SESSION['error_message'] = 'Permohonan tidak ditemukan';
            header('Location: index.php?controller=permohonanadmin&action=index');
            exit();
        }

        // Get SKPD data based on komponen_tujuan
        $skpd_data = $this->permohonanAdminModel->getSKPDDataByName($permohonan['komponen_tujuan']);

        $this->generateTCPDF($permohonan, $skpd_data);
    }

    // Generate PDF for bukti proses
    public function generateBuktiProsesPDF()
    {
        if (!isset($_GET['id'])) {
            $_SESSION['error_message'] = 'ID permohonan tidak ditemukan';
            header('Location: index.php?controller=permohonanadmin&action=diprosesIndex');
            exit();
        }

        $id = $_GET['id'];
        $permohonan = $this->permohonanAdminModel->getPermohonanById($id);

        if (!$permohonan) {
            $_SESSION['error_message'] = 'Permohonan tidak ditemukan';
            header('Location: index.php?controller=permohonanadmin&action=diprosesIndex');
            exit();
        }

        // Get SKPD data based on komponen_tujuan
        $skpd_data = $this->permohonanAdminModel->getSKPDDataByName($permohonan['komponen_tujuan']);

        $this->generateBuktiProsesTCPDF($permohonan, $skpd_data);
    }

    private function generateTCPDF($data, $skpd_data = null)
    {
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // Document settings
        $pdf->SetCreator('PPID Mandailing Natal');
        $pdf->SetAuthor('PPID Mandailing Natal');
        $pdf->SetTitle('Bukti Permohonan Informasi');
        $pdf->SetSubject('Bukti Permohonan Informasi');

        // Remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // Set margins
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetAutoPageBreak(true, 15);

        $pdf->AddPage();
        // Using Times New Roman font with size 12 and 1.15 line spacing
        $pdf->SetFont('times', '', 12);
        $pdf->SetCellHeightRatio(1.15); // Set line spacing to 1.15

        // Header with logo and SKPD information
        $this->addHeader($pdf, $data, $skpd_data);

        // Title section
        $this->addTitle($pdf, $data);

        // Data section
        $this->addDataSection($pdf, $data);

        // Signature section
        $this->addSignatureSection($pdf, $data);

        // Footer notes
        $this->addFooterNotes($pdf);

        // Output PDF
        $filename = 'Bukti_Permohonan_' . ($data['no_permohonan'] ?? $data['id_permohonan']) . '.pdf';
        $pdf->Output($filename, 'I');
        exit();
    }

    private function addHeader($pdf, $data, $skpd_data = null)
    {
        $pdf->SetFont('times', 'B', 16);
        $pdf->SetTextColor(0, 0, 0);

        $logo_path = __DIR__ . '/../ppid_assets/logo-resmi.png';
        $start_x = 15;
        $start_y = 15;
        $logo_width = 25;
        $logo_height = 25;
        $text_start_x = $start_x + $logo_width + 5;
        $text_y = $start_y;

        if (file_exists($logo_path)) {
            $pdf->Image($logo_path, $start_x, $start_y, $logo_width, $logo_height, 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
            $pdf->SetXY($text_start_x, $text_y);
        } else {
            $pdf->SetXY($start_x, $start_y);
        }

        $skpd_name = !empty($data['komponen_tujuan']) ? strtoupper($data['komponen_tujuan']) : '';
        $pdf->Cell(0, 7, $skpd_name, 0, 1, 'L');

        $pdf->SetFont('times', '', 11);

        $email = ($skpd_data && !empty($skpd_data['email'])) ? $skpd_data['email'] : '';
        $telp = ($skpd_data && !empty($skpd_data['telepon'])) ? $skpd_data['telepon'] : '';

        $pdf->SetX($text_start_x);
        $pdf->Cell(0, 5, 'Email: ' . $email, 0, 1, 'L');

        $pdf->SetX($text_start_x);
        $pdf->Cell(0, 5, 'Telp: ' . $telp, 0, 1, 'L');

        $pdf->Ln(12);

        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetLineWidth(0.1);
        $pdf->Line(11, $pdf->GetY(), $pdf->getPageWidth() - 11, $pdf->GetY());
        $pdf->SetLineWidth(0.1);
    }

    private function addTitle($pdf, $data)
    {
        $pdf->Ln(3);
        $default_height = 5;
        $line_spacing_factor = 1.0;
        $next_line_y_jump = $default_height * $line_spacing_factor;

        $pdf->SetFont('times', 'B', 12);

        $pdf->Cell(0, $default_height, 'BUKTI PERMOHONAN INFORMASI', 0, 0, 'C');

        $pdf->SetY($pdf->GetY() + $next_line_y_jump);

        $pdf->SetFont('times', 12);

        $pdf->Cell(0, $default_height, 'Nomor Permohonan : ' . ($data['no_permohonan'] ?? $data['id_permohonan']), 0, 1, 'C');
    }

    private function addDataSection($pdf, $data)
    {
        $pdf->Ln(5);
        $pdf->SetFont('times', '', 12);
        $items = [
            ['Nama Pemohon', $data['nama_lengkap'] ?? $data['username']],
            ['Alamat', $data['alamat'] ?? ''],
            ['Telepon', $data['no_kontak'] ?? ''],
            ['Email', $data['email'] ?? ''],
            ['Informasi Dimohon', $data['judul_dokumen'] ?? ''],
            ['Provinsi Tujuan', $data['provinsi'] ?? ''],
            ['Kab/Kota Tujuan', $data['city'] ?? ''],
            ['OPD Tujuan', $data['komponen_tujuan'] ?? '']
        ];

        foreach ($items as $item) {
            $pdf->Cell(50, 6, $item[0], 0, 0, 'L');
            $pdf->Cell(5, 6, ':', 0, 0, 'L');
            $pdf->Cell(0, 6, $item[1], 0, 1, 'L');
        }

        // Align all information fields in one line without extra line breaks
        $kandungan_info = $data['kandungan_informasi'] ?? $data['tujuan_permohonan'] ?? '';
        $tujuan_penggunaan = $data['tujuan_penggunaan_informasi'] ?? '';

        // Baris 1: Kandungan Informasi
        $pdf->Cell(50, 6, 'Kandungan Informasi', 0, 0, 'L');
        $pdf->Cell(5, 6, ':', 0, 0, 'L');
        $pdf->SetFillColor(211, 211, 211);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(0, 6, $kandungan_info, 1, 1, 'L', true);

        $pdf->Ln(1);

        // Baris 2: Tujuan Penggunaan
        $pdf->Cell(50, 6, 'Tujuan Penggunaan', 0, 0, 'L');
        $pdf->Cell(5, 6, ':', 0, 0, 'L');
        $pdf->SetFillColor(211, 211, 211);
        $pdf->Cell(0, 6, $tujuan_penggunaan, 1, 1, 'L', true);

        $pdf->SetFillColor(255, 255, 255);

        $pdf->SetFont('times', '', 10);
        $lebar_indentasi = 55;

        $pdf->Cell(50, 6, 'Cara Memperoleh Informasi', 0, 0, 'L');
        $pdf->Cell(5, 6, ':', 0, 0, 'L');

        $pdf->SetFont('dejavusans', '', 12);
        $pdf->Cell(10, 6, '☐', 0, 0, 'L');

        $pdf->SetFont('times', '', 12);
        $pdf->Cell(0, 6, 'Melihat/Membaca/Mendengarkan/Mencatat', 0, 1, 'L');

        $pdf->Cell($lebar_indentasi, 6, '', 0, 0, 'L');

        $pdf->SetFont('dejavusans', '', 12);
        $pdf->Cell(10, 6, '☑', 0, 0, 'L');

        $pdf->SetFont('times', '', 12);
        $pdf->Cell(0, 6, 'Mendapatkan Salinan Informasi (Hard Copy / Soft Copy)', 0, 1, 'L');

        $pdf->Ln(10);

        $pdf->SetFillColor(255, 255, 255);
    }

    private function addSignatureSection($pdf, $data)
    {
        $y = $pdf->GetY();
        $vertical_space = 20;
        $line_gap = 10;

        $pdf->SetXY(20, $y);
        $pdf->Cell(80, 6, 'Petugas Pelayanan Informasi', 0, 1, 'C');
        $pdf->SetY($y + 6 + $vertical_space);
        $pdf->SetX(20);
        $pdf->Cell(80, 6, 'Pemerintah Kabupaten Mandailing Natal', 0, 1, 'C');

        $pdf->SetXY(120, $y);
        $pdf->Cell(80, 6, 'Pemohon', 0, 1, 'C');
        $pdf->SetY($y + 6 + $vertical_space);
        $pdf->SetX(120);
        $pdf->Cell(80, 6, strtoupper($data['nama_lengkap'] ?? $data['username']), 0, 1, 'C');

        $pdf->SetY($y + 6 + $vertical_space + 6 + $line_gap);

        $pdf->SetLineWidth(0.1);

        $page_width = $pdf->GetPageWidth();
        $line_y = $pdf->GetY();
        $pdf->Line(10, $line_y, $page_width - 10, $line_y);

        $pdf->SetLineWidth(0.2);
        $pdf->Ln(10);
    }

    private function addFooterNotes($pdf)
    {
        $pdf->SetFont('times', '', 10);
        $pdf->Cell(0, 5, 'Berdasarkan Undang-Undang No 14 Tahun 2008 Tentang Keterbukaan Informasi Publik, maka :', 0, 1, 'L');

        $notes = [
            'Bukti Permohonan Ini merupakan hak pemohon yang wajib diterbitkan oleh Badan Publik. (Pasal 22 Ayat 3 dan 4)',
            'Pemohon dapat menerima pemberitahuan atas permohonannya dalam waktu 10 (sepuluh) hari. (Pasal 22 Ayat 7)',
            'Bukti Permohonan ini merupakan bukti sah atas permohonan informasi yang diajukan ke daerah tujuan.',
            'Badan Publik dapat memperpanjang waktu pemberitahuan / jawaban permohonan hingga 7 (tujuh) hari. (Pasal 22 Ayat 8)',
            'Informasi Publik yang dapat diberikan diatur dalam Pasal 9 s.d 16',
            'Dalam hal terjadi sengketa, Pemohon dapat mengajukan gugatan ke pengadilan apabila dalam mendapatkan Informasi Publik mendapatkan hambatan / kegagalan. (Pasal 4 Ayat 4)'
        ];

        foreach ($notes as $note) {
            $pdf->Cell(5, 4, '', 0, 0);
            $pdf->Cell(5, 4, '•', 0, 0, 'L');
            $pdf->MultiCell(0, 4, $note, 0, 'L');
        }

        $pdf->Ln(5);

        $hari = array(1 => 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu');
        $bulan = array(1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');

        $tgl = date('d');
        $bln = date('n');
        $thn = date('Y');
        $hr = date('N');

        $tanggal_indonesia = $hari[$hr] . ', ' . $tgl . ' ' . $bulan[$bln] . ' ' . $thn;

        $pdf->SetFont('times', 'I', 10);
        $pdf->Cell(0, 4, 'Lembaran ini diterbitkan oleh PPID Kemendagri dan dicetak pada ' . $tanggal_indonesia, 0, 1, 'R');
    }

    // BUKTI PROSES PDF GENERATION
    private function generateBuktiProsesTCPDF($data, $skpd_data = null)
    {
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // Document settings
        $pdf->SetCreator('PPID Mandailing Natal');
        $pdf->SetAuthor('PPID Mandailing Natal');
        $pdf->SetTitle('Bukti Proses Permohonan Informasi');
        $pdf->SetSubject('Bukti Proses Permohonan Informasi');

        // Remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // Set margins
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetAutoPageBreak(true, 15);

        $pdf->AddPage();
        // Using Times New Roman font with size 12 and 1.15 line spacing
        $pdf->SetFont('times', '', 12);
        $pdf->SetCellHeightRatio(1.15);

        // Header with logo and SKPD information
        $this->addBuktiProsesHeader($pdf, $data, $skpd_data);

        // Title section
        $this->addBuktiProsesTitle($pdf, $data);

        // Data section
        $this->addBuktiProsesDataSection($pdf, $data);

        // Signature section
        $this->addBuktiProsesSignature($pdf, $data);

        // Footer notes
        $this->addBuktiProsesFooter($pdf);

        // Output PDF
        $filename = 'Bukti_Proses_' . ($data['no_permohonan'] ?? $data['id_permohonan']) . '.pdf';
        $pdf->Output($filename, 'I');
        exit();
    }

    private function addBuktiProsesHeader($pdf, $data, $skpd_data = null)
    {
        $pdf->SetFont('times', 'B', 16);
        $pdf->SetTextColor(0, 0, 0);

        $logo_path = __DIR__ . '/../ppid_assets/logo-resmi.png';
        $start_x = 15;
        $start_y = 15;
        $logo_width = 25;
        $logo_height = 25;
        $text_start_x = $start_x + $logo_width + 5;
        $text_y = $start_y;

        if (file_exists($logo_path)) {
            $pdf->Image($logo_path, $start_x, $start_y, $logo_width, $logo_height, 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
            $pdf->SetXY($text_start_x, $text_y);
        } else {
            $pdf->SetXY($start_x, $start_y);
        }

        $skpd_name = !empty($data['komponen_tujuan']) ? strtoupper($data['komponen_tujuan']) : 'DINAS KOMUNIKASI DAN INFORMATIKA';
        $pdf->Cell(0, 7, $skpd_name, 0, 1, 'L');

        $pdf->SetFont('times', '', 11);

        // Get dynamic address from SKPD data
        $alamat = ($skpd_data && !empty($skpd_data['alamat']))
            ? $skpd_data['alamat']
            : 'Perkantoran Panyabungan, Panyabungan, Kabupaten Mandailing Natal, Provinsi Sumatera Utara';
        $email = ($skpd_data && !empty($skpd_data['email'])) ? $skpd_data['email'] : 'diskominfo@gmail.madina.go.id';
        $telp = ($skpd_data && !empty($skpd_data['telepon'])) ? $skpd_data['telepon'] : '0821-xxxx-xxxx';

        $pdf->SetX($text_start_x);
        $pdf->Cell(0, 5, 'Alamat: ' . $alamat, 0, 1, 'L');

        $pdf->SetX($text_start_x);
        $pdf->Cell(0, 5, 'Email: ' . $email, 0, 1, 'L');

        $pdf->SetX($text_start_x);
        $pdf->Cell(0, 5, 'Telp: ' . $telp, 0, 1, 'L');

        $pdf->Ln(12);

        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetLineWidth(0.1);
        $pdf->Line(11, $pdf->GetY(), $pdf->getPageWidth() - 11, $pdf->GetY());
        $pdf->SetLineWidth(0.1);
    }

    private function addBuktiProsesTitle($pdf, $data)
    {
        $pdf->Ln(3);
        $default_height = 5;
        $line_spacing_factor = 1.0;
        $next_line_y_jump = $default_height * $line_spacing_factor;

        $pdf->SetFont('times', 'B', 12);

        $pdf->Cell(0, $default_height, 'BUKTI PERMOHONAN INFORMASI', 0, 0, 'C');

        $pdf->SetY($pdf->GetY() + $next_line_y_jump);

        $pdf->SetFont('times', '', 12);

        $pdf->Cell(0, $default_height, 'Nomor Permohonan : ' . ($data['no_permohonan'] ?? $data['id_permohonan']), 0, 1, 'C');
    }

    private function addBuktiProsesDataSection($pdf, $data)
    {
        $pdf->Ln(5);
        $pdf->SetFont('times', '', 12);

        $items = [
            ['Nama Pemohon', $data['nama_lengkap'] ?? $data['username']],
            ['Alamat', $data['alamat'] ?? ''],
            ['Telepon', $data['no_kontak'] ?? ''],
            ['Email', $data['email'] ?? ''],
            ['Informasi Dimohon', $data['judul_dokumen'] ?? ''],
            ['Provinsi Tujuan', $data['provinsi'] ?? 'Kabupaten Mandailing Natal'],
            ['Kab/Kota Tujuan', $data['city'] ?? 'Panyabungan'],
            ['OPD Tujuan', $data['komponen_tujuan'] ?? 'DINAS KOMUNIKASI DAN INFORMATIKA']
        ];

        foreach ($items as $item) {
            $pdf->Cell(50, 6, $item[0], 0, 0, 'L');
            $pdf->Cell(5, 6, ':', 0, 0, 'L');
            $pdf->Cell(0, 6, $item[1], 0, 1, 'L');
        }

        // Kandungan Informasi dengan background
        $kandungan_info = $data['kandungan_informasi'] ?? $data['tujuan_permohonan'] ?? 'permintaan informasi perbup tentang spbe';
        $pdf->Cell(50, 6, 'Kandungan Informasi', 0, 0, 'L');
        $pdf->Cell(5, 6, ':', 0, 0, 'L');
        $pdf->SetFillColor(211, 211, 211);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(0, 6, $kandungan_info, 1, 1, 'L', true);

        $pdf->Ln(1);

        // Tujuan Penggunaan dengan background
        $tujuan_penggunaan = $data['tujuan_penggunaan_informasi'] ?? 'permintaan informasi perbup tentang spbe';
        $pdf->Cell(50, 6, 'Tujuan Penggunaan', 0, 0, 'L');
        $pdf->Cell(5, 6, ':', 0, 0, 'L');
        $pdf->SetFillColor(211, 211, 211);
        $pdf->Cell(0, 6, $tujuan_penggunaan, 1, 1, 'L', true);

        $pdf->SetFillColor(255, 255, 255);

        $pdf->Ln(2);

        // Keputusan PPID section
        $pdf->SetFont('times', '', 12);
        $pdf->Cell(0, 6, 'Keputusan PPID', 0, 1, 'C');

        $pdf->SetFont('times', 'B', 12);
        $pdf->Cell(0, 6, 'PERMOHONAN DIPENUHI', 0, 1, 'C');

        $pdf->SetFont('times', '', 12);
        $pdf->Ln(2);

        // Cara Memperoleh Informasi
        $lebar_indentasi = 55;
        $pdf->Cell(50, 6, 'Cara Memperoleh Informasi', 0, 0, 'L');
        $pdf->Cell(5, 6, ':', 0, 0, 'L');

        $pdf->SetFont('dejavusans', '', 12);
        $pdf->Cell(10, 6, '☐', 0, 0, 'L');

        $pdf->SetFont('times', '', 12);
        $pdf->Cell(0, 6, 'Melihat/Membaca/Mendengarkan/Mencatat', 0, 1, 'L');

        $pdf->Cell($lebar_indentasi, 6, '', 0, 0, 'L');

        $pdf->SetFont('dejavusans', '', 12);
        $pdf->Cell(10, 6, '☑', 0, 0, 'L');

        $pdf->SetFont('times', '', 12);
        $pdf->Cell(0, 6, 'Mendapatkan Salinan Informasi (Hard Copy / Soft Copy)', 0, 1, 'L');

        $pdf->Ln(2);

        // Catatan Petugas dengan background seperti kandungan informasi
        $catatan_petugas = !empty($data['catatan_petugas']) ? $data['catatan_petugas'] : '-';
        $pdf->SetFont('times', '', 12);
        $pdf->Cell(50, 6, 'Catatan Petugas', 0, 0, 'L');
        $pdf->Cell(5, 6, ':', 0, 0, 'L');
        $pdf->SetFillColor(211, 211, 211);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(0, 6, $catatan_petugas, 1, 1, 'L', true);

        $pdf->SetFillColor(255, 255, 255);
        $pdf->Ln(2);

        $pdf->Ln(10);
    }

    private function addBuktiProsesSignature($pdf, $data)
    {
        $y = $pdf->GetY();
        $vertical_space = 20;
        $line_gap = 10;

        // Petugas Pelayanan Informasi
        $pdf->SetXY(20, $y);
        $pdf->Cell(80, 6, 'Petugas Pelayanan Informasi', 0, 1, 'C');
        $pdf->SetY($y + 6 + $vertical_space);
        $pdf->SetX(20);
        $pdf->Cell(80, 6, strtoupper($data['komponen_tujuan'] ?? 'DINAS KOMUNIKASI DAN INFORMATIKA'), 0, 1, 'C');

        // Pemohon
        $pdf->SetXY(120, $y);
        $pdf->Cell(80, 6, 'Pemohon', 0, 1, 'C');
        $pdf->SetY($y + 6 + $vertical_space);
        $pdf->SetX(120);
        $pdf->Cell(80, 6, strtoupper($data['nama_lengkap'] ?? $data['username']), 0, 1, 'C');

        $pdf->SetY($y + 6 + $vertical_space + 6 + $line_gap);

        $pdf->SetLineWidth(0.1);

        $page_width = $pdf->GetPageWidth();
        $line_y = $pdf->GetY();
        $pdf->Line(10, $line_y, $page_width - 10, $line_y);

        $pdf->SetLineWidth(0.2);
        $pdf->Ln(10);
    }

    private function addBuktiProsesFooter($pdf)
    {
        $pdf->SetFont('times', '', 10);
        $pdf->Cell(0, 5, 'Berdasarkan Undang-Undang No 14 Tahun 2008 Tentang Keterbukaan Informasi Publik, maka :', 0, 1, 'L');

        $notes = [
            'Bukti Permohonan Ini merupakan hak pemohon yang wajib diterbitkan oleh Badan Publik. (Pasal 22 Ayat 3 dan 4)',
            'Pemohon dapat menerima pemberitahuan atas permohonannya dalam waktu 10 (sepuluh) hari. (Pasal 22 Ayat 7)',
            'Bukti Permohonan ini merupakan bukti sah atas permohonan informasi yang diajukan ke daerah tujuan.',
            'Badan Publik dapat memperpanjang waktu pemberitahuan / jawaban permohonan hingga 7 (tujuh) hari. (Pasal 22 Ayat 8)',
            'Informasi Publik yang dapat diberikan diatur dalam Pasal 9 s.d 16',
            'Dalam hal terjadi sengketa, Pemohon dapat mengajukan gugatan ke pengadilan apabila dalam mendapatkan Informasi Publik mendapatkan hambatan / kegagalan. (Pasal 4 Ayat 4)'
        ];

        foreach ($notes as $note) {
            $pdf->Cell(5, 4, '', 0, 0);
            $pdf->Cell(5, 4, '•', 0, 0, 'L');
            $pdf->MultiCell(0, 4, $note, 0, 'L');
        }

        $pdf->Ln(5);

        $hari = array(1 => 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu');
        $bulan = array(1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');

        $tgl = date('d');
        $bln = date('n');
        $thn = date('Y');
        $hr = date('N');

        $tanggal_indonesia = $hari[$hr] . ', ' . $tgl . ' ' . $bulan[$bln] . ' ' . $thn;

        $pdf->SetFont('times', 'I', 10);
        $pdf->Cell(0, 4, 'Lembaran ini diterbitkan oleh PPID Kemendagri dan dicetak pada ' . $tanggal_indonesia, 0, 1, 'R');
    }

    // BUKTI SELESAI PDF GENERATION
    public function generateBuktiSelesaiPDF()
    {
        if (!isset($_GET['id'])) {
            $_SESSION['error_message'] = 'ID permohonan tidak ditemukan';
            header('Location: index.php?controller=permohonanadmin&action=selesaiIndex');
            exit();
        }

        $id = $_GET['id'];
        $permohonan = $this->permohonanAdminModel->getPermohonanById($id);

        if (!$permohonan) {
            $_SESSION['error_message'] = 'Permohonan tidak ditemukan';
            header('Location: index.php?controller=permohonanadmin&action=selesaiIndex');
            exit();
        }

        // Get SKPD data based on komponen_tujuan
        $skpd_data = $this->permohonanAdminModel->getSKPDDataByName($permohonan['komponen_tujuan']);

        $this->generateBuktiSelesaiTCPDF($permohonan, $skpd_data);
    }

    private function generateBuktiSelesaiTCPDF($data, $skpd_data = null)
    {
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // Document settings
        $pdf->SetCreator('PPID Mandailing Natal');
        $pdf->SetAuthor('PPID Mandailing Natal');
        $pdf->SetTitle('Bukti Selesai Permohonan Informasi');
        $pdf->SetSubject('Bukti Selesai Permohonan Informasi');

        // Remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // Set margins
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetAutoPageBreak(true, 15);

        $pdf->AddPage();
        $pdf->SetFont('times', '', 12);
        $pdf->SetCellHeightRatio(1.15);

        // Use same header as bukti proses
        $this->addBuktiProsesHeader($pdf, $data, $skpd_data);

        // Title section
        $this->addBuktiProsesTitle($pdf, $data);

        // Data section with PERMOHONAN SELESAI
        $this->addBuktiSelesaiDataSection($pdf, $data);

        // Signature section
        $this->addBuktiProsesSignature($pdf, $data);

        // Footer notes
        $this->addBuktiProsesFooter($pdf);

        // Output PDF
        $filename = 'Bukti_Selesai_' . ($data['no_permohonan'] ?? $data['id_permohonan']) . '.pdf';
        $pdf->Output($filename, 'I');
        exit();
    }

    private function addBuktiSelesaiDataSection($pdf, $data)
    {
        $pdf->Ln(5);
        $pdf->SetFont('times', '', 12);

        $items = [
            ['Nama Pemohon', $data['nama_lengkap'] ?? $data['username']],
            ['Alamat', $data['alamat'] ?? ''],
            ['Telepon', $data['no_kontak'] ?? ''],
            ['Email', $data['email'] ?? ''],
            ['Informasi Dimohon', $data['judul_dokumen'] ?? ''],
            ['Provinsi Tujuan', $data['provinsi'] ?? 'Kabupaten Mandailing Natal'],
            ['Kab/Kota Tujuan', $data['city'] ?? 'Panyabungan'],
            ['OPD Tujuan', $data['komponen_tujuan'] ?? 'DINAS KOMUNIKASI DAN INFORMATIKA']
        ];

        foreach ($items as $item) {
            $pdf->Cell(50, 6, $item[0], 0, 0, 'L');
            $pdf->Cell(5, 6, ':', 0, 0, 'L');
            $pdf->Cell(0, 6, $item[1], 0, 1, 'L');
        }

        // Kandungan Informasi dengan background
        $kandungan_info = $data['kandungan_informasi'] ?? $data['tujuan_permohonan'] ?? 'permintaan informasi perbup tentang spbe';
        $pdf->Cell(50, 6, 'Kandungan Informasi', 0, 0, 'L');
        $pdf->Cell(5, 6, ':', 0, 0, 'L');
        $pdf->SetFillColor(211, 211, 211);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(0, 6, $kandungan_info, 1, 1, 'L', true);

        $pdf->Ln(1);

        // Tujuan Penggunaan dengan background
        $tujuan_penggunaan = $data['tujuan_penggunaan_informasi'] ?? 'permintaan informasi perbup tentang spbe';
        $pdf->Cell(50, 6, 'Tujuan Penggunaan', 0, 0, 'L');
        $pdf->Cell(5, 6, ':', 0, 0, 'L');
        $pdf->SetFillColor(211, 211, 211);
        $pdf->Cell(0, 6, $tujuan_penggunaan, 1, 1, 'L', true);

        $pdf->SetFillColor(255, 255, 255);

        $pdf->Ln(2);

        // Keputusan PPID section - PERMOHONAN SELESAI
        $pdf->SetFont('times', '', 12);
        $pdf->Cell(0, 6, 'Keputusan PPID', 0, 1, 'C');

        $pdf->SetFont('times', 'B', 12);
        $pdf->Cell(0, 6, 'PERMOHONAN SELESAI', 0, 1, 'C');

        $pdf->SetFont('times', '', 12);
        $pdf->Ln(2);

        // Cara Memperoleh Informasi
        $lebar_indentasi = 55;
        $pdf->Cell(50, 6, 'Cara Memperoleh Informasi', 0, 0, 'L');
        $pdf->Cell(5, 6, ':', 0, 0, 'L');

        $pdf->SetFont('dejavusans', '', 12);
        $pdf->Cell(10, 6, '☐', 0, 0, 'L');

        $pdf->SetFont('times', '', 12);
        $pdf->Cell(0, 6, 'Melihat/Membaca/Mendengarkan/Mencatat', 0, 1, 'L');

        $pdf->Cell($lebar_indentasi, 6, '', 0, 0, 'L');

        $pdf->SetFont('dejavusans', '', 12);
        $pdf->Cell(10, 6, '☑', 0, 0, 'L');

        $pdf->SetFont('times', '', 12);
        $pdf->Cell(0, 6, 'Mendapatkan Salinan Informasi (Hard Copy / Soft Copy)', 0, 1, 'L');

        $pdf->Ln(2);

        // Catatan Petugas dengan background seperti kandungan informasi
        $catatan_petugas = !empty($data['catatan_petugas']) ? $data['catatan_petugas'] : '-';
        $pdf->SetFont('times', '', 12);
        $pdf->Cell(50, 6, 'Catatan Petugas', 0, 0, 'L');
        $pdf->Cell(5, 6, ':', 0, 0, 'L');
        $pdf->SetFillColor(211, 211, 211);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(0, 6, $catatan_petugas, 1, 1, 'L', true);

        $pdf->SetFillColor(255, 255, 255);
        $pdf->Ln(2);

        $pdf->Ln(10);
    }

    // DISPOSISI METHODS

    // Display disposisi index
    public function disposisiIndex()
    {
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $status = isset($_GET['status']) ? $_GET['status'] : 'Disposisi';
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';

        // Get permohonan with disposisi focus (only status: Disposisi)
        $disposisi_status = ['Disposisi'];
        $permohonan_list = $this->permohonanAdminModel->getDisposisiPermohonan($limit, $offset, $status, $search, $disposisi_status);
        $total_records = $this->permohonanAdminModel->countDisposisiPermohonan($status, $search, $disposisi_status);
        $total_pages = ceil($total_records / $limit);

        // Get disposisi specific stats
        $stats = $this->permohonanAdminModel->getDisposisiStats();

        $success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
        $error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';

        unset($_SESSION['success_message']);
        unset($_SESSION['error_message']);

        include 'views/permohonan_admin/disposisi/index.php';
    }

    // Display disposisi view
    public function disposisiView()
    {
        if (!isset($_GET['id'])) {
            $_SESSION['error_message'] = 'ID permohonan tidak ditemukan';
            header('Location: index.php?controller=permohonanadmin&action=disposisiIndex');
            exit();
        }

        $id = $_GET['id'];
        $permohonan = $this->permohonanAdminModel->getPermohonanById($id);

        if (!$permohonan) {
            $_SESSION['error_message'] = 'Permohonan tidak ditemukan';
            header('Location: index.php?controller=permohonanadmin&action=disposisiIndex');
            exit();
        }

        // Check if this is a disposisi-related permohonan
        $disposisi_status = ['Disposisi', 'Selesai', 'Ditolak'];
        if (!in_array($permohonan['status'], $disposisi_status)) {
            $_SESSION['error_message'] = 'Permohonan ini belum masuk tahap disposisi';
            header('Location: index.php?controller=permohonanadmin&action=disposisiIndex');
            exit();
        }

        include 'views/permohonan_admin/disposisi/view.php';
    }

    // AJAX endpoint untuk mendapatkan data SKPD untuk disposisi
    public function getSKPDData()
    {
        header('Content-Type: application/json');

        try {
            // Get unique categories
            $categories = $this->skpdModel->getUniqueCategories();

            // Get all SKPD data
            $skpd_list = $this->skpdModel->getSKPDForDisposisi();

            $response = [
                'success' => true,
                'data' => [
                    'categories' => $categories,
                    'skpd_list' => $skpd_list
                ]
            ];

            echo json_encode($response);
        } catch (Exception $e) {
            $response = [
                'success' => false,
                'message' => 'Gagal mengambil data SKPD: ' . $e->getMessage()
            ];
            echo json_encode($response);
        }
        exit();
    }

    // AJAX endpoint untuk mendapatkan SKPD berdasarkan kategori
    public function getSKPDByCategory()
    {
        header('Content-Type: application/json');

        if (!isset($_GET['category'])) {
            echo json_encode([
                'success' => false,
                'message' => 'Kategori tidak disediakan'
            ]);
            exit();
        }

        try {
            $category = $_GET['category'];
            $skpd_list = $this->skpdModel->getSKPDByCategory($category);

            $response = [
                'success' => true,
                'data' => $skpd_list
            ];

            echo json_encode($response);
        } catch (Exception $e) {
            $response = [
                'success' => false,
                'message' => 'Gagal mengambil data SKPD: ' . $e->getMessage()
            ];
            echo json_encode($response);
        }
        exit();
    }

    // SELESAI METHODS

    // Display selesai index
    public function selesaiIndex()
    {
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $search = isset($_GET['search']) ? trim($_GET['search']) : '';

        // Get permohonan with status "Selesai" ONLY
        $selesai_status = ['Selesai'];
        $permohonan_list = $this->permohonanAdminModel->getSelesaiPermohonan($limit, $offset, $search, $selesai_status);
        $total_records = $this->permohonanAdminModel->countSelesaiPermohonan($search, $selesai_status);
        $total_pages = ($total_records > 0) ? ceil($total_records / $limit) : 0;

        // Get selesai specific stats
        $stats = $this->permohonanAdminModel->getSelesaiStats();

        $success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
        $error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';

        unset($_SESSION['success_message']);
        unset($_SESSION['error_message']);

        // Debug: Log the query results
        error_log("Selesai Index - Total Records: " . $total_records);
        error_log("Selesai Index - Permohonan List Count: " . count($permohonan_list));

        include 'views/permohonan_admin/selesai/index.php';
    }

    // Display selesai detail
    public function selesaiDetail()
    {
        if (!isset($_GET['id'])) {
            $_SESSION['error_message'] = 'ID permohonan tidak ditemukan';
            header('Location: index.php?controller=permohonanadmin&action=selesaiIndex');
            exit();
        }

        $id = $_GET['id'];
        $permohonan = $this->permohonanAdminModel->getPermohonanById($id);

        if (!$permohonan) {
            $_SESSION['error_message'] = 'Permohonan tidak ditemukan';
            header('Location: index.php?controller=permohonanadmin&action=selesaiIndex');
            exit();
        }

        // Check if this is a selesai-related permohonan (status Selesai)
        if ($permohonan['status'] !== 'Selesai') {
            $_SESSION['error_message'] = 'Permohonan ini bukan dalam status selesai';
            header('Location: index.php?controller=permohonanadmin&action=selesaiIndex');
            exit();
        }

        include 'views/permohonan_admin/selesai/detail.php';
    }

    // DITOLAK METHODS

    // Display ditolak index
    public function ditolakIndex()
    {
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        // Hardcoded status to "Ditolak" only
        $ditolak_status = ['Ditolak'];
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';

        $permohonan_list = $this->permohonanAdminModel->getDitolakPermohonan($limit, $offset, $search, $ditolak_status);
        $total_records = $this->permohonanAdminModel->countDitolakPermohonan($search, $ditolak_status);
        $total_pages = ceil($total_records / $limit);

        $stats = $this->permohonanAdminModel->getDitolakStats();

        $success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
        $error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';

        unset($_SESSION['success_message']);
        unset($_SESSION['error_message']);

        include 'views/permohonan_admin/ditolak/index.php';
    }

    // Display ditolak detail
    public function ditolakDetail()
    {
        if (!isset($_GET['id'])) {
            $_SESSION['error_message'] = 'ID permohonan tidak ditemukan';
            header('Location: index.php?controller=permohonanadmin&action=ditolakIndex');
            exit();
        }

        $id = $_GET['id'];
        $permohonan = $this->permohonanAdminModel->getPermohonanById($id);

        if (!$permohonan) {
            $_SESSION['error_message'] = 'Permohonan tidak ditemukan';
            header('Location: index.php?controller=permohonanadmin&action=ditolakIndex');
            exit();
        }

        // Check if this is a ditolak-related permohonan (status Ditolak)
        if ($permohonan['status'] !== 'Ditolak') {
            $_SESSION['error_message'] = 'Permohonan ini bukan dalam status ditolak';
            header('Location: index.php?controller=permohonanadmin&action=ditolakIndex');
            exit();
        }

        include 'views/permohonan_admin/ditolak/detail.php';
    }

    // Generate Bukti Ditolak PDF
    public function generateBuktiDitolakPDF()
    {
        if (!isset($_GET['id'])) {
            $_SESSION['error_message'] = 'ID permohonan tidak ditemukan';
            header('Location: index.php?controller=permohonanadmin&action=ditolakIndex');
            exit();
        }

        $id = $_GET['id'];
        $permohonan = $this->permohonanAdminModel->getPermohonanById($id);

        if (!$permohonan) {
            $_SESSION['error_message'] = 'Permohonan tidak ditemukan';
            header('Location: index.php?controller=permohonanadmin&action=ditolakIndex');
            exit();
        }

        // Get SKPD data for dynamic address
        $skpd_data = null;
        if (!empty($permohonan['komponen_tujuan'])) {
            $skpd_data = $this->permohonanAdminModel->getSKPDDataByName($permohonan['komponen_tujuan']);
        }

        // Generate PDF
        $this->generateBuktiDitolakTCPDF($permohonan, $skpd_data);
    }

    // Generate Bukti Ditolak using TCPDF
    private function generateBuktiDitolakTCPDF($data, $skpd_data = null)
    {
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // Document settings
        $pdf->SetCreator('PPID Mandailing Natal');
        $pdf->SetAuthor('PPID Mandailing Natal');
        $pdf->SetTitle('Bukti Ditolak Permohonan Informasi');
        $pdf->SetSubject('Bukti Ditolak Permohonan Informasi');

        // Remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // Set margins
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetAutoPageBreak(true, 15);

        $pdf->AddPage();
        $pdf->SetFont('times', '', 12);
        $pdf->SetCellHeightRatio(1.15);

        // Use same header as bukti proses
        $this->addBuktiProsesHeader($pdf, $data, $skpd_data);

        // Title section
        $this->addBuktiProsesTitle($pdf, $data);

        // Data section with PERMOHONAN DITOLAK
        $this->addBuktiDitolakDataSection($pdf, $data);

        // Signature section
        $this->addBuktiProsesSignature($pdf, $data);

        // Footer notes
        $this->addBuktiProsesFooter($pdf);

        // Output PDF
        $filename = 'Bukti_Ditolak_' . ($data['no_permohonan'] ?? $data['id_permohonan']) . '.pdf';
        $pdf->Output($filename, 'I');
        exit();
    }

    // Add data section for Bukti Ditolak with PERMOHONAN DITOLAK
    private function addBuktiDitolakDataSection($pdf, $data)
    {
        $pdf->Ln(5);
        $pdf->SetFont('times', '', 12);

        $items = [
            ['Nama Pemohon', $data['nama_lengkap'] ?? $data['username']],
            ['Alamat', $data['alamat'] ?? ''],
            ['Pekerjaan', $data['pekerjaan'] ?? '-']
        ];

        foreach ($items as $item) {
            $pdf->Cell(50, 6, $item[0], 0, 0, 'L');
            $pdf->Cell(5, 6, ':', 0, 0, 'L');
            $pdf->Cell(0, 6, $item[1], 0, 1, 'L');
        }

        $pdf->Ln(1);

        // Kandungan Informasi dengan background
        $kandungan_info = $data['kandungan_informasi'] ?? $data['tujuan_permohonan'] ?? 'permintaan informasi perbup tentang spbe';
        $pdf->Cell(50, 6, 'Kandungan Informasi', 0, 0, 'L');
        $pdf->Cell(5, 6, ':', 0, 0, 'L');
        $pdf->SetFillColor(211, 211, 211);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(0, 6, $kandungan_info, 1, 1, 'L', true);

        $pdf->Ln(1);

        // Tujuan Penggunaan dengan background
        $tujuan_penggunaan = $data['tujuan_penggunaan_informasi'] ?? 'permintaan informasi perbup tentang spbe';
        $pdf->Cell(50, 6, 'Tujuan Penggunaan', 0, 0, 'L');
        $pdf->Cell(5, 6, ':', 0, 0, 'L');
        $pdf->SetFillColor(211, 211, 211);
        $pdf->Cell(0, 6, $tujuan_penggunaan, 1, 1, 'L', true);

        $pdf->SetFillColor(255, 255, 255);

        $pdf->Ln(2);

        // Keputusan PPID section - PERMOHONAN DITOLAK
        $pdf->SetFont('times', '', 12);
        $pdf->Cell(0, 6, 'Keputusan PPID', 0, 1, 'C');

        $pdf->SetFont('times', 'B', 12);
        $pdf->Cell(0, 6, 'PERMOHONAN DITOLAK', 0, 1, 'C');

        $pdf->SetFont('times', '', 12);
        $pdf->Ln(2);

        // Alasan Penolakan
        $alasan_penolakan = !empty($data['alasan_penolakan']) ? $data['alasan_penolakan'] : '-';
        $pdf->Cell(50, 6, 'Alasan Penolakan', 0, 0, 'L');
        $pdf->Cell(5, 6, ':', 0, 0, 'L');
        $pdf->SetFillColor(255, 235, 59);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(0, 6, $alasan_penolakan, 1, 1, 'L', true);

        $pdf->SetFillColor(255, 255, 255);
        $pdf->Ln(2);

        // Catatan Petugas dengan background seperti kandungan informasi
        $catatan_petugas = !empty($data['catatan_petugas']) ? $data['catatan_petugas'] : '-';
        $pdf->SetFont('times', '', 12);
        $pdf->Cell(50, 6, 'Catatan Petugas', 0, 0, 'L');
        $pdf->Cell(5, 6, ':', 0, 0, 'L');
        $pdf->SetFillColor(211, 211, 211);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(0, 6, $catatan_petugas, 1, 1, 'L', true);

        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetTextColor(0, 0, 0);

        $pdf->Ln(2);

        // Website
        $pdf->Cell(50, 6, 'Website', 0, 0, 'L');
        $pdf->Cell(5, 6, ':', 0, 0, 'L');
        $pdf->Cell(0, 6, 'www.madinakab.go.id', 0, 1, 'L');
    }

    // PROSES METHODS

    // Display diproses index
    public function diprosesIndex()
    {
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $search = isset($_GET['search']) ? trim($_GET['search']) : '';

        // Get permohonan with status "Diproses" ONLY
        $proses_status = ['Diproses'];
        $permohonan_list = $this->permohonanAdminModel->getProsesPermohonan($limit, $offset, $search, $proses_status);
        $total_records = $this->permohonanAdminModel->countProsesPermohonan($search, $proses_status);
        $total_pages = ($total_records > 0) ? ceil($total_records / $limit) : 0;

        // Get proses specific stats
        $stats = $this->permohonanAdminModel->getProsesStats();

        $success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
        $error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';

        unset($_SESSION['success_message']);
        unset($_SESSION['error_message']);

        // Debug: Log the query results
        error_log("Diproses Index - Total Records: " . $total_records);
        error_log("Diproses Index - Permohonan List Count: " . count($permohonan_list));

        include 'views/permohonan_admin/proses/index.php';
    }

    // Display diproses detail
    public function diprosesDetail()
    {
        if (!isset($_GET['id'])) {
            $_SESSION['error_message'] = 'ID permohonan tidak ditemukan';
            header('Location: index.php?controller=permohonanadmin&action=diprosesIndex');
            exit();
        }

        $id = $_GET['id'];
        $permohonan = $this->permohonanAdminModel->getPermohonanById($id);

        if (!$permohonan) {
            $_SESSION['error_message'] = 'Permohonan tidak ditemukan';
            header('Location: index.php?controller=permohonanadmin&action=diprosesIndex');
            exit();
        }

        // Check if this is a proses-related permohonan (status Diproses)
        if ($permohonan['status'] !== 'Diproses') {
            $_SESSION['error_message'] = 'Permohonan ini bukan dalam status diproses';
            header('Location: index.php?controller=permohonanadmin&action=diprosesIndex');
            exit();
        }

        include 'views/permohonan_admin/proses/detail.php';
    }
}
