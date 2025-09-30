<?php
require_once 'models/KategoriBerkalaModel.php';

class KategoriBerkalaController {
    private $kategoriBerkalaModel;

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

        $this->kategoriBerkalaModel = new KategoriBerkalaModel($db);
    }

    public function index() {
        try {
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $limit = 10;
            $offset = ($page - 1) * $limit;
            $search = isset($_GET['search']) ? trim($_GET['search']) : '';

            if (!empty($search)) {
                $dokumen_list = $this->kategoriBerkalaModel->searchDokumenBerkala($search, $limit, $offset);
                $totalRecords = count($this->kategoriBerkalaModel->searchDokumenBerkala($search));
            } else {
                $dokumen_list = $this->kategoriBerkalaModel->getAllDokumenBerkala($limit, $offset);
                $totalRecords = $this->kategoriBerkalaModel->getTotalCount();
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
                'title' => 'Kategori Berkala - PPID Mandailing Natal',
                'description' => 'Manajemen Dokumen Kategori Berkala'
            ];

            include 'views/kategori_berkala/index.php';

        } catch (Exception $e) {
            error_log("Error in KategoriBerkalaController::index: " . $e->getMessage());
            $_SESSION['error'] = 'Gagal memuat data kategori berkala';
            header('Location: index.php?controller=beranda&action=index');
            exit();
        }
    }

    public function create() {
        try {
            $data['dokumen_pemda_options'] = $this->kategoriBerkalaModel->getAllDokumenPemda();

            $pageInfo = [
                'title' => 'Tambah Dokumen Berkala - PPID Mandailing Natal',
                'description' => 'Form Tambah Dokumen Kategori Berkala'
            ];

            include 'views/kategori_berkala/form.php';

        } catch (Exception $e) {
            error_log("Error in KategoriBerkalaController::create: " . $e->getMessage());
            $_SESSION['error'] = 'Gagal memuat form tambah dokumen berkala';
            header('Location: index.php?controller=kategoriberkala&action=index');
            exit();
        }
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=kategoriberkala&action=index');
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
                $uploadDir = 'uploads/dokumen_berkala/';
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
                header('Location: index.php?controller=kategoriberkala&action=create');
                exit();
            }

            $result = $this->kategoriBerkalaModel->createDokumen($data);

            if ($result) {
                $_SESSION['success'] = 'Dokumen berkala berhasil ditambahkan';
            } else {
                $_SESSION['error'] = 'Gagal menambahkan dokumen berkala';
            }

        } catch (Exception $e) {
            error_log("Error in KategoriBerkalaController::store: " . $e->getMessage());
            $_SESSION['error'] = 'Terjadi kesalahan saat menyimpan data';
        }

        header('Location: index.php?controller=kategoriberkala&action=index');
        exit();
    }

    public function edit() {
        try {
            $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

            if ($id <= 0) {
                $_SESSION['error'] = 'ID dokumen tidak valid';
                header('Location: index.php?controller=kategoriberkala&action=index');
                exit();
            }

            $data['dokumen'] = $this->kategoriBerkalaModel->getDokumenById($id);

            if (!$data['dokumen']) {
                $_SESSION['error'] = 'Dokumen tidak ditemukan';
                header('Location: index.php?controller=kategoriberkala&action=index');
                exit();
            }

            $data['dokumen_pemda_options'] = $this->kategoriBerkalaModel->getAllDokumenPemda();

            $pageInfo = [
                'title' => 'Edit Dokumen Berkala - PPID Mandailing Natal',
                'description' => 'Form Edit Dokumen Kategori Berkala'
            ];

            include 'views/kategori_berkala/form.php';

        } catch (Exception $e) {
            error_log("Error in KategoriBerkalaController::edit: " . $e->getMessage());
            $_SESSION['error'] = 'Gagal memuat data dokumen berkala';
            header('Location: index.php?controller=kategoriberkala&action=index');
            exit();
        }
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=kategoriberkala&action=index');
            exit();
        }

        try {
            $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

            if ($id <= 0) {
                $_SESSION['error'] = 'ID dokumen tidak valid';
                header('Location: index.php?controller=kategoriberkala&action=index');
                exit();
            }

            // Get existing document for file handling
            $existingDokumen = $this->kategoriBerkalaModel->getDokumenById($id);
            if (!$existingDokumen) {
                $_SESSION['error'] = 'Dokumen tidak ditemukan';
                header('Location: index.php?controller=kategoriberkala&action=index');
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
                $uploadDir = 'uploads/dokumen_berkala/';
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
                header('Location: index.php?controller=kategoriberkala&action=edit&id=' . $id);
                exit();
            }

            $result = $this->kategoriBerkalaModel->updateDokumen($id, $data);

            if ($result) {
                $_SESSION['success'] = 'Dokumen berkala berhasil diperbarui';
            } else {
                $_SESSION['error'] = 'Gagal memperbarui dokumen berkala';
            }

        } catch (Exception $e) {
            error_log("Error in KategoriBerkalaController::update: " . $e->getMessage());
            $_SESSION['error'] = 'Terjadi kesalahan saat memperbarui data';
        }

        header('Location: index.php?controller=kategoriberkala&action=index');
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

            $dokumen = $this->kategoriBerkalaModel->getDokumenById($id);

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

            $result = $this->kategoriBerkalaModel->deleteDokumen($id);

            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Dokumen berkala berhasil dihapus'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Gagal menghapus dokumen berkala'
                ]);
            }

        } catch (Exception $e) {
            error_log("Error in KategoriBerkalaController::destroy: " . $e->getMessage());
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

            $result = $this->kategoriBerkalaModel->updateStatusToDraft($id);

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
            error_log("Error in KategoriBerkalaController::updateToDraft: " . $e->getMessage());
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

            $result = $this->kategoriBerkalaModel->updateStatusToPublikasi($id);

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
            error_log("Error in KategoriBerkalaController::updateToPublikasi: " . $e->getMessage());
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
                $dokumen_list = $this->kategoriBerkalaModel->searchDraftDokumen($search, $limit, $offset);
                $totalRecords = count($this->kategoriBerkalaModel->searchDraftDokumen($search));
            } else {
                $dokumen_list = $this->kategoriBerkalaModel->getDraftDokumen($limit, $offset);
                $totalRecords = $this->kategoriBerkalaModel->getTotalDraftCount();
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
                'title' => 'Draft Dokumen Berkala - PPID Mandailing Natal',
                'description' => 'Manajemen Draft Dokumen Kategori Berkala'
            ];

            include 'views/kategori_berkala/draft.php';

        } catch (Exception $e) {
            error_log("Error in KategoriBerkalaController::draft: " . $e->getMessage());
            $_SESSION['error'] = 'Gagal memuat data draft dokumen berkala';
            header('Location: index.php?controller=kategoriberkala&action=index');
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