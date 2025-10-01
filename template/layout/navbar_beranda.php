<?php
// Get profile menu data
require_once 'models/ProfileModel.php';
global $database;
$db = $database->getConnection();
$profileModel = new ProfileModel($db);
$profile_menu = $profileModel->getProfilesForNavbar();
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

                <a href="#">LAYANAN INFORMASI PUBLIK</a>
                <a href="#">DAFTAR INFORMASI PUBLIK</a>
                <a href="#">TATA KELOLA</a>
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