<?php
// Memastikan file ini diakses melalui index.php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

// Include header template
include('template/header.php');
?>

<body class="with-welcome-text">
  <div class="container-scroller">
    <?php include 'template/navbar.php'; ?>
    <div class="container-fluid page-body-wrapper">
      <?php include 'template/setting_panel.php'; ?>
      <?php include 'template/sidebar.php'; ?>
      <div class="main-panel">
        <div class="content-wrapper">
          <!-- Page Title -->
          <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <h4 class="font-weight-bold mb-0"><?php echo htmlspecialchars($title) ?></h4>
                </div>
                <div>
                  <button type="button" class="btn btn-primary btn-icon-text btn-rounded">
                    <i class="ti-file btn-icon-prepend"></i>Total: <?php echo $total_pemohon ?> Pemohon
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Data Tabel -->
          <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Daftar Data Pemohon</h4>
                  <p class="card-description">Tabel data pemohon permohonan informasi publik</p>
                  
                  <!-- Tabel Data Pemohon -->
                  <div class="table-responsive">
                    <table class="table table-striped table-hover">
                      <thead>
                        <tr>
                          <th>No</th>
                          <th>Nama</th>
                          <th>Alamat</th>
                          <th>Kota</th>
                          <th>Email</th>
                          <th>No Telp</th>
                          <th>Aksi</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if (!empty($pemohon_list)): ?>
                          <?php $no = 1; ?>
                          <?php foreach ($pemohon_list as $pemohon): ?>
                            <tr>
                              <td><?php echo $no++ ?></td>
                              <td><?php echo htmlspecialchars($pemohon['nama_lengkap']) ?></td>
                              <td><?php echo htmlspecialchars($pemohon['alamat']) ?></td>
                              <td><?php echo htmlspecialchars($pemohon['city']) ?></td>
                              <td><?php echo htmlspecialchars($pemohon['email']) ?></td>
                              <td><?php echo htmlspecialchars($pemohon['no_kontak']) ?></td>
                              <td>
                                <div class="btn-group" role="group">
                                  <a href="index.php?controller=permohonan_admin&action=detail_pemohon&id=<?php echo $pemohon['id_biodata'] ?>" 
                                     class="btn btn-outline-info btn-sm" 
                                     title="Detail">
                                    <i class="fas fa-eye"></i>
                                  </a>
                                  <a href="#" 
                                     class="btn btn-outline-warning btn-sm" 
                                     title="Edit" 
                                     onclick="confirm('Fitur edit akan segera hadir.')">
                                    <i class="fas fa-edit"></i>
                                  </a>
                                </div>
                              </td>
                            </tr>
                          <?php endforeach; ?>
                        <?php else: ?>
                          <tr>
                            <td colspan="7" class="text-center">
                              <div class="py-5">
                                <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                                <h4 class="text-muted">Belum Ada Data Pemohon</h4>
                                <p class="text-muted">Data pemohon akan ditampilkan di sini</p>
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
        
        <!-- Footer -->
        <?php include('template/footer.php') ?>
      </div>
    </div>
  </div>
  
  <!-- Scripts -->
  <?php include('template/script.php') ?>
</body>
</html>