<?php
session_start();
if (!isset($_SESSION['user_id'])) { header('Location: index.php'); exit; }
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Function Test</title>
<link rel="stylesheet" href="style.css">
<script src="script.js" defer></script>
<style>textarea{width:100%;height:100px;}</style>
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
<h2>Function Test</h2>
<textarea id="log" readonly></textarea><br>
<input id="msg" placeholder="Message"><button onclick="send()">Send</button>
<script>
function send(){
  const key=prompt('One_Key');
  const log=document.getElementById('log');
  fetch('chat.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify([{One_Key:key,role:'user',content:document.getElementById('msg').value}])})
    .then(r=>r.text()).then(t=>{log.value+=t+'\n';});
}
</script>
</div>
<div id="loading" class="loading-overlay"><div>Loading...</div></div>
<div class="toast-container"></div>
</body>
</html>
