<?php
session_start();

// Kalau sudah login → langsung ke beranda
if (isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit;
}

// Koneksi DB
$conn = mysqli_connect('localhost', 'root', 'saya123');
if (!$conn) die("Koneksi gagal: " . mysqli_connect_error());
mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS cyber_security");
mysqli_select_db($conn, 'cyber_security');

// Tabel percobaan masuk (untuk firewall)
mysqli_query($conn, "CREATE TABLE IF NOT EXISTS percobaan_masuk (
  id INT AUTO_INCREMENT PRIMARY KEY,
  ip VARCHAR(45) NOT NULL,
  waktu DATETIME NOT NULL,
  berhasil TINYINT(1) NOT NULL DEFAULT 0
)");

// Tabel pengguna
mysqli_query($conn, "CREATE TABLE IF NOT EXISTS pengguna (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  nama_lengkap VARCHAR(100) NOT NULL
)");

// Isi akun kalau belum ada
$cek = mysqli_query($conn, "SELECT * FROM pengguna WHERE username='admin'");
if (!$cek || mysqli_num_rows($cek) == 0) {
  mysqli_query($conn, "INSERT INTO pengguna (username,password,nama_lengkap) VALUES 
    ('admin', 'admin123', 'Administrator'),
    ('pengguna', 'rahasia', 'Pengguna Biasa')");
}

// === FIREWALL: BLOKIR TERLALU BANYAK PERCOBAAN ===
$ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
$batas_waktu = "NOW() - INTERVAL 5 MINUTE";
$cek_percobaan = mysqli_query($conn, "SELECT COUNT(*) AS jumlah FROM percobaan_masuk 
  WHERE ip='$ip' AND waktu > $batas_waktu");
$jumlah = mysqli_fetch_assoc($cek_percobaan)['jumlah'];

$terkunci = false;
if ($jumlah >= 5) {
  $terkunci = true;
  $pesan = "🔴 SISTEM TERKUNCI — Terlalu banyak percobaan. Coba lagi 5 menit.";
}

// === BUAT CSRF TOKEN ===
if (empty($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$pesan = $pesan ?? '';

// === PROSES MASUK ===
if (!$terkunci && $_SERVER['REQUEST_METHOD'] === 'POST') {
  // Cek CSRF Token
  $token_kirim = $_POST['csrf_token'] ?? '';
  if (empty($token_kirim) || $token_kirim !== $_SESSION['csrf_token']) {
    $pesan = "🔴 Token tidak sah — coba muat ulang halaman.";
  } else {
    $user = trim($_POST['username'] ?? '');
    $pass = trim($_POST['password'] ?? '');

    // Catat percobaan
    mysqli_query($conn, "INSERT INTO percobaan_masuk (ip,waktu,berhasil) VALUES 
      ('$ip', NOW(), 0)");

    // ⚠️ Versi Rentan (untuk latihan SQLi) — hapus komentar di bawah untuk pakai
    // $hasil = mysqli_query($conn, "SELECT * FROM pengguna WHERE username='$user' AND password='$pass'");
    
    // ✅ Versi AMAN (menggunakan Prepared Statement)
    $stmt = mysqli_prepare($conn, "SELECT id,username,nama_lengkap FROM pengguna WHERE username=? AND password=?");
    mysqli_stmt_bind_param($stmt, "ss", $user, $pass);
    mysqli_stmt_execute($stmt);
    $hasil = mysqli_stmt_get_result($stmt);

    if ($baris = mysqli_fetch_assoc($hasil)) {
      $_SESSION['user_id'] = $baris['id'];
      $_SESSION['username'] = $baris['username'];
      $_SESSION['nama'] = $baris['nama_lengkap'];
      // Tandai berhasil
      mysqli_query($conn, "UPDATE percobaan_masuk SET berhasil=1 WHERE ip='$ip' ORDER BY id DESC LIMIT 1");
      // Hapus token lama
      unset($_SESSION['csrf_token']);
      header("Location: index.php");
      exit;
    } else {
      $pesan = "❌ Nama pengguna atau sandi salah!";
    }
  }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <title>LOGIN — CYBER SECURITY</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Courier New', monospace; }
    :root {
      --hitam: #050508; --abu-gelap: #12121f; --abu-terang: #1e1e30;
      --neon-biru: #00f0ff; --neon-hijau: #00ff94; --neon-pink: #ff00c8;
      --neon-merah: #ff3355; --teks: #e0e0ff; --abu-teks: #9999cc;
    }
    html, body {
      background: var(--hitam); color: var(--teks); min-height: 100vh;
      display: flex; align-items: center; justify-content: center;
      background-image: linear-gradient(rgba(0,240,255,0.03) 1px, transparent 1px),
        linear-gradient(90deg, rgba(0,240,255,0.03) 1px, transparent 1px);
      background-size: 30px 30px;
    }
    .kotak {
      background: var(--abu-gelap); border: 1px solid var(--neon-biru);
      border-radius: 12px; padding: 2rem 1.5rem; width: 100%; max-width: 360px;
      box-shadow: 0 0 20px rgba(0,240,255,0.15);
    }
    .logo {
      font-size: 1.3rem; color: var(--neon-biru); text-align: center;
      text-shadow: 0 0 8px var(--neon-biru); margin-bottom: 0.3rem;
    }
    .sub { text-align: center; color: var(--abu-teks); font-size: 0.85rem; margin-bottom: 1.2rem; }
    .pesan {
      padding: 0.7rem; margin-bottom: 1rem; border-radius: 4px; font-size: 0.85rem;
      line-height: 1.4;
    }
    .pesan.merah { background: rgba(255,51,85,0.1); border-left: 2px solid var(--neon-merah); color: #ff99aa; }
    .pesan.biru { background: rgba(0,240,255,0.1); border-left: 2px solid var(--neon-biru); color: #99eeff; }
    label { display: block; margin-bottom: 0.4rem; color: var(--neon-hijau); font-size: 0.9rem; }
    input {
      width: 100%; padding: 0.9rem; background: var(--hitam);
      border: 1px solid var(--abu-terang); border-radius: 6px;
      color: var(--teks); font-size: 1rem; margin-bottom: 1rem;
    }
    input:focus { outline: none; border-color: var(--neon-biru); box-shadow: 0 0 6px rgba(0,240,255,0.4); }
    input:disabled { opacity: 0.5; cursor: not-allowed; }
    button {
      width: 100%; background: transparent; color: var(--neon-hijau);
      border: 1px solid var(--neon-hijau); border-radius: 6px;
      padding: 0.9rem; font-size: 1rem; cursor: pointer; transition: all 0.2s;
    }
    button:disabled { opacity: 0.4; cursor: not-allowed; }
    button:active { background: rgba(0,255,148,0.15); }
    .info { margin-top: 1rem; font-size: 0.7rem; color: var(--abu-teks); text-align: center; }
    .peringatan-simulasi {
      margin-top: 1rem; padding: 0.7rem; background: rgba(255,170,0,0.08);
      border-radius: 6px; font-size: 0.7rem; color: #ffdd88; line-height: 1.5;
    }
    kbd { background: rgba(0,0,0,0.4); padding: 0.15rem 0.4rem; border-radius: 3px; }
  </style>
</head>
<body>

<div class="kotak">
  <div class="logo">◈ CYBER SECURITY</div>
  <p class="sub">Sistem Autentikasi — Masuk untuk melanjutkan</p>

  <?php if ($pesan): ?>
  <div class="pesan <?= $terkunci ? 'merah' : 'biru' ?>"><?= $pesan ?></div>
  <?php endif; ?>

  <?php if (!$terkunci): ?>
  <form method="post">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
    
    <label>USERNAME</label>
    <input type="text" name="username" placeholder="Masukkan nama..." required>
    
    <label>PASSWORD</label>
    <input type="password" name="password" placeholder="Masukkan sandi..." required>
    
    <button type="submit">▶ MASUK SISTEM</button>
  </form>
  <?php else: ?>
  <button disabled>🔴 SISTEM TERKUNCI</button>
  <?php endif; ?>

  <div class="info">
    Percobaan: <?= $jumlah ?? 0 ?>/5 dalam 5 menit<br>
    CSRF Token: Aktif ✅
  </div>

  <div class="peringatan-simulasi">
    <strong>Catatan:</strong> Untuk latihan SQL Injection, ubah kode di berkas — ganti Prepared Statement dengan kueri langsung. Versi saat ini sudah AMAN dari SQLi.
  </div>
</div>

</body>
</html>
