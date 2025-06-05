<?php
$config = require __DIR__ . '/../config.php';
$pdo = new PDO("mysql:host={$config['db_host']};dbname={$config['db_name']};charset=utf8mb4", $config['db_user'], $config['db_pass']);
$input = json_decode(file_get_contents('php://input'), true);
if (!$input || !isset($input[0]['One_Key'])) { http_response_code(400); echo 'Invalid'; exit; }
$key = $input[0]['One_Key'];
$stmt = $pdo->prepare('SELECT * FROM one_keys WHERE one_key=?');
$stmt->execute([$key]);
$one = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$one) { http_response_code(403); echo 'Invalid Key'; exit; }

// Here we should forward to third-party API using stored api_keys
$apiKeyRow = $pdo->query('SELECT api_key FROM api_keys WHERE active=1 LIMIT 1')->fetch(PDO::FETCH_ASSOC);
if(!$apiKeyRow){http_response_code(500); echo 'No API Key'; exit;}
$apiKey = $apiKeyRow['api_key'];

// Build conversation for forwarding
$messages = [];
foreach($input as $msg){
    $messages[] = ['role'=>$msg['role'],'content'=>$msg['content']];
}

// placeholder call - in real scenario call external API
$response = ['role'=>'assistant','content'=>'Simulated response'];

// update tokens_used (simple count of characters as tokens)
$tokenCount = strlen(json_encode($messages));
$pdo->prepare('UPDATE one_keys SET tokens_used = tokens_used + ? WHERE id=?')->execute([$tokenCount,$one['id']]);

header('Content-Type: application/json');
echo json_encode($response);
