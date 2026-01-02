<?php
include 'config/koneksi.php';

// 1. Setup Data Admin
$username_admin = "admin";
$email_admin    = "admin@ourcoffee.com";
$pass_admin     = password_hash("admin123", PASSWORD_DEFAULT); // Password: admin123
$nama_admin     = "Administrator";
$role_admin     = "admin";

// 2. Setup Data Kasir
$username_kasir = "kasir";
$email_kasir    = "kasir@ourcoffee.com";
$pass_kasir     = password_hash("kasir123", PASSWORD_DEFAULT); // Password: kasir123
$nama_kasir     = "Kasir Livia";
$role_kasir     = "kasir";

// Hapus user lama jika ada biar bersih (Opsional)
mysqli_query($koneksi, "DELETE FROM users WHERE username IN ('admin', 'kasir')");

// Insert Admin Baru
$query_admin = "INSERT INTO users (full_name, username, email, password, role) 
                VALUES ('$nama_admin', '$username_admin', '$email_admin', '$pass_admin', '$role_admin')";

// Insert Kasir Baru
$query_kasir = "INSERT INTO users (full_name, username, email, password, role) 
                VALUES ('$nama_kasir', '$username_kasir', '$email_kasir', '$pass_kasir', '$role_kasir')";

echo "<h3>Proses Pembuatan Akun:</h3>";

if (mysqli_query($koneksi, $query_admin)) {
  echo "✅ Akun ADMIN berhasil dibuat!<br>";
  echo "Username: <b>admin</b> / Email: <b>admin@ourcoffee.com</b><br>";
  echo "Password: <b>admin123</b><br><br>";
} else {
  echo "❌ Gagal buat Admin: " . mysqli_error($koneksi) . "<br>";
}

if (mysqli_query($koneksi, $query_kasir)) {
  echo "✅ Akun KASIR berhasil dibuat!<br>";
  echo "Username: <b>kasir</b> / Email: <b>kasir@ourcoffee.com</b><br>";
  echo "Password: <b>kasir123</b><br>";
} else {
  echo "❌ Gagal buat Kasir: " . mysqli_error($koneksi) . "<br>";
}
