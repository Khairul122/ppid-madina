<?php
// Get profile menu data
require_once 'models/ProfileModel.php';
require_once 'models/LayananInformasiModel.php';
require_once 'models/InformasiPublikModel.php';
global $database;
$db = $database->getConnection();
$profileModel = new ProfileModel($db);
$profile_menu = $profileModel->getProfilesForNavbar();

// Get layanan informasi menu data
$layananModel = new LayananInformasiModel($db);
$allLayanan = $layananModel->getAllLayanan();

// Group by nama_layanan
$layanan_menu = [];
foreach ($allLayanan as $layanan) {
    $nama = $layanan['nama_layanan'];
    if (!isset($layanan_menu[$nama])) {
        $layanan_menu[$nama] = [];
    }
    if (!empty($layanan['sub_layanan'])) {
        $layanan_menu[$nama][] = [
            'id' => $layanan['id_layanan'],
            'sub_layanan' => $layanan['sub_layanan']
        ];
    } else {
        // Jika tidak ada sub_layanan, simpan ID untuk direct link
        $layanan_menu[$nama]['_direct_id'] = $layanan['id_layanan'];
    }
}

// Get informasi publik menu data
$informasiModel = new InformasiPublikModel($db);
$allInformasi = $informasiModel->getAllInformasi();

// Group by nama_informasi_publik
$informasi_menu = [];
foreach ($allInformasi as $informasi) {
    $nama = $informasi['nama_informasi_publik'];
    if (!isset($informasi_menu[$nama])) {
        $informasi_menu[$nama] = [];
    }
    if (!empty($informasi['sub_informasi_publik'])) {
        $informasi_menu[$nama][] = [
            'id' => $informasi['id_informasi_publik'],
            'sub_informasi_publik' => $informasi['sub_informasi_publik']
        ];
    } else {
        // Jika tidak ada sub_informasi_publik, simpan ID untuk direct link
        $informasi_menu[$nama]['_direct_id'] = $informasi['id_informasi_publik'];
    }
}

// Get dokumen pemda menu data
$query_dokumen = "SELECT dp.id_dokumen_pemda, dp.nama_jenis, k.id_kategori, k.nama_kategori
                  FROM dokumen_pemda dp
                  INNER JOIN kategori k ON dp.id_kategori = k.id_kategori
                  ORDER BY k.nama_kategori ASC, dp.nama_jenis ASC";
$stmt_dokumen = $db->prepare($query_dokumen);
$stmt_dokumen->execute();
$allDokumenPemda = $stmt_dokumen->fetchAll(PDO::FETCH_ASSOC);

// Group by nama_kategori -> nama_jenis
$dokumen_menu = [];
foreach ($allDokumenPemda as $dok) {
    $kategori = $dok['nama_kategori'];
    if (!isset($dokumen_menu[$kategori])) {
        $dokumen_menu[$kategori] = [];
    }
    $dokumen_menu[$kategori][] = [
        'id_kategori' => $dok['id_kategori'],
        'nama_jenis' => $dok['nama_jenis']
    ];
}
?>

<div class="top-info-bar">
    <div class="container">
        <div class="top-info-links">
            <a href="#">TENTANG PPID</a>
            <a href="#">KONTAK PPID</a>
        </div>
        <div class="top-info-contact">
            <span><i class="fas fa-envelope"></i> ppid@mandaiing.go.id</span>
            <span><i class="fas fa-phone"></i> Call Center: +628117905000</span>
        </div>
    </div>
</div>

<nav class="navbar-custom">
    <div class="container main-navbar">
        <div class="d-flex justify-content-between align-items-center w-100">
            <a href="index.php" class="navbar-brand">
                <div class="logo-img">
                    <img src="ppid_assets/logo-new.png" alt="Logo">
                </div>
            </a>

            <button class="mobile-menu-btn" id="mobileMenuBtn">
                <i class="fas fa-bars"></i>
            </button>

            <div class="navbar-nav" id="navLinks">
                <a href="index.php">BERANDA</a>

                <div class="dropdown-wrapper">
                    <a href="#" class="nav-link-main">
                        PROFIL <i class="fas fa-chevron-down dropdown-icon"></i>
                    </a>
                    <div class="dropdown-content">
                        <?php if (!empty($profile_menu)): ?>
                            <?php foreach ($profile_menu as $kategori => $items): ?>
                                <div class="dropdown-item-wrapper">
                                    <a href="#" class="dropdown-kategori">
                                        <?php echo htmlspecialchars($kategori); ?>
                                        <i class="fas fa-chevron-right kategori-icon"></i>
                                    </a>
                                    <div class="dropdown-sub">
                                        <?php foreach ($items as $item): ?>
                                            <a href="index.php?controller=profile&action=viewDetail&id=<?php echo $item['id_profile']; ?>">
                                                <?php echo htmlspecialchars($item['keterangan']); ?>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="dropdown-wrapper">
                    <a href="#" class="nav-link-main">
                        LAYANAN INFORMASI PUBLIK <i class="fas fa-chevron-down dropdown-icon"></i>
                    </a>
                    <div class="dropdown-content">
                        <?php if (!empty($layanan_menu)): ?>
                            <?php foreach ($layanan_menu as $nama_layanan => $items): ?>
                                <?php
                                // Cek apakah ada sub_layanan atau direct link
                                $has_sub = false;
                                $direct_id = null;

                                if (isset($items['_direct_id'])) {
                                    $direct_id = $items['_direct_id'];
                                } else {
                                    // Filter hanya item yang bukan _direct_id
                                    $sub_items = array_filter($items, function($key) {
                                        return $key !== '_direct_id';
                                    }, ARRAY_FILTER_USE_KEY);

                                    if (!empty($sub_items)) {
                                        $has_sub = true;
                                    }
                                }
                                ?>

                                <?php if ($has_sub): ?>
                                    <!-- Dengan Sub Layanan - Dropdown Bertingkat -->
                                    <div class="dropdown-item-wrapper">
                                        <a href="#" class="dropdown-kategori">
                                            <?php echo htmlspecialchars($nama_layanan); ?>
                                            <i class="fas fa-chevron-right kategori-icon"></i>
                                        </a>
                                        <div class="dropdown-sub">
                                            <?php foreach ($items as $item): ?>
                                                <?php if (is_array($item) && isset($item['sub_layanan'])): ?>
                                                    <a href="index.php?controller=layananInformasi&action=viewDetail&id=<?php echo $item['id']; ?>">
                                                        <?php echo htmlspecialchars($item['sub_layanan']); ?>
                                                    </a>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <!-- Tanpa Sub Layanan - Direct Link -->
                                    <a href="index.php?controller=layananInformasi&action=viewDetail&id=<?php echo $direct_id; ?>" class="dropdown-kategori-direct">
                                        <?php echo htmlspecialchars($nama_layanan); ?>
                                    </a>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="dropdown-wrapper">
                    <a href="#" class="nav-link-main">
                        DAFTAR INFORMASI PUBLIK <i class="fas fa-chevron-down dropdown-icon"></i>
                    </a>
                    <div class="dropdown-content">
                        <?php if (!empty($informasi_menu)): ?>
                            <?php foreach ($informasi_menu as $nama_informasi => $items): ?>
                                <?php
                                // Cek apakah ada sub_informasi_publik atau direct link
                                $has_sub = false;
                                $direct_id = null;

                                if (isset($items['_direct_id'])) {
                                    $direct_id = $items['_direct_id'];
                                } else {
                                    // Filter hanya item yang bukan _direct_id
                                    $sub_items = array_filter($items, function($key) {
                                        return $key !== '_direct_id';
                                    }, ARRAY_FILTER_USE_KEY);

                                    if (!empty($sub_items)) {
                                        $has_sub = true;
                                    }
                                }
                                ?>

                                <?php if ($has_sub): ?>
                                    <!-- Dengan Sub Informasi - Dropdown Bertingkat -->
                                    <div class="dropdown-item-wrapper">
                                        <a href="#" class="dropdown-kategori">
                                            <?php echo htmlspecialchars($nama_informasi); ?>
                                            <i class="fas fa-chevron-right kategori-icon"></i>
                                        </a>
                                        <div class="dropdown-sub">
                                            <?php foreach ($items as $item): ?>
                                                <?php if (is_array($item) && isset($item['sub_informasi_publik'])): ?>
                                                    <a href="index.php?controller=informasiPublik&action=viewDetail&id=<?php echo $item['id']; ?>">
                                                        <?php echo htmlspecialchars($item['sub_informasi_publik']); ?>
                                                    </a>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <!-- Tanpa Sub Informasi - Direct Link -->
                                    <a href="index.php?controller=informasiPublik&action=viewDetail&id=<?php echo $direct_id; ?>" class="dropdown-kategori-direct">
                                        <?php echo htmlspecialchars($nama_informasi); ?>
                                    </a>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="dropdown-wrapper">
                    <a href="#" class="nav-link-main">
                        TATA KELOLA <i class="fas fa-chevron-down dropdown-icon"></i>
                    </a>
                    <div class="dropdown-content">
                        <?php if (!empty($dokumen_menu)): ?>
                            <?php foreach ($dokumen_menu as $nama_kategori => $items): ?>
                                <div class="dropdown-item-wrapper">
                                    <a href="#" class="dropdown-kategori">
                                        <?php echo htmlspecialchars($nama_kategori); ?>
                                        <i class="fas fa-chevron-right kategori-icon"></i>
                                    </a>
                                    <div class="dropdown-sub">
                                        <?php foreach ($items as $item): ?>
                                            <a href="index.php?controller=dokumen&action=index&kategori=<?php echo $item['id_kategori']; ?>&nama_jenis=<?php echo urlencode($item['nama_jenis']); ?>">
                                                <?php echo htmlspecialchars($item['nama_jenis']); ?>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <a href="#">INFO</a>
                <a href="index.php?controller=auth&action=login">LOGIN</a>

                <div class="nav-social">
                    <a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" title="Twitter"><i class="fab fa-twitter"></i></a>
                    <a href="#" title="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" title="YouTube"><i class="fab fa-youtube"></i></a>
                    <a href="#" title="Search"><i class="fas fa-search"></i></a>
                </div>
            </div>
        </div>
    </div>
</nav>

<style>
/* Dropdown Wrapper */
.dropdown-wrapper {
    position: relative;
    display: inline-block;
}

.nav-link-main {
    color: white;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 5px;
    padding: 10px 15px;
}

.dropdown-icon {
    font-size: 12px;
    transition: transform 0.3s;
}

.dropdown-wrapper:hover .dropdown-icon {
    transform: rotate(180deg);
}

/* Level 1 Dropdown (Kategori) */
.dropdown-content {
    display: none;
    position: absolute;
    top: 100%;
    left: 0;
    background: #000000;
    min-width: 250px;
    box-shadow: 0 8px 16px rgba(0,0,0,0.3);
    z-index: 1000;
    /* margin-top: 5px; <-- BARIS INI DIHAPUS UNTUK MEMPERBAIKI MASALAH */
}

.dropdown-wrapper:hover .dropdown-content {
    display: block;
}

/* Dropdown Item Wrapper */
.dropdown-item-wrapper {
    position: relative;
}

.dropdown-kategori {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 20px;
    color: white;
    text-decoration: none;
    border-bottom: 1px solid #333;
    transition: background 0.3s;
}

.dropdown-kategori:hover {
    background: #1a1a1a;
    color: white;
}

.kategori-icon {
    font-size: 12px;
    transition: transform 0.3s;
}

.dropdown-item-wrapper:hover .kategori-icon {
    transform: translateX(5px);
}

/* Level 2 Dropdown (Keterangan) */
.dropdown-sub {
    display: none;
    position: absolute;
    left: 100%;
    top: 0;
    background: #1a1a1a;
    min-width: 250px;
    box-shadow: 0 8px 16px rgba(0,0,0,0.3);
    /* margin-left: 2px; <-- BARIS INI DIHAPUS UNTUK MEMPERBAIKI MASALAH */
}

.dropdown-item-wrapper:hover .dropdown-sub {
    display: block;
}

.dropdown-sub a {
    display: block;
    padding: 12px 20px;
    color: white;
    text-decoration: none;
    border-bottom: 1px solid #333;
    transition: all 0.3s;
}

.dropdown-sub a:hover {
    background: #2a2a2a;
    padding-left: 30px;
}

.dropdown-sub a:last-child {
    border-bottom: none;
}

/* Direct Link (Tanpa Sub) */
.dropdown-kategori-direct {
    display: block;
    padding: 12px 20px;
    color: white;
    text-decoration: none;
    border-bottom: 1px solid #333;
    transition: all 0.3s;
}

.dropdown-kategori-direct:hover {
    background: #1a1a1a;
    color: white;
    padding-left: 30px;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .dropdown-content {
        position: static;
        box-shadow: none;
        background: #1a1a1a;
        margin-top: 0;
    }

    .dropdown-wrapper.mobile-open .dropdown-content {
        display: block;
    }

    .dropdown-sub {
        position: static;
        box-shadow: none;
        background: #2a2a2a;
        margin-left: 0;
    }

    .dropdown-item-wrapper.mobile-open .dropdown-sub {
        display: block;
    }

    .dropdown-kategori {
        padding-left: 30px;
    }

    .dropdown-sub a {
        padding-left: 50px;
    }

    .dropdown-sub a:hover {
        padding-left: 60px;
    }
}
</style>

<script>
    // Mobile menu toggle
    if (document.getElementById('mobileMenuBtn')) {
        document.getElementById('mobileMenuBtn').addEventListener('click', function() {
            const navLinks = document.getElementById('navLinks');
            navLinks.classList.toggle('show');
        });
    }

    // Close mobile menu when clicking outside
    document.addEventListener('click', function(event) {
        const navLinks = document.getElementById('navLinks');
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');

        if (navLinks && mobileMenuBtn && !navLinks.contains(event.target) && !mobileMenuBtn.contains(event.target)) {
            navLinks.classList.remove('show');
        }
    });

    // Dropdown mobile handling
    document.addEventListener('DOMContentLoaded', function() {
        // Main dropdown toggle
        const mainLinks = document.querySelectorAll('.nav-link-main');
        mainLinks.forEach(function(link) {
            link.addEventListener('click', function(e) {
                if (window.innerWidth <= 768) {
                    e.preventDefault();
                    const wrapper = this.closest('.dropdown-wrapper');
                    wrapper.classList.toggle('mobile-open');
                }
            });
        });

        // Kategori toggle for mobile
        const kategoriLinks = document.querySelectorAll('.dropdown-kategori');
        kategoriLinks.forEach(function(link) {
            link.addEventListener('click', function(e) {
                if (window.innerWidth <= 768) {
                    e.preventDefault();
                    const wrapper = this.closest('.dropdown-item-wrapper');
                    wrapper.classList.toggle('mobile-open');
                }
            });
        });
    });
</script>