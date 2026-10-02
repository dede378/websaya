<?php
session_start();
// WAJIB LOGIN DULU
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>CYBER SECURITY</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Courier New', monospace; }
    :root {
      --hitam: #050508; --abu-gelap: #12121f; --abu-terang: #1e1e30;
      --neon-biru: #00f0ff; --neon-ungu: #b000ff; --neon-pink: #ff00c8; --neon-hijau: #00ff94;
      --teks: #e0e0ff; --abu-teks: #9999cc;
    }
    html, body {
      background: var(--hitam); color: var(--teks); min-height: 100vh;
      background-image: 
        linear-gradient(rgba(0,240,255,0.03) 1px, transparent 1px),
        linear-gradient(90deg, rgba(0,240,255,0.03) 1px, transparent 1px);
      background-size: 30px 30px;
    }

    /* Nav HP */
    nav {
      background: rgba(18,18,31,0.95); border-bottom: 1px solid var(--neon-biru);
      padding: 0.8rem 1rem; position: sticky; top: 0; z-index: 999;
    }
    .logo {
      font-size: 1.1rem; font-weight: 700; color: var(--neon-biru);
      text-decoration: none; text-shadow: 0 0 6px var(--neon-biru);
      display: block; text-align: center; margin-bottom: 0.6rem;
    }
    .menu { display: flex; justify-content: space-around; flex-wrap: wrap; gap: 0.4rem; }
    .menu a {
      color: var(--abu-teks); text-decoration: none; font-size: 0.8rem;
      padding: 0.3rem 0.5rem; border-radius: 4px;
    }
    .menu a.aktif, .menu a:hover {
      color: var(--neon-hijau); text-shadow: 0 0 4px var(--neon-hijau);
      background: rgba(0,255,148,0.1);
    }

    /* Konten HP */
    .wadah { padding: 1.2rem; max-width: 100%; }
    .pembuka { text-align: center; margin-bottom: 2rem; }
    .pembuka h1 {
      font-size: 1.5rem; margin-bottom: 0.6rem;
      background: linear-gradient(90deg, var(--neon-biru), var(--neon-ungu));
      -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    }
    .pembuka p { color: var(--abu-teks); font-size: 0.9rem; line-height: 1.5; }

    .kartu {
      background: var(--abu-gelap); border: 1px solid var(--abu-terang);
      border-radius: 10px; padding: 1.2rem; margin-bottom: 1rem;
      text-decoration: none; color: inherit; display: block;
      transition: all 0.2s;
    }
    .kartu:active { border-color: var(--neon-ungu); transform: scale(0.98); }
    .kartu h3 { color: var(--neon-biru); margin-bottom: 0.4rem; font-size: 1rem; }
    .kartu p { color: var(--abu-teks); font-size: 0.85rem; margin-bottom: 0.6rem; line-height: 1.4; }
    .label {
      display: inline-block; font-size: 0.7rem; padding: 0.2rem 0.5rem;
      border-radius: 3px; background: rgba(0,255,148,0.15);
      color: var(--neon-hijau); border: 1px solid rgba(0,255,148,0.3);
    }

    .peringatan {
      background: rgba(255,0,196,0.08); border-left: 2px solid var(--neon-pink);
      padding: 0.9rem; border-radius: 0 6px 6px 0; margin-top: 1.5rem;
      color: #ff99dd; font-size: 0.85rem; line-height: 1.5;
    }
  </style>
</head>
<body>

<nav>
  <a href="index.php" class="logo">◈ CYBER SECURITY</a>
  <div class="menu">
    <a href="index.php" class="aktif">BERANDA</a>
    <a href="buku_tamu.php">TAMU</a>
    <a href="pencarian.php">CARI</a>
    <a href="selamat-datang.php">PROFIL</a>
    <a href="celah.php">CELAH</a>
    <a href="keluar.php" style="color:var(--neon-pink);">KELUAR</a>

  </div>
</nav>

<div class="wadah">
  <div class="pembuka">
    <h1>KEAMANAN SISTEM</h1>
    <p>Simulasi celah keamanan — belajar mengenali & mengamankan</p>
  </div>

  <a href="buku_tamu.php" class="kartu">
    <h3>◉ BUKU TAMU</h3>
    <p>Pesan tersimpan & ditampilkan ke semua pengunjung.</p>
    <span class="label">STORED XSS</span>
  </a>

  <a href="pencarian.php" class="kartu">
    <h3>◉ PENCARIAN</h3>
    <p>Kata kunci dipantulkan lewat URL — tidak tersimpan.</p>
    <span class="label">REFLECTED XSS</span>
  </a>

  <a href="selamat-datang.php" class="kartu">
    <h3>◉ PROFIL</h3>
    <p>Data diproses di peramban — server tidak tahu.</p>
    <span class="label">DOM-BASED XSS</span>
  </a>

  <div class="peringatan">
    ⚠️ Lingkungan pembelajaran. Tidak aman — jangan gunakan sungguhan.
  </div>
</div>

</body>
</html>
