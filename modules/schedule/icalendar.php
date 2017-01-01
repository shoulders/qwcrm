<?php

require(INCLUDES_DIR.'modules/schedule.php');
require(INCLUDES_DIR.'modules/workorder.php');

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

/* 1. Get the data */

$single_schedule    = display_single_schedule($db, $schedule_id);
$single_workorder   = display_single_workorder($db, $single_schedule['0']['WORKORDER_ID']);

$start_datetime     = iCalendar_timestamp_to_datetime($single_schedule['0']['SCHEDULE_START']);
$end_datetime       = iCalendar_timestamp_to_datetime($single_schedule['0']['SCHEDULE_END']);
$current_datetime   = iCalendar_timestamp_to_datetime(time());

$summary            = iCalendar_escapeThisString($single_workorder['0']['CUSTOMER_DISPLAY_NAME'].' - Workorder '.$single_schedule['0']['WORKORDER_ID'].' - Schedule '.$schedule_id);
$description        = build_description($single_workorder['0']['WORK_ORDER_SCOPE'], $single_workorder['0']['WORK_ORDER_DESCRIPTION'], $single_schedule['0']['SCHEDULE_NOTES']);
$address            = iCalendar_escapeThisString(build_full_address($single_workorder['0']['CUSTOMER_ADDRESS'], $single_workorder['0']['CUSTOMER_CITY'], $single_workorder['0']['CUSTOMER_STATE'], $single_workorder['0']['CUSTOMER_ZIP']));

$uniqid             = uniqid();
$filename           = str_replace(' ', '_', $single_workorder['0']['CUSTOMER_DISPLAY_NAME']).'-Workorder-'.$single_schedule['0']['WORKORDER_ID'].'-Schedule-'.$schedule_id.'.ics';
//$filename           = 'schedule.ics'; 

/* 2. Set the correct headers for this file */

header('Content-type: text/calendar; charset=utf-8');
header('Content-Disposition: attachment; filename=' . $filename);

/* 3. Define helper functions */

//$bob = build_full_address($single_workorder['0']['CUSTOMER_ADDRESS'], $single_workorder['0']['CUSTOMER_CITY'], $single_workorder['0']['CUSTOMER_STATE'], $single_workorder['0']['CUSTOMER_POSTCODE']);  

// Correctly build the location field using address data
function build_full_address($address, $city, $state, $postcode){
       
    // Replace real newlines with comma and space, build address using commans
    return preg_replace("/(\r\n|\r|\n)/", ', ', $address).', '.$city.', '.$state.', '.$postcode;
    
}


// Build the main message with the job informtion
function build_description($workorder_scope, $workorder_description, $schedule_notes) {    
    
    $workorder_description  = iCalendar_html_to_textarea($workorder_description);
    $schedule_notes         = iCalendar_html_to_textarea($schedule_notes);
    
    // Workorder Information
    $description =  'Scope: \n\n'.$workorder_scope.'\n\n'.'Description: \n\n'.$workorder_description.'\n\n'.'Schedule Notes: \n\n'.$schedule_notes;
    
    // Contact Information
    $description .= '';
        
    return $description;
    
}



// Converts a unix timestamp to an ics-friendly format
// NOTE: "Z" means that this timestamp is a UTC timestamp. If you need
// to set a locale, remove the "\Z" and modify DTEND, DTSTAMP and DTSTART
// with TZID properties (see RFC 5545 section 3.3.5 for info)
//
// Also note that we are using "H" instead of "g" because iCalendar's Time format
// requires 24-hour time (see RFC 5545 section 3.3.12 for info).
function iCalendar_timestamp_to_datetime($timestamp) {
    return date('Ymd\THis\Z', $timestamp);
}

// Escapes a string of characters
function iCalendar_escapeThisString($content) {
    return preg_replace('/([\,;])/', '\\\$1', $content);
}

// convert textarea stuff to icalendar
function iCalendar_html_to_textarea($content) {   
    
    // Remove real newlines
    $content = preg_replace("/(\r\n|\r|\n)/", '', $content);

    // Escape some characters
    $content = iCalendar_escapeThisString($content);
        
    // Replace <br /> and variants with newline
    $content = preg_replace('/<br ?\/?>/', '\n', $content);    
    
    // Replace <p> with nothing (works)
    $content = preg_replace('/<p>/', '', $content);    
    
    // Replace </p> with 2 newlines (works)
    $content = preg_replace('/<\/p>/', '\n', $content);    
    
    return strip_tags($content);
    
}

/* 4. Echo out the ics file's contents */
echo 
'BEGIN:VCALENDAR'."\r\n".
'VERSION:2.0'."\r\n".
'PRODID:-//QuantumWarp//QWcrm//EN'."\r\n".
'CALSCALE:GREGORIAN'."\r\n".
'BEGIN:VEVENT'."\r\n".
'DTEND:'.$end_datetime."\r\n".
'UID:'.$uniqid."\r\n".
'DTSTAMP:'.$current_datetime."\r\n".
'LOCATION:'.$address."\r\n".
'DESCRIPTION:'.$description."\r\n".
'SUMMARY:'.$summary."\r\n".
'DTSTART:'.$start_datetime."\r\n".
        'CONTACT:Jim Dolittle\, ABC Industries\, +1-919-555-1234'."\r\n".
'END:VEVENT'."\r\n".
'END:VCALENDAR'."\r\n";