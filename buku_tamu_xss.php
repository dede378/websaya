<?php
session_start();
$conn = mysqli_connect('localhost', 'root', 'saya123', 'buku_tamu');
if (!$conn) {
  die("Koneksi gagal: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['nama']) && !empty($_POST['pesan'])) {
  $nama = $_POST['nama'];
  $pesan = $_POST['pesan'];
  
  // ✅ LINDUNGI SAAT SIMPAN — agar SQL tidak rusak
  $nama = mysqli_real_escape_string($conn, $nama);
  $pesan = mysqli_real_escape_string($conn, $pesan);
  
  $sql = "INSERT INTO pesan (nama, pesan) VALUES ('$nama', '$pesan')";
  mysqli_query($conn, $sql);
  
  header("Location: buku_tamu_xss.php");
  exit;
}

$result = mysqli_query($conn, "SELECT * FROM pesan ORDER BY id DESC LIMIT 20");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Latihan XSS — Rentan</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; font-family: sans-serif; }
    body { background: #1a1a2e; color: #fff; padding: 2rem; }
    .container { max-width: 700px; margin: 0 auto; }
    .peringatan { background: rgba(255,0,0,0.2); border: 1px solid #f00; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; text-align:center; }
    .card { background: rgba(255,255,255,0.05); padding: 1.5rem; border-radius: 12px; margin: 1rem 0; }
    input, textarea { width: 100%; padding: 0.8rem; margin: 0.5rem 0; border-radius: 8px; border: none; background: rgba(255,255,255,0.1); color: #fff; }
    button { padding: 0.8rem 1.5rem; background: #e74c3c; border: none; border-radius: 8px; color: white; font-weight: bold; cursor: pointer; }
    .pesan { border-bottom: 1px solid rgba(255,255,255,0.1); padding: 1rem 0; }
    .nama { font-weight: bold; color: #f39c12; }
  </style>
</head>
<body>
  <div class="container">
    <div class="peringatan">
      ⚠️ HALAMAN INI SENGAJA DIBUAT RENTAN — UNTUK LATIHAN SAJA!
    </div>

    <h1>📖 Buku Tamu — Latihan XSS</h1>
    <p style="margin-bottom:1rem;"><a href="index.php" style="color:#38bdf8;">← Kembali ke Beranda</a></p>

    <div class="card">
      <h3>Tinggalkan Pesan</h3>
      <form method="post">
        <input type="text" name="nama" placeholder="Nama Anda" required>
        <textarea name="pesan" rows="3" placeholder="Pesan Anda..." required></textarea>
        <button type="submit">Kirim Pesan</button>
      </form>
    </div>

    <div class="card">
      <h3>Daftar Pesan</h3>
      <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <div class="pesan">
          <span class="nama"><?= htmlspecialchars($row['nama']) ?></span>
          <!-- ⚠️ CELAH XSS DI SINI — TANPA htmlspecialchars() -->
          <p style="margin-top:0.5rem;"><?= $row['pesan'] ?></p>
        </div>
      <?php endwhile; ?>
    </div>
  </div>
</body>
</html>
<?php mysqli_close($conn); ?>
