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
                  <h4 class="card-title"><?php echo ($data['action'] === 'create') ? 'Tambah Banner' : 'Edit Banner'; ?></h4>
                  
                  <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger">
                      <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                    </div>
                  <?php endif; ?>

                  <form method="POST" enctype="multipart/form-data" action="index.php?controller=banner&action=<?php echo $data['action']; ?><?php if ($data['action'] === 'edit'): ?>&id=<?php echo $data['banner']['id_benner']; ?><?php endif; ?>">
                    <div class="form-group">
                      <label for="upload">Upload (Gambar atau Video)</label>
                      <?php if ($data['action'] === 'edit' && !empty($data['banner']['upload'])): ?>
                        <div class="mb-2">
                          <?php $ext = strtolower(pathinfo($data['banner']['upload'], PATHINFO_EXTENSION)); ?>
                          <?php if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])): ?>
                            <img src="<?php echo $data['banner']['upload']; ?>" alt="Current Banner" width="100">
                          <?php else: ?>
                            <video width="100" controls>
                              <source src="<?php echo $data['banner']['upload']; ?>" type="video/<?php echo $ext; ?>">
                              Video
                            </video>
                          <?php endif; ?>
                        </div>
                        <label>Ganti dengan file baru:</label>
                      <?php endif; ?>
                      <input type="file" class="form-control" id="upload" name="upload" <?php echo ($data['action'] === 'create') ? 'required' : ''; ?> accept="image/*,video/*">
                      <small class="form-text text-muted">
                        Format diperbolehkan: JPG, JPEG, PNG, GIF, MP4, AVI, MOV, WMV, WEBM. Maksimal 10MB.
                      </small>
                    </div>
                    
                    <div class="form-group">
                      <label for="judul">Judul</label>
                      <input type="text" class="form-control" id="judul" name="judul" value="<?php echo isset($data['banner']) ? htmlspecialchars($data['banner']['judul']) : ''; ?>" >
                    </div>
                    
                    <div class="form-group">
                      <label for="teks">Teks</label>
                      <textarea class="form-control" id="teks" name="teks" rows="3"><?php echo isset($data['banner']) ? htmlspecialchars($data['banner']['teks']) : ''; ?></textarea>
                    </div>
                    
                    <div class="form-group">
                      <label for="urutan">Urutan</label>
                      <input type="number" class="form-control" id="urutan" name="urutan" value="<?php echo isset($data['banner']) ? htmlspecialchars($data['banner']['urutan']) : '0'; ?>" min="0">
                    </div>
                    
                    <div class="form-group">
                      <button type="submit" class="btn btn-success"><?php echo ($data['action'] === 'create') ? 'Simpan' : 'Update'; ?></button>
                      <a href="index.php?controller=banner" class="btn btn-secondary">Batal</a>
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