<?php
require_once 'models/PetugasModel.php';

class PetugasController {
    private $petugasModel;

    public function __construct() {
        global $database;
        $db = $database->getConnection();
        $this->petugasModel = new PetugasModel($db);
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
            $petugas_list = $this->petugasModel->searchPetugasWithPagination($search, $limit, $offset);
            $total_records = $this->petugasModel->getSearchResultCount($search);
        } else {
            $petugas_list = $this->petugasModel->getPetugasWithPagination($limit, $offset);
            $total_records = $this->petugasModel->getTotalPetugas();
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

        include 'views/petugas/index.php';
    }

    public function form() {
        $this->checkAdmin();

        $petugas_data = null;
        $is_edit = false;

        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $petugas_data = $this->petugasModel->getPetugasById($id);
            if (!$petugas_data) {
                $_SESSION['error_message'] = 'Data petugas tidak ditemukan';
                header('Location: index.php?controller=petugas&action=index');
                exit();
            }
            $is_edit = true;
        }

        // Get SKPD list for dropdown
        $skpd_list = $this->petugasModel->getAllSKPD();

        include 'views/petugas/form.php';
    }

    public function create() {
        $this->checkAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=petugas&action=form');
            exit();
        }

        $data = [
            'id_skpd' => trim($_POST['id_skpd'] ?? ''),
            'nama_petugas' => trim($_POST['nama_petugas'] ?? ''),
            'no_kontak' => trim($_POST['no_kontak'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'password' => trim($_POST['password'] ?? '')
        ];

        // Validasi input
        $validation = $this->validateInput($data, false);
        if (!$validation['valid']) {
            $_SESSION['error_message'] = $validation['message'];
            $_SESSION['form_data'] = $data;
            header('Location: index.php?controller=petugas&action=form');
            exit();
        }

        $result = $this->petugasModel->createPetugas($data);

        if ($result['success']) {
            $_SESSION['success_message'] = $result['message'];
            unset($_SESSION['form_data']);
        } else {
            $_SESSION['error_message'] = $result['message'];
            $_SESSION['form_data'] = $data;
        }

        header('Location: index.php?controller=petugas&action=index');
        exit();
    }

    public function update() {
        $this->checkAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id_petugas'])) {
            header('Location: index.php?controller=petugas&action=index');
            exit();
        }

        $id = $_POST['id_petugas'];
        $data = [
            'id_skpd' => trim($_POST['id_skpd'] ?? ''),
            'nama_petugas' => trim($_POST['nama_petugas'] ?? ''),
            'no_kontak' => trim($_POST['no_kontak'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'password' => trim($_POST['password'] ?? '') // Optional for update
        ];

        // Validasi input
        $validation = $this->validateInput($data, true, $id);
        if (!$validation['valid']) {
            $_SESSION['error_message'] = $validation['message'];
            $_SESSION['form_data'] = $data;
            header('Location: index.php?controller=petugas&action=form&id=' . $id);
            exit();
        }

        $result = $this->petugasModel->updatePetugas($id, $data);

        if ($result['success']) {
            $_SESSION['success_message'] = $result['message'];
            unset($_SESSION['form_data']);
        } else {
            $_SESSION['error_message'] = $result['message'];
            $_SESSION['form_data'] = $data;
        }

        header('Location: index.php?controller=petugas&action=index');
        exit();
    }

    public function delete() {
        $this->checkAdmin();

        if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
            $_SESSION['error_message'] = 'ID petugas tidak valid';
            header('Location: index.php?controller=petugas&action=index');
            exit();
        }

        $id = $_POST['id'];
        $result = $this->petugasModel->deletePetugas($id);

        if ($result['success']) {
            $_SESSION['success_message'] = $result['message'];
        } else {
            $_SESSION['error_message'] = $result['message'];
        }

        header('Location: index.php?controller=petugas&action=index');
        exit();
    }

    public function export() {
        $this->checkAdmin();

        $petugas_list = $this->petugasModel->getAllPetugas();

        if (empty($petugas_list)) {
            $_SESSION['error_message'] = 'Tidak ada data petugas untuk diekspor';
            header('Location: index.php?controller=petugas&action=index');
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
            ->setTitle("Data Petugas")
            ->setSubject("Data Petugas")
            ->setDescription("Data Petugas yang diekspor dari Sistem PPID Mandailing");

        // Set header
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nama Petugas');
        $sheet->setCellValue('C1', 'SKPD');
        $sheet->setCellValue('D1', 'Email');
        $sheet->setCellValue('E1', 'No Kontak');

        // Style header row
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4A90E2']
            ]
        ];

        $sheet->getStyle('A1:E1')->applyFromArray($headerStyle);

        // Add data
        $row = 2;
        $no = 1;
        foreach ($petugas_list as $petugas) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $petugas['nama_petugas']);
            $sheet->setCellValue('C' . $row, $petugas['nama_skpd']);
            $sheet->setCellValue('D' . $row, $petugas['email']);
            $sheet->setCellValue('E' . $row, $petugas['no_kontak'] ?: '-');

            $row++;
        }

        // Auto-size columns
        foreach (range('A', 'E') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Set alignment
        $sheet->getStyle('A1:E' . ($row - 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

        $filename = 'Data_Petugas_' . date('Y-m-d_H-i-s') . '.xlsx';

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
            $_SESSION['error_message'] = 'ID petugas tidak valid';
            header('Location: index.php?controller=petugas&action=index');
            exit();
        }

        $id = $_GET['id'];
        $petugas_data = $this->petugasModel->getPetugasById($id);

        if (!$petugas_data) {
            $_SESSION['error_message'] = 'Data petugas tidak ditemukan';
            header('Location: index.php?controller=petugas&action=index');
            exit();
        }

        // Return JSON untuk AJAX request
        if (isset($_GET['ajax'])) {
            header('Content-Type: application/json');
            echo json_encode($petugas_data);
            exit();
        }

        include 'views/petugas/detail.php';
    }

    private function validateInput($data, $isUpdate = false, $petugasId = null) {
        // Validasi nama petugas
        if (empty($data['nama_petugas'])) {
            return ['valid' => false, 'message' => 'Nama petugas wajib diisi'];
        }

        if (strlen($data['nama_petugas']) > 255) {
            return ['valid' => false, 'message' => 'Nama petugas maksimal 255 karakter'];
        }

        // Validasi SKPD
        if (empty($data['id_skpd']) || !is_numeric($data['id_skpd'])) {
            return ['valid' => false, 'message' => 'SKPD wajib dipilih'];
        }

        // Validasi email
        if (empty($data['email'])) {
            return ['valid' => false, 'message' => 'Email wajib diisi'];
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return ['valid' => false, 'message' => 'Format email tidak valid'];
        }

        if (strlen($data['email']) > 255) {
            return ['valid' => false, 'message' => 'Email maksimal 255 karakter'];
        }

        // Validasi password
        if (!$isUpdate && empty($data['password'])) {
            return ['valid' => false, 'message' => 'Password wajib diisi untuk petugas baru'];
        }

        if (!empty($data['password']) && strlen($data['password']) < 6) {
            return ['valid' => false, 'message' => 'Password minimal 6 karakter'];
        }

        // Validasi no_kontak
        if (!empty($data['no_kontak'])) {
            if (strlen($data['no_kontak']) > 20) {
                return ['valid' => false, 'message' => 'No kontak maksimal 20 karakter'];
            }
            if (!preg_match('/^[0-9\-\+\(\)\s]+$/', $data['no_kontak'])) {
                return ['valid' => false, 'message' => 'Format no kontak tidak valid'];
            }
        }

        // Validasi email unique
        if ($isUpdate && $petugasId) {
            $currentData = $this->petugasModel->getPetugasById($petugasId);
            if ($currentData && !$this->petugasModel->isEmailUnique($data['email'], $currentData['id_users'])) {
                return ['valid' => false, 'message' => 'Email sudah digunakan oleh petugas lain'];
            }
        } elseif (!$isUpdate && !$this->petugasModel->isEmailUnique($data['email'])) {
            return ['valid' => false, 'message' => 'Email sudah digunakan'];
        }

        return ['valid' => true, 'message' => ''];
    }
}
?>