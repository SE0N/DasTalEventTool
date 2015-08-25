<?php

require_once 'cfg/my.conf.php';

$nick = filter_input(INPUT_POST, 'nickname');
$event = filter_input(INPUT_POST, 'event');

// Only go further, when Nick isn't empty and an Event is set
if ($nick !== '' && isset($event) && isset($nick) ) {
    $db = mysqli_connect($cfg['mysql']['host'], $cfg['mysql']['user'], $cfg['mysql']['pass'], $cfg['mysql']['db']);

    $security = hash($cfg['cookie']['hash'], $cfg['cookie']['salt1'].md5($nick).$cfg['cookie']['salt2']);

    setcookie($nick, $security, strtotime( '+365 days' ));

    $nick = $db->escape_string($nick);

    $sql = "INSERT INTO participants"
        . "(nickname, eventId, security)"
        . "VALUES"
        . "('".$nick."', '".$event."', '".$security."')";

    $query = $db->query($sql);

    if(!$query) var_dump($query);

    echo "OK";
} else {
    echo "Nick and Event must be set. Don't try to cheat!";
}
