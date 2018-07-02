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

defined('_QWEXEC') or die;

/** Mandatory Code **/

/** Display Functions **/

#####################################################
# Display all Work orders for the given status      # // Status is not currently used but it will be
#####################################################

function display_schedules($order_by, $direction, $use_pages = false, $records_per_page = null, $page_no = null, $search_category = null, $search_term = null, $status = null, $employee_id = null, $customer_id = null, $workorder_id = null) {
    
    $db = QFactory::getDbo();
    $smarty = QFactory::getSmarty();
    
    // Process certain variables - This prevents undefined variable errors
    $records_per_page = $records_per_page ?: '25';
    $page_no = $page_no ?: '1';
    $search_category = $search_category ?: 'schedule_id';    
   
    /* Records Search */
    
    // Default Action
    $whereTheseRecords = "WHERE ".PRFX."schedule_records.schedule_id\n";
    
    // Restrict results by search category (customer) and search term
    if($search_category == 'customer_display_name') {$whereTheseRecords .= " AND ".PRFX."customer_records.display_name LIKE ".$db->qstr('%'.$search_term.'%');}
    
   // Restrict results by search category (employee) and search term
    elseif($search_category == 'employee_display_name') {$whereTheseRecords .= " AND ".PRFX."user_records.display_name LIKE ".$db->qstr('%'.$search_term.'%');}     
    
    // Restrict results by search category and search term
    elseif($search_term) {$whereTheseRecords .= " AND ".PRFX."schedule_records.$search_category LIKE ".$db->qstr('%'.$search_term.'%');} 
    
    /* Filter the Records */
    
    // Restrict by Status
    if($status) {
        
        // All Open schedules
        if($status == 'open') {
            
            $whereTheseRecords .= " AND ".PRFX."schedule_records.is_closed != '1'";
        
        // All Closed schedules
        } elseif($status == 'closed') {
            
            $whereTheseRecords .= " AND ".PRFX."schedule_records.is_closed = '1'";
        
        // Return schedules for the given status
        } else {
            
            $whereTheseRecords .= " AND ".PRFX."schedule_records.status= ".$db->qstr($status);
            
        }
        
    }        

    // Restrict by Employee
    if($employee_id) {$whereTheseRecords .= " AND ".PRFX."schedule_records.user_id=".$db->qstr($employee_id);}

    // Restrict by Customer
    if($customer_id) {$whereTheseRecords .= " AND ".PRFX."schedule_records.customer_id=".$db->qstr($customer_id);}
    
    // Restrict by Work Order
    if($workorder_id) {$whereTheseRecords .= " AND ".PRFX."schedule_records.workorder_id=".$db->qstr($workorder_id);}    
    
    /* The SQL code */
    
    $sql =  "SELECT
            ".PRFX."schedule_records.*,
                
            ".PRFX."customer_records.display_name AS customer_display_name,       
            
            ".PRFX."user_records.display_name AS employee_display_name           
               
            FROM ".PRFX."schedule_records
            LEFT JOIN ".PRFX."user_records ON ".PRFX."schedule_records.employee_id   = ".PRFX."user_records.user_id
            LEFT JOIN ".PRFX."customer_records ON ".PRFX."schedule_records.customer_id = ".PRFX."customer_records.customer_id                 
            ".$whereTheseRecords."
            GROUP BY ".PRFX."schedule_records.".$order_by."
            ORDER BY ".PRFX."schedule_records.".$order_by."
            ".$direction;           
    
    /* Restrict by pages */
    
    if($use_pages) {
    
        // Get Start Record
        $start_record = (($page_no * $records_per_page) - $records_per_page);
        
        // Figure out the total number of records in the database for the given search        
        if(!$rs = $db->Execute($sql)) {
            force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to count the matching schedules."));
        } else {        
            $total_results = $rs->RecordCount();            
            $smarty->assign('total_results', $total_results);
        }        

        // Figure out the total number of pages. Always round up using ceil()
        $total_pages = ceil($total_results / $records_per_page);
        $smarty->assign('total_pages', $total_pages);
        
        // Set the page number
        $smarty->assign('page_no', $page_no);
        
        // Assign the Previous page        
        $previous_page_no = ($page_no - 1);        
        $smarty->assign('previous_page_no', $previous_page_no);          
        
        // Assign the next page        
        if($page_no == $total_pages) {$next_page_no = 0;}
        elseif($page_no < $total_pages) {$next_page_no = ($page_no + 1);}
        else {$next_page_no = $total_pages;}
        $smarty->assign('next_page_no', $next_page_no);
        
        // Only return the given page's records
        $limitTheseRecords = " LIMIT ".$start_record.", ".$records_per_page;
        
        // add the restriction on to the SQL
        $sql .= $limitTheseRecords;
        
    } else {
        
        // This make the drop down menu look correct
        $smarty->assign('total_pages', 1);
        
    }
  
    /* Return the records */
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to return the matching schedules."));
    } else {
        
        $records = $rs->GetArray();

        if(empty($records)){
            
            return false;
            
        } else {
            
            return $records;
            
        }
        
    }
    
}

/** Insert Functions **/

######################################
#  Insert schedule                   #
######################################

function insert_schedule($VAR) {
    
    $db = QFactory::getDbo();

    // Get Full Timestamps for the schedule item (date/hour/minute/second) - 12 Hour
    //$start_timestamp = datetime_to_timestamp($start_date, $start_time['Time_Hour'], $start_time['Time_Minute'], '0', '12', $start_time['time_meridian']);
    //$end_timestamp   = datetime_to_timestamp($end_date, $end_time['Time_Hour'], $end_time['Time_Minute'], '0', '12', $end_time['time_meridian']);
    
    // Get Full Timestamps for the schedule item (date/hour/minute/second) - 24 Hour
    $start_timestamp = datetime_to_timestamp($VAR['start_date'], $VAR['StartTime']['Time_Hour'], $VAR['StartTime']['Time_Minute'], '0', '24');
    $end_timestamp   = datetime_to_timestamp($VAR['end_date'], $VAR['EndTime']['Time_Hour'], $VAR['EndTime']['Time_Minute'], '0', '24');
    
    // Corrects the extra time segment issue
    $end_timestamp -= 1;
    
    // Validate the submitted dates
    if(!validate_schedule_times($VAR['start_date'], $start_timestamp, $end_timestamp, $VAR['employee_id'])) {return false;}        

    // Insert schedule item into the database
    $sql = "INSERT INTO ".PRFX."schedule_records SET
            employee_id     =". $db->qstr( $VAR['employee_id']      ).",
            customer_id     =". $db->qstr( $VAR['customer_id']      ).",   
            workorder_id    =". $db->qstr( $VAR['workorder_id']     ).",
            start_time      =". $db->qstr( $start_timestamp         ).",
            end_time        =". $db->qstr( $end_timestamp           ).",            
            note            =". $db->qstr( $VAR['note']             );            

    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to insert the schedule record into the database."));
    } else {
        
        // Get work order details
        $workorder_details = get_workorder_details($VAR['workorder_id']);
        
        // Get the new Schedule ID
        $schedule_id = $db->Insert_ID();
        
        // Assign the work order to the scheduled employee (if not already)
        if($VAR['employee_id'] != $workorder_details['employee_id']) {
            assign_workorder_to_employee($VAR['workorder_id'], $VAR['employee_id']);
        }
    
        // Change the Workorders Status to scheduled (if not already)
        if($workorder_details['status'] != 'scheduled') {
            update_workorder_status($VAR['workorder_id'], 'scheduled');
        }
        
        // Insert Work Order History Note
        insert_workorder_history_note($VAR['workorder_id'], _gettext("Schedule").' '.$schedule_id.' '._gettext("was created by").' '.QFactory::getUser()->login_display_name.'.');             
        
        // Log activity 
        $record = _gettext("Schedule").' '.$schedule_id.' '._gettext("has been created and added to work order").' '.$VAR['workorder_id'].' '._gettext("by").' '.QFactory::getUser()->login_display_name.'.';
        write_record_to_activity_log($record, $VAR['employee_id'], $VAR['customer_id'], $VAR['workorder_id']);
        
        // Update last active record
        update_workorder_last_active($VAR['workorder_id']);
        update_customer_last_active($VAR['customer_id']);
    
        return true;
        
    }    

}

/** Get Functions **/

################################
#  Get Schedule Details        #
################################

function get_schedule_details($schedule_id, $item = null) {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT * FROM ".PRFX."schedule_records WHERE schedule_id=".$db->qstr($schedule_id);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get the schedule details."));
    } else { 
        
        if($item === null){
            
            return $rs->GetRowAssoc(); 
            
        } else {
            
            return $rs->fields[$item];   
            
        } 
        
    }
    
}

##########################################################
#    Get all schedule IDs for an employee for a date     #
##########################################################

function get_schedule_ids_for_employee_on_date($employee_id, $start_year, $start_month, $start_day) {
    
    $db = QFactory::getDbo();
    
    // Get the start and end time of the calendar schedule to be displayed, Office hours only - (unix timestamp)
    $company_day_start = mktime(get_company_details('opening_hour'), get_company_details('opening_minute'), 0, $start_month, $start_day, $start_year);
    $company_day_end   = mktime(get_company_details('closing_hour'), get_company_details('closing_minute'), 59, $start_month, $start_day, $start_year);    
      
    // Look in the database for a scheduled events for the current schedule day (within business hours)
    $sql = "SELECT schedule_id
            FROM ".PRFX."schedule_records       
            WHERE start_time >= ".$company_day_start." AND start_time <= ".$company_day_end."
            AND employee_id =".$db->qstr($employee_id)."
            ORDER BY start_time
            ASC";
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get all schedule IDs belonging to an employee."));
    } else {
        
        return $rs->GetArray();  
        
    }     
    
}

/** Update Functions **/

######################################
#      Update Schedule               #
######################################

function update_schedule($VAR) {
    
    $db = QFactory::getDbo();
    
    // Get Full Timestamps for the schedule item (date/hour/minute/second) - 12 Hour
    //$start_timestamp = datetime_to_timestamp($start_date, $start_time['Time_Hour'], $start_time['Time_Minute'], '0', '12', $start_time['time_meridian']);
    //$end_timestamp   = datetime_to_timestamp($end_date, $end_time['Time_Hour'], $end_time['Time_Minute'], '0', '12', $end_time['time_meridian']);
    
    // Get Full Timestamps for the schedule item (date/hour/minute/second) - 24 Hour
    $start_timestamp = datetime_to_timestamp($VAR['start_date'], $VAR['StartTime']['Time_Hour'], $VAR['StartTime']['Time_Minute'], '0', '24');
    $end_timestamp   = datetime_to_timestamp($VAR['end_date'], $VAR['EndTime']['Time_Hour'], $VAR['EndTime']['Time_Minute'], '0', '24');
    
    // Corrects the extra time segment issue
    $end_timestamp -= 1;
    
    // Validate the submitted dates
    if(!validate_schedule_times($VAR['start_date'], $start_timestamp, $end_timestamp, $VAR['employee_id'], $VAR['schedule_id'])) { return false; }        
    
    $sql = "UPDATE ".PRFX."schedule_records SET
        schedule_id         =". $db->qstr( $VAR['schedule_id']      ).",
        employee_id         =". $db->qstr( $VAR['employee_id']      ).",
        customer_id         =". $db->qstr( $VAR['customer_id']      ).",
        workorder_id        =". $db->qstr( $VAR['workorder_id']     ).",   
        start_time          =". $db->qstr( $start_timestamp         ).",
        end_time            =". $db->qstr( $end_timestamp           ).",                
        note                =". $db->qstr( $VAR['note']             )."
        WHERE schedule_id   =". $db->qstr( $VAR['schedule_id']      );
   
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to update a schedule record."));
    } else {       
         
        // Insert Work Order History Note
        insert_workorder_history_note($VAR['workorder_id'], _gettext("Schedule").' '.$VAR['schedule_id'].' '._gettext("was updated by").' '.QFactory::getUser()->login_display_name.'.');             
        
        // Log activity 
        $record = _gettext("Schedule").' '.$VAR['schedule_id'].' '._gettext("was updated by").' '.QFactory::getUser()->login_display_name.'.';
        write_record_to_activity_log($record, $VAR['employee_id'], $VAR['customer_id'], $VAR['workorder_id']);
        
        // Update last active record
        update_workorder_last_active($VAR['workorder_id']);
        update_customer_last_active($VAR['customer_id']);        
        
        return true;
        
    }        
    
}

/** Close Functions **/

/** Delete Functions **/

##################################
#        Delete Schedule         #
##################################

function delete_schedule($schedule_id) {
    
    $db = QFactory::getDbo();
    
    // Get schedule details before deleting
    $schedule_details = get_schedule_details($schedule_id);
    
    $sql = "DELETE FROM ".PRFX."schedule_records WHERE schedule_id =".$db->qstr($schedule_id);

    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to delete a schedule record."));
        
    } else {
        
        // If there are no schedules left for this workorder
        if(count_schedules($schedule_details['workorder_id']) == 0) {
            
            // if the workorder status is 'scheduled', change the status to 'assigned'
            if(get_workorder_details($schedule_details['workorder_id'], 'status') == 'scheduled') {
                update_workorder_status($schedule_details['workorder_id'], 'assigned');
            }
            
        }
        
        // Create a Workorder History Note        
        insert_workorder_history_note($schedule_details['workorder_id'], _gettext("Schedule").' '.$schedule_id.' '._gettext("was deleted by").' '.QFactory::getUser()->login_display_name.'.');
        
        // Log activity        
        $record = _gettext("Schedule").' '.$schedule_id.' '._gettext("for Work Order").' '.$schedule_details['workorder_id'].' '._gettext("was deleted by").' '.QFactory::getUser()->login_display_name.'.';
        write_record_to_activity_log($record, $schedule_details['employee_id'], $schedule_details['customer_id'], $schedule_details['workorder_id']);
        
        // Update last active record
        update_workorder_last_active($schedule_details['workorder_id']);
        update_customer_last_active($schedule_details['customer_id']);
        
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

function build_single_schedule_ics($schedule_id, $ics_type = 'single') {
    
    // Get the schedule information
    $schedule_details   = get_schedule_details($schedule_id);
    $customer_details   = get_customer_details($schedule_details['customer_id']);
    
    $start_datetime     = timestamp_to_ics_datetime($schedule_details['start_time']);
    $end_datetime       = timestamp_to_ics_datetime($schedule_details['end_time']);
    $current_datetime   = timestamp_to_ics_datetime(time());

    $summary            = prepare_ics_strings('SUMMARY', $customer_details['display_name'].' - Workorder '.$schedule_details['workorder_id'].' - Schedule '.$schedule_id);
    $description        = prepare_ics_strings('DESCRIPTION', build_ics_description('textarea', $schedule_details, $schedule_details['customer_id'], $schedule_details['workorder_id']));
    $x_alt_desc         = prepare_ics_strings('X-ALT-DESC;FMTTYPE=text/html', build_ics_description('html', $schedule_details, $schedule_details['customer_id'], $schedule_details['workorder_id']));
    
    $location           = prepare_ics_strings('LOCATION', build_single_line_address($customer_details['address'], $customer_details['city'], $customer_details['state'], $customer_details['zip']));
    $uniqid             = 'QWcrm-'.$schedule_details['schedule_id'].'-'.$schedule_details['start_time'];    
  
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

function build_ics_schedule_day($employee_id, $start_year, $start_month, $start_day) {
    
    // fetch all schdule items for this setup
    $schedule_multi_ics = ics_header_settings();
    
    $schedule_multi_id = get_schedule_ids_for_employee_on_date($employee_id, $start_year, $start_month, $start_day);    
    
    foreach($schedule_multi_id as $schedule_id) {
        $schedule_multi_ics .= build_single_schedule_ics($schedule_id['schedule_id'], $type = 'multi');
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

function build_ics_description($type, $single_schedule, $customer_id, $workorder_id) {
    
    $workorder_details  = get_workorder_details($workorder_id);
    $customer_details   = get_customer_details($customer_id);
    
    if($type == 'textarea') {      

        // Workorder and Schedule Information
        $description =  _gettext("Scope").': \n\n'.
                        $workorder_details['scope'].'\n\n'.
                        _gettext("Description").': \n\n'.
                        html_to_textarea($workorder_details['description']).'\n\n'.
                        _gettext("Schedule Note").': \n\n'.
                        html_to_textarea($single_schedule['note']);

        // Contact Information
        $description .= _gettext("Contact Information")  .''.'\n\n'.
                        _gettext("Company")              .': '   .$customer_details['display_name'].'\n\n'.
                        _gettext("Contact")              .': '   .$customer_details['contact_name'].'\n\n'.
                        _gettext("Phone")                .': '   .$customer_details['primary_phone'].'\n\n'.
                        _gettext("Mobile")               .': '   .$customer_details['mobile_phone'].'\n\n'.
                        _gettext("Website")              .': '   .$customer_details['website'].'\n\n'.
                        _gettext("Email")                .': '   .$customer_details['email'].'\n\n'.
                        _gettext("Address")              .': '   .build_single_line_address($customer_details['address'], $customer_details['city'], $customer_details['state'], $customer_details['zip']).'\n\n';                        
    
    }
    
    if($type == 'html') {
        
        $description = '';
        
        // Open HTML Wrapper
        $description .= '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 3.2//EN">\n'.
                        '<HTML>\n'.
                        '<HEAD>\n'.
                        '<META NAME="Generator" CONTENT="QuantumWarp - QWcrm">\n'.
                        '<TITLE></TITLE>\n'.
                        '</HEAD>\n'.
                        '<BODY>\n';
    
        // Workorder and Schedule Information
        $description .= '<p><strong>'._gettext("Scope").': </strong></p>'.
                        '<p>'.$workorder_details['scope'].'</p>'.
                        '<p><strong>'._gettext("Description").': </strong></p>'.
                        '<div>'.$workorder_details['description'].'</div>'.
                        '<p><strong>'._gettext("Schedule Note").': </strong></p>'.
                        '<div>'.$single_schedule['note'].'</div>';        

        // Contact Information
        $description .= '<p><strong>'._gettext("Contact Information").'</strong></p>'.
                        '<p>'.
                        '<strong>'._gettext("Company")   .':</strong> '  .$customer_details['display_name'].'<br>'.
                        '<strong>'._gettext("Contact")   .':</strong> '  .$customer_details['contact_name'].'<br>'.              
                        '<strong>'._gettext("Phone")     .':</strong> '  .$customer_details['primary_phone'].'<br>'.
                        '<strong>'._gettext("Mobile")    .':</strong> '  .$customer_details['mobile_phone'].'<br>'.
                        '<strong>'._gettext("Website")   .':</strong> '  .$customer_details['website'].
                        '<strong>'._gettext("Email")     .':</strong> '  .$customer_details['email'].'<br>'.                        
                        '</p>'.                
                        '<p><strong>'._gettext("Contact Information").'Address: </strong></p>'.
                        build_html_adddress($customer_details['address'], $customer_details['city'], $customer_details['state'], $customer_details['zip']);
        
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

function build_calendar_matrix($start_year, $start_month, $start_day, $employee_id, $workorder_id = null) {
    
    $db = QFactory::getDbo();
    $calendar_matrix = '';
    
    // Get the start and end time of the calendar schedule to be displayed, Office hours only - (unix timestamp)
    $company_day_start = mktime(get_company_details('opening_hour'), get_company_details('opening_minute'), 0, $start_month, $start_day, $start_year);
    $company_day_end   = mktime(get_company_details('closing_hour'), get_company_details('closing_minute'), 59, $start_month, $start_day, $start_year);    
    /* Same as above but my code - Get the start and end time of the calendar schedule to be displayed, Office hours only - (unix timestamp)
    $company_day_start = datetime_to_timestamp($current_schedule_date, get_company_details('opening_hour'), 0, 0, $clock = '24');
    $company_day_end   = datetime_to_timestamp($current_schedule_date, get_company_details('closing_hour'), 59, 0, $clock = '24');*/
      
    // Look in the database for a scheduled events for the current schedule day (within business hours)
    $sql ="SELECT 
        ".PRFX."schedule_records.*,
        ".PRFX."customer_records.display_name AS customer_display_name
        FROM ".PRFX."schedule_records
        INNER JOIN ".PRFX."workorder_records
        ON ".PRFX."schedule_records.workorder_id = ".PRFX."workorder_records.workorder_id
        INNER JOIN ".PRFX."customer_records
        ON ".PRFX."workorder_records.customer_id = ".PRFX."customer_records.customer_id
        WHERE ".PRFX."schedule_records.start_time >= ".$company_day_start."
        AND ".PRFX."schedule_records.start_time <= ".$company_day_end."
        AND ".PRFX."schedule_records.employee_id =".$db->qstr($employee_id)."
        ORDER BY ".PRFX."schedule_records.start_time
        ASC";
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to return the selected schedules."));
    }

    // Add any scheduled events found into the $scheduleObject for any employee
    $scheduleObject = array();
    while (!$rs->EOF){        
        array_push($scheduleObject, array(
            'schedule_id'           => $rs->fields['schedule_id'],
            'customer_display_name' => $rs->fields['customer_display_name'],
            'workorder_id'          => $rs->fields['workorder_id'],
            'start_time'            => $rs->fields['start_time'],
            'end_time'              => $rs->fields['end_time'],
            'note'                  => $rs->fields['note']            
            ));
        $rs->MoveNext();
    }
    
    /* Build the Calendar Matrix Table Content */   

    // Open the Calendar Matrix Table - Blue Header Bar
    $calendar_matrix .= "<table cellpadding=\"0\" cellspacing=\"0\" class=\"olotable\">\n
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
    
    // Cycle through the Business day in 15 minute segments (set at the bottom) - you take off the $time_slot_length to prevent an additional slot at the end
    while($matrixStartTime <= $company_day_end - $time_slot_length) {        

        /*
         * There are 2 segment/row types: Whole Hour, Hour With minutes
         * Both have different Styles
         * Left Cells = Time
         * Right Cells = Blank || Clickable Links || Schedule Item
         * each ROW is assigned a date and are seperated by 15 minutes
         */
        
        /* Start ROW */
        $calendar_matrix .= "<tr>\n";        

        /* Schedule Block ROW */
        
        // If the ROW is within the time range of the schedule item (assuming a schedule object has been created)          
        if(!empty($scheduleObject[$i]) && $matrixStartTime >= $scheduleObject[$i]['start_time'] && $matrixStartTime <= $scheduleObject[$i]['end_time']) {
            
            /* LEFT CELL*/           
            
            // Make the left column blank when there is a schedule item
            $calendar_matrix .= "<td></td>\n";           

            /* RIGHT CELL */
            
            // Build the Schedule Block (If the ROW is the same as the schedule item's start time)
            if($matrixStartTime == $scheduleObject[$i]['start_time']){

                // Open CELL and add clickable link (to workorder) for CELL
                $calendar_matrix .= "<td class=\"menutd2\" align=\"center\" >\n";

                // Schedule Item Title
                $calendar_matrix .= "<b><font color=\"red\">"._gettext("Work Order")." ".$scheduleObject[$i]['workorder_id']." "._gettext("for")." ". $scheduleObject[$i]['customer_display_name']."</font></b><br>\n";

                // Time period of schedule
                $calendar_matrix .= "<b><font color=\"red\">".date("H:i",$scheduleObject[$i]['start_time'])." - ".date("H:i",$scheduleObject[$i]['end_time'])."</font></b><br>\n";

                // Schedule Note
                $calendar_matrix .= "<div style=\"color: blue; font-weight: bold;\">"._gettext("Note").":  ".$scheduleObject[$i]['note']."</div><br>\n";

                // Links for schedule
                $calendar_matrix .= "<b><a href=\"index.php?component=workorder&page_tpl=details&workorder_id=".$scheduleObject[$i]['workorder_id']."\">"._gettext("Work Order")."</a> - </b>";
                $calendar_matrix .= "<b><a href=\"index.php?component=schedule&page_tpl=details&schedule_id=".$scheduleObject[$i]['schedule_id']."\">"._gettext("Details")."</a></b>";
                if(!get_workorder_details($scheduleObject[$i]['workorder_id'], 'is_closed')) {                    
                    $calendar_matrix .= " - <b><a href=\"index.php?component=schedule&page_tpl=edit&schedule_id=".$scheduleObject[$i]['schedule_id']."\">"._gettext("Edit")."</a></b> - ".
                                    "<b><a href=\"index.php?component=schedule&page_tpl=icalendar&schedule_id=".$scheduleObject[$i]['schedule_id']."&theme=print\">"._gettext("Export")."</a></b> - ".
                                    "<b><a href=\"index.php?component=schedule&page_tpl=delete&schedule_id=".$scheduleObject[$i]['schedule_id']."\" onclick=\"return confirmChoice('"._gettext("Are you sure you want to delete this schedule?")."');\">"._gettext("Delete")."</a></b>\n";                                    
                }

                // Close CELL
                $calendar_matrix .= "</td>\n";                
                
            }           
            
        /* Empty ROW */
            
        } else {  
            
            // If just viewing/no workorder_id -  no clickable links to create schedule items
            if(!$workorder_id) {
                if(date('i',$matrixStartTime) == 0) {
                    $calendar_matrix .= "<td class=\"olotd\"><b>&nbsp;".date("H:i", $matrixStartTime)."</b></td>\n";
                    $calendar_matrix .= "<td class=\"olotd\"></td>\n";
                } else {
                    $calendar_matrix .= "<td class=\"olotd4\">&nbsp;".date("H:i", $matrixStartTime)."</td>\n";
                    $calendar_matrix .= "<td class=\"olotd4\"></td>\n";
                }
            
            // If workorder_id is present enable clickable links
            } else {            
                if(date('i',$matrixStartTime) == 0) {
                    $calendar_matrix .= "<td class=\"olotd\" onClick=\"window.location='index.php?component=schedule&page_tpl=new&start_year={$start_year}&start_month={$start_month}&start_day={$start_day}&start_time=".date("H:i", $matrixStartTime)."&employee_id=".$employee_id."&workorder_id=".$workorder_id."'\"><b>&nbsp;".date("H:i", $matrixStartTime)."</b></td>\n";
                    $calendar_matrix .= "<td class=\"olotd\" onClick=\"window.location='index.php?component=schedule&page_tpl=new&start_year={$start_year}&start_month={$start_month}&start_day={$start_day}&start_time=".date("H:i", $matrixStartTime)."&employee_id=".$employee_id."&workorder_id=".$workorder_id."'\"></td>\n";
                } else {
                    $calendar_matrix .= "<td class=\"olotd4\" onClick=\"window.location='index.php?component=schedule&page_tpl=new&start_year={$start_year}&start_month={$start_month}&start_day={$start_day}&start_time=".date("H:i", $matrixStartTime)."&employee_id=".$employee_id."&workorder_id=".$workorder_id."'\">&nbsp;".date("H:i", $matrixStartTime)."</td>\n";
                    $calendar_matrix .= "<td class=\"olotd4\" onClick=\"window.location='index.php?component=schedule&page_tpl=new&start_year={$start_year}&start_month={$start_month}&start_day={$start_day}&start_time=".date("H:i", $matrixStartTime)."&employee_id=".$employee_id."&workorder_id=".$workorder_id."'\"></td>\n";
                }                
            }          
            
        }

        /* Close ROW */
        $calendar_matrix .= "</tr>\n";             
        
        /* Loop Advancement */        
        
        // Advance the schedule counter to the next schedule item if the schedule end time has been reached
        if(!empty($scheduleObject[$i]) && $matrixStartTime >= $scheduleObject[$i]['end_time']) { $i++; }

        // Advance matrixStartTime by 15 minutes before restarting loop to create 15 minute segements        
        $matrixStartTime += $time_slot_length;      
       
    }

    // Close the Calendar Matrix Table
    $calendar_matrix .= "</table>\n";    
    
    // Return Calender HTML Matrix
    return $calendar_matrix;
    
}

############################################
#   validate schedule start and end time   #
############################################

function validate_schedule_times($start_date, $start_timestamp, $end_timestamp, $employee_id, $schedule_id = null) {    
    
    $db = QFactory::getDbo();
    $smarty = QFactory::getSmarty();    
    
    $company_day_start = datetime_to_timestamp($start_date, get_company_details('opening_hour'), get_company_details('opening_minute'), '0', '24');
    $company_day_end   = datetime_to_timestamp($start_date, get_company_details('closing_hour'), get_company_details('closing_minute'), '0', '24');
    
    // Add the second I removed to correct extra segment issue
    $end_timestamp += 1;
     
    // If start time is after end time show message and stop further processing
    if($start_timestamp > $end_timestamp) {        
        $smarty->assign('warning_msg', _gettext("Schedule ends before it starts."));
        return false;
    }

    // If the start time is the same as the end time show message and stop further processing
    if($start_timestamp == $end_timestamp) {       
        $smarty->assign('warning_msg', _gettext("Start Time and End Time are the Same."));        
        return false;
    }

    // Check the schedule is within Company Hours    
    if($start_timestamp < $company_day_start || $end_timestamp > $company_day_end) {            
        $smarty->assign('warning_msg', _gettext("You cannot book work outside of company hours"));    
        return false;
    }    

    // Load all schedule items from the database for the supplied employee for the specified day (this currently ignores company hours)
    $sql = "SELECT
            schedule_id, start_time, end_time
            FROM ".PRFX."schedule_records
            WHERE start_time >= ".$company_day_start."
            AND end_time <=".$company_day_end."
            AND employee_id =".$db->qstr($employee_id)."
            ORDER BY start_time
            ASC";
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to return the selected schedules."));
    }   
    
    // Loop through all schedule items in the database (for the selected day and employee) and validate that schedule item can be inserted with no conflict.
    while (!$rs->EOF){
        
        // Check the schedule is not getting updated
        if($schedule_id != $rs->fields['schedule_id']) {

            // Check if this schedule item ends after another item has started      
            if($start_timestamp <= $rs->fields['start_time'] && $end_timestamp >= $rs->fields['start_time']) {                        
                $smarty->assign('warning_msg', _gettext("Schedule conflict - This schedule item ends after another schedule has started."));    
                return false;           
            }

            // Check if this schedule item starts before another item has finished
            if($start_timestamp >= $rs->fields['start_time'] && $start_timestamp <= $rs->fields['end_time']) {                    
                $smarty->assign('warning_msg', _gettext("Schedule conflict - This schedule item starts before another schedule ends."));    
                return false;
            }
        
        }

        $rs->MoveNext();
        
    }
    
    return true;
    
}