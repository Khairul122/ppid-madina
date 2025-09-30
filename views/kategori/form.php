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
                        <h4 class="card-title">
                            <?= isset($data['kategori']['id_kategori']) ? 'Edit Kategori' : 'Tambah Kategori' ?>
                        </h4>
                        
                        <form method="POST" 
                              action="index.php?controller=kategori&action=<?= isset($data['kategori']['id_kategori']) ? 'update&id=' . $data['kategori']['id_kategori'] : 'store' ?>">
                            <div class="mb-3">
                                <label for="nama_kategori" class="form-label">Nama Kategori *</label>
                                <input type="text" 
                                       class="form-control <?= isset($data['errors']['nama_kategori']) ? 'is-invalid' : '' ?>" 
                                       id="nama_kategori" 
                                       name="nama_kategori" 
                                       value="<?= htmlspecialchars($data['kategori']['nama_kategori'] ?? '') ?>" 
                                       required>
                                <?php if (isset($data['errors']['nama_kategori'])): ?>
                                    <div class="invalid-feedback">
                                        <?= $data['errors']['nama_kategori'] ?>
                                    </div>
                                <?php endif; ?>
                                <div class="form-text">Nama kategori harus unik dan minimal 3 karakter</div>
                            </div>
                            
                            <div class="d-flex justify-content-between">
                                <a href="index.php?controller=kategori&action=index" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Kembali
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>
                                    <?= isset($data['kategori']['id_kategori']) ? 'Update' : 'Simpan' ?>
                                </button>
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