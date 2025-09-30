<?php
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?controller=auth&action=login');
    exit();
}

$title = 'Dashboard Admin - PPID Mandailing';
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
          <div class="page-header">
            <h3 class="page-title">
              <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-home"></i>
              </span> Dashboard Admin
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
            <div class="col-md-4 stretch-card grid-margin">
              <div class="card bg-gradient-danger card-img-holder text-white">
                <div class="card-body">
                  <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCA1MTIgNTEyIj48cGF0aCBmaWxsPSIjZmZmIiBvcGFjaXR5PSIwLjEiIGQ9Ik0yNTYgMEMxMTQuNiAwIDAgMTE0LjYgMCAyNTZzMTE0LjYgMjU2IDI1NiAyNTYgMjU2LTExNC42IDI1Ni0yNTZTMzk3LjQgMCAyNTYgMHptMCA0NzYuOGMtMTIyLjEgMC0yMjEuOC05OS43LTIyMS44LTIyMS44UzEzMy45IDM0LjIgMjU2IDM0LjJzMjIxLjggOTkuNyAyMjEuOCAyMjEuOC05OS43IDIyMS44LTIyMS44IDIyMS44eiIvPjwvc3ZnPg==" class="card-img-absolute" alt="circle-image" />
                  <h4 class="font-weight-normal mb-3">Total Pengguna <i class="mdi mdi-account-group mdi-24px float-right"></i></h4>
                  <h2 class="mb-5">1,242</h2>
                  <h6 class="card-text">Increased by 60%</h6>
                </div>
              </div>
            </div>
            <div class="col-md-4 stretch-card grid-margin">
              <div class="card bg-gradient-info card-img-holder text-white">
                <div class="card-body">
                  <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCA1MTIgNTEyIj48cGF0aCBmaWxsPSIjZmZmIiBvcGFjaXR5PSIwLjEiIGQ9Ik0yNTYgMEMxMTQuNiAwIDAgMTE0LjYgMCAyNTZzMTE0LjYgMjU2IDI1NiAyNTYgMjU2LTExNC42IDI1Ni0yNTZTMzk3LjQgMCAyNTYgMHptMCA0NzYuOGMtMTIyLjEgMC0yMjEuOC05OS43LTIyMS44LTIyMS44UzEzMy45IDM0LjIgMjU2IDM0LjJzMjIxLjggOTkuNyAyMjEuOCAyMjEuOC05OS43IDIyMS44LTIyMS44IDIyMS44eiIvPjwvc3ZnPg==" class="card-img-absolute" alt="circle-image" />
                  <h4 class="font-weight-normal mb-3">Permohonan <i class="mdi mdi-file-document mdi-24px float-right"></i></h4>
                  <h2 class="mb-5">328</h2>
                  <h6 class="card-text">Decreased by 10%</h6>
                </div>
              </div>
            </div>
            <div class="col-md-4 stretch-card grid-margin">
              <div class="card bg-gradient-success card-img-holder text-white">
                <div class="card-body">
                  <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCA1MTIgNTEyIj48cGF0aCBmaWxsPSIjZmZmIiBvcGFjaXR5PSIwLjEiIGQ9Ik0yNTYgMEMxMTQuNiAwIDAgMTE0LjYgMCAyNTZzMTE0LjYgMjU2IDI1NiAyNTYgMjU2LTExNC42IDI1Ni0yNTZTMzk3LjQgMCAyNTYgMHptMCA0NzYuOGMtMTIyLjEgMC0yMjEuOC05OS43LTIyMS44LTIyMS44UzEzMy45IDM0LjIgMjU2IDM0LjJzMjIxLjggOTkuNyAyMjEuOCAyMjEuOC05OS43IDIyMS44LTIyMS44IDIyMS44eiIvPjwvc3ZnPg==" class="card-img-absolute" alt="circle-image" />
                  <h4 class="font-weight-normal mb-3">Keberatan <i class="mdi mdi-alert-circle mdi-24px float-right"></i></h4>
                  <h2 class="mb-5">24</h2>
                  <h6 class="card-text">Increased by 5%</h6>
                </div>
              </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-12 grid-margin">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Aktivitas Terbaru</h4>
                  <div class="table-responsive">
                    <table class="table">
                      <thead>
                        <tr>
                          <th> Pengguna </th>
                          <th> Aktivitas </th>
                          <th> Waktu </th>
                          <th> Status </th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>
                            <i class="mdi mdi-account-circle" style="font-size: 24px; margin-right: 10px;"></i> Budi Santoso
                          </td>
                          <td> Mendaftar sebagai pengguna baru </td>
                          <td>
                            <label class="badge badge-gradient-success">2 menit lalu</label>
                          </td>
                          <td> Berhasil </td>
                        </tr>
                        <tr>
                          <td>
                            <i class="mdi mdi-account-circle" style="font-size: 24px; margin-right: 10px;"></i> Ani Wijaya
                          </td>
                          <td> Mengajukan permohonan informasi </td>
                          <td>
                            <label class="badge badge-gradient-warning">15 menit lalu</label>
                          </td>
                          <td> Diproses </td>
                        </tr>
                        <tr>
                          <td>
                            <i class="mdi mdi-account-circle" style="font-size: 24px; margin-right: 10px;"></i> John Doe
                          </td>
                          <td> Mengajukan keberatan informasi </td>
                          <td>
                            <label class="badge badge-gradient-info">1 jam lalu</label>
                          </td>
                          <td> Diproses </td>
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