<?php
require_once 'models/KategoriDikecualikanPetugasModel.php';

class KategoriDikecualikanPetugasController {
    private $kategoriDikecualikanPetugasModel;
    private $skpd_info;

    public function __construct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'petugas') {
            header('Location: index.php?controller=auth&action=login');
            exit();
        }

        global $database;
        $db = null;

        if (isset($database)) {
            $db = $database->getConnection();
        }

        $this->kategoriDikecualikanPetugasModel = new KategoriDikecualikanPetugasModel($db);

        // Get SKPD info for current petugas
        $this->skpd_info = $this->kategoriDikecualikanPetugasModel->getPetugasSKPDByUserId($_SESSION['user_id']);

        if (!$this->skpd_info) {
            $_SESSION['error'] = 'Data SKPD tidak ditemukan untuk akun Anda';
            header('Location: index.php?controller=beranda&action=index');
            exit();
        }
    }

    public function index() {
        try {
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $limit = 10;
            $offset = ($page - 1) * $limit;
            $search = isset($_GET['search']) ? trim($_GET['search']) : '';

            $id_skpd = $this->skpd_info['id_skpd'];

            if (!empty($search)) {
                $dokumen_list = $this->kategoriDikecualikanPetugasModel->searchDokumenDikecualikanBySKPD($id_skpd, $search, $limit, $offset);
                $totalRecords = count($this->kategoriDikecualikanPetugasModel->searchDokumenDikecualikanBySKPD($id_skpd, $search));
            } else {
                $dokumen_list = $this->kategoriDikecualikanPetugasModel->getAllDokumenDikecualikanBySKPD($id_skpd, $limit, $offset);
                $totalRecords = $this->kategoriDikecualikanPetugasModel->getTotalCountBySKPD($id_skpd);
            }

            $totalPages = ceil($totalRecords / $limit);

            // Pagination data
            $pagination = [
                'current_page' => $page,
                'total_pages' => $totalPages,
                'total_records' => $totalRecords,
                'limit' => $limit,
                'start_record' => ($page - 1) * $limit + 1,
                'end_record' => min($page * $limit, $totalRecords)
            ];

            $pageInfo = [
                'title' => 'Kategori Dikecualikan - ' . $this->skpd_info['nama_skpd'],
                'description' => 'Manajemen Dokumen Kategori Dikecualikan'
            ];

            // Pass SKPD info to view
            $skpd_info = $this->skpd_info;

            include 'views/kategori_dikecualikan_petugas/index.php';

        } catch (Exception $e) {
            error_log("Error in KategoriDikecualikanPetugasController::index: " . $e->getMessage());
            $_SESSION['error'] = 'Gagal memuat data kategori dikecualikan';
            header('Location: index.php?controller=beranda&action=index');
            exit();
        }
    }

    public function create() {
        try {
            $data['dokumen_pemda_options'] = $this->kategoriDikecualikanPetugasModel->getAllDokumenPemda();
            $data['skpd_info'] = $this->skpd_info;

            $pageInfo = [
                'title' => 'Tambah Dokumen Dikecualikan - ' . $this->skpd_info['nama_skpd'],
                'description' => 'Form Tambah Dokumen Kategori Dikecualikan'
            ];

            include 'views/kategori_dikecualikan_petugas/form.php';

        } catch (Exception $e) {
            error_log("Error in KategoriDikecualikanPetugasController::create: " . $e->getMessage());
            $_SESSION['error'] = 'Gagal memuat form tambah dokumen dikecualikan';
            header('Location: index.php?controller=kategoridikecualikanpetugas&action=index');
            exit();
        }
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=kategoridikecualikanpetugas&action=index');
            exit();
        }

        try {
            $data = [
                'judul' => trim($_POST['judul'] ?? ''),
                'kandungan_informasi' => trim($_POST['kandungan_informasi'] ?? ''),
                'terbitkan_sebagai' => trim($_POST['terbitkan_sebagai'] ?? ''),
                'id_dokumen_pemda' => !empty($_POST['id_dokumen_pemda']) ? (int)$_POST['id_dokumen_pemda'] : null,
                'tipe_file' => $_POST['tipe_file'] ?? 'text',
                'upload_file' => null,
                'status' => $_POST['status'] ?? 'draft'
            ];

            // Handle file upload
            if (isset($_FILES['upload_file']) && $_FILES['upload_file']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'uploads/dokumen_dikecualikan/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                $fileExtension = pathinfo($_FILES['upload_file']['name'], PATHINFO_EXTENSION);
                $fileName = uniqid() . '_' . time() . '.' . $fileExtension;
                $uploadPath = $uploadDir . $fileName;

                if (move_uploaded_file($_FILES['upload_file']['tmp_name'], $uploadPath)) {
                    $data['upload_file'] = $uploadPath;
                }
            }

            $errors = $this->validateDokumenData($data);

            if (!empty($errors)) {
                $_SESSION['error'] = implode('<br>', $errors);
                $_SESSION['form_data'] = $data;
                header('Location: index.php?controller=kategoridikecualikanpetugas&action=create');
                exit();
            }

            $result = $this->kategoriDikecualikanPetugasModel->createDokumen($data, $_SESSION['user_id']);

            if ($result) {
                $_SESSION['success'] = 'Dokumen dikecualikan berhasil ditambahkan';
            } else {
                $_SESSION['error'] = 'Gagal menambahkan dokumen dikecualikan';
            }

        } catch (Exception $e) {
            error_log("Error in KategoriDikecualikanPetugasController::store: " . $e->getMessage());
            $_SESSION['error'] = 'Terjadi kesalahan saat menyimpan data';
        }

        header('Location: index.php?controller=kategoridikecualikanpetugas&action=index');
        exit();
    }

    public function edit() {
        try {
            $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

            if ($id <= 0) {
                $_SESSION['error'] = 'ID dokumen tidak valid';
                header('Location: index.php?controller=kategoridikecualikanpetugas&action=index');
                exit();
            }

            $id_skpd = $this->skpd_info['id_skpd'];
            $data['dokumen'] = $this->kategoriDikecualikanPetugasModel->getDokumenByIdAndSKPD($id, $id_skpd);

            if (!$data['dokumen']) {
                $_SESSION['error'] = 'Dokumen tidak ditemukan atau Anda tidak memiliki akses';
                header('Location: index.php?controller=kategoridikecualikanpetugas&action=index');
                exit();
            }

            $data['dokumen_pemda_options'] = $this->kategoriDikecualikanPetugasModel->getAllDokumenPemda();
            $data['skpd_info'] = $this->skpd_info;

            $pageInfo = [
                'title' => 'Edit Dokumen Dikecualikan - ' . $this->skpd_info['nama_skpd'],
                'description' => 'Form Edit Dokumen Kategori Dikecualikan'
            ];

            include 'views/kategori_dikecualikan_petugas/form.php';

        } catch (Exception $e) {
            error_log("Error in KategoriDikecualikanPetugasController::edit: " . $e->getMessage());
            $_SESSION['error'] = 'Gagal memuat data dokumen dikecualikan';
            header('Location: index.php?controller=kategoridikecualikanpetugas&action=index');
            exit();
        }
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=kategoridikecualikanpetugas&action=index');
            exit();
        }

        try {
            $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

            if ($id <= 0) {
                $_SESSION['error'] = 'ID dokumen tidak valid';
                header('Location: index.php?controller=kategoridikecualikanpetugas&action=index');
                exit();
            }

            $id_skpd = $this->skpd_info['id_skpd'];

            // Get existing document for file handling
            $existingDokumen = $this->kategoriDikecualikanPetugasModel->getDokumenByIdAndSKPD($id, $id_skpd);
            if (!$existingDokumen) {
                $_SESSION['error'] = 'Dokumen tidak ditemukan atau Anda tidak memiliki akses';
                header('Location: index.php?controller=kategoridikecualikanpetugas&action=index');
                exit();
            }

            $data = [
                'judul' => trim($_POST['judul'] ?? ''),
                'kandungan_informasi' => trim($_POST['kandungan_informasi'] ?? ''),
                'terbitkan_sebagai' => trim($_POST['terbitkan_sebagai'] ?? ''),
                'id_dokumen_pemda' => !empty($_POST['id_dokumen_pemda']) ? (int)$_POST['id_dokumen_pemda'] : null,
                'tipe_file' => $_POST['tipe_file'] ?? 'text',
                'upload_file' => $existingDokumen['upload_file'], // Keep existing file by default
                'status' => $_POST['status'] ?? 'draft'
            ];

            // Handle file upload
            if (isset($_FILES['upload_file']) && $_FILES['upload_file']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'uploads/dokumen_dikecualikan/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                $fileExtension = pathinfo($_FILES['upload_file']['name'], PATHINFO_EXTENSION);
                $fileName = uniqid() . '_' . time() . '.' . $fileExtension;
                $uploadPath = $uploadDir . $fileName;

                if (move_uploaded_file($_FILES['upload_file']['tmp_name'], $uploadPath)) {
                    // Delete old file if exists
                    if ($existingDokumen['upload_file'] && file_exists($existingDokumen['upload_file'])) {
                        unlink($existingDokumen['upload_file']);
                    }
                    $data['upload_file'] = $uploadPath;
                }
            }

            $errors = $this->validateDokumenData($data);

            if (!empty($errors)) {
                $_SESSION['error'] = implode('<br>', $errors);
                $_SESSION['form_data'] = $data;
                header('Location: index.php?controller=kategoridikecualikanpetugas&action=edit&id=' . $id);
                exit();
            }

            $result = $this->kategoriDikecualikanPetugasModel->updateDokumen($id, $data, $id_skpd);

            if ($result) {
                $_SESSION['success'] = 'Dokumen dikecualikan berhasil diperbarui';
            } else {
                $_SESSION['error'] = 'Gagal memperbarui dokumen dikecualikan';
            }

        } catch (Exception $e) {
            error_log("Error in KategoriDikecualikanPetugasController::update: " . $e->getMessage());
            $_SESSION['error'] = 'Terjadi kesalahan saat memperbarui data';
        }

        header('Location: index.php?controller=kategoridikecualikanpetugas&action=index');
        exit();
    }

    public function destroy() {
        try {
            $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

            if ($id <= 0) {
                echo json_encode([
                    'success' => false,
                    'message' => 'ID dokumen tidak valid'
                ]);
                exit();
            }

            $id_skpd = $this->skpd_info['id_skpd'];
            $dokumen = $this->kategoriDikecualikanPetugasModel->getDokumenByIdAndSKPD($id, $id_skpd);

            if (!$dokumen) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Dokumen tidak ditemukan atau Anda tidak memiliki akses'
                ]);
                exit();
            }

            // Delete file if exists
            if ($dokumen['upload_file'] && file_exists($dokumen['upload_file'])) {
                unlink($dokumen['upload_file']);
            }

            $result = $this->kategoriDikecualikanPetugasModel->deleteDokumen($id, $id_skpd);

            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Dokumen dikecualikan berhasil dihapus'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Gagal menghapus dokumen dikecualikan'
                ]);
            }

        } catch (Exception $e) {
            error_log("Error in KategoriDikecualikanPetugasController::destroy: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus data'
            ]);
        }
        exit();
    }

    public function updateToDraft() {
        try {
            $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

            if ($id <= 0) {
                echo json_encode([
                    'success' => false,
                    'message' => 'ID dokumen tidak valid'
                ]);
                exit();
            }

            $id_skpd = $this->skpd_info['id_skpd'];
            $result = $this->kategoriDikecualikanPetugasModel->updateStatusToDraft($id, $id_skpd);

            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Status berhasil diubah ke draft'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Gagal mengubah status ke draft'
                ]);
            }

        } catch (Exception $e) {
            error_log("Error in KategoriDikecualikanPetugasController::updateToDraft: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengubah status'
            ]);
        }
        exit();
    }

    public function updateToPublikasi() {
        try {
            $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

            if ($id <= 0) {
                echo json_encode([
                    'success' => false,
                    'message' => 'ID dokumen tidak valid'
                ]);
                exit();
            }

            $id_skpd = $this->skpd_info['id_skpd'];
            $result = $this->kategoriDikecualikanPetugasModel->updateStatusToPublikasi($id, $id_skpd);

            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Status berhasil diubah ke publikasi'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Gagal mengubah status ke publikasi'
                ]);
            }

        } catch (Exception $e) {
            error_log("Error in KategoriDikecualikanPetugasController::updateToPublikasi: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengubah status'
            ]);
        }
        exit();
    }

    public function draft() {
        try {
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $limit = 10;
            $offset = ($page - 1) * $limit;
            $search = isset($_GET['search']) ? trim($_GET['search']) : '';

            $id_skpd = $this->skpd_info['id_skpd'];

            if (!empty($search)) {
                $dokumen_list = $this->kategoriDikecualikanPetugasModel->searchDraftDokumenBySKPD($id_skpd, $search, $limit, $offset);
                $totalRecords = count($this->kategoriDikecualikanPetugasModel->searchDraftDokumenBySKPD($id_skpd, $search));
            } else {
                $dokumen_list = $this->kategoriDikecualikanPetugasModel->getDraftDokumenBySKPD($id_skpd, $limit, $offset);
                $totalRecords = $this->kategoriDikecualikanPetugasModel->getTotalDraftCountBySKPD($id_skpd);
            }

            $totalPages = ceil($totalRecords / $limit);

            // Pagination data
            $pagination = [
                'current_page' => $page,
                'total_pages' => $totalPages,
                'total_records' => $totalRecords,
                'limit' => $limit,
                'start_record' => ($page - 1) * $limit + 1,
                'end_record' => min($page * $limit, $totalRecords)
            ];

            $pageInfo = [
                'title' => 'Draft Dokumen Dikecualikan - ' . $this->skpd_info['nama_skpd'],
                'description' => 'Manajemen Draft Dokumen Kategori Dikecualikan'
            ];

            // Pass SKPD info to view
            $skpd_info = $this->skpd_info;

            include 'views/kategori_dikecualikan_petugas/draft.php';

        } catch (Exception $e) {
            error_log("Error in KategoriDikecualikanPetugasController::draft: " . $e->getMessage());
            $_SESSION['error'] = 'Gagal memuat data draft dokumen dikecualikan';
            header('Location: index.php?controller=kategoridikecualikanpetugas&action=index');
            exit();
        }
    }

    private function validateDokumenData($data) {
        $errors = [];

        if (empty($data['judul'])) {
            $errors[] = 'Judul dokumen harus diisi';
        } elseif (strlen($data['judul']) > 255) {
            $errors[] = 'Judul dokumen maksimal 255 karakter';
        }

        if (empty($data['terbitkan_sebagai'])) {
            $errors[] = 'Terbitkan sebagai harus diisi';
        } elseif (strlen($data['terbitkan_sebagai']) > 255) {
            $errors[] = 'Terbitkan sebagai maksimal 255 karakter';
        }

        if (!in_array($data['tipe_file'], ['audio', 'video', 'text', 'gambar', 'lainnya'])) {
            $errors[] = 'Tipe file tidak valid';
        }

        if (!in_array($data['status'], ['draft', 'publikasi'])) {
            $errors[] = 'Status tidak valid';
        }

        return $errors;
    }
}
?>
