<?php
require_once 'models/KategoriDikecualikanAdminModel.php';

class KategoriDikecualikanAdminController {
    private $kategoriDikecualikanAdminModel;

    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?controller=auth&action=login');
            exit();
        }

        global $database;
        $db = null;

        if (isset($database)) {
            $db = $database->getConnection();
        }

        $this->kategoriDikecualikanAdminModel = new KategoriDikecualikanAdminModel($db);
    }

    public function index() {
        try {
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $limit = 10;
            $offset = ($page - 1) * $limit;
            $search = isset($_GET['search']) ? trim($_GET['search']) : '';

            if (!empty($search)) {
                $dokumen_list = $this->kategoriDikecualikanAdminModel->searchDokumenDikecualikan($search, $limit, $offset);
                $totalRecords = count($this->kategoriDikecualikanAdminModel->searchDokumenDikecualikan($search));
            } else {
                $dokumen_list = $this->kategoriDikecualikanAdminModel->getAllDokumenDikecualikan($limit, $offset);
                $totalRecords = $this->kategoriDikecualikanAdminModel->getTotalCount();
            }

            $totalPages = ceil($totalRecords / $limit);

            // Pagination data matching SKPD style
            $pagination = [
                'current_page' => $page,
                'total_pages' => $totalPages,
                'total_records' => $totalRecords,
                'limit' => $limit,
                'start_record' => ($page - 1) * $limit + 1,
                'end_record' => min($page * $limit, $totalRecords)
            ];

            $pageInfo = [
                'title' => 'Kategori Dikecualikan - PPID Mandailing Natal',
                'description' => 'Manajemen Dokumen Kategori Dikecualikan'
            ];

            include 'views/kategori_dikecualikan_admin/index.php';

        } catch (Exception $e) {
            error_log("Error in KategoriDikecualikanAdminController::index: " . $e->getMessage());
            $_SESSION['error'] = 'Gagal memuat data kategori dikecualikan';
            header('Location: index.php?controller=beranda&action=index');
            exit();
        }
    }

    public function create() {
        try {
            $data['dokumen_pemda_options'] = $this->kategoriDikecualikanAdminModel->getAllDokumenPemda();

            $pageInfo = [
                'title' => 'Tambah Dokumen Dikecualikan - PPID Mandailing Natal',
                'description' => 'Form Tambah Dokumen Kategori Dikecualikan'
            ];

            include 'views/kategori_dikecualikan_admin/form.php';

        } catch (Exception $e) {
            error_log("Error in KategoriDikecualikanAdminController::create: " . $e->getMessage());
            $_SESSION['error'] = 'Gagal memuat form tambah dokumen dikecualikan';
            header('Location: index.php?controller=kategoridikecualikanadmin&action=index');
            exit();
        }
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=kategoridikecualikanadmin&action=index');
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
                header('Location: index.php?controller=kategoridikecualikanadmin&action=create');
                exit();
            }

            $result = $this->kategoriDikecualikanAdminModel->createDokumen($data);

            if ($result) {
                $_SESSION['success'] = 'Dokumen dikecualikan berhasil ditambahkan';
            } else {
                $_SESSION['error'] = 'Gagal menambahkan dokumen dikecualikan';
            }

        } catch (Exception $e) {
            error_log("Error in KategoriDikecualikanAdminController::store: " . $e->getMessage());
            $_SESSION['error'] = 'Terjadi kesalahan saat menyimpan data';
        }

        header('Location: index.php?controller=kategoridikecualikanadmin&action=index');
        exit();
    }

    public function edit() {
        try {
            $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

            if ($id <= 0) {
                $_SESSION['error'] = 'ID dokumen tidak valid';
                header('Location: index.php?controller=kategoridikecualikanadmin&action=index');
                exit();
            }

            $data['dokumen'] = $this->kategoriDikecualikanAdminModel->getDokumenById($id);

            if (!$data['dokumen']) {
                $_SESSION['error'] = 'Dokumen tidak ditemukan';
                header('Location: index.php?controller=kategoridikecualikanadmin&action=index');
                exit();
            }

            $data['dokumen_pemda_options'] = $this->kategoriDikecualikanAdminModel->getAllDokumenPemda();

            $pageInfo = [
                'title' => 'Edit Dokumen Dikecualikan - PPID Mandailing Natal',
                'description' => 'Form Edit Dokumen Kategori Dikecualikan'
            ];

            include 'views/kategori_dikecualikan_admin/form.php';

        } catch (Exception $e) {
            error_log("Error in KategoriDikecualikanAdminController::edit: " . $e->getMessage());
            $_SESSION['error'] = 'Gagal memuat data dokumen dikecualikan';
            header('Location: index.php?controller=kategoridikecualikanadmin&action=index');
            exit();
        }
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=kategoridikecualikanadmin&action=index');
            exit();
        }

        try {
            $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

            if ($id <= 0) {
                $_SESSION['error'] = 'ID dokumen tidak valid';
                header('Location: index.php?controller=kategoridikecualikanadmin&action=index');
                exit();
            }

            // Get existing document for file handling
            $existingDokumen = $this->kategoriDikecualikanAdminModel->getDokumenById($id);
            if (!$existingDokumen) {
                $_SESSION['error'] = 'Dokumen tidak ditemukan';
                header('Location: index.php?controller=kategoridikecualikanadmin&action=index');
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
                header('Location: index.php?controller=kategoridikecualikanadmin&action=edit&id=' . $id);
                exit();
            }

            $result = $this->kategoriDikecualikanAdminModel->updateDokumen($id, $data);

            if ($result) {
                $_SESSION['success'] = 'Dokumen dikecualikan berhasil diperbarui';
            } else {
                $_SESSION['error'] = 'Gagal memperbarui dokumen dikecualikan';
            }

        } catch (Exception $e) {
            error_log("Error in KategoriDikecualikanAdminController::update: " . $e->getMessage());
            $_SESSION['error'] = 'Terjadi kesalahan saat memperbarui data';
        }

        header('Location: index.php?controller=kategoridikecualikanadmin&action=index');
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

            $dokumen = $this->kategoriDikecualikanAdminModel->getDokumenById($id);

            if (!$dokumen) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Dokumen tidak ditemukan'
                ]);
                exit();
            }

            // Delete file if exists
            if ($dokumen['upload_file'] && file_exists($dokumen['upload_file'])) {
                unlink($dokumen['upload_file']);
            }

            $result = $this->kategoriDikecualikanAdminModel->deleteDokumen($id);

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
            error_log("Error in KategoriDikecualikanAdminController::destroy: " . $e->getMessage());
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

            $result = $this->kategoriDikecualikanAdminModel->updateStatusToDraft($id);

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
            error_log("Error in KategoriDikecualikanAdminController::updateToDraft: " . $e->getMessage());
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

            $result = $this->kategoriDikecualikanAdminModel->updateStatusToPublikasi($id);

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
            error_log("Error in KategoriDikecualikanAdminController::updateToPublikasi: " . $e->getMessage());
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

            if (!empty($search)) {
                $dokumen_list = $this->kategoriDikecualikanAdminModel->searchDraftDokumen($search, $limit, $offset);
                $totalRecords = count($this->kategoriDikecualikanAdminModel->searchDraftDokumen($search));
            } else {
                $dokumen_list = $this->kategoriDikecualikanAdminModel->getDraftDokumen($limit, $offset);
                $totalRecords = $this->kategoriDikecualikanAdminModel->getTotalDraftCount();
            }

            $totalPages = ceil($totalRecords / $limit);

            // Pagination data matching SKPD style
            $pagination = [
                'current_page' => $page,
                'total_pages' => $totalPages,
                'total_records' => $totalRecords,
                'limit' => $limit,
                'start_record' => ($page - 1) * $limit + 1,
                'end_record' => min($page * $limit, $totalRecords)
            ];

            $pageInfo = [
                'title' => 'Draft Dokumen Dikecualikan - PPID Mandailing Natal',
                'description' => 'Manajemen Draft Dokumen Kategori Dikecualikan'
            ];

            include 'views/kategori_dikecualikan_admin/draft.php';

        } catch (Exception $e) {
            error_log("Error in KategoriDikecualikanAdminController::draft: " . $e->getMessage());
            $_SESSION['error'] = 'Gagal memuat data draft dokumen dikecualikan';
            header('Location: index.php?controller=kategoridikecualikanadmin&action=index');
            exit();
        }
    }

    public function view() {
        try {
            $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

            if ($id <= 0) {
                $_SESSION['error'] = 'ID dokumen tidak valid';
                header('Location: index.php');
                exit();
            }

            $dokumen = $this->kategoriDikecualikanAdminModel->getDokumenById($id);

            if (!$dokumen) {
                $_SESSION['error'] = 'Dokumen tidak ditemukan';
                header('Location: index.php');
                exit();
            }

            // Check if document is published
            if ($dokumen['status'] !== 'publikasi') {
                $_SESSION['error'] = 'Dokumen tidak tersedia untuk publik';
                header('Location: index.php');
                exit();
            }

            $data = [
                'dokumen' => $dokumen,
                'title' => $dokumen['judul']
            ];

            include 'views/kategori_dikecualikan_admin/view.php';

        } catch (Exception $e) {
            error_log("Error in KategoriDikecualikanAdminController::view: " . $e->getMessage());
            $_SESSION['error'] = 'Gagal memuat dokumen dikecualikan';
            header('Location: index.php');
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
