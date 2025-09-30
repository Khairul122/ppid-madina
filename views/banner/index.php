<?php
// Check if user is logged in and has admin role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
  header('Location: index.php?controller=auth&action=login');
  exit();
}

include 'template/header.php';
?>

<body class="with-welcome-text">
  <div class="container-scroller">
    <?php include 'template/navbar.php'; ?>
    <div class="container-fluid page-body-wrapper">
      <?php include 'template/setting_panel.php'; ?>
      <?php include 'template/sidebar.php'; ?>
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-12 grid-margin">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Manage Banner</h4>

                  <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success">
                      <?php echo $_SESSION['success'];
                      unset($_SESSION['success']); ?>
                    </div>
                  <?php endif; ?>

                  <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger">
                      <?php echo $_SESSION['error'];
                      unset($_SESSION['error']); ?>
                    </div>
                  <?php endif; ?>

                  <div class="row">
                    <div class="col-md-6">
                      <a href="index.php?controller=banner&action=create" class="btn btn-primary btn-sm mb-3">
                        <i class="mdi mdi-plus"></i> Tambah Banner
                      </a>
                    </div>
                    <div class="col-md-6 text-md-right">
                      <form method="GET" class="form-inline">
                        <input type="hidden" name="controller" value="banner">
                        <input type="hidden" name="action" value="index">
                        <div class="input-group">
                          <input type="text" name="search" class="form-control" placeholder="Cari judul atau teks..." value="<?php echo htmlspecialchars($data['search']); ?>">
                          <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="submit">Cari</button>
                            <a href="index.php?controller=banner&action=index" class="btn btn-outline-secondary ml-1">Reset</a>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>

                  <div class="table-responsive">
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th>No</th>
                          <th>Upload</th>
                          <th>Judul</th>
                          <th>Teks</th>
                          <th>Urutan</th>
                          <th>Created At</th>
                          <th>Aksi</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if (empty($data['banners'])): ?>
                          <tr>
                            <td colspan="7" class="text-center">Tidak ada data banner</td>
                          </tr>
                        <?php else: ?>
                          <?php $no = 1; ?>
                          <?php foreach ($data['banners'] as $banner): ?>
                            <tr>
                              <td><?php echo $no++; ?></td>
                              <td>
                                <?php if (!empty($banner['upload'])): ?>
                                  <?php $ext = strtolower(pathinfo($banner['upload'], PATHINFO_EXTENSION)); ?>
                                  <?php if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])): ?>
                                    <img src="<?php echo $banner['upload']; ?>" alt="Banner Image" width="50" height="30">
                                  <?php else: ?>
                                    <video width="50" height="30" controls>
                                      <source src="<?php echo $banner['upload']; ?>" type="video/<?php echo $ext; ?>">
                                      Video
                                    </video>
                                  <?php endif; ?>
                                <?php else: ?>
                                  -
                                <?php endif; ?>
                              </td>
                              <td><?php echo htmlspecialchars($banner['judul']); ?></td>
                              <td><?php echo htmlspecialchars(substr($banner['teks'], 0, 50)) . (strlen($banner['teks']) > 50 ? '...' : ''); ?></td>
                              <td><?php echo htmlspecialchars($banner['urutan']); ?></td>
                              <td><?php echo date('d/m/Y H:i', strtotime($banner['created_at'])); ?></td>
                              <td>
                                <div style="display: inline-flex; gap: 5px;">
                                  <a href="index.php?controller=banner&action=edit&id=<?php echo $banner['id_banner']; ?>" class="btn btn-warning btn-sm">
                                    <i class="mdi mdi-pencil"></i>
                                  </a>
                                  <a href="index.php?controller=banner&action=delete&id=<?php echo $banner['id_banner']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus banner ini?')">
                                    <i class="mdi mdi-delete"></i>
                                  </a>
                                </div>
                              </td>
                            </tr>
                          <?php endforeach; ?>
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
                            <a class="page-link" href="index.php?controller=banner&action=index&search=<?php echo urlencode($data['search']); ?>&page=<?php echo $data['currentPage'] - 1; ?>">Sebelumnya</a>
                          </li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $data['totalPages']; $i++): ?>
                          <?php if ($i >= max(1, $data['currentPage'] - 2) && $i <= min($data['totalPages'], $data['currentPage'] + 2)): ?>
                            <li class="page-item <?php echo ($i == $data['currentPage']) ? 'active' : ''; ?>">
                              <a class="page-link" href="index.php?controller=banner&action=index&search=<?php echo urlencode($data['search']); ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                            </li>
                          <?php endif; ?>
                        <?php endfor; ?>

                        <?php if ($data['currentPage'] < $data['totalPages']): ?>
                          <li class="page-item">
                            <a class="page-link" href="index.php?controller=banner&action=index&search=<?php echo urlencode($data['search']); ?>&page=<?php echo $data['currentPage'] + 1; ?>">Selanjutnya</a>
                          </li>
                        <?php endif; ?>
                      </ul>
                    </nav>
                  <?php endif; ?>

                  <div class="mt-3">
                    <p>Menampilkan <?php echo count($data['banners']); ?> dari <?php echo $data['totalCount']; ?> total data</p>
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
</body>

</html>