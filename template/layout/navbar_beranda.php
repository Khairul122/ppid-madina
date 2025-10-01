<!-- Top Info Bar -->
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

<!-- Main Navigation Header -->
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
                <a href="index.php?controller=user&action=profile">PROFIL</a>
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
</script>