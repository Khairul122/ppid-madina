<?php
require_once 'models/SKPDModel.php';

class SKPDController {
    private $skpdModel;

    public function __construct() {
        global $database;
        $db = $database->getConnection();
        $this->skpdModel = new SKPDModel($db);
    }

    private function checkAdmin() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            $_SESSION['error_message'] = 'Akses ditolak. Hanya admin yang dapat mengakses halaman ini.';
            header('Location: index.php?controller=auth&action=login');
            exit();
        }
    }

    public function index() {
        $this->checkAdmin();

        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;

        // Validasi page dan limit
        $page = max(1, $page);
        $limit = max(1, min(100, $limit)); // limit maksimal 100

        $offset = ($page - 1) * $limit;

        if (!empty($search)) {
            $skpd_list = $this->skpdModel->searchSKPDWithPagination($search, $limit, $offset);
            $total_records = $this->skpdModel->getSearchResultCount($search);
        } else {
            $skpd_list = $this->skpdModel->getSKPDWithPagination($limit, $offset);
            $total_records = $this->skpdModel->getTotalSKPD();
        }

        // Hitung pagination
        $total_pages = ceil($total_records / $limit);
        $start_record = $offset + 1;
        $end_record = min($offset + $limit, $total_records);

        // Data untuk view
        $pagination = [
            'current_page' => $page,
            'total_pages' => $total_pages,
            'limit' => $limit,
            'total_records' => $total_records,
            'start_record' => $start_record,
            'end_record' => $end_record,
            'search' => $search
        ];

        include 'views/skpd/index.php';
    }

    public function form() {
        $this->checkAdmin();

        $skpd_data = null;
        $is_edit = false;

        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $skpd_data = $this->skpdModel->getSKPDById($id);
            if (!$skpd_data) {
                $_SESSION['error_message'] = 'Data SKPD tidak ditemukan';
                header('Location: index.php?controller=skpd&action=index');
                exit();
            }
            $is_edit = true;
        }

        include 'views/skpd/form.php';
    }

    public function create() {
        $this->checkAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=skpd&action=form');
            exit();
        }

        $data = [
            'nama_skpd' => trim($_POST['nama_skpd'] ?? ''),
            'alamat' => trim($_POST['alamat'] ?? ''),
            'telepon' => trim($_POST['telepon'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'kategori' => trim($_POST['kategori'] ?? '')
        ];

        // Validasi input
        $validation = $this->validateInput($data);
        if (!$validation['valid']) {
            $_SESSION['error_message'] = $validation['message'];
            $_SESSION['form_data'] = $data;
            header('Location: index.php?controller=skpd&action=form');
            exit();
        }

        // Cek email unique
        if (!$this->skpdModel->isEmailUnique($data['email'])) {
            $_SESSION['error_message'] = 'Email sudah digunakan oleh SKPD lain';
            $_SESSION['form_data'] = $data;
            header('Location: index.php?controller=skpd&action=form');
            exit();
        }

        $result = $this->skpdModel->createSKPD($data);

        if ($result['success']) {
            $_SESSION['success_message'] = $result['message'];
            unset($_SESSION['form_data']);
        } else {
            $_SESSION['error_message'] = $result['message'];
            $_SESSION['form_data'] = $data;
        }

        header('Location: index.php?controller=skpd&action=index');
        exit();
    }

    public function update() {
        $this->checkAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id_skpd'])) {
            header('Location: index.php?controller=skpd&action=index');
            exit();
        }

        $id = $_POST['id_skpd'];
        $data = [
            'nama_skpd' => trim($_POST['nama_skpd'] ?? ''),
            'alamat' => trim($_POST['alamat'] ?? ''),
            'telepon' => trim($_POST['telepon'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'kategori' => trim($_POST['kategori'] ?? '')
        ];

        // Validasi input
        $validation = $this->validateInput($data);
        if (!$validation['valid']) {
            $_SESSION['error_message'] = $validation['message'];
            $_SESSION['form_data'] = $data;
            header('Location: index.php?controller=skpd&action=form&id=' . $id);
            exit();
        }

        // Cek email unique (kecuali untuk data yang sedang diedit)
        if (!$this->skpdModel->isEmailUnique($data['email'], $id)) {
            $_SESSION['error_message'] = 'Email sudah digunakan oleh SKPD lain';
            $_SESSION['form_data'] = $data;
            header('Location: index.php?controller=skpd&action=form&id=' . $id);
            exit();
        }

        $result = $this->skpdModel->updateSKPD($id, $data);

        if ($result['success']) {
            $_SESSION['success_message'] = $result['message'];
            unset($_SESSION['form_data']);
        } else {
            $_SESSION['error_message'] = $result['message'];
            $_SESSION['form_data'] = $data;
        }

        header('Location: index.php?controller=skpd&action=index');
        exit();
    }

    public function delete() {
        $this->checkAdmin();

        if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
            $_SESSION['error_message'] = 'ID SKPD tidak valid';
            header('Location: index.php?controller=skpd&action=index');
            exit();
        }

        $id = $_POST['id'];
        $result = $this->skpdModel->deleteSKPD($id);

        if ($result['success']) {
            $_SESSION['success_message'] = $result['message'];
        } else {
            $_SESSION['error_message'] = $result['message'];
        }

        header('Location: index.php?controller=skpd&action=index');
        exit();
    }

    public function export() {
        $this->checkAdmin();

        $skpd_list = $this->skpdModel->getAllSKPD();

        if (empty($skpd_list)) {
            $_SESSION['error_message'] = 'Tidak ada data SKPD untuk diekspor';
            header('Location: index.php?controller=skpd&action=index');
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
            ->setTitle("Data SKPD")
            ->setSubject("Data SKPD")
            ->setDescription("Data SKPD yang diekspor dari Sistem PPID Mandailing");

        // Set header
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nama SKPD');
        $sheet->setCellValue('C1', 'Alamat');
        $sheet->setCellValue('D1', 'Telepon');
        $sheet->setCellValue('E1', 'Email');

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
        foreach ($skpd_list as $skpd) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $skpd['id_skpd']);
            $sheet->setCellValue('C' . $row, $skpd['nama_skpd']);
            $sheet->setCellValue('D' . $row, $skpd['alamat'] ?: '-');
            $sheet->setCellValue('E' . $row, $skpd['telepon'] ?: '-');
            $sheet->setCellValue('F' . $row, $skpd['email'] ?: '-');
            
            $row++;
        }

        // Auto-size columns
        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Set alignment
        $sheet->getStyle('A1:F' . ($row - 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

        $filename = 'Data_SKPD_' . date('Y-m-d_H-i-s') . '.xlsx';

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

    public function detail() {
        $this->checkAdmin();

        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            $_SESSION['error_message'] = 'ID SKPD tidak valid';
            header('Location: index.php?controller=skpd&action=index');
            exit();
        }

        $id = $_GET['id'];
        $skpd_data = $this->skpdModel->getSKPDById($id);

        if (!$skpd_data) {
            $_SESSION['error_message'] = 'Data SKPD tidak ditemukan';
            header('Location: index.php?controller=skpd&action=index');
            exit();
        }

        // Return JSON untuk AJAX request
        if (isset($_GET['ajax'])) {
            header('Content-Type: application/json');
            echo json_encode($skpd_data);
            exit();
        }

        include 'views/skpd/detail.php';
    }

    private function validateInput($data) {
        if (empty($data['nama_skpd'])) {
            return ['valid' => false, 'message' => 'Nama SKPD wajib diisi'];
        }

        if (strlen($data['nama_skpd']) > 255) {
            return ['valid' => false, 'message' => 'Nama SKPD maksimal 255 karakter'];
        }

        if (!empty($data['alamat']) && strlen($data['alamat']) > 255) {
            return ['valid' => false, 'message' => 'Alamat maksimal 255 karakter'];
        }

        if (!empty($data['telepon'])) {
            if (strlen($data['telepon']) > 20) {
                return ['valid' => false, 'message' => 'Telepon maksimal 20 karakter'];
            }
            if (!preg_match('/^[0-9\-\+\(\)\s]+$/', $data['telepon'])) {
                return ['valid' => false, 'message' => 'Format telepon tidak valid'];
            }
        }

        if (!empty($data['email'])) {
            if (strlen($data['email']) > 255) {
                return ['valid' => false, 'message' => 'Email maksimal 255 karakter'];
            }
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                return ['valid' => false, 'message' => 'Format email tidak valid'];
            }
        }

        if (!empty($data['kategori']) && strlen($data['kategori']) > 100) {
            return ['valid' => false, 'message' => 'Kategori maksimal 100 karakter'];
        }

        return ['valid' => true, 'message' => ''];
    }
}
?>