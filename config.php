<?php
declare(strict_types=1);
session_start();
$host=getenv('DB_HOST') ?: 'db';
$name=getenv('DB_NAME') ?: 'cyber_security';
$user=getenv('DB_USER') ?: 'websaya';
$pass=getenv('DB_PASS') ?: 'websaya_lab';
$conn=mysqli_connect($host,$user,$pass,$name);
if(!$conn){http_response_code(500);die('Database unavailable.');}
mysqli_set_charset($conn,'utf8mb4');
if(empty($_SESSION['csrf_token'])) $_SESSION['csrf_token']=bin2hex(random_bytes(32));
function h(mixed $v): string { return htmlspecialchars((string)$v,ENT_QUOTES,'UTF-8'); }
function require_login(): void { if(empty($_SESSION['user_id'])){header('Location: login.php');exit;} }
function csrf_ok(): bool { return hash_equals($_SESSION['csrf_token'] ?? '',$_POST['csrf_token'] ?? ''); }
function require_csrf(): void { if(!csrf_ok()){http_response_code(403);exit('CSRF validation failed.');} }
function require_admin(): void { require_login(); if(empty($_SESSION['is_admin'])){http_response_code(403);exit('Admin only.');} }
