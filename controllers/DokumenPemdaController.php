<?php
require_once 'models/DokumenPemdaModel.php';

class DokumenPemdaController {
    private $dokumenPemdaModel;

    public function __construct() {
        global $database;
        $db = $database->getConnection();
        $this->dokumenPemdaModel = new DokumenPemdaModel($db);
    }

    private function checkAuth() {
        if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'operator')) {
            $_SESSION['error'] = 'Akses ditolak. Hanya admin dan operator yang dapat mengakses halaman ini.';
            header('Location: index.php?controller=auth&action=login');
            exit();
        }
    }

    // Method untuk menampilkan halaman index dokumen pemda
    public function index() {
        $this->checkAuth();

        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;

        // Validasi page dan limit
        $page = max(1, $page);
        $limit = max(1, min(100, $limit)); // limit maksimal 100

        $offset = ($page - 1) * $limit;

        if (!empty($search)) {
            $dokumens = $this->dokumenPemdaModel->searchDokumenPemda($search, $limit, $offset);
            $totalCount = $this->dokumenPemdaModel->getSearchResultCount($search);
        } else {
            $dokumens = $this->dokumenPemdaModel->getAllDokumenPemda($limit, $offset);
            $totalCount = $this->dokumenPemdaModel->getTotalDokumenPemda();
        }

        // Hitung pagination
        $totalPages = ceil($totalCount / $limit);
        $start_record = $offset + 1;
        $end_record = min($offset + $limit, $totalCount);

        // Data untuk view
        $data = [
            'dokumens' => $dokumens,
            'search' => $search,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'limit' => $limit,
            'totalCount' => $totalCount,
            'start_record' => $start_record,
            'end_record' => $end_record,
            'kategoriOptions' => $this->dokumenPemdaModel->getKategoriOptions()
        ];

        include 'views/dokumen_pemda/index.php';
    }

    // Method untuk menampilkan form tambah dokumen
    public function create() {
        $this->checkAuth();

        $data = [
            'dokumen' => null,
            'errors' => [],
            'kategoriOptions' => $this->dokumenPemdaModel->getKategoriOptions()
        ];

        include 'views/dokumen_pemda/form.php';
    }

    // Method untuk menyimpan dokumen baru
    public function store() {
        $this->checkAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=dokumenpemda&action=create');
            exit();
        }

        $data = [
            'nama_jenis' => trim($_POST['nama_jenis'] ?? ''),
            'id_kategori' => trim($_POST['id_kategori'] ?? ''),
            'area' => trim($_POST['area'] ?? 'pemda')
        ];

        // Validasi input
        $validation = $this->validateInput($data);
        if (!$validation['valid']) {
            $_SESSION['error'] = $validation['message'];
            $_SESSION['form_data'] = $data;
            header('Location: index.php?controller=dokumenpemda&action=create');
            exit();
        }

        $result = $this->dokumenPemdaModel->createDokumenPemda($data);

        if ($result) {
            $_SESSION['success'] = 'Dokumen Pemda berhasil ditambahkan';
            unset($_SESSION['form_data']);
        } else {
            $_SESSION['error'] = 'Gagal menambahkan dokumen Pemda';
            $_SESSION['form_data'] = $data;
        }

        header('Location: index.php?controller=dokumenpemda&action=index');
        exit();
    }

    // Method untuk menampilkan form edit dokumen
    public function edit() {
        $this->checkAuth();

        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        $dokumen = $this->dokumenPemdaModel->getDokumenPemdaById($id);
        if (!$dokumen) {
            $_SESSION['error'] = 'Dokumen tidak ditemukan';
            header('Location: index.php?controller=dokumenpemda&action=index');
            exit();
        }

        $data = [
            'dokumen' => $dokumen,
            'errors' => [],
            'kategoriOptions' => $this->dokumenPemdaModel->getKategoriOptions()
        ];

        include 'views/dokumen_pemda/form.php';
    }

    // Method untuk update dokumen
    public function update() {
        $this->checkAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id'])) {
            header('Location: index.php?controller=dokumenpemda&action=index');
            exit();
        }

        $id = $_POST['id'];
        $data = [
            'nama_jenis' => trim($_POST['nama_jenis'] ?? ''),
            'id_kategori' => trim($_POST['id_kategori'] ?? ''),
            'area' => trim($_POST['area'] ?? 'pemda')
        ];

        // Validasi input
        $validation = $this->validateInput($data);
        if (!$validation['valid']) {
            $_SESSION['error'] = $validation['message'];
            $_SESSION['form_data'] = $data;
            header('Location: index.php?controller=dokumenpemda&action=edit&id=' . $id);
            exit();
        }

        $result = $this->dokumenPemdaModel->updateDokumenPemda($id, $data);

        if ($result) {
            $_SESSION['success'] = 'Dokumen Pemda berhasil diperbarui';
            unset($_SESSION['form_data']);
        } else {
            $_SESSION['error'] = 'Gagal memperbarui dokumen Pemda';
            $_SESSION['form_data'] = $data;
        }

        header('Location: index.php?controller=dokumenpemda&action=index');
        exit();
    }

    // Method untuk menghapus dokumen
    public function delete() {
        $this->checkAuth();

        if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
            $_SESSION['error'] = 'ID Dokumen tidak valid';
            header('Location: index.php?controller=dokumenpemda&action=index');
            exit();
        }

        $id = $_POST['id'];
        $result = $this->dokumenPemdaModel->deleteDokumenPemda($id);

        if ($result) {
            $_SESSION['success'] = 'Dokumen Pemda berhasil dihapus';
        } else {
            $_SESSION['error'] = 'Gagal menghapus dokumen Pemda';
        }

        header('Location: index.php?controller=dokumenpemda&action=index');
        exit();
    }

    // Method untuk detail dokumen (untuk AJAX)
    public function detail() {
        $this->checkAuth();

        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            $_SESSION['error'] = 'ID Dokumen tidak valid';
            header('Location: index.php?controller=dokumenpemda&action=index');
            exit();
        }

        $id = $_GET['id'];
        $dokumen = $this->dokumenPemdaModel->getDokumenPemdaById($id);

        if (!$dokumen) {
            $_SESSION['error'] = 'Data Dokumen tidak ditemukan';
            header('Location: index.php?controller=dokumenpemda&action=index');
            exit();
        }

        // Return JSON untuk AJAX request
        if (isset($_GET['ajax'])) {
            header('Content-Type: application/json');
            echo json_encode($dokumen);
            exit();
        }

        include 'views/dokumen_pemda/detail.php';
    }

    // Method untuk export dokumen
    public function export() {
        $this->checkAuth();

        $dokumens = $this->dokumenPemdaModel->getAllDokumenPemda();

        if (empty($dokumens)) {
            $_SESSION['error'] = 'Tidak ada data Dokumen Pemda untuk diekspor';
            header('Location: index.php?controller=dokumenpemda&action=index');
            exit();
        }

        // Include PhpSpreadsheet
        require_once __DIR__ . '/../vendor/autoload.php';

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set document properties
        $spreadsheet->getProperties()
            ->setCreator("Sistem PPID Mandailing")
            ->setLastModifiedBy("Sistem PPID Mandailing")
            ->setTitle("Data Dokumen Pemda")
            ->setSubject("Data Dokumen Pemda")
            ->setDescription("Data Dokumen Pemda yang diekspor dari Sistem PPID Mandailing");

        // Set header
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'ID Dokumen');
        $sheet->setCellValue('C1', 'Nama Jenis');
        $sheet->setCellValue('D1', 'Kategori');
        $sheet->setCellValue('E1', 'Area');
        $sheet->setCellValue('F1', 'Dibuat');

        // Style header row
        $headerStyle = [
            'font' => [
                'bold' => true,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4A90E2']
            ],
            'font' => [
                'color' => ['rgb' => 'FFFFFF']
            ]
        ];
        
        $sheet->getStyle('A1:F1')->applyFromArray($headerStyle);

        // Add data
        $row = 2;
        $no = 1;
        foreach ($dokumens as $dokumen) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $dokumen['id_dokumen_pemda']);
            $sheet->setCellValue('C' . $row, $dokumen['nama_jenis']);
            $sheet->setCellValue('D' . $row, $dokumen['nama_kategori'] ?: '-');
            $sheet->setCellValue('E' . $row, $dokumen['area']);
            $sheet->setCellValue('F' . $row, $dokumen['created_at'] ? date('Y-m-d H:i:s', strtotime($dokumen['created_at'])) : '-');
            
            $row++;
        }

        // Auto-size columns
        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Set alignment
        $sheet->getStyle('A1:F' . ($row - 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

        $filename = 'Data_Dokumen_Pemda_' . date('Y-m-d_H-i-s') . '.xlsx';

        // Set header for download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        // Write file to output
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');

        exit();
    }

    private function validateInput($data) {
        $errors = [];

        // Validasi nama_jenis
        if (empty($data['nama_jenis'])) {
            $errors[] = 'Nama jenis dokumen wajib diisi';
        } elseif (strlen($data['nama_jenis']) > 255) {
            $errors[] = 'Nama jenis dokumen maksimal 255 karakter';
        }

        // Validasi id_kategori
        if (empty($data['id_kategori'])) {
            $errors[] = 'Kategori wajib dipilih';
        } else {
            $kategoriOptions = $this->dokumenPemdaModel->getKategoriOptions();
            $found = false;
            foreach ($kategoriOptions as $kategori) {
                if ($kategori['id_kategori'] == $data['id_kategori']) {
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $errors[] = 'Kategori yang dipilih tidak valid';
            }
        }

        // Validasi area
        if (empty($data['area'])) {
            $errors[] = 'Area wajib diisi';
        } elseif (strlen($data['area']) > 50) {
            $errors[] = 'Area maksimal 50 karakter';
        }

        return [
            'valid' => empty($errors),
            'message' => implode('<br>', $errors)
        ];
    }
}
?>