# AGENTS.md - Panduan Pengembangan PPID Mandailing Natal

## 🏛️ Tentang Proyek

Sistem Informasi Publik Pejabat Pengelola Informasi dan Dokumentasi (SIP PPID) Mandailing Natal adalah aplikasi web untuk mengelola dan menyediakan informasi publik sesuai dengan ketentuan Undang-Undang Keterbukaan Informasi Publik.

## 🎯 Prinsip Pengembangan

### 1. Desain untuk Pemerintahan
- **Warna Resmi**:
  - Primary: `#1e3a8a` (Biru tua - profesional dan dapat dipercaya)
  - Secondary: `#f59e0b` (Kuning/Orange - energik dan accessible)
  - Accent: `#fbbf24` (Kuning cerah - untuk highlight)
- **Font**: Inter (clean, modern, highly readable)
- **Tone**: Formal namun ramah, menggunakan Bahasa Indonesia yang baku
- **Accessibility**: Harus memenuhi standar WCAG untuk aksesibilitas publik

### 2. Teknologi Stack
- **Backend**: PHP Native (tanpa framework)
- **Frontend**: HTML5, CSS3, JavaScript (Vanilla/ES6+)
- **UI Framework**: Bootstrap 5.3.0
- **Icons**: Font Awesome 6.4.0
- **Rich Text Editor**: TinyMCE
- **Animation**: AOS (Animate On Scroll)
- **Database**: MySQL/MariaDB

## 📁 Struktur Proyek

```
ppid-mandailing/
├── index.php                 # Entry point dengan routing
├── template.php              # Template untuk admin panel
├── controllers/              # Controller untuk MVC pattern
├── models/                   # Model untuk database operations
├── views/                    # View files
│   ├── auth/                # Halaman autentikasi
│   ├── beranda/             # Halaman public
│   └── [module]/            # Module-specific views
├── template/                 # Komponen template admin
│   ├── header.php
│   ├── navbar.php
│   ├── sidebar.php
│   ├── setting_panel.php
│   ├── script.php
│   └── layout/              # Komponen layout public
│       ├── navbar_beranda.php
│       └── footer.php
├── ppid_assets/             # Assets statis
├── uploads/                 # File uploads
└── vendor/                  # Dependencies (Composer)
```

## 🎨 Konvensi Kode

### Penamaan File
```
✅ BENAR:
- navbar_beranda.php (snake_case untuk PHP)
- setting_panel.php
- berita_controller.php

❌ SALAH:
- NavbarBeranda.php (PascalCase)
- settingPanel.php (camelCase)
- berita-controller.php (kebab-case)
```

### Penamaan Variabel

#### PHP (snake_case)
```php
✅ BENAR:
$nama_lengkap = "John Doe";
$upload_ktp = $_FILES['upload_ktp'];
$data['slider'] = $slider_data;

❌ SALAH:
$namaLengkap = "John Doe";
$uploadKtp = $_FILES['upload_ktp'];
```

#### JavaScript (camelCase)
```javascript
✅ BENAR:
const bannerTrack = document.getElementById('bannerTrack');
let currentIndex = 0;
function updateBannerPosition() {}

❌ SALAH:
const banner_track = document.getElementById('bannerTrack');
let current_index = 0;
```

#### CSS Classes (kebab-case)
```css
✅ BENAR:
.navbar-custom { }
.banner-container { }
.info-card-header { }

❌ SALAH:
.navbarCustom { }
.banner_container { }
.infoCardHeader { }
```

## 🏗️ Pattern dan Arsitektur

### MVC Pattern
```php
// URL Pattern
index.php?controller=auth&action=login
index.php?controller=berita&action=show&id=123

// Controller Pattern
class BeritaController {
    public function index() {
        // Logic untuk menampilkan daftar berita
        $data['berita'] = $this->model->getAllBerita();
        include 'views/berita/index.php';
    }
}
```

### Template Admin Panel
```php
<?php include('template/header.php'); ?>

<body class="with-welcome-text">
  <div class="container-scroller">
    <?php include 'template/navbar.php'; ?>
    <div class="container-fluid page-body-wrapper">
      <?php include 'template/setting_panel.php'; ?>
      <?php include 'template/sidebar.php'; ?>
      <div class="main-panel">
        <div class="content-wrapper">
          <!-- Konten admin di sini -->
        </div>
      </div>
    </div>
  </div>
  <?php include 'template/script.php'; ?>
</body>
</html>
```

### Template Public Pages
```php
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Judul Halaman - PPID Mandailing Natal</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* CSS Variables */
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
        }

        /* Component styles di sini */
    </style>
</head>

<body>
    <?php include 'template/layout/navbar_beranda.php'; ?>

    <!-- Konten utama -->

    <?php include 'template/layout/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // JavaScript di sini
    </script>
</body>
</html>
```

## 💅 Styling Guidelines

### CSS Variables (Gunakan Konsisten)
```css
:root {
    --primary-color: #1e3a8a;
    --secondary-color: #f59e0b;
    --accent-color: #fbbf24;
    --text-color: #1f2937;
    --muted-color: #6b7280;
    --light-bg: #f8f9fa;
}

/* Penggunaan */
.button-primary {
    background-color: var(--primary-color);
    color: white;
}
```

### Responsive Design
```css
/* Mobile First Approach */

/* Base styles untuk mobile */
.container {
    padding: 15px;
}

/* Tablet */
@media (max-width: 992px) {
    .sidebar {
        display: none;
    }
}

/* Desktop */
@media (max-width: 768px) {
    .navbar-nav {
        flex-direction: column;
    }
}

/* Small mobile */
@media (max-width: 576px) {
    .section-title {
        font-size: 1.5rem;
    }
}
```

### Card Pattern (Digunakan di Seluruh Aplikasi)
```css
.card {
    border: none;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.15);
}
```

## 🎭 Component Patterns

### Form Pattern
```html
<div class="form-group">
    <label for="field_name" class="form-label">
        Label Text <span class="required">*</span>
    </label>
    <input
        type="text"
        class="form-control"
        id="field_name"
        name="field_name"
        value="<?php echo isset($_POST['field_name']) ? htmlspecialchars($_POST['field_name']) : ''; ?>"
        required>
</div>
```

### Alert Pattern
```php
<?php if (!empty($error)): ?>
    <div class="alert alert-danger" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <?php echo htmlspecialchars($error); ?>
    </div>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <div class="alert alert-success" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        <?php echo htmlspecialchars($success); ?>
    </div>
<?php endif; ?>
```

### Modal Pattern
```html
<div id="myModal" class="modal-custom">
    <div class="modal-content">
        <button class="modal-close" onclick="closeModal()">
            <i class="fas fa-times"></i>
        </button>
        <div class="modal-header">
            <img id="modalImage" src="" alt="" class="modal-image">
        </div>
        <div class="modal-body">
            <h1 id="modalTitle"></h1>
            <div id="modalContent"></div>
        </div>
    </div>
</div>
```

### Data Grid Pattern
```html
<div class="info-grid">
    <?php if (!empty($data['items'])): ?>
        <?php foreach($data['items'] as $index => $item): ?>
        <div class="info-item" data-aos="fade-up" data-aos-delay="<?= $index * 50 ?>">
            <div class="card info-card h-100">
                <!-- Card content -->
            </div>
        </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12">
            <div class="text-center py-5">
                <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">Belum Ada Data</h4>
                <p class="text-muted">Data akan ditampilkan di sini</p>
            </div>
        </div>
    <?php endif; ?>
</div>
```

## 🔒 Security Best Practices

### XSS Prevention
```php
// SELALU escape output
<?php echo htmlspecialchars($user_input, ENT_QUOTES, 'UTF-8'); ?>

// Untuk attribute
<input value="<?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8') ?>">

// Untuk JSON dalam JavaScript
<script>
const data = <?= htmlspecialchars(json_encode($data), ENT_QUOTES, 'UTF-8') ?>;
</script>
```

### File Upload Validation
```php
// Validasi tipe file
$allowed_types = ['image/jpeg', 'image/png', 'application/pdf'];
$file_type = $_FILES['upload']['type'];

if (!in_array($file_type, $allowed_types)) {
    $error = "Tipe file tidak diizinkan";
}

// Validasi ukuran
$max_size = 5 * 1024 * 1024; // 5MB
if ($_FILES['upload']['size'] > $max_size) {
    $error = "Ukuran file terlalu besar";
}

// Generate nama file unik
$timestamp = time();
$random = uniqid();
$extension = pathinfo($_FILES['upload']['name'], PATHINFO_EXTENSION);
$filename = $nik . '_' . $type . '_' . $timestamp . '.' . $extension;
```

### SQL Injection Prevention
```php
// SELALU gunakan prepared statements
$stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND status = ?");
$stmt->bind_param("ss", $email, $status);
$stmt->execute();
```

## 📱 Responsive Behavior

### Breakpoints (Sesuai Bootstrap 5)
```
- Extra Small (xs): < 576px (Mobile)
- Small (sm): ≥ 576px (Large Mobile)
- Medium (md): ≥ 768px (Tablet)
- Large (lg): ≥ 992px (Desktop)
- Extra Large (xl): ≥ 1200px (Large Desktop)
```

### Mobile Menu Pattern
```javascript
// Toggle mobile menu
document.getElementById('mobileMenuBtn').addEventListener('click', function() {
    document.getElementById('navLinks').classList.toggle('show');
});

// Close when clicking outside
document.addEventListener('click', function(event) {
    const navLinks = document.getElementById('navLinks');
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    if (!navLinks.contains(event.target) && !mobileMenuBtn.contains(event.target)) {
        navLinks.classList.remove('show');
    }
});
```

## 🎬 Animation Guidelines

### AOS (Animate On Scroll)
```html
<!-- Fade in -->
<section data-aos="fade-in">

<!-- Fade up with delay -->
<div data-aos="fade-up" data-aos-delay="100">

<!-- Initialization -->
<script>
AOS.init({
    duration: 1000,
    once: false,
    offset: 100
});
</script>
```

### CSS Transitions
```css
/* Konsisten gunakan timing yang sama */
.element {
    transition: all 0.3s ease;
}

/* Untuk hover effects */
.card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.15);
}
```

## 📊 Data Handling

### Passing Data ke View
```php
// Controller
$data = [
    'slider' => $slider_model->getActiveSliders(),
    'berita' => $berita_model->getLatestNews(6),
    'informasi' => $info_model->getPublicInfo(),
    'statistik' => [
        [
            'icon' => 'fas fa-users',
            'value' => '1,234',
            'label' => 'Pengguna Terdaftar',
            'growth' => '+12%',
            'description' => 'dari bulan lalu'
        ]
    ]
];

include 'views/beranda/index.php';
```

### Loop Pattern
```php
<?php foreach($data['items'] as $index => $item): ?>
    <div class="item" data-index="<?= $index ?>">
        <h3><?= htmlspecialchars($item['title']) ?></h3>
        <p><?= htmlspecialchars($item['description']) ?></p>
    </div>
<?php endforeach; ?>
```

## 🌐 Internationalization (Bahasa Indonesia)

### Format Tanggal
```php
// Convert ke format Indonesia
$date = new DateTime($item['published_at']);
$days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
$months = [
    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
];
$dayName = $days[$date->format('w')];
$day = $date->format('d');
$month = $months[(int)$date->format('n')];
$year = $date->format('Y');
$indonesianDate = "$dayName, $day $month $year";
```

### Pesan Error/Success (Bahasa Indonesia Formal)
```php
$messages = [
    'success_register' => 'Pendaftaran berhasil. Silakan login dengan akun Anda.',
    'error_email_exists' => 'Email sudah terdaftar. Silakan gunakan email lain.',
    'error_file_upload' => 'Gagal mengunggah file. Silakan coba lagi.',
    'success_submit' => 'Data berhasil disimpan.',
    'error_validation' => 'Data yang Anda masukkan tidak valid.',
];
```

## ⚡ Performance Best Practices

### Loading States
```javascript
// Tampilkan loading saat proses
const button = document.querySelector('.btn-submit');
const originalText = button.innerHTML;

button.innerHTML = '<span class="spinner me-2"></span>Memproses...';
button.disabled = true;

// Setelah proses selesai
setTimeout(() => {
    button.innerHTML = originalText;
    button.disabled = false;
}, 1000);
```

### Lazy Loading untuk Image
```html
<img src="placeholder.jpg"
     data-src="actual-image.jpg"
     class="lazy-load"
     alt="Description">
```

### Debounce untuk Search
```javascript
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Penggunaan
searchInput.addEventListener('input', debounce(function() {
    performSearch(this.value);
}, 250));
```

## 🎯 PPID Specific Features

### Kategori Informasi Publik
```php
$categories = [
    'berkala' => 'Informasi Berkala',
    'serta-merta' => 'Informasi Serta Merta',
    'setiap-saat' => 'Informasi Setiap Saat'
];
```

### Status Pengguna
```php
$user_status = [
    'pribadi' => 'Pribadi/Perseorangan',
    'lembaga' => 'Lembaga/Organisasi'
];
```

### Document Types
```php
function getInfoIcon($fileType) {
    switch(strtolower($fileType)) {
        case 'pdf': return 'fas fa-file-pdf';
        case 'doc':
        case 'docx': return 'fas fa-file-word';
        case 'xls':
        case 'xlsx': return 'fas fa-file-excel';
        case 'ppt':
        case 'pptx': return 'fas fa-file-powerpoint';
        case 'jpg':
        case 'jpeg':
        case 'png': return 'fas fa-file-image';
        default: return 'fas fa-file-alt';
    }
}
```

## 🧪 Testing Checklist

### Sebelum Deploy
- [ ] Test semua form validation
- [ ] Test file upload (size, type, security)
- [ ] Test responsive di berbagai device
- [ ] Test browser compatibility (Chrome, Firefox, Safari, Edge)
- [ ] Test accessibility dengan screen reader
- [ ] Validasi semua XSS prevention
- [ ] Test SQL injection prevention
- [ ] Validasi format tanggal Indonesia
- [ ] Test semua modal dan popup
- [ ] Test mobile menu
- [ ] Test search dan filter functionality

## 🚀 Development Workflow

### 1. Menambah Fitur Baru
```
1. Buat branch baru (jika menggunakan git)
2. Buat controller di folder controllers/
3. Buat model di folder models/ (jika perlu)
4. Buat view di folder views/
5. Update routing di index.php
6. Test fungsionalitas
7. Commit dan push
```

### 2. Menambah Halaman Public Baru
```php
// 1. Buat file di views/nama_halaman/index.php
// 2. Gunakan template pattern:

<!DOCTYPE html>
<html lang="id">
<head>
    <!-- Head content -->
</head>
<body>
    <?php include 'template/layout/navbar_beranda.php'; ?>

    <!-- Content -->

    <?php include 'template/layout/footer.php'; ?>
</body>
</html>
```

### 3. Menambah Halaman Admin Baru
```php
// Gunakan template.php pattern
<?php include('template/header.php'); ?>

<body class="with-welcome-text">
  <div class="container-scroller">
    <?php include 'template/navbar.php'; ?>
    <div class="container-fluid page-body-wrapper">
      <?php include 'template/setting_panel.php'; ?>
      <?php include 'template/sidebar.php'; ?>
      <div class="main-panel">
        <div class="content-wrapper">
          <!-- Admin content here -->
        </div>
      </div>
    </div>
  </div>
  <?php include 'template/script.php'; ?>
</body>
</html>
```

## 📝 Code Comments

### PHP Comments
```php
// Single line comment untuk penjelasan singkat

/**
 * Multi-line comment untuk fungsi penting
 *
 * @param string $email Email pengguna
 * @param string $password Password pengguna
 * @return bool Status login
 */
function login($email, $password) {
    // Implementation
}
```

### JavaScript Comments
```javascript
// Single line untuk logic sederhana

/**
 * Fungsi untuk update posisi banner
 * Mengatur transform translateX berdasarkan currentIndex
 */
function updateBannerPosition() {
    // Implementation
}
```

## 🎨 Icon Usage (Font Awesome)

### Common Icons
```html
<!-- Navigation -->
<i class="fas fa-home"></i> Beranda
<i class="fas fa-info-circle"></i> Informasi
<i class="fas fa-newspaper"></i> Berita
<i class="fas fa-user"></i> Profil

<!-- Actions -->
<i class="fas fa-download"></i> Download
<i class="fas fa-upload"></i> Upload
<i class="fas fa-edit"></i> Edit
<i class="fas fa-trash"></i> Hapus
<i class="fas fa-eye"></i> Lihat
<i class="fas fa-share-alt"></i> Bagikan

<!-- Status -->
<i class="fas fa-check-circle"></i> Success
<i class="fas fa-exclamation-triangle"></i> Warning
<i class="fas fa-times-circle"></i> Error
<i class="fas fa-info-circle"></i> Info

<!-- Documents -->
<i class="fas fa-file-pdf"></i> PDF
<i class="fas fa-file-word"></i> Word
<i class="fas fa-file-excel"></i> Excel
<i class="fas fa-file-image"></i> Image
```

## 🔍 SEO Best Practices

### Meta Tags
```html
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="Sistem Informasi Publik PPID Mandailing Natal">
    <meta name="keywords" content="PPID, Mandailing Natal, Informasi Publik">
    <meta name="author" content="PPID Mandailing Natal">
    <title>Judul Halaman - PPID Mandailing Natal</title>
</head>
```

### Semantic HTML
```html
<header>
    <nav>...</nav>
</header>

<main>
    <section>
        <article>...</article>
    </section>
</main>

<footer>...</footer>
```

## ♿ Accessibility Guidelines

### ARIA Labels
```html
<button aria-label="Tutup menu" class="close-btn">
    <i class="fas fa-times"></i>
</button>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">...</ol>
</nav>
```

### Keyboard Navigation
```javascript
// Support Enter dan Space untuk custom buttons
element.addEventListener('keydown', function(e) {
    if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        handleAction();
    }
});
```

### Focus Styles
```css
.form-control:focus,
.btn:focus {
    outline: 2px solid #3b82f6;
    outline-offset: 2px;
}
```

## 📚 Resources & References

### Official Documentation
- [Bootstrap 5 Docs](https://getbootstrap.com/docs/5.3/)
- [Font Awesome Icons](https://fontawesome.com/icons)
- [PHP Manual](https://www.php.net/manual/en/)
- [MDN Web Docs](https://developer.mozilla.org/)

### Design Inspiration
- [Dribbble - Government Websites](https://dribbble.com/tags/government)
- [Awwwards - Government](https://www.awwwards.com/websites/government/)

### Tools
- [Can I Use](https://caniuse.com/) - Browser compatibility
- [WebAIM](https://webaim.org/) - Accessibility checker
- [Google PageSpeed Insights](https://pagespeed.web.dev/) - Performance

---

## 📌 Quick Reference

### Warna Palette
```css
--primary-color: #1e3a8a;      /* Biru Tua */
--secondary-color: #f59e0b;    /* Orange */
--accent-color: #fbbf24;       /* Kuning */
--text-color: #1f2937;         /* Abu Gelap */
--muted-color: #6b7280;        /* Abu */
--light-bg: #f8f9fa;           /* Abu Terang */
```

### Common Spacing
```css
padding: 15px;    /* Small */
padding: 30px;    /* Medium */
padding: 60px;    /* Large */
margin-bottom: 20px;  /* Small */
margin-bottom: 40px;  /* Medium */
gap: 30px;        /* Grid gap */
```

### Common Border Radius
```css
border-radius: 8px;   /* Form inputs */
border-radius: 12px;  /* Cards */
border-radius: 16px;  /* Large containers */
border-radius: 50px;  /* Pills/buttons */
border-radius: 50%;   /* Circles */
```

---

**Last Updated**: 2025
**Version**: 1.0.0
**Maintainer**: PPID Mandailing Natal Development Team
