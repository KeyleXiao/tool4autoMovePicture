<?php
session_start();

require __DIR__.'/lib.php';

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
        log_action("Login success for {$username}");
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Invalid credentials';
        log_action("Login failed for {$username}");
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js" defer></script>
    <style>.login{background:#fff;padding:20px;border-radius:4px;box-shadow:0 2px 4px rgba(0,0,0,.1);}</style>
</head>
<body>
<div class="login">
    <form method="post" onsubmit="return validateForm()">
        <h2>Login</h2>
        <?php if($error) echo "<p style='color:red'>$error</p>"; ?>
        <input name="username" placeholder="Username" required>
        <input name="password" type="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
</div>
<div id="loading" class="loading-overlay"><div>Loading...</div></div>
<div class="toast-container"></div>
</body>
</html>
