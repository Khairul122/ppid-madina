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
                  <i class="mdi mdi-newspaper me-2" style="font-size: 24px;"></i>
                  <span style="font-size: 18px; font-weight: 500;">Data Berita</span>
                </div>
                <div class="d-flex gap-2">
                  <a href="index.php?controller=berita&action=create" class="btn btn-primary btn-sm">
                    <i class="mdi mdi-plus"></i> Tambah Berita
                  </a>
                  <a href="index.php?controller=berita&action=export" class="btn btn-success btn-sm">
                    <i class="mdi mdi-file-excel"></i> Export Excel
                  </a>
                </div>
              </div>

              <!-- Search Form -->
              <div class="card mb-3">
                <div class="card-body py-3">
                  <form method="GET" class="d-flex align-items-center">
                    <input type="hidden" name="controller" value="berita">
                    <input type="hidden" name="action" value="index">
                    <div class="input-group">
                      <input
                        type="text"
                        name="search"
                        class="form-control"
                        placeholder="Cari berdasarkan judul, URL, atau summary..."
                        value="<?php echo htmlspecialchars($data['search']); ?>"
                      >
                      <button class="btn btn-outline-primary" type="submit">
                        <i class="mdi mdi-magnify"></i> Cari
                      </button>
                      <?php if (!empty($data['search'])): ?>
                        <a href="index.php?controller=berita" class="btn btn-outline-secondary">
                          <i class="mdi mdi-close"></i> Reset
                        </a>
                      <?php endif; ?>
                    </div>
                  </form>
                </div>
              </div>

              <!-- Data Table -->
              <div class="card">
                <div class="card-body">
                  <?php if (!empty($data['search'])): ?>
                    <div class="mb-3">
                      <small class="text-muted">
                        Menampilkan <?php echo count($data['berita']); ?> dari <?php echo $data['totalCount']; ?> hasil
                        untuk pencarian: <strong>"<?php echo htmlspecialchars($data['search']); ?>"</strong>
                      </small>
                    </div>
                  <?php else: ?>
                    <div class="mb-3">
                      <small class="text-muted">Total: <?php echo $data['totalCount']; ?> berita</small>
                    </div>
                  <?php endif; ?>

                  <?php if (empty($data['berita'])): ?>
                    <div class="text-center py-4">
                      <i class="mdi mdi-newspaper" style="font-size: 48px; color: #ccc;"></i>
                      <p class="text-muted mt-2">
                        <?php echo !empty($data['search']) ? 'Tidak ada berita yang ditemukan.' : 'Belum ada data berita.'; ?>
                      </p>
                    </div>
                  <?php else: ?>
                    <div class="table-responsive">
                      <table class="table table-hover">
                        <thead class="table-light">
                          <tr>
                            <th style="width: 50px;">No</th>
                            <th>Judul</th>
                            <th>URL</th>
                            <th>Summary</th>
                            <th>Image</th>
                            <th style="width: 120px;">Tanggal</th>
                            <th style="width: 100px;">Aksi</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $no = ($data['currentPage'] - 1) * $data['limit'] + 1;
                          foreach ($data['berita'] as $berita):
                          ?>
                            <tr>
                              <td><?php echo $no++; ?></td>
                              <td>
                                <strong><?php echo htmlspecialchars($berita['judul']); ?></strong>
                              </td>
                              <td>
                                <?php if (!empty($berita['url'])): ?>
                                  <a href="<?php echo htmlspecialchars($berita['url']); ?>" target="_blank" class="text-primary">
                                    <i class="mdi mdi-open-in-new"></i>
                                  </a>
                                <?php else: ?>
                                  <span class="text-muted">-</span>
                                <?php endif; ?>
                              </td>
                              <td>
                                <div style="max-width: 200px;">
                                  <?php
                                  $summary = strip_tags($berita['summary']);
                                  echo strlen($summary) > 100 ? substr($summary, 0, 100) . '...' : $summary;
                                  ?>
                                </div>
                              </td>
                              <td>
                                <?php if (!empty($berita['image'])): ?>
                                  <?php if (filter_var($berita['image'], FILTER_VALIDATE_URL)): ?>
                                    <img src="<?php echo htmlspecialchars($berita['image']); ?>" alt="Image" class="img-thumbnail" style="max-width: 50px; max-height: 50px;">
                                  <?php elseif (file_exists($berita['image'])): ?>
                                    <img src="<?php echo htmlspecialchars($berita['image']); ?>" alt="Image" class="img-thumbnail" style="max-width: 50px; max-height: 50px;">
                                  <?php else: ?>
                                    <span class="text-muted">No Image</span>
                                  <?php endif; ?>
                                <?php else: ?>
                                  <span class="text-muted">No Image</span>
                                <?php endif; ?>
                              </td>
                              <td>
                                <small><?php echo date('d/m/Y', strtotime($berita['created_at'])); ?></small>
                              </td>
                              <td>
                                <div class="dropdown">
                                  <button class="btn btn-sm btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="mdi mdi-dots-vertical"></i>
                                  </button>
                                  <ul class="dropdown-menu">
                                    <li>
                                      <a class="dropdown-item" href="index.php?controller=berita&action=edit&id=<?php echo $berita['id_berita']; ?>">
                                        <i class="mdi mdi-pencil"></i> Edit
                                      </a>
                                    </li>
                                    <li>
                                      <a class="dropdown-item text-danger" href="index.php?controller=berita&action=delete&id=<?php echo $berita['id_berita']; ?>"
                                         onclick="return confirm('Apakah Anda yakin ingin menghapus berita ini?')">
                                        <i class="mdi mdi-delete"></i> Hapus
                                      </a>
                                    </li>
                                  </ul>
                                </div>
                              </td>
                            </tr>
                          <?php endforeach; ?>
                        </tbody>
                      </table>
                    </div>

                    <!-- Pagination -->
                    <?php if ($data['totalPages'] > 1): ?>
                      <nav aria-label="Page navigation" class="mt-3">
                        <ul class="pagination justify-content-center">
                          <?php if ($data['currentPage'] > 1): ?>
                            <li class="page-item">
                              <a class="page-link" href="?controller=berita&page=<?php echo $data['currentPage'] - 1; ?><?php echo !empty($data['search']) ? '&search=' . urlencode($data['search']) : ''; ?>">
                                <i class="mdi mdi-chevron-left"></i>
                              </a>
                            </li>
                          <?php endif; ?>

                          <?php
                          $startPage = max(1, $data['currentPage'] - 2);
                          $endPage = min($data['totalPages'], $data['currentPage'] + 2);

                          for ($i = $startPage; $i <= $endPage; $i++):
                          ?>
                            <li class="page-item <?php echo $i == $data['currentPage'] ? 'active' : ''; ?>">
                              <a class="page-link" href="?controller=berita&page=<?php echo $i; ?><?php echo !empty($data['search']) ? '&search=' . urlencode($data['search']) : ''; ?>">
                                <?php echo $i; ?>
                              </a>
                            </li>
                          <?php endfor; ?>

                          <?php if ($data['currentPage'] < $data['totalPages']): ?>
                            <li class="page-item">
                              <a class="page-link" href="?controller=berita&page=<?php echo $data['currentPage'] + 1; ?><?php echo !empty($data['search']) ? '&search=' . urlencode($data['search']) : ''; ?>">
                                <i class="mdi mdi-chevron-right"></i>
                              </a>
                            </li>
                          <?php endif; ?>
                        </ul>
                      </nav>

                      <div class="text-center mt-2">
                        <small class="text-muted">
                          Halaman <?php echo $data['currentPage']; ?> dari <?php echo $data['totalPages']; ?>
                          (<?php echo $data['totalCount']; ?> total data)
                        </small>
                      </div>
                    <?php endif; ?>
                  <?php endif; ?>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php include 'template/script.php'; ?>
</body>

</html>