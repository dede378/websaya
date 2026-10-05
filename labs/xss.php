<?php
$q = $_GET['q'] ?? '';
header('Content-Type: text/html; charset=UTF-8');
?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><title>XSS Lab</title></head><body>
<h1>Reflected XSS Lab</h1>
<p>Parameter <code>q</code> sengaja direfleksikan tanpa escaping sebagai target latihan.</p>
<p>Input: <?= $q ?></p>
<p>Source: <a href="../pentest.php">Pentest dashboard</a></p>
</body></html>
