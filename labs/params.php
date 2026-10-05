<?php
header('Content-Type: text/html; charset=UTF-8');
$known = ['debug','role','page','format','lang'];
echo '<!doctype html><html lang="id"><head><meta charset="utf-8"><title>Parameter Lab</title></head><body>';
echo '<h1>Parameter Discovery Lab</h1><p>Endpoint menerima beberapa parameter latihan.</p><ul>';
foreach ($known as $k) { $v = $_GET[$k] ?? '(tidak dikirim)'; echo '<li><code>'.htmlspecialchars($k).'</code> = '.htmlspecialchars($v, ENT_QUOTES, 'UTF-8').'</li>'; }
echo '</ul><a href="../pentest.php">Kembali</a></body></html>';
