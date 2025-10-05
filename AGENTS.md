# AGENTS.md - Panduan Pengembangan PPID Mandailing Natal

## 🏛️ Tentang Proyek

Sistem Informasi Publik Pejabat Pengelola Informasi dan Dokumentasi (SIP PPID) Mandailing Natal adalah aplikasi web untuk mengelola dan menyediakan informasi publik sesuai dengan ketentuan Undang-Undang Keterbukaan Informasi Publik.

## 🎯 Prinsip Pengembangan

### 1. Desain untuk Pemerintahan
- **Warna Resmi**:
  - Primary: `#1e3a8a` (Biru tua - profesional dan dapat dipercaya)
  - Secondary: `#f59e0b` (Kuning/Orange - energik dan accessible)
  - Accent: `#fbbf24` (Kuning cerah - untuk highlight)
- **Font**: Inter (bersih, modern, mudah dibaca)
- **Tone**: Formal namun ramah, menggunakan Bahasa Indonesia yang baku
- **Aksesibilitas**: Harus memenuhi standar WCAG untuk aksesibilitas publik

### 2. Teknologi yang Digunakan
- **Backend**: PHP Native (tanpa framework)
- **Frontend**: HTML5, CSS3, JavaScript (Vanilla/ES6+)
- **Kerangka UI**: Bootstrap 5.3.0
- **Ikon**: Font Awesome 6.4.0
- **Editor Teks**: TinyMCE
- **Animasi**: AOS (Animate On Scroll)
- **Basis Data**: MySQL/MariaDB

## 📁 Struktur Proyek

```
ppid-mandailing/
├── index.php                 # Titik masuk dengan routing
├── template.php              # Template untuk panel admin
├── controllers/              # Controller untuk pola MVC
├── models/                   # Model untuk operasi basis data
├── views/                    # File tampilan
│   ├── auth/                # Halaman autentikasi
│   ├── beranda/             # Halaman publik
│   └── [module]/            # Tampilan spesifik modul
├── template/                 # Komponen template admin
│   ├── header.php
│   ├── navbar.php
│   ├── sidebar.php
│   ├── setting_panel.php
│   ├── script.php
│   └── layout/              # Komponen layout publik
│       ├── navbar_beranda.php
│       └── footer.php
├── ppid_assets/             # Aset statis
├── uploads/                 # Unggahan file
└── vendor/                  # Dependensi (Composer)
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

## 🏗️ Pola dan Arsitektur

### Pola MVC
```php
// Pola URL
index.php?controller=auth&action=login
index.php?controller=berita&action=show&id=123

// Pola Controller
class BeritaController {
    public function index() {
        // Logika untuk menampilkan daftar berita
        $data['berita'] = $this->model->getAllBerita();
        include 'views/berita/index.php';
    }
}
```

### Template Panel Admin
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

### Template Halaman Publik
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

## 💅 Panduan Styling

### Variabel CSS (Gunakan Konsisten)
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

### Desain Responsif
```css
/* Pendekatan Mobile First */

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

### Pola Card (Digunakan di Seluruh Aplikasi)
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

## 🎭 Pola Komponen

### Pola Form
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

### Pola Alert
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

### Pola Modal
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

### Pola Data Grid
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

## 🔒 Praktik Keamanan Terbaik

### Pencegahan XSS
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

### Validasi Unggahan File
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

### Pencegahan SQL Injection
```php
// SELALU gunakan prepared statements
$stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND status = ?");
$stmt->bind_param("ss", $email, $status);
$stmt->execute();
```

## 📱 Perilaku Responsif

### Breakpoint (Sesuai Bootstrap 5)
```
- Extra Small (xs): < 576px (Mobile)
- Small (sm): ≥ 576px (Large Mobile)
- Medium (md): ≥ 768px (Tablet)
- Large (lg): ≥ 992px (Desktop)
- Extra Large (xl): ≥ 1200px (Large Desktop)
```

### Pola Menu Mobile
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

## 🎬 Panduan Animasi

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

### Transisi CSS
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

## 📊 Penanganan Data

### Mengirim Data ke View
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

### Pola Loop
```php
<?php foreach($data['items'] as $index => $item): ?>
    <div class="item" data-index="<?= $index ?>">
        <h3><?= htmlspecialchars($item['title']) ?></h3>
        <p><?= htmlspecialchars($item['description']) ?></p>
    </div>
<?php endforeach; ?>
```

## 🌐 Internasionalisasi (Bahasa Indonesia)

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

## ⚡ Praktik Performa Terbaik

### Status Loading
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

### Lazy Loading untuk Gambar
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

## ✅ Do's and Don'ts

### 📋 Naming Conventions

#### ✅ DO:
```php
// PHP - snake_case
$nama_layanan = "Informasi Publik";
$sub_layanan_2 = "Berkala";
$id_kategori = 1;

function updateSubLayanan($id, $sub_layanan, $sub_layanan_2) {
    // Implementation
}
```

```javascript
// JavaScript - camelCase
const bannerTrack = document.getElementById('bannerTrack');
let currentIndex = 0;
function updateBannerPosition() {}
```

```css
/* CSS - kebab-case */
.navbar-custom { }
.dropdown-sub-kategori { }
.content-wrapper { }
```

#### ❌ DON'T:
```php
// ❌ JANGAN gunakan camelCase di PHP
$namaLayanan = "Wrong";
$subLayanan2 = "Wrong";

// ❌ JANGAN gunakan PascalCase untuk variable
$NamaLayanan = "Wrong";
```

```javascript
// ❌ JANGAN gunakan snake_case di JavaScript
const banner_track = document.getElementById('bannerTrack'); // Wrong
let current_index = 0; // Wrong
```

```css
/* ❌ JANGAN gunakan camelCase atau snake_case di CSS */
.navbarCustom { } /* Wrong */
.dropdown_sub_kategori { } /* Wrong */
```

### 🔒 Security & Data Handling

#### ✅ DO:
```php
// SELALU escape output untuk prevent XSS
echo htmlspecialchars($user_input, ENT_QUOTES, 'UTF-8');

// SELALU gunakan prepared statements
$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();

// SELALU validasi file upload
$allowed_types = ['image/jpeg', 'image/png', 'application/pdf'];
if (!in_array($_FILES['upload']['type'], $allowed_types)) {
    $error = "Tipe file tidak diizinkan";
}

// SELALU cek ukuran file
$max_size = 5 * 1024 * 1024; // 5MB
if ($_FILES['upload']['size'] > $max_size) {
    $error = "Ukuran file terlalu besar";
}
```

#### ❌ DON'T:
```php
// ❌ JANGAN output langsung tanpa escape
echo $user_input; // XSS vulnerability!

// ❌ JANGAN gunakan string concatenation untuk SQL
$query = "SELECT * FROM users WHERE email = '$email'"; // SQL Injection!

// ❌ JANGAN terima semua tipe file
move_uploaded_file($_FILES['upload']['tmp_name'], $destination); // Dangerous!

// ❌ JANGAN skip validasi ukuran file
// No size check = server resource abuse
```

### 🎨 UI/UX Patterns

#### ✅ DO:
```php
// Selalu gunakan template pattern untuk admin
<?php include('template/header.php'); ?>
<body class="with-welcome-text">
  <div class="container-scroller">
    <?php include 'template/navbar.php'; ?>
    <!-- Content -->
  </div>
</body>

// Selalu gunakan template pattern untuk public
<?php include 'template/layout/navbar_beranda.php'; ?>
<!-- Content -->
<?php include 'template/layout/footer.php'; ?>

// Selalu beri feedback ke user
if ($success) {
    $_SESSION['success'] = 'Data berhasil disimpan!';
}

// Selalu cek empty state
<?php if (!empty($data['items'])): ?>
    <!-- Show items -->
<?php else: ?>
    <div class="empty-state">
        <i class="fas fa-folder-open"></i>
        <h4>Belum Ada Data</h4>
    </div>
<?php endif; ?>
```

#### ❌ DON'T:
```php
// ❌ JANGAN buat template dari scratch
<html>
<head>
    <!-- Duplicating all header code -->
</head>
<!-- Wrong approach! Use template -->

// ❌ JANGAN silent failure tanpa feedback
if ($error) {
    // No message to user - bad UX!
    header('Location: index.php');
}

// ❌ JANGAN tampilkan empty table/grid
<table>
    <thead>...</thead>
    <tbody>
        <!-- Empty tbody - confusing for users -->
    </tbody>
</table>
```

### 🎯 Dropdown Menu Hierarchy

#### ✅ DO:
```php
// Selalu konsisten dengan icon chevron
<a href="#" class="dropdown-kategori">
    Kategori
    <i class="fas fa-chevron-right kategori-icon"></i>
</a>

// Selalu support 3 level jika diperlukan
Level 1: nama_layanan (direct atau parent)
  └─ Level 2: sub_layanan (direct atau parent)
      └─ Level 3: sub_layanan_2 (always direct link)

// Selalu tampilkan judul yang paling spesifik
if (!empty($sub_layanan_2)) {
    echo $sub_layanan_2; // Most specific
} elseif (!empty($sub_layanan)) {
    echo $sub_layanan;
} else {
    echo $nama_layanan;
}
```

#### ❌ DON'T:
```php
// ❌ JANGAN inconsistent icon size/style
.kategori-icon { font-size: 12px; }
.sub-kategori-icon { font-size: 10px; } // Different!

// ❌ JANGAN double judul
<h2>
    Regulasi
    <br>Klasifikasi
    <br>Informasi Berkala  <!-- Too many levels! -->
</h2>

// ❌ JANGAN skip chevron untuk item dengan child
<a href="#">Kategori</a> <!-- No icon, confusing! -->
```

### 📱 Responsive Design

#### ✅ DO:
```css
/* Selalu mobile-first approach */
.container {
    padding: 15px; /* Mobile */
}

@media (min-width: 768px) {
    .container {
        padding: 30px; /* Tablet+ */
    }
}

/* Selalu test di breakpoints Bootstrap */
/* xs: <576px, sm: ≥576px, md: ≥768px, lg: ≥992px, xl: ≥1200px */

/* Selalu support mobile menu */
@media (max-width: 992px) {
    .navbar-nav {
        display: none;
        position: absolute;
        /* Mobile menu styles */
    }
    .navbar-nav.show {
        display: flex;
    }
}
```

#### ❌ DON'T:
```css
/* ❌ JANGAN desktop-first */
.container {
    padding: 60px; /* Desktop */
}
@media (max-width: 768px) {
    .container {
        padding: 15px; /* Mobile as afterthought */
    }
}

/* ❌ JANGAN fixed width tanpa responsive */
.sidebar {
    width: 300px; /* Breaks on mobile! */
}

/* ❌ JANGAN skip mobile menu */
/* No mobile navigation = unusable on phones */
```

### 📁 File Creation & Modification

#### ✅ DO:
```php
// HANYA edit file yang diminta
// User: "Update navbar_beranda.php"
// Action: Edit navbar_beranda.php ONLY

// HANYA create file jika EXPLICITLY diminta
// User: "Buatkan file README.md"
// Action: Create README.md

// SELALU tanya jika tidak jelas
// User: "Update dokumentasi"
// Response: "Apakah Anda ingin saya update file AGENTS.md yang ada,
//           atau buat file dokumentasi baru?"
```

#### ❌ DON'T:
```php
// ❌ JANGAN create file tanpa diminta
// User: "Update navbar"
// Wrong: Create navbar_new.php, navbar_backup.php, navbar_v2.php
// Correct: Edit navbar_beranda.php yang sudah ada

// ❌ JANGAN create dokumentasi otomatis
// User: "Selesai dengan fitur X"
// Wrong: Auto-create README.md, CHANGELOG.md, DOCS.md
// Correct: Hanya edit jika diminta

// ❌ JANGAN create backup files
// Wrong: file_backup.php, file_old.php, file_2024.php
// Correct: Gunakan version control (git)

// ❌ JANGAN proactive file creation
// Wrong: "Saya buatkan config.example.php untuk referensi"
// Correct: Tunggu instruksi eksplisit dari user
```

### 🗂️ File Structure & Organization

#### ✅ DO:
```
✅ Organize by feature/module
views/
  ├── layanan_informasi/
  │   ├── index.php
  │   ├── detail_public.php
  │   └── dokumen.php
  ├── berita/
  │   ├── index.php
  │   └── public.php

✅ Separate concerns
models/LayananInformasiModel.php    // Database logic
controllers/LayananInformasiController.php  // Business logic
views/layanan_informasi/index.php   // Presentation

✅ Use consistent naming
navbar_beranda.php      // Snake case for PHP files
setting_panel.php       // Descriptive names
detail_public.php       // Clear purpose

✅ ALWAYS prefer EDITING existing files over creating new ones
```

#### ❌ DON'T:
```
❌ Mix different concerns
views/
  ├── page1.php          // Vague name
  ├── page2.php          // No structure
  ├── NavbarBeranda.php  // Wrong case
  └── form-data.php      // Inconsistent separator

❌ Put logic in views
<?php
// views/something.php
$data = mysqli_query($conn, "SELECT * FROM table"); // Wrong!
// Database logic should be in Model

❌ Use generic names
form.php               // Which form?
list.php              // List of what?
detail.php            // Detail of what?

❌ Create files without explicit request
// Don't create: README.md, CHANGELOG.md, docs.md, backup files, etc.
```

### 🎬 Animations & Interactions

#### ✅ DO:
```css
/* Selalu gunakan transition yang smooth */
.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-10px);
}

/* Selalu konsisten timing */
transition: all 0.3s ease; // Standard across project
```

```javascript
// Selalu cleanup animation state
function toggleMenu() {
    wrapper.classList.toggle('active');
    if (!wrapper.classList.contains('active')) {
        setTimeout(() => {
            input.value = ''; // Cleanup after animation
        }, 300);
    }
}
```

#### ❌ DON'T:
```css
/* ❌ JANGAN timing yang berbeda-beda */
.card { transition: 0.5s; }
.button { transition: 0.2s; }
.dropdown { transition: 0.8s; } /* Inconsistent! */

/* ❌ JANGAN animasi yang terlalu cepat/lambat */
.modal { transition: 0.05s; } /* Too fast - jarring */
.menu { transition: 2s; } /* Too slow - annoying */
```

```javascript
// ❌ JANGAN lupa cleanup
function toggleMenu() {
    wrapper.classList.toggle('active');
    // No cleanup - memory leaks!
}
```

### 🔄 Form Handling

#### ✅ DO:
```php
// Selalu validate input
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama']);

    if (empty($nama)) {
        $error = "Nama harus diisi";
    }

    if (strlen($nama) < 3) {
        $error = "Nama minimal 3 karakter";
    }
}

// Selalu retain form values on error
<input type="text"
       value="<?php echo isset($_POST['nama']) ? htmlspecialchars($_POST['nama']) : ''; ?>">

// Selalu gunakan method yang tepat
<form method="POST"> <!-- For data submission -->
<form method="GET">  <!-- For search/filter -->
```

#### ❌ DON'T:
```php
// ❌ JANGAN skip validation
$nama = $_POST['nama'];
// Direct use without validation!

// ❌ JANGAN clear form on error
<input type="text" value="">
<!-- User has to retype everything! -->

// ❌ JANGAN salah method
<form method="GET"> <!-- For sensitive data - exposed in URL! -->
```

### 📊 Database Operations

#### ✅ DO:
```php
// Selalu gunakan model pattern
class LayananInformasiModel {
    public function getAllLayanan() {
        $query = "SELECT * FROM layanan_informasi ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

// Selalu handle errors
try {
    $stmt->execute();
} catch (PDOException $e) {
    error_log($e->getMessage());
    return false;
}

// Selalu close connections (PDO auto-closes, but good practice)
$stmt = null;
$conn = null;
```

#### ❌ DON'T:
```php
// ❌ JANGAN query langsung di controller/view
$result = mysqli_query($conn, "SELECT * FROM table");
// Should be in Model!

// ❌ JANGAN expose error details to user
echo $e->getMessage(); // Security risk!

// ❌ JANGAN multiple queries tanpa transaction
$conn->query("INSERT INTO table1 VALUES (...)");
$conn->query("INSERT INTO table2 VALUES (...)");
// Use transactions for related operations!
```

### 🌐 Internationalization (Bahasa Indonesia)

#### ✅ DO:
```php
// Selalu gunakan format tanggal Indonesia
$months = [
    1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
    4 => 'April', 5 => 'Mei', 6 => 'Juni',
    7 => 'Juli', 8 => 'Agustus', 9 => 'September',
    10 => 'Oktober', 11 => 'November', 12 => 'Desember'
];

// Selalu gunakan pesan formal
$messages = [
    'success' => 'Data berhasil disimpan.',
    'error' => 'Gagal menyimpan data. Silakan coba lagi.',
    'confirm' => 'Apakah Anda yakin ingin menghapus data ini?'
];
```

#### ❌ DON'T:
```php
// ❌ JANGAN mix bahasa
$error = "Failed to simpan data"; // Mixed!

// ❌ JANGAN gunakan format tanggal Inggris
echo date('M d, Y'); // Dec 05, 2025 - Wrong for Indonesia!

// ❌ JANGAN informal untuk government site
$error = "Yah, gagal nih bro!"; // Too casual!
```

### 🚀 Performance

#### ✅ DO:
```javascript
// Selalu debounce untuk search/filter
function debounce(func, wait) {
    let timeout;
    return function(...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => func(...args), wait);
    };
}

searchInput.addEventListener('input', debounce(search, 250));
```

```php
// Selalu limit query results
$query = "SELECT * FROM table LIMIT 50";

// Selalu optimize images
// Resize images before upload
// Max 1920px width for web display
```

#### ❌ DON'T:
```javascript
// ❌ JANGAN trigger on every keystroke
searchInput.addEventListener('input', function() {
    performExpensiveSearch(); // Too many requests!
});
```

```php
// ❌ JANGAN load semua data
$query = "SELECT * FROM large_table";
// Could crash with millions of rows!

// ❌ JANGAN terima file berukuran besar
// No max size = server storage abuse
```

### 📝 Documentation & Comments

#### ✅ DO:
```php
// Selalu comment untuk logic kompleks
/**
 * Generate unique filename dengan timestamp dan random string
 * Format: {nik}_{type}_{timestamp}.{ext}
 */
$filename = $nik . '_' . $type . '_' . time() . '.' . $ext;

// Selalu document public functions
/**
 * Update sub layanan dan sub layanan 2
 *
 * @param int $id ID layanan
 * @param string|null $sub_layanan Sub layanan level 1
 * @param string|null $sub_layanan_2 Sub layanan level 2
 * @return bool Success status
 */
public function updateSubLayanan($id, $sub_layanan, $sub_layanan_2 = null) {
    // Implementation
}
```

#### ❌ DON'T:
```php
// ❌ JANGAN comment yang obvious
$i = 0; // Set i to 0 (unnecessary!)

// ❌ JANGAN comment yang outdated
// This function returns string (actually returns array now!)

// ❌ JANGAN commented-out code berlebihan
// $old_code = "something";
// $another_old = "old";
// Use version control instead!
```

### 🔧 JavaScript Best Practices

#### ✅ DO:
```javascript
// Selalu check element exists
const element = document.getElementById('myElement');
if (element) {
    element.addEventListener('click', handleClick);
}

// Selalu use const/let, bukan var
const API_URL = 'https://api.example.com';
let currentPage = 1;

// Selalu cleanup event listeners
function init() {
    const btn = document.getElementById('btn');
    btn.addEventListener('click', handleClick);
}

function destroy() {
    const btn = document.getElementById('btn');
    btn.removeEventListener('click', handleClick);
}
```

#### ❌ DON'T:
```javascript
// ❌ JANGAN assume element exists
document.getElementById('myElement').addEventListener('click', handleClick);
// Crashes if element doesn't exist!

// ❌ JANGAN gunakan var
var name = "something"; // Use const or let

// ❌ JANGAN memory leaks
// Event listeners tanpa cleanup
// setTimeout/setInterval tanpa clear
```

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

**Last Updated**: Januari 2025
**Version**: 2.0.0
**Maintainer**: PPID Mandailing Natal Development Team

## 💡 Tips Terakhir

1. **Konsistensi adalah Kunci**: Ikuti pattern yang sudah ada di project
2. **Security First**: Selalu validasi input dan escape output
3. **User Experience**: Pikirkan dari perspektif pengguna akhir
4. **Performance Matters**: Optimize untuk loading cepat
5. **Mobile Friendly**: Test di berbagai ukuran layar
6. **Aksesibilitas**: Website pemerintah harus accessible untuk semua
7. **Documentation**: Code yang terdokumentasi adalah code yang maintainable
8. **Testing**: Test sebelum deploy ke production

---

💙 **Happy Coding & Semoga Bermanfaat!** 💙
