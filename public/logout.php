<?php
session_start();
require __DIR__.'/lib.php';
$uid = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'unknown';
log_action("Logout user {$uid}");
session_destroy();
header('Location: index.php');
