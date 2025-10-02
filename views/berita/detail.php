<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $pageInfo['title'] ?? 'Detail Berita - PPID Mandailing Natal' ?></title>
    <meta name="description" content="<?= $pageInfo['description'] ?? '' ?>">

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
        .article-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 60px 15px;
        }

        .article-header {
            margin-bottom: 30px;
        }

        .article-title {
            font-size: 42px;
            font-weight: 800;
            line-height: 1.2;
            color: var(--text-color);
            margin-bottom: 20px;
            letter-spacing: -0.5px;
        }

        .article-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            align-items: center;
            padding: 20px 0;
            border-top: 2px solid #e5e7eb;
            border-bottom: 2px solid #e5e7eb;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--muted-color);
            font-size: 14px;
        }

        .meta-item i {
            color: var(--primary-color);
            font-size: 16px;
        }

        .article-image {
            width: 100%;
            max-height: 550px;
            object-fit: cover;
            border-radius: 16px;
            margin: 30px 0;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }

        .article-content {
            font-size: 18px;
            line-height: 1.8;
            color: #374151;
            margin: 40px 0;
        }

        .article-content p {
            margin-bottom: 1.5em;
            text-align: justify;
        }

        .article-content h2 {
            font-size: 28px;
            font-weight: 700;
            margin: 40px 0 20px;
            color: var(--text-color);
        }

        .article-content h3 {
            font-size: 24px;
            font-weight: 600;
            margin: 30px 0 15px;
            color: var(--text-color);
        }

        /* Share Buttons */
        .share-section {
            margin: 50px 0;
            padding: 30px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        .share-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
            color: var(--text-color);
        }

        .share-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }

        .share-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            color: white;
            font-weight: 500;
            font-size: 14px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .share-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            color: white;
        }

        .share-btn-facebook {
            background: #1877f2;
        }

        .share-btn-twitter {
            background: #1da1f2;
        }

        .share-btn-whatsapp {
            background: #25d366;
        }

        .share-btn-copy {
            background: #6b7280;
        }

        /* Related News */
        .related-news {
            margin-top: 80px;
        }

        .related-title {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 40px;
            color: var(--text-color);
            text-align: center;
        }

        .related-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 30px;
        }

        .related-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            text-decoration: none;
            display: block;
        }

        .related-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }

        .related-image {
            height: 180px;
            overflow: hidden;
        }

        .related-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .related-card:hover .related-image img {
            transform: scale(1.08);
        }

        .related-content {
            padding: 20px;
        }

        .related-card-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-color);
            line-height: 1.4;
            margin-bottom: 10px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .related-date {
            color: var(--muted-color);
            font-size: 13px;
        }

        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 12px 24px;
            background: var(--primary-color);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
            margin-bottom: 30px;
        }

        .back-button:hover {
            background: #1e40af;
            transform: translateX(-5px);
            color: white;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .article-container {
                padding: 40px 15px;
            }

            .article-title {
                font-size: 32px;
            }

            .article-content {
                font-size: 16px;
            }

            .article-meta {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }

            .share-buttons {
                flex-direction: column;
            }

            .share-btn {
                justify-content: center;
                width: 100%;
            }

            .related-grid {
                grid-template-columns: 1fr;
            }

            .related-title {
                font-size: 28px;
            }
        }

        @media (max-width: 576px) {
            .article-title {
                font-size: 28px;
            }

            .article-content {
                font-size: 15px;
            }

            .article-image {
                border-radius: 8px;
                max-height: 300px;
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
                    <li class="breadcrumb-item"><a href="index.php?controller=berita&action=public">Berita</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Detail</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Main Content -->
    <div class="article-container">
        <a href="index.php?controller=berita&action=public" class="back-button">
            <i class="fas fa-arrow-left"></i>
            Kembali ke Daftar Berita
        </a>

        <article>
            <!-- Article Header -->
            <header class="article-header">
                <h1 class="article-title"><?= htmlspecialchars($berita['judul']) ?></h1>

                <div class="article-meta">
                    <div class="meta-item">
                        <i class="fas fa-calendar-alt"></i>
                        <span>
                            <?php
                            $date = new DateTime($berita['created_at']);
                            $days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                            $months = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                                      'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                            echo $days[$date->format('w')] . ', ' . $date->format('d') . ' ' .
                                 $months[(int)$date->format('n')] . ' ' . $date->format('Y');
                            ?>
                        </span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-clock"></i>
                        <span><?= $date->format('H:i') ?> WIB</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-user"></i>
                        <span>Admin PPID</span>
                    </div>
                </div>
            </header>

            <!-- Article Image -->
            <?php if (!empty($berita['image'])): ?>
            <img src="<?= htmlspecialchars($berita['image']) ?>"
                 alt="<?= htmlspecialchars($berita['judul']) ?>"
                 class="article-image">
            <?php endif; ?>

            <!-- Article Content -->
            <div class="article-content">
                <?php
                // Format the content: convert line breaks to paragraphs
                $content = $berita['summary'];
                $paragraphs = explode("\n\n", $content);
                foreach ($paragraphs as $paragraph) {
                    if (!empty(trim($paragraph))) {
                        echo '<p>' . nl2br(htmlspecialchars(trim($paragraph))) . '</p>';
                    }
                }
                ?>
            </div>

            <!-- Share Section -->
            <div class="share-section">
                <h3 class="share-title">
                    <i class="fas fa-share-alt"></i> Bagikan Berita Ini
                </h3>
                <div class="share-buttons">
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) ?>"
                       class="share-btn share-btn-facebook"
                       target="_blank"
                       rel="noopener noreferrer">
                        <i class="fab fa-facebook-f"></i>
                        Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?url=<?= urlencode((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) ?>&text=<?= urlencode($berita['judul']) ?>"
                       class="share-btn share-btn-twitter"
                       target="_blank"
                       rel="noopener noreferrer">
                        <i class="fab fa-twitter"></i>
                        Twitter
                    </a>
                    <a href="https://api.whatsapp.com/send?text=<?= urlencode($berita['judul'] . ' ' . ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'])) ?>"
                       class="share-btn share-btn-whatsapp"
                       target="_blank"
                       rel="noopener noreferrer">
                        <i class="fab fa-whatsapp"></i>
                        WhatsApp
                    </a>
                    <button onclick="copyToClipboard()" class="share-btn share-btn-copy">
                        <i class="fas fa-link"></i>
                        Salin Link
                    </button>
                </div>
            </div>
        </article>

        <!-- Related News -->
        <?php if (!empty($relatedNews)): ?>
        <section class="related-news">
            <h2 class="related-title">Berita Terkait</h2>
            <div class="related-grid">
                <?php foreach ($relatedNews as $related): ?>
                <a href="index.php?controller=berita&action=detail&id=<?= $related['id_berita'] ?>"
                   class="related-card">
                    <div class="related-image">
                        <img src="<?= !empty($related['image']) ? htmlspecialchars($related['image']) : 'ppid_assets/images/default-news.jpg' ?>"
                             alt="<?= htmlspecialchars($related['judul']) ?>">
                    </div>
                    <div class="related-content">
                        <h3 class="related-card-title"><?= htmlspecialchars($related['judul']) ?></h3>
                        <p class="related-date">
                            <i class="fas fa-calendar-alt"></i>
                            <?php
                            $relatedDate = new DateTime($related['created_at']);
                            echo $relatedDate->format('d/m/Y');
                            ?>
                        </p>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>
    </div>

    <?php include 'template/layout/footer.php'; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Copy to clipboard function
        function copyToClipboard() {
            const url = window.location.href;

            // Modern browsers
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(url).then(() => {
                    alert('Link berhasil disalin ke clipboard!');
                }).catch(() => {
                    fallbackCopyToClipboard(url);
                });
            } else {
                // Fallback for older browsers
                fallbackCopyToClipboard(url);
            }
        }

        // Fallback copy to clipboard
        function fallbackCopyToClipboard(text) {
            const textArea = document.createElement('textarea');
            textArea.value = text;
            textArea.style.position = 'fixed';
            textArea.style.left = '-999999px';
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();

            try {
                document.execCommand('copy');
                alert('Link berhasil disalin ke clipboard!');
            } catch (err) {
                alert('Gagal menyalin link. Silakan salin secara manual.');
            }

            document.body.removeChild(textArea);
        }
    </script>
</body>
</html>
