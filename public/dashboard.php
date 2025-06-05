<?php
session_start();
$config = require __DIR__ . '/config.php';
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
<link rel="stylesheet" href="style.css">
<script src="script.js" defer></script>
<style>
.sidebar a{color:#fff;display:block;margin-bottom:10px;text-decoration:none;}
.layout{display:flex;height:100vh;}
@media(max-width:600px){.sidebar{width:100%;}}
</style>
</head>
<body>
<div class="nav">
    <div class="nav-links">
        <a href="dashboard.php">Dashboard</a>
        <a href="api_keys.php">API Keys</a>
        <a href="one_keys.php">One Keys</a>
        <a href="test.php">Function Test</a>
        <a href="stats.php">Stats</a>
        <a href="logout.php">Logout</a>
    </div>
    <button class="nav-toggle" onclick="toggleNav()">☰</button>
    <button id="theme-toggle" class="nav-toggle" onclick="toggleTheme()">🌗</button>
</div>
<div class="content">
    <h2>Welcome</h2>
    <p>Select a menu item.</p>
</div>
<div id="loading" class="loading-overlay"><div>Loading...</div></div>
<div class="toast-container"></div>
</body>
</html>
