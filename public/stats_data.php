<?php
require __DIR__.'/lib.php';
$pdo=get_pdo();
$rows=$pdo->query("SELECT DATE(created_at) d, SUM(tokens) t FROM api_logs GROUP BY d ORDER BY d")->fetchAll(PDO::FETCH_ASSOC);
$labels=[];$data=[];
foreach($rows as $r){$labels[]=$r['d'];$data[]=(int)$r['t'];}
$success=$pdo->query("SELECT AVG(success)*100 AS rate FROM api_logs")->fetchColumn();
header('Content-Type: application/json; charset=utf-8');
echo json_encode(['tokens'=>['labels'=>$labels,'data'=>$data],'success'=>round($success,2)]);
