<?php
require_once 'models/WAGatewayModel.php';

class WAGatewayController {
    private $model;

    public function __construct() {
        $this->model = new WAGatewayModel();
    }

    public function index() {
        $stats = $this->model->getMessageStats();
        $recentMessages = $this->model->getAllMessages();

        // Limit recent messages to 10
        $recentMessages = array_slice($recentMessages, 0, 10);

        include 'views/wagateway/index.php';
    }

    public function pesan_keluar() {
        $messages = $this->model->getMessagesByStatus('terkirim');

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            if(isset($_POST['action'])) {
                switch($_POST['action']) {
                    case 'send_message':
                        $this->sendMessage();
                        break;
                    case 'archive_message':
                        $this->archiveMessage($_POST['id']);
                        break;
                    case 'delete_message':
                        $this->deleteMessage($_POST['id']);
                        break;
                }
                header('Location: index.php?controller=wagateway&action=pesan_keluar');
                exit;
            }
        }

        include 'views/wagateway/pesan_keluar.php';
    }

    public function draft() {
        $drafts = $this->model->getMessagesByStatus('terjadwal');

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            if(isset($_POST['action'])) {
                switch($_POST['action']) {
                    case 'save_draft':
                        $this->saveDraft();
                        break;
                    case 'send_draft':
                        $this->sendDraft($_POST['id']);
                        break;
                    case 'edit_draft':
                        $this->editDraft($_POST['id']);
                        break;
                    case 'delete_draft':
                        $this->deleteMessage($_POST['id']);
                        break;
                }
                header('Location: index.php?controller=wagateway&action=draft');
                exit;
            }
        }

        $editDraft = null;
        if(isset($_GET['edit'])) {
            $editDraft = $this->model->getMessageById($_GET['edit']);
        }

        include 'views/wagateway/draft.php';
    }

    public function arsip() {
        $archives = $this->model->getMessagesByStatus('arsip');

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            if(isset($_POST['action'])) {
                switch($_POST['action']) {
                    case 'restore_message':
                        $this->restoreMessage($_POST['id']);
                        break;
                    case 'delete_permanent':
                        $this->deleteMessage($_POST['id']);
                        break;
                }
                header('Location: index.php?controller=wagateway&action=arsip');
                exit;
            }
        }

        include 'views/wagateway/arsip.php';
    }

    private function sendMessage() {
        $no_tujuan = $_POST['no_tujuan'] ?? '';
        $pesan = $_POST['pesan'] ?? '';

        if(empty($no_tujuan) || empty($pesan)) {
            $_SESSION['error'] = 'Nomor tujuan dan pesan tidak boleh kosong';
            return;
        }

        $result = $this->model->sendMessageAndSave($no_tujuan, $pesan);

        if($result['success']) {
            $_SESSION['success'] = $result['message'];
        } else {
            $_SESSION['error'] = $result['message'];
        }
    }


    private function saveDraft() {
        $no_tujuan = $_POST['no_tujuan'] ?? '';
        $pesan = $_POST['pesan'] ?? '';

        if(empty($no_tujuan) || empty($pesan)) {
            $_SESSION['error'] = 'Nomor tujuan dan pesan tidak boleh kosong';
            return;
        }

        if(isset($_POST['draft_id']) && !empty($_POST['draft_id'])) {
            // Update existing draft
            $result = $this->model->updateMessage($_POST['draft_id'], $no_tujuan, $pesan, 'terjadwal');
        } else {
            // Create new draft
            $result = $this->model->createMessage($no_tujuan, $pesan, 'terjadwal');
        }

        if($result) {
            $_SESSION['success'] = 'Draft berhasil disimpan';
        } else {
            $_SESSION['error'] = 'Gagal menyimpan draft';
        }
    }

    private function sendDraft($id) {
        $draft = $this->model->getMessageById($id);
        if($draft) {
            $result = $this->model->sendWhatsAppMessage($draft['no_tujuan'], $draft['pesan']);

            if($result && isset($result['success']) && $result['success']) {
                $this->model->updateStatus($id, 'terkirim');
                $_SESSION['success'] = 'Draft berhasil dikirim';
            } else {
                $this->model->updateStatus($id, 'gagal');
                $_SESSION['error'] = 'Gagal mengirim draft';
            }
        }
    }

    private function editDraft($id) {
        header('Location: index.php?controller=wagateway&action=draft&edit=' . $id);
        exit;
    }

    private function archiveMessage($id) {
        $result = $this->model->updateStatus($id, 'arsip');
        if($result) {
            $_SESSION['success'] = 'Pesan berhasil diarsipkan';
        } else {
            $_SESSION['error'] = 'Gagal mengarsipkan pesan';
        }
    }

    private function restoreMessage($id) {
        $result = $this->model->updateStatus($id, 'terkirim');
        if($result) {
            $_SESSION['success'] = 'Pesan berhasil dipulihkan';
        } else {
            $_SESSION['error'] = 'Gagal memulihkan pesan';
        }
    }

    private function deleteMessage($id) {
        $result = $this->model->deleteMessage($id);
        if($result) {
            $_SESSION['success'] = 'Pesan berhasil dihapus';
        } else {
            $_SESSION['error'] = 'Gagal menghapus pesan';
        }
    }

    public function getAccountInfo() {
        header('Content-Type: application/json');
        $result = $this->model->getAccountInfo();
        echo json_encode($result);
        exit;
    }

    public function getQRCode() {
        header('Content-Type: application/json');
        $result = $this->model->getQRCode();
        echo json_encode($result);
        exit;
    }

    public function sendMessageAjax() {
        header('Content-Type: application/json');

        $no_tujuan = $_POST['no_tujuan'] ?? '';
        $pesan = $_POST['pesan'] ?? '';

        if(empty($no_tujuan) || empty($pesan)) {
            echo json_encode(['success' => false, 'message' => 'Nomor tujuan dan pesan tidak boleh kosong']);
            exit;
        }

        $result = $this->model->sendMessageAndSave($no_tujuan, $pesan);
        echo json_encode($result);
        exit;
    }

    public function getMessageStats() {
        header('Content-Type: application/json');
        $stats = $this->model->getMessageStats();
        echo json_encode($stats);
        exit;
    }

    public function testConnection() {
        header('Content-Type: application/json');
        $result = $this->model->testConnection();
        echo json_encode($result);
        exit;
    }

    public function testSendMessage() {
        header('Content-Type: application/json');

        $no_tujuan = $_POST['no_tujuan'] ?? '';
        $pesan = $_POST['pesan'] ?? 'Test message dari WhatsApp Gateway';

        if(empty($no_tujuan)) {
            echo json_encode(['success' => false, 'message' => 'Nomor tujuan tidak boleh kosong']);
            exit;
        }

        $result = $this->model->testSendMessage($no_tujuan, $pesan);

        // Add debug info for troubleshooting
        $result['debug_info'] = [
            'sent_target' => $no_tujuan,
            'sent_message' => $pesan,
            'timestamp' => date('Y-m-d H:i:s')
        ];

        echo json_encode($result, JSON_PRETTY_PRINT);
        exit;
    }

    public function debugLastResponse() {
        header('Content-Type: application/json');

        // Read last few lines from PHP error log to see the API response
        $logFile = ini_get('error_log');
        if(file_exists($logFile)) {
            $lines = file($logFile);
            $lastLines = array_slice($lines, -20); // Get last 20 lines
            $fonteResponses = array_filter($lastLines, function($line) {
                return strpos($line, 'Fonnte API Response:') !== false;
            });

            echo json_encode([
                'success' => true,
                'last_responses' => array_values($fonteResponses),
                'log_file' => $logFile
            ], JSON_PRETTY_PRINT);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Error log file not found',
                'log_file_setting' => $logFile
            ]);
        }
        exit;
    }

    public function getMessageDetail() {
        header('Content-Type: application/json');

        $id = $_POST['id'] ?? $_GET['id'] ?? '';
        if(empty($id)) {
            echo json_encode(['success' => false, 'message' => 'ID pesan tidak ditemukan']);
            exit;
        }

        $message = $this->model->getMessageById($id);
        if($message) {
            echo json_encode([
                'success' => true,
                'data' => $message
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Pesan tidak ditemukan']);
        }
        exit;
    }
}
?>