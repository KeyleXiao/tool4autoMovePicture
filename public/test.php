<?php
session_start();
if (!isset($_SESSION['user_id'])) { header('Location: index.php'); exit; }
?>
<!DOCTYPE html>
<html><head><meta charset="utf-8"><title>Function Test</title>
<style>body{font-family:Arial;padding:20px;}textarea{width:100%;height:100px;}</style>
</head>
<body>
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
<p><a href="dashboard.php">Back</a></p>
</body></html>
