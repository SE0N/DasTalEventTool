<?php
$eventId = filter_input(INPUT_GET, 'id');

require_once "cfg/my.conf.php";

$db = mysqli_connect($cfg['mysql']['host'], $cfg['mysql']['user'], $cfg['mysql']['pass'], $cfg['mysql']['db']);

$sql = "SELECT * FROM events WHERE id=$eventId";

$sqlEsc = $db->escape_string($sql);

$query = $db->query($sqlEsc);

while ($row = $query->fetch_object()) {
    $event = $row;
}

$participants = [];

$sql2 = "SELECT * FROM participants WHERE eventID=$eventId";

$sqlEsc2 = $db->escape_string($sql2);

$query2 = $db->query($sqlEsc2);

while ($row = $query2->fetch_object()) {
    $participants[] = $row;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title><?= $event->name ?></title>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

        <!-- Optional theme -->
        <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    </head>
    <body>
        <div class="container">
            <div class="jumbotron">
                <div class="container">
                    <div class="text-center">
                        <a href="http://das-tal-game.com" onclick="window.open(this.href); return false;"><img src="http://assets.das-tal-game.com/blog/embed/img/logo2.png"></a>
                    </div>
                    <div class="page-header">
                        <h1 class="title"><?= $event->name ?></h1>
                        <div class="row">
                            <div class="panel panel-default">
                                <div class="panel-heading">Informations</div>
                                <div class="panel-body" id="participants">
                                    <table class="col-lg-12">
                                        <tr><th>Startdate:</th><td id="startdate">UTC: <?= date('d.m.Y H:i', strtotime($event->date)) ?></td><td>(UTC: <?= date('d.m.Y H:i', strtotime($event->date)) ?>)</td></tr>
                                        <tr><th>Enddate:</th><td id="enddate">UTC: <?= date('d.m.Y H:i', strtotime($event->enddate)) ?></td><td>(UTC: <?= date('d.m.Y H:i', strtotime($event->enddate)) ?>)</td></tr>
                                        <tr><th>Server:</th><td colspan="2"><?= $event->Server ?></td></tr>
                                        <tr><th>Description:</th><td colspan="2"><?= $event->description ?></td></tr>
                                        <tr><th>Questions to:</th><td colspan="2"><a href="http://forum.das-tal-game.com/memberlist.php?mode=viewprofile&u=<?= $event->organizerLink ?>" onclick="window.open(this.href); return false;"><?= $event->organizer ?></a></td></tr>
                                    </table>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">Participants</div>
                                <div class="panel-body" id="participants">
                                    <ul>
                                        <?php foreach($participants as $p): ?>
                                        <li><?= $p->nickname ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">Registration</div>
                                <div class="panel-body">
                                    <form class="form-horizontal">
                                        <div class="form-group">
                                            <label for="inpNickname" class="col-sm-2 control-label">Nickname</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="inpNickname" placeholder="Nickname">
                                            </div>
                                        </div>
                                        <input type="hidden" id="eventId" value="<?= $eventId ?>">
                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <button type="button" class="btn btn-default" id="registerBtn">Register for Event</button>
                                                <a href="/" class="btn btn-danger" role="button">Back</a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">Unsubscribe</div>
                                <div class="panel-body">
                                    <form class="form-horizontal">
                                        <div class="form-group">
                                            <label for="inpNickname" class="col-sm-2 control-label">Nickname</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="inpUnNickname" placeholder="Nickname">
                                            </div>
                                        </div>
                                        <input type="hidden" id="eventUnId" value="<?= $eventId ?>">
                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <button type="button" class="btn btn-default" id="unregisterBtn">Unregister from the Event</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>                
            </div>           
        </div>

        <footer class="footer">
            <div class="container">
                <p class="text-muted">Your local time: <span id="localtime"></span> <span id="estTZ"></span></p>
            </div>
        </footer>

        <script src="http://code.jquery.com/jquery-2.1.4.min.js"></script>
        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
        
        <script src="assets/js/notify.min.js"></script>
        
        <script type="text/javascript">
            $(document).ready(function() {
               var targetTime = new Date(<?= strtotime($event->date) ?>*1000);
               var timeZoneFromDB = 0;
               var tzDifference = (timeZoneFromDB * 60 * -1) + (-1 * targetTime.getTimezoneOffset());
               var offsetTime = new Date(targetTime.getTime() + tzDifference * 60 * 1000);
               $('#startdate').text(offsetTime);
               //$('#startdate').text(targetTime());
               var targetTime2 = new Date(<?= strtotime($event->enddate) ?>*1000);
               var timeZoneFromDB2 = 0;
               var tzDifference2 = (timeZoneFromDB2 * 60 * -1) + (-1 * targetTime2.getTimezoneOffset());
               var offsetTime2 = new Date(targetTime2.getTime() + tzDifference2 * 60 * 1000);
               $('#enddate').text(offsetTime2);
               //$('#enddate').text(targetTime2);
               $('#localtime').text(new Date().toLocaleDateString()+" "+new Date().toLocaleTimeString());
               $('#estTZ').text("UTC"+tzDifference);
            });
        $('#registerBtn').on('click', function() {
            var nick = $('#inpNickname').val();
            var event = $('#eventId').val();
            $.ajax({
                url: 'push.php',
                method: "POST",
                data: {nickname: nick, event: event}
            }).done(function(msg) {
                if(msg==="OK") {
                    $("#participants ul").append("<li>"+nick+"</li>");
                    $.notify("Successfull registered");
                    $('#inpNickname').val("");
                } else {
                    console.log(msg);
                    $.notify("Error");
                }                
            });
        });
        
        $('#unregisterBtn').on('click', function() {
            var nick = $('#inpUnNickname').val();
            var event = $('#eventUnId').val();
            $.ajax({
                url: 'unpush.php',
                method: "POST",
                data: {nickname: nick, event: event}
            }).done(function(msg) {
                if(msg==="OK") {
                    $('#inpUnNickname').val("");
                    location.reload();
                } else {
                    console.log(msg);
                    $.notify("Error: Security Test not passed");
                }
            })
        });
        </script>
    </body>
</html>