<?php

require_once 'cfg/my.conf.php';

$db = mysqli_connect($cfg['mysql']['host'], $cfg['mysql']['user'], $cfg['mysql']['pass'], $cfg['mysql']['db']);

$nick = filter_input(INPUT_POST, 'nickname');
$event = filter_input(INPUT_POST, 'event');

$security = filter_input(INPUT_COOKIE, "$nick");

if(!isset($security)) { echo "Cookie not found"; exit; }

// Check for Security
$sql = "SELECT security FROM participants WHERE nickname = '$nick' AND eventId = '$event'";

$query = $db->query($sql);

$security2 = $query->fetch_object()->security;

if( $security === $security2 ) {
    // Delete the Username and unset Cookie
    $sql2 = "DELETE FROM participants WHERE nickname = '$nick' AND eventId = '$event'";
    
    $query2 = $db->query($sql2);
    
    setcookie($nick, null, -1);
    unset($_COOKIE[$nick]);
    
    if(!$query2) {
        var_dump($query2); exit;
    }
} else {
    exit;
}

echo "OK";