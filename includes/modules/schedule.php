<?php

/*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

/*
 * Mandatory Code - Code that is run upon the file being loaded
 * Display Functions - Code that is used to primarily display records - linked tables
 * New/Insert Functions - Creation of new records
 * Get Functions - Grabs specific records/fields ready for update - no table linking
 * Update Functions - For updating records/fields
 * Close Functions - Closing Work Orders code
 * Delete Functions - Deleting Work Orders
 * iCalendar Functions - code for creating and manipulation iCalendar .ics format
 * Other Functions - All other functions not covered above
 */

/** Mandatory Code **/

/** Display Functions **/

###############################
# Display Work Order Schedule #
###############################

function display_workorder_schedule($db, $workorder_id){
    
    global $smarty;
    
    $sql = "SELECT * FROM ".PRFX."TABLE_SCHEDULE WHERE WORKORDER_ID=".$db->qstr($workorder_id);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_schedule_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        
        return $rs->GetArray();  
        
    }
    
}

/** New/Insert Functions **/

######################################
# Insert New schedule                #
######################################

function insert_new_schedule($db, $schedule_start_date, $scheduleStartTime, $schedule_end_date, $scheduleEndTime, $schedule_notes, $employee_id, $workorder_id){

    //global $smarty;    

    // Get Full Timestamps for the schedule item (date/hour/minute/second) - 12 Hour
    //$schedule_start_timestamp = datetime_to_timestamp($schedule_start_date, $scheduleStartTime['Time_Hour'], $scheduleStartTime['Time_Minute'], '0', '12', $scheduleStartTime['Time_Meridian']);
    //$schedule_end_timestamp   = datetime_to_timestamp($schedule_end_date, $scheduleEndTime['Time_Hour'], $scheduleEndTime['Time_Minute'], '0', '12', $scheduleEndTime['Time_Meridian']);
    
    // Get Full Timestamps for the schedule item (date/hour/minute/second) - 24 Hour
    $schedule_start_timestamp = datetime_to_timestamp($schedule_start_date, $scheduleStartTime['Time_Hour'], $scheduleStartTime['Time_Minute'], '0', '24');
    $schedule_end_timestamp   = datetime_to_timestamp($schedule_end_date, $scheduleEndTime['Time_Hour'], $scheduleEndTime['Time_Minute'], '0', '24');
    
    // Corrects the extra time segment issue
    $schedule_end_timestamp -= 1;
    
    // Validate the submitted dates
    if(!validate_schedule_times($db, $schedule_start_date, $schedule_start_timestamp, $schedule_end_timestamp, $employee_id)) {return false;}        

    // Insert schedule item into the database
    $sql = "INSERT INTO ".PRFX."TABLE_SCHEDULE SET
            SCHEDULE_START     = ". $db->qstr( $schedule_start_timestamp  ).",
            SCHEDULE_END       = ". $db->qstr( $schedule_end_timestamp    ).",
            WORKORDER_ID       = ". $db->qstr( $workorder_id              ).",
            EMPLOYEE_ID        = ". $db->qstr( $employee_id               ).",
            SCHEDULE_NOTES     = ". $db->qstr( $schedule_notes            );            

    if(!$rs = $db->Execute($sql)) {
        
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
        
    } else {
        
        // Get the new Workorders ID
        $schedule_id = $db->Insert_ID();
        
        // Assign the workorder to the scheduled employee        
        assign_workorder_to_employee($db, $workorder_id, $_SESSION['login_id'], get_workorder_details($db, $workorder_id, 'WORK_ORDER_ASSIGN_TO'), $employee_id);
    
        // Change the Workorders Status
        update_workorder_status($db, $workorder_id, 2); 
        
        // Insert Work Order History Note
        insert_workorder_history_note($db, $workorder_id, 'Schdule 51 added');              
        
        // Log activity 
        write_record_to_activity_log('Schedule'.' '.$schedule_id.' '.'has been created and added to work order'.' '.$workorder_id);        
        
        // Update Workorder last activity record
        update_workorder_last_active($db, $workorder_id);
    
        return true;
        
    }    

}

/** Get Functions **/

################################
#  Get Schedule Details        #
################################

function get_schedule_details($db, $schedule_id, $item = null){
    
    global $smarty;
    
    $sql = "SELECT * FROM ".PRFX."TABLE_SCHEDULE WHERE SCHEDULE_ID=".$db->qstr($schedule_id);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_schedule_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else { 
        
        if($item === null){
            
            return $rs->GetArray(); 
            
        } else {
            
            return $rs->fields[$item];   
            
        } 
        
    }
    
}

###############################################
#    Get a workorder ID from a schedule ID    #
###############################################

// this actually loads the whole schedule
// not currently used - do i need this

function get_workorder_id_from_schedule($db, $schedule_id) {
    
    global $smarty;    
     
    $sql = "SELECT WORKORDER_ID FROM ".PRFX."TABLE_SCHEDULE WHERE SCHEDULE_ID=".$db->qstr($schedule_id);
    
    if(!$rs = $db->execute($sql)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    }  

    return $rs->fields['SCHEDULE_ID'];

}

##########################################################
#    Get all schedule IDs for an employee for a date     #
##########################################################

function get_schedule_ids_for_employee_on_date($db, $employee_id, $schedule_start_year, $schedule_start_month, $schedule_start_day) {
    
    // Get the start and end time of the calendar schedule to be displayed, Office hours only - (unix timestamp)
    $company_day_start = mktime(get_company_details($db, 'OPENING_HOUR'), get_company_details($db, 'OPENING_MINUTE'), 0, $schedule_start_month, $schedule_start_day, $schedule_start_year);
    $company_day_end   = mktime(get_company_details($db, 'CLOSING_HOUR'), get_company_details($db, 'CLOSING_MINUTE'), 59, $schedule_start_month, $schedule_start_day, $schedule_start_year);    
      
    // Look in the database for a scheduled events for the current schedule day (within business hours)
    $sql = "SELECT SCHEDULE_ID FROM ".PRFX."TABLE_SCHEDULE       
            WHERE SCHEDULE_START >= ".$company_day_start." AND SCHEDULE_START <= ".$company_day_end."
            AND EMPLOYEE_ID ='".$employee_id.
            "' ORDER BY SCHEDULE_START ASC";
    
    if(!$rs = $db->Execute($sql)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    }
    
    return $rs->GetArray();    
    
}

/** Update Functions **/

######################################
#      Update New schedule           #
######################################

function update_schedule($db, $schedule_start_date, $scheduleStartTime, $schedule_end_date, $scheduleEndTime, $schedule_notes, $schedule_id, $employee_id, $workorder_id) {
    
    global $smarty;

    // Get Full Timestamps for the schedule item (date/hour/minute/second) - 12 Hour
    //$schedule_start_timestamp = datetime_to_timestamp($schedule_start_date, $scheduleStartTime['Time_Hour'], $scheduleStartTime['Time_Minute'], '0', '12', $scheduleStartTime['Time_Meridian']);
    //$schedule_end_timestamp   = datetime_to_timestamp($schedule_end_date, $scheduleEndTime['Time_Hour'], $scheduleEndTime['Time_Minute'], '0', '12', $scheduleEndTime['Time_Meridian']);
    
    // Get Full Timestamps for the schedule item (date/hour/minute/second) - 24 Hour
    $schedule_start_timestamp = datetime_to_timestamp($schedule_start_date, $scheduleStartTime['Time_Hour'], $scheduleStartTime['Time_Minute'], '0', '24');
    $schedule_end_timestamp   = datetime_to_timestamp($schedule_end_date, $scheduleEndTime['Time_Hour'], $scheduleEndTime['Time_Minute'], '0', '24');
    
    // Corrects the extra time segment issue
    $schedule_end_timestamp -= 1;
    
    // Validate the submitted dates
    if(!validate_schedule_times($db, $schedule_start_date, $schedule_start_timestamp, $schedule_end_timestamp, $employee_id, $schedule_id)) {return false;}        
    
    $sql = "UPDATE ".PRFX."TABLE_SCHEDULE SET
        SCHEDULE_ID         =". $db->qstr( $schedule_id                 ).",
        SCHEDULE_START      =". $db->qstr( $schedule_start_timestamp    ).",
        SCHEDULE_END        =". $db->qstr( $schedule_end_timestamp      ).",
        WORKORDER_ID        =". $db->qstr( $workorder_id                ).",   
        EMPLOYEE_ID         =". $db->qstr( $employee_id                 ).",
        SCHEDULE_NOTES      =". $db->qstr( $schedule_notes              )."
        WHERE SCHEDULE_ID   =". $db->qstr( $schedule_id                 );
   
    if(!$rs = $db->execute($sql)) {
        // error message need translating
        force_page('core', 'error', 'error_page='.prepare_error_data('error_page', $_GET['page']).'&error_type=database&error_location='.prepare_error_data('error_location', __FILE__).'&php_function='.prepare_error_data('php_function', __FUNCTION__).'&database_error='.prepare_error_data('database_error',$db->ErrorMsg()).'&error_msg='.$smarty->get_template_vars('translate_workorder_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {       
         
        return true;
        
    }        
    
}

/** Close Functions **/

/** Delete Functions **/

##################################
#        Delete Schedule         #
##################################

function delete_schedule($db, $schedule_id) {
    
    $sql = "DELETE FROM ".PRFX."TABLE_SCHEDULE WHERE SCHEDULE_ID =".$db->qstr($schedule_id);

    if(!$rs = $db->execute($sql)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    } else {
        
        return true;
        
    }
    
}

/** iCalendar Functions **/

#####################################################
#     .ics header settings                          #
#####################################################

function ics_header_settings() {
    
    $ics_header_settings =
        'BEGIN:VCALENDAR'."\r\n".
        'VERSION:2.0'."\r\n".
        'PRODID:-//QuantumWarp//QWcrm//EN'."\r\n".
        'CALSCALE:GREGORIAN'."\r\n".
        'METHOD:PUBLISH'."\r\n";        // does this force events to be added rather than create a new calendar
    
    return $ics_header_settings;
    
}

#####################################################
#        This is the schedule .ics builder          #
#####################################################

function build_single_schedule_ics($db, $schedule_id, $ics_type = 'single') {
    
    // Get the schedule information
    $single_schedule    = get_schedule_details($db, $schedule_id);
    $workorder          = get_workorder_details($db, $single_schedule['WORKORDER_ID']);
    $customer           = get_customer_details($db, $workorder['0']['CUSTOMER_ID']);
    
    $start_datetime     = timestamp_to_ics_datetime($single_schedule['0']['SCHEDULE_START']);
    $end_datetime       = timestamp_to_ics_datetime($single_schedule['0']['SCHEDULE_END']);
    $current_datetime   = timestamp_to_ics_datetime(time());

    $summary            = prepare_ics_strings('SUMMARY', $customer['0']['CUSTOMER_DISPLAY_NAME'].' - Workorder '.$single_schedule['0']['WORKORDER_ID'].' - Schedule '.$schedule_id);
    $description        = prepare_ics_strings('DESCRIPTION', build_ics_description('textarea', $single_schedule, $customer, $workorder));
    $x_alt_desc         = prepare_ics_strings('X-ALT-DESC;FMTTYPE=text/html', build_ics_description('html', $single_schedule, $customer, $workorder));
    
    $location           = prepare_ics_strings('LOCATION', build_single_line_address($customer['0']['CUSTOMER_ADDRESS'], $customer['0']['CUSTOMER_CITY'], $customer['0']['CUSTOMER_STATE'], $customer['0']['CUSTOMER_ZIP']));
    $uniqid             = 'QWcrm-'.$single_schedule['0']['SCHEDULE_ID'].'-'.$single_schedule['0']['SCHEDULE_START'];    
  
    // Build the Schedule .ics content
    
    $single_schedule_ics = '';    
   
    if($ics_type == 'single') {$single_schedule_ics .= ics_header_settings();}
    
    $single_schedule_ics .= 
        'BEGIN:VEVENT'."\r\n".
        'DTSTART:'.$start_datetime."\r\n".    
        'DTEND:'.$end_datetime."\r\n".        
        'DTSTAMP:'.$current_datetime."\r\n".
        'LOCATION:'.$location."\r\n".
        'SUMMARY:'.$summary."\r\n".
        'DESCRIPTION:'.$description."\r\n".
        'X-ALT-DESC;FMTTYPE=text/html:'.$x_alt_desc."\r\n".        
        'UID:'.$uniqid."\r\n".
        'END:VEVENT'."\r\n";

    if($ics_type == 'single') {$single_schedule_ics .= 'END:VCALENDAR'."\r\n";}

    // Return the .ics content
    return $single_schedule_ics;
    
}

#########################################################################
#    Build a multi .ics - the employees schedule items for that day     #
#########################################################################

function build_ics_schedule_day($db, $employee_id, $schedule_start_year, $schedule_start_month, $schedule_start_day) {
    
    // fetch all schdule items for this setup
    $schedule_multi_ics = ics_header_settings();
    
    $schedule_multi_id = get_schedule_ids_for_employee_on_date($db, $employee_id, $schedule_start_year, $schedule_start_month, $schedule_start_day);    
    
    foreach($schedule_multi_id as $schedule_id) {
        $schedule_multi_ics .= build_single_schedule_ics($db, $schedule_id['SCHEDULE_ID'], $type = 'multi');
    }
   
    $schedule_multi_ics .= 'END:VCALENDAR'."\r\n";
    
    return $schedule_multi_ics;
    
}

#########################################################
# Build single line address (suitable for .ics location #
#########################################################

function build_single_line_address($address, $city, $state, $postcode){
       
    // Replace real newlines with comma and space, build address using commans
    return preg_replace("/(\r\n|\r|\n)/", ', ', $address).', '.$city.', '.$state.', '.$postcode;
    
}

#####################################
#     build adddress html style     #
#####################################

// build adddress html style
function build_html_adddress($address, $city, $state, $postcode){
       
    // Open address block
    $html_address = '<address>';
    
    // Replace real newlines with comma and space, build address using commas
    $html_address .= preg_replace("/(\r\n|\r|\n)/", '<br>', $address).'<br>'.$city.'<br>'.$state.'<br>'.$postcode;
    
    // Close address block
    $html_address .= '</address>';
    
    // Return the built address block
    return $html_address;
    
}

##################################################
#    Build description for ics                   #
##################################################

function build_ics_description($type, $single_schedule, $customer, $workorder) {     
    
    if($type == 'textarea') {      

        // Workorder and Schedule Information
        $description =  'Scope: \n\n'.
                        $workorder['0']['WORK_ORDER_SCOPE'].'\n\n'.
                        'Description: \n\n'.
                        html_to_textarea($workorder['0']['WORK_ORDER_DESCRIPTION']).'\n\n'.
                        'Schedule Notes: \n\n'.
                        html_to_textarea($single_schedule['0']['SCHEDULE_NOTES']);

        // Contact Information
        $description .= 'Contact Information'.'\n\n'.
                        'Company: ' .$customer['0']['CUSTOMER_DISPLAY_NAME'].'\n\n'.
                        'Contact: ' .$customer['0']['CUSTOMER_FIRST_NAME'].' '.$customer['0']['CUSTOMER_LAST_NAME'].'\n\n'.
                        'Phone: '   .$customer['0']['CUSTOMER_PHONE'].'\n\n'.
                        'Mobile: '  .$customer['0']['CUSTOMER_MOBILE_PHONE'].'\n\n'.
                        'Email: '   .$customer['0']['CUSTOMER_EMAIL'].'\n\n'.
                        'Website: ' .$customer['0']['CUSTOMER_WWW'].'\n\n'.
                        'Address: ' .build_single_line_address($customer['0']['CUSTOMER_ADDRESS'], $customer['0']['CUSTOMER_CITY'], $customer['0']['CUSTOMER_STATE'], $customer['0']['CUSTOMER_ZIP']).'\n\n';                        
    
    }
    
    if($type == 'html') {
        
        // Open HTML Wrapper
        $description .= '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 3.2//EN">\n'.
                        '<HTML>\n'.
                        '<HEAD>\n'.
                        '<META NAME="Generator" CONTENT="QuantumWarp - QWcrm">\n'.
                        '<TITLE></TITLE>\n'.
                        '</HEAD>\n'.
                        '<BODY>\n';
    
        // Workorder and Schedule Information
        $description .= '<p><strong>Scope: </strong></p>'.
                        '<p>'.$workorder['0']['WORK_ORDER_SCOPE'].'</p>'.
                        '<p><strong>Description: </strong></p>'.
                        '<div>'.$workorder['0']['WORK_ORDER_DESCRIPTION'].'</div>'.
                        '<p><strong>Schedule Notes: </strong></p>'.
                        '<div>'.$single_schedule['0']['SCHEDULE_NOTES'].'</div>';        

        // Contact Information
        $description .= '<p><strong>Contact Information:</strong></p>'.
                        '<p>'.
                        '<strong>Company:</strong> ' .$customer['0']['CUSTOMER_DISPLAY_NAME'].'<br>'.
                        '<strong>Contact:</strong> ' .$customer['0']['CUSTOMER_FIRST_NAME'].' '.$customer['0']['CUSTOMER_LAST_NAME'].'<br>'.              
                        '<strong>Phone:</strong> '   .$customer['0']['CUSTOMER_PHONE'].'<br>'.
                        '<strong>Mobile:</strong> '  .$customer['0']['CUSTOMER_MOBILE_PHONE'].'<br>'.
                        '<strong>Email:</strong> '   .$customer['0']['CUSTOMER_EMAIL'].'<br>'.
                        '<strong>Website:</strong> ' .$customer['0']['CUSTOMER_WWW'].
                        '</p>'.                
                        '<p><strong>Address: </strong></p>'.
                        build_html_adddress($customer['0']['CUSTOMER_ADDRESS'], $customer['0']['CUSTOMER_CITY'], $customer['0']['CUSTOMER_STATE'], $customer['0']['CUSTOMER_ZIP']);
        
        // Close HTML Wrapper
        $description .= '</BODY>\n'.
                        '</HTML>';        
    
    }
    
    return $description;
    
}

##################################################
# Convert Timestamp into .ics compatible  format #
##################################################

// Converts a unix timestamp to an ics-friendly format
// NOTE: "Z" means that this timestamp is a UTC timestamp. If you need
// to set a locale, remove the "\Z" and modify DTEND, DTSTAMP and DTSTART
// with TZID properties (see RFC 5545 section 3.3.5 for info)
//
// Also note that we are using "H" instead of "g" because iCalendar's Time format
// requires 24-hour time (see RFC 5545 section 3.3.12 for info).
function timestamp_to_ics_datetime($timestamp) {
    return date('Ymd\THis\Z', $timestamp);
}

##################################################
#      Convert HTML into Textarea                #
##################################################

function html_to_textarea($content) {   
    
    // Remove real newlines
    $content = preg_replace("/(\r|\n)/", '', $content);
        
    // Replace <br> and variants with newline
    $content = preg_replace('/<br ?\/?>/', '\n', $content);    
    
    // Remove <p>
    $content = preg_replace('/<p>/', '', $content);    
    
    // Replace </p> with newline
    $content = preg_replace('/<\/p>/', '\n', $content);    
    
    return strip_tags($content);
    
}

##################################################
#      Prepare the text strings for .ics         #
##################################################

// prepare the text strings
function prepare_ics_strings($ics_keyname, $ics_string) {
    
    // Remove whitespace at the beginning and end of the string
    $ics_string = trim($ics_string);
    
    // Replace real newlines with escaped character (i dont think this is needed)
    $ics_string = preg_replace("/(\r|\n)/", '', $ics_string);
    
    // Replace combined escaped newlines to escaped unix style newlines
    $ics_string = preg_replace('/(\r\n)/', '\n', $ics_string);
    
    // Escape some characters .ics does not like
    $ics_string = preg_replace('/([\,;])/', '\\\$1', $ics_string);
    
    // Break into octets with 75 character line limit (as per spec)
    $ics_string = ics_string_octet_split($ics_keyname, $ics_string);
    
    return $ics_string;
    
}

##################################################
#     split ics content into 75-octet line       #
##################################################

// Original script from https://gist.github.com/hugowetterberg/81747

function ics_string_octet_split($ics_keyname, $ics_string) {    

    // Get the ics_key length (after correction)
    $ics_keyname        .= ':';                 
    $ics_keyname_len    = strlen($ics_keyname);
    
    // Get the Key by Regex if full string supplied
    //preg_match('/^.*\:/U', $ics_string, $ics_keyname);
    
    $lines = array();
    
    // Loop out the chopped lines to the array
    while (strlen($ics_string) > (75 - $ics_keyname_len)) {
        
        $space  = (75 - $ics_keyname_len);
        $mbcc   = $space;
        
        while ($mbcc) {
            $line = mb_substr($ics_string, 0, $mbcc);
            $oct = strlen($line);
            
            if ($oct > $space) {
                $mbcc -= $oct - $space;
            } else {
                $lines[] = $line;
                $ics_keyname_len = 1; // Still take the tab into account
                $ics_string = mb_substr($ics_string, $mbcc);
                break;
            }
            
        }
      
    }
    
    if (!empty($ics_string)) {
        $lines[] = $ics_string;
    }
    
    // Join the lines and return the result
    return join($lines, "\r\n\t");
    
}

/** Other **/

#####################################################
#        Build Calendar Matrix                      #
#####################################################

function build_calendar_matrix($db, $schedule_start_year, $schedule_start_month, $schedule_start_day, $employee_id, $workorder_id = null) {
            
    // Get the start and end time of the calendar schedule to be displayed, Office hours only - (unix timestamp)
    $company_day_start = mktime(get_company_details($db, 'OPENING_HOUR'), get_company_details($db, 'OPENING_MINUTE'), 0, $schedule_start_month, $schedule_start_day, $schedule_start_year);
    $company_day_end   = mktime(get_company_details($db, 'CLOSING_HOUR'), get_company_details($db, 'CLOSING_MINUTE'), 59, $schedule_start_month, $schedule_start_day, $schedule_start_year);    
    /* Same as above but my code - Get the start and end time of the calendar schedule to be displayed, Office hours only - (unix timestamp)
    $company_day_start = datetime_to_timestamp($current_schedule_date, get_company_details($db, 'OPENING_HOUR'), 0, 0, $clock = '24');
    $company_day_end   = datetime_to_timestamp($current_schedule_date, get_company_details($db, 'CLOSING_HOUR'), 59, 0, $clock = '24');*/
      
    // Look in the database for a scheduled events for the current schedule day (within business hours)
    $sql = "SELECT ".PRFX."TABLE_SCHEDULE.*,
        ".PRFX."TABLE_CUSTOMER.CUSTOMER_DISPLAY_NAME
        FROM ".PRFX."TABLE_SCHEDULE
        INNER JOIN ".PRFX."TABLE_WORK_ORDER
        ON ".PRFX."TABLE_SCHEDULE.WORKORDER_ID = ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_ID
        INNER JOIN ".PRFX."TABLE_CUSTOMER
        ON ".PRFX."TABLE_WORK_ORDER.CUSTOMER_ID = ".PRFX."TABLE_CUSTOMER.CUSTOMER_ID
        WHERE ".PRFX."TABLE_SCHEDULE.SCHEDULE_START >= ".$company_day_start." AND ".PRFX."TABLE_SCHEDULE.SCHEDULE_START <= ".$company_day_end."
        AND ".PRFX."TABLE_SCHEDULE.EMPLOYEE_ID ='".$employee_id."' ORDER BY ".PRFX."TABLE_SCHEDULE.SCHEDULE_START ASC";
    
    if(!$rs = $db->Execute($sql)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    }

    // Add any scheduled events found into the $scheduleObject for any employee
    $scheduleObject = array();
    while (!$rs->EOF ){        
        array_push($scheduleObject, array(
            "SCHEDULE_ID"      => $rs->fields["SCHEDULE_ID"],
            "SCHEDULE_START"   => $rs->fields["SCHEDULE_START"],
            "SCHEDULE_END"     => $rs->fields["SCHEDULE_END"],
            "SCHEDULE_NOTES"   => $rs->fields["SCHEDULE_NOTES"],
            "CUSTOMER_NAME"    => $rs->fields["CUSTOMER_DISPLAY_NAME"],
            "WORKORDER_ID"     => $rs->fields["WORKORDER_ID"]
            ));
        $rs->MoveNext();
    }
    
    /* Build the Calendar Matrix Table Content */   

    // Open the Calendar Matrix Table - Blue Header Bar
    $calendar .= "<table cellpadding=\"0\" cellspacing=\"0\" class=\"olotable\">\n
        <tr>\n
            <td class=\"olohead\" width=\"75\">&nbsp;</td>\n
            <td class=\"olohead\" width=\"600\">&nbsp;</td>\n
        </tr>\n";
    
    // Set the Schedule item array counter
    $i = 0;
    
    // Length of calendar times slots/segments
    $time_slot_length   = 900;
    
    // Needed for the loop advancement
    $matrixStartTime    = $company_day_start;
    
    // Cycle through the Business day in 15 minute segments (set at the bottom) - you take of the $time_slot_length to prevent an additional slot at the end
    while($matrixStartTime <= $company_day_end - $time_slot_length) {        

        /*
         * There are 2 segment/row types: Whole Hour, Hour With minutes
         * Both have different Styles
         * Left Cells = Time
         * Right Cells = Blank || Clickable Links || Schedule Item
         * each ROW is assigned a date and are seperated by 15 minutes
         */
        
        /* Start ROW */
        $calendar .= "<tr>\n";        

        /* Schedule Block ROW */
        
        // If the ROW is within the time range of the schedule item            
        if($matrixStartTime >= $scheduleObject[$i]['SCHEDULE_START'] && $matrixStartTime <= $scheduleObject[$i]['SCHEDULE_END']) {
            
            /* LEFT CELL*/           
            
            // Make the left column blank when there is a schedule item
            $calendar .= "<td></td>\n";           

            /* RIGHT CELL */
            
            // Build the Schedule Block (If the ROW is the same as the schedule item's start time)
            if($matrixStartTime == $scheduleObject[$i]['SCHEDULE_START']){

                // Open CELL and add clickable link (to workorder) for CELL
                $calendar .= "<td class=\"menutd2\" align=\"center\" >\n";

                // Schedule Item Title
                $calendar .= "<b><font color=\"red\">Work Order ".$scheduleObject[$i]['WORKORDER_ID']." for ". $scheduleObject[$i]['CUSTOMER_NAME']."</font></b><br>\n";

                // Time period of schedule
                $calendar .= "<b><font color=\"red\">".date("H:i",$scheduleObject[$i]['SCHEDULE_START'])." - ".date("H:i",$scheduleObject[$i]['SCHEDULE_END'])."</font></b><br>\n";

                // Schedule Notes
                $calendar .= "<div style=\"color: blue; font-weight: bold;\">NOTES:  ".$scheduleObject[$i]['SCHEDULE_NOTES']."</div><br>\n";

                // Links for schedule
                $calendar .= "<b><a href=\"?page=workorder:details&workorder_id=".$scheduleObject[$i]['WORKORDER_ID']."\">View Work Order</a> - </b>";
                $calendar .= "<b><a href=\"index.php?page=schedule:view&schedule_id=".$scheduleObject[$i]['SCHEDULE_ID']."\">View Schedule Item</a></b>";
                if(check_workorder_is_open($db, $scheduleObject[$i]['WORKORDER_ID'])) {                    
                    $calendar .= " - <b><a href=\"index.php?page=schedule:edit&schedule_id=".$scheduleObject[$i]['SCHEDULE_ID']."\">Edit Schedule Item</a></b> - ".
                                    "<b><a href=\"index.php?page=schedule:icalendar&schedule_id=".$scheduleObject[$i]['SCHEDULE_ID']."&theme=print\">iCalendar</a></b> - ".
                                    "<b><a href=\"index.php?page=schedule:delete&schedule_id=".$scheduleObject[$i]['SCHEDULE_ID']."\" onclick=\"return confirmDelete('are you sure');\">Delete</a></b>\n";                                    
                }

                // Close CELL
                $calendar .= "</td>\n";                
                
            }           
            
        /* Empty ROW */
            
        } else {  
            
            // If just viewing/no workorder_id -  no clickable links to create schedule items
            if(!$workorder_id) {
                if(date('i',$matrixStartTime) == 0) {
                    $calendar .= "<td class=\"olotd\"><b>&nbsp;".date("H:i", $matrixStartTime)."</b></td>\n";
                    $calendar .= "<td class=\"olotd\"></td>\n";
                } else {
                    $calendar .= "<td class=\"olotd4\">&nbsp;".date("H:i", $matrixStartTime)."</td>\n";
                    $calendar .= "<td class=\"olotd4\"></td>\n";
                }
            
            // If workorder_id is present enable clickable links
            } else {            
                if(date('i',$matrixStartTime) == 0) {
                    $calendar .= "<td class=\"olotd\" onClick=\"window.location='?page=schedule:new&schedule_start_year={$schedule_start_year}&schedule_start_month={$schedule_start_month}&schedule_start_day={$schedule_start_day}&schedule_start_time=".date("H:i", $matrixStartTime)."&employee_id=".$employee_id."&workorder_id=".$workorder_id."'\"><b>&nbsp;".date("H:i", $matrixStartTime)."</b></td>\n";
                    $calendar .= "<td class=\"olotd\" onClick=\"window.location='?page=schedule:new&schedule_start_year={$schedule_start_year}&schedule_start_month={$schedule_start_month}&schedule_start_day={$schedule_start_day}&schedule_start_time=".date("H:i", $matrixStartTime)."&employee_id=".$employee_id."&workorder_id=".$workorder_id."'\"></td>\n";
                } else {
                    $calendar .= "<td class=\"olotd4\" onClick=\"window.location='?page=schedule:new&schedule_start_year={$schedule_start_year}&schedule_start_month={$schedule_start_month}&schedule_start_day={$schedule_start_day}&schedule_start_time=".date("H:i", $matrixStartTime)."&employee_id=".$employee_id."&workorder_id=".$workorder_id."'\">&nbsp;".date("H:i", $matrixStartTime)."</td>\n";
                    $calendar .= "<td class=\"olotd4\" onClick=\"window.location='?page=schedule:new&schedule_start_year={$schedule_start_year}&schedule_start_month={$schedule_start_month}&schedule_start_day={$schedule_start_day}&schedule_start_time=".date("H:i", $matrixStartTime)."&employee_id=".$employee_id."&workorder_id=".$workorder_id."'\"></td>\n";
                }                
            }          
            
        }

        /* Close ROW */
        $calendar .= "</tr>\n";             
        
        /* Loop Advancement */        
        
        // Advance the schedule counter to the next item
        if($matrixStartTime >= $scheduleObject[$i]['SCHEDULE_END']) {$i++;}

        // Advance matrixStartTime by 15 minutes before restarting loop to create 15 minute segements        
        $matrixStartTime += $time_slot_length;      
       
    }

    // Close the Calendar Matrix Table
    $calendar .= "</table>\n";    
    
    // Return Calender HTML Matrix
    return $calendar;
    
}

############################################
#   validate schedule start and end time   #
############################################

function validate_schedule_times($db, $schedule_start_date, $schedule_start_timestamp, $schedule_end_timestamp, $employee_id, $schedule_id = null) {
    
    global $smarty;
    
    $company_day_start = datetime_to_timestamp($schedule_start_date, get_company_details($db, 'OPENING_HOUR'), get_company_details($db, 'OPENING_MINUTE'), '0', '24');
    $company_day_end   = datetime_to_timestamp($schedule_start_date, get_company_details($db, 'CLOSING_HOUR'), get_company_details($db, 'CLOSING_MINUTE'), '0', '24');
    
    // Add the second I removed to correct extra segment issue
    $schedule_end_timestamp += 1;
     
    // If start time is after end time show message and stop further processing
    if($schedule_start_timestamp > $schedule_end_timestamp) {        
        $smarty->assign('warning_msg', 'Schedule ends before it starts.');
        return false;
    }

    // If the start time is the same as the end time show message and stop further processing
    if($schedule_start_timestamp == $schedule_end_timestamp) {       
        $smarty->assign('warning_msg', 'Start Time and End Time are the Same');        
        return false;
    }

    // Check the schedule is within Company Hours    
    if($schedule_start_timestamp < $company_day_start || $schedule_end_timestamp > $company_day_end) {            
        $smarty->assign('warning_msg', 'You cannot book work outside of company hours');    
        return false;
    }    

    // Load all schedule items from the database for the supplied employee for the specified day (this currently ignores company hours)
    $sql = "SELECT SCHEDULE_START, SCHEDULE_END, SCHEDULE_ID
            FROM ".PRFX."TABLE_SCHEDULE
            WHERE SCHEDULE_START >= ".$company_day_start."
            AND SCHEDULE_END <=".$company_day_end."
            AND EMPLOYEE_ID ='".$employee_id."'
            ORDER BY SCHEDULE_START ASC";
    
    if(!$rs = $db->Execute($sql)) {
        force_page('core', 'error', 'error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    }    
    
    // Loop through all schedule items in the database (for the selected day and employee) and validate that schedule item can be inserted with no conflict.
    while (!$rs->EOF){
        
        // Check the schedule is not getting updated
        if($schedule_id != $rs->fields['SCHEDULE_ID']) {

            // Check if this schedule item ends after another item has started      
            if($schedule_start_timestamp <= $rs->fields['SCHEDULE_START'] && $schedule_end_timestamp >= $rs->fields['SCHEDULE_START']) {                        
                $smarty->assign('warning_msg', 'Schedule conflict - This schedule item ends after another schedule has started');    
                return false;           
            }

            // Check if this schedule item starts before another item has finished
            if($schedule_start_timestamp >= $rs->fields['SCHEDULE_START'] && $schedule_start_timestamp <= $rs->fields['SCHEDULE_END']) {                    
                $smarty->assign('warning_msg', 'Schedule conflict - This schedule item starts before another schedule ends');    
                return false;
            }
        
        }

        $rs->MoveNext();
        
    }
    
    return true;
    
}