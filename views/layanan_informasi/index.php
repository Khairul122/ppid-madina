<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
  header('Location: index.php?controller=auth&action=login');
  exit();
}
?>

<?php include('template/header.php'); ?>

<body class="with-welcome-text">
  <div class="container-scroller">
    <?php include 'template/navbar.php'; ?>
    <div class="container-fluid page-body-wrapper">
      <?php include 'template/setting_panel.php'; ?>
      <?php include 'template/sidebar.php'; ?>
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-sm-12">

              <!-- Alert Messages -->
              <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                  <i class="mdi mdi-check-circle me-2"></i>
                  <?php
                  echo $_SESSION['success'];
                  unset($_SESSION['success']);
                  ?>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
              <?php endif; ?>

              <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                  <i class="mdi mdi-alert-circle me-2"></i>
                  <?php
                  echo $_SESSION['error'];
                  unset($_SESSION['error']);
                  ?>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
              <?php endif; ?>

              <!-- Header Section -->
              <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center">
                  <i class="mdi mdi-information-outline me-2" style="font-size: 24px;"></i>
                  <span style="font-size: 18px; font-weight: 500;">Layanan Informasi Publik</span>
                </div>
                <!-- Add New Button -->
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addLayananModal">
                  <i class="mdi mdi-plus"></i> Tambah Data Baru
                </button>
              </div>

              <!-- Note Card -->
              <div class="card mb-4">
                <div class="card-body py-3">
                  <div class="d-flex align-items-start">
                    <i class="mdi mdi-information text-primary me-2" style="font-size: 20px; margin-top: 2px;"></i>
                    <div>
                      <strong>Note:</strong> Kelola data layanan informasi publik.
                    </div>
                  </div>
                </div>
              </div>

              <!-- All Layanan Data -->
              <div class="layanan-list">
                <?php
                $allLayanan = $this->layananModel->getAllLayanan();
                if (empty($allLayanan)):
                ?>
                  <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                      <i class="mdi mdi-folder-open-outline" style="font-size: 56px; color: #e0e0e0;"></i>
                      <p class="text-muted mt-3 mb-0">Belum ada data layanan informasi.</p>
                    </div>
                  </div>
                <?php else: ?>
                  <?php foreach ($allLayanan as $index => $layanan): ?>
                    <div class="layanan-item card border-0 shadow-sm mb-3" data-index="<?php echo $index; ?>">
                      <div class="card-header bg-white border-0 p-0" id="heading_<?php echo $layanan['id_layanan']; ?>">
                        <button
                          class="layanan-toggle w-100 text-start p-3 border-0 bg-transparent d-flex justify-content-between align-items-center"
                          type="button"
                          data-bs-toggle="collapse"
                          data-bs-target="#collapse_<?php echo $layanan['id_layanan']; ?>"
                          aria-expanded="false"
                          aria-controls="collapse_<?php echo $layanan['id_layanan']; ?>">
                          <div class="d-flex align-items-start flex-grow-1">
                            <div class="layanan-number me-3 mt-1">
                              <span class="badge bg-light text-dark"><?php echo $index + 1; ?></span>
                            </div>
                            <div class="layanan-info flex-grow-1">
                              <h6 class="mb-1 fw-semibold text-dark">
                                <?php echo htmlspecialchars($layanan['nama_layanan']); ?>
                              </h6>
                              <?php if (!empty($layanan['sub_layanan'])): ?>
                                <small class="text-muted d-flex align-items-center">
                                  <i class="mdi mdi-arrow-right-thin me-1"></i>
                                  <?php echo htmlspecialchars($layanan['sub_layanan']); ?>
                                </small>
                              <?php endif; ?>
                              <?php if (!empty($layanan['sub_layanan_2'])): ?>
                                <small class="text-muted d-flex align-items-center">
                                  <i class="mdi mdi-arrow-right-thin me-1"></i>
                                  <?php echo htmlspecialchars($layanan['sub_layanan_2']); ?>
                                </small>
                              <?php endif; ?>
                              <small class="text-muted d-block mt-1">
                                <i class="mdi mdi-clock-outline me-1"></i>
                                Terakhir diupdate: <?php echo date('d M Y', strtotime($layanan['updated_at'])); ?>
                              </small>
                            </div>
                          </div>
                          <div class="chevron-icon ms-3">
                            <i class="mdi mdi-chevron-down text-secondary"></i>
                          </div>
                        </button>
                      </div>

                      <div
                        id="collapse_<?php echo $layanan['id_layanan']; ?>"
                        class="collapse"
                        aria-labelledby="heading_<?php echo $layanan['id_layanan']; ?>"
                        data-bs-parent=".layanan-list">
                              <div class="card-body">
                                <!-- Edit Form -->
                                <form method="POST" action="index.php?controller=layananInformasi&action=update" enctype="multipart/form-data">
                                  <input type="hidden" name="id_layanan" value="<?php echo $layanan['id_layanan']; ?>">

                                  <!-- Sub Layanan Field -->
                                  <div class="mb-4">
                                    <label class="form-label fw-semibold text-dark mb-2">Sub Layanan (Opsional)</label>
                                    <div class="sub-layanan-wrapper border rounded p-3" id="subLayananCard_<?php echo $layanan['id_layanan']; ?>">
                                      <div class="form-check form-switch mb-0">
                                        <input
                                          class="form-check-input"
                                          type="checkbox"
                                          role="switch"
                                          name="has_sub_layanan"
                                          id="hasSubLayanan_<?php echo $layanan['id_layanan']; ?>"
                                          onchange="toggleSubLayanan(<?php echo $layanan['id_layanan']; ?>)"
                                          <?php echo !empty($layanan['sub_layanan']) ? 'checked' : ''; ?>>
                                        <label class="form-check-label fw-medium" for="hasSubLayanan_<?php echo $layanan['id_layanan']; ?>">
                                          <i class="mdi mdi-folder-outline me-1"></i>
                                          Aktifkan Sub Layanan
                                        </label>
                                      </div>
                                      <div class="sub-layanan-input-area mt-3 <?php echo !empty($layanan['sub_layanan']) ? 'active' : ''; ?>" id="subLayananInputArea_<?php echo $layanan['id_layanan']; ?>">
                                        <input
                                          type="text"
                                          class="form-control"
                                          name="sub_layanan"
                                          id="subLayanan_<?php echo $layanan['id_layanan']; ?>"
                                          placeholder="Masukkan nama sub layanan"
                                          value="<?php echo htmlspecialchars($layanan['sub_layanan'] ?? ''); ?>">
                                        <small class="text-muted d-block mt-1">
                                          <i class="mdi mdi-information-outline me-1"></i>
                                          Contoh: Informasi Berkala, Informasi Setiap Saat
                                        </small>
                                      </div>
                                    </div>
                                  </div>

                                  <!-- Sub Layanan 2 Field -->
                                  <div class="mb-4">
                                    <label class="form-label fw-semibold text-dark mb-2">Sub Layanan 2 (Opsional)</label>
                                    <div class="sub-layanan-wrapper border rounded p-3" id="subLayanan2Card_<?php echo $layanan['id_layanan']; ?>">
                                      <div class="form-check form-switch mb-0">
                                        <input
                                          class="form-check-input"
                                          type="checkbox"
                                          role="switch"
                                          name="has_sub_layanan_2"
                                          id="hasSubLayanan2_<?php echo $layanan['id_layanan']; ?>"
                                          onchange="toggleSubLayanan2(<?php echo $layanan['id_layanan']; ?>)"
                                          <?php echo !empty($layanan['sub_layanan_2']) ? 'checked' : ''; ?>>
                                        <label class="form-check-label fw-medium" for="hasSubLayanan2_<?php echo $layanan['id_layanan']; ?>">
                                          <i class="mdi mdi-folder-outline me-1"></i>
                                          Aktifkan Sub Layanan 2
                                        </label>
                                      </div>
                                      <div class="sub-layanan-input-area mt-3 <?php echo !empty($layanan['sub_layanan_2']) ? 'active' : ''; ?>" id="subLayanan2InputArea_<?php echo $layanan['id_layanan']; ?>">
                                        <input
                                          type="text"
                                          class="form-control"
                                          name="sub_layanan_2"
                                          id="subLayanan2_<?php echo $layanan['id_layanan']; ?>"
                                          placeholder="Masukkan nama sub layanan 2"
                                          value="<?php echo htmlspecialchars($layanan['sub_layanan_2'] ?? ''); ?>">
                                        <small class="text-muted d-block mt-1">
                                          <i class="mdi mdi-information-outline me-1"></i>
                                          Sub kategori tambahan untuk klasifikasi lebih detail
                                        </small>
                                      </div>
                                    </div>
                                  </div>

                                  <?php
                                  // Check if the layanan content is a PDF file or image
                                  $extension = pathinfo($layanan['isi'], PATHINFO_EXTENSION);
                                  $is_pdf = (strtolower($extension) === 'pdf' && file_exists($layanan['isi']));
                                  $is_image = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp']) && file_exists($layanan['isi']);
                                  ?>

                                  <?php if ($is_pdf || $is_image): ?>
                                    <div class="mb-3">
                                      <label class="form-label fw-bold">File Saat Ini</label>
                                      <div class="alert alert-info">
                                        <?php if ($is_pdf): ?>
                                          <i class="mdi mdi-file-pdf text-danger me-2" style="font-size: 1.5em;"></i>
                                          <span>File PDF: <?php echo basename($layanan['isi']); ?></span>
                                          <a href="<?php echo $layanan['isi']; ?>" target="_blank" class="btn btn-sm btn-outline-primary ms-2">Klik Disini</a>
                                        <?php elseif ($is_image): ?>
                                          <i class="mdi mdi-file-image text-primary me-2" style="font-size: 1.5em;"></i>
                                          <span>File Gambar: <?php echo basename($layanan['isi']); ?></span>
                                          <a href="<?php echo $layanan['isi']; ?>" target="_blank" class="btn btn-sm btn-outline-primary ms-2">Lihat Gambar</a>
                                        <?php endif; ?>
                                      </div>
                                      <label class="form-label fw-bold mt-3">Upload File Baru (PDF atau Gambar)</label>
                                      <input type="file" name="isi" class="form-control" accept=".pdf,.jpg,.jpeg,.png,.gif,.bmp,.webp" />
                                      <small class="text-muted">Kosongkan jika tidak ingin mengganti file</small>
                                    </div>
                                  <?php else: ?>
                                    <div class="mb-3">
                                      <label class="form-label fw-bold">Isi Konten</label>

                                      <!-- TinyMCE Editor -->
                                      <textarea
                                        name="isi_text"
                                        id="editor_<?php echo $layanan['id_layanan']; ?>"
                                        class="form-control tinymce-editor"
                                        rows="15"><?php echo htmlspecialchars_decode($layanan['isi']); ?></textarea>
                                    </div>
                                  <?php endif; ?>

                                  <!-- Action Buttons -->
                                  <div class="d-flex justify-content-between gap-2 mt-4">
                                    <button
                                      type="button"
                                      class="btn btn-danger"
                                      onclick="confirmDelete(<?php echo $layanan['id_layanan']; ?>, '<?php echo htmlspecialchars($layanan['nama_layanan']); ?>')">
                                      <i class="mdi mdi-delete"></i> Hapus
                                    </button>
                                    <div class="d-flex gap-2">
                                      <button
                                        type="button"
                                        class="btn btn-secondary"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#collapse_<?php echo $layanan['id_layanan']; ?>">
                                        <i class="mdi mdi-close"></i> Tutup
                                      </button>
                                      <button type="submit" class="btn btn-success">
                                        <i class="mdi mdi-content-save"></i> Simpan
                                      </button>
                                    </div>
                                  </div>
                                </form>
                              </div>
                      </div>
                    </div>
                  <?php endforeach; ?>
                <?php endif; ?>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Add Layanan Modal -->
  <div class="modal fade" id="addLayananModal" tabindex="-1" aria-labelledby="addLayananModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
      <div class="modal-content border-0 shadow-lg">
        <div class="modal-header border-0 pb-0">
          <h5 class="modal-title fw-bold" id="addLayananModalLabel">Tambah Data Layanan Informasi</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form method="POST" action="index.php?controller=layananInformasi&action=create" enctype="multipart/form-data" id="addLayananForm">
          <div class="modal-body px-4 py-3">
            <!-- Nama Layanan -->
            <div class="mb-4">
              <label class="form-label fw-semibold mb-2">Nama Layanan <span class="text-danger">*</span></label>
              <input
                type="text"
                class="form-control form-control-lg"
                name="nama_layanan"
                id="namaLayananInput"
                placeholder="Masukkan nama layanan"
                required>
            </div>

            <!-- Checkbox Sub Layanan dengan Card -->
            <div class="mb-4">
              <div class="card border sub-layanan-card">
                <div class="card-body p-3">
                  <div class="form-check form-switch">
                    <input
                      class="form-check-input"
                      type="checkbox"
                      role="switch"
                      name="has_sub_layanan"
                      id="hasSubLayananNew"
                      onchange="toggleSubLayananNew()">
                    <label class="form-check-label fw-semibold" for="hasSubLayananNew">
                      <i class="mdi mdi-format-list-bulleted-square me-1"></i>
                      Aktifkan Sub Layanan
                    </label>
                  </div>

                  <!-- Input Sub Layanan dengan Animasi -->
                  <div class="sub-layanan-input-wrapper mt-3" id="subLayananWrapper">
                    <label class="form-label fw-semibold mb-2">Sub Layanan</label>
                    <input
                      type="text"
                      class="form-control"
                      name="sub_layanan"
                      id="subLayananNew"
                      placeholder="Masukkan sub layanan">
                  </div>
                </div>
              </div>
            </div>

            <!-- Checkbox Sub Layanan 2 dengan Card -->
            <div class="mb-4">
              <div class="card border sub-layanan-card">
                <div class="card-body p-3">
                  <div class="form-check form-switch">
                    <input
                      class="form-check-input"
                      type="checkbox"
                      role="switch"
                      name="has_sub_layanan_2"
                      id="hasSubLayanan2New"
                      onchange="toggleSubLayanan2New()">
                    <label class="form-check-label fw-semibold" for="hasSubLayanan2New">
                      <i class="mdi mdi-format-list-bulleted-square me-1"></i>
                      Aktifkan Sub Layanan 2
                    </label>
                  </div>

                  <!-- Input Sub Layanan 2 dengan Animasi -->
                  <div class="sub-layanan-input-wrapper mt-3" id="subLayanan2Wrapper">
                    <label class="form-label fw-semibold mb-2">Sub Layanan 2</label>
                    <input
                      type="text"
                      class="form-control"
                      name="sub_layanan_2"
                      id="subLayanan2New"
                      placeholder="Masukkan sub layanan 2">
                  </div>
                </div>
              </div>
            </div>

            <!-- Isi Konten -->
            <div class="mb-3">
              <label class="form-label fw-semibold mb-2">Isi Konten <span class="text-danger">*</span></label>
              <div class="editor-wrapper">
                <textarea
                  name="isi_text"
                  id="newEditor"
                  class="form-control"
                  rows="15"></textarea>
              </div>
            </div>
          </div>
          <div class="modal-footer border-0 pt-0">
            <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary px-4">
              <i class="mdi mdi-content-save me-1"></i>
              Simpan Data
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <style>
    /* Layanan List - Minimalist & Dynamic */
    .layanan-list {
      position: relative;
    }

    .layanan-item {
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      border-radius: 8px;
      overflow: hidden;
      background: #fff;
    }

    .layanan-item:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08) !important;
    }

    /* Toggle Button */
    .layanan-toggle {
      cursor: pointer;
      transition: all 0.2s ease;
      position: relative;
    }

    .layanan-toggle:hover {
      background-color: #f8f9fa !important;
    }

    .layanan-toggle:focus {
      outline: none;
      box-shadow: none;
    }

    .layanan-toggle[aria-expanded="true"] {
      background-color: #f8f9fa !important;
      border-bottom: 1px solid #e9ecef;
    }

    .layanan-toggle[aria-expanded="true"] .chevron-icon i {
      transform: rotate(180deg);
    }

    /* Number Badge */
    .layanan-number .badge {
      width: 32px;
      height: 32px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 6px;
      font-size: 13px;
      font-weight: 600;
      border: 1px solid #e0e0e0;
      transition: all 0.3s ease;
    }

    .layanan-item:hover .layanan-number .badge {
      background-color: #2c3e50 !important;
      color: white !important;
      border-color: #2c3e50;
    }

    /* Layanan Info */
    .layanan-info h6 {
      font-size: 15px;
      color: #2c3e50;
      transition: color 0.2s ease;
    }

    .layanan-toggle:hover .layanan-info h6 {
      color: #1a252f;
    }

    .layanan-info small {
      font-size: 12px;
      line-height: 1.4;
    }

    /* Chevron Icon */
    .chevron-icon {
      transition: all 0.3s ease;
    }

    .chevron-icon i {
      font-size: 20px;
      transition: transform 0.3s ease;
    }

    /* Card Body */
    .layanan-item .card-body {
      background: #fafbfc;
      border-top: 1px solid #e9ecef;
      padding: 1.5rem;
    }

    /* Form Elements in Card Body */
    .layanan-item .form-label {
      color: #495057;
      font-size: 14px;
      margin-bottom: 0.5rem;
    }

    .layanan-item .form-control,
    .layanan-item .form-check-input {
      border-color: #dee2e6;
      transition: all 0.2s ease;
    }

    .layanan-item .form-control:focus {
      border-color: #6c757d;
      box-shadow: 0 0 0 0.2rem rgba(108, 117, 125, 0.15);
    }

    /* Sub Layanan Wrapper - Clean & Minimal */
    .sub-layanan-wrapper {
      background: #ffffff;
      border-color: #dee2e6 !important;
      transition: all 0.3s ease;
    }

    .sub-layanan-wrapper:has(.form-check-input:checked) {
      background: #f8f9fa;
      border-color: #6c757d !important;
    }

    .sub-layanan-wrapper .form-check-input {
      width: 2.5em;
      height: 1.25em;
      cursor: pointer;
      border: 2px solid #dee2e6;
    }

    .sub-layanan-wrapper .form-check-input:checked {
      background-color: #2c3e50;
      border-color: #2c3e50;
    }

    .sub-layanan-wrapper .form-check-label {
      color: #495057;
      cursor: pointer;
      font-size: 14px;
      user-select: none;
    }

    .sub-layanan-wrapper .form-check-input:checked ~ .form-check-label {
      color: #2c3e50;
      font-weight: 600;
    }

    /* Sub Layanan Input Area - Slide Animation */
    .sub-layanan-input-area {
      max-height: 0;
      opacity: 0;
      overflow: hidden;
      transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
      transform: translateY(-8px);
    }

    .sub-layanan-input-area.active {
      max-height: 150px;
      opacity: 1;
      transform: translateY(0);
    }

    .sub-layanan-input-area .form-control {
      border-radius: 6px;
      font-size: 14px;
    }

    .sub-layanan-input-area small {
      font-size: 12px;
      color: #6c757d;
    }

    /* Action Buttons Minimalist */
    .layanan-item .btn {
      border-radius: 6px;
      font-size: 13px;
      font-weight: 500;
      padding: 0.5rem 1rem;
      transition: all 0.2s ease;
    }

    .layanan-item .btn-danger {
      background-color: #fff;
      border: 1px solid #dc3545;
      color: #dc3545;
    }

    .layanan-item .btn-danger:hover {
      background-color: #dc3545;
      color: white;
      transform: translateY(-1px);
      box-shadow: 0 2px 8px rgba(220, 53, 69, 0.25);
    }

    .layanan-item .btn-secondary {
      background-color: #fff;
      border: 1px solid #6c757d;
      color: #6c757d;
    }

    .layanan-item .btn-secondary:hover {
      background-color: #6c757d;
      color: white;
      transform: translateY(-1px);
    }

    .layanan-item .btn-success {
      background-color: #2c3e50;
      border: 1px solid #2c3e50;
      color: white;
    }

    .layanan-item .btn-success:hover {
      background-color: #1a252f;
      border-color: #1a252f;
      transform: translateY(-1px);
      box-shadow: 0 2px 8px rgba(44, 62, 80, 0.25);
    }

    /* Collapse Animation */
    .collapse {
      transition: height 0.35s ease;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .layanan-toggle {
        padding: 1rem !important;
      }

      .layanan-number .badge {
        width: 28px;
        height: 28px;
        font-size: 12px;
      }

      .layanan-info h6 {
        font-size: 14px;
      }

      .layanan-info small {
        font-size: 11px;
      }

      .layanan-item .card-body {
        padding: 1rem;
      }

      .layanan-item .btn {
        font-size: 12px;
        padding: 0.4rem 0.8rem;
      }
    }

    @media (max-width: 576px) {
      .layanan-item {
        margin-bottom: 0.75rem !important;
      }

      .d-flex.justify-content-between.gap-2 {
        flex-direction: column;
      }

      .d-flex.justify-content-between.gap-2 > * {
        width: 100%;
      }

      .d-flex.gap-2 {
        gap: 0.5rem !important;
      }
    }

    /* Modal Responsif */
    .modal-content {
      border-radius: 10px;
    }

    .modal-body {
      max-height: 75vh;
      overflow-y: auto;
    }

    /* Form Control */
    .form-control-lg {
      padding: 0.75rem 1rem;
      font-size: 1rem;
      border-radius: 6px;
    }

    .form-control:focus {
      border-color: #4e73df;
      box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    }

    /* Sub Layanan Card - Dinamis */
    .sub-layanan-card {
      transition: all 0.3s ease;
      background: #f8f9fc;
    }

    .sub-layanan-card:has(.form-check-input:checked) {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      border-color: #667eea !important;
      box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .sub-layanan-card:has(.form-check-input:checked) .form-check-label {
      color: white;
      font-weight: 600;
    }

    .sub-layanan-card:has(.form-check-input:checked) .mdi {
      color: white;
    }

    /* Switch Checkbox - Lebih Besar */
    .form-check-input[type="checkbox"] {
      width: 3em;
      height: 1.5em;
      cursor: pointer;
      border: 2px solid #cbd5e0;
      transition: all 0.3s ease;
    }

    .form-check-input:checked {
      background-color: white;
      border-color: white;
    }

    .form-check-input:checked::before {
      background-color: #667eea;
    }

    .form-check-label {
      cursor: pointer;
      user-select: none;
      transition: all 0.3s ease;
    }

    /* Input Sub Layanan - Animasi Slide Down */
    .sub-layanan-input-wrapper {
      max-height: 0;
      opacity: 0;
      overflow: hidden;
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      transform: translateY(-10px);
    }

    .sub-layanan-input-wrapper.active {
      max-height: 200px;
      opacity: 1;
      transform: translateY(0);
    }

    .sub-layanan-input-wrapper label {
      color: white;
    }

    .sub-layanan-input-wrapper .form-control {
      background: rgba(255, 255, 255, 0.95);
      border: 2px solid rgba(255, 255, 255, 0.3);
      transition: all 0.3s ease;
    }

    .sub-layanan-input-wrapper .form-control:focus {
      background: white;
      border-color: white;
      box-shadow: 0 0 0 0.2rem rgba(255, 255, 255, 0.5);
    }

    /* Editor Wrapper */
    .editor-wrapper {
      border-radius: 6px;
      overflow: hidden;
    }

    /* Buttons */
    .modal-footer .btn {
      border-radius: 6px;
      font-weight: 500;
      transition: all 0.3s ease;
    }

    .modal-footer .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(78, 115, 223, 0.4);
    }

    .modal-footer .btn-secondary:hover {
      transform: translateY(-2px);
    }

    /* Scrollbar Custom */
    .modal-body::-webkit-scrollbar {
      width: 6px;
    }

    .modal-body::-webkit-scrollbar-track {
      background: #f1f1f1;
      border-radius: 10px;
    }

    .modal-body::-webkit-scrollbar-thumb {
      background: #888;
      border-radius: 10px;
    }

    .modal-body::-webkit-scrollbar-thumb:hover {
      background: #555;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .modal-lg {
        max-width: 95%;
        margin: 1rem auto;
      }

      .modal-body {
        padding: 1.5rem 1rem !important;
      }

      .form-control-lg {
        font-size: 0.95rem;
        padding: 0.65rem 0.9rem;
      }

      .form-check-input[type="checkbox"] {
        width: 2.5em;
        height: 1.3em;
      }
    }

    @media (max-width: 576px) {
      .modal-lg {
        max-width: 100%;
        margin: 0.5rem;
      }

      .modal-title {
        font-size: 1.1rem;
      }

      .modal-footer .btn {
        font-size: 0.9rem;
        padding: 0.5rem 1rem;
      }
    }
  </style>

  <!-- Delete Confirmation Form (Hidden) -->
  <form id="deleteForm" method="POST" action="index.php?controller=layananInformasi&action=delete" style="display: none;">
    <input type="hidden" name="id_layanan" id="deleteId">
  </form>

  <?php include 'template/script.php'; ?>

  <!-- TinyMCE Editor -->
   <script src="https://cdn.tiny.cloud/1/9lnh38d8gyc431et3kg0xkojz37mxqxpb7acund2y4xun237/tinymce/8/tinymce.min.js" referrerpolicy="origin" crossorigin="anonymous"></script>

  <script src="views/tinymce-init.js"></script>
</body>

</html>
