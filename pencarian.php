<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}

session_start();
$kata = trim($_GET['q'] ?? '');
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <title>PENCARIAN — CYBER</title>
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
    h1 { font-size: 1.2rem; color: var(--neon-hijau); margin-bottom: 1rem; }

    .form-cari { display: flex; gap: 0.6rem; margin-bottom: 1.2rem; }
    .form-cari input {
      flex: 1; padding: 0.8rem; background: var(--abu-gelap);
      border: 1px solid var(--abu-terang); border-radius: 6px;
      color: var(--teks); font-size: 1rem;
    }
    .form-cari input:focus { border-color: var(--neon-biru); outline: none; }
    .form-cari button {
      background: transparent; color: var(--neon-biru); border: 1px solid var(--neon-biru);
      border-radius: 6px; padding: 0 1rem; cursor: pointer;
    }

    .hasil {
      background: var(--abu-gelap); border: 1px solid var(--abu-terang);
      border-radius: 10px; padding: 1.2rem;
    }
    .label-kata { color: var(--abu-teks); font-size: 0.8rem; margin-bottom: 0.4rem; }
    .kata-dicari { font-weight: bold; font-size: 1rem; color: var(--neon-pink); }
    .pesan { margin-top: 0.8rem; color: var(--abu-teks); font-size: 0.85rem; line-height: 1.4; }
    .contoh {
      margin-top: 1.2rem; padding: 0.9rem; background: rgba(176,0,255,0.05);
      border-left: 2px solid var(--neon-pink); border-radius: 0 6px 6px 0;
      font-size: 0.8rem; color: #cc99ff;
    }
  </style>
</head>
<body>

<nav>
  <a href="index.php" class="logo">◈ CYBER SECURITY</a>
  <div class="menu">
    <a href="index.php">HOME</a>
    <a href="buku_tamu.php">TAMU</a>
    <a href="pencarian.php" class="aktif">CARI</a>
    <a href="selamat-datang.php">PROFIL</a>
  </div>
</nav>

<div class="wadah">
  <a href="index.php" class="kembali">◀ Kembali</a>
  <h1>◉ PENCARIAN</h1>

  <form class="form-cari" method="get" action="pencarian.php">
    <input type="text" name="q" placeholder="Ketik kata..." value="<?= htmlspecialchars($kata) ?>">
    <button type="submit">▶</button>
  </form>

  <?php if ($kata): ?>
  <div class="hasil">
    <p class="label-kata">Dicari:</p>
    <!-- ⚠️ CELAH -->
    <p class="kata-dicari"><?= $kata ?></p>
    <p class="pesan">Tidak ditemukan hasil. Coba kata lain.</p>
  </div>
  <?php else: ?>
  <p style="color:var(--abu-teks);font-size:0.85rem;">Masukkan kata kunci di atas.</p>
  <?php endif; ?>

  <div class="contoh">
    <strong>Contoh:</strong> keamanan, enkripsi, akses, log
  </div>
</div>

</body>
</html>
