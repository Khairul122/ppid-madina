<?php
require_once 'models/BerandaModel.php';

class BerandaController {
    private $berandaModel;

    public function __construct() {
        // Tidak perlu cek login untuk halaman beranda
        global $database;
        $db = null;

        // Jika database tersedia, gunakan koneksi
        if (isset($database)) {
            $db = $database->getConnection();
        }

        $this->berandaModel = new BerandaModel($db);
    }

    // Method untuk menampilkan halaman beranda
    public function index() {
        try {
            // Ambil semua data yang diperlukan untuk beranda
            $data = [
                'slider' => $this->berandaModel->getSliderData(),
                'layanan' => $this->berandaModel->getLayananData(),
                'informasi' => $this->berandaModel->getInformasiData(),
                'statistik' => $this->berandaModel->getStatistikData(),
                'berita' => $this->berandaModel->getBeritaData(),
                'kontak' => $this->berandaModel->getKontakData(),
                'quick_links' => $this->berandaModel->getQuickLinksData()
            ];

            // Get status counts for pie chart
            $statusCounts = $this->berandaModel->getStatusCounts();

            // Get monthly data for area chart
            $monthlyData = $this->berandaModel->getMonthlyPermohonan();
            $monthlyDataJson = json_encode($monthlyData['total']);
            $monthlySelesaiJson = json_encode($monthlyData['selesai']);

            // Set page info
            $pageInfo = [
                'title' => 'PPID Mandailing Natal - Beranda',
                'description' => 'Pejabat Pengelola Informasi dan Dokumentasi Kabupaten Mandailing Natal - Melayani Transparansi Informasi Publik',
                'keywords' => 'PPID, Mandailing Natal, Informasi Publik, Transparansi, Pemerintahan'
            ];

            // Include view
            include 'views/beranda/index.php';

        } catch (Exception $e) {
            error_log("ERROR in BerandaController::index: " . $e->getMessage());

            // Fallback ke data minimal jika terjadi error
            $data = [
                'slider' => [],
                'layanan' => [],
                'informasi' => [],
                'statistik' => [],
                'berita' => [],
                'kontak' => [],
                'quick_links' => []
            ];

            // Default status counts
            $statusCounts = [
                'selesai' => 0,
                'disposisi' => 0,
                'proses' => 0
            ];

            $monthlyDataJson = json_encode([0,0,0,0,0,0,0,0,0,0,0,0]);
            $monthlySelesaiJson = json_encode([0,0,0,0,0,0,0,0,0,0,0,0]);

            $pageInfo = [
                'title' => 'PPID Mandailing Natal',
                'description' => 'Pejabat Pengelola Informasi dan Dokumentasi',
                'keywords' => 'PPID, Mandailing Natal'
            ];

            include 'views/beranda/index.php';
        }
    }

    // Method untuk AJAX load more berita
    public function loadMoreBerita() {
        header('Content-Type: application/json');

        try {
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 6;

            // Gunakan data dinamis dari database
            $offset = ($page - 1) * $limit;
            $berita = $this->berandaModel->getBeritaData($limit + 1); // +1 untuk cek hasMore

            $hasMore = count($berita) > $limit;
            if ($hasMore) {
                array_pop($berita); // Remove extra item
            }

            echo json_encode([
                'success' => true,
                'data' => $berita,
                'hasMore' => $hasMore,
                'currentPage' => $page,
                'totalItems' => count($berita)
            ]);

        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Gagal memuat berita: ' . $e->getMessage()
            ]);
        }
        exit();
    }

    // Method untuk search berita
    public function searchBerita() {
        header('Content-Type: application/json');

        try {
            $keyword = isset($_GET['q']) ? trim($_GET['q']) : '';

            if (empty($keyword)) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Kata kunci pencarian tidak boleh kosong'
                ]);
                exit();
            }

            // Search berita dari database
            $results = $this->searchBeritaInDatabase($keyword);

            echo json_encode([
                'success' => true,
                'data' => array_values($results),
                'keyword' => $keyword,
                'totalResults' => count($results)
            ]);

        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Gagal melakukan pencarian: ' . $e->getMessage()
            ]);
        }
        exit();
    }

    // Method untuk mendapatkan statistik terbaru (AJAX)
    public function getStatistik() {
        header('Content-Type: application/json');

        try {
            $statistik = $this->berandaModel->getStatistikData();

            echo json_encode([
                'success' => true,
                'data' => $statistik,
                'timestamp' => date('Y-m-d H:i:s')
            ]);

        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Gagal memuat statistik: ' . $e->getMessage()
            ]);
        }
        exit();
    }

    // Method untuk mendapatkan informasi kontak
    public function getKontak() {
        header('Content-Type: application/json');

        try {
            $kontak = $this->berandaModel->getKontakData();

            echo json_encode([
                'success' => true,
                'data' => $kontak
            ]);

        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Gagal memuat informasi kontak: ' . $e->getMessage()
            ]);
        }
        exit();
    }

    // Method untuk download informasi publik
    public function downloadInformasi() {
        try {
            $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

            if ($id <= 0) {
                header('HTTP/1.0 404 Not Found');
                echo "File tidak ditemukan";
                exit();
            }

            // Get informasi dari database
            $informasi = $this->getInformasiById($id);

            if (!$informasi) {
                header('HTTP/1.0 404 Not Found');
                echo "File tidak ditemukan";
                exit();
            }

            // Redirect ke file atau tampilkan informasi
            echo "Download informasi: " . $informasi['title'];
            exit();

        } catch (Exception $e) {
            header('HTTP/1.0 500 Internal Server Error');
            echo "Terjadi kesalahan saat download file";
            exit();
        }
    }

    // Helper method untuk search berita di database
    private function searchBeritaInDatabase($keyword) {
        try {
            global $database;
            if (!$database) {
                return [];
            }
            $conn = $database->getConnection();

            $query = "SELECT id_berita as id, judul as title, summary, image,
                             'Admin PPID' as author,
                             DATE_FORMAT(created_at, '%Y-%m-%d') as published_at,
                             'Berita' as category,
                             0 as views
                      FROM berita
                      WHERE judul LIKE :keyword OR summary LIKE :keyword
                      ORDER BY created_at DESC
                      LIMIT 20";

            $stmt = $conn->prepare($query);
            $searchKeyword = "%$keyword%";
            $stmt->bindParam(':keyword', $searchKeyword);
            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Process image paths
            foreach ($results as &$item) {
                if (empty($item['image']) || !file_exists($item['image'])) {
                    $item['image'] = 'ppid_assets/images/default-news.png';
                }
            }

            return $results;
        } catch (Exception $e) {
            error_log("Error searching berita: " . $e->getMessage());
            return [];
        }
    }

    // Helper method untuk mendapatkan informasi berdasarkan ID
    private function getInformasiById($id) {
        try {
            global $database;
            if (!$database) {
                return null;
            }
            $conn = $database->getConnection();

            $query = "SELECT id_permohonan as id, kategori_informasi as category,
                             rincian_informasi as title, tujuan_penggunaan as description
                      FROM permohonan
                      WHERE id_permohonan = :id AND status_permohonan = 'Selesai'
                      LIMIT 1";

            $stmt = $conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting informasi: " . $e->getMessage());
            return null;
        }
    }

    // Method untuk mendapatkan statistik dokumen
    public function getStatistikDokumen() {
        header('Content-Type: application/json');

        try {
            global $database;
            if (!$database) {
                throw new Exception("Database tidak tersedia");
            }
            $conn = $database->getConnection();

            // Total dokumen per kategori
            $query = "SELECT k.nama_kategori, COUNT(d.id_dokumen) as count 
                     FROM kategori k 
                     LEFT JOIN dokumen d ON k.id_kategori = d.id_kategori AND d.status = 'publikasi'
                     GROUP BY k.id_kategori, k.nama_kategori";
            
            $stmt = $conn->prepare($query);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $statistik = [
                'total_dokumen' => 0,
                'kategori' => []
            ];

            foreach ($results as $result) {
                $kategori = strtolower($result['nama_kategori']);
                $statistik['kategori'][$kategori] = $result['count'];
                $statistik['total_dokumen'] += $result['count'];
            }

            echo json_encode([
                'success' => true,
                'data' => $statistik
            ]);

        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Gagal memuat statistik dokumen: ' . $e->getMessage()
            ]);
        }
        exit();
    }

    // Method untuk mendapatkan statistik permohonan
    public function getStatistikPermohonan() {
        header('Content-Type: application/json');

        try {
            global $database;
            if (!$database) {
                throw new Exception("Database tidak tersedia");
            }
            $conn = $database->getConnection();

            $query = "SELECT COUNT(*) as total FROM permohonan";
            $stmt = $conn->prepare($query);
            $stmt->execute();
            $totalPermohonan = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

            $querySelesai = "SELECT COUNT(*) as selesai FROM permohonan WHERE status = 'Selesai'";
            $stmtSelesai = $conn->prepare($querySelesai);
            $stmtSelesai->execute();
            $permohonanSelesai = $stmtSelesai->fetch(PDO::FETCH_ASSOC)['selesai'];

            $queryPemohon = "SELECT COUNT(*) as total FROM users WHERE role = 'masyarakat'";
            $stmtPemohon = $conn->prepare($queryPemohon);
            $stmtPemohon->execute();
            $totalPemohon = $stmtPemohon->fetch(PDO::FETCH_ASSOC)['total'];

            echo json_encode([
                'success' => true,
                'data' => [
                    'total_permohonan' => $totalPermohonan,
                    'permohonan_selesai' => $permohonanSelesai,
                    'total_pemohon' => $totalPemohon
                ]
            ]);

        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Gagal memuat statistik permohonan: ' . $e->getMessage()
            ]);
        }
        exit();
    }
}
?>