<?php
// Check session - data already passed from controller
if (!isset($_SESSION['user_id'])) {
  header('Location: index.php?controller=auth&action=login');
  exit();
}

// Set title
$title = 'Ajukan Permohonan - PPID Mandailing';
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title><?php echo $title; ?></title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <style>
    :root {
      --primary-color: #3b82f6;
      --primary-dark: #1e3a8a;
      --secondary-color: #6b7280;
      --success-color: #10b981;
      --warning-color: #f59e0b;
      --danger-color: #dc2626;
      --light-bg: #f8f9fa;
      --white: #ffffff;
      --text-primary: #1f2937;
      --text-secondary: #6b7280;
      --border-color: #e5e7eb;
      --shadow-light: rgba(0, 0, 0, 0.1);
      --shadow-medium: rgba(0, 0, 0, 0.15);
      --border-radius: 8px;
      --border-radius-lg: 15px;
      --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      --spacing-xs: 4px;
      --spacing-sm: 8px;
      --spacing-md: 16px;
      --spacing-lg: 24px;
      --spacing-xl: 32px;
    }

    * {
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', sans-serif;
      margin: 0;
      padding: 0;
      background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      line-height: 1.6;
      color: var(--text-primary);
      overflow-x: hidden;
    }

    /* Smooth scrolling */
    html {
      scroll-behavior: smooth;
    }

    /* Loading overlay */
    .page-loading {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(255, 255, 255, 0.9);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 9999;
      opacity: 0;
      visibility: hidden;
      transition: var(--transition);
    }

    .page-loading.active {
      opacity: 1;
      visibility: visible;
    }

    .loading-spinner {
      width: 50px;
      height: 50px;
      border: 4px solid #e5e7eb;
      border-top: 4px solid var(--primary-color);
      border-radius: 50%;
      animation: spin 1s linear infinite;
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    /* Progressive Enhancement Styles */
    .invalid-feedback {
      display: none;
      color: var(--danger-color);
      font-size: 0.875rem;
      margin-top: var(--spacing-xs);
      font-weight: 500;
    }

    .form-control.is-invalid,
    .form-select.is-invalid {
      border-color: var(--danger-color);
      box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
    }

    .upload-success {
      animation: successPulse 0.6s ease-out;
    }

    @keyframes successPulse {
      0% { transform: scale(1); }
      50% { transform: scale(1.02); background: rgba(16, 185, 129, 0.1); }
      100% { transform: scale(1); }
    }

    .file-preview {
      display: flex;
      align-items: center;
      gap: var(--spacing-sm);
      padding: var(--spacing-sm);
      background: rgba(16, 185, 129, 0.1);
      border-radius: var(--border-radius);
      margin-top: var(--spacing-sm);
    }

    .file-details {
      flex: 1;
      min-width: 0;
    }

    .file-details strong {
      display: block;
      font-size: 0.875rem;
      font-weight: 600;
      color: var(--text-primary);
      word-break: break-word;
    }

    .keyboard-navigation *:focus {
      outline: 3px solid var(--primary-color);
      outline-offset: 2px;
    }

    .network-status {
      position: fixed;
      top: -60px;
      left: 50%;
      transform: translateX(-50%);
      background: var(--white);
      color: var(--text-primary);
      padding: var(--spacing-sm) var(--spacing-lg);
      border-radius: 0 0 var(--border-radius) var(--border-radius);
      box-shadow: 0 4px 20px var(--shadow-medium);
      font-weight: 600;
      font-size: 0.875rem;
      z-index: 9999;
      transition: var(--transition);
      display: flex;
      align-items: center;
      gap: var(--spacing-sm);
    }

    .network-status.show {
      top: 0;
    }

    .network-status.online {
      background: var(--success-color);
      color: var(--white);
    }

    .network-status.offline {
      background: var(--danger-color);
      color: var(--white);
    }

    .animate-in {
      animation: slideInLeft 0.5s cubic-bezier(0.23, 1, 0.32, 1) forwards;
    }

    .page-loaded .form-card,
    .page-loaded .permohonan-card {
      opacity: 1;
      transform: translateY(0) translateX(0) scale(1);
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
      padding: var(--spacing-md) 0;
      transition: var(--transition);
    }

    .navbar-custom.scrolled {
      box-shadow: 0 8px 32px var(--shadow-medium);
    }

    .navbar-brand {
      display: flex;
      align-items: center;
      color: var(--white) !important;
      font-weight: 600;
      text-decoration: none;
      transition: var(--transition);
    }

    .navbar-brand:hover {
      color: #e5e7eb !important;
      transform: translateY(-2px);
    }

    .logo-img {
      width: 50px;
      height: 50px;
      background: white;
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 15px;
    }

    .logo-img img {
      width: 40px;
      height: 40px;
    }

    .nav-text {
      display: flex;
      flex-direction: column;
    }

    .nav-title {
      font-size: 14px;
      font-weight: 500;
      line-height: 1.2;
      margin: 0;
    }

    .nav-subtitle {
      font-size: 18px;
      font-weight: 700;
      line-height: 1.2;
      margin: 0;
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
      font-size: 14px;
      transition: all 0.3s ease;
      padding: 8px 16px;
      border-radius: 4px;
      white-space: nowrap;
    }

    .navbar-nav a:hover {
      color: #ddd;
      background-color: rgba(255, 255, 255, 0.1);
    }

    .navbar-nav a.active {
      background-color: rgba(255, 255, 255, 0.2);
      font-weight: 600;
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
      flex: 1;
      padding: 40px 0;
    }

    .form-card {
      background: var(--white);
      border-radius: var(--border-radius-lg);
      box-shadow: 0 10px 40px var(--shadow-light);
      margin-bottom: var(--spacing-xl);
      overflow: hidden;
      transition: var(--transition);
      border: 1px solid rgba(255, 255, 255, 0.2);
      backdrop-filter: blur(20px);
    }

    .form-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 20px 60px var(--shadow-medium);
    }

    .form-header {
      background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
      padding: var(--spacing-xl) var(--spacing-xl);
      color: var(--white);
      position: relative;
      overflow: hidden;
    }

    .form-header::before {
      content: '';
      position: absolute;
      top: 0;
      right: -50%;
      width: 100%;
      height: 100%;
      background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
      transform: skewX(-20deg);
      transition: var(--transition);
    }

    .form-card:hover .form-header::before {
      right: 100%;
    }

    .form-title {
      font-size: clamp(1.5rem, 4vw, 2rem);
      font-weight: 700;
      margin: 0 0 var(--spacing-sm) 0;
      display: flex;
      align-items: center;
      gap: var(--spacing-md);
      position: relative;
      z-index: 2;
    }

    .form-title i {
      font-size: 1.2em;
      opacity: 0.9;
    }

    .form-subtitle {
      font-size: clamp(0.875rem, 2vw, 1rem);
      opacity: 0.9;
      margin: 0;
      position: relative;
      z-index: 2;
    }

    .form-content {
      padding: var(--spacing-xl);
      background: linear-gradient(to bottom, var(--white), #fafbfc);
    }

    .container {
      padding-left: var(--spacing-md);
      padding-right: var(--spacing-md);
    }

    @media (min-width: 576px) {
      .container {
        padding-left: var(--spacing-lg);
        padding-right: var(--spacing-lg);
      }
    }

    .section-title {
      font-size: 18px;
      font-weight: 700;
      color: #1f2937;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .section-title i {
      color: #3b82f6;
    }

    .form-group {
      margin-bottom: 25px;
    }

    .form-label {
      font-weight: 600;
      color: #374151;
      margin-bottom: 8px;
      display: block;
    }

    .required {
      color: #dc3545;
    }

    .form-control,
    .form-select {
      border: 2px solid #e5e7eb;
      border-radius: 8px;
      padding: 12px 15px;
      font-size: 16px;
      transition: all 0.3s ease;
      width: 100%;
    }

    .form-control:focus,
    .form-select:focus {
      border-color: #3b82f6;
      box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
      outline: none;
    }

    textarea.form-control {
      resize: vertical;
      min-height: 100px;
    }

    .file-upload-area {
      border: 2px dashed #d1d5db;
      border-radius: 8px;
      padding: 25px;
      text-align: center;
      transition: all 0.3s ease;
      position: relative;
      background: #f8f9fa;
    }

    .file-upload-area:hover {
      border-color: #3b82f6;
      background: rgba(59, 130, 246, 0.05);
    }

    .file-upload-area.dragover {
      border-color: #3b82f6;
      background: rgba(59, 130, 246, 0.1);
    }

    .file-upload-icon {
      font-size: 48px;
      color: #9ca3af;
      margin-bottom: 15px;
    }

    .file-upload-text {
      color: #6b7280;
      margin-bottom: 10px;
    }

    .file-upload-input {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      opacity: 0;
      cursor: pointer;
    }

    .file-info {
      display: none;
      background: #e0f2fe;
      border-radius: 6px;
      padding: 10px 15px;
      margin-top: 10px;
      color: #0277bd;
      font-size: 14px;
    }

    .file-info i {
      margin-right: 8px;
    }

    .btn {
      padding: 12px 24px;
      border-radius: 8px;
      font-weight: 600;
      font-size: 14px;
      border: none;
      cursor: pointer;
      transition: all 0.3s ease;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 8px;
    }

    .btn-primary {
      background: #3b82f6;
      color: white;
    }

    .btn-primary:hover {
      background: #2563eb;
      transform: translateY(-2px);
      color: white;
    }

    .btn-secondary {
      background: #6b7280;
      color: white;
    }

    .btn-secondary:hover {
      background: #4b5563;
      color: white;
    }

    .btn-danger {
      background: #dc2626;
      color: white;
    }

    .btn-danger:hover {
      background: #b91c1c;
      color: white;
    }

    .alert {
      border-radius: 8px;
      border: none;
      padding: 15px;
      margin-bottom: 25px;
      display: flex;
      align-items: center;
      font-weight: 500;
    }

    .alert-danger {
      background-color: #fee2e2;
      color: #dc2626;
      border-left: 4px solid #dc2626;
    }

    .alert-success {
      background-color: #d1fae5;
      color: #059669;
      border-left: 4px solid #059669;
    }

    .alert i {
      margin-right: 10px;
      font-size: 16px;
    }

    .permohonan-list {
      margin-top: 40px;
    }

    .permohonan-card {
      background: var(--white);
      border-radius: var(--border-radius-lg);
      padding: var(--spacing-lg);
      margin-bottom: var(--spacing-lg);
      box-shadow: 0 4px 20px var(--shadow-light);
      border-left: 4px solid var(--primary-color);
      transition: var(--transition);
      position: relative;
      overflow: hidden;
    }

    .permohonan-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
      transition: left 0.6s ease;
    }

    .permohonan-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 40px var(--shadow-medium);
    }

    .permohonan-card:hover::before {
      left: 100%;
    }

    .permohonan-header {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      margin-bottom: var(--spacing-md);
      position: relative;
      z-index: 2;
    }

    .permohonan-number {
      font-weight: 700;
      color: var(--text-primary);
      font-size: clamp(1rem, 2vw, 1.125rem);
      margin: 0;
      line-height: 1.3;
    }

    .permohonan-meta {
      font-size: 0.875rem;
      color: var(--text-secondary);
      margin-top: var(--spacing-xs);
    }

    .status-badge {
      padding: var(--spacing-xs) var(--spacing-md);
      border-radius: 20px;
      font-size: 0.75rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.025em;
      display: inline-flex;
      align-items: center;
      gap: var(--spacing-xs);
      position: relative;
      z-index: 2;
      animation: pulse-badge 2s ease-in-out infinite;
    }

    @keyframes pulse-badge {
      0%, 100% { transform: scale(1); }
      50% { transform: scale(1.02); }
    }

    .status-pending {
      background: linear-gradient(135deg, #fef3c7, #fde68a);
      color: #92400e;
      box-shadow: 0 2px 8px rgba(217, 119, 6, 0.2);
    }

    .status-approved {
      background: linear-gradient(135deg, #d1fae5, #a7f3d0);
      color: #047857;
      box-shadow: 0 2px 8px rgba(5, 150, 105, 0.2);
    }

    .status-rejected {
      background: linear-gradient(135deg, #fee2e2, #fecaca);
      color: #b91c1c;
      box-shadow: 0 2px 8px rgba(220, 38, 38, 0.2);
    }

    .status-pending::before { content: '⏳ '; }
    .status-approved::before { content: '✅ '; }
    .status-rejected::before { content: '❌ '; }

    .footer {
      background: #1f2937;
      color: white;
      text-align: center;
      padding: 20px 0;
      margin-top: auto;
    }

    .footer p {
      margin: 0;
      font-size: 14px;
    }

    .accessibility-btn {
      position: fixed;
      bottom: 20px;
      right: 20px;
      background: #10b981;
      color: white;
      border: none;
      border-radius: 50%;
      width: 60px;
      height: 60px;
      font-size: 24px;
      box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
      z-index: 1000;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .accessibility-btn:hover {
      background: #059669;
      transform: scale(1.1);
    }

    /* Progressive Mobile-First Responsive Design */

    /* Small devices and up (576px and up) */
    @media (min-width: 576px) {
      .container {
        max-width: 540px;
      }

      .btn {
        width: auto;
      }
    }

    /* Medium devices and up (768px and up) */
    @media (min-width: 768px) {
      .container {
        max-width: 720px;
      }

      .main-content {
        padding: 40px 0;
      }

      .form-content {
        padding: var(--spacing-xl);
      }

      .form-header {
        padding: var(--spacing-xl);
      }

      .permohonan-header {
        flex-direction: row;
        align-items: flex-start;
      }
    }

    /* Large devices and up (992px and up) */
    @media (min-width: 992px) {
      .container {
        max-width: 960px;
      }

      .form-card {
        margin-bottom: 48px;
      }

      .navbar-custom .container {
        max-width: 100%;
      }
    }

    /* Extra large devices and up (1200px and up) */
    @media (min-width: 1200px) {
      .container {
        max-width: 1140px;
      }
    }

    /* Mobile-specific styles */
    @media (max-width: 767.98px) {
      .main-content {
        padding: var(--spacing-lg) 0;
      }

      .form-content {
        padding: var(--spacing-lg);
      }

      .form-header {
        padding: var(--spacing-lg);
      }

      .top-info-bar {
        display: none;
      }

      .navbar-nav {
        display: none;
      }

      .btn {
        width: 100%;
        justify-content: center;
        margin-bottom: var(--spacing-sm);
      }

      .permohonan-header {
        flex-direction: column;
        align-items: flex-start;
        gap: var(--spacing-sm);
      }

      .file-upload-area {
        padding: var(--spacing-lg);
      }

      .file-upload-icon {
        font-size: 2rem;
      }

      .d-flex.gap-3 {
        flex-direction: column;
      }

      .section-title {
        font-size: 1rem;
      }
    }

    /* Touch device optimizations */
    @media (hover: none) and (pointer: coarse) {
      .btn {
        min-height: 48px;
        font-size: 1rem;
      }

      .form-control,
      .form-select {
        min-height: 48px;
        font-size: 16px; /* Prevents zoom on iOS */
      }

      .file-upload-area {
        min-height: 120px;
      }
    }

    /* Enhanced Animations */
    .form-card {
      animation: slideInUp 0.6s cubic-bezier(0.23, 1, 0.32, 1) forwards;
      opacity: 0;
      transform: translateY(40px);
    }

    .permohonan-card {
      animation: slideInLeft 0.5s cubic-bezier(0.23, 1, 0.32, 1) forwards;
      opacity: 0;
      transform: translateX(-40px);
    }

    @keyframes slideInUp {
      from {
        opacity: 0;
        transform: translateY(40px) scale(0.95);
      }
      to {
        opacity: 1;
        transform: translateY(0) scale(1);
      }
    }

    @keyframes slideInLeft {
      from {
        opacity: 0;
        transform: translateX(-40px);
      }
      to {
        opacity: 1;
        transform: translateX(0);
      }
    }

    .btn {
      position: relative;
      overflow: hidden;
    }

    .btn::after {
      content: '';
      position: absolute;
      top: 50%;
      left: 50%;
      width: 0;
      height: 0;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.3);
      transform: translate(-50%, -50%);
      transition: width 0.6s, height 0.6s;
    }

    .btn:active::after {
      width: 300px;
      height: 300px;
    }

    .loading {
      opacity: 0.7;
      pointer-events: none;
    }

    .loading .btn-primary {
      background: #9ca3af;
    }

    /* Navigation Dropdown Styles */
    .nav-dropdown {
      position: relative;
      display: inline-block;
    }

    .nav-dropdown .dropdown-toggle {
      color: white;
      text-decoration: none;
      font-weight: 500;
      font-size: 14px;
      transition: all 0.3s ease;
      padding: 8px 16px;
      border-radius: 4px;
      white-space: nowrap;
      display: flex;
      align-items: center;
    }

    .nav-dropdown .dropdown-toggle:hover {
      color: #ddd;
      background-color: rgba(255, 255, 255, 0.1);
    }

    .nav-dropdown .dropdown-toggle.active {
      background-color: rgba(255, 255, 255, 0.2);
      font-weight: 600;
    }

    .dropdown-menu {
      background: black;
      border: none;
      border-radius: 8px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      padding: 8px 0;
      min-width: 200px;
      margin-top: 8px;
    }

    .dropdown-item {
      padding: 10px 16px;
      color: #374151;
      text-decoration: none;
      font-size: 14px;
      font-weight: 500;
      display: flex;
      align-items: center;
      transition: all 0.3s ease;
    }

    .dropdown-item:hover {
      background-color: #f3f4f6;
      color: #1e3a8a;
    }

    .dropdown-item.active {
      background-color: #3b82f6;
      color: white;
    }

    .dropdown-divider {
      margin: 8px 0;
      border-color: #e5e7eb;
    }

    /* User Dropdown Styles */
    .user-dropdown .dropdown-toggle {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 6px 12px;
    }

    .user-avatar {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.2);
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
      border: 2px solid rgba(255, 255, 255, 0.3);
    }

    .user-avatar img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .user-avatar i {
      color: white;
      font-size: 14px;
    }

    .username {
      font-size: 14px;
      font-weight: 500;
      color: white;
      max-width: 120px;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
    }
  </style>
</head>

<body>
  <!-- Top Info Bar -->
  <div class="top-info-bar">
    <div class="container">
      <div class="top-info-links">
        <a href="#">TENTANG PPID</a>
        <a href="#">KONTAK PPID</a>
      </div>
      <div class="top-info-contact">
        <span><i class="fas fa-envelope"></i> ppid@mandailingnatal.go.id</span>
        <span><i class="fas fa-phone"></i> Call Center: +628117905000</span>
      </div>
    </div>
  </div>

  <!-- Main Navigation Header -->
  <?php include 'views/layout/navbar_masyarakat.php'; ?>

  <!-- Breadcrumb -->
  <div class="breadcrumb-section">
    <div class="container">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php?controller=user&action=index">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">Ajukan Permohonan</li>
        </ol>
      </nav>
    </div>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <!-- Form Card -->
          <div class="form-card">
            <div class="form-header">
              <h2 class="form-title">
                <i class="fas fa-file-plus"></i>
                Ajukan Permohonan Informasi
              </h2>
              <p class="form-subtitle">Lengkapi formulir berikut untuk mengajukan permohonan informasi publik</p>
            </div>

            <div class="form-content">
              <?php if (!empty($error)): ?>
                <div class="alert alert-danger" role="alert">
                  <i class="fas fa-exclamation-triangle"></i>
                  <?php echo htmlspecialchars($error); ?>
                </div>
              <?php endif; ?>

              <?php if (!empty($success)): ?>
                <div class="alert alert-success" role="alert">
                  <i class="fas fa-check-circle"></i>
                  <?php echo htmlspecialchars($success); ?>
                </div>
              <?php endif; ?>

              <form id="permohonanForm" method="POST" enctype="multipart/form-data">
                <!-- Basic Information -->
                <h3 class="section-title">
                  <i class="fas fa-info-circle"></i>
                  Informasi Permohonan
                </h3>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="tujuan_permohonan" class="form-label">Tujuan Permohonan <span class="required">*</span></label>
                      <textarea class="form-control" id="tujuan_permohonan" name="tujuan_permohonan" rows="3" required placeholder="Jelaskan tujuan permohonan informasi Anda"><?php echo isset($_POST['tujuan_permohonan']) ? htmlspecialchars($_POST['tujuan_permohonan']) : ''; ?></textarea>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="komponen_tujuan" class="form-label">Komponen/Unit Tujuan <span class="required">*</span></label>
                      <select class="form-select" id="komponen_tujuan" name="komponen_tujuan" required>
                        <option value="">Pilih Unit Tujuan</option>
                        <?php if (!empty($skpd_list)): ?>
                          <?php foreach ($skpd_list as $skpd): ?>
                            <option value="<?php echo htmlspecialchars($skpd['nama_skpd']); ?>" <?php echo (isset($_POST['komponen_tujuan']) && $_POST['komponen_tujuan'] == $skpd['nama_skpd']) ? 'selected' : ''; ?>>
                              <?php echo htmlspecialchars($skpd['nama_skpd']); ?>
                            </option>
                          <?php endforeach; ?>
                        <?php endif; ?>
                      </select>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="judul_dokumen" class="form-label">Judul/Rincian Informasi yang Dibutuhkan <span class="required">*</span></label>
                      <input type="text" class="form-control" id="judul_dokumen" name="judul_dokumen" required placeholder="Sebutkan secara spesifik informasi yang Anda butuhkan" value="<?php echo isset($_POST['judul_dokumen']) ? htmlspecialchars($_POST['judul_dokumen']) : ''; ?>">
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <label for="tujuan_penggunaan_informasi" class="form-label">Tujuan Penggunaan Informasi <span class="required">*</span></label>
                  <textarea class="form-control" id="tujuan_penggunaan_informasi" name="tujuan_penggunaan_informasi" rows="3" required placeholder="Jelaskan untuk apa informasi ini akan digunakan"><?php echo isset($_POST['tujuan_penggunaan_informasi']) ? htmlspecialchars($_POST['tujuan_penggunaan_informasi']) : ''; ?></textarea>
                </div>

                <!-- File Upload Section -->
                <h3 class="section-title">
                  <i class="fas fa-cloud-upload-alt"></i>
                  Dokumen Pendukung
                </h3>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="form-label">Upload Foto Identitas (KTP) <span class="required">*</span></label>
                      <div class="file-upload-area" onclick="document.getElementById('upload_foto_identitas').click()">
                        <div class="file-upload-icon">
                          <i class="fas fa-id-card"></i>
                        </div>
                        <div class="file-upload-text">
                          <strong>Klik untuk upload KTP</strong><br>
                          <small>Format: JPG, PNG, PDF (Max: 5MB)</small>
                        </div>
                        <input type="file" id="upload_foto_identitas" name="upload_foto_identitas" class="file-upload-input" accept="image/*,application/pdf" required onchange="showFileInfo(this, 'identitas-info')">
                        <div id="identitas-info" class="file-info"></div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="form-label">Upload Data Pendukung (Opsional)</label>
                      <div class="file-upload-area" onclick="document.getElementById('upload_data_pendukung').click()">
                        <div class="file-upload-icon">
                          <i class="fas fa-file-upload"></i>
                        </div>
                        <div class="file-upload-text">
                          <strong>Klik untuk upload dokumen</strong><br>
                          <small>Format: PDF, DOC, DOCX, JPG, PNG (Max: 5MB)</small>
                        </div>
                        <input type="file" id="upload_data_pendukung" name="upload_data_pendukung" class="file-upload-input" accept="image/*,application/pdf,.doc,.docx" onchange="showFileInfo(this, 'pendukung-info')">
                        <div id="pendukung-info" class="file-info"></div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Submit Button -->
                <div class="d-flex gap-3 flex-wrap mt-4">
                  <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i>
                    Ajukan Permohonan
                  </button>
                  <a href="index.php?controller=user&action=index" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    Kembali ke Dashboard
                  </a>
                </div>
              </form>
            </div>
          </div>

          <!-- Existing Permohonan List -->
          <?php if (!empty($permohonan_list)): ?>
          <div class="permohonan-list">
            <h3 class="section-title">
              <i class="fas fa-list"></i>
              Permohonan Saya
            </h3>

            <?php foreach ($permohonan_list as $permohonan): ?>
            <div class="permohonan-card">
              <div class="permohonan-header">
                <div>
                  <div class="permohonan-number"><?php echo htmlspecialchars($permohonan['no_permohonan']); ?></div>
                  <small class="permohonan-meta">Diajukan: <?php echo date('d M Y', strtotime($permohonan['created_at'] ?? 'now')); ?></small>
                </div>
                <span class="status-badge status-<?php echo isset($permohonan['status']) && $permohonan['status'] ? $permohonan['status'] : 'pending'; ?>">
                  <?php echo isset($permohonan['status']) && $permohonan['status'] ? ucfirst($permohonan['status']) : 'Pending'; ?>
                </span>
              </div>
              <div class="permohonan-content">
                <p><strong>Tujuan:</strong> <?php echo htmlspecialchars($permohonan['tujuan_permohonan']); ?></p>
                <p><strong>Dokumen:</strong> <?php echo htmlspecialchars($permohonan['judul_dokumen']); ?></p>
                <div class="d-flex gap-2 flex-wrap mt-3">
                  <a href="index.php?controller=AjukanPermohonan&action=view&id=<?php echo $permohonan['id_permohonan']; ?>" class="btn btn-sm btn-primary">
                    <i class="fas fa-eye"></i> Detail
                  </a>
                  <?php if (!isset($permohonan['status']) || $permohonan['status'] === 'pending' || $permohonan['status'] === ''): ?>
                  <a href="index.php?controller=AjukanPermohonan&action=delete&id=<?php echo $permohonan['id_permohonan']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus permohonan ini?')">
                    <i class="fas fa-trash"></i> Hapus
                  </a>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer class="footer">
    <div class="container">
      <p>&copy; 2025 PPID Kemendagri ALL Rights Reserved</p>
    </div>
  </footer>

  <!-- Accessibility Button -->
  <button class="accessibility-btn" title="Accessibility">
    <i class="fas fa-universal-access"></i>
  </button>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    // Progressive Enhancement Features
    const ProgressiveEnhancement = {
      init() {
        this.setupFormValidation();
        this.setupFileHandling();
        this.setupAccessibility();
        this.setupPerformanceOptimizations();
        this.setupOfflineSupport();
      },

      setupFormValidation() {
        const form = document.getElementById('permohonanForm');
        const inputs = form.querySelectorAll('input, textarea, select');

        inputs.forEach(input => {
          input.addEventListener('blur', this.validateField.bind(this));
          input.addEventListener('input', this.clearError.bind(this));
        });
      },

      validateField(e) {
        const field = e.target;
        const value = field.value.trim();
        let isValid = true;
        let errorMsg = '';

        // Remove existing error styling
        this.clearFieldError(field);

        // Required field validation
        if (field.hasAttribute('required') && !value) {
          isValid = false;
          errorMsg = 'Field ini wajib diisi';
        }

        // Specific validations
        if (field.type === 'file' && field.files.length > 0) {
          const file = field.files[0];
          if (file.size > 5000000) {
            isValid = false;
            errorMsg = 'Ukuran file terlalu besar (maksimal 5MB)';
          }
        }

        if (field.type === 'textarea' && value && value.length < 10) {
          isValid = false;
          errorMsg = 'Deskripsi terlalu singkat (minimal 10 karakter)';
        }

        if (!isValid) {
          this.showFieldError(field, errorMsg);
        }

        return isValid;
      },

      showFieldError(field, message) {
        field.classList.add('is-invalid');

        let errorDiv = field.parentNode.querySelector('.invalid-feedback');
        if (!errorDiv) {
          errorDiv = document.createElement('div');
          errorDiv.className = 'invalid-feedback';
          field.parentNode.appendChild(errorDiv);
        }
        errorDiv.textContent = message;
        errorDiv.style.display = 'block';
      },

      clearFieldError(field) {
        field.classList.remove('is-invalid');
        const errorDiv = field.parentNode.querySelector('.invalid-feedback');
        if (errorDiv) {
          errorDiv.style.display = 'none';
        }
      },

      clearError(e) {
        this.clearFieldError(e.target);
      },

      setupFileHandling() {
        // Enhanced file upload with preview
        document.querySelectorAll('input[type="file"]').forEach(input => {
          input.addEventListener('change', (e) => {
            this.handleFileSelect(e.target);
          });
        });
      },

      handleFileSelect(input) {
        const file = input.files[0];
        if (!file) return;

        const infoId = input.id.includes('identitas') ? 'identitas-info' : 'pendukung-info';
        const infoDiv = document.getElementById(infoId);
        const fileSize = (file.size / 1024 / 1024).toFixed(2);
        const uploadArea = input.closest('.file-upload-area');

        // Create file preview
        const fileInfo = `
          <div class="file-preview">
            <i class="fas fa-check-circle text-success"></i>
            <div class="file-details">
              <strong>${file.name}</strong>
              <small class="d-block text-muted">${fileSize} MB</small>
              <div class="progress mt-2" style="height: 4px;">
                <div class="progress-bar bg-success" style="width: 100%"></div>
              </div>
            </div>
          </div>
        `;

        infoDiv.innerHTML = fileInfo;
        infoDiv.style.display = 'block';

        // Update upload area styling
        uploadArea.style.borderColor = '#10b981';
        uploadArea.style.background = 'rgba(16, 185, 129, 0.05)';

        // Add success animation
        uploadArea.classList.add('upload-success');
        setTimeout(() => uploadArea.classList.remove('upload-success'), 600);
      },

      setupAccessibility() {
        // Add ARIA labels and keyboard navigation
        document.querySelectorAll('.file-upload-area').forEach(area => {
          const input = area.querySelector('input[type="file"]');
          area.setAttribute('role', 'button');
          area.setAttribute('tabindex', '0');
          area.setAttribute('aria-label', 'Klik untuk upload file');

          area.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
              e.preventDefault();
              input.click();
            }
          });
        });

        // Focus management
        document.addEventListener('keydown', (e) => {
          if (e.key === 'Tab') {
            document.body.classList.add('keyboard-navigation');
          }
        });

        document.addEventListener('mousedown', () => {
          document.body.classList.remove('keyboard-navigation');
        });
      },

      setupPerformanceOptimizations() {
        // Lazy load non-critical features
        if ('IntersectionObserver' in window) {
          const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
              if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
              }
            });
          });

          document.querySelectorAll('.permohonan-card').forEach(card => {
            observer.observe(card);
          });
        }

        // Debounced input validation
        this.debounce = (func, wait) => {
          let timeout;
          return function executedFunction(...args) {
            const later = () => {
              clearTimeout(timeout);
              func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
          };
        };
      },

      setupOfflineSupport() {
        // Basic offline detection
        window.addEventListener('online', () => {
          this.showNetworkStatus('online');
        });

        window.addEventListener('offline', () => {
          this.showNetworkStatus('offline');
        });
      },

      showNetworkStatus(status) {
        const statusBar = document.createElement('div');
        statusBar.className = `network-status ${status}`;
        statusBar.innerHTML = status === 'online'
          ? '<i class="fas fa-wifi"></i> Koneksi tersambung'
          : '<i class="fas fa-wifi-slash"></i> Koneksi terputus';

        document.body.appendChild(statusBar);

        setTimeout(() => {
          statusBar.classList.add('show');
        }, 100);

        setTimeout(() => {
          statusBar.classList.remove('show');
          setTimeout(() => statusBar.remove(), 300);
        }, 3000);
      }
    };

    // Legacy function for backward compatibility
    function showFileInfo(input, infoId) {
      ProgressiveEnhancement.handleFileSelect(input);
    }

    // Form submission handling
    document.getElementById('permohonanForm').addEventListener('submit', function(e) {
      const submitBtn = this.querySelector('button[type="submit"]');
      const originalText = submitBtn.innerHTML;

      // Show loading state
      submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';
      submitBtn.disabled = true;
      this.classList.add('loading');

      // Validate file upload
      const identitasFile = document.getElementById('upload_foto_identitas');
      if (!identitasFile.files || identitasFile.files.length === 0) {
        e.preventDefault();
        alert('Upload foto identitas (KTP) wajib dilakukan!');

        // Revert button state
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
        this.classList.remove('loading');
        return;
      }

      // Check file size
      if (identitasFile.files[0].size > 5000000) {
        e.preventDefault();
        alert('Ukuran file KTP terlalu besar! Maksimal 5MB');

        // Revert button state
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
        this.classList.remove('loading');
        return;
      }

      // Check optional file
      const pendukungFile = document.getElementById('upload_data_pendukung');
      if (pendukungFile.files && pendukungFile.files.length > 0) {
        if (pendukungFile.files[0].size > 5000000) {
          e.preventDefault();
          alert('Ukuran file data pendukung terlalu besar! Maksimal 5MB');

          // Revert button state
          submitBtn.innerHTML = originalText;
          submitBtn.disabled = false;
          this.classList.remove('loading');
          return;
        }
      }
    });

    // Drag and drop functionality
    document.querySelectorAll('.file-upload-area').forEach(area => {
      area.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('dragover');
      });

      area.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
      });

      area.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');

        const input = this.querySelector('input[type="file"]');
        const files = e.dataTransfer.files;

        if (files.length > 0) {
          input.files = files;
          // Trigger change event
          const event = new Event('change', { bubbles: true });
          input.dispatchEvent(event);
        }
      });
    });

    // Initialize page with progressive enhancement
    document.addEventListener('DOMContentLoaded', function() {
      // Initialize progressive enhancement
      ProgressiveEnhancement.init();

      // Staggered animations
      const cards = document.querySelectorAll('.form-card, .permohonan-card');
      cards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.15}s`;
        card.style.animationFillMode = 'both';
      });

      // Auto-hide alerts with smooth transition
      setTimeout(() => {
        document.querySelectorAll('.alert').forEach(alert => {
          alert.style.transition = 'all 0.5s ease-out';
          alert.style.transform = 'translateY(-20px)';
          alert.style.opacity = '0';

          setTimeout(() => {
            alert.style.maxHeight = '0';
            alert.style.padding = '0';
            alert.style.margin = '0';
            setTimeout(() => alert.remove(), 300);
          }, 500);
        });
      }, 5000);

      // Add page load completion indicator
      document.body.classList.add('page-loaded');

      // Performance metrics (for debugging)
      if (window.performance) {
        const loadTime = window.performance.timing.loadEventEnd - window.performance.timing.navigationStart;
        console.log(`Page loaded in ${loadTime}ms`);
      }
    });
  </script>
</body>

</html>