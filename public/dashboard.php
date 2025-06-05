<?php
session_start();
$config = require __DIR__ . '/../config.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}
$pdo = new PDO("mysql:host={$config['db_host']};dbname={$config['db_name']};charset=utf8mb4", $config['db_user'], $config['db_pass']);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Dashboard</title>
<style>
body{font-family:Arial;margin:0;display:flex;height:100vh;}
.sidebar{width:200px;background:#333;color:#fff;padding:20px;}
.content{flex:1;padding:20px;}
a{color:#fff;display:block;margin-bottom:10px;text-decoration:none;}
</style>
</head>
<body>
<div class="sidebar">
    <h3>Menu</h3>
    <a href="api_keys.php">API Keys</a>
    <a href="one_keys.php">One Keys</a>
    <a href="test.php">Function Test</a>
    <a href="logout.php">Logout</a>
</div>
<div class="content">
    <h2>Welcome</h2>
    <p>Select a menu item.</p>
</div>
</body>
</html>
