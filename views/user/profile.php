<?php
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?controller=auth&action=login');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <?php 
    $title = 'Profil Pengguna - PPID Mandailing';
    include 'views/layout/head.php'; 
  ?>
</head>
<body>
  <div class="container-scroller">
    <?php 
      $controller = 'user';
      $action = 'profile';
      include 'views/layout/sidebar.php'; 
    ?>
    
    <div class="container-fluid page-body-wrapper">
      <?php include 'views/layout/header.php'; ?>
      
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="page-header">
            <h3 class="page-title">
              <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-account"></i>
              </span> Profil Pengguna
            </h3>
            <nav aria-label="breadcrumb">
              <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                  <span></span>Profil <i class="mdi mdi-account icon-sm text-primary align-middle"></i>
                </li>
              </ul>
            </nav>
          </div>
          
          <div class="row">
            <div class="col-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Informasi Pengguna</h4>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-sm-3 col-form-label">ID Pengguna</label>
                        <div class="col-sm-9">
                          <input type="text" class="form-control" value="<?php echo $_SESSION['user_id']; ?>" readonly />
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Username</label>
                        <div class="col-sm-9">
                          <input type="text" class="form-control" value="<?php echo $_SESSION['username']; ?>" readonly />
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Email</label>
                        <div class="col-sm-9">
                          <input type="email" class="form-control" value="<?php echo $_SESSION['email']; ?>" readonly />
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Role</label>
                        <div class="col-sm-9">
                          <input type="text" class="form-control" value="<?php echo $_SESSION['role']; ?>" readonly />
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Jabatan</label>
                        <div class="col-sm-9">
                          <input type="text" class="form-control" value="<?php echo $_SESSION['jabatan']; ?>" readonly />
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Tanggal Bergabung</label>
                        <div class="col-sm-9">
                          <input type="text" class="form-control" value="12 Mei 2023" readonly />
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="exampleInputPassword2" class="col-sm-3 col-form-label">Ganti Password</label>
                        <div class="input-group">
                          <input type="password" class="form-control" id="exampleInputPassword2" placeholder="Password baru">
                          <div class="input-group-append">
                            <button class="btn btn-sm btn-gradient-primary" type="button">Simpan</button>
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
    </div>
  </div>
</body>
</html>