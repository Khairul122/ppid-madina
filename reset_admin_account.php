<?php
require_once 'config/koneksi.php';

try {
    // Connect to database
    $database = new Database();
    $conn = $database->getConnection();

    if (!$conn) {
        throw new Exception("Database connection failed");
    }

    // New admin credentials
    $new_email = 'Aquamenbajubaru2026@gmail.com';
    $new_username = 'Aquamenbajubaru2026';
    $new_password = 'Botolminumbelidipasar2026';

    // Hash password (sesuai sistem yang ada menggunakan MD5)
    $hashed_password = md5($new_password);

    echo "<!DOCTYPE html>
    <html>
    <head>
        <title>Update Akun Admin - PPID Mandailing Natal</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 20px; background-color: #f5f5f5; }
            .container { max-width: 600px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
            .header { text-align: center; margin-bottom: 30px; }
            .success { background-color: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin: 20px 0; }
            .info { background-color: #d1ecf1; color: #0c5460; padding: 15px; border-radius: 5px; margin: 20px 0; }
            table { width: 100%; border-collapse: collapse; margin: 20px 0; }
            th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
            th { background-color: #f8f9fa; }
            .btn { display: inline-block; padding: 12px 24px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px; margin: 10px 5px; }
            .btn:hover { background-color: #0056b3; }
            .btn-danger { background-color: #dc3545; }
            .btn-danger:hover { background-color: #c82333; }
        </style>
    </head>
    <body>";

    echo "<div class='container'>
          <div class='header'>
            <h1>🔐 Update Akun Admin PPID</h1>
            <p>Kabupaten Mandailing Natal</p>
          </div>";

    // Check if admin user exists (id_user = 1)
    $check_query = "SELECT id_user, email, username, jabatan, role FROM users WHERE id_user = 1";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->execute();
    $existing_admin = $check_stmt->fetch(PDO::FETCH_ASSOC);

    if ($existing_admin) {
        echo "<div class='info'>
              <h3>📋 Informasi Akun Admin Saat Ini:</h3>
              <table>
                <tr><th>ID User</th><td>" . $existing_admin['id_user'] . "</td></tr>
                <tr><th>Email</th><td>" . htmlspecialchars($existing_admin['email']) . "</td></tr>
                <tr><th>Username</th><td>" . htmlspecialchars($existing_admin['username']) . "</td></tr>
                <tr><th>Jabatan</th><td>" . htmlspecialchars($existing_admin['jabatan']) . "</td></tr>
                <tr><th>Role</th><td>" . htmlspecialchars($existing_admin['role']) . "</td></tr>
              </table>
              </div>";

        // Update existing admin account
        $update_query = "UPDATE users SET
                        email = :email,
                        username = :username,
                        password = :password
                        WHERE id_user = 1";

        $stmt = $conn->prepare($update_query);
        $stmt->bindParam(':email', $new_email);
        $stmt->bindParam(':username', $new_username);
        $stmt->bindParam(':password', $hashed_password);

        if ($stmt->execute()) {
            echo "<div class='success'>
                  <h3>✅ Akun Admin Berhasil Diperbarui!</h3>
                  </div>";

            echo "<h3>🔑 Login Credentials Baru:</h3>";
            echo "<table>
                <tr><th>Email</th><td>" . htmlspecialchars($new_email) . "</td></tr>
                <tr><th>Username</th><td>" . htmlspecialchars($new_username) . "</td></tr>
                <tr><th>Password</th><td><code>" . htmlspecialchars($new_password) . "</code></td></tr>
                </table>";

            echo "<div class='info'>
                  <p><strong>⚠️ Catatan Penting:</strong></p>
                  <ul>
                    <li>Simpan informasi login ini dengan aman</li>
                    <li>Ganti password secara berkala untuk keamanan</li>
                    <li>Jangan berikan credentials kepada pihak tidak berwenang</li>
                  </ul>
                  </div>";

        } else {
            throw new Exception("Gagal memperbarui akun admin");
        }

    } else {
        // Create new admin account if doesn't exist
        $insert_query = "INSERT INTO users (email, username, password, jabatan, role)
                        VALUES (:email, :username, :password, 'Administrator', 'admin')";

        $stmt = $conn->prepare($insert_query);
        $stmt->bindParam(':email', $new_email);
        $stmt->bindParam(':username', $new_username);
        $stmt->bindParam(':password', $hashed_password);

        if ($stmt->execute()) {
            echo "<div class='success'>
                  <h3>✅ Akun Admin Baru Berhasil Dibuat!</h3>
                  </div>";

            echo "<h3>🔑 Login Credentials:</h3>";
            echo "<table>
                <tr><th>Email</th><td>" . htmlspecialchars($new_email) . "</td></tr>
                <tr><th>Username</th><td>" . htmlspecialchars($new_username) . "</td></tr>
                <tr><th>Password</th><td><code>" . htmlspecialchars($new_password) . "</code></td></tr>
                <tr><th>Jabatan</th><td>Administrator</td></tr>
                <tr><th>Role</th><td>admin</td></tr>
                </table>";

            echo "<div class='info'>
                  <p><strong>⚠️ Catatan Penting:</strong></p>
                  <ul>
                    <li>Simpan informasi login ini dengan aman</li>
                    <li>Ganti password secara berkala untuk keamanan</li>
                    <li>Jangan berikan credentials kepada pihak tidak berwenang</li>
                  </ul>
                  </div>";

        } else {
            throw new Exception("Gagal membuat akun admin baru");
        }
    }

    // Verify the update/insert
    $verify_query = "SELECT id_user, email, username, role FROM users WHERE email = :email";
    $verify_stmt = $conn->prepare($verify_query);
    $verify_stmt->bindParam(':email', $new_email);
    $verify_stmt->execute();
    $final_result = $verify_stmt->fetch(PDO::FETCH_ASSOC);

    if ($final_result) {
        echo "<div class='success'>
              <h3>🔍 Verifikasi Database Berhasil!</h3>
              <p>Akun dengan email <strong>" . htmlspecialchars($new_email) . "</strong> telah tersimpan di database.</p>
              </div>";
    }

    // Action buttons
    echo "<div style='text-align: center; margin-top: 30px;'>";
    echo "<a href='admin/login.php' class='btn'>🚪 Login ke Admin Panel</a>";
    echo "<a href='javascript:void(0)' onclick='window.close()' class='btn btn-danger'>❌ Tutup Window</a>";
    echo "</div>";

    echo "<hr>";
    echo "<div style='margin-top: 20px; font-size: 12px; color: #666;'>";
    echo "<p><strong>Informasi Script:</strong></p>";
    echo "<ul>";
    echo "<li>Script ini akan memperbarui/membuat akun admin dengan credentials baru</li>";
    echo "<li>Password di-hash menggunakan MD5 sesuai sistem yang ada</li>";
    echo "<li>Pastikan file config/koneksi.php sudah terhubung dengan benar ke database</li>";
    echo "<li>Hapus file ini setelah penggunaan untuk keamanan</li>";
    echo "</ul>";
    echo "</div>";

    echo "</div></body></html>";

} catch (Exception $e) {
    echo "<div style='background-color: #f8d7da; padding: 15px; border-radius: 5px; max-width: 600px; margin: 20px auto;'>";
    echo "<h2>❌ Error:</h2>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "<hr>";
    echo "<p><strong>Troubleshooting:</strong></p>";
    echo "<ul>";
    echo "<li>Pastikan file config/koneksi.php ada dan konfigurasinya benar</li>";
    echo "<li>Pastikan database MySQL berjalan dan accessible</li>";
    echo "<li>Periksa koneksi ke database</li>";
    echo "</ul>";
    echo "</div>";
}
?>