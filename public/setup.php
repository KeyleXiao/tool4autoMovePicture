<?php
session_start();
require __DIR__.'/lib.php';
$configFile = __DIR__ . '/config.php';
if (file_exists($configFile)) {
    try {
        $cfg = require $configFile;
        $pdo = new PDO("mysql:host={$cfg['db_host']};dbname={$cfg['db_name']};charset=utf8mb4", $cfg['db_user'], $cfg['db_pass']);
        if ($pdo->query('SELECT COUNT(*) FROM users')->fetchColumn() > 0) {
            header('Location: index.php');
            exit;
        }
    } catch (Exception $e) {
        // allow setup if connection fails
    }
}
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db_host = $_POST['db_host'];
    $db_user = $_POST['db_user'];
    $db_pass = $_POST['db_pass'];
    $db_name = $_POST['db_name'];
    $admin = $_POST['admin'];
    $admin_pass = md5($_POST['admin_pass']);
    try {
        $pdo = new PDO("mysql:host=$db_host", $db_user, $db_pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `$db_name` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $pdo->exec("USE `$db_name`");
        $pdo->exec("CREATE TABLE IF NOT EXISTS users(id INT AUTO_INCREMENT PRIMARY KEY, username VARCHAR(50) UNIQUE, password VARCHAR(32))");
        $pdo->exec("CREATE TABLE IF NOT EXISTS api_keys(id INT AUTO_INCREMENT PRIMARY KEY, api_key VARCHAR(255), remark VARCHAR(255), active TINYINT(1) DEFAULT 1, flag INT, weight INT DEFAULT 1)");
        $pdo->exec("CREATE TABLE IF NOT EXISTS one_keys(id INT AUTO_INCREMENT PRIMARY KEY, one_key CHAR(24) UNIQUE, remark VARCHAR(255), tokens_used INT DEFAULT 0, qps_limit INT DEFAULT 60, allowance FLOAT DEFAULT 60, last_check INT DEFAULT 0)");
        $pdo->exec("CREATE TABLE IF NOT EXISTS api_logs(id INT AUTO_INCREMENT PRIMARY KEY, success TINYINT(1), tokens INT DEFAULT 0, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP)");
        $stmt = $pdo->prepare('INSERT INTO users(username,password) VALUES(?,?)');
        $stmt->execute([$admin,$admin_pass]);
        $config = ['db_host'=>$db_host,'db_user'=>$db_user,'db_pass'=>$db_pass,'db_name'=>$db_name];
        if (file_put_contents($configFile, "<?php\nreturn " . var_export($config,true) . ";\n") === false) {
            throw new Exception('Failed to write configuration file');
        }
        log_action('Setup completed');
        header('Location: index.php');
        exit;
    } catch (Exception $e) {
        $error = $e->getMessage();
        log_action('Setup error: '.$error);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Setup</title>
    <style>
        body{font-family:Arial;display:flex;justify-content:center;align-items:center;height:100vh;background:#eef;}
        .setup{background:#fff;padding:20px;border-radius:4px;box-shadow:0 2px 4px rgba(0,0,0,.1);width:300px;}
        input{display:block;width:100%;padding:8px;margin-bottom:10px;}
    </style>
</head>
<body>
<div class="setup">
    <form method="post">
        <h2>Initial Setup</h2>
        <?php if($error) echo "<p style='color:red'>$error</p>"; ?>
        <input name="db_host" placeholder="DB Host" required>
        <input name="db_user" placeholder="DB User" required>
        <input name="db_pass" placeholder="DB Password">
        <input name="db_name" placeholder="DB Name" required>
        <input name="admin" placeholder="Admin Username" required>
        <input name="admin_pass" type="password" placeholder="Admin Password" required>
        <button type="submit">Create</button>
    </form>
</div>
</body>
</html>
