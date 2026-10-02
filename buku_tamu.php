<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}

// === PROSES FORMULIR ===
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // 🔒 CEK CSRF TOKEN
  if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die("🔴 Token tidak sah — akses ditolak!");
  }

  // lanjut proses simpan pesan...
  $nama = trim($_POST['nama'] ?? '');
  $pesan = trim($_POST['pesan'] ?? '');
  // ... sisanya tetap sama
}

session_start();
$conn = mysqli_connect('localhost', 'root', 'saya123', 'buku_tamu');
if (!$conn) die("Sistem tidak tersedia.");

mysqli_query($conn, "CREATE TABLE IF NOT EXISTS pesan_tamu (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(80) NOT NULL,
  pesan TEXT NOT NULL,
  dibuat_pada TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nama = mysqli_real_escape_string($conn, trim($_POST['nama'] ?? ''));
  $pesan = mysqli_real_escape_string($conn, trim($_POST['pesan'] ?? ''));
  if ($nama && $pesan) {
    mysqli_query($conn, "INSERT INTO pesan_tamu (nama, pesan) VALUES ('$nama', '$pesan')");
  }
  header("Location: buku_tamu.php");
  exit;
}

$hasil = mysqli_query($conn, "SELECT * FROM pesan_tamu ORDER BY id DESC LIMIT 10");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <title>BUKU TAMU — CYBER</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Courier New', monospace; }
    :root {
      --hitam: #050508; --abu-gelap: #12121f; --abu-terang: #1e1e30;
      --neon-biru: #00f0ff; --neon-hijau: #00ff94; --neon-pink: #ff00c8;
      --teks: #e0e0ff; --abu-teks: #9999cc;
    }
    html, body {
      background: var(--hitam); color: var(--teks); min-height: 100vh;
      background-image: linear-gradient(rgba(0,240,255,0.03) 1px, transparent 1px),
        linear-gradient(90deg, rgba(0,240,255,0.03) 1px, transparent 1px);
      background-size: 30px 30px;
    }
    nav {
      background: rgba(18,18,31,0.95); border-bottom: 1px solid var(--neon-biru);
      padding: 0.8rem 1rem; position: sticky; top: 0;
    }
    .logo {
      font-size: 1rem; color: var(--neon-biru); text-decoration: none;
      text-shadow: 0 0 6px var(--neon-biru); display: block; text-align: center; margin-bottom: 0.5rem;
    }
    .menu { display: flex; justify-content: space-around; }
    .menu a { color: var(--abu-teks); text-decoration: none; font-size: 0.75rem; padding: 0.3rem; }
    .menu a.aktif { color: var(--neon-hijau); text-shadow: 0 0 4px var(--neon-hijau); }
    .wadah { padding: 1rem; }
    .kembali { color: var(--neon-biru); text-decoration: none; font-size: 0.9rem; display: inline-block; margin-bottom: 1rem; }
    h1 { font-size: 1.2rem; color: var(--neon-hijau); margin-bottom: 0.3rem; }
    .sub { color: var(--abu-teks); font-size: 0.85rem; margin-bottom: 1.2rem; }

    .form-kotak {
      background: var(--abu-gelap); border: 1px solid var(--abu-terang);
      border-radius: 10px; padding: 1rem; margin-bottom: 1.5rem;
    }
    label { display: block; margin-bottom: 0.4rem; color: var(--neon-biru); font-size: 0.9rem; }
    input, textarea {
      width: 100%; padding: 0.8rem; background: var(--hitam);
      border: 1px solid var(--abu-terang); border-radius: 6px;
      color: var(--teks); font-size: 1rem; margin-bottom: 1rem;
    }
    input:focus, textarea:focus { border-color: var(--neon-biru); outline: none; box-shadow: 0 0 6px rgba(0,240,255,0.4); }
    button {
      width: 100%; background: transparent; color: var(--neon-hijau);
      border: 1px solid var(--neon-hijau); border-radius: 6px;
      padding: 0.8rem; font-size: 1rem; cursor: pointer;
    }
    button:active { background: rgba(0,255,148,0.15); }

    .item { border-bottom: 1px solid var(--abu-terang); padding: 1rem 0; }
    .item-nama { font-weight: bold; color: var(--neon-biru); font-size: 0.9rem; }
    .item-waktu { color: #666699; font-size: 0.7rem; margin-left: 0.5rem; }
    .item-isi { margin-top: 0.6rem; font-size: 0.9rem; line-height: 1.5; }
  </style>
</head>
<body>

<nav>
  <a href="index.php" class="logo">◈ CYBER SECURITY</a>
  <div class="menu">
    <a href="index.php">HOME</a>
    <a href="buku_tamu.php" class="aktif">TAMU</a>
    <a href="pencarian.php">CARI</a>
    <a href="selamat-datang.php">PROFIL</a>
  </div>
</nav>

<div class="wadah">
  <a href="index.php" class="kembali">◀ Kembali</a>
  <h1>◉ BUKU TAMU</h1>
  <p class="sub">Tinggalkan pesan untuk pengunjung lain.</p>

  <div class="form-kotak">
    <form method="post">
      <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
      <label>Nama</label>
      <input type="text" name="nama" placeholder="Masukkan nama..." required>
      <label>Pesan</label>
      <textarea name="pesan" rows="3" placeholder="Tulis pesan..." required></textarea>
      <button type="submit">▶ KIRIM</button>
    </form>
  </div>

  <?php while ($row = mysqli_fetch_assoc($hasil)): ?>
  <div class="item">
    <span class="item-nama"><?= htmlspecialchars($row['nama']) ?></span>
    <span class="item-waktu"><?= date('d/m H:i', strtotime($row['dibuat_pada'])) ?></span>
    <!-- ⚠️ CELAH -->
    <div class="item-isi"><?= $row['pesan'] ?></div>
  </div>
  <?php endwhile; ?>
</div>

</body>
</html>
