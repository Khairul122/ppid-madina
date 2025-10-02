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
              <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="d-flex align-items-center">
                  <i class="mdi mdi-email-outline me-2" style="font-size: 24px;"></i>
                  <span style="font-size: 18px; font-weight: 500;">Pesan Masuk</span>
                </div>
              </div>

              <!-- Statistics Cards -->
              <div class="row mb-4">
                <div class="col-md-6">
                  <div class="card border-0 shadow-sm">
                    <div class="card-body">
                      <div class="d-flex align-items-center">
                        <div class="icon-wrapper bg-primary bg-opacity-10 p-3 rounded me-3">
                          <i class="mdi mdi-email-multiple text-primary" style="font-size: 28px;"></i>
                        </div>
                        <div>
                          <p class="text-muted mb-1 small">Total Pesan</p>
                          <h3 class="mb-0 fw-bold"><?php echo $totalCount ?? 0; ?></h3>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="card border-0 shadow-sm">
                    <div class="card-body">
                      <div class="d-flex align-items-center">
                        <div class="icon-wrapper bg-warning bg-opacity-10 p-3 rounded me-3">
                          <i class="mdi mdi-email-alert text-warning" style="font-size: 28px;"></i>
                        </div>
                        <div>
                          <p class="text-muted mb-1 small">Belum Dibaca</p>
                          <h3 class="mb-0 fw-bold"><?php echo $unreadCount ?? 0; ?></h3>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Pesan List -->
              <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                  <?php if (empty($pesanList)): ?>
                    <div class="text-center py-5">
                      <i class="mdi mdi-email-outline" style="font-size: 64px; color: #e0e0e0;"></i>
                      <p class="text-muted mt-3 mb-0">Belum ada pesan masuk.</p>
                    </div>
                  <?php else: ?>
                    <div class="table-responsive">
                      <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                          <tr>
                            <th class="border-0 px-4 py-3" style="width: 50px;">
                              <i class="mdi mdi-email"></i>
                            </th>
                            <th class="border-0 py-3">Pengirim</th>
                            <th class="border-0 py-3">Subjek</th>
                            <th class="border-0 py-3">Tanggal</th>
                            <th class="border-0 py-3 text-center" style="width: 200px;">Aksi</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php foreach ($pesanList as $pesan): ?>
                            <tr class="pesan-row <?php echo $pesan['is_read'] == 0 ? 'unread-message' : ''; ?>">
                              <td class="px-4">
                                <?php if ($pesan['is_read'] == 0): ?>
                                  <i class="mdi mdi-email text-primary" style="font-size: 20px;"></i>
                                <?php else: ?>
                                  <i class="mdi mdi-email-open text-muted" style="font-size: 20px;"></i>
                                <?php endif; ?>
                              </td>
                              <td>
                                <div>
                                  <strong class="d-block <?php echo $pesan['is_read'] == 0 ? 'text-dark' : 'text-muted'; ?>">
                                    <?php echo htmlspecialchars($pesan['nama']); ?>
                                  </strong>
                                  <small class="text-muted">
                                    <i class="mdi mdi-email-outline me-1"></i>
                                    <?php echo htmlspecialchars($pesan['email']); ?>
                                  </small>
                                  <?php if (!empty($pesan['no_telp'])): ?>
                                    <br>
                                    <small class="text-muted">
                                      <i class="mdi mdi-phone me-1"></i>
                                      <?php echo htmlspecialchars($pesan['no_telp']); ?>
                                    </small>
                                  <?php endif; ?>
                                </div>
                              </td>
                              <td>
                                <button
                                  type="button"
                                  class="btn btn-link text-start p-0 text-decoration-none <?php echo $pesan['is_read'] == 0 ? 'fw-bold text-dark' : 'text-muted'; ?>"
                                  data-bs-toggle="modal"
                                  data-bs-target="#viewModal<?php echo $pesan['id_pesan']; ?>">
                                  <?php echo htmlspecialchars($pesan['subjek']); ?>
                                </button>
                              </td>
                              <td>
                                <small class="text-muted">
                                  <?php echo date('d M Y, H:i', strtotime($pesan['created_at'])); ?>
                                </small>
                              </td>
                              <td class="text-center">
                                <div class="btn-group" role="group">
                                  <button
                                    type="button"
                                    class="btn btn-sm btn-outline-primary"
                                    data-bs-toggle="modal"
                                    data-bs-target="#viewModal<?php echo $pesan['id_pesan']; ?>"
                                    title="Lihat Pesan">
                                    <i class="mdi mdi-eye"></i>
                                  </button>
                                  <?php if ($pesan['is_read'] == 0): ?>
                                    <a
                                      href="index.php?controller=pesan_masuk&action=mark_read&id=<?php echo $pesan['id_pesan']; ?>"
                                      class="btn btn-sm btn-outline-success"
                                      title="Tandai Sudah Dibaca">
                                      <i class="mdi mdi-check"></i>
                                    </a>
                                  <?php else: ?>
                                    <a
                                      href="index.php?controller=pesan_masuk&action=mark_unread&id=<?php echo $pesan['id_pesan']; ?>"
                                      class="btn btn-sm btn-outline-warning"
                                      title="Tandai Belum Dibaca">
                                      <i class="mdi mdi-email-alert"></i>
                                    </a>
                                  <?php endif; ?>
                                  <button
                                    type="button"
                                    class="btn btn-sm btn-outline-danger"
                                    onclick="confirmDelete(<?php echo $pesan['id_pesan']; ?>, '<?php echo htmlspecialchars($pesan['nama']); ?>')"
                                    title="Hapus Pesan">
                                    <i class="mdi mdi-delete"></i>
                                  </button>
                                </div>
                              </td>
                            </tr>

                            <!-- View Modal -->
                            <div class="modal fade" id="viewModal<?php echo $pesan['id_pesan']; ?>" tabindex="-1" aria-hidden="true">
                              <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content border-0 shadow-lg">
                                  <div class="modal-header border-0 pb-0">
                                    <div>
                                      <h5 class="modal-title fw-bold mb-1"><?php echo htmlspecialchars($pesan['subjek']); ?></h5>
                                      <div class="text-muted small">
                                        <i class="mdi mdi-clock-outline me-1"></i>
                                        <?php echo date('d F Y, H:i', strtotime($pesan['created_at'])); ?>
                                      </div>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                  </div>
                                  <div class="modal-body px-4 py-3">
                                    <!-- Sender Info -->
                                    <div class="card bg-light border-0 mb-3">
                                      <div class="card-body p-3">
                                        <div class="row">
                                          <div class="col-md-6 mb-2">
                                            <small class="text-muted d-block mb-1">
                                              <i class="mdi mdi-account me-1"></i>Nama
                                            </small>
                                            <strong><?php echo htmlspecialchars($pesan['nama']); ?></strong>
                                          </div>
                                          <div class="col-md-6 mb-2">
                                            <small class="text-muted d-block mb-1">
                                              <i class="mdi mdi-email me-1"></i>Email
                                            </small>
                                            <strong><?php echo htmlspecialchars($pesan['email']); ?></strong>
                                          </div>
                                          <?php if (!empty($pesan['no_telp'])): ?>
                                            <div class="col-md-6">
                                              <small class="text-muted d-block mb-1">
                                                <i class="mdi mdi-phone me-1"></i>No. Telepon
                                              </small>
                                              <strong><?php echo htmlspecialchars($pesan['no_telp']); ?></strong>
                                            </div>
                                          <?php endif; ?>
                                        </div>
                                      </div>
                                    </div>

                                    <!-- Message Content -->
                                    <div>
                                      <h6 class="fw-semibold mb-2">Pesan:</h6>
                                      <div class="message-content p-3 bg-light rounded">
                                        <?php echo nl2br(htmlspecialchars($pesan['pesan'])); ?>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="modal-footer border-0 pt-0">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                  </div>
                                </div>
                              </div>
                            </div>
                          <?php endforeach; ?>
                        </tbody>
                      </table>
                    </div>
                  <?php endif; ?>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Delete Confirmation Form (Hidden) -->
  <form id="deleteForm" method="POST" action="index.php?controller=pesan_masuk&action=delete" style="display: none;">
    <input type="hidden" name="id_pesan" id="deleteId">
  </form>

  <style>
    /* Unread Message Styling */
    .unread-message {
      background-color: #f8f9ff;
    }

    .unread-message:hover {
      background-color: #f0f2ff !important;
    }

    /* Icon Wrapper */
    .icon-wrapper {
      width: 60px;
      height: 60px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    /* Table Styling */
    .table tbody tr {
      transition: all 0.2s ease;
    }

    .table tbody tr:hover {
      background-color: #f8f9fa;
    }

    /* Button Group */
    .btn-group .btn {
      border-radius: 4px !important;
      margin: 0 2px;
    }

    /* Message Content */
    .message-content {
      max-height: 300px;
      overflow-y: auto;
      line-height: 1.6;
      white-space: pre-wrap;
      word-wrap: break-word;
    }

    /* Modal Styling */
    .modal-content {
      border-radius: 12px;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .btn-group .btn {
        padding: 0.25rem 0.5rem;
        font-size: 12px;
      }

      .table {
        font-size: 14px;
      }
    }
  </style>

  <?php include 'template/script.php'; ?>

  <script>
    // Function to confirm delete
    function confirmDelete(id, nama) {
      if (confirm('Apakah Anda yakin ingin menghapus pesan dari "' + nama + '"?\nData yang dihapus tidak dapat dikembalikan.')) {
        document.getElementById('deleteId').value = id;
        document.getElementById('deleteForm').submit();
      }
    }
  </script>
</body>

</html>
