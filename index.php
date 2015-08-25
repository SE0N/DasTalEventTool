<?php
    require_once "cfg/my.conf.php";
    
    $db = mysqli_connect($cfg['mysql']['host'], $cfg['mysql']['user'], $cfg['mysql']['pass'], $cfg['mysql']['db']);
    
    $sql = "SELECT * FROM events WHERE date > NOW()";
    
    $sqlEsc = $db->escape_string($sql);
    
    $query = $db->query($sqlEsc);
    
    $upcomingEvents = [];
    
    while( $row = $query->fetch_object() ) {
        $upcomingEvents[$row->id] = $row;
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Das Tal Dev Server Events</title>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

        <!-- Optional theme -->
        <link rel="stylesheet" href="assets/css/bootstrap.min.css">
        
        <script src="http://code.jquery.com/jquery-2.1.4.min.js"></script>
        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    </head>
    <body>
        <div class="container">
            <div class="jumbotron">
                <div class="container">
                    <div class="text-center">
                        <a href="http://das-tal-game.com" onclick="window.open(this.href); return false;"><img src="http://assets.das-tal-game.com/blog/embed/img/logo2.png"></a>
                    </div>
                    <div class="page-header">
                        <h1 class="title">Upcoming Events</h1>
                        <div class="row">
                            <ul>
                                <?php foreach( $upcomingEvents as $event ): ?>
                                <li><a href="event.php?id=<?= $event->id ?>"><?= $event->name ?> (<span id="timespan<?= $event->id ?>"></span>)</a></li>
                                <script type="text/javascript">
                                    var targetTime = new Date(<?= strtotime($event->date) ?>*1000);
                                    var timeZoneFromDB = 0;
                                    var tzDifference = (timeZoneFromDB * 60 * -1) + (-1 * targetTime.getTimezoneOffset());
                                    var offsetTime = new Date(targetTime.getTime() + tzDifference * 60 * 1000);
                                    var targetTime2 = new Date(<?= strtotime($event->enddate) ?>*1000);
                                    var timeZoneFromDB2 = 0;
                                    var tzDifference2 = (timeZoneFromDB2 * 60 * -1) + (-1 * targetTime2.getTimezoneOffset());
                                    var offsetTime2 = new Date(targetTime2.getTime() + tzDifference2 * 60 * 1000);
                                    $('#timespan<?= $event->id ?>').text(offsetTime.toLocaleDateString()+" "+offsetTime.toLocaleTimeString()+" - "+offsetTime2.toLocaleDateString()+" "+offsetTime2.toLocaleTimeString())
                                </script>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>                
            </div>           
        </div>
    </body>
</html>