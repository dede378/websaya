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
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <title>PROFIL — CYBER</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Courier New', monospace; }
    :root {
      --hitam: #050508; --abu-gelap: #12121f; --abu-terang: #1e1e30;
      --neon-biru: #00f0ff; --neon-hijau: #00ff94; --neon-ungu: #b000ff;
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
    .wadah { padding: 2rem 1rem; text-align: center; }
    .kembali { color: var(--neon-biru); text-decoration: none; font-size: 0.9rem; display: inline-block; margin-bottom: 1.5rem; }

    .kotak {
      background: var(--abu-gelap); border: 1px solid var(--neon-ungu);
      border-radius: 12px; padding: 1.5rem; box-shadow: 0 0 12px rgba(176,0,255,0.15);
    }
    .salam {
      font-size: 1.5rem; font-weight: bold; margin-bottom: 0.8rem;
      color: var(--neon-hijau); text-shadow: 0 0 8px rgba(0,255,148,0.4);
    }
    .sub { color: var(--abu-teks); font-size: 0.9rem; margin-bottom: 1.2rem; }
    .tautan {
      background: var(--hitam); border: 1px solid var(--abu-terang);
      border-radius: 6px; padding: 0.8rem; margin-top: 0.6rem;
      font-family: monospace; font-size: 0.75rem; color: var(--neon-biru);
      word-break: break-all; line-height: 1.4;
    }
    .info { margin-top: 1.2rem; font-size: 0.8rem; color: var(--abu-teks); line-height: 1.5; }
  </style>
</head>
<body>

<nav>
  <a href="index.php" class="logo">◈ CYBER SECURITY</a>
  <div class="menu">
    <a href="index.php">HOME</a>
    <a href="buku_tamu.php">TAMU</a>
    <a href="pencarian.php">CARI</a>
    <a href="selamat-datang.php" class="aktif">PROFIL</a>
  </div>
</nav>

<div class="wadah">
  <a href="index.php" class="kembali">◀ Kembali</a>

  <div class="kotak">
    <div class="salam" id="salam">Memuat...</div>
    <p class="sub">Selamat datang di sistem.</p>

    <p style="font-size:0.85rem;color:var(--abu-teks);">Bagikan tautan ini:</p>
    <div class="tautan" id="tautan">sedang diproses...</div>

    <div class="info">
      Data diambil dari alamat URL secara langsung — tidak diverifikasi server.
    </div>
  </div>
</div>

<script>
// ⚠️ CELAH DOM-based XSS
const param = new URLSearchParams(window.location.search);
const nama = param.get('dari') || 'PENGGUNA';

// ❌ Rentan — pakai innerHTML
document.getElementById('salam').innerHTML = 'Halo, ' + nama + '!';

const dasar = window.location.origin + window.location.pathname;
document.getElementById('tautan').textContent = dasar + '?dari=' + encodeURIComponent(nama);
</script>

</body>
</html>

