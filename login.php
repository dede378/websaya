<?php
require __DIR__.'/config.php';
mysqli_query($conn,"CREATE TABLE IF NOT EXISTS pengguna (id INT AUTO_INCREMENT PRIMARY KEY, username VARCHAR(50) NOT NULL UNIQUE, password VARCHAR(255) NOT NULL, nama_lengkap VARCHAR(100) NOT NULL, is_admin TINYINT(1) NOT NULL DEFAULT 0)");
mysqli_query($conn,"CREATE TABLE IF NOT EXISTS percobaan_masuk (id INT AUTO_INCREMENT PRIMARY KEY, ip VARCHAR(45) NOT NULL, waktu DATETIME NOT NULL, berhasil TINYINT(1) NOT NULL DEFAULT 0)");
if(isset($_SESSION['user_id'])){header('Location:index.php');exit;}
$ip=$_SERVER['REMOTE_ADDR']??'127.0.0.1';
$s=mysqli_prepare($conn,"SELECT COUNT(*) FROM percobaan_masuk WHERE ip=? AND waktu>NOW()-INTERVAL 5 MINUTE AND berhasil=0");
mysqli_stmt_bind_param($s,'s',$ip);mysqli_stmt_execute($s);mysqli_stmt_bind_result($s,$attempts);mysqli_stmt_fetch($s);mysqli_stmt_close($s);
$locked=$attempts>=10;$message='';
if($_SERVER['REQUEST_METHOD']==='POST'&&!$locked){
 if(!csrf_ok())$message='Token CSRF tidak sah.';
 else{
  $username=trim($_POST['username']??'');$password=$_POST['password']??'';
  $s=mysqli_prepare($conn,"SELECT id,username,password,nama_lengkap,is_admin FROM pengguna WHERE username=? LIMIT 1");
  mysqli_stmt_bind_param($s,'s',$username);mysqli_stmt_execute($s);$row=mysqli_fetch_assoc(mysqli_stmt_get_result($s));mysqli_stmt_close($s);
  $ok=$row&&password_verify($password,$row['password']);
  $l=mysqli_prepare($conn,"INSERT INTO percobaan_masuk(ip,waktu,berhasil) VALUES(?,NOW(),?)");$flag=$ok?1:0;mysqli_stmt_bind_param($l,'si',$ip,$flag);mysqli_stmt_execute($l);mysqli_stmt_close($l);
  if($ok){session_regenerate_id(true);$_SESSION['user_id']=$row['id'];$_SESSION['username']=$row['username'];$_SESSION['nama']=$row['nama_lengkap'];$_SESSION['is_admin']=(bool)$row['is_admin'];header('Location:index.php');exit;}
  $message='Username atau password salah.';
 }
}
if($locked)$message='Terlalu banyak percobaan gagal. Tunggu 5 menit.';
?><!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width"><title>WebSaya Login</title></head><body><main><h1>🔐 WebSaya</h1><?php if($message):?><p><?=h($message)?></p><?php endif;?><form method="post"><input type="hidden" name="csrf_token" value="<?=h($_SESSION['csrf_token'])?>"><input name="username" placeholder="Username" required><input name="password" type="password" placeholder="Password" required><button <?= $locked?'disabled':'' ?>>Masuk</button></form><p><a href="daftar.php">Daftar akun</a></p></main></body></html>