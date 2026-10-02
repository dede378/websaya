<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = mysqli_connect('localhost', 'root', 'saya123', 'buku_tamu');
if (!$conn) die("Koneksi gagal: " . mysqli_connect_error());

$pesan = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nama_lengkap = trim($_POST['nama_lengkap']);
  $username = trim($_POST['username']);
  $password = trim($_POST['password']);
  $email = trim($_POST['email']);

  if (empty($nama_lengkap) || empty($username) || empty($password)) {
    $pesan = '<p class="error">Harap isi semua kolom wajib!</p>';
  } elseif (strlen($password) < 6) {
    $pesan = '<p class="error">Kata sandi minimal 6 karakter!</p>';
  } else {
    $nama_lengkap = mysqli_real_escape_string($conn, $nama_lengkap);
    $username = mysqli_real_escape_string($conn, $username);
    $email = mysqli_real_escape_string($conn, $email);
    $pass_hash = password_hash($password, PASSWORD_DEFAULT);

    $cek = mysqli_query($conn, "SELECT id FROM pengguna WHERE username='$username'");
    if (mysqli_num_rows($cek) > 0) {
      $pesan = '<p class="error">Nama pengguna sudah dipakai!</p>';
    } else {
      $sql = "INSERT INTO pengguna (nama_lengkap, username, password, email)
              VALUES ('$nama_lengkap', '$username', '$pass_hash', '$email')";
      if (mysqli_query($conn, $sql)) {
        $pesan = '<p class="sukses">✅ Pendaftaran berhasil! Silakan <a href="login.php">Masuk</a></p>';
      } else {
        $pesan = '<p class="error">Terjadi kesalahan: ' . mysqli_error($conn) . '</p>';
      }
    }
  }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar Akun Baru</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
    body { 
      background: linear-gradient(135deg, #0f172a, #1e293b); 
      min-height: 100vh; 
      color: #fff; 
      padding: 1rem; 
    }
    .card { 
      background: rgba(255,255,255,0.05); 
      padding: 2rem; 
      border-radius: 20px; 
      width: 100%; 
      max-width: 420px; 
      border: 1px solid rgba(255,255,255,0.1); 
    }
    h2 { text-align: center; color: #fff; margin-bottom: 1.5rem; font-size: 1.6rem; }
    .form-group { margin-bottom: 1rem; }
    label { display: block; color: #cbd5e1; margin-bottom: 0.4rem; font-size: 0.9rem; }
    input { width: 100%; padding: 0.85rem 1rem; border-radius: 10px; border: none; background: rgba(255,255,255,0.1); color: #fff; font-size: 1rem; }
    input:focus { outline: 2px solid #3b82f6; }
    button { width: 100%; padding: 0.9rem; background: linear-gradient(90deg, #3b82f6, #8b5cf6); border: none; border-radius: 10px; color: white; font-size: 1rem; font-weight: 600; cursor: pointer; margin-top: 0.5rem; }
    .error { color: #f87171; text-align: center; margin: 1rem 0; padding: 0.7rem; background: rgba(248,113,113,0.1); border-radius: 8px; }
    .sukses { color: #10b981; text-align: center; margin: 1rem 0; padding: 0.7rem; background: rgba(16,185,129,0.1); border-radius: 8px; }
    .link { text-align: center; margin-top: 1.5rem; color: #94a3b8; font-size: 0.9rem; }
    .link a { color: #38bdf8; text-decoration: none; }
  </style>
</head>
<body>
  <nav style="background:rgba(0,0,0,0.2); padding:1rem; margin-bottom:2rem; border-radius:12px; text-align:center; max-width:700px; margin-left:auto; margin-right:auto; display:flex; justify-content:center; gap:1rem; flex-wrap:wrap;">
    <a href="index.php" style="color:#38bdf8; text-decoration:none;">Beranda</a>
    <a href="buku_tamu.php" style="color:#38bdf8; text-decoration:none;">Buku Tamu</a>
    <?php if (isset($_SESSION['user_id'])): ?>
      <a href="profil.php" style="color:#38bdf8; text-decoration:none;">Profil</a>
      <a href="admin.php" style="color:#38bdf8; text-decoration:none;">Admin</a>
      <a href="login.php?keluar=1" style="color:#f87171; text-decoration:none;">Keluar</a>
    <?php else: ?>
      <a href="daftar.php" style="color:#38bdf8; text-decoration:none;">Daftar</a>
      <a href="login.php" style="color:#38bdf8; text-decoration:none;">Masuk</a>
    <?php endif; ?>
  </nav>

  <div style="display:flex; align-items:center; justify-content:center; min-height:calc(100vh - 140px);">
    <div class="card">
      <h2>📝 Daftar Akun Baru</h2>
      <?php echo $pesan; ?>
      <form method="post">
        <div class="form-group">
          <label>Nama Lengkap *</label>
          <input type="text" name="nama_lengkap" placeholder="Masukkan nama lengkap" required>
        </div>
        <div class="form-group">
          <label>Nama Pengguna *</label>
          <input type="text" name="username" placeholder="Buat nama pengguna" required>
        </div>
        <div class="form-group">
          <label>Email</label>
          <input type="email" name="email" placeholder="contoh@email.com">
        </div>
        <div class="form-group">
          <label>Kata Sandi *</label>
          <input type="password" name="password" placeholder="Minimal 6 karakter" required>
        </div>
        <button type="submit">Daftar Sekarang</button>
      </form>
      <div class="link">
        Sudah punya akun? <a href="login.php">Masuk di sini</a><br>
        <a href="index.php">← Kembali ke Beranda</a>
      </div>
    </div>
  </div>
</body>
</html>
<?php mysqli_close($conn); ?>
