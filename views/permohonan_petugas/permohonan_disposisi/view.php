<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'petugas') {
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
      <?php include 'template/sidebar_petugas.php'; ?>
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row justify-content-center">
            <div class="col-12 col-xl-11">

              <!-- Header Section -->
              <div class="page-header mb-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                  <div class="mb-2 mb-md-0">
                    <h4 class="page-title mb-1 text-dark">Detail Disposisi Permohonan</h4>
                    <nav aria-label="breadcrumb">
                      <ol class="breadcrumb mb-0 fs-6">
                        <li class="breadcrumb-item"><a href="index.php?controller=user&action=index" class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="index.php?controller=permohonanpetugas&action=disposisiIndex" class="text-decoration-none">Disposisi</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Detail</li>
                      </ol>
                    </nav>
                  </div>
                  <div class="d-flex flex-column flex-sm-row gap-2">
                    <a href="index.php?controller=permohonanpetugas&action=generatePDF&id=<?php echo $permohonan['id_permohonan']; ?>" class="btn btn-success btn-sm" target="_blank">
                      <i class="fas fa-file-pdf me-1"></i>Cetak Bukti Permohonan
                    </a>
                    <a href="index.php?controller=permohonanpetugas&action=disposisiIndex" class="btn btn-outline-secondary btn-sm">
                      <i class="fas fa-arrow-left me-1"></i>Kembali
                    </a>
                  </div>
                </div>
              </div>

              <div class="row g-4">
                <!-- Permohonan Info -->
                <div class="col-lg-8">
                  <!-- Informasi Permohonan -->
                  <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-light border-bottom">
                      <h5 class="card-title mb-0 text-dark fw-normal">
                        <i class="fas fa-file-alt me-2 text-primary"></i>
                        Informasi Permohonan
                      </h5>
                    </div>
                    <div class="card-body p-4">
                      <div class="row g-3">
                        <div class="col-12">
                          <div class="info-item">
                            <label class="info-label">No. Permohonan</label>
                            <div class="info-value">
                              <span class="badge bg-primary fs-6 px-3 py-2"><?php echo htmlspecialchars($permohonan['no_permohonan'] ?? ''); ?></span>
                            </div>
                          </div>
                        </div>

                        <div class="col-md-6">
                          <div class="info-item">
                            <label class="info-label">Status Permohonan</label>
                            <div class="info-value">
                              <?php
                              $status = $permohonan['status'] ?? 'Diproses';
                              $statusClass = '';
                              switch ($status) {
                                case 'Selesai':
                                case 'approved':
                                  $statusClass = 'bg-success';
                                  break;
                                case 'Ditolak':
                                case 'rejected':
                                  $statusClass = 'bg-danger';
                                  break;
                                case 'Disposisi':
                                  $statusClass = 'bg-warning text-dark';
                                  break;
                                default:
                                  $statusClass = 'bg-secondary';
                              }
                              ?>
                              <span class="badge <?php echo $statusClass; ?> fs-6 px-3 py-2"><?php echo htmlspecialchars($status); ?></span>
                            </div>
                          </div>
                        </div>

                        <div class="col-md-6">
                          <div class="info-item">
                            <label class="info-label">SKPD Tujuan</label>
                            <div class="info-value">
                              <span class="badge bg-info fs-6 px-3 py-2"><?php echo htmlspecialchars($permohonan['komponen_tujuan'] ?? ''); ?></span>
                            </div>
                          </div>
                        </div>

                        <div class="col-12">
                          <div class="info-item">
                            <label class="info-label">Judul Dokumen Informasi</label>
                            <div class="info-value"><?php echo htmlspecialchars($permohonan['judul_dokumen'] ?? ''); ?></div>
                          </div>
                        </div>

                        <div class="col-12">
                          <div class="info-item">
                            <label class="info-label">Kandungan Informasi</label>
                            <div class="info-value"><?php echo nl2br(htmlspecialchars($permohonan['kandungan_informasi'] ?? '')); ?></div>
                          </div>
                        </div>

                        <div class="col-12">
                          <div class="info-item">
                            <label class="info-label">Tujuan Penggunaan Informasi</label>
                            <div class="info-value"><?php echo nl2br(htmlspecialchars($permohonan['tujuan_penggunaan_informasi'] ?? '')); ?></div>
                          </div>
                        </div>

                        <div class="col-md-6">
                          <div class="info-item">
                            <label class="info-label">Sumber Media</label>
                            <div class="info-value">
                              <span class="badge bg-info fs-6 px-3 py-2"><?php echo htmlspecialchars($permohonan['sumber_media'] ?? 'Website'); ?></span>
                            </div>
                          </div>
                        </div>

                        <div class="col-md-6">
                          <div class="info-item">
                            <label class="info-label">Sisa Jatuh Tempo</label>
                            <div class="info-value">
                              <span class="badge bg-secondary fs-6 px-3 py-2"><?php echo htmlspecialchars($permohonan['sisa_jatuh_tempo'] ?? '0'); ?> hari</span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Data Pemohon -->
                  <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-light border-bottom">
                      <h5 class="card-title mb-0 text-dark fw-normal">
                        <i class="fas fa-user me-2 text-primary"></i>
                        Data Pemohon
                      </h5>
                    </div>
                    <div class="card-body p-4">
                      <div class="row g-3">
                        <div class="col-md-6">
                          <div class="info-item">
                            <label class="info-label">Nama Lengkap</label>
                            <div class="info-value"><?php echo htmlspecialchars($permohonan['nama_lengkap'] ?? $permohonan['username']); ?></div>
                          </div>
                        </div>

                        <div class="col-md-6">
                          <div class="info-item">
                            <label class="info-label">NIK</label>
                            <div class="info-value"><?php echo htmlspecialchars($permohonan['nik'] ?? ''); ?></div>
                          </div>
                        </div>

                        <div class="col-md-6">
                          <div class="info-item">
                            <label class="info-label">Email</label>
                            <div class="info-value"><?php echo htmlspecialchars($permohonan['email'] ?? ''); ?></div>
                          </div>
                        </div>

                        <div class="col-md-6">
                          <div class="info-item">
                            <label class="info-label">No. Kontak</label>
                            <div class="info-value"><?php echo htmlspecialchars($permohonan['no_kontak'] ?? ''); ?></div>
                          </div>
                        </div>

                        <div class="col-12">
                          <div class="info-item">
                            <label class="info-label">Alamat Lengkap</label>
                            <div class="info-value"><?php echo nl2br(htmlspecialchars($permohonan['alamat'] ?? '')); ?></div>
                          </div>
                        </div>

                        <div class="col-12">
                          <div class="info-item">
                            <label class="info-label">Provinsi / Kota</label>
                            <div class="info-value"><?php echo htmlspecialchars(($permohonan['provinsi'] ?? '') . ', ' . ($permohonan['city'] ?? '')); ?></div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Actions & Files -->
                <div class="col-lg-4">
                  <!-- Update Status -->
                  <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-light border-bottom">
                      <h5 class="card-title mb-0 text-dark fw-normal">
                        <i class="fas fa-edit me-2 text-primary"></i>
                        Update Status
                      </h5>
                    </div>
                    <div class="card-body p-4">
                      <form id="status-form">
                        <input type="hidden" name="id" value="<?php echo $permohonan['id_permohonan']; ?>">
                        <div class="mb-3">
                          <label class="form-label text-dark fw-medium">Status Permohonan</label>
                          <select name="status" class="form-select form-control-lg" id="status-select">
                            <option value="Diproses">Diproses</option>
                          </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 btn-lg">
                          </i>Update Status
                        </button>
                      </form>

                      <div class="mt-4">
                        <button type="button" class="btn btn-warning w-100 btn-lg" id="perpanjang-jatuh-tempo" data-id="<?php echo $permohonan['id_permohonan']; ?>">
                          </i>Perpanjang Jatuh Tempo 7 Hari Kerja
                        </button>
                      </div>

                      <div class="mt-4">
                        <button type="button" class="btn btn-primary w-100 btn-lg" onclick="ubahStatusKeMasuk(<?php echo $permohonan['id_permohonan']; ?>)">
                          </i>Kembalikan Disposisi
                        </button>
                      </div>
                    </div>
                  </div>

                  <!-- Foto Identitas -->
                  <?php if (!empty($permohonan['upload_foto_identitas'])): ?>
                    <div class="card shadow-sm border-0 mb-4">
                      <div class="card-header bg-light border-bottom">
                        <h5 class="card-title mb-0 text-dark fw-normal">
                          <i class="fas fa-camera me-2 text-primary"></i>
                          Foto Identitas
                        </h5>
                      </div>
                      <div class="card-body p-4 text-center">
                        <div class="photo-container mb-3">
                          <img src="<?php echo htmlspecialchars($permohonan['upload_foto_identitas']); ?>"
                            class="img-fluid rounded shadow-sm" style="max-width: 100%; max-height: 300px; object-fit: cover;" alt="Foto Identitas">
                        </div>
                        <a href="index.php?controller=permohonanpetugas&action=downloadFile&file=<?php echo basename($permohonan['upload_foto_identitas']); ?>&id=<?php echo $permohonan['id_permohonan']; ?>"
                          class="btn btn-outline-primary btn-sm">
                          <i class="fas fa-download me-1"></i>Download Foto Identitas
                        </a>
                      </div>
                    </div>
                  <?php endif; ?>

                  <!-- Foto Profile -->
                  <?php if (!empty($permohonan['foto_profile'])): ?>
                    <div class="card shadow-sm border-0 mb-4">
                      <div class="card-header bg-light border-bottom">
                        <h5 class="card-title mb-0 text-dark fw-normal">
                          <i class="fas fa-user-circle me-2 text-primary"></i>
                          Foto Profile
                        </h5>
                      </div>
                      <div class="card-body p-4 text-center">
                        <div class="profile-photo-container">
                          <img src="<?php echo htmlspecialchars($permohonan['foto_profile']); ?>"
                            class="img-fluid rounded shadow-sm" style="max-width: 200px; max-height: 200px; object-fit: cover;" alt="Foto Profile">
                        </div>
                      </div>
                    </div>
                  <?php endif; ?>

                  <!-- File Downloads -->
                  <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-light border-bottom">
                      <h5 class="card-title mb-0 text-dark fw-normal">
                        <i class="fas fa-download me-2 text-primary"></i>
                        File Dokumen
                      </h5>
                    </div>
                    <div class="card-body p-4">
                      <div class="d-grid gap-2">
                        <?php if (!empty($permohonan['upload_ktp'])): ?>
                          <a href="index.php?controller=permohonanpetugas&action=downloadFile&file=<?php echo basename($permohonan['upload_ktp']); ?>&id=<?php echo $permohonan['id_permohonan']; ?>"
                            class="btn btn-outline-primary">
                            <i class="fas fa-id-card me-2"></i>Download KTP
                          </a>
                        <?php endif; ?>

                        <?php if (!empty($permohonan['upload_akta'])): ?>
                          <a href="index.php?controller=permohonanpetugas&action=downloadFile&file=<?php echo basename($permohonan['upload_akta']); ?>&id=<?php echo $permohonan['id_permohonan']; ?>"
                            class="btn btn-outline-primary">
                            <i class="fas fa-certificate me-2"></i>Download Akta Lembaga
                          </a>
                        <?php endif; ?>

                        <?php if (!empty($permohonan['upload_data_pedukung'])): ?>
                          <a href="index.php?controller=permohonanpetugas&action=downloadFile&file=<?php echo basename($permohonan['upload_data_pedukung']); ?>&id=<?php echo $permohonan['id_permohonan']; ?>"
                            class="btn btn-outline-info">
                            <i class="fas fa-file-upload me-2"></i>Download Data Pendukung
                          </a>
                        <?php endif; ?>

                        <?php if (
                          empty($permohonan['upload_ktp']) && empty($permohonan['upload_akta']) &&
                          empty($permohonan['upload_foto_identitas']) && empty($permohonan['upload_data_pedukung'])
                        ): ?>
                          <div class="text-center py-4">
                            <i class="fas fa-file-alt text-muted" style="font-size: 3rem; opacity: 0.3;"></i>
                            <p class="text-muted mt-3 mb-0">Tidak ada file upload</p>
                          </div>
                        <?php endif; ?>
                      </div>
                    </div>
                  </div>

                  <!-- Action Buttons -->
                  <div class="card shadow-sm border-0">
                    <div class="card-header bg-light border-bottom">
                      <h5 class="card-title mb-0 text-dark fw-normal">
                        <i class="fas fa-cogs me-2 text-primary"></i>
                        Aksi Disposisi
                      </h5>
                    </div>
                    <div class="card-body p-4">
                      <div class="d-grid gap-2">
                        <a href="index.php?controller=permohonanpetugas&action=generatePDF&id=<?php echo $permohonan['id_permohonan']; ?>"
                          class="btn btn-success" target="_blank">
                          <i class="fas fa-file-pdf me-2"></i>Bukti Permohonan (PDF)
                        </a>

                        <?php if (!empty($permohonan['upload_foto_identitas'])): ?>
                          <button type="button" class="btn btn-info" onclick="showPhotoModal('<?php echo htmlspecialchars($permohonan['upload_foto_identitas']); ?>')">
                            <i class="fas fa-camera me-2"></i>Lihat Foto Identitas
                          </button>
                        <?php endif; ?>

                        <button type="button" class="btn btn-danger" onclick="confirmDelete(<?php echo $permohonan['id_permohonan']; ?>)">
                          <i class="fas fa-trash me-2"></i>Hapus Permohonan
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Catatan Petugas -->
  <div class="modal fade" id="catatanPetugasModal" tabindex="-1" aria-labelledby="catatanPetugasModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="catatanPetugasModalLabel">
            <i class="fas fa-edit me-2"></i>Tambah Catatan - Update Status Diproses
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="catatan-petugas-form">
          <div class="modal-body">
            <input type="hidden" name="id" id="modal_id_permohonan" value="<?php echo $permohonan['id_permohonan']; ?>">
            <input type="hidden" name="status" value="Diproses">

            <div class="alert alert-info">
              <i class="fas fa-info-circle me-2"></i>
              <strong>Informasi:</strong> Tambahkan catatan internal mengenai proses permohonan ini.
            </div>

            <div class="mb-3">
              <label class="form-label fw-bold">Catatan Petugas <span class="text-danger">*</span></label>
              <textarea name="catatan_petugas" id="catatan_petugas" class="form-control" rows="6" placeholder="Masukkan catatan mengenai proses permohonan ini..." required></textarea>
              <small class="text-muted">Catatan ini untuk keperluan internal petugas</small>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
              <i class="fas fa-times me-1"></i>Batal
            </button>
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-save me-1"></i>Simpan & Update Status
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Photo Modal -->
  <div class="modal fade" id="photoModal" tabindex="-1" aria-labelledby="photoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="photoModalLabel">Foto Identitas</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body text-center">
          <img id="modalPhoto" src="" class="img-fluid rounded" alt="Foto Identitas" style="max-width: 100%; max-height: 70vh;">
        </div>
        <div class="modal-footer">
          <a id="downloadPhotoBtn" href="#" class="btn btn-primary" target="_blank">
            <i class="fas fa-download me-1"></i>Download
          </a>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        </div>
      </div>
    </div>
  </div>


  <?php include 'template/script.php'; ?>

  <script>
    // Handle status update
    $('#status-form').on('submit', function(e) {
      e.preventDefault();

      const selectedStatus = $('#status-select').val();

      // If status is "Diproses", show the catatan petugas modal
      if (selectedStatus === 'Diproses') {
        const catatanModal = new bootstrap.Modal(document.getElementById('catatanPetugasModal'));
        catatanModal.show();
        return;
      }

      // Otherwise, submit normally
      const formData = new FormData(this);

      // Debug: log form data
      console.log('Form data:');
      for (let pair of formData.entries()) {
        console.log(pair[0] + ': ' + pair[1]);
      }

      $.ajax({
        url: 'index.php?controller=permohonanpetugas&action=updateStatus',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(response) {
          console.log('Raw response:', response);
          if (response.success) {
            alert('Status berhasil diupdate');
            location.reload();
          } else {
            alert('Gagal mengupdate status: ' + response.message);
          }
        },
        error: function(xhr, status, error) {
          console.error('AJAX error:', error);
          console.log('Response Text:', xhr.responseText);
          alert('Terjadi kesalahan saat mengupdate status: ' + error);
        }
      });
    });

    // Handle catatan petugas form submission
    $('#catatan-petugas-form').on('submit', function(e) {
      e.preventDefault();

      const formData = new FormData(this);

      // Debug: log form data
      console.log('Catatan Petugas Form data:');
      for (let pair of formData.entries()) {
        console.log(pair[0] + ': ' + pair[1]);
      }

      $.ajax({
        url: 'index.php?controller=permohonanpetugas&action=updateStatus',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(response) {
          console.log('Raw response:', response);
          if (response.success) {
            alert('Status berhasil diupdate ke Diproses dengan catatan');
            location.reload();
          } else {
            alert('Gagal mengupdate status: ' + response.message);
          }
        },
        error: function(xhr, status, error) {
          console.error('AJAX error:', error);
          console.log('Response Text:', xhr.responseText);
          alert('Terjadi kesalahan saat mengupdate status: ' + error);
        }
      });
    });

    // Handle perpanjang jatuh tempo
    $('#perpanjang-jatuh-tempo').on('click', function() {
      const id = $(this).data('id');

      if (confirm('Apakah Anda yakin ingin memperpanjang jatuh tempo permohonan ini 7 hari kerja?')) {
        $.ajax({
          url: 'index.php?controller=permohonanpetugas&action=perpanjangJatuhTempo',
          type: 'POST',
          data: {
            id: id
          },
          dataType: 'json',
          success: function(response) {
            console.log('Perpanjang response:', response);
            if (response.success) {
              alert('Jatuh tempo berhasil diperpanjang');
              location.reload();
            } else {
              alert('Gagal memperpanjang jatuh tempo: ' + response.message);
            }
          },
          error: function(xhr, status, error) {
            console.error('Perpanjang AJAX error:', error);
            console.log('Response Text:', xhr.responseText);
            alert('Terjadi kesalahan saat memperpanjang jatuh tempo: ' + error);
          }
        });
      }
    });

    // Show photo modal
    function showPhotoModal(photoSrc) {
      document.getElementById('modalPhoto').src = photoSrc;
      document.getElementById('downloadPhotoBtn').href = 'index.php?controller=permohonanpetugas&action=downloadFile&file=' +
        encodeURIComponent(photoSrc.split('/').pop()) + '&id=<?php echo $permohonan['id_permohonan']; ?>';

      const modal = new bootstrap.Modal(document.getElementById('photoModal'));
      modal.show();
    }

    // Confirm delete
    function confirmDelete(id) {
      if (confirm('Apakah Anda yakin ingin menghapus permohonan ini?\n\nData yang dihapus tidak dapat dikembalikan dan akan menghapus semua data terkait termasuk user dan biodata.')) {
        window.location.href = 'index.php?controller=permohonanpetugas&action=delete&id=' + id;
      }
    }

    // Function to change status to Masuk
    function ubahStatusKeMasuk(id) {
      if (confirm('Apakah Anda yakin ingin mengembalikan permohonan ini ke status Masuk?')) {
        // Create a form to submit the request
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'index.php?controller=permohonanpetugas&action=ubahStatusKeMasuk';
        
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'id';
        input.value = id;
        
        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
      }
    }
  </script>

  <style>
    /* Government Standard Styling */
    :root {
      --gov-primary: #2563eb;
      --gov-secondary: #64748b;
      --gov-success: #10b981;
      --gov-danger: #ef4444;
      --gov-warning: #f59e0b;
      --gov-light: #f8fafc;
      --gov-dark: #1e293b;
      --gov-border: #e2e8f0;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      color: var(--gov-dark);
      background-color: #f1f5f9;
    }

    .page-header {
      background: white;
      padding: 1.5rem;
      border-radius: 8px;
      border: 1px solid var(--gov-border);
      margin-bottom: 1.5rem;
    }

    .page-title {
      color: var(--gov-dark);
      font-weight: 600;
      margin: 0;
    }

    .breadcrumb {
      background: none;
      padding: 0;
      margin: 0;
    }

    .breadcrumb-item a {
      color: var(--gov-secondary);
      text-decoration: none;
    }

    .breadcrumb-item.active {
      color: var(--gov-dark);
    }

    .card {
      border: 1px solid var(--gov-border);
      border-radius: 8px;
      background: white;
      box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
    }

    .card-header {
      background-color: #f8fafc;
      border-bottom: 1px solid var(--gov-border);
      padding: 1rem 1.5rem;
    }

    .card-title {
      font-size: 1.1rem;
      font-weight: 500;
      margin: 0;
    }

    .card-body {
      padding: 1.5rem;
      background: white;
    }

    .info-item {
      margin-bottom: 1.5rem;
    }

    .info-item:last-child {
      margin-bottom: 0;
    }

    .info-label {
      font-weight: 600;
      color: var(--gov-dark);
      font-size: 0.875rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      margin-bottom: 0.5rem;
      display: block;
    }

    .info-value {
      color: var(--gov-dark);
      font-size: 1rem;
      line-height: 1.5;
      padding: 0.75rem 0;
      border-bottom: 1px solid #f1f5f9;
    }

    .badge {
      font-size: 0.875rem;
      padding: 0.5rem 1rem;
      font-weight: 500;
      border-radius: 6px;
    }

    .bg-primary {
      background-color: var(--gov-primary) !important;
      color: white;
    }

    .bg-success {
      background-color: var(--gov-success) !important;
      color: white;
    }

    .bg-warning {
      background-color: var(--gov-warning) !important;
      color: white;
    }

    .bg-danger {
      background-color: var(--gov-danger) !important;
      color: white;
    }

    .bg-info {
      background-color: #0ea5e9 !important;
      color: white;
    }

    .bg-secondary {
      background-color: var(--gov-secondary) !important;
      color: white;
    }

    .btn {
      font-weight: 500;
      border-radius: 6px;
      transition: all 0.2s ease;
      border: none;
    }

    .btn-primary {
      background-color: var(--gov-primary);
      color: white;
      box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    .btn-primary:hover {
      background-color: #1d4ed8;
      transform: translateY(-1px);
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
    }

    .btn-success {
      background-color: var(--gov-success);
      color: white;
    }

    .btn-success:hover {
      background-color: #059669;
      transform: translateY(-1px);
    }

    .btn-info {
      background-color: #0ea5e9;
      color: white;
    }

    .btn-info:hover {
      background-color: #0284c7;
      transform: translateY(-1px);
    }

    .btn-warning {
      background-color: var(--gov-warning);
      color: white;
    }

    .btn-warning:hover {
      background-color: #d97706;
      transform: translateY(-1px);
    }

    .btn-danger {
      background-color: var(--gov-danger);
      color: white;
    }

    .btn-danger:hover {
      background-color: #dc2626;
      transform: translateY(-1px);
    }

    .btn-outline-primary {
      border: 1.5px solid var(--gov-primary);
      color: var(--gov-primary);
      background-color: white;
    }

    .btn-outline-primary:hover {
      background-color: var(--gov-primary);
      color: white;
      transform: translateY(-1px);
    }

    .btn-outline-info {
      border: 1.5px solid #0ea5e9;
      color: #0ea5e9;
      background-color: white;
    }

    .btn-outline-info:hover {
      background-color: #0ea5e9;
      color: white;
      transform: translateY(-1px);
    }

    .btn-outline-secondary {
      border: 1.5px solid var(--gov-secondary);
      color: var(--gov-secondary);
      background-color: white;
    }

    .btn-outline-secondary:hover {
      background-color: var(--gov-secondary);
      color: white;
      transform: translateY(-1px);
    }

    .btn-lg {
      padding: 0.75rem 1.5rem;
      font-size: 1rem;
    }

    .btn-sm {
      padding: 0.375rem 0.75rem;
      font-size: 0.875rem;
    }

    .form-control,
    .form-select {
      border: 1.5px solid var(--gov-border);
      border-radius: 6px;
      padding: 0.75rem 1rem;
      background-color: white;
      color: var(--gov-dark);
      transition: all 0.2s ease;
    }

    .form-control:focus,
    .form-select:focus {
      border-color: var(--gov-primary);
      box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
      background-color: white;
    }

    .form-control-lg {
      padding: 0.875rem 1.25rem;
      font-size: 1rem;
    }

    .form-label {
      color: var(--gov-dark);
      font-weight: 500;
      margin-bottom: 0.5rem;
    }

    .profile-photo-container {
      display: inline-block;
      position: relative;
    }

    .profile-photo-container img {
      border: 3px solid var(--gov-border);
    }

    .photo-container img {
      border: 2px solid var(--gov-border);
    }

    .d-grid {
      display: grid !important;
    }

    .gap-2 {
      gap: 0.5rem !important;
    }

    .text-muted {
      color: #94a3b8 !important;
    }

    .shadow-sm {
      box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
    }

    /* Modal Styles */
    .modal-content {
      border-radius: 8px;
      border: none;
      box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }

    .modal-header {
      background-color: var(--gov-light);
      border-bottom: 1px solid var(--gov-border);
    }

    .modal-footer {
      border-top: 1px solid var(--gov-border);
    }

    /* Responsive Design */
    @media (max-width: 992px) {

      .col-lg-8,
      .col-lg-4 {
        margin-bottom: 2rem;
      }

      .card-body {
        padding: 1rem;
      }

      .page-header {
        padding: 1rem;
      }
    }

    @media (max-width: 768px) {
      .container-fluid {
        padding: 1rem;
      }

      .info-item {
        margin-bottom: 1rem;
      }

      .info-label {
        font-size: 0.8rem;
      }

      .info-value {
        padding: 0.5rem 0;
      }

      .btn-lg {
        padding: 0.625rem 1.25rem;
        font-size: 0.95rem;
      }

      .d-flex.gap-2 {
        flex-direction: column;
        gap: 0.5rem !important;
      }

      .d-flex.gap-2 .btn {
        width: 100%;
      }

      .modal-dialog {
        margin: 1rem;
      }
    }

    @media (max-width: 576px) {
      .page-title {
        font-size: 1.5rem;
      }

      .breadcrumb {
        font-size: 0.875rem;
      }

      .card-body {
        padding: 1rem;
      }

      .badge {
        font-size: 0.8rem;
        padding: 0.375rem 0.75rem;
      }
    }

    /* Animation */
    .btn:active {
      transform: translateY(0);
    }

    .card {
      transition: box-shadow 0.2s ease;
    }

    .card:hover {
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
  </style>

</body>

</html>