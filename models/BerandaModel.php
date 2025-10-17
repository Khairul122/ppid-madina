<?php
class BerandaModel {
    private $conn;

    public function __construct($db = null) {
        $this->conn = $db;
    }

    // Method untuk mendapatkan data slider dari database banner
    public function getSliderData() {
        if (!$this->conn) {
            return [];
        }

        $query = "SELECT id_banner as id, upload as image, judul as title, teks as description, urutan as sort_order
                  FROM benner
                  WHERE upload IS NOT NULL AND upload != ''
                  ORDER BY urutan ASC, id_banner DESC";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $banners = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $sliderData = [];
            foreach ($banners as $banner) {
                $sliderData[] = [
                    'id' => $banner['id'],
                    'image' => $banner['image'] ?: 'ppid_assets/images/default-banner.png',
                ];
            }

            return $sliderData;
        } catch (PDOException $e) {
            error_log("Database error in getSliderData: " . $e->getMessage());
            return [];
        }
    }

    // Method untuk mendapatkan data layanan informasi publik (tetap statis karena tidak ada tabel khusus)
    public function getLayananData() {
        return [
            [
                'id' => 1,
                'icon' => 'fas fa-file-alt',
                'title' => 'Permohonan Informasi',
                'description' => 'Ajukan permohonan informasi publik secara online dengan mudah dan cepat.',
                'color' => 'primary',
                'link' => 'index.php?controller=auth&action=register'
            ],
            [
                'id' => 2,
                'icon' => 'fas fa-clock',
                'title' => 'Lacak Status',
                'description' => 'Pantau status permohonan informasi Anda secara real-time.',
                'color' => 'success',
                'link' => 'index.php?controller=auth&action=login'
            ],
            [
                'id' => 3,
                'icon' => 'fas fa-download',
                'title' => 'Download Dokumen',
                'description' => 'Unduh berbagai dokumen informasi publik yang tersedia.',
                'color' => 'info',
                'link' => '#informasi'
            ],
            [
                'id' => 4,
                'icon' => 'fas fa-headset',
                'title' => 'Bantuan Online',
                'description' => 'Dapatkan bantuan dan konsultasi terkait informasi publik.',
                'color' => 'warning',
                'link' => '#kontak'
            ],
            [
                'id' => 5,
                'icon' => 'fas fa-balance-scale',
                'title' => 'Keberatan Informasi',
                'description' => 'Ajukan keberatan jika permohonan informasi ditolak.',
                'color' => 'danger',
                'link' => 'index.php?controller=auth&action=register'
            ],
            [
                'id' => 6,
                'icon' => 'fas fa-chart-bar',
                'title' => 'Laporan Berkala',
                'description' => 'Akses laporan berkala dan statistik layanan PPID.',
                'color' => 'secondary',
                'link' => '#statistik'
            ]
        ];
    }

    // Method untuk mendapatkan data informasi publik dari database permohonan yang sudah selesai
    public function getInformasiData() {
        if (!$this->conn) {
            return [];
        }

        $query = "SELECT p.id_permohonan as id, p.kategori_informasi as category,
                         p.rincian_informasi as title, p.tujuan_penggunaan as description,
                         'PDF' as file_type, '- MB' as file_size, 0 as download_count,
                         DATE_FORMAT(p.updated_at, '%Y-%m-%d') as updated_at
                  FROM permohonan p
                  WHERE p.status_permohonan = 'Selesai'
                  ORDER BY p.updated_at DESC
                  LIMIT 10";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $informasi = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $informasi;
        } catch (PDOException $e) {
            error_log("Database error in getInformasiData: " . $e->getMessage());
            return [];
        }
    }

    // Method untuk mendapatkan data statistik dari database
    public function getStatistikData() {
        if (!$this->conn) {
            return [];
        }

        try {
            // Total permohonan
            $totalQuery = "SELECT COUNT(*) as total FROM permohonan";
            $totalStmt = $this->conn->prepare($totalQuery);
            $totalStmt->execute();
            $totalPermohonan = $totalStmt->fetch(PDO::FETCH_ASSOC)['total'];

            // Permohonan selesai
            $selesaiQuery = "SELECT COUNT(*) as selesai FROM permohonan WHERE status_permohonan = 'Selesai'";
            $selesaiStmt = $this->conn->prepare($selesaiQuery);
            $selesaiStmt->execute();
            $permohonanSelesai = $selesaiStmt->fetch(PDO::FETCH_ASSOC)['selesai'];

            // Persentase penyelesaian
            $persentase = $totalPermohonan > 0 ? round(($permohonanSelesai / $totalPermohonan) * 100) : 0;

            // Permohonan bulan ini
            $bulanIniQuery = "SELECT COUNT(*) as bulan_ini FROM permohonan WHERE MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())";
            $bulanIniStmt = $this->conn->prepare($bulanIniQuery);
            $bulanIniStmt->execute();
            $permohonanBulanIni = $bulanIniStmt->fetch(PDO::FETCH_ASSOC)['bulan_ini'];

            return [
                [
                    'label' => 'Total Permohonan',
                    'value' => $totalPermohonan,
                    'icon' => 'fas fa-file-alt',
                    'color' => 'primary',
                    'growth' => '+' . $permohonanBulanIni,
                    'description' => 'Bulan ini'
                ],
                [
                    'label' => 'Permohonan Selesai',
                    'value' => $permohonanSelesai,
                    'icon' => 'fas fa-check-circle',
                    'color' => 'success',
                    'growth' => $persentase . '%',
                    'description' => 'Tingkat penyelesaian'
                ],
                [
                    'label' => 'Permohonan Proses',
                    'value' => $totalPermohonan - $permohonanSelesai,
                    'icon' => 'fas fa-clock',
                    'color' => 'info',
                    'growth' => 'Aktif',
                    'description' => 'Sedang diproses'
                ],
                [
                    'label' => 'Keberatan',
                    'value' => $this->getKeberatanCount(),
                    'icon' => 'fas fa-balance-scale',
                    'color' => 'warning',
                    'growth' => 'Total',
                    'description' => 'Pengajuan keberatan'
                ]
            ];
        } catch (PDOException $e) {
            error_log("Database error in getStatistikData: " . $e->getMessage());
            return [];
        }
    }

    // Helper method untuk mendapatkan jumlah keberatan
    private function getKeberatanCount() {
        try {
            $query = "SELECT COUNT(*) as total FROM keberatan";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        } catch (PDOException $e) {
            return 0;
        }
    }

    // Method untuk mendapatkan data berita dari database
    public function getBeritaData($limit = 6) {
        if (!$this->conn) {
            return [];
        }

        $query = "SELECT id_berita as id, judul as title, summary, image,
                         'Admin PPID' as author,
                         DATE_FORMAT(created_at, '%Y-%m-%d') as published_at,
                         'Berita' as category,
                         0 as views
                  FROM berita
                  ORDER BY created_at DESC
                  LIMIT :limit";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            $berita = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Process image paths
            foreach ($berita as &$item) {
                if (empty($item['image']) || !file_exists($item['image'])) {
                    $item['image'] = 'ppid_assets/images/default-news.png';
                }
            }

            return $berita;
        } catch (PDOException $e) {
            error_log("Database error in getBeritaData: " . $e->getMessage());
            return [];
        }
    }

    // Method untuk mendapatkan data kontak dari database dan sosial media
    public function getKontakData() {
        $kontak = [
            'alamat' => 'Komplek Perkantoran Payaloting, Parbangunan - Kecamatan Panyabungan, Kabupaten Mandailing Natal, Provinsi Sumatera Utara, Kode Pos 2297',
            'telepon' => '(0636) 21234',
            'fax' => '(0636) 21235',
            'email' => 'ppid@madina.go.id',
            'website' => 'https://ppid.madinakab.go.id',
            'jam_operasional' => [
                'senin_kamis' => '08:00 - 16:00 WIB',
                'jumat' => '08:00 - 16:30 WIB',
                'weekend' => 'Tutup'
            ],
            'media_sosial' => []
        ];

        // Get profile data from database if available
        if ($this->conn) {
            try {
                $profileQuery = "SELECT * FROM profile LIMIT 1";
                $profileStmt = $this->conn->prepare($profileQuery);
                $profileStmt->execute();
                $profile = $profileStmt->fetch(PDO::FETCH_ASSOC);

                if ($profile) {
                    // Tabel profile menggunakan struktur keterangan-isi, tidak ada kolom alamat/telepon/email langsung
                    // Bisa dikembangkan untuk mengambil data berdasarkan keterangan
                    // Untuk saat ini, tetap gunakan data default
                }

                // Get social media data
                $sosmedQuery = "SELECT * FROM sosial_media ORDER BY created_at ASC";
                $sosmedStmt = $this->conn->prepare($sosmedQuery);
                $sosmedStmt->execute();
                $sosialMedia = $sosmedStmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($sosialMedia as $sosmed) {
                    // Membangun data sosial media berdasarkan kolom yang sebenarnya ada di database
                    // Kolom yang tersedia: site, facebook_link, instagram_link, instagram_post
                    if (!empty($sosmed['site'])) {
                        $platform = $sosmed['site'];
                        $url = '';
                        
                        // Menentukan URL berdasarkan platform
                        if (strtolower($platform) === 'facebook' && !empty($sosmed['facebook_link'])) {
                            $url = $sosmed['facebook_link'];
                        } elseif (strtolower($platform) === 'instagram' && !empty($sosmed['instagram_link'])) {
                            $url = $sosmed['instagram_link'];
                        } elseif (strtolower($platform) === 'instagram_post' && !empty($sosmed['instagram_post'])) {
                            $url = $sosmed['instagram_post'];
                        } elseif (empty($url) && !empty($sosmed['facebook_link'])) {
                            $url = $sosmed['facebook_link'];
                        } elseif (empty($url) && !empty($sosmed['instagram_link'])) {
                            $url = $sosmed['instagram_link'];
                        }
                        
                        $kontak['media_sosial'][] = [
                            'platform' => $platform,
                            'icon' => $this->getSocialMediaIcon($platform),
                            'url' => $url ?: '#',
                            'followers' => $sosmed['followers'] ?? '0'
                        ];
                    }
                }

            } catch (PDOException $e) {
                error_log("Database error in getKontakData: " . $e->getMessage());
            }
        }

        // Fallback social media if database is empty
        if (empty($kontak['media_sosial'])) {
            $kontak['media_sosial'] = [
                [
                    'platform' => 'Facebook',
                    'icon' => 'fab fa-facebook-f',
                    'url' => 'https://facebook.com/ppidmadina',
                    'followers' => '0'
                ],
                [
                    'platform' => 'Instagram',
                    'icon' => 'fab fa-instagram',
                    'url' => 'https://instagram.com/ppidmadina',
                    'followers' => '0'
                ]
            ];
        }

        return $kontak;
    }

    // Helper method untuk mendapatkan icon media sosial
    private function getSocialMediaIcon($platform) {
        $icons = [
            'Facebook' => 'fab fa-facebook-f',
            'Instagram' => 'fab fa-instagram',
            'Twitter' => 'fab fa-twitter',
            'YouTube' => 'fab fa-youtube',
            'WhatsApp' => 'fab fa-whatsapp',
            'Telegram' => 'fab fa-telegram'
        ];

        return $icons[$platform] ?? 'fas fa-link';
    }

    // Method untuk mendapatkan data quick links (tetap statis)
    public function getQuickLinksData() {
        return [
            [
                'title' => 'Formulir Permohonan',
                'description' => 'Download formulir permohonan informasi',
                'icon' => 'fas fa-file-download',
                'link' => 'index.php?controller=auth&action=register'
            ],
            [
                'title' => 'Alur Permohonan',
                'description' => 'Panduan alur permohonan informasi',
                'icon' => 'fas fa-route',
                'link' => '#layanan'
            ],
            [
                'title' => 'Daftar Informasi Publik',
                'description' => 'Katalog informasi yang tersedia',
                'icon' => 'fas fa-list',
                'link' => '#informasi'
            ],
            [
                'title' => 'Standar Layanan',
                'description' => 'Standar pelayanan informasi publik',
                'icon' => 'fas fa-award',
                'link' => '#statistik'
            ]
        ];
    }

    // Method untuk mendapatkan statistik dokumen
    public function getStatistikDokumen() {
        if (!$this->conn) {
            return [
                'total_dokumen' => 0,
                'kategori' => []
            ];
        }

        try {
            // Total dokumen per kategori
            $query = "SELECT k.nama_kategori, COUNT(d.id_dokumen) as count 
                     FROM kategori k 
                     LEFT JOIN dokumen d ON k.id_kategori = d.id_kategori AND d.status = 'publikasi'
                     GROUP BY k.id_kategori, k.nama_kategori";
            
            $stmt = $this->conn->prepare($query);
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

            return $statistik;
        } catch (PDOException $e) {
            error_log("Database error in getStatistikDokumen: " . $e->getMessage());
            return [
                'total_dokumen' => 0,
                'kategori' => []
            ];
        }
    }

    // Method untuk mendapatkan statistik permohonan
    public function getStatistikPermohonan() {
        if (!$this->conn) {
            return [
                'total_permohonan' => 0,
                'permohonan_selesai' => 0,
                'total_pemohon' => 0
            ];
        }

        try {
            $queryTotal = "SELECT COUNT(*) as total FROM permohonan";
            $stmtTotal = $this->conn->prepare($queryTotal);
            $stmtTotal->execute();
            $totalPermohonan = $stmtTotal->fetch(PDO::FETCH_ASSOC)['total'];

            $querySelesai = "SELECT COUNT(*) as selesai FROM permohonan WHERE status = 'publikasi'";
            $stmtSelesai = $this->conn->prepare($querySelesai);
            $stmtSelesai->execute();
            $permohonanSelesai = $stmtSelesai->fetch(PDO::FETCH_ASSOC)['selesai'];

            $queryPemohon = "SELECT COUNT(*) as total FROM users WHERE role = 'masyarakat'";
            $stmtPemohon = $this->conn->prepare($queryPemohon);
            $stmtPemohon->execute();
            $totalPemohon = $stmtPemohon->fetch(PDO::FETCH_ASSOC)['total'];

            return [
                'total_permohonan' => $totalPermohonan,
                'permohonan_selesai' => $permohonanSelesai,
                'total_pemohon' => $totalPemohon
            ];
        } catch (PDOException $e) {
            error_log("Database error in getStatistikPermohonan: " . $e->getMessage());
            return [
                'total_permohonan' => 0,
                'permohonan_selesai' => 0,
                'total_pemohon' => 0
            ];
        }
    }

    // Method untuk mendapatkan data galeri foto terbaru
    public function getGaleriFoto($limit = 6) {
        if (!$this->conn) {
            return [];
        }

        try {
            $query = "SELECT id_album, kategori, nama_album, upload, created_at
                     FROM album
                     WHERE kategori = 'foto'
                     ORDER BY created_at DESC
                     LIMIT :limit";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Process image paths
            foreach ($results as &$item) {
                if (empty($item['upload']) || !file_exists($item['upload'])) {
                    $item['upload'] = 'ppid_assets/images/default-gallery.jpg';
                }
            }

            return $results;
        } catch (PDOException $e) {
            error_log("Database error in getGaleriFoto: " . $e->getMessage());
            return [];
        }
    }

    // Method untuk mendapatkan status counts permohonan
    public function getStatusCounts() {
        if (!$this->conn) {
            return [
                'selesai' => 0,
                'disposisi' => 0,
                'proses' => 0
            ];
        }

        try {
            // Mapping status dari database ke kategori chart
            $statusMapping = [
                'Selesai' => 'selesai',
                'Diterima' => 'selesai',
                'Disposisi' => 'disposisi',
                'Diproses' => 'proses',
                'Ditolak' => 'proses'
            ];

            $counts = [
                'selesai' => 0,
                'disposisi' => 0,
                'proses' => 0
            ];

            $query = "SELECT status, COUNT(*) as count FROM permohonan GROUP BY status";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($results as $result) {
                $status = trim($result['status']);
                $count = (int)$result['count'];

                // Map status ke kategori
                if (isset($statusMapping[$status])) {
                    $kategori = $statusMapping[$status];
                    $counts[$kategori] += $count;
                } else {
                    // Default ke proses jika status tidak diketahui
                    $counts['proses'] += $count;
                }
            }

            return $counts;
        } catch (PDOException $e) {
            error_log("Database error in getStatusCounts: " . $e->getMessage());
            return [
                'selesai' => 0,
                'disposisi' => 0,
                'proses' => 0
            ];
        }
    }

    // Method untuk mendapatkan data permohonan per bulan (12 bulan terakhir)
    public function getMonthlyPermohonan() {
        if (!$this->conn) {
            return [
                'total' => array_fill(0, 12, 0),
                'selesai' => array_fill(0, 12, 0)
            ];
        }

        try {
            // Array untuk menyimpan data 12 bulan
            $monthlyTotal = array_fill(0, 12, 0);
            $monthlySelesai = array_fill(0, 12, 0);

            // Get current year
            $currentYear = date('Y');

            // Query untuk total permohonan per bulan
            $queryTotal = "SELECT MONTH(created_at) as month, COUNT(*) as count
                          FROM permohonan
                          WHERE YEAR(created_at) = :year
                          GROUP BY MONTH(created_at)";

            $stmtTotal = $this->conn->prepare($queryTotal);
            $stmtTotal->bindParam(':year', $currentYear);
            $stmtTotal->execute();
            $resultsTotal = $stmtTotal->fetchAll(PDO::FETCH_ASSOC);

            foreach ($resultsTotal as $result) {
                $month = (int)$result['month'] - 1; // Array index 0-11
                $monthlyTotal[$month] = (int)$result['count'];
            }

            // Query untuk permohonan selesai per bulan
            $querySelesai = "SELECT MONTH(created_at) as month, COUNT(*) as count
                            FROM permohonan
                            WHERE YEAR(created_at) = :year
                            AND (status = 'Selesai' OR status = 'Diterima')
                            GROUP BY MONTH(created_at)";

            $stmtSelesai = $this->conn->prepare($querySelesai);
            $stmtSelesai->bindParam(':year', $currentYear);
            $stmtSelesai->execute();
            $resultsSelesai = $stmtSelesai->fetchAll(PDO::FETCH_ASSOC);

            foreach ($resultsSelesai as $result) {
                $month = (int)$result['month'] - 1; // Array index 0-11
                $monthlySelesai[$month] = (int)$result['count'];
            }

            return [
                'total' => $monthlyTotal,
                'selesai' => $monthlySelesai
            ];
        } catch (PDOException $e) {
            error_log("Database error in getMonthlyPermohonan: " . $e->getMessage());
            return [
                'total' => array_fill(0, 12, 0),
                'selesai' => array_fill(0, 12, 0)
            ];
        }
    }
}
?>