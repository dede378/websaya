<?php
$url = $_GET['url'] ?? '#';
if (isset($_GET['url'])) { header('Location: ' . $url); exit; }
?><!doctype html><html lang="id"><head><meta charset="utf-8"><title>Redirect Lab</title></head><body>
<h1>Open Redirect Lab</h1><p>Gunakan parameter <code>url</code> untuk menguji validasi redirect pada lab terisolasi.</p>
<a href="../pentest.php">Kembali</a></body></html>
