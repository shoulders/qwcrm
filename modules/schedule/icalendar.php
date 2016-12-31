<?php



//  check for better versions via the forked versions of the script at github





require(INCLUDES_DIR.'modules/schedule.php');
require(INCLUDES_DIR.'modules/workorder.php');

/*this forces it to save as a file or display on screen?
if(isset($_GET["text"])) {
    header("Content-Type: text/plain"); 
}
else {
    header("Content-Type: text/Calendar");
    header("Content-Disposition: inline; filename=$Filename");
}*/

$single_schedule = display_single_schedule($db, $schedule_id);
$single_open_workorder = display_single_workorder($db, $single_schedule['0']['WORKORDER_ID']);

$summary        = 'Workorder '.$single_schedule['0']['WORKORDER_ID'].' for '.$single_open_workorder['0']['CUSTOMER_FIRST_NAME'].' '.$single_open_workorder['0']['CUSTOMER_LAST_NAME'].
$datestart      = $single_schedule['0']['SCHEDULE_START'];
$dateend        = $single_schedule['0']['SCHEDULE_END'];
$address        = $single_open_workorder['0']['CUSTOMER_ADDRESS'];
$uri            = QWCRM_PROTOCOL.QWCRM_DOMAIN.QWCRM_PATH;
$description    = $single_schedule['0']['SCHEDULE_NOTES'];
$filename       = 'schedule.ics';

// https://gist.github.com/jakebellacera/635416

// Variables used in this script:
//   $summary     - text title of the event
//   $datestart   - the starting date (in seconds since unix epoch)
//   $dateend     - the ending date (in seconds since unix epoch)
//   $address     - the event's address
//   $uri         - the URL of the event (add http://)
//   $description - text description of the event
//   $filename    - the name of this file for saving (e.g. my-event-name.ics)
//
// Notes:
//  - the UID should be unique to the event, so in this case I'm just using
//    uniqid to create a uid, but you could do whatever you'd like.
//
//  - iCal requires a date format of "yyyymmddThhiissZ". The "T" and "Z"
//    characters are not placeholders, just plain ol' characters. The "T"
//    character acts as a delimeter between the date (yyyymmdd) and the time
//    (hhiiss), and the "Z" states that the date is in UTC time. Note that if
//    you don't want to use UTC time, you must prepend your date-time values
//    with a TZID property. See RFC 5545 section 3.3.5
//
//  - The Content-Disposition: attachment; header tells the browser to save/open
//    the file. The filename param sets the name of the file, so you could set
//    it as "my-event-name.ics" or something similar.
//
//  - Read up on RFC 5545, the iCalendar specification. There is a lot of helpful
//    info in there, such as formatting rules. There are also many more options
//    to set, including alarms, invitees, busy status, etc.
//
//      https://www.ietf.org/rfc/rfc5545.txt

// 1. Set the correct headers for this file
header('Content-type: text/calendar; charset=utf-8');
header('Content-Disposition: attachment; filename=' . $filename);

// 2. Define helper functions

// Converts a unix timestamp to an ics-friendly format
// NOTE: "Z" means that this timestamp is a UTC timestamp. If you need
// to set a locale, remove the "\Z" and modify DTEND, DTSTAMP and DTSTART
// with TZID properties (see RFC 5545 section 3.3.5 for info)
//
// Also note that we are using "H" instead of "g" because iCalendar's Time format
// requires 24-hour time (see RFC 5545 section 3.3.12 for info).
function timestamp_to_iCalendar_date($timestamp) {
    return date('Ymd\THis\Z', $timestamp);
}

// Escapes a string of characters
function iCalendar_escapeString($string) {
    return preg_replace('/([\,;])/','\\\$1', $string);
}

// 3. Echo out the ics file's contents
?>
BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//hacksw/handcal//NONSGML v1.0//EN
CALSCALE:GREGORIAN
BEGIN:VEVENT
DTEND:<?= timestamp_to_iCalendar_date($dateend) ?>
UID:<?= uniqid() ?>
DTSTAMP:<?= timestamp_to_iCalendar_date(time()) ?>
LOCATION:<?= iCalendar_escapeString($address) ?>
DESCRIPTION:<?= iCalendar_escapeString($description) ?>
URL;VALUE=URI:<?= iCalendar_escapeString($uri) ?>
SUMMARY:<?= iCalendar_escapeString($summary) ?>
DTSTART:<?= timestamp_to_iCalendar_date($datestart) ?>
END:VEVENT
END:VCALENDAR