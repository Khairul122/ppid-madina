<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $pageInfo['title'] ?? 'Berita - PPID Mandailing Natal' ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
            color: #333;
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
            background-color: #f8f9fa;
        }

        .news-header {
            text-align: center;
            margin-bottom: 40px;
            padding: 0 20px;
        }

        .news-title {
            font-size: 42px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 15px;
        }

        .news-subtitle {
            font-size: 18px;
            color: #6b7280;
            max-width: 800px;
            margin: 0 auto;
        }

        .news-filters {
            background: white;
            padding: 15px 0;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            border-radius: 8px;
        }

        .filter-tabs {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
        }

        .filter-tab {
            padding: 8px 20px;
            background: #e5e7eb;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .filter-tab:hover {
            background: #d1d5db;
        }

        .filter-tab.active {
            background: #1e3a8a;
            color: white;
        }

        .news-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }

        .news-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 30px;
        }

        .news-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .news-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .news-image {
            height: 200px;
            overflow: hidden;
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

        .news-content {
            padding: 20px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .news-category {
            display: inline-block;
            padding: 4px 12px;
            background: #e5e7eb;
            color: #374151;
            font-size: 12px;
            font-weight: 600;
            border-radius: 20px;
            margin-bottom: 10px;
        }

        .news-title-link {
            color: #1f2937;
            text-decoration: none;
            font-weight: 600;
            font-size: 18px;
            line-height: 1.4;
            margin-bottom: 10px;
            display: block;
        }

        .news-title-link:hover {
            color: #1e3a8a;
        }

        .news-excerpt {
            color: #6b7280;
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 15px;
            flex: 1;
        }

        .news-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #9ca3af;
            font-size: 13px;
            border-top: 1px solid #e5e7eb;
            padding-top: 15px;
        }

        .news-date {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .news-read-more {
            color: #1e3a8a;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
        }

        .news-read-more:hover {
            text-decoration: underline;
        }

        .news-highlight {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 40px;
            display: flex;
            flex-direction: column;
        }

        .highlight-image {
            height: 300px;
        }

        .highlight-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .highlight-content {
            padding: 25px;
        }

        .highlight-category {
            display: inline-block;
            padding: 6px 15px;
            background: #f59e0b;
            color: white;
            font-size: 13px;
            font-weight: 600;
            border-radius: 20px;
            margin-bottom: 10px;
        }

        .highlight-title {
            font-size: 28px;
            font-weight: 700;
            color: #1f2937;
            margin: 10px 0;
            line-height: 1.3;
        }

        .highlight-excerpt {
            color: #6b7280;
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .highlight-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #9ca3af;
            font-size: 14px;
        }

        .pagination-container {
            display: flex;
            justify-content: center;
            margin-top: 40px;
        }

        .pagination {
            display: flex;
            gap: 5px;
        }

        .page-link {
            display: block;
            padding: 8px 16px;
            background: white;
            color: #1f2937;
            text-decoration: none;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        .page-link:hover,
        .page-link.active {
            background: #1e3a8a;
            color: white;
            border-color: #1e3a8a;
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

        @media (max-width: 992px) {
            .news-grid {
                grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            }
            
            .news-title {
                font-size: 36px;
            }
        }

        @media (max-width: 768px) {
            .news-header {
                padding: 0 15px;
            }
            
            .news-title {
                font-size: 32px;
            }
            
            .filter-tabs {
                justify-content: flex-start;
                overflow-x: auto;
                padding-bottom: 10px;
            }
            
            .news-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .highlight-image {
                height: 250px;
            }
            
            .highlight-title {
                font-size: 24px;
            }
        }

        @media (max-width: 576px) {
            .main-content {
                padding: 40px 0;
            }
            
            .news-title {
                font-size: 28px;
            }
            
            .filter-tab {
                white-space: nowrap;
            }
            
            .news-card {
                margin: 0 10px;
            }
            
            .highlight-image {
                height: 200px;
            }
            
            .highlight-title {
                font-size: 22px;
            }
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
                    <li class="breadcrumb-item active" aria-current="page">Berita</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container">
            <!-- Header -->
            <div class="news-header">
                <h1 class="news-title">Berita Terkini</h1>
                <p class="news-subtitle">
                    Informasi terbaru dan terpercaya seputar Kabupaten Mandailing Natal
                </p>
            </div>

            <!-- News Filters -->
            <div class="news-filters">
                <div class="filter-tabs">
                    <button class="filter-tab active">Semua Berita</button>
                    <button class="filter-tab">Berita Utama</button>
                    <button class="filter-tab">Pengumuman</button>
                    <button class="filter-tab">Kegiatan</button>
                    <button class="filter-tab">Info Publik</button>
                </div>
            </div>

            <?php if (!empty($newsList) && is_array($newsList)): ?>
                <div class="news-container">
                    <?php 
                    // Get the first news item for highlight
                    $highlightNews = $newsList[0] ?? null;
                    $otherNews = array_slice($newsList, 1);
                    ?>

                    <?php if ($highlightNews): ?>
                        <!-- Highlighted News -->
                        <div class="news-highlight">
                            <div class="highlight-image">
                                <img src="<?= $highlightNews['gambar'] ?? 'assets/images/placeholder-news.jpg' ?>" 
                                     alt="<?= htmlspecialchars($highlightNews['judul_berita'] ?? 'Berita Utama') ?>">
                            </div>
                            <div class="highlight-content">
                                <span class="highlight-category">Berita Utama</span>
                                <h2 class="highlight-title">
                                    <a href="index.php?controller=berita&action=detail&id=<?= $highlightNews['id_berita'] ?? 1 ?>" 
                                       class="news-title-link">
                                        <?= htmlspecialchars($highlightNews['judul_berita'] ?? 'Judul Berita') ?>
                                    </a>
                                </h2>
                                <p class="highlight-excerpt">
                                    <?= htmlspecialchars(substr($highlightNews['isi_berita'] ?? 'Isi berita tidak tersedia', 0, 200)) ?>...
                                </p>
                                <div class="highlight-meta">
                                    <div class="news-date">
                                        <i class="fas fa-calendar"></i>
                                        <?= date('d M Y', strtotime($highlightNews['tanggal_berita'] ?? date('Y-m-d'))) ?>
                                    </div>
                                    <a href="index.php?controller=berita&action=detail&id=<?= $highlightNews['id_berita'] ?? 1 ?>" 
                                       class="news-read-more">
                                        Baca Selengkapnya <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- News Grid -->
                    <div class="news-grid">
                        <?php foreach ($otherNews as $index => $news): ?>
                            <div class="news-card">
                                <div class="news-image">
                                    <img src="<?= $news['gambar'] ?? 'assets/images/placeholder-news.jpg' ?>" 
                                         alt="<?= htmlspecialchars($news['judul_berita'] ?? 'Berita') ?>">
                                </div>
                                <div class="news-content">
                                    <span class="news-category">
                                        <?= htmlspecialchars($news['kategori'] ?? 'Berita') ?>
                                    </span>
                                    <a href="index.php?controller=berita&action=detail&id=<?= $news['id_berita'] ?? 1 ?>" 
                                       class="news-title-link">
                                        <?= htmlspecialchars($news['judul_berita'] ?? 'Judul Berita') ?>
                                    </a>
                                    <p class="news-excerpt">
                                        <?= htmlspecialchars(substr($news['isi_berita'] ?? 'Isi berita tidak tersedia', 0, 100)) ?>...
                                    </p>
                                    <div class="news-meta">
                                        <div class="news-date">
                                            <i class="fas fa-calendar"></i>
                                            <?= date('d M Y', strtotime($news['tanggal_berita'] ?? date('Y-m-d'))) ?>
                                        </div>
                                        <a href="index.php?controller=berita&action=detail&id=<?= $news['id_berita'] ?? 1 ?>" 
                                           class="news-read-more">
                                            Baca
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="pagination-container">
                    <div class="pagination">
                        <?php if (isset($currentPage) && $currentPage > 1): ?>
                            <a href="?page=<?= $currentPage - 1 ?>" class="page-link">Sebelumnya</a>
                        <?php endif; ?>
                        
                        <?php for ($i = 1; $i <= ($totalPages ?? 5); $i++): ?>
                            <a href="?page=<?= $i ?>" class="page-link <?= ($i == ($currentPage ?? 1)) ? 'active' : '' ?>">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>
                        
                        <?php if (isset($currentPage) && $currentPage < ($totalPages ?? 5)): ?>
                            <a href="?page=<?= $currentPage + 1 ?>" class="page-link">Berikutnya</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php else: ?>
                <!-- Empty State -->
                <div class="empty-state">
                    <i class="fas fa-newspaper"></i>
                    <h3>Belum Ada Berita</h3>
                    <p>Berita terbaru akan ditampilkan di sini.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Filter functionality
        document.querySelectorAll('.filter-tab').forEach(tab => {
            tab.addEventListener('click', function() {
                // Remove active class from all tabs
                document.querySelectorAll('.filter-tab').forEach(t => {
                    t.classList.remove('active');
                });
                
                // Add active class to clicked tab
                this.classList.add('active');
                
                // Here you would typically filter the news based on the selected category
                // For now, we'll just simulate the filtering
                console.log('Filter selected:', this.textContent);
            });
        });

        // Simulate loading more news when reaching bottom of page
        window.addEventListener('scroll', function() {
            if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 500) {
                // Load more news functionality would go here
                console.log('Reached bottom, loading more news...');
            }
        });
    </script>
</body>

</html>