<?php
require __DIR__.'/lib.php';
$pdo = get_pdo();
$input = json_decode(file_get_contents('php://input'), true);
if(!$input || !isset($input[0]['One_Key'])){
    log_api_call(false);
    http_response_code(400);
    api_response(400, [], 'Invalid request');
}
$key = $input[0]['One_Key'];
$stmt = $pdo->prepare('SELECT * FROM one_keys WHERE one_key=?');
$stmt->execute([$key]);
$one = $stmt->fetch(PDO::FETCH_ASSOC);
if(!$one){
    log_api_call(false);
    http_response_code(403);
    api_response(403, [], 'Invalid Key');
}
rate_limit_check($one);
$apiKey = select_api_key();
if(!$apiKey){
    log_api_call(false);
    api_response(500, [], 'No API Key');
}
$messages=[];
foreach($input as $msg){
    $messages[]=['role'=>$msg['role'],'content'=>$msg['content']];
}
// placeholder external API call
$response=['role'=>'assistant','content'=>'Simulated response'];
$tokenCount = strlen(json_encode($messages));
$pdo->prepare('UPDATE one_keys SET tokens_used = tokens_used + ? WHERE id=?')->execute([$tokenCount,$one['id']]);
log_api_call(true,$tokenCount);
api_response(200, $response, 'ok');
?>
