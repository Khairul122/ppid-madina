<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $pageInfo['title'] ?? 'Berita - PPID Mandailing Natal' ?></title>
    <meta name="description" content="<?= $pageInfo['description'] ?? 'Informasi terbaru dan terpercaya seputar Kabupaten Mandailing Natal' ?>">

    <!-- External CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

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
            color: var(--text-color);
        }

        /* Top Info Bar */
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

        /* Navbar */
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

        /* Breadcrumb */
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
            color: var(--muted-color);
            text-decoration: none;
        }

        .breadcrumb-item.active {
            color: var(--primary-color);
            font-weight: 600;
        }

        /* Main Content */
        .main-content {
            padding: 60px 0;
            min-height: calc(100vh - 200px);
            background-color: var(--light-bg);
        }

        /* News Header */
        .news-header {
            text-align: center;
            margin-bottom: 50px;
            padding: 0 20px;
        }

        .news-title {
            font-size: 42px;
            font-weight: 800;
            color: var(--text-color);
            margin-bottom: 15px;
            letter-spacing: -0.5px;
        }

        .news-subtitle {
            font-size: 18px;
            color: var(--muted-color);
            max-width: 800px;
            margin: 0 auto;
        }

        /* Search & Filter */
        .news-controls {
            margin-bottom: 40px;
        }

        .search-bar {
            max-width: 600px;
            margin: 0 auto 30px;
        }

        .search-bar .input-group {
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border-radius: 50px;
            overflow: hidden;
        }

        .search-bar input {
            border: none;
            padding: 15px 25px;
            font-size: 15px;
        }

        .search-bar input:focus {
            box-shadow: none;
            outline: none;
        }

        .search-bar .btn {
            background: var(--primary-color);
            border: none;
            padding: 0 30px;
            color: white;
        }

        .search-bar .btn:hover {
            background: #1e40af;
        }

        /* News Grid */
        .news-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 15px;
        }

        .news-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
            gap: 30px;
            margin-bottom: 50px;
        }

        /* Featured News (Large Card) */
        .featured-news {
            grid-column: span 2;
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            height: 500px;
        }

        .featured-news:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.15);
        }

        .featured-news .news-image {
            height: 300px;
            overflow: hidden;
            position: relative;
        }

        .featured-news .news-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .featured-news:hover .news-image img {
            transform: scale(1.05);
        }

        .featured-badge {
            position: absolute;
            top: 20px;
            left: 20px;
            background: var(--secondary-color);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .featured-news .news-content {
            padding: 30px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .featured-news .news-title-link {
            font-size: 28px;
            font-weight: 700;
            line-height: 1.3;
            color: var(--text-color);
            text-decoration: none;
            margin-bottom: 15px;
            display: block;
        }

        .featured-news .news-title-link:hover {
            color: var(--primary-color);
        }

        .featured-news .news-excerpt {
            font-size: 16px;
            line-height: 1.6;
            color: var(--muted-color);
            margin-bottom: 20px;
            flex: 1;
        }

        /* Regular News Card */
        .news-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .news-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }

        .news-image {
            height: 220px;
            overflow: hidden;
            position: relative;
        }

        .news-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .news-card:hover .news-image img {
            transform: scale(1.08);
        }

        .news-content {
            padding: 25px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .news-category {
            display: inline-block;
            padding: 5px 12px;
            background: #e0e7ff;
            color: var(--primary-color);
            font-size: 11px;
            font-weight: 600;
            border-radius: 20px;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .news-title-link {
            color: var(--text-color);
            text-decoration: none;
            font-weight: 700;
            font-size: 18px;
            line-height: 1.4;
            margin-bottom: 12px;
            display: block;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .news-title-link:hover {
            color: var(--primary-color);
        }

        .news-excerpt {
            color: var(--muted-color);
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: auto;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .news-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #9ca3af;
            font-size: 13px;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #e5e7eb;
        }

        .news-date {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .news-date i {
            font-size: 12px;
        }

        /* Pagination */
        .pagination-container {
            display: flex;
            justify-content: center;
            margin-top: 60px;
        }

        .pagination {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .page-link {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 42px;
            height: 42px;
            padding: 0 15px;
            background: white;
            color: var(--text-color);
            text-decoration: none;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-weight: 500;
            font-size: 14px;
        }

        .page-link:hover,
        .page-link.active {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        .page-link.disabled {
            opacity: 0.5;
            cursor: not-allowed;
            pointer-events: none;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 100px 20px;
        }

        .empty-state i {
            font-size: 80px;
            color: #d1d5db;
            margin-bottom: 25px;
        }

        .empty-state h3 {
            color: var(--muted-color);
            font-size: 24px;
            margin-bottom: 15px;
        }

        .empty-state p {
            color: #9ca3af;
            font-size: 16px;
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .news-grid {
                grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            }

            .featured-news {
                grid-column: span 2;
            }
        }

        @media (max-width: 992px) {
            .news-grid {
                grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            }

            .featured-news {
                grid-column: span 1;
                height: auto;
            }

            .featured-news .news-image {
                height: 250px;
            }

            .news-title {
                font-size: 36px;
            }
        }

        @media (max-width: 768px) {
            .top-info-bar {
                display: none;
            }

            .news-header {
                padding: 0 15px;
            }

            .news-title {
                font-size: 32px;
            }

            .news-subtitle {
                font-size: 16px;
            }

            .news-grid {
                grid-template-columns: 1fr;
                gap: 25px;
            }

            .featured-news .news-title-link {
                font-size: 24px;
            }

            .search-bar {
                padding: 0 15px;
            }
        }

        @media (max-width: 576px) {
            .main-content {
                padding: 40px 0;
            }

            .news-title {
                font-size: 28px;
            }

            .featured-news .news-content,
            .news-content {
                padding: 20px;
            }

            .news-image {
                height: 200px;
            }

            .featured-news .news-image {
                height: 220px;
            }
        }

        /* Loading Skeleton */
        .skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
        }

        @keyframes loading {
            0% {
                background-position: 200% 0;
            }
            100% {
                background-position: -200% 0;
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

            <!-- Search Bar -->
            <div class="news-controls">
                <div class="search-bar">
                    <form method="GET" action="index.php">
                        <input type="hidden" name="controller" value="berita">
                        <input type="hidden" name="action" value="public">
                        <div class="input-group">
                            <input
                                type="text"
                                class="form-control"
                                name="search"
                                placeholder="Cari berita..."
                                value="<?= htmlspecialchars($search ?? '') ?>"
                                aria-label="Cari berita">
                            <button class="btn" type="submit">
                                <i class="fas fa-search"></i> Cari
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <?php if (!empty($beritaList) && is_array($beritaList)): ?>
                <div class="news-container">
                    <div class="news-grid">
                        <?php foreach ($beritaList as $index => $news): ?>
                            <?php if ($index === 0): ?>
                                <!-- Featured News (First Item) -->
                                <div class="featured-news">
                                    <div class="news-image">
                                        <img src="<?= !empty($news['image']) ? htmlspecialchars($news['image']) : 'ppid_assets/images/default-news.jpg' ?>"
                                             alt="<?= htmlspecialchars($news['judul']) ?>">
                                        <span class="featured-badge"><i class="fas fa-star"></i> Berita Utama</span>
                                    </div>
                                    <div class="news-content">
                                        <a href="index.php?controller=berita&action=detail&id=<?= $news['id_berita'] ?>"
                                           class="news-title-link">
                                            <?= htmlspecialchars($news['judul']) ?>
                                        </a>
                                        <p class="news-excerpt">
                                            <?= htmlspecialchars(substr(strip_tags($news['summary']), 0, 250)) ?>...
                                        </p>
                                        <div class="news-meta">
                                            <div class="news-date">
                                                <i class="fas fa-calendar-alt"></i>
                                                <?php
                                                $date = new DateTime($news['created_at']);
                                                $days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                                                $months = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                                                          'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                                                echo $days[$date->format('w')] . ', ' . $date->format('d') . ' ' .
                                                     $months[(int)$date->format('n')] . ' ' . $date->format('Y');
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <!-- Regular News Card -->
                                <div class="news-card">
                                    <div class="news-image">
                                        <img src="<?= !empty($news['image']) ? htmlspecialchars($news['image']) : 'ppid_assets/images/default-news.jpg' ?>"
                                             alt="<?= htmlspecialchars($news['judul']) ?>">
                                    </div>
                                    <div class="news-content">
                                        <span class="news-category">
                                            <i class="fas fa-newspaper"></i> Berita
                                        </span>
                                        <a href="index.php?controller=berita&action=detail&id=<?= $news['id_berita'] ?>"
                                           class="news-title-link">
                                            <?= htmlspecialchars($news['judul']) ?>
                                        </a>
                                        <p class="news-excerpt">
                                            <?= htmlspecialchars(substr(strip_tags($news['summary']), 0, 120)) ?>...
                                        </p>
                                        <div class="news-meta">
                                            <div class="news-date">
                                                <i class="fas fa-calendar-alt"></i>
                                                <?php
                                                $date = new DateTime($news['created_at']);
                                                echo $date->format('d/m/Y');
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                <div class="pagination-container">
                    <div class="pagination">
                        <!-- Previous Button -->
                        <?php if ($page > 1): ?>
                            <a href="?controller=berita&action=public&page=<?= $page - 1 ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?>"
                               class="page-link">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        <?php else: ?>
                            <span class="page-link disabled">
                                <i class="fas fa-chevron-left"></i>
                            </span>
                        <?php endif; ?>

                        <!-- Page Numbers -->
                        <?php
                        $startPage = max(1, $page - 2);
                        $endPage = min($totalPages, $page + 2);

                        if ($startPage > 1): ?>
                            <a href="?controller=berita&action=public&page=1<?= !empty($search) ? '&search=' . urlencode($search) : '' ?>"
                               class="page-link">1</a>
                            <?php if ($startPage > 2): ?>
                                <span class="page-link disabled">...</span>
                            <?php endif; ?>
                        <?php endif; ?>

                        <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                            <a href="?controller=berita&action=public&page=<?= $i ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?>"
                               class="page-link <?= $i == $page ? 'active' : '' ?>">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>

                        <?php if ($endPage < $totalPages): ?>
                            <?php if ($endPage < $totalPages - 1): ?>
                                <span class="page-link disabled">...</span>
                            <?php endif; ?>
                            <a href="?controller=berita&action=public&page=<?= $totalPages ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?>"
                               class="page-link"><?= $totalPages ?></a>
                        <?php endif; ?>

                        <!-- Next Button -->
                        <?php if ($page < $totalPages): ?>
                            <a href="?controller=berita&action=public&page=<?= $page + 1 ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?>"
                               class="page-link">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        <?php else: ?>
                            <span class="page-link disabled">
                                <i class="fas fa-chevron-right"></i>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

            <?php else: ?>
                <!-- Empty State -->
                <div class="empty-state">
                    <i class="fas fa-newspaper"></i>
                    <h3>Belum Ada Berita</h3>
                    <p>Berita terbaru akan ditampilkan di sini</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php include 'template/layout/footer.php'; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
