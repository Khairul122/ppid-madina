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
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h4 class="card-title">Daftar Kategori</h4>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <a href="index.php?controller=kategori&action=create" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Tambah Kategori
                                </a>
                            </div>
                        </div>
                        
                        <!-- Alert Messages -->
                        <?php if (isset($_SESSION['success'])): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <?= $_SESSION['success']; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                            <?php unset($_SESSION['success']); ?>
                        <?php endif; ?>
                        
                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?= $_SESSION['error']; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                            <?php unset($_SESSION['error']); ?>
                        <?php endif; ?>
                        
                        <!-- Search Form -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <form method="GET" action="index.php" class="d-flex">
                                    <input type="hidden" name="controller" value="kategori">
                                    <input type="hidden" name="action" value="index">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="search" placeholder="Cari kategori..." 
                                               value="<?= htmlspecialchars($data['search'] ?? '') ?>">
                                        <button class="btn btn-outline-secondary" type="submit">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        
                        <!-- Data Table -->
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Kategori</th>
                                        <th>Dibuat Pada</th>
                                        <th>Diupdate Pada</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($data['kategoris'])): ?>
                                        <?php 
                                        $no = ($data['currentPage'] - 1) * $data['limit'] + 1;
                                        foreach ($data['kategoris'] as $kategori): ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td><?= htmlspecialchars($kategori['nama_kategori']) ?></td>
                                                <td><?= date('d M Y H:i', strtotime($kategori['created_at'])) ?></td>
                                                <td><?= date('d M Y H:i', strtotime($kategori['updated_at'])) ?></td>
                                                <td class="text-center">
                                                    <a href="index.php?controller=kategori&action=edit&id=<?= $kategori['id_kategori'] ?>" 
                                                       class="btn btn-sm btn-outline-primary" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="#" class="btn btn-sm btn-outline-danger delete-btn" 
                                                       data-id="<?= $kategori['id_kategori'] ?>" title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="text-center">Tidak ada data kategori</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <?php if ($data['totalPages'] > 1): ?>
                            <nav aria-label="Page navigation">
                                <ul class="pagination justify-content-center">
                                    <?php if ($data['currentPage'] > 1): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="index.php?controller=kategori&action=index&page=<?= $data['currentPage'] - 1 ?>&search=<?= urlencode($data['search']) ?>">Sebelumnya</a>
                                        </li>
                                    <?php endif; ?>
                                    
                                    <?php for ($i = 1; $i <= $data['totalPages']; $i++): ?>
                                        <?php if ($i >= max(1, $data['currentPage'] - 2) && $i <= min($data['totalPages'], $data['currentPage'] + 2)): ?>
                                            <li class="page-item <?= $i == $data['currentPage'] ? 'active' : '' ?>">
                                                <a class="page-link" href="index.php?controller=kategori&action=index&page=<?= $i ?>&search=<?= urlencode($data['search']) ?>"><?= $i ?></a>
                                            </li>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                    
                                    <?php if ($data['currentPage'] < $data['totalPages']): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="index.php?controller=kategori&action=index&page=<?= $data['currentPage'] + 1 ?>&search=<?= urlencode($data['search']) ?>">Selanjutnya</a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </nav>
                        <?php endif; ?>
                        
                        <div class="mt-3">
                            <small class="text-muted">Total: <?= $data['totalCount'] ?> kategori</small>
                        </div>
                    </div>
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Delete Confirmation Modal -->
  <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                  Apakah Anda yakin ingin menghapus kategori ini? Tindakan ini tidak dapat dibatalkan.
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                  <a href="#" id="confirmDeleteBtn" class="btn btn-danger">Hapus</a>
              </div>
          </div>
      </div>
  </div>

  <script>
      // Delete confirmation functionality
      document.querySelectorAll('.delete-btn').forEach(function(button) {
          button.addEventListener('click', function(e) {
              e.preventDefault();
              const id = this.getAttribute('data-id');
              const deleteUrl = `index.php?controller=kategori&action=delete&id=${id}`;
              
              document.getElementById('confirmDeleteBtn').href = deleteUrl;
              const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
              modal.show();
          });
      });
  </script>
  
  <?php include 'template/script.php'; ?>
</body>

</html>