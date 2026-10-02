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
  <title>PANDUAN CELAH — CYBER SECURITY</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Courier New', monospace; }
    :root {
      --hitam: #050508; --abu-gelap: #12121f; --abu-terang: #1e1e30;
      --neon-biru: #00f0ff; --neon-hijau: #00ff94; --neon-pink: #ff00c8;
      --neon-ungu: #b000ff; --teks: #e0e0ff; --abu-teks: #9999cc;
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
    .menu { display: flex; justify-content: space-around; flex-wrap: wrap; gap: 0.3rem; }
    .menu a { color: var(--abu-teks); text-decoration: none; font-size: 0.75rem; padding: 0.3rem; }
    .menu a.aktif { color: var(--neon-hijau); text-shadow: 0 0 4px var(--neon-hijau); }
    .wadah { padding: 1rem; }
    .kembali { color: var(--neon-biru); text-decoration: none; font-size: 0.9rem; display: inline-block; margin-bottom: 1rem; }
    h1 { font-size: 1.3rem; color: var(--neon-hijau); margin-bottom: 1.2rem; text-align: center; }
    
    .bagian {
      background: var(--abu-gelap); border: 1px solid var(--abu-terang);
      border-radius: 10px; padding: 1.2rem; margin-bottom: 1rem;
    }
    .bagian h2 { font-size: 1rem; color: var(--neon-biru); margin-bottom: 0.7rem; }
    .bagian h3 { font-size: 0.9rem; color: var(--neon-pink); margin: 0.8rem 0 0.4rem; }
    .bagian p { font-size: 0.85rem; color: var(--abu-teks); line-height: 1.5; margin-bottom: 0.5rem; }
    .kode {
      background: var(--hitam); border: 1px solid var(--abu-terang);
      border-radius: 6px; padding: 0.8rem; margin: 0.5rem 0;
      font-size: 0.75rem; color: var(--neon-hijau); word-break: break-all;
    }
    .peringatan {
      background: rgba(255,0,196,0.08); border-left: 2px solid var(--neon-pink);
      padding: 0.8rem; border-radius: 0 6px 6px 0; margin: 1rem 0;
      color: #ff99dd; font-size: 0.8rem; line-height: 1.5;
    }
    .tautan {
      color: var(--neon-biru); text-decoration: none; display: inline-block;
      margin: 0.3rem 0; padding: 0.4rem 0.6rem;
      background: rgba(0,240,255,0.08); border-radius: 4px;
      font-size: 0.85rem;
    }
    .tautan:active { background: rgba(0,240,255,0.2); }
    ul { margin-left: 1rem; margin-bottom: 0.5rem; }
    li { font-size: 0.85rem; line-height: 1.5; margin-bottom: 0.3rem; color: var(--abu-teks); }
  </style>
</head>
<body>

<nav>
  <a href="index.php" class="logo">◈ CYBER SECURITY</a>
  <div class="menu">
    <a href="index.php">HOME</a>
    <a href="buku_tamu.php">TAMU</a>
    <a href="pencarian.php">CARI</a>
    <a href="celah.php" class="aktif">CELAH</a>
    <a href="selamat-datang.php">PROFIL</a>
  </div>
</nav>

<div class="wadah">
  <a href="index.php" class="kembali">◀ Kembali</a>
  <h1>◉ PANDUAN CELAH KEAMANAN</h1>

  <div class="bagian">
    <h2>🔴 XSS — Cross-Site Scripting</h2>
    <p>Menyisipkan kode skrip ke halaman yang dilihat pengguna lain.</p>

    <h3>1. Stored XSS (Tersimpan)</h3>
    <p>Kode tersimpan di basis data — muncul setiap kali halaman dibuka.</p>
    <div class="kode">Contoh: &lt;img src=x onerror="alert('Ditembus!')"&gt;</div>
    <a href="buku_tamu.php" class="tautan">→ Latihan: Buku Tamu</a>

    <h3>2. Reflected XSS (Dipantulkan)</h3>
    <p>Kode lewat URL — hanya aktif jika tautan dibuka.</p>
    <div class="kode">Contoh: pencarian.php?q=&lt;script&gt;alert(1)&lt;/script&gt;</div>
    <a href="pencarian.php?q=<script>alert('Tes!')</script>" class="tautan">→ Latihan: Pencarian</a>

    <h3>3. DOM-based XSS (Klien Saja)</h3>
    <p>Kode diproses langsung di peramban — server tidak melihat.</p>
    <div class="kode">Contoh: selamat-datang.php?dari=&lt;img src=x onerror=alert(1)&gt;</div>
    <a href="selamat-datang.php?dari=<b>Penembus</b>" class="tautan">→ Latihan: Profil</a>
  </div>

  <div class="bagian">
    <h2>🔴 SQL Injection</h2>
    <p>Menyisipkan perintah SQL ke input — membaca/mengubah basis data.</p>
    
    <h3>Contoh Dasar</h3>
    <div class="kode">Nama: ' OR '1'='1 -- -</div>
    <p>Dapat melewati pemeriksaan masuk tanpa kata sandi.</p>

    <h3>Contoh Lain</h3>
    <div class="kode">id=1 UNION SELECT 1,version(),database() -- -</div>
    <p>Menampilkan data tambahan dari sistem.</p>
  </div>

  <div class="bagian">
    <h2>🔴 Celah Lainnya</h2>
    <ul>
      <li><b>CSRF</b> — Memaksa pengguna melakukan aksi tanpa izin</li>
      <li><b>File Upload</b> — Mengunggah skrip berbahaya alih-alih gambar</li>
      <li><b>Path Traversal</b> — Membaca berkas di luar folder yang diizinkan (contoh: ../../etc/passwd)</li>
      <li><b>Broken Access Control</b> — Mengakses halaman orang lain lewat ubah nomor ID</li>
      <li><b>Command Injection</b> — Menjalankan perintah sistem lewat input (contoh: ; ls -la)</li>
      <li><b>Session Hijacking</b> — Memakai sesi pengguna lain yang dicuri</li>
      <li><b>Weak Password / Bruteforce</b> — Menebak kata sandi yang lemah</li>
    </ul>
  </div>

  <div class="bagian">
    <h2>💡 SARAN</h2>
    <ul>
      <li>✅ Buat halaman <b>Login</b> dengan SQL Injection — lewat masuk tanpa sandi</li>
      <li>✅ Tambah halaman <b>Unggah Berkas</b> — coba kirim .php bukan .jpg</li>
      <li>✅ Tambah halaman <b>Lihat Data Pengguna</b> — coba ubah ?id=1 jadi ?id=2</li>
      <li>✅ Buat <b>Papan Skor/Peringkat</b> — target manipulasi angka</li>
      <li>✅ Tambah halaman <b>Eksekusi Perintah</b> — coba masukkan ; whoami</li>
      <li>✅ Pasang <b>WAF Sederhana</b> — lalu pelajari cara melewatinya</li>
      <li>✅ Setiap halaman celah → tambah bagian <b>"Cara Mengamankan"</b> untuk belajar perbaikannya</li>
    </ul>
  </div>

  <div class="peringatan">
    ⚠️ HANYA UNTUK PEMBELAJARAN — Jangan gunakan pada sistem yang bukan milikmu.
  </div>
</div>

</body>
</html>
