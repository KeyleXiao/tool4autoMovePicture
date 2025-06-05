<?php
session_start();
if(!isset($_SESSION['user_id'])){header('Location: index.php');exit;}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Stats</title>
<link rel="stylesheet" href="style.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
    <h2>Usage Statistics</h2>
    <canvas id="tokenChart"></canvas>
    <canvas id="successGauge" width="200" height="200"></canvas>
    <div class="progress-bar"><div></div></div>
    <button onclick="exportCharts()">Export</button>
</div>
<div id="loading" class="loading-overlay"><div>Loading...</div></div>
<div class="toast-container"></div>
<script>
function loadStats(){
    showLoading();
    fetch('stats_data.php').then(r=>r.json()).then(d=>{
        hideLoading();
        updateProgress(100);
        new Chart(document.getElementById('tokenChart').getContext('2d'),{
            type:'line',
            data:{labels:d.tokens.labels,datasets:[{label:'Tokens',data:d.tokens.data,fill:false,borderColor:'#09f'}]}
        });
        new Chart(document.getElementById('successGauge').getContext('2d'),{
            type:'doughnut',
            data:{labels:['Success','Fail'],datasets:[{data:[d.success,100-d.success],backgroundColor:['#4caf50','#f44336']}]},
            options:{circumference:180,rotation:270,cutout:'70%'}
        });
    }).catch(()=>{hideLoading();showToast('Failed to load','error');});
}
function exportCharts(){
    const canvas=document.getElementById('tokenChart');
    const link=document.createElement('a');
    link.download='chart.png';
    link.href=canvas.toDataURL();
    link.click();
}
loadStats();
</script>
</body>
</html>
