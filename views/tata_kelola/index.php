<?php include('template/header.php'); ?>

<style>
  .gov-card {
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: none;
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    transition: all 0.3s ease;
  }

  .gov-card:hover {
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
    transform: translateY(-2px);
  }

  .gov-header {
    background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
    border-radius: 12px 12px 0 0;
    padding: 25px 30px;
    color: white;
    margin: -20px -20px 25px -20px;
  }

  .gov-title {
    font-size: 1.75rem;
    font-weight: 600;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 12px;
  }

  .gov-title i {
    color: #ffd700;
  }

  .btn-gov-primary {
    background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
    border: none;
    color: white;
    padding: 12px 24px;
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(30, 60, 114, 0.3);
  }

  .btn-gov-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(30, 60, 114, 0.4);
    background: linear-gradient(135deg, #2a5298 0%, #1e3c72 100%);
    color: white;
  }

  .gov-table {
    font-size: 1.05rem;
  }

  .gov-table thead {
    background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
    color: white;
  }

  .gov-table thead th {
    border: none;
    padding: 18px 15px;
    font-weight: 600;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    font-size: 0.9rem;
  }

  .gov-table tbody tr {
    transition: all 0.3s ease;
    border-bottom: 1px solid #e9ecef;
  }

  .gov-table tbody tr:hover {
    background: linear-gradient(90deg, #f8f9fa 0%, #e9ecef 100%);
    transform: scale(1.01);
  }

  .gov-table tbody td {
    padding: 18px 15px;
    vertical-align: middle;
  }

  .btn-action {
    padding: 8px 16px;
    border-radius: 6px;
    transition: all 0.3s ease;
    font-weight: 500;
    border-width: 2px;
  }

  .btn-action:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  }

  .btn-edit {
    background: white;
    border-color: #0d6efd;
    color: #0d6efd;
  }

  .btn-edit:hover {
    background: #0d6efd;
    color: white;
    border-color: #0d6efd;
  }

  .btn-delete {
    background: white;
    border-color: #dc3545;
    color: #dc3545;
  }

  .btn-delete:hover {
    background: #dc3545;
    color: white;
    border-color: #dc3545;
  }

  .link-external {
    color: #1e3c72;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
  }

  .link-external:hover {
    color: #2a5298;
    text-decoration: underline;
  }

  .empty-state {
    padding: 60px 20px;
  }

  .empty-state i {
    color: #1e3c72;
    opacity: 0.3;
  }

  @media (max-width: 768px) {
    .gov-title {
      font-size: 1.3rem;
    }

    .gov-header {
      padding: 20px 20px;
    }

    .gov-table {
      font-size: 0.95rem;
    }

    .btn-gov-primary {
      padding: 10px 18px;
      font-size: 0.9rem;
    }
  }
</style>

<body class="with-welcome-text">
  <div class="container-scroller">
    <?php include 'template/navbar.php'; ?>
    <div class="container-fluid page-body-wrapper">
      <?php include 'template/setting_panel.php'; ?>
      <?php include 'template/sidebar.php'; ?>
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-12">
              <div class="card gov-card">
                <div class="card-body">
                  <div class="gov-header">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                      <h4 class="gov-title">
                        Data Tata Kelola PPID
                      </h4>
                      <a href="index.php?controller=tataKelola&action=form" class="btn btn-gov-primary">
                        <i class="fas fa-plus me-2"></i>Tambah Data
                      </a>
                    </div>
                  </div>

                  <div class="table-responsive">
                    <table class="table gov-table table-hover">
                      <thead>
                        <tr>
                          <th style="width: 80px;">No</th>
                          <th>Nama Tata Kelola</th>
                          <th style="width: 200px;">Link</th>
                          <th style="width: 180px;">Aksi</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if (!empty($tataKelolaList)): ?>
                          <?php $no = 1; foreach ($tataKelolaList as $tataKelola): ?>
                            <tr>
                              <td class="text-center"><strong><?= $no++ ?></strong></td>
                              <td><strong><?= htmlspecialchars($tataKelola['nama_tata_kelola']) ?></strong></td>
                              <td>
                                <?php if (!empty($tataKelola['link'])): ?>
                                  <a href="<?= htmlspecialchars($tataKelola['link']) ?>" target="_blank" class="link-external">
                                    <i class="fas fa-external-link-alt"></i>
                                    Lihat Link
                                  </a>
                                <?php else: ?>
                                  <span class="text-muted">-</span>
                                <?php endif; ?>
                              </td>
                              <td>
                                <a href="index.php?controller=tataKelola&action=form&id=<?= $tataKelola['id_tata_kelola'] ?>"
                                   class="btn btn-sm btn-action btn-edit me-2">
                                  <i class="fas fa-edit me-1"></i>Edit
                                </a>
                                <button class="btn btn-sm btn-action btn-delete"
                                        onclick="confirmDelete(<?= $tataKelola['id_tata_kelola'] ?>, '<?= addslashes(htmlspecialchars($tataKelola['nama_tata_kelola'])) ?>')">
                                  <i class="fas fa-trash me-1"></i>Hapus
                                </button>
                              </td>
                            </tr>
                          <?php endforeach; ?>
                        <?php else: ?>
                          <tr>
                            <td colspan="4" class="text-center">
                              <div class="empty-state">
                                <i class="fas fa-folder-open fa-4x mb-4"></i>
                                <h5 class="text-muted mb-3">Belum Ada Data Tata Kelola</h5>
                                <p class="text-muted mb-4">Silakan tambahkan data tata kelola untuk memulai</p>
                                <a href="index.php?controller=tataKelola&action=form" class="btn btn-gov-primary">
                                  <i class="fas fa-plus me-2"></i>Tambah Data Tata Kelola
                                </a>
                              </div>
                            </td>
                          </tr>
                        <?php endif; ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php include 'template/script.php'; ?>

  <script>
    function confirmDelete(id, name) {
      if (confirm(`Apakah Anda yakin ingin menghapus data "${name}"?`)) {
        fetch(`index.php?controller=tataKelola&action=delete&id=${id}`, {
          method: 'GET',
          headers: {
            'Content-Type': 'application/json',
          }
        })
        .then(response => {
          // Check if the response status is OK (200-299)
          if (!response.ok) {
            throw new Error('Network response was not ok');
          }
          return response.json();
        })
        .then(data => {
          if (data.success) {
            alert(data.message);
            window.location.href = data.redirect;
          } else {
            alert(data.message);
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Terjadi kesalahan saat menghapus data. Silakan coba lagi.');
        });
      }
    }
  </script>
</body>

</html>