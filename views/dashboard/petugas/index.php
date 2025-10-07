<?php
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?controller=auth&action=login');
    exit();
}

$title = 'Dashboard Petugas - PPID Mandailing';
?>

<?php include('template/header.php'); ?>

<body class="with-welcome-text">
  <div class="container-scroller">
    <?php include 'template/navbar.php'; ?>
    <div class="container-fluid page-body-wrapper">
      <?php include 'template/setting_panel.php'; ?>
      <?php include 'template/sidebar_petugas.php'; ?>
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="page-header">
            <h3 class="page-title">
              <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-home"></i>
              </span> Dashboard Petugas
            </h3>
            <nav aria-label="breadcrumb">
              <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                  <span></span>Overview <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
                </li>
              </ul>
            </nav>
          </div>
          
          <div class="row">
            <div class="col-md-3 stretch-card grid-margin">
              <div class="card bg-gradient-danger card-img-holder text-white">
                <div class="card-body">
                  <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCA1MTIgNTEyIj48cGF0aCBmaWxsPSIjZmZmIiBvcGFjaXR5PSIwLjEiIGQ9Ik0yNTYgMEMxMTQuNiAwIDAgMTE0LjYgMCAyNTZzMTE0LjYgMjU2IDI1NiAyNTYgMjU2LTExNC42IDI1Ni0yNTZTMzk3LjQgMCAyNTYgMHptMCA0NzYuOGMtMTIyLjEgMC0yMjEuOC05OS43LTIyMS44LTIyMS44UzEzMy45IDM0LjIgMjU2IDM0LjJzMjIxLjggOTkuNyAyMjEuOCAyMjEuOC05OS43IDIyMS44LTIyMS44IDIyMS44eiIvPjwvc3ZnPg==" class="card-img-absolute" alt="circle-image" />
                  <h4 class="font-weight-normal mb-3">Permohonan Baru <i class="mdi mdi-file-document mdi-24px float-right"></i></h4>
                  <h2 class="mb-5">12</h2>
                  <h6 class="card-text">Menunggu diproses</h6>
                </div>
              </div>
            </div>
            <div class="col-md-3 stretch-card grid-margin">
              <div class="card bg-gradient-info card-img-holder text-white">
                <div class="card-body">
                  <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCA1MTIgNTEyIj48cGF0aCBmaWxsPSIjZmZmIiBvcGFjaXR5PSIwLjEiIGQ9Ik0yNTYgMEMxMTQuNiAwIDAgMTE0LjYgMCAyNTZzMTE0LjYgMjU2IDI1NiAyNTYgMjU2LTExNC42IDI1Ni0yNTZTMzk3LjQgMCAyNTYgMHptMCA0NzYuOGMtMTIyLjEgMC0yMjEuOC05OS43LTIyMS44LTIyMS44UzEzMy45IDM0LjIgMjU2IDM0LjJzMjIxLjggOTkuNyAyMjEuOCAyMjEuOC05OS43IDIyMS44LTIyMS44IDIyMS44eiIvPjwvc3ZnPg==" class="card-img-absolute" alt="circle-image" />
                  <h4 class="font-weight-normal mb-3">Diproses <i class="mdi mdi-progress-clock mdi-24px float-right"></i></h4>
                  <h2 class="mb-5">8</h2>
                  <h6 class="card-text">Sedang dalam proses</h6>
                </div>
              </div>
            </div>
            <div class="col-md-3 stretch-card grid-margin">
              <div class="card bg-gradient-success card-img-holder text-white">
                <div class="card-body">
                  <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCA1MTIgNTEyIj48cGF0aCBmaWxsPSIjZmZmIiBvcGFjaXR5PSIwLjEiIGQ9Ik0yNTYgMEMxMTQuNiAwIDAgMTE0LjYgMCAyNTZzMTE0LjYgMjU2IDI1NiAyNTYgMjU2LTExNC42IDI1Ni0yNTZTMzk3LjQgMCAyNTYgMHptMCA0NzYuOGMtMTIyLjEgMC0yMjEuOC05OS43LTIyMS44LTIyMS44UzEzMy45IDM0LjIgMjU2IDM0LjJzMjIxLjggOTkuNyAyMjEuOCAyMjEuOC05OS43IDIyMS44LTIyMS44IDIyMS44eiIvPjwvc3ZnPg==" class="card-img-absolute" alt="circle-image" />
                  <h4 class="font-weight-normal mb-3">Selesai <i class="mdi mdi-check-circle mdi-24px float-right"></i></h4>
                  <h2 class="mb-5">42</h2>
                  <h6 class="card-text">Permohonan selesai</h6>
                </div>
              </div>
            </div>
            <div class="col-md-3 stretch-card grid-margin">
              <div class="card bg-gradient-warning card-img-holder text-white">
                <div class="card-body">
                  <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCA1MTIgNTEyIj48cGF0aCBmaWxsPSIjZmZmIiBvcGFjaXR5PSIwLjEiIGQ9Ik0yNTYgMEMxMTQuNiAwIDAgMTE0LjYgMCAyNTZzMTE0LjYgMjU2IDI1NiAyNTYgMjU2LTExNC42IDI1Ni0yNTZTMzk3LjQgMCAyNTYgMHptMCA0NzYuOGMtMTIyLjEgMC0yMjEuOC05OS43LTIyMS44LTIyMS44UzEzMy45IDM0LjIgMjU2IDM0LjJzMjIxLjggOTkuNyAyMjEuOCAyMjEuOC05OS43IDIyMS44LTIyMS44IDIyMS44eiIvPjwvc3ZnPg==" class="card-img-absolute" alt="circle-image" />
                  <h4 class="font-weight-normal mb-3">Ditolak <i class="mdi mdi-close-circle mdi-24px float-right"></i></h4>
                  <h2 class="mb-5">3</h2>
                  <h6 class="card-text">Permohonan ditolak</h6>
                </div>
              </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-12 grid-margin">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Permohonan Terbaru</h4>
                  <div class="table-responsive">
                    <table class="table">
                      <thead>
                        <tr>
                          <th> Pemohon </th>
                          <th> Permohonan </th>
                          <th> Tanggal </th>
                          <th> Status </th>
                          <th> Aksi </th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>
                            <i class="mdi mdi-account-circle" style="font-size: 24px; margin-right: 10px;"></i> Budi Santoso
                          </td>
                          <td> Permohonan #12345 </td>
                          <td> 12 Mei 2023 </td>
                          <td>
                            <label class="badge badge-gradient-info">Baru</label>
                          </td>
                          <td>
                            <button class="btn btn-gradient-primary btn-sm">
                              <i class="mdi mdi-pencil"></i> Edit
                            </button>
                            <button class="btn btn-gradient-danger btn-sm">
                              <i class="mdi mdi-delete"></i> Hapus
                            </button>
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <i class="mdi mdi-account-circle" style="font-size: 24px; margin-right: 10px;"></i> Ani Wijaya
                          </td>
                          <td> Permohonan #12344 </td>
                          <td> 11 Mei 2023 </td>
                          <td>
                            <label class="badge badge-gradient-success">Diproses</label>
                          </td>
                          <td>
                            <button class="btn btn-gradient-primary btn-sm">
                              <i class="mdi mdi-pencil"></i> Edit
                            </button>
                            <button class="btn btn-gradient-danger btn-sm">
                              <i class="mdi mdi-delete"></i> Hapus
                            </button>
                          </td>
                        </tr>
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
</body>

</html>