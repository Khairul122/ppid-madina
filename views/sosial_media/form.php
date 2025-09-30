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
                  <h4 class="card-title"><?php echo ($data['action'] === 'create') ? 'Tambah Sosial Media' : 'Edit Sosial Media'; ?></h4>
                  
                  <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger">
                      <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                    </div>
                  <?php endif; ?>

                  <form method="POST" action="index.php?controller=sosialmedia&action=<?php echo $data['action']; ?><?php if ($data['action'] === 'edit'): ?>&id=<?php echo $data['sosialMedia']['id_sosial_media']; ?><?php endif; ?>">
                    <div class="form-group">
                      <label for="site">Site *</label>
                      <input type="text" class="form-control" id="site" name="site" value="<?php echo isset($data['sosialMedia']) ? htmlspecialchars($data['sosialMedia']['site']) : ''; ?>" required>
                      <small class="form-text text-muted">Nama situs media sosial (contoh: Facebook, Instagram, Twitter)</small>
                    </div>
                    
                    <div class="form-group">
                      <label for="facebook_link">Facebook Link</label>
                      <input type="url" class="form-control" id="facebook_link" name="facebook_link" value="<?php echo isset($data['sosialMedia']) ? htmlspecialchars($data['sosialMedia']['facebook_link']) : ''; ?>">
                      <small class="form-text text-muted">URL lengkap ke akun Facebook (contoh: https://www.facebook.com/namaakun)</small>
                    </div>
                    
                    <div class="form-group">
                      <label for="instagram_link">Instagram Link</label>
                      <input type="url" class="form-control" id="instagram_link" name="instagram_link" value="<?php echo isset($data['sosialMedia']) ? htmlspecialchars($data['sosialMedia']['instagram_link']) : ''; ?>">
                      <small class="form-text text-muted">URL lengkap ke akun Instagram (contoh: https://www.instagram.com/namaakun)</small>
                    </div>
                    
                    <div class="form-group">
                      <label for="instagram_post">Instagram Post</label>
                      <input type="url" class="form-control" id="instagram_post" name="instagram_post" value="<?php echo isset($data['sosialMedia']) ? htmlspecialchars($data['sosialMedia']['instagram_post']) : ''; ?>">
                      <small class="form-text text-muted">URL lengkap ke post Instagram terbaru</small>
                    </div>
                    
                    <div class="form-group">
                      <button type="submit" class="btn btn-<?php echo ($data['action'] === 'create') ? 'success' : 'primary'; ?>"><?php echo ($data['action'] === 'create') ? 'Simpan' : 'Update'; ?></button>
                      <a href="index.php?controller=sosialmedia" class="btn btn-secondary">Batal</a>
                    </div>
                  </form>
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