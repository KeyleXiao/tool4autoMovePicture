<?php
session_start();
if (!isset($_SESSION['user_id'])) { header('Location: index.php'); exit; }
$config = require __DIR__ . '/../config.php';
$pdo = new PDO("mysql:host={$config['db_host']};dbname={$config['db_name']};charset=utf8mb4", $config['db_user'], $config['db_pass']);

function random_key($len=24){ return substr(str_shuffle(str_repeat('0123456789abcdef', $len)),0,$len); }

if (isset($_POST['add'])) {
    $key = random_key();
    $stmt = $pdo->prepare('INSERT INTO one_keys(one_key,remark) VALUES(?,?)');
    $stmt->execute([$key,$_POST['remark']]);
}
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare('DELETE FROM one_keys WHERE id=?');
    $stmt->execute([$_GET['delete']]);
}
$keys = $pdo->query('SELECT * FROM one_keys')->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html><head><meta charset="utf-8"><title>One Keys</title></head><body>
<h2>One Keys</h2>
<form method="post">
    <input name="remark" placeholder="Remark">
    <button name="add" type="submit">Generate</button>
</form>
<table border="1" cellpadding="5" cellspacing="0">
<tr><th>ID</th><th>One_Key</th><th>Remark</th><th>Tokens Used</th><th>Action</th></tr>
<?php foreach($keys as $k): ?>
<tr>
<td><?= $k['id'] ?></td>
<td><?= htmlspecialchars($k['one_key']) ?></td>
<td><?= htmlspecialchars($k['remark']) ?></td>
<td><?= $k['tokens_used'] ?></td>
<td><a href="?delete=<?= $k['id'] ?>">Delete</a></td>
</tr>
<?php endforeach; ?>
</table>
<p><a href="dashboard.php">Back</a></p>
</body></html>
