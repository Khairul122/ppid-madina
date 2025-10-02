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

  .filter-tabs {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
    flex-wrap: wrap;
  }

  .filter-tab {
    padding: 10px 24px;
    border-radius: 8px;
    border: 2px solid #e9ecef;
    background: white;
    color: #6c757d;
    font-weight: 500;
    transition: all 0.3s ease;
    cursor: pointer;
    text-decoration: none;
  }

  .filter-tab:hover {
    border-color: #2a5298;
    color: #2a5298;
    transform: translateY(-2px);
  }

  .filter-tab.active {
    background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
    border-color: #1e3c72;
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

  .badge-kategori {
    padding: 8px 16px;
    border-radius: 6px;
    font-weight: 600;
    font-size: 0.9rem;
  }

  .badge-foto {
    background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
    color: white;
  }

  .badge-video {
    background: linear-gradient(135deg, #dc3545 0%, #b02a37 100%);
    color: white;
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

  .btn-view {
    background: white;
    border-color: #198754;
    color: #198754;
  }

  .btn-view:hover {
    background: #198754;
    color: white;
    border-color: #198754;
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
                        <i class="fas fa-images"></i>
                        Galeri Album PPID
                      </h4>
                      <a href="index.php?controller=album&action=form" class="btn btn-gov-primary">
                        <i class="fas fa-plus me-2"></i>Tambah Album
                      </a>
                    </div>
                  </div>

                  <!-- Filter Tabs -->
                  <div class="filter-tabs">
                    <a href="index.php?controller=album&action=index&kategori=all"
                       class="filter-tab <?= ($kategoriFilter ?? 'all') === 'all' ? 'active' : '' ?>">
                      <i class="fas fa-th-large me-2"></i>Semua
                    </a>
                    <a href="index.php?controller=album&action=index&kategori=foto"
                       class="filter-tab <?= ($kategoriFilter ?? 'all') === 'foto' ? 'active' : '' ?>">
                      <i class="fas fa-camera me-2"></i>Foto
                    </a>
                    <a href="index.php?controller=album&action=index&kategori=video"
                       class="filter-tab <?= ($kategoriFilter ?? 'all') === 'video' ? 'active' : '' ?>">
                      <i class="fas fa-video me-2"></i>Video
                    </a>
                  </div>

                  <div class="table-responsive">
                    <table class="table gov-table table-hover">
                      <thead>
                        <tr>
                          <th style="width: 80px;">No</th>
                          <th style="width: 150px;">Kategori</th>
                          <th>Nama Album</th>
                          <th style="width: 200px;">File</th>
                          <th style="width: 220px;">Aksi</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if (!empty($albumList)): ?>
                          <?php $no = 1; foreach ($albumList as $album): ?>
                            <tr>
                              <td class="text-center"><strong><?= $no++ ?></strong></td>
                              <td>
                                <span class="badge-kategori badge-<?= $album['kategori'] ?>">
                                  <i class="fas fa-<?= $album['kategori'] === 'foto' ? 'camera' : 'video' ?> me-1"></i>
                                  <?= ucfirst($album['kategori']) ?>
                                </span>
                              </td>
                              <td><strong><?= htmlspecialchars($album['nama_album']) ?></strong></td>
                              <td>
                                <?php if (!empty($album['upload'])): ?>
                                  <a href="<?= htmlspecialchars($album['upload']) ?>" target="_blank" class="text-primary">
                                    <i class="fas fa-file-<?= $album['kategori'] === 'foto' ? 'image' : 'video' ?> me-1"></i>
                                    Lihat File
                                  </a>
                                <?php else: ?>
                                  <span class="text-muted">-</span>
                                <?php endif; ?>
                              </td>
                              <td>
                                <?php if (!empty($album['upload'])): ?>
                                  <a href="<?= htmlspecialchars($album['upload']) ?>" target="_blank"
                                     class="btn btn-sm btn-action btn-view me-1">
                                    <i class="fas fa-eye me-1"></i>Lihat
                                  </a>
                                <?php endif; ?>
                                <a href="index.php?controller=album&action=form&id=<?= $album['id_album'] ?>"
                                   class="btn btn-sm btn-action btn-edit me-1">
                                  <i class="fas fa-edit me-1"></i>Edit
                                </a>
                                <button class="btn btn-sm btn-action btn-delete"
                                        onclick="confirmDelete(<?= $album['id_album'] ?>, '<?= addslashes(htmlspecialchars($album['nama_album'])) ?>')">
                                  <i class="fas fa-trash me-1"></i>Hapus
                                </button>
                              </td>
                            </tr>
                          <?php endforeach; ?>
                        <?php else: ?>
                          <tr>
                            <td colspan="5" class="text-center">
                              <div class="empty-state">
                                <i class="fas fa-images fa-4x mb-4"></i>
                                <h5 class="text-muted mb-3">Belum Ada Album</h5>
                                <p class="text-muted mb-4">Silakan tambahkan album foto atau video untuk memulai</p>
                                <a href="index.php?controller=album&action=form" class="btn btn-gov-primary">
                                  <i class="fas fa-plus me-2"></i>Tambah Album
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
      if (confirm(`Apakah Anda yakin ingin menghapus album "${name}"?`)) {
        fetch(`index.php?controller=album&action=delete&id=${id}`, {
          method: 'GET',
          headers: {
            'Content-Type': 'application/json',
          }
        })
        .then(response => {
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
