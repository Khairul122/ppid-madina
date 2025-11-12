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

    // Check if admin user exists (id_user = 1)
    $check_query = "SELECT id_user, email, username FROM users WHERE id_user = 1";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->execute();
    $existing_admin = $check_stmt->fetch(PDO::FETCH_ASSOC);

    if ($existing_admin) {
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
            echo "<h2>✅ Akun Admin Berhasil Diperbarui!</h2>";
            echo "<table border='1' cellpadding='10' style='border-collapse: collapse;'>";
            echo "<tr><td><strong>ID User</strong></td><td>" . $existing_admin['id_user'] . "</td></tr>";
            echo "<tr><td><strong>Email Lama</strong></td><td>" . htmlspecialchars($existing_admin['email']) . "</td></tr>";
            echo "<tr><td><strong>Username Lama</strong></td><td>" . htmlspecialchars($existing_admin['username']) . "</td></tr>";
            echo "<tr><td><strong>Email Baru</strong></td><td>" . htmlspecialchars($new_email) . "</td></tr>";
            echo "<tr><td><strong>Username Baru</strong></td><td>" . htmlspecialchars($new_username) . "</td></tr>";
            echo "<tr><td><strong>Password Baru</strong></td><td>" . htmlspecialchars($new_password) . "</td></tr>";
            echo "<tr><td><strong>Password (Hash)</strong></td><td>" . $hashed_password . "</td></tr>";
            echo "</table>";

            echo "<br><h3>📝 Detail Login Baru:</h3>";
            echo "<ul>";
            echo "<li><strong>Email:</strong> " . htmlspecialchars($new_email) . "</li>";
            echo "<li><strong>Username:</strong> " . htmlspecialchars($new_username) . "</li>";
            echo "<li><strong>Password:</strong> " . htmlspecialchars($new_password) . "</li>";
            echo "</ul>";

            echo "<br><p style='color: green;'><strong>Status:</strong> Akun admin berhasil diperbarui!</p>";

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
            echo "<h2>✅ Akun Admin Baru Berhasil Dibuat!</h2>";
            echo "<table border='1' cellpadding='10' style='border-collapse: collapse;'>";
            echo "<tr><td><strong>Email</strong></td><td>" . htmlspecialchars($new_email) . "</td></tr>";
            echo "<tr><td><strong>Username</strong></td><td>" . htmlspecialchars($new_username) . "</td></tr>";
            echo "<tr><td><strong>Password</strong></td><td>" . htmlspecialchars($new_password) . "</td></tr>";
            echo "<tr><td><strong>Jabatan</strong></td><td>Administrator</td></tr>";
            echo "<tr><td><strong>Role</strong></td><td>admin</td></tr>";
            echo "</table>";

            echo "<br><h3>📝 Detail Login:</h3>";
            echo "<ul>";
            echo "<li><strong>Email:</strong> " . htmlspecialchars($new_email) . "</li>";
            echo "<li><strong>Username:</strong> " . htmlspecialchars($new_username) . "</li>";
            echo "<li><strong>Password:</strong> " . htmlspecialchars($new_password) . "</li>";
            echo "</ul>";

            echo "<br><p style='color: green;'><strong>Status:</strong> Akun admin baru berhasil dibuat!</p>";

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
        echo "<br><div style='background-color: #d4edda; padding: 15px; border-radius: 5px;'>";
        echo "<h3>✅ Verifikasi Berhasil:</h3>";
        echo "<p>Akun dengan email <strong>" . htmlspecialchars($new_email) . "</strong> telah tersimpan di database.</p>";
        echo "<p>Silakan login menggunakan credentials di atas.</p>";
        echo "</div>";
    }

} catch (Exception $e) {
    echo "<div style='background-color: #f8d7da; padding: 15px; border-radius: 5px;'>";
    echo "<h2>❌ Error:</h2>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "</div>";
}

// Back to admin link
echo "<br><div style='margin-top: 20px; padding: 10px; background-color: #e9ecef; border-radius: 5px;'>";
echo "<a href='admin/login.php' style='text-decoration: none; padding: 10px 20px; background-color: #007bff; color: white; border-radius: 5px;'>← Kembali ke Halaman Login</a>";
echo "</div>";

echo "<br><hr>";
echo "<p><small>Script ini akan memperbarui/membuat akun admin dengan credentials baru.</small></p>";
echo "<p><small><strong>Catatan:</strong> Pastikan file config/koneksi.php sudah terhubung dengan benar ke database.</small></p>";
?>