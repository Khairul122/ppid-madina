<?php
class WAGatewayModel {
    private $conn;
    private $table = "wagateway";

    // Fonnte API Configuration
    private $fonnte_token = "mebMc5vfWw1ZpMbh1n77";
    private $api_url = "https://api.fonnte.com";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAllMessages() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY tanggal_kirim DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMessagesByStatus($status) {
        $query = "SELECT * FROM " . $this->table . " WHERE status = :status ORDER BY tanggal_kirim DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMessageById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id_wagateway = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createMessage($no_tujuan, $pesan, $status = 'terjadwal') {
        $query = "INSERT INTO " . $this->table . " (no_tujuan, pesan, tanggal_kirim, status) VALUES (:no_tujuan, :pesan, NOW(), :status)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':no_tujuan', $no_tujuan);
        $stmt->bindParam(':pesan', $pesan);
        $stmt->bindParam(':status', $status);

        if($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    public function updateMessage($id, $no_tujuan, $pesan, $status) {
        $query = "UPDATE " . $this->table . " SET no_tujuan = :no_tujuan, pesan = :pesan, status = :status WHERE id_wagateway = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':no_tujuan', $no_tujuan);
        $stmt->bindParam(':pesan', $pesan);
        $stmt->bindParam(':status', $status);
        return $stmt->execute();
    }

    public function updateStatus($id, $status) {
        $query = "UPDATE " . $this->table . " SET status = :status WHERE id_wagateway = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':status', $status);
        return $stmt->execute();
    }

    public function deleteMessage($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id_wagateway = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function sendWhatsAppMessage($no_tujuan, $pesan) {
        // Format nomor telepon
        $no_tujuan = $this->formatPhoneNumber($no_tujuan);

        $data = [
            'target' => $no_tujuan,
            'message' => $pesan,
            'countryCode' => '62'
        ];

        $response = $this->makeAPICall('/send', $data);
        return $response;
    }

    public function sendMessageAndSave($no_tujuan, $pesan) {
        // Simpan ke database terlebih dahulu
        $messageId = $this->createMessage($no_tujuan, $pesan, 'terjadwal');

        if($messageId) {
            // Kirim via WhatsApp
            $response = $this->sendWhatsAppMessage($no_tujuan, $pesan);

            if($response && isset($response['success']) && $response['success']) {
                // Update status jika berhasil
                $this->updateStatus($messageId, 'terkirim');
                return ['success' => true, 'message' => 'Pesan berhasil dikirim', 'data' => $response];
            } else {
                // Update status jika gagal
                $this->updateStatus($messageId, 'gagal');
                return ['success' => false, 'message' => 'Gagal mengirim pesan: ' . ($response['message'] ?? 'Unknown error')];
            }
        }

        return ['success' => false, 'message' => 'Gagal menyimpan pesan ke database'];
    }

    public function getAccountInfo() {
        $response = $this->makeAPICall('/device');
        return $response;
    }

    public function getQRCode() {
        $response = $this->makeAPICall('/qr');
        return $response;
    }

    private function formatPhoneNumber($phone) {
        // Remove any non-numeric characters
        $phone = preg_replace('/\D/', '', $phone);

        // Add country code if not present
        if(substr($phone, 0, 2) !== '62') {
            if(substr($phone, 0, 1) === '0') {
                $phone = '62' . substr($phone, 1);
            } else {
                $phone = '62' . $phone;
            }
        }

        return $phone;
    }

    private function makeAPICall($endpoint, $data = []) {
        $url = $this->api_url . $endpoint;

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => [
                'Authorization: ' . $this->fonnte_token
            ],
        ]);

        $response = curl_exec($curl);
        $error_msg = curl_errno($curl) ? curl_error($curl) : null;
        curl_close($curl);

        if($error_msg) {
            return ['success' => false, 'message' => 'CURL Error: ' . $error_msg];
        }

        if(!$response) {
            return ['success' => false, 'message' => 'Empty response from API'];
        }

        $decodedResponse = json_decode($response, true);
        if(json_last_error() !== JSON_ERROR_NONE) {
            return ['success' => false, 'message' => 'Invalid JSON response', 'raw_response' => $response];
        }

        // Debug: Log the actual response to understand the format
        error_log("Fonnte API Response: " . $response);

        // Check multiple possible success indicators
        $isSuccess = false;

        // Check for various success indicators from Fonnte API
        if(isset($decodedResponse['status']) && $decodedResponse['status'] === 'success') {
            $isSuccess = true;
        } elseif(isset($decodedResponse['id']) && !empty($decodedResponse['id'])) {
            // If there's an ID, it usually means message was sent successfully
            $isSuccess = true;
        } elseif(isset($decodedResponse['target']) && isset($decodedResponse['message'])) {
            // If response contains target and message, it's likely successful
            $isSuccess = true;
        }

        if($isSuccess) {
            return ['success' => true, 'data' => $decodedResponse];
        } else {
            $errorMessage = isset($decodedResponse['reason']) ? $decodedResponse['reason'] :
                           (isset($decodedResponse['message']) ? $decodedResponse['message'] :
                           (isset($decodedResponse['error']) ? $decodedResponse['error'] : 'Unknown error'));

            return ['success' => false, 'message' => $errorMessage, 'response' => $decodedResponse, 'raw_response' => $response];
        }
    }

    public function getMessageStats() {
        $stats = [];

        // Total messages
        $query = "SELECT COUNT(*) as total FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['total'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        // Messages by status
        $statuses = ['terkirim', 'terjadwal', 'arsip', 'gagal'];
        foreach($statuses as $status) {
            $query = "SELECT COUNT(*) as count FROM " . $this->table . " WHERE status = :status";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':status', $status);
            $stmt->execute();
            $stats[$status] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        }

        // Messages today
        $query = "SELECT COUNT(*) as today FROM " . $this->table . " WHERE DATE(tanggal_kirim) = CURDATE()";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['today'] = $stmt->fetch(PDO::FETCH_ASSOC)['today'];

        return $stats;
    }

    public function testConnection() {
        $response = $this->makeAPICall('/device');

        if($response['success']) {
            return [
                'success' => true,
                'message' => 'Koneksi berhasil',
                'device_info' => $response['data']
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Koneksi gagal: ' . $response['message'],
                'details' => $response
            ];
        }
    }

    // Method untuk test kirim pesan tanpa save ke database
    public function testSendMessage($no_tujuan, $pesan) {
        $no_tujuan = $this->formatPhoneNumber($no_tujuan);

        $data = [
            'target' => $no_tujuan,
            'message' => $pesan,
            'countryCode' => '62'
        ];

        // Call API and get detailed response for debugging
        $result = $this->makeAPICallWithDebug('/send', $data);
        return $result;
    }

    // Special method for debugging that returns raw response
    private function makeAPICallWithDebug($endpoint, $data = []) {
        $url = $this->api_url . $endpoint;

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => [
                'Authorization: ' . $this->fonnte_token
            ],
        ]);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $error_msg = curl_errno($curl) ? curl_error($curl) : null;
        curl_close($curl);

        // Prepare debug info
        $debugInfo = [
            'url' => $url,
            'sent_data' => $data,
            'http_code' => $httpCode,
            'curl_error' => $error_msg,
            'raw_response' => $response,
            'token_used' => substr($this->fonnte_token, 0, 10) . '...'
        ];

        if($error_msg) {
            return [
                'success' => false,
                'message' => 'CURL Error: ' . $error_msg,
                'debug' => $debugInfo
            ];
        }

        if(!$response) {
            return [
                'success' => false,
                'message' => 'Empty response from API',
                'debug' => $debugInfo
            ];
        }

        $decodedResponse = json_decode($response, true);
        if(json_last_error() !== JSON_ERROR_NONE) {
            return [
                'success' => false,
                'message' => 'Invalid JSON response: ' . json_last_error_msg(),
                'debug' => $debugInfo
            ];
        }

        $debugInfo['parsed_response'] = $decodedResponse;

        // Check for success - let's be more lenient and see what we actually get
        $isSuccess = false;
        $successReason = '';

        if(isset($decodedResponse['status']) && $decodedResponse['status'] === 'success') {
            $isSuccess = true;
            $successReason = 'status=success';
        } elseif(isset($decodedResponse['id']) && !empty($decodedResponse['id'])) {
            $isSuccess = true;
            $successReason = 'has ID';
        } elseif($httpCode === 200 && !isset($decodedResponse['status']) && !isset($decodedResponse['error'])) {
            // If HTTP 200 and no explicit error, consider it success
            $isSuccess = true;
            $successReason = 'HTTP 200 with no error';
        }

        if($isSuccess) {
            return [
                'success' => true,
                'data' => $decodedResponse,
                'success_reason' => $successReason,
                'debug' => $debugInfo
            ];
        } else {
            $errorMessage = isset($decodedResponse['reason']) ? $decodedResponse['reason'] :
                           (isset($decodedResponse['message']) ? $decodedResponse['message'] :
                           (isset($decodedResponse['error']) ? $decodedResponse['error'] : 'No explicit error found'));

            return [
                'success' => false,
                'message' => $errorMessage,
                'debug' => $debugInfo
            ];
        }
    }
}
?>