<?php
$id = $_GET['id'] ?? '1';
header('Content-Type: text/html; charset=UTF-8');
?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><title>SQLi Lab</title></head><body>
<h1>SQL Injection Lab</h1>
<p>Parameter <code>id</code> adalah target latihan SQL injection. Endpoint ini sengaja tidak menjalankan query sistem/OS.</p>
<p>Nilai id yang diterima: <strong><?= htmlspecialchars($id, ENT_QUOTES, 'UTF-8') ?></strong></p>
<p>Untuk latihan SQLmap, gunakan endpoint ini sebagai target parameter discovery; implementasikan backend SQL yang terisolasi sebelum menguji payload SQLi aktif.</p>
<p><a href="../pentest.php">Kembali ke dashboard</a></p>
</body></html>
