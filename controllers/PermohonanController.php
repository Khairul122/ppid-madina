<?php
require_once 'models/PermohonanModel.php';
require_once 'models/UserModel.php';
require_once 'vendor/tecnickcom/tcpdf/tcpdf.php';

class PermohonanController {
    private $permohonanModel;
    private $userModel;
    private $conn;

    public function __construct() {
        global $database;
        $db = $database->getConnection();
        $this->conn = $db;
        $this->permohonanModel = new PermohonanModel($db);
        $this->userModel = new UserModel($db);
    }

    private function getSKPDData($nama_skpd) {
        if (empty($nama_skpd)) {
            return null;
        }

        $query = "SELECT * FROM skpd WHERE nama_skpd = :nama_skpd LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nama_skpd', $nama_skpd);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Get SKPD Diskominfo (ID = 5) data untuk header kop surat
     */
    private function getSKPDDiskominfo() {
        $query = "SELECT * FROM skpd WHERE id_skpd = 5 LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?controller=auth&action=login');
            exit();
        }

        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $status = isset($_GET['status']) ? $_GET['status'] : 'all';
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';

        $user_data = $this->userModel->getUserBiodata($_SESSION['user_id']);

        if (!empty($search)) {
            $permohonan_list = $this->permohonanModel->searchPermohonan($_SESSION['user_id'], $search, $limit, $offset);
            $total_records = $this->permohonanModel->countSearchResults($_SESSION['user_id'], $search);
        } else {
            $permohonan_list = $this->permohonanModel->getPermohonanByUserId($_SESSION['user_id'], $limit, $offset, $status);
            $total_records = $this->permohonanModel->countPermohonanByUserId($_SESSION['user_id'], $status);
        }

        $total_pages = ceil($total_records / $limit);
        $stats = $this->permohonanModel->getPermohonanStats($_SESSION['user_id']);

        $nama_lengkap = $user_data['nama_lengkap'] ?? $_SESSION['username'] ?? 'Pengguna';
        $status_pengguna = $user_data['status_pengguna'] ?? 'publik';
        $current_datetime = date('d F Y H:i');

        $success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
        $error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';

        unset($_SESSION['success_message']);
        unset($_SESSION['error_message']);

        include 'views/permohonan/index.php';
    }

    public function view() {
        if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
            header('Location: index.php?controller=permohonan&action=index');
            exit();
        }

        $id = $_GET['id'];
        $permohonan = $this->permohonanModel->getPermohonanById($id, $_SESSION['user_id']);

        if (!$permohonan) {
            $_SESSION['error_message'] = 'Permohonan tidak ditemukan';
            header('Location: index.php?controller=permohonan&action=index');
            exit();
        }

        $user_data = $this->userModel->getUserBiodata($_SESSION['user_id']);
        $nama_lengkap = $user_data['nama_lengkap'] ?? $_SESSION['username'] ?? 'Pengguna';
        $status_pengguna = $user_data['status_pengguna'] ?? 'publik';
        $current_datetime = date('d F Y H:i');

        include 'views/permohonan/view.php';
    }

    public function delete() {
        if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
            $_SESSION['error_message'] = 'Parameter tidak valid';
            header('Location: index.php?controller=permohonan&action=index');
            exit();
        }

        $id = $_GET['id'];
        $result = $this->permohonanModel->deletePermohonan($id, $_SESSION['user_id']);

        if ($result['success']) {
            $_SESSION['success_message'] = $result['message'];
        } else {
            $_SESSION['error_message'] = $result['message'];
        }

        header('Location: index.php?controller=permohonan&action=index');
        exit();
    }

    public function downloadFile() {
        if (!isset($_SESSION['user_id']) || !isset($_GET['file']) || !isset($_GET['id'])) {
            header('HTTP/1.0 404 Not Found');
            exit();
        }

        $permohonan_id = $_GET['id'];
        $filename = $_GET['file'];

        $permohonan = $this->permohonanModel->getPermohonanById($permohonan_id, $_SESSION['user_id']);

        if (!$permohonan) {
            header('HTTP/1.0 404 Not Found');
            exit();
        }

        $filepath = 'uploads/' . $filename;

        if (!file_exists($filepath) || strpos($filepath, 'uploads/') !== 0) {
            header('HTTP/1.0 404 Not Found');
            exit();
        }

        $user_data = $this->userModel->getUserBiodata($_SESSION['user_id']);
        $user_nik = $user_data['nik'] ?? '';

        if (!$user_nik || strpos($filename, $user_nik) !== 0) {
            header('HTTP/1.0 403 Forbidden');
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

    public function getStats() {
        if (!isset($_SESSION['user_id'])) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Unauthorized']);
            exit();
        }

        $stats = $this->permohonanModel->getPermohonanStats($_SESSION['user_id']);

        header('Content-Type: application/json');
        echo json_encode($stats);
        exit();
    }

    public function export() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?controller=auth&action=login');
            exit();
        }

        $format = isset($_GET['format']) ? $_GET['format'] : 'pdf';
        $status = isset($_GET['status']) ? $_GET['status'] : 'all';

        $permohonan_list = $this->permohonanModel->getPermohonanByUserId($_SESSION['user_id'], 1000, 0, $status);
        $user_data = $this->userModel->getUserBiodata($_SESSION['user_id']);

        if ($format === 'csv') {
            $this->exportCSV($permohonan_list, $user_data);
        } else {
            $_SESSION['error_message'] = 'Format export tidak didukung';
            header('Location: index.php?controller=permohonan&action=index');
            exit();
        }
    }

    private function exportCSV($data, $user_data) {
        $filename = 'permohonan_' . ($user_data['nik'] ?? $_SESSION['user_id']) . '_' . date('Y-m-d') . '.csv';

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: must-revalidate');

        $output = fopen('php://output', 'w');

        fputcsv($output, [
            'No Permohonan',
            'Tanggal Ajuan',
            'Tujuan Permohonan',
            'Unit Tujuan',
            'Judul Dokumen',
            'Status'
        ]);

        foreach ($data as $row) {
            fputcsv($output, [
                $row['no_permohonan'],
                date('d/m/Y', strtotime($row['created_at'] ?? 'now')),
                $row['tujuan_permohonan'],
                $row['komponen_tujuan'],
                $row['judul_dokumen'],
                $row['status'] ?? 'pending'
            ]);
        }

        fclose($output);
        exit();
    }

    public function submitLayananKepuasan() {
        if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error_message'] = 'Akses tidak valid';
            header('Location: index.php?controller=permohonan&action=index');
            exit();
        }

        // Validasi input
        $required_fields = ['id_permohonan', 'nama', 'umur', 'provinsi', 'kota', 'permohonan_informasi', 'rating'];
        foreach ($required_fields as $field) {
            if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
                $_SESSION['error_message'] = 'Semua field wajib diisi';
                header('Location: index.php?controller=permohonan&action=index');
                exit();
            }
        }

        // Validasi permohonan milik user
        $permohonan = $this->permohonanModel->getPermohonanById($_POST['id_permohonan'], $_SESSION['user_id']);
        if (!$permohonan) {
            $_SESSION['error_message'] = 'Permohonan tidak ditemukan';
            header('Location: index.php?controller=permohonan&action=index');
            exit();
        }

        // Validasi status permohonan - tidak boleh dalam status keberatan, sengketa, atau selesai
        $forbiddenStatuses = ['keberatan', 'sengketa', 'selesai', 'Selesai'];
        $currentStatus = $permohonan['status'] ?? null;
        $currentStatusLower = $currentStatus ? strtolower($currentStatus) : '';
        if (in_array($currentStatusLower, $forbiddenStatuses) || in_array($currentStatus, $forbiddenStatuses)) {
            $_SESSION['error_message'] = 'Layanan kepuasan hanya dapat diisi untuk permohonan yang bukan dalam status keberatan, sengketa, atau selesai';
            header('Location: index.php?controller=permohonan&action=index');
            exit();
        }

        // Validasi umur dan rating
        $umur = intval($_POST['umur']);
        $rating = intval($_POST['rating']);

        if ($umur < 17 || $umur > 100) {
            $_SESSION['error_message'] = 'Umur harus antara 17-100 tahun';
            header('Location: index.php?controller=permohonan&action=index');
            exit();
        }

        if ($rating < 1 || $rating > 5) {
            $_SESSION['error_message'] = 'Rating harus antara 1-5';
            header('Location: index.php?controller=permohonan&action=index');
            exit();
        }

        // Siapkan data untuk disimpan
        $data = [
            'id_permohonan' => $_POST['id_permohonan'],
            'nama' => trim($_POST['nama']),
            'umur' => $umur,
            'provinsi' => trim($_POST['provinsi']),
            'kota' => trim($_POST['kota']),
            'permohonan_informasi' => trim($_POST['permohonan_informasi']),
            'rating' => $rating
        ];

        // Simpan ke database
        $result = $this->permohonanModel->saveLayananKepuasan($data);

        if ($result['success']) {
            $_SESSION['success_message'] = $result['message'];
        } else {
            $_SESSION['error_message'] = $result['message'];
        }

        header('Location: index.php?controller=permohonan&action=index');
        exit();
    }

    public function ajukanSengketa() {
        if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error_message'] = 'Akses tidak valid';
            header('Location: index.php?controller=permohonan&action=index');
            exit();
        }

        // Validasi input
        $required_fields = ['id_permohonan', 'sengketa_decision'];
        foreach ($required_fields as $field) {
            if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
                $_SESSION['error_message'] = 'Semua field wajib diisi';
                header('Location: index.php?controller=permohonan&action=index');
                exit();
            }
        }

        $id_permohonan = intval($_POST['id_permohonan']);
        $sengketa_decision = trim($_POST['sengketa_decision']);

        // Validasi permohonan milik user
        $permohonan = $this->permohonanModel->getPermohonanById($id_permohonan, $_SESSION['user_id']);
        if (!$permohonan) {
            $_SESSION['error_message'] = 'Permohonan tidak ditemukan';
            header('Location: index.php?controller=permohonan&action=index');
            exit();
        }

        // Validasi status permohonan - hanya bisa ajukan sengketa jika statusnya "Ditolak"
        $currentStatus = $permohonan['status'] ?? null;
        if ($currentStatus !== 'Ditolak') {
            $_SESSION['error_message'] = 'Sengketa hanya bisa diajukan untuk permohonan yang ditolak';
            header('Location: index.php?controller=permohonan&action=index');
            exit();
        }

        // Jika user memilih 'ya', ubah status menjadi sengketa
        if ($sengketa_decision === 'ya') {
            $result = $this->permohonanModel->ajukanSengketa($id_permohonan);

            if ($result['success']) {
                $_SESSION['success_message'] = $result['message'];
            } else {
                $_SESSION['error_message'] = $result['message'];
            }
        } else {
            $_SESSION['success_message'] = 'Permohonan sengketa dibatalkan';
        }

        header('Location: index.php?controller=permohonan&action=index');
        exit();
    }

    public function submitKeberatan() {
        if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Akses tidak valid'
            ]);
            exit();
        }

        // Validasi input
        $required_fields = ['id_permohonan', 'alasan_keberatan', 'keterangan'];
        foreach ($required_fields as $field) {
            if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'message' => 'Semua field wajib diisi'
                ]);
                exit();
            }
        }

        $id_permohonan = intval($_POST['id_permohonan']);
        $alasan_keberatan = trim($_POST['alasan_keberatan']);
        $keterangan = trim($_POST['keterangan']);

        // Validasi permohonan milik user
        $permohonan = $this->permohonanModel->getPermohonanById($id_permohonan, $_SESSION['user_id']);
        if (!$permohonan) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Permohonan tidak ditemukan'
            ]);
            exit();
        }

        // Siapkan data untuk disimpan
        $data = [
            'id_permohonan' => $id_permohonan,
            'id_users' => $_SESSION['user_id'],
            'alasan_keberatan' => $alasan_keberatan,
            'keterangan' => $keterangan
        ];

        // Simpan keberatan dan update status
        $result = $this->permohonanModel->submitKeberatan($data);

        header('Content-Type: application/json');
        echo json_encode($result);
        exit();
    }

    // Generate PDF Bukti Permohonan
    public function generatePDF() {
        if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
            $_SESSION['error_message'] = 'Parameter tidak valid';
            header('Location: index.php?controller=permohonan&action=index');
            exit();
        }

        $id = intval($_GET['id']);
        $permohonan = $this->permohonanModel->getPermohonanById($id, $_SESSION['user_id']);

        if (!$permohonan) {
            $_SESSION['error_message'] = 'Permohonan tidak ditemukan';
            header('Location: index.php?controller=permohonan&action=index');
            exit();
        }

        $skpd_data = $this->getSKPDData($permohonan['komponen_tujuan']);
        $this->generateBuktiPermohonanPDF($permohonan, $skpd_data);
    }

    // Generate Bukti Proses PDF
    public function generateBuktiProsesPDF() {
        if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
            $_SESSION['error_message'] = 'Parameter tidak valid';
            header('Location: index.php?controller=permohonan&action=index');
            exit();
        }

        $id = intval($_GET['id']);
        $permohonan = $this->permohonanModel->getPermohonanById($id, $_SESSION['user_id']);

        if (!$permohonan) {
            $_SESSION['error_message'] = 'Permohonan tidak ditemukan';
            header('Location: index.php?controller=permohonan&action=index');
            exit();
        }

        if ($permohonan['status'] !== 'Diproses') {
            $_SESSION['error_message'] = 'Bukti proses hanya tersedia untuk status Diproses';
            header('Location: index.php?controller=permohonan&action=view&id=' . $id);
            exit();
        }

        $skpd_data = $this->getSKPDData($permohonan['komponen_tujuan']);
        $this->generateBuktiProsesTCPDF($permohonan, $skpd_data);
    }

    // Generate Bukti Selesai PDF
    public function generateBuktiSelesaiPDF() {
        if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
            $_SESSION['error_message'] = 'Parameter tidak valid';
            header('Location: index.php?controller=permohonan&action=index');
            exit();
        }

        $id = intval($_GET['id']);
        $permohonan = $this->permohonanModel->getPermohonanById($id, $_SESSION['user_id']);

        if (!$permohonan) {
            $_SESSION['error_message'] = 'Permohonan tidak ditemukan';
            header('Location: index.php?controller=permohonan&action=index');
            exit();
        }

        if ($permohonan['status'] !== 'Selesai') {
            $_SESSION['error_message'] = 'Bukti selesai hanya tersedia untuk status Selesai';
            header('Location: index.php?controller=permohonan&action=view&id=' . $id);
            exit();
        }

        $skpd_data = $this->getSKPDData($permohonan['komponen_tujuan']);
        $this->generateBuktiSelesaiTCPDF($permohonan, $skpd_data);
    }

    // Generate Bukti Ditolak PDF
    public function generateBuktiDitolakPDF() {
        if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
            $_SESSION['error_message'] = 'Parameter tidak valid';
            header('Location: index.php?controller=permohonan&action=index');
            exit();
        }

        $id = intval($_GET['id']);
        $permohonan = $this->permohonanModel->getPermohonanById($id, $_SESSION['user_id']);

        if (!$permohonan) {
            $_SESSION['error_message'] = 'Permohonan tidak ditemukan';
            header('Location: index.php?controller=permohonan&action=index');
            exit();
        }

        if ($permohonan['status'] !== 'Ditolak') {
            $_SESSION['error_message'] = 'Bukti ditolak hanya tersedia untuk status Ditolak';
            header('Location: index.php?controller=permohonan&action=view&id=' . $id);
            exit();
        }

        $skpd_data = $this->getSKPDData($permohonan['komponen_tujuan']);
        $this->generateBuktiDitolakTCPDF($permohonan, $skpd_data);
    }

    // ============ PRIVATE PDF GENERATION METHODS ============

    private function generateBuktiPermohonanPDF($data, $skpd_data = null) {
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetCreator('PPID Mandailing Natal');
        $pdf->SetAuthor('PPID Mandailing Natal');
        $pdf->SetTitle('Bukti Permohonan Informasi');
        $pdf->SetSubject('Bukti Permohonan Informasi');

        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetAutoPageBreak(true, 15);

        $pdf->AddPage();
        $pdf->SetFont('times', '', 12);
        $pdf->SetCellHeightRatio(1.15);

        $this->addPDFHeader($pdf, $data, $skpd_data);
        $this->addPDFTitle($pdf, $data);
        $this->addPDFDataSection($pdf, $data);
        $this->addPDFSignature($pdf, $data, $skpd_data);
        $this->addPDFFooter($pdf);

        $filename = 'Bukti_Permohonan_' . ($data['no_permohonan'] ?? $data['id_permohonan']) . '.pdf';
        $pdf->Output($filename, 'I');
        exit();
    }

    private function addPDFHeader($pdf, $data, $skpd_data = null) {
        $pdf->SetFont('times', 'B', 16);
        $pdf->SetTextColor(0, 0, 0);

        $logo_path = __DIR__ . '/../ppid_assets/logo-resmi.png';
        $logo_x = 15;
        $logo_y = 15;
        $text_x = $logo_x + 30;

        if (file_exists($logo_path)) {
            $pdf->Image($logo_path, $logo_x, $logo_y, 25, 25, 'PNG', '', 'T', false, 300);
            $pdf->SetXY($text_x, $logo_y);
        } else {
            $pdf->SetXY($logo_x, $logo_y);
        }

        // Nama SKPD dengan ukuran 16 bold
        $skpd_name = ($skpd_data && !empty($skpd_data['nama_skpd'])) ? $skpd_data['nama_skpd'] : ($data['komponen_tujuan'] ?? '');
        $pdf->Cell(0, 7, $skpd_name, 0, 1, 'L');

        $pdf->SetFont('times', '', 12);

        // Alamat dari tabel SKPD
        $alamat = ($skpd_data && !empty($skpd_data['alamat'])) ? $skpd_data['alamat'] : '';
        if (!empty($alamat)) {
            $pdf->SetX($text_x);
            $pdf->Cell(0, 5, $alamat, 0, 1, 'L');
        }

        // Email dari tabel SKPD
        $email = ($skpd_data && !empty($skpd_data['email'])) ? 'Email : ' . $skpd_data['email'] : '';
        if (!empty($email)) {
            $pdf->SetX($text_x);
            $pdf->Cell(0, 5, $email, 0, 1, 'L');
        }

        // Telp dari tabel SKPD
        $telp = ($skpd_data && !empty($skpd_data['telepon'])) ? 'Telp : ' . $skpd_data['telepon'] : '';
        if (!empty($telp)) {
            $pdf->SetX($text_x);
            $pdf->Cell(0, 5, $telp, 0, 1, 'L');
        }

        $pdf->Ln(12);
        $pdf->SetLineWidth(0.1);
        $pdf->Line(11, $pdf->GetY(), $pdf->getPageWidth() - 11, $pdf->GetY());
    }

    private function addPDFTitle($pdf, $data) {
        $pdf->Ln(3);
        $pdf->SetFont('times', 'B', 12);
        $pdf->Cell(0, 5, 'BUKTI PERMOHONAN INFORMASI', 0, 0, 'C');
        $pdf->SetY($pdf->GetY() + 5);
        $pdf->SetFont('times', '', 12);
        $pdf->Cell(0, 5, 'Nomor Permohonan : ' . ($data['no_permohonan'] ?? $data['id_permohonan']), 0, 1, 'C');
    }

    private function addPDFDataSection($pdf, $data) {
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

        $pdf->Cell(50, 6, 'Kandungan Informasi', 0, 0, 'L');
        $pdf->Cell(5, 6, ':', 0, 0, 'L');
        $pdf->SetFillColor(211, 211, 211);
        $pdf->Cell(0, 6, $data['kandungan_informasi'] ?? $data['tujuan_permohonan'] ?? '', 1, 1, 'L', true);
        $pdf->Ln(1);

        $pdf->Cell(50, 6, 'Tujuan Penggunaan', 0, 0, 'L');
        $pdf->Cell(5, 6, ':', 0, 0, 'L');
        $pdf->Cell(0, 6, $data['tujuan_penggunaan_informasi'] ?? '', 1, 1, 'L', true);
        $pdf->SetFillColor(255, 255, 255);

        $pdf->Cell(50, 6, 'Cara Memperoleh Informasi', 0, 0, 'L');
        $pdf->Cell(5, 6, ':', 0, 0, 'L');
        $pdf->SetFont('dejavusans', '', 12);
        $pdf->Cell(10, 6, '☐', 0, 0, 'L');
        $pdf->SetFont('times', '', 12);
        $pdf->Cell(0, 6, 'Melihat/Membaca/Mendengarkan/Mencatat', 0, 1, 'L');

        $pdf->Cell(55, 6, '', 0, 0, 'L');
        $pdf->SetFont('dejavusans', '', 12);
        $pdf->Cell(10, 6, '☑', 0, 0, 'L');
        $pdf->SetFont('times', '', 12);
        $pdf->Cell(0, 6, 'Mendapatkan Salinan Informasi (Hard Copy / Soft Copy)', 0, 1, 'L');
        $pdf->Ln(10);
    }

    private function addPDFSignature($pdf, $data, $skpd_data = null) {
        $y = $pdf->GetY();

        // Ambil nama SKPD dari data
        $nama_skpd = ($skpd_data && !empty($skpd_data['nama_skpd']))
            ? strtoupper($skpd_data['nama_skpd'])
            : strtoupper($data['komponen_tujuan'] ?? 'Pemerintah Kabupaten Mandailing Natal');

        $pdf->SetXY(20, $y);
        $pdf->Cell(80, 6, 'Petugas Pelayanan Informasi', 0, 1, 'C');
        $pdf->SetY($y + 26);
        $pdf->SetX(20);
        $pdf->Cell(80, 6, $nama_skpd, 0, 1, 'C');

        $pdf->SetXY(120, $y);
        $pdf->Cell(80, 6, 'Pemohon', 0, 1, 'C');
        $pdf->SetY($y + 26);
        $pdf->SetX(120);
        $pdf->Cell(80, 6, strtoupper($data['nama_lengkap'] ?? $data['username']), 0, 1, 'C');

        $pdf->SetY($y + 42);
        $pdf->SetLineWidth(0.1);
        $pdf->Line(10, $pdf->GetY(), $pdf->getPageWidth() - 10, $pdf->GetY());
        $pdf->Ln(10);
    }

    private function addPDFFooter($pdf) {
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
        $pdf->SetFont('times', 'I', 10);
        $pdf->Cell(0, 4, 'Lembaran ini diterbitkan oleh PPID Mandailing Natal dan dicetak pada ' . $this->getIndonesianDate(), 0, 1, 'R');
    }

    private function generateBuktiProsesTCPDF($data, $skpd_data = null) {
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetCreator('PPID Mandailing Natal');
        $pdf->SetAuthor('PPID Mandailing Natal');
        $pdf->SetTitle('Bukti Proses Permohonan Informasi');
        $pdf->SetSubject('Bukti Proses Permohonan Informasi');

        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetAutoPageBreak(true, 15);

        $pdf->AddPage();
        $pdf->SetFont('times', '', 12);
        $pdf->SetCellHeightRatio(1.15);

        $this->addPDFHeader($pdf, $data, $skpd_data);
        $this->addPDFTitle($pdf, $data);
        $this->addBuktiProsesDataSection($pdf, $data);
        $this->addPDFSignature($pdf, $data, $skpd_data);
        $this->addPDFFooter($pdf);

        $filename = 'Bukti_Proses_' . ($data['no_permohonan'] ?? $data['id_permohonan']) . '.pdf';
        $pdf->Output($filename, 'I');
        exit();
    }

    private function addBuktiProsesDataSection($pdf, $data) {
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

        $kandungan_info = $data['kandungan_informasi'] ?? $data['tujuan_permohonan'] ?? '';
        $pdf->Cell(50, 6, 'Kandungan Informasi', 0, 0, 'L');
        $pdf->Cell(5, 6, ':', 0, 0, 'L');
        $pdf->SetFillColor(211, 211, 211);
        $pdf->Cell(0, 6, $kandungan_info, 1, 1, 'L', true);
        $pdf->Ln(1);

        $tujuan_penggunaan = $data['tujuan_penggunaan_informasi'] ?? '';
        $pdf->Cell(50, 6, 'Tujuan Penggunaan', 0, 0, 'L');
        $pdf->Cell(5, 6, ':', 0, 0, 'L');
        $pdf->Cell(0, 6, $tujuan_penggunaan, 1, 1, 'L', true);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->Ln(2);

        // Keputusan PPID
        $pdf->SetFont('times', '', 12);
        $pdf->Cell(0, 6, 'Keputusan PPID', 0, 1, 'C');
        $pdf->SetFont('times', 'B', 12);
        $pdf->Cell(0, 6, 'PERMOHONAN TERPENUHI', 0, 1, 'C');
        $pdf->SetFont('times', '', 12);
        $pdf->Ln(2);

        // Cara Memperoleh Informasi
        $pdf->Cell(50, 6, 'Cara Memperoleh Informasi', 0, 0, 'L');
        $pdf->Cell(5, 6, ':', 0, 0, 'L');
        $pdf->SetFont('dejavusans', '', 12);
        $pdf->Cell(10, 6, '☐', 0, 0, 'L');
        $pdf->SetFont('times', '', 12);
        $pdf->Cell(0, 6, 'Melihat/Membaca/Mendengarkan/Mencatat', 0, 1, 'L');

        $pdf->Cell(55, 6, '', 0, 0, 'L');
        $pdf->SetFont('dejavusans', '', 12);
        $pdf->Cell(10, 6, '☑', 0, 0, 'L');
        $pdf->SetFont('times', '', 12);
        $pdf->Cell(0, 6, 'Mendapatkan Salinan Informasi (Hard Copy / Soft Copy)', 0, 1, 'L');
        $pdf->Ln(2);

        // Catatan Petugas
        if (!empty($data['catatan_petugas'])) {
            $pdf->Cell(50, 6, 'Catatan Petugas', 0, 0, 'L');
            $pdf->Cell(5, 6, ':', 0, 0, 'L');

            // Simpan posisi Y sebelum MultiCell
            $current_y = $pdf->GetY();
            $current_x = $pdf->GetX();

            // Gunakan MultiCell untuk text wrapping
            $pdf->SetFillColor(211, 211, 211);
            $pdf->MultiCell(0, 6, $data['catatan_petugas'], 1, 'L', true);
            $pdf->SetFillColor(255, 255, 255);
        }

        $pdf->Ln(10);
    }

    private function generateBuktiSelesaiTCPDF($data, $skpd_data = null) {
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetCreator('PPID Mandailing Natal');
        $pdf->SetAuthor('PPID Mandailing Natal');
        $pdf->SetTitle('Bukti Selesai Permohonan Informasi');
        $pdf->SetSubject('Bukti Selesai Permohonan Informasi');

        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetAutoPageBreak(true, 15);

        $pdf->AddPage();
        $pdf->SetFont('times', '', 12);
        $pdf->SetCellHeightRatio(1.15);

        $this->addPDFHeader($pdf, $data, $skpd_data);
        $this->addPDFTitle($pdf, $data);
        $this->addBuktiSelesaiDataSection($pdf, $data);
        $this->addPDFSignature($pdf, $data, $skpd_data);
        $this->addPDFFooter($pdf);

        $filename = 'Bukti_Selesai_' . ($data['no_permohonan'] ?? $data['id_permohonan']) . '.pdf';
        $pdf->Output($filename, 'I');
        exit();
    }

    private function addBuktiSelesaiDataSection($pdf, $data) {
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

        $kandungan_info = $data['kandungan_informasi'] ?? $data['tujuan_permohonan'] ?? '';
        $pdf->Cell(50, 6, 'Kandungan Informasi', 0, 0, 'L');
        $pdf->Cell(5, 6, ':', 0, 0, 'L');
        $pdf->SetFillColor(211, 211, 211);
        $pdf->Cell(0, 6, $kandungan_info, 1, 1, 'L', true);
        $pdf->Ln(1);

        $tujuan_penggunaan = $data['tujuan_penggunaan_informasi'] ?? '';
        $pdf->Cell(50, 6, 'Tujuan Penggunaan', 0, 0, 'L');
        $pdf->Cell(5, 6, ':', 0, 0, 'L');
        $pdf->Cell(0, 6, $tujuan_penggunaan, 1, 1, 'L', true);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->Ln(2);

        // Keputusan PPID - SELESAI
        $pdf->SetFont('times', '', 12);
        $pdf->Cell(0, 6, 'Keputusan PPID', 0, 1, 'C');
        $pdf->SetFont('times', 'B', 12);
        $pdf->Cell(0, 6, 'PERMOHONAN SELESAI', 0, 1, 'C');
        $pdf->SetFont('times', '', 12);
        $pdf->Ln(2);

        // Cara Memperoleh Informasi
        $pdf->Cell(50, 6, 'Cara Memperoleh Informasi', 0, 0, 'L');
        $pdf->Cell(5, 6, ':', 0, 0, 'L');
        $pdf->SetFont('dejavusans', '', 12);
        $pdf->Cell(10, 6, '☐', 0, 0, 'L');
        $pdf->SetFont('times', '', 12);
        $pdf->Cell(0, 6, 'Melihat/Membaca/Mendengarkan/Mencatat', 0, 1, 'L');

        $pdf->Cell(55, 6, '', 0, 0, 'L');
        $pdf->SetFont('dejavusans', '', 12);
        $pdf->Cell(10, 6, '☑', 0, 0, 'L');
        $pdf->SetFont('times', '', 12);
        $pdf->Cell(0, 6, 'Mendapatkan Salinan Informasi (Hard Copy / Soft Copy)', 0, 1, 'L');
        $pdf->Ln(2);

        // Catatan Petugas
        if (!empty($data['catatan_petugas'])) {
            $pdf->Cell(50, 6, 'Catatan Petugas', 0, 0, 'L');
            $pdf->Cell(5, 6, ':', 0, 0, 'L');

            // Simpan posisi Y sebelum MultiCell
            $current_y = $pdf->GetY();
            $current_x = $pdf->GetX();

            // Gunakan MultiCell untuk text wrapping
            $pdf->SetFillColor(211, 211, 211);
            $pdf->MultiCell(0, 6, $data['catatan_petugas'], 1, 'L', true);
            $pdf->SetFillColor(255, 255, 255);
        }

        $pdf->Ln(10);
    }

    private function generateBuktiDitolakTCPDF($data, $skpd_data = null) {
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetCreator('PPID Mandailing Natal');
        $pdf->SetAuthor('PPID Mandailing Natal');
        $pdf->SetTitle('Bukti Ditolak Permohonan Informasi');
        $pdf->SetSubject('Bukti Ditolak Permohonan Informasi');

        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetAutoPageBreak(true, 15);

        $pdf->AddPage();
        $pdf->SetFont('times', '', 12);
        $pdf->SetCellHeightRatio(1.15);

        $this->addPDFHeader($pdf, $data, $skpd_data);
        $this->addPDFTitle($pdf, $data);
        $this->addBuktiDitolakDataSection($pdf, $data);
        $this->addPDFSignature($pdf, $data, $skpd_data);
        $this->addPDFFooter($pdf);

        $filename = 'Bukti_Ditolak_' . ($data['no_permohonan'] ?? $data['id_permohonan']) . '.pdf';
        $pdf->Output($filename, 'I');
        exit();
    }

    private function addBuktiDitolakDataSection($pdf, $data) {
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

        $kandungan_info = $data['kandungan_informasi'] ?? $data['tujuan_permohonan'] ?? '';
        $pdf->Cell(50, 6, 'Kandungan Informasi', 0, 0, 'L');
        $pdf->Cell(5, 6, ':', 0, 0, 'L');
        $pdf->SetFillColor(211, 211, 211);
        $pdf->Cell(0, 6, $kandungan_info, 1, 1, 'L', true);
        $pdf->Ln(1);

        $tujuan_penggunaan = $data['tujuan_penggunaan_informasi'] ?? '';
        $pdf->Cell(50, 6, 'Tujuan Penggunaan', 0, 0, 'L');
        $pdf->Cell(5, 6, ':', 0, 0, 'L');
        $pdf->Cell(0, 6, $tujuan_penggunaan, 1, 1, 'L', true);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->Ln(2);

        // Keputusan PPID - DITOLAK
        $pdf->SetFont('times', '', 12);
        $pdf->Cell(0, 6, 'Keputusan PPID', 0, 1, 'C');
        $pdf->SetFont('times', 'B', 12);
        $pdf->Cell(0, 6, 'PERMOHONAN DITOLAK', 0, 1, 'C');
        $pdf->SetFont('times', '', 12);
        $pdf->Ln(2);

        // Alasan Penolakan
        $alasan_penolakan = !empty($data['alasan_penolakan']) ? $data['alasan_penolakan'] : 'Belum dikuasai informasi';
        $pdf->Cell(50, 6, 'Alasan Penolakan', 0, 0, 'L');
        $pdf->Cell(5, 6, ':', 0, 0, 'L');
        $pdf->SetFillColor(255, 255, 204);
        $pdf->Cell(0, 6, $alasan_penolakan, 1, 1, 'L', true);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->Ln(1);

        // Catatan Petugas
        if (!empty($data['catatan_petugas'])) {
            $pdf->Cell(50, 6, 'Catatan Petugas', 0, 0, 'L');
            $pdf->Cell(5, 6, ':', 0, 0, 'L');

            // Simpan posisi Y sebelum MultiCell
            $current_y = $pdf->GetY();
            $current_x = $pdf->GetX();

            // Gunakan MultiCell untuk text wrapping
            $pdf->SetFillColor(211, 211, 211);
            $pdf->MultiCell(0, 6, $data['catatan_petugas'], 1, 'L', true);
            $pdf->SetFillColor(255, 255, 255);
        }

        $pdf->Ln(2);

        // Cara Memperoleh Informasi
        $pdf->Cell(50, 6, 'Cara Memperoleh Informasi', 0, 0, 'L');
        $pdf->Cell(5, 6, ':', 0, 0, 'L');
        $pdf->SetFont('dejavusans', '', 12);
        $pdf->Cell(10, 6, '☐', 0, 0, 'L');
        $pdf->SetFont('times', '', 12);
        $pdf->Cell(0, 6, 'Melihat/Membaca/Mendengarkan/Mencatat', 0, 1, 'L');

        $pdf->Cell(55, 6, '', 0, 0, 'L');
        $pdf->SetFont('dejavusans', '', 12);
        $pdf->Cell(10, 6, '☑', 0, 0, 'L');
        $pdf->SetFont('times', '', 12);
        $pdf->Cell(0, 6, 'Mendapatkan Salinan Informasi (Hard Copy / Soft Copy)', 0, 1, 'L');

        $pdf->Ln(10);
    }

    private function getIndonesianDate() {
        $hari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        $bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        return $hari[date('N') - 1] . ', ' . date('d') . ' ' . $bulan[date('n') - 1] . ' ' . date('Y');
    }
}
?>