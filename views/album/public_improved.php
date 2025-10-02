<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $pageInfo['title'] ?? 'Galeri - PPID Mandailing Natal' ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Fancybox CSS for advanced lightbox -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.css">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }

        .top-info-bar {
            background-color: #e5e7eb;
            padding: 8px 0;
            font-size: 13px;
            color: #6b7280;
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
            color: #6b7280;
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

        .top-info-contact span {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .top-info-contact i {
            font-size: 12px;
        }

        .navbar-custom {
            background: #000000;
            padding: 0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .main-navbar {
            padding: 12px 0;
        }

        .main-navbar .d-flex {
            flex-wrap: nowrap !important;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            color: white !important;
            font-weight: 600;
            text-decoration: none;
        }

        .navbar-brand:hover {
            color: #e5e7eb !important;
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
            flex-wrap: nowrap;
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

        .nav-social a:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
        }

        .breadcrumb-section {
            background-color: #e5e7eb;
            padding: 15px 0;
        }

        .breadcrumb {
            background: none;
            margin: 0;
            padding: 0;
        }

        .breadcrumb-item {
            font-size: 14px;
        }

        .breadcrumb-item a {
            color: #6b7280;
            text-decoration: none;
        }

        .breadcrumb-item.active {
            color: #1e3a8a;
            font-weight: 600;
        }

        .main-content {
            padding: 60px 0;
            min-height: calc(100vh - 200px);
        }

        .gallery-header {
            text-align: center;
            margin-bottom: 50px;
        }

        .gallery-title {
            font-size: 42px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 15px;
        }

        .gallery-subtitle {
            font-size: 18px;
            color: #6b7280;
        }

        .album-section {
            margin-bottom: 60px;
        }

        .album-title {
            font-size: 28px;
            font-weight: 700;
            color: #1e3a8a;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 3px solid #f59e0b;
            display: inline-block;
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .gallery-item {
            position: relative;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            background: white;
            cursor: pointer;
        }

        .gallery-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .gallery-item img,
        .gallery-item video {
            width: 100%;
            height: 250px;
            object-fit: cover;
            display: block;
        }

        .gallery-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.7) 0%, transparent 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .gallery-item:hover .gallery-overlay {
            opacity: 1;
        }

        .gallery-icon {
            color: white;
            font-size: 48px;
        }

        .video-play-icon {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 70px;
            height: 70px;
            background: rgba(245, 158, 11, 0.9);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 30px;
            transition: all 0.3s ease;
        }

        .gallery-item:hover .video-play-icon {
            background: rgba(245, 158, 11, 1);
            transform: translate(-50%, -50%) scale(1.1);
        }

        .empty-state {
            text-align: center;
            padding: 80px 20px;
        }

        .empty-state i {
            font-size: 80px;
            color: #d1d5db;
            margin-bottom: 20px;
        }

        .empty-state h3 {
            color: #6b7280;
            margin-bottom: 15px;
        }

        .empty-state p {
            color: #9ca3af;
        }

        /* Fancybox customization */
        .fancybox__caption {
            font-size: 16px;
            font-weight: 500;
        }

        /* Video Modal */
        .video-modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.95);
        }

        .video-modal-content {
            position: relative;
            margin: 5% auto;
            width: 80%;
            max-width: 900px;
        }

        .video-modal video {
            width: 100%;
            border-radius: 8px;
        }

        .video-modal-close {
            position: absolute;
            top: -40px;
            right: 0;
            color: white;
            font-size: 35px;
            font-weight: bold;
            cursor: pointer;
        }

        .video-modal-close:hover {
            color: #f59e0b;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .gallery-title {
                font-size: 32px;
            }

            .album-title {
                font-size: 22px;
            }

            .gallery-grid {
                grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
                gap: 15px;
            }

            .gallery-item img,
            .gallery-item video {
                height: 180px;
            }

            .video-modal-content {
                width: 95%;
            }
        }

        @media (max-width: 576px) {
            .gallery-title {
                font-size: 28px;
            }
            
            .album-title {
                font-size: 20px;
            }
            
            .gallery-grid {
                grid-template-columns: 1fr;
            }
            
            .main-content {
                padding: 40px 0;
            }
        }
        
        /* Loading spinner */
        .loading-spinner {
            display: inline-block;
            width: 40px;
            height: 40px;
            border: 4px solid rgba(0, 0, 0, 0.1);
            border-left-color: #0d6efd;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>

<body>
    <?php include 'template/layout/navbar_beranda.php'; ?>

    <!-- Breadcrumb -->
    <div class="breadcrumb-section">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">Galeri</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <?= ucfirst(is_array($kategori) ? 'Foto' : ($kategori ?? 'Foto')) ?>
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container">
            <!-- Header -->
            <div class="gallery-header">
                <h1 class="gallery-title">
                    <i class="fas fa-<?= (is_array($kategori) ? 'foto' : ($kategori ?? 'foto')) === 'foto' ? 'camera' : 'video' ?> me-3"></i>
                    Galeri <?= ucfirst(is_array($kategori) ? 'Foto' : ($kategori ?? 'Foto')) ?>
                </h1>
                <p class="gallery-subtitle">
                    Dokumentasi kegiatan dan informasi PPID Mandailing Natal dalam bentuk <?= is_array($kategori) ? 'foto' : ($kategori ?? 'foto') ?>
                </p>
            </div>

            <?php if (!empty($albumList)): ?>
                <?php
                // Group albums by name
                $groupedAlbums = [];
                foreach ($albumList as $album) {
                    $groupedAlbums[$album['nama_album']][] = $album;
                }
                ?>

                <?php foreach ($groupedAlbums as $albumName => $albums): ?>
                    <div class="album-section">
                        <h2 class="album-title">
                            <i class="fas fa-folder-open me-2"></i>
                            <?= htmlspecialchars($albumName) ?>
                        </h2>

                        <div class="gallery-grid">
                            <?php foreach ($albums as $index => $album): ?>
                                <?php if (!empty($album['upload'])): ?>
                                    <div class="gallery-item">
                                        <?php if ($album['kategori'] === 'foto'): ?>
                                            <!-- Foto dengan Fancybox -->
                                            <a href="<?= htmlspecialchars($album['upload']) ?>"
                                               data-fancybox="gallery-<?= preg_replace('/[^a-zA-Z0-9]/', '', $albumName) ?>"
                                               data-caption="<?= htmlspecialchars($album['nama_album']) ?>">
                                                <img src="<?= htmlspecialchars($album['upload']) ?>"
                                                     alt="<?= htmlspecialchars($album['nama_album']) ?>">
                                                <div class="gallery-overlay">
                                                    <i class="fas fa-search-plus gallery-icon"></i>
                                                </div>
                                            </a>
                                        <?php else: ?>
                                            <!-- Video dengan modal -->
                                            <div class="video-container" onclick="openVideoModal('<?= htmlspecialchars($album['upload']) ?>', '<?= htmlspecialchars($album['nama_album']) ?>')">
                                                <video preload="metadata">
                                                    <source src="<?= htmlspecialchars($album['upload']) ?>" type="video/mp4">
                                                    Browser Anda tidak mendukung video.
                                                </video>
                                                <div class="video-play-icon">
                                                    <i class="fas fa-play"></i>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Empty State -->
                <div class="empty-state">
                    <i class="fas fa-<?= (is_array($kategori) ? 'foto' : ($kategori ?? 'foto')) === 'foto' ? 'images' : 'video' ?>"></i>
                    <h3>Belum Ada <?= ucfirst(is_array($kategori) ? 'Foto' : ($kategori ?? 'Foto')) ?></h3>
                    <p>Galeri <?= is_array($kategori) ? 'foto' : ($kategori ?? 'foto') ?> belum tersedia saat ini.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Video Modal -->
    <div id="videoModal" class="video-modal">
        <div class="video-modal-content">
            <span class="video-modal-close" onclick="closeVideoModal()">&times;</span>
            <div id="videoContainer">
                <video id="modalVideo" controls preload="metadata">
                    <source id="modalVideoSource" src="" type="video/mp4">
                    Browser Anda tidak mendukung video.
                </video>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery (required for Fancybox) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Fancybox JS for advanced lightbox -->
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.umd.js"></script>

    <script>
        // Initialize Fancybox with advanced options
        Fancybox.bind("[data-fancybox]", {
            // Enable infinite gallery navigation
            infinite: true,
            // Display image count
            caption: function (fancybox, carousel, slide) {
                return slide.caption || false;
            },
            // Customize arrows and buttons
            navigation: {
                next: ".fancybox__button--next",
                prev: ".fancybox__button--prev",
            },
            // Animation settings
            animated: true,
            hideScrollbar: false,
            // Transition effect
            dragToClose: false,
        });

        // Video modal functions
        function openVideoModal(videoSrc, videoTitle) {
            const modal = document.getElementById('videoModal');
            const video = document.getElementById('modalVideo');
            const source = document.getElementById('modalVideoSource');
            
            // Update title if needed
            if(videoTitle) {
                console.log("Playing: " + videoTitle);
            }
            
            // Set video source and load
            source.src = videoSrc;
            video.load();
            modal.style.display = 'block';
            
            // Start playing after loaded
            video.onloadeddata = function() {
                video.play();
            };
        }

        function closeVideoModal() {
            const modal = document.getElementById('videoModal');
            const video = document.getElementById('modalVideo');
            
            modal.style.display = 'none';
            video.pause();
            video.currentTime = 0;
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('videoModal');
            if (event.target === modal) {
                closeVideoModal();
            }
        }

        // Close modal with ESC key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeVideoModal();
            }
        });
        
        // Initialize all video elements for better performance
        document.addEventListener('DOMContentLoaded', function() {
            const videos = document.querySelectorAll('video');
            videos.forEach(video => {
                video.addEventListener('click', function(e) {
                    // Prevent triggering parent click events
                    e.stopPropagation();
                });
            });
        });
    </script>
</body>

</html>