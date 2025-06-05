<?php
function get_pdo(){
    static $pdo=null;
    if($pdo===null){
        $config = require __DIR__ . '/../config.php';
        $pdo = new PDO("mysql:host={$config['db_host']};dbname={$config['db_name']};charset=utf8mb4", $config['db_user'], $config['db_pass']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    return $pdo;
}

function api_response($status,$data=[], $message=''){
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['status'=>$status,'data'=>$data,'message'=>$message], JSON_UNESCAPED_UNICODE);
    exit;
}

function select_api_key(){
    $pdo = get_pdo();
    $rows = $pdo->query('SELECT api_key,weight FROM api_keys WHERE active=1')->fetchAll(PDO::FETCH_ASSOC);
    if(!$rows){
        return null;
    }
    $pool=[];
    foreach($rows as $r){
        for($i=0;$i<$r['weight'];$i++){
            $pool[]=$r['api_key'];
        }
    }
    return $pool[array_rand($pool)];
}

function rate_limit_check($one){
    $pdo = get_pdo();
    $now = time();
    $allowance = $one['allowance'];
    $limit = $one['qps_limit'];
    $allowance += ($now - $one['last_check']) * $limit;
    if($allowance > $limit) $allowance = $limit;
    if($allowance < 1){
        $wait = ceil((1 - $allowance) / $limit);
        $stmt = $pdo->prepare('UPDATE one_keys SET allowance=?, last_check=? WHERE id=?');
        $stmt->execute([$allowance, $now, $one['id']]);
        header('Retry-After: '.$wait);
        http_response_code(429);
        api_response(429, [], "Rate limit exceeded, retry after {$wait}s");
    }
    $allowance -= 1;
    $stmt = $pdo->prepare('UPDATE one_keys SET allowance=?, last_check=? WHERE id=?');
    $stmt->execute([$allowance, $now, $one['id']]);
}

function log_api_call($success,$tokens=0){
    $pdo=get_pdo();
    $stmt=$pdo->prepare('INSERT INTO api_logs(success,tokens) VALUES(?,?)');
    $stmt->execute([$success?1:0,$tokens]);
}
?>
