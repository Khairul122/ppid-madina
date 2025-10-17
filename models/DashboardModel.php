<?php
require_once 'config/koneksi.php';

class DashboardModel
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Mendapatkan statistik dasar
    public function getStats()
    {
        $stats = [];

        try {
            // Jumlah dokumen yang dipublikasi
            $query = "SELECT COUNT(*) as total FROM dokumen WHERE status = 'publikasi'";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $stats['dokumen'] = $result ? (int)$result['total'] : 0;

            // Jumlah permohonan
            $query = "SELECT COUNT(*) as total FROM permohonan";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $stats['permohonan'] = $result ? (int)$result['total'] : 0;

            // Jumlah permohonan yang selesai
            $query = "SELECT COUNT(*) as total FROM permohonan WHERE status = 'Selesai'";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $stats['permohonan_selesai'] = $result ? (int)$result['total'] : 0;

            // Jumlah pengguna masyarakat
            $query = "SELECT COUNT(*) as total FROM users WHERE role = 'masyarakat'";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $stats['pengguna_masyarakat'] = $result ? (int)$result['total'] : 0;
            
            error_log("Dashboard Stats: " . print_r($stats, true));
        } catch (Exception $e) {
            error_log("Dashboard Model Error - getStats: " . $e->getMessage());
            // Jika terjadi error, kembalikan nilai default
            $stats = [
                'dokumen' => 0,
                'permohonan' => 0,
                'permohonan_selesai' => 0,
                'pengguna_masyarakat' => 0
            ];
        }

        return $stats;
    }

    // Mendapatkan data kategori dokumentasi
    public function getKategoriData()
    {
        $kategori_data = [];
        try {
            $query = "SELECT k.nama_kategori, COUNT(d.id_dokumen) as jumlah FROM kategori k LEFT JOIN dokumen d ON k.id_kategori = d.id_kategori AND d.status = 'publikasi' GROUP BY k.id_kategori, k.nama_kategori ORDER BY jumlah DESC LIMIT 8";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $kategori_data[] = [
                    'nama_kategori' => $row['nama_kategori'] ?? 'Tidak Diketahui',
                    'jumlah' => (int)($row['jumlah'] ?? 0)
                ];
            }
            error_log("Kategori Data Count: " . count($kategori_data));
        } catch (Exception $e) {
            error_log("Dashboard Model Error - getKategoriData: " . $e->getMessage());
            // Return empty array jika terjadi error
            $kategori_data = [];
        }

        return $kategori_data;
    }

    // Mendapatkan data status permohonan
    public function getStatusData()
    {
        $status_data = [];
        try {
            $query = "SELECT status, COUNT(*) as jumlah FROM permohonan GROUP BY status ORDER BY jumlah DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $status_data[] = [
                    'status' => $row['status'] ?? 'Tidak Diketahui',
                    'jumlah' => (int)($row['jumlah'] ?? 0)
                ];
            }
            error_log("Status Data Count: " . count($status_data));
        } catch (Exception $e) {
            error_log("Dashboard Model Error - getStatusData: " . $e->getMessage());
            // Return empty array jika terjadi error
            $status_data = [];
        }

        return $status_data;
    }

    // Mendapatkan data 5 permohonan terbaru
    public function getRecentPermohonan()
    {
        $recent_permohonan = [];
        try {
            $query = "SELECT p.*, u.username FROM permohonan p LEFT JOIN users u ON p.id_user = u.id_user ORDER BY p.created_at DESC LIMIT 5";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $recent_permohonan[] = [
                    'id_permohonan' => $row['id_permohonan'] ?? '',
                    'id_user' => $row['id_user'] ?? '',
                    'judul_dokumen' => $row['judul_dokumen'] ?? '',
                    'kandungan_informasi' => $row['kandungan_informasi'] ?? '',
                    'status' => $row['status'] ?? 'Pending',
                    'username' => $row['username'] ?? 'Pengguna',
                    'created_at' => $row['created_at'] ?? date('Y-m-d H:i:s')
                ];
            }
            error_log("Recent Permohonan Count: " . count($recent_permohonan));
        } catch (Exception $e) {
            error_log("Dashboard Model Error - getRecentPermohonan: " . $e->getMessage());
            // Return empty array jika terjadi error
            $recent_permohonan = [];
        }

        return $recent_permohonan;
    }

    // Mendapatkan data 5 berita terbaru
    public function getRecentBerita()
    {
        $recent_berita = [];
        try {
            $query = "SELECT * FROM berita ORDER BY created_at DESC LIMIT 5";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $recent_berita[] = [
                    'id_berita' => $row['id_berita'] ?? '',
                    'judul' => $row['judul'] ?? 'Berita Tanpa Judul',
                    'summary' => $row['summary'] ?? '',
                    'image' => $row['image'] ?? '',
                    'created_at' => $row['created_at'] ?? date('Y-m-d H:i:s')
                ];
            }
            error_log("Recent Berita Count: " . count($recent_berita));
        } catch (Exception $e) {
            error_log("Dashboard Model Error - getRecentBerita: " . $e->getMessage());
            // Return empty array jika terjadi error
            $recent_berita = [];
        }

        return $recent_berita;
    }
}