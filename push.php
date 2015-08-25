<?php

require_once 'cfg/my.conf.php';

$db = mysqli_connect($cfg['mysql']['host'], $cfg['mysql']['user'], $cfg['mysql']['pass'], $cfg['mysql']['db']);

$nick = filter_input(INPUT_POST, 'nickname');
$event = filter_input(INPUT_POST, 'event');

$security = hash($cfg['cookie']['hash'], $cfg['cookie']['salt1'].md5($nick).$cfg['cookie']['salt2']);

setcookie($nick, $security, strtotime( '+365 days' ));

$sql = "INSERT INTO participants"
        . "(nickname, eventId, security)"
        . "VALUES"
        . "('".$nick."', '".$event."', '".$security."')";

$query = $db->query($sql);

if(!$query) var_dump($query);

echo "OK";
