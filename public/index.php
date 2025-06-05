<?php
session_start();

$configFile = __DIR__ . '/../config.php';
if (!file_exists($configFile)) {
    header('Location: setup.php');
    exit;
}

$config = require $configFile;
$pdo = new PDO("mysql:host={$config['db_host']};dbname={$config['db_name']};charset=utf8mb4", $config['db_user'], $config['db_pass']);

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = md5($_POST['password'] ?? '');
    $stmt = $pdo->prepare('SELECT id FROM users WHERE username=? AND password=?');
    $stmt->execute([$username, $password]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Invalid credentials';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Login</title>
    <style>
        body{font-family: Arial; display:flex;justify-content:center;align-items:center;height:100vh;background:#f5f5f5;}
        .login{background:#fff;padding:20px;border-radius:4px;box-shadow:0 2px 4px rgba(0,0,0,.1);}        
        input{display:block;margin-bottom:10px;width:100%;padding:8px;}
    </style>
</head>
<body>
<div class="login">
    <form method="post">
        <h2>Login</h2>
        <?php if($error) echo "<p style='color:red'>$error</p>"; ?>
        <input name="username" placeholder="Username" required>
        <input name="password" type="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
</div>
</body>
</html>
