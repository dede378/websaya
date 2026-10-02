<?php
session_start();
if (!isset($_SESSION['user_id'])) { 
  header("Location: login.php"); 
  exit; 
}

$conn = mysqli_connect('localhost', 'root', 'saya123', 'buku_tamu');
if (!$conn) die("Koneksi gagal: " . mysqli_connect_error());

$user_id = $_SESSION['user_id'];
$pesan = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nama_lengkap = mysqli_real_escape_string($conn, trim($_POST['nama_lengkap']));
  $email = mysqli_real_escape_string($conn, trim($_POST['email']));

  if (!empty($_POST['password_baru'])) {
    if (strlen(trim($_POST['password_baru'])) < 6) {
      $pesan = '<p class="error">Sandi baru minimal 6 karakter!</p>';
    } else {
      $pass_baru = password_hash(trim($_POST['password_baru']), PASSWORD_DEFAULT);
      mysqli_query($conn, "UPDATE pengguna SET nama_lengkap='$nama_lengkap', email='$email', password='$pass_baru' WHERE id=$user_id");
      $pesan = '<p class="sukses">✅ Profil & sandi berhasil diperbarui!</p>';
    }
  } else {
    mysqli_query($conn, "UPDATE pengguna SET nama_lengkap='$nama_lengkap', email='$email' WHERE id=$user_id");
    $pesan = '<p class="sukses">✅ Profil berhasil diperbarui!</p>';
  }
}

$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM pengguna WHERE id=$user_id"));
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profil Saya</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
    body { 
      background: linear-gradient(135deg, #0f172a, #1e293b); 
      min-height: 100vh; 
      color: #fff; 
      padding: 1rem; 
    }
    .container { max-width: 500px; margin: 0 auto; }
    .card { 
      background: rgba(255,255,255,0.05); 
      padding: 2rem; 
      border-radius: 16px; 
      border: 1px solid rgba(255,255,255,0.1); 
    }
    h2 { text-align: center; margin-bottom: 1.5rem; }
    label { display: block; margin: 1rem 0 0.3rem; color: #cbd5e1; }
    input { 
      width: 100%; 
      padding: 0.8rem; 
      border-radius: 8px; 
      border: none; 
      background: rgba(255,255,255,0.1); 
      color: #fff; 
    }
    button { 
      width: 100%; 
      padding: 0.9rem; 
      background: linear-gradient(90deg, #3b82f6, #8b5cf6); 
      border: none; 
      border-radius: 8px; 
      color: white; 
      font-weight: bold; 
      margin-top: 1rem; 
      cursor: pointer; 
    }
    .sukses { 
      color: #10b981; 
      text-align: center; 
      margin: 1rem 0; 
      padding: 0.7rem; 
      background: rgba(16,185,129,0.1); 
      border-radius: 8px; 
    }
    .error { 
      color: #f87171; 
      text-align: center; 
      margin: 1rem 0; 
      padding: 0.7rem; 
      background: rgba(248,113,113,0.1); 
      border-radius: 8px; 
    }
    .link { text-align: center; margin-top: 1.5rem; }
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

  <div class="container">
    <div class="card">
      <h2>👤 Profil Saya</h2>
      <?php echo $pesan; ?>
      <form method="post">
        <label>Nama Lengkap</label>
        <input type="text" name="nama_lengkap" value="<?= htmlspecialchars($user['nama_lengkap']) ?>" required>

        <label>Nama Pengguna</label>
        <input type="text" value="<?= htmlspecialchars($user['username']) ?>" disabled style="opacity:0.5">

        <label>Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>">

        <label>Kata Sandi Baru (kosongkan jika tidak ingin mengubah)</label>
        <input type="password" name="password_baru" placeholder="Minimal 6 karakter">

        <button type="submit">Simpan Perubahan</button>
      </form>
      <div class="link">
        <a href="index.php">← Kembali ke Beranda</a>
      </div>
    </div>
  </div>
</body>
</html>
<?php mysqli_close($conn); ?>
