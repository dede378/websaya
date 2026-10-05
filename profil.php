<?php
require __DIR__.'/config.php';
require_login();
$user_id=(int)$_SESSION['user_id'];$message='';
$s=mysqli_prepare($conn,"SELECT username,nama_lengkap FROM pengguna WHERE id=?");mysqli_stmt_bind_param($s,'i',$user_id);mysqli_stmt_execute($s);$user=mysqli_fetch_assoc(mysqli_stmt_get_result($s));mysqli_stmt_close($s);
if($_SERVER['REQUEST_METHOD']==='POST'){require_csrf();$nama=trim($_POST['nama_lengkap']??'');
 if($nama==='')$message='Nama tidak boleh kosong.';
 else{$s=mysqli_prepare($conn,"UPDATE pengguna SET nama_lengkap=? WHERE id=?");mysqli_stmt_bind_param($s,'si',$nama,$user_id);mysqli_stmt_execute($s);mysqli_stmt_close($s);$_SESSION['nama']=$nama;$message='Profil berhasil diperbarui.';}
}
?><!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width"><title>Profil</title></head><body><main><h1>👤 Profil</h1><p><?=h($message)?></p><form method="post"><input type="hidden" name="csrf_token" value="<?=h($_SESSION['csrf_token'])?>"><label>Username</label><input value="<?=h($user['username'])?>" disabled><label>Nama</label><input name="nama_lengkap" value="<?=h($user['nama_lengkap'])?>" required><button>Simpan</button></form><p><a href="index.php">Beranda</a> · <a href="pentest.php">Pentest Lab</a> · <a href="keluar.php">Keluar</a></p></main></body></html>