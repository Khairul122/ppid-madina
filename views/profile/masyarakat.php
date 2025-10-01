<?php
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?controller=auth&action=login');
    exit();
}
?>

<?php include('template/header.php'); ?>

<body class="with-welcome-text">
  <div class="container-scroller">
    <?php include 'views/layout/navbar_masyarakat.php'; ?>
    <div class="container-fluid page-body-wrapper">
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
                  <i class="mdi mdi-account-card-details me-2" style="font-size: 24px;"></i>
                  <span style="font-size: 18px; font-weight: 500;">User Profile</span>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6 grid-margin stretch-card">
                  <div class="card">
                    <div class="card-body">
                      <h4 class="card-title">Ubah Password</h4>
                      
                      <form method="POST" action="index.php?controller=user&action=changePassword">
                        <div class="form-group">
                          <label for="current_password">Password Saat Ini</label>
                          <input type="password" class="form-control" id="current_password" name="current_password" required>
                        </div>
                        <div class="form-group">
                          <label for="new_password">Password Baru</label>
                          <input type="password" class="form-control" id="new_password" name="new_password" required>
                        </div>
                        <div class="form-group">
                          <label for="confirm_password">Konfirmasi Password Baru</label>
                          <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Ubah Password</button>
                      </form>
                    </div>
                  </div>
                </div>
                
                <div class="col-md-6 grid-margin stretch-card">
                  <div class="card">
                    <div class="card-body">
                      <h4 class="card-title">Upload Foto Profil</h4>
                      
                      <form method="POST" action="index.php?controller=user&action=uploadProfilePhoto" enctype="multipart/form-data">
                        <div class="form-group">
                          <label for="profile_photo">Pilih Foto</label>
                          <input type="file" class="form-control" id="profile_photo" name="profile_photo" accept="image/*" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Upload Foto</button>
                      </form>
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

  <?php include 'template/script.php'; ?>
</body>

</html>