<?php

require 'ics-parser/class.iCalReader.php';

ob_start();


date_default_timezone_set('America/New_York');
$allgames = file_get_contents("http://mlb.am/tix/tigers_schedule_home");
file_put_contents('allgames.ics', $allgames);
$ical = new ICal('allgames.ics');

$events = $ical->events();

echoHeader();

foreach ($events as $event) {
    $startTime = $ical->iCalDateToUnixTimestamp($event['DTSTART']);
    $time = date('H', $startTime);
    $day = date('D', $startTime);

    if ($startTime > date("now")) {
        if ($day != 'Sat' && $day != 'Sun') {
            if ($time > 12 && $time < 21) {
                echoEvent($event);
            }
        }
    }
}

unlink ('allgames.ics');
$newCal = ob_get_contents();
file_put_contents("daygames.ics", $newCal);
ob_end_clean();

function echoFooter()
{
    echo "END:VCALENDAR";
}

function echoHeader()
{
    ?>
BEGIN:VCALENDAR
PRODID:-//MLB.com//Schedule Calendar 0.001//EN
VERSION:2.0
CALSCALE:GREGORIAN
METHOD:PUBLISH
NAME:Detroit Tigers
X-WR-CALNAME:Detroit Tigers
TZ:+00
<?php
}


function echoEvent($event)
{
    echo "BEGIN:VEVENT\n";
    echo 'DTSTART:' . $event['DTSTART'] . "\n";
    echo 'DTEND:' . $event['DTEND'] . "\n";
    echo 'DTSTAMP:' . $event['DTSTAMP'] . "\n";
    echo 'UID:' . $event['UID'] . "\n";
    echo 'CREATED:' . $event['CREATED'] . "\n";
    echo 'DESCRIPTION:' . $event['DESCRIPTION'] . "\n";
    echo 'LAST-MODIFIED:' . $event['LAST-MODIFIED'] . "\n";
    echo 'LOCATION:' . $event['LOCATION'] . "\n";
    echo 'SUMMARY:' . $event['SUMMARY'] . "\n";
    echo 'TRANSP:' . $event['TRANSP'] . "\n";
    echo 'SEQUENCE:' . $event['SEQUENCE'] . "\n";
    echo "END:VEVENT\n";
}

?>
