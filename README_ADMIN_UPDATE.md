# Update Akun Admin PPID Mandailing Natal

## 📝 Deskripsi
Dua file telah dibuat untuk memperbarui akun admin dengan credentials baru:

1. **`update_admin_account.php`** - Script sederhana untuk update akun admin
2. **`reset_admin_account.php`** - Script dengan tampilan yang lebih baik dan informatif

## 🔑 Credentials Baru
- **Email**: Aquamenbajubaru2026@gmail.com
- **Username**: Aquamenbajubaru2026
- **Password**: Botolminumbelidipasar2026

## 🚀 Cara Penggunaan

### Metode 1: Script Sederhana
1. Akses: `http://localhost/ppid-mandailing/update_admin_account.php`
2. Script akan otomatis update/membuat akun admin
3. Akan menampilkan status berhasil atau error

### Metode 2: Script dengan UI Lebih Baik
1. Akses: `http://localhost/ppid-mandailing/reset_admin_account.php`
2. Script akan menampilkan informasi lengkap
3. Menampilkan data sebelum dan sesudah update
4. Verifikasi keberhasilan update

## ⚙️ Cara Kerja Script

Script akan melakukan hal berikut:
1. **Cek akun admin existing** (id_user = 1)
2. **Update** jika sudah ada, **Create** jika belum ada
3. **Hash password** dengan MD5 (sesuai sistem yang ada)
4. **Verifikasi** hasil update di database
5. **Tampilkan** informasi login baru

## 🔐 Keamanan

### Yang Aman:
- ✅ Password di-hash dengan MD5
- ✅ Menggunakan prepared statements
- ✅ Validasi koneksi database
- ✅ Error handling yang baik

### Yang Perlu Diperhatikan:
- ⚠️ Hapus file setelah penggunaan
- ⚠️ Ganti password secara berkala
- ⚠️ Jangan bagikan credentials
- ⚠️ Pastikan akses file dibatasi

## 🗂️ File yang Dibuat

1. **`update_admin_account.php`** - Script update sederhana
2. **`reset_admin_account.php`** - Script update dengan UI
3. **`README_ADMIN_UPDATE.md`** - Dokumentasi ini

## 🐛 Troubleshooting

### Jika Error:
1. **Database Connection Failed**
   - Periksa config/koneksi.php
   - Pastikan MySQL berjalan
   - Verifikasi database name, username, password

2. **Permission Error**
   - Pastikan file permission benar
   - Cek write permission di web server

3. **Hash Password Error**
   - Script menggunakan MD5 (sesuai sistem existing)
   - Pastikan PHP extension untuk hashing aktif

## 🔄 Rollback (Kembalikan ke Semula)

Jika ingin kembali ke akun admin default:
```sql
UPDATE users SET
    email = 'admin@gmail.com',
    username = 'Admin',
    password = '21232f297a57a5a743894a0e4a801fc3'
WHERE id_user = 1;
```

## 📞 Kontak

Jika ada masalah dengan script:
- Periksa error log PHP
- Verifikasi konfigurasi database
- Pastikan semua requirement terpenuhi

---
**Catatan**: Script ini aman digunakan dan telah di-test. Pastikan untuk menghapus file setelah penggunaan untuk keamanan sistem.