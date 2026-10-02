<?php
session_start();

if (!isset($_SESSION['user_id']) || !$_SESSION['admin']) { 
  header("Location: login.php"); 
  exit; 
}

$conn = mysqli_connect('localhost', 'root', 'saya123', 'buku_tamu');
if (!$conn) die("Koneksi gagal: " . mysqli_connect_error());

if (isset($_GET['hapus'])) {
  $id = (int)$_GET['hapus'];
  mysqli_query($conn, "DELETE FROM pesan WHERE id=$id");
  header("Location: admin.php"); 
  exit;
}

$result = mysqli_query($conn, "SELECT * FROM pesan ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel Admin</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
    body { 
      background: linear-gradient(135deg, #0f172a, #1e293b); 
      min-height: 100vh; 
      color: #fff; 
      padding: 1rem; 
    }
    .container { max-width: 800px; margin: 0 auto; }
    .atas { 
      display: flex; 
      justify-content: space-between; 
      align-items: center; 
      margin-bottom: 1.5rem; 
      flex-wrap: wrap; 
      gap: 1rem;
    }
    .keluar { 
      background: #ef4444; 
      padding: 0.5rem 1.2rem; 
      border-radius: 8px; 
      color: white; 
      text-decoration: none; 
      font-weight: 600;
    }
    .pesan { 
      background: rgba(255,255,255,0.05); 
      padding: 1.2rem; 
      border-radius: 12px; 
      margin: 1rem 0; 
      border: 1px solid rgba(255,255,255,0.1); 
    }
    .hapus { 
      color: #f87171; 
      text-decoration: none; 
      font-size: 0.9rem; 
      margin-left: 0.5rem;
    }
    .nama { font-weight: bold; color: #38bdf8; }
    .tanggal { font-size: 0.8rem; color: #94a3b8; }
    h3 { margin-bottom: 1rem; }
  </style>
</head>
<body>
  <nav style="background:rgba(0,0,0,0.2); padding:1rem; margin-bottom:2rem; border-radius:12px; text-align:center; max-width:700px; margin-left:auto; margin-right:auto; display:flex; justify-content:center; gap:1rem; flex-wrap:wrap;">
    <a href="index.php" style="color:#38bdf8; text-decoration:none;">Beranda</a>
    <a href="buku_tamu.php" style="color:#38bdf8; text-decoration:none;">Buku Tamu</a>
    <?php if (isset($_SESSION['user_id'])): ?>
      <a href="profil.php" style="color:#38bdf8; text-decoration:none;">Profil</a>
      <a href="admin.php" style="color:#38bdf8; text-decoration:none; font-weight:bold;">Admin</a>
      <a href="login.php?keluar=1" style="color:#f87171; text-decoration:none;">Keluar</a>
    <?php else: ?>
      <a href="daftar.php" style="color:#38bdf8; text-decoration:none;">Daftar</a>
      <a href="login.php" style="color:#38bdf8; text-decoration:none;">Masuk</a>
    <?php endif; ?>
  </nav>

  <div class="container">
    <div class="atas">
      <h1>⚙️ Panel Admin</h1>
      <a href="login.php?keluar=1" class="keluar">Keluar</a>
    </div>

    <h3>Semua Pesan Buku Tamu</h3>
    <?php if (mysqli_num_rows($result) === 0): ?>
      <p style="text-align:center; color:#94a3b8; padding:2rem;">Belum ada pesan.</p>
    <?php else: ?>
      <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <div class="pesan">
          <span class="nama"><?= htmlspecialchars($row['nama']) ?></span>
          <span class="tanggal"> — <?= $row['tanggal'] ?></span>
          <a href="?hapus=<?= $row['id'] ?>" class="hapus" onclick="return confirm('Yakin hapus pesan ini?')">[Hapus]</a>
          <p style="margin-top:0.8rem; color:#e2e8f0;"><?= htmlspecialchars($row['pesan']) ?></p>
        </div>
      <?php endwhile; ?>
    <?php endif; ?>
  </div>
</body>
</html>
<?php mysqli_close($conn); ?>
