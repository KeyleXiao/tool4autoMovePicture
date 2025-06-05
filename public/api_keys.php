<?php
session_start();
require __DIR__.'/lib.php';
if (!isset($_SESSION['user_id'])) { header('Location: index.php'); exit; }
$config = require __DIR__ . '/config.php';
$pdo = new PDO("mysql:host={$config['db_host']};dbname={$config['db_name']};charset=utf8mb4", $config['db_user'], $config['db_pass']);

if (isset($_POST['add'])) {
    $stmt = $pdo->prepare('INSERT INTO api_keys(api_key,remark,active,flag) VALUES(?,?,?,?)');
    $stmt->execute([$_POST['api_key'], $_POST['remark'], isset($_POST['active'])?1:0, $_POST['flag']]);
    log_action('Add API key');
}
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare('DELETE FROM api_keys WHERE id=?');
    $stmt->execute([$_GET['delete']]);
    log_action('Delete API key '.$_GET['delete']);
}
$keys = $pdo->query('SELECT * FROM api_keys')->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>API Keys</title>
<link rel="stylesheet" href="style.css">
<script src="script.js" defer></script>
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
<h2>API Keys</h2>
<form method="post" onsubmit="return validateForm()">
    <input name="api_key" placeholder="API Key" required>
    <input name="remark" placeholder="Remark">
    <input name="flag" placeholder="Platform Flag" value="1">
    <label><input type="checkbox" name="active" checked>Active</label>
    <button name="add" type="submit">Add</button>
</form>
<table border="1" cellpadding="5" cellspacing="0">
<tr><th>ID</th><th>Key</th><th>Remark</th><th>Active</th><th>Flag</th><th>Action</th></tr>
<?php foreach($keys as $k): ?>
<tr>
<td><?= $k['id'] ?></td>
<td><?= htmlspecialchars($k['api_key']) ?></td>
<td><?= htmlspecialchars($k['remark']) ?></td>
<td><?= $k['active'] ?></td>
<td><?= $k['flag'] ?></td>
<td><a href="?delete=<?= $k['id'] ?>">Delete</a></td>
</tr>
<?php endforeach; ?>
</table>
</div>
<div id="loading" class="loading-overlay"><div>Loading...</div></div>
<div class="toast-container"></div>
</body>
</html>
