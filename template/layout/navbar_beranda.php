<?php
// Get profile menu data
require_once 'models/ProfileModel.php';
require_once 'models/LayananInformasiModel.php';
require_once 'models/InformasiPublikModel.php';
require_once 'models/TataKelolaModel.php';
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

// Get dokumen menu data (dari tabel kategori)
$query_dokumen = "SELECT id_kategori, nama_kategori FROM kategori ORDER BY nama_kategori ASC";
$stmt_dokumen = $db->prepare($query_dokumen);
$stmt_dokumen->execute();
$dokumen_menu = $stmt_dokumen->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
    :root {
        --primary-color: #1e3a8a;
        --secondary-color: #f59e0b;
        --accent-color: #fbbf24;
        --text-color: #1f2937;
        --muted-color: #6b7280;
        --light-bg: #f8f9fa;
    }

    body {
        font-family: 'Inter', sans-serif;
        margin: 0;
        padding: 0;
        background-color: var(--light-bg);
    }

    /* Header Styles */
    .top-info-bar {
        background-color: #e5e7eb;
        padding: 8px 0;
        font-size: 13px;
        color: var(--muted-color);
        border-bottom: 1px solid #d1d5db;
    }

    .top-info-bar .container {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .top-info-links {
        display: flex;
        gap: 20px;
    }

    .top-info-links a {
        color: var(--muted-color);
        text-decoration: none;
        font-weight: 500;
    }

    .top-info-links a:hover {
        color: #374151;
    }

    .top-info-contact {
        display: flex;
        gap: 25px;
        align-items: center;
    }

    .navbar-custom {
        background: #000000;
        padding: 0;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .main-navbar {
        padding: 12px 0;
    }

    .navbar-brand {
        display: flex;
        align-items: center;
        color: white !important;
        font-weight: 600;
        text-decoration: none;
    }

    .logo-img {
        width: 45px;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
        background: white;
        border-radius: 8px;
    }

    .logo-img img {
        width: 35px;
        height: 35px;
    }

    .navbar-nav {
        display: flex;
        flex-direction: row;
        gap: 8px;
        align-items: center;
        margin-left: auto;
    }

    .navbar-nav a {
        color: white;
        text-decoration: none;
        font-weight: 500;
        font-size: 11px;
        transition: all 0.3s ease;
        padding: 8px 16px;
        border-radius: 4px;
        white-space: nowrap;
    }

    .navbar-nav a:hover {
        color: #ddd;
        background-color: rgba(255, 255, 255, 0.1);
    }

    .nav-social {
        display: flex;
        gap: 8px;
        align-items: center;
        margin-left: 15px;
        padding-left: 15px;
        border-left: 1px solid rgba(255, 255, 255, 0.3);
    }

    .nav-social a {
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
        background: rgba(255, 255, 255, 0.1);
        font-size: 14px;
        padding: 0;
        color: white;
    }

    .mobile-menu-btn {
        display: none;
        background: none;
        border: none;
        color: white;
        font-size: 24px;
        cursor: pointer;
    }

    /* Banner Styles */
    .banner-container {
        position: relative;
        width: 100%;
        height: 600px;
        overflow: hidden;
    }

    .banner-track {
        display: flex;
        width: 100%;
        height: 100%;
        transition: transform 0.6s ease-in-out;
    }

    .banner-slide {
        min-width: 100%;
        height: 100%;
        position: relative;
    }

    .banner-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .banner-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, rgba(0, 0, 0, 0.5) 0%, rgba(0, 0, 0, 0.3) 100%);
    }

    .banner-dots {
        position: absolute;
        bottom: 30px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 10px;
        z-index: 10;
    }

    .dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.5);
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .dot.active {
        background: var(--accent-color);
        transform: scale(1.2);
    }

    .banner-arrow {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(255, 255, 255, 0.3);
        border: none;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        cursor: pointer;
        z-index: 10;
        transition: all 0.3s ease;
        backdrop-filter: blur(5px);
    }

    .banner-arrow:hover {
        background: rgba(255, 255, 255, 0.5);
        transform: translateY(-50%) scale(1.1);
    }

    .banner-arrow-prev {
        left: 20px;
    }

    .banner-arrow-next {
        right: 20px;
    }

    .banner-progress {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 5px;
        background: rgba(255, 255, 255, 0.2);
        z-index: 10;
    }

    .progress-bar-fill {
        height: 100%;
        width: 0%;
        background: var(--accent-color);
        transition: width 4s linear;
    }

    .empty-banner {
        height: 600px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #999 0%, #666 100%);
        color: white;
        text-align: center;
    }

    /* Section Styles */
    .features-section {
        padding: 60px 0 80px;
        background-color: #ffffff;
    }

    .section-title {
        text-align: center;
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 50px;
        color: var(--primary-color);
        position: relative;
    }

    .section-title::after {
        content: "";
        position: absolute;
        bottom: -15px;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 4px;
        background: linear-gradient(90deg, var(--secondary-color), var(--accent-color));
        border-radius: 2px;
    }

    .card {
        border: none;
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
    }

    .info-card {
        background: white;
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .info-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border-color: #667eea;
    }

    /* Information Section Styles */
    .info-filter-tabs .nav-pills {
        background: #f8fafc;
        border-radius: 50px;
        padding: 5px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .info-filter-tabs .nav-link {
        border-radius: 50px;
        padding: 10px 25px;
        margin: 0 2px;
        border: none;
        background: transparent;
        color: #64748b;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .info-filter-tabs .nav-link.active {
        background: linear-gradient(45deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }

    .info-filter-tabs .nav-link:hover:not(.active) {
        background: #e2e8f0;
        color: #475569;
    }

    .info-search-bar .input-group {
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        border-radius: 50px;
        overflow: hidden;
    }

    .info-search-bar .form-control {
        border: 1px solid #e2e8f0;
        padding: 12px 20px;
        font-size: 16px;
    }

    .info-search-bar .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
        margin-bottom: 40px;
    }

    .info-item {
        transition: all 0.3s ease;
    }

    .info-item.hide {
        opacity: 0;
        transform: scale(0.8);
        height: 0;
        margin: 0;
        overflow: hidden;
    }

    .info-card-header {
        position: absolute;
        top: 15px;
        left: 15px;
        right: 15px;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        z-index: 2;
    }

    .info-category-badge .badge {
        font-size: 11px;
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: 500;
        letter-spacing: 0.5px;
    }

    .info-actions {
        display: flex;
        gap: 5px;
        opacity: 0;
        transition: all 0.3s ease;
    }

    .info-card:hover .info-actions {
        opacity: 1;
    }

    .info-actions .btn {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        backdrop-filter: blur(10px);
        background: rgba(255, 255, 255, 0.9);
    }

    .info-card-body {
        padding-top: 60px;
    }

    .info-icon {
        text-align: center;
        margin-bottom: 15px;
    }

    .info-meta {
        border-top: 1px solid #f1f5f9;
        border-bottom: 1px solid #f1f5f9;
        padding: 10px 0;
        margin: 15px 0;
    }

    .info-actions-bottom .btn {
        border-radius: 25px;
        padding: 10px 20px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .info-actions-bottom .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .info-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .info-filter-tabs .nav-link {
            padding: 8px 16px;
            font-size: 14px;
        }

        .info-search-bar {
            margin-bottom: 30px;
        }
    }

    @media (max-width: 576px) {
        .info-filter-tabs .nav-pills {
            flex-wrap: wrap;
            justify-content: center;
        }

        .info-filter-tabs .nav-link {
            margin: 2px;
            padding: 6px 12px;
            font-size: 13px;
        }
    }

    .stat-card {
        background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
    }

    .news-card {
        background: white;
        cursor: pointer;
        border: 1px solid #e2e8f0;
    }

    .card-icon {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-size: 24px;
        margin-bottom: 15px;
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--primary-color);
        display: block;
    }

    .news-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 30px;
        margin-bottom: 40px;
    }

    .news-image {
        height: 220px;
        overflow: hidden;
        border-radius: 10px 10px 0 0;
    }

    .news-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .news-card:hover .news-image img {
        transform: scale(1.05);
    }

    /* Layanan Section */
    .layanan-section {
        padding: 80px 0;
    }

    .layanan-container {
        display: flex;
        gap: 40px;
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
        min-height: 600px;
    }

    .layanan-sidebar {
        width: 350px;
        background: white;
        box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05);
        padding: 30px 0;
    }

    .sidebar-item {
        display: flex;
        align-items: center;
        padding: 20px 30px;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
    }

    .sidebar-item:hover {
        background-color: #f8fafc;
    }

    .sidebar-item.active {
        background-color: #eff6ff;
    }

    .icon-wrapper {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 16px;
        background-color: #e2e8f0;
        transition: all 0.3s ease;
    }

    .sidebar-item.active .icon-wrapper {
        background-color: #3b82f6;
    }

    .sidebar-item.active .icon-wrapper i {
        color: white;
    }

    .layanan-content {
        flex: 1;
        padding: 40px;
    }

    .content-section {
        display: none;
        animation: fadeIn 0.5s ease;
    }

    .content-section.active {
        display: block;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .cards-container {
        display: flex;
        gap: 30px;
        flex-wrap: wrap;
    }

    .card-item {
        flex: 1;
        min-width: 250px;
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 35px 25px;
        text-align: center;
        transition: all 0.4s ease;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }

    .card-item:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 25px rgba(0, 0, 0, 0.1);
    }

    /* Footer */


    .social-links {
        display: flex;
        gap: 15px;
        margin-top: 20px;
    }

    .social-links a {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        transition: all 0.3s ease;
    }

    .social-links a:hover {
        background: var(--accent-color);
        transform: translateY(-3px);
    }

    .copyright {
        text-align: center;
        padding-top: 30px;
        margin-top: 30px;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        color: #cbd5e1;
    }

    /* News Modal */
    .news-modal {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.8);
        animation: fadeIn 0.3s ease;
    }

    .news-modal-content {
        background-color: #ffffff;
        margin: 2% auto;
        padding: 0;
        border-radius: 10px;
        width: 90%;
        max-width: 800px;
        max-height: 90vh;
        overflow-y: auto;
        position: relative;
        animation: slideIn 0.3s ease;
    }

    .news-modal-image {
        width: 100%;
        height: 300px;
        object-fit: cover;
        border-radius: 10px 10px 0 0;
    }

    .news-modal-body {
        padding: 30px;
    }

    .modal-close {
        position: absolute;
        top: 15px;
        right: 20px;
        background: rgba(0, 0, 0, 0.5);
        color: white;
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        font-size: 18px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background-color 0.3s ease;
        z-index: 10000;
    }

    .modal-close:hover {
        background: rgba(0, 0, 0, 0.7);
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-50px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Responsive Design */
    @media (max-width: 992px) {
        .top-info-bar {
            display: none;
        }

        .navbar-nav {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: #000000;
            flex-direction: column;
            gap: 0;
            padding: 10px 0;
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            margin-left: 0;
        }

        .navbar-nav.show {
            display: flex;
        }

        .mobile-menu-btn {
            display: block;
        }

        .layanan-container {
            flex-direction: column;
            min-height: auto;
        }

        .layanan-sidebar {
            width: 100%;
            padding: 20px 0;
        }

        .banner-container {
            height: 500px;
        }
    }

    @media (max-width: 768px) {
        .section-title {
            font-size: 1.8rem;
        }

        .banner-container {
            height: 400px;
        }

        .news-grid {
            grid-template-columns: 1fr;
        }

        .cards-container {
            flex-direction: column;
        }

        .banner-arrow {
            width: 40px;
            height: 40px;
        }
    }

    @media (max-width: 576px) {
        .banner-container {
            height: 300px;
        }

        .section-title {
            font-size: 1.5rem;
        }

        .layanan-section {
            padding: 60px 0;
        }
    }
</style>

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
                                    $sub_items = array_filter($items, function ($key) {
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
                                    $sub_items = array_filter($items, function ($key) {
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

                        <!-- Dropdown Dokumen -->
                        <div class="dropdown-item-wrapper">
                            <a href="#" class="dropdown-kategori">
                                Dokumen
                                <i class="fas fa-chevron-right kategori-icon"></i>
                            </a>
                            <div class="dropdown-sub">
                                <?php if (!empty($dokumen_menu)): ?>
                                    <?php foreach ($dokumen_menu as $kategori): ?>
                                        <a href="index.php?controller=dokumen&action=index&kategori=<?php echo $kategori['id_kategori']; ?>">
                                            <?php echo htmlspecialchars($kategori['nama_kategori']); ?>
                                        </a>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="dropdown-wrapper">
                    <a href="#" class="nav-link-main">
                        TATA KELOLA <i class="fas fa-chevron-down dropdown-icon"></i>
                    </a>
                    <div class="dropdown-content">
                        <?php
                        // Fetch tata kelola data
                        $tataKelolaModel = new TataKelolaModel($db);
                        $tataKelolaList = $tataKelolaModel->getAllTataKelola();
                        ?>
                        <?php if (!empty($tataKelolaList)): ?>
                            <?php foreach ($tataKelolaList as $tataKelola): ?>
                                <a href="<?php echo !empty($tataKelola['link']) ? htmlspecialchars($tataKelola['link']) : '#'; ?>"
                                    <?php echo !empty($tataKelola['link']) ? 'target="_blank"' : ''; ?>>
                                    <?php echo htmlspecialchars($tataKelola['nama_tata_kelola']); ?>
                                </a>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <a href="#" class="dropdown-kategori-direct">Tidak ada data</a>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="dropdown-wrapper">
                    <a href="#" class="nav-link-main">
                        INFO <i class="fas fa-chevron-down dropdown-icon"></i>
                    </a>
                    <div class="dropdown-content">
                        <a href="index.php?controller=album&action=public&kategori=foto" class="dropdown-kategori-direct">
                            </i>Galeri Foto
                        </a>
                        <a href="index.php?controller=album&action=public&kategori=video" class="dropdown-kategori-direct">
                            </i>Galeri Video
                        </a>
                        <a href="index.php?controller=berita&action=public" class="dropdown-kategori-direct">
                            </i> Berita
                        </a>
                        <a href="index.php?controller=faq&action=public" class="dropdown-kategori-direct">
                            </i> FAQ
                        </a>
                        <a href="index.php?controller=pesanMasuk&action=public" class="dropdown-kategori-direct">
                            </i> Kontak
                        </a>
                    </div>
                </div>
                <a href="index.php?controller=auth&action=login">LOGIN</a>

                <div class="nav-social">
                    <a href="https://www.facebook.com/PemkabMandailingNatal" title="Facebook" target="_blank" rel="noopener noreferrer">
                        <i class="fab fa-facebook-f"></i>
                    </a>

                    <a href="https://www.instagram.com/pemkabmandailingnatal/" title="Instagram" target="_blank" rel="noopener noreferrer">
                        <i class="fab fa-instagram"></i>
                    </a>

                    <a href="https://www.youtube.com/@DISKOMINFOMADINA" title="YouTube" target="_blank" rel="noopener noreferrer">
                        <i class="fab fa-youtube"></i>
                    </a>

                    <a href="https://www.tiktok.com/@diskominfomadina2" title="TikTok" target="_blank" rel="noopener noreferrer">
                        <i class="fab fa-tiktok"></i>
                    </a>

                    <a href="#" title="Search">
                        <i class="fas fa-search"></i>
                    </a>
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
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
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
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
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