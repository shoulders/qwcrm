<?php

######################################
# Insert New schedule                #
######################################

//$schedule_start_date and $schedule_end_date, add back into the time arrray would be neater?

function insert_new_schedule($db, $schedule_start_date, $scheduleStartTime, $schedule_end_date, $scheduleEndTime, $schedule_notes, $employee_id, $workorder_id){

    //global $smarty;    

    // Get Full Timestamps for the schedule item (date/hour/minute/second) - 12 Hour
    //$schedule_start_timestamp = datetime_to_timestamp($schedule_start_date, $scheduleStartTime['Time_Hour'], $scheduleStartTime['Time_Minute'], '0', '12', $scheduleStartTime['Time_Meridian']);
    //$schedule_end_timestamp   = datetime_to_timestamp($schedule_end_date, $scheduleEndTime['Time_Hour'], $scheduleEndTime['Time_Minute'], '0', '12', $scheduleEndTime['Time_Meridian']);
    
    // Get Full Timestamps for the schedule item (date/hour/minute/second) - 24 Hour
    $schedule_start_timestamp = datetime_to_timestamp($schedule_start_date, $scheduleStartTime['Time_Hour'], $scheduleStartTime['Time_Minute'], '0', '24');
    $schedule_end_timestamp   = datetime_to_timestamp($schedule_end_date, $scheduleEndTime['Time_Hour'], $scheduleEndTime['Time_Minute'], '0', '24');
    
    // Corrects the extra segment issue
    $schedule_end_timestamp += 1;
        
    validate_schedule_times($db, $schedule_start_date, $schedule_start_timestamp, $schedule_end_timestamp, $employee_id);

/*
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
    $company_day_start = datetime_to_timestamp($schedule_start_date, get_setup_info($db, 'OPENING_HOUR'), get_setup_info($db, 'OPENING_MINUTE'), '0', '24');
    $company_day_end   = datetime_to_timestamp($schedule_start_date, get_setup_info($db, 'CLOSING_HOUR'), get_setup_info($db, 'CLOSING_MINUTE'), '0', '24');
    if($schedule_start_timestamp <= $company_day_start || $schedule_end_timestamp >= $company_day_end) {            
        $smarty->assign('warning_msg', 'You cannot book work outside of company hours');    
        return false;
    }
    
    // Get Todays Schedule time range (this ignores the company hours and returns the whole day)
    //$todays_schedule_start = mktime(0,0,0,date('m',$schedule_start_timestamp),date('d',$schedule_start_timestamp),date('Y',$schedule_start_timestamp));
    //$todays_schedule_end   = mktime(23,59,59,date('m',$schedule_end_timestamp),date('d',$schedule_end_timestamp),date('Y',$schedule_end_timestamp));    
    
    /*
    // Load all schedule items from the database for the supplied employee for the specified day (this currently ignores company hours)
    $q = "SELECT SCHEDULE_START,SCHEDULE_END, SCHEDULE_ID
            FROM ".PRFX."TABLE_SCHEDULE
            WHERE SCHEDULE_START >= ".$todays_schedule_start."
            AND SCHEDULE_END <=".$todays_schedule_end."
            AND EMPLOYEE_ID ='".$employee_id."'
            ORDER BY SCHEDULE_START ASC";
    
    if(!$rs = $db->Execute($q)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    }    
    
    // Loop through all schedule items in the database (for the selected day and employee) and validate that schedule item can be inserted with no conflict.
    while (!$rs->EOF){      

        // Check if this schedule item ends after another item has started      
        if($schedule_start_timestamp <= $rs->fields["SCHEDULE_START"] && $schedule_end_timestamp >= $rs->fields["SCHEDULE_START"]) {            
            $smarty->assign('warning_msg', 'Schedule conflict - This schedule item ends after another schdule has started');    
            return false;
        }
        
        // Check if this schedule item starts before another item has finished
        if($schedule_start_timestamp >= $rs->fields["SCHEDULE_START"] && $schedule_start_timestamp <= $rs->fields["SCHEDULE_END"]) {            
            $smarty->assign('warning_msg', 'Schedule conflict - This schedule item starts before another schedule ends');            
            return false;
        }

        $rs->MoveNext();
    }
*/
    // Assign the workorder to the scheduled employee - this caues a page redirect
    //update_workorder_status($db, $workorder_id, 2);

    // Insert Workorder Note - change this to the displayname of the employee_id not login display name
    insert_new_workorder_history_note($db, $workorder_id, 'Work Order Assigned to '.$_SESSION['login_display_name']);        

    // Insert Note
    insert_new_workorder_history_note($db, $workorder_id, 'Schedule has been set.');   

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
    }

    return true;

}

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
    
    // Corrects the extra segment issue
    $schedule_end_timestamp += 1;
    
    if(!validate_schedule_times($db, $schedule_start_date, $schedule_start_timestamp, $schedule_end_timestamp, $employee_id)) {return false;}
     
    /*
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
    $company_day_start = datetime_to_timestamp($schedule_start_date, get_setup_info($db, 'OPENING_HOUR'), get_setup_info($db, 'OPENING_MINUTE'), '0', '24');
    $company_day_end   = datetime_to_timestamp($schedule_start_date, get_setup_info($db, 'CLOSING_HOUR'), get_setup_info($db, 'CLOSING_MINUTE'), '0', '24');
    if($schedule_start_timestamp <= $company_day_start || $schedule_end_timestamp >= $company_day_end) {            
        $smarty->assign('warning_msg', 'You cannot book work outside of company hours');    
        return false;
    }
    
    /*
    // Check if this schedule item ends after another item has started      
    if($schedule_start_timestamp <= $rs->fields["SCHEDULE_START"] && $schedule_end_timestamp >= $rs->fields["SCHEDULE_START"]) {            
        $smarty->assign('warning_msg', 'Schedule conflict - This schedule item ends after another schdule has started');    
        return false;
    }

    // Check if this schedule item starts before another item has finished
    if($schedule_start_timestamp >= $rs->fields["SCHEDULE_START"] && $schedule_start_timestamp <= $rs->fields["SCHEDULE_END"]) {            
        $smarty->assign('warning_msg', 'Schedule conflict - This schedule item starts before another schedule ends');            
        return false;
    }*/        
    
    $q = "UPDATE ".PRFX."TABLE_SCHEDULE SET
        SCHEDULE_ID         =". $db->qstr( $schedule_id                 ).",
        SCHEDULE_START      =". $db->qstr( $schedule_start_timestamp    ).",
        SCHEDULE_END        =". $db->qstr( $schedule_end_timestamp      ).",
        WORKORDER_ID        =". $db->qstr( $workorder_id                ).",   
        EMPLOYEE_ID         =". $db->qstr( $employee_id                 ).",
        SCHEDULE_NOTES      =". $db->qstr( $schedule_notes              )."
        WHERE SCHEDULE_ID   =". $db->qstr( $schedule_id                 );
    //echo $q;die;

    if(!$rs = $db->execute($q)) {
        // error message need translating
        force_page('core', 'error', 'error_page='.prepare_error_data('error_page', $_GET['page']).'&error_type=database&error_location='.prepare_error_data('error_location', __FILE__).'&php_function='.prepare_error_data('php_function', __FUNCTION__).'&database_error='.prepare_error_data('database_error',$db->ErrorMsg()).'&error_msg='.$smarty->get_template_vars('translate_workorder_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        
        //force_page('schedule', 'main','schedule_id='.$schedule_id.'&schedule_start_year='.$schedule_start_year.'&schedule_start_month='.$schedule_start_month.'&schedule_start_day='.$schedule_start_day);                 
        //exit; 
        return true;
    }
        
    return true;
    
}

######################################
#       Display Schedule             #
######################################

function display_single_schedule($db, $schedule_id) {

    $q = "SELECT ".PRFX."TABLE_SCHEDULE.*, ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_DISPLAY_NAME FROM ".PRFX."TABLE_SCHEDULE 
            LEFT JOIN ".PRFX."TABLE_EMPLOYEE ON (".PRFX."TABLE_SCHEDULE.EMPLOYEE_ID=".PRFX."TABLE_EMPLOYEE.EMPLOYEE_ID )
            WHERE SCHEDULE_ID='".$schedule_id."'";

    if(!$rs = $db->Execute($q)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    }

    $arr = $rs->GetAll();    
    return $arr;

}
    
########################################
# List of all employees and their data #
########################################
    
function display_employees_info($db){
    
    $q = "SELECT EMPLOYEE_ID, EMPLOYEE_TYPE, EMPLOYEE_LOGIN FROM ".PRFX."TABLE_EMPLOYEE";
    
    if(!$rs = $db->Execute($q)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    } else {    
        return $rs->GetArray();    
    }    
}





###############################################################
# check the status of the workorder supplied to the schedule  #
###############################################################

function check_schedule_workorder_status($db, $workorder_id) {
    
    $q = "SELECT WORK_ORDER_CURRENT_STATUS FROM ".PRFX."TABLE_WORK_ORDER WHERE WORK_ORDER_ID=".$db->qstr($workorder_id);
    
    if(!$rs = $db->execute($q)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    } else {
        $status = $rs->fields['WORK_ORDER_CURRENT_STATUS'];
    }

    if($status == '6') {
        force_page('workorder', 'view', 'workorder_id='.$workorder_id.'&error_msg=Can not set a schedule for closed work order&page_title=Work Order ID '.$workorder_id.'&type=warning');
        exit;
    } elseif ($status == '7') {
        force_page('workorder', 'view', 'workorder_id='.$workorder_id.'&error_msg=Can not set a schedule for closed work order&page_title=Work Order ID '.$workorder_id.'&type=warning');
        exit;
    } elseif ($status == '8') {
        force_page('workorder', 'view', 'workorder_id='.$workorder_id.'&error_msg=Can not set a schedule for closed work order&page_title=Work Order ID '.$workorder_id.'&type=warning');
        exit;
    } elseif ($status == '9') {
        force_page('workorder', 'view', 'workorder_id='.$workorder_id.'&error_msg=Can not set a schedule for closed work order&page_title=Work Order ID '.$workorder_id.'&type=warning');
        exit;
    }    
}

#####################################################
#        Build Calendar Matrix                      #
#####################################################

function build_calendar_matrix($db, $schedule_start_year, $schedule_start_month, $schedule_start_day, $employee_id, $workorder_id = null) {
            
    // Get the start and end time of the calendar schedule to be displayed, Office hours only - (unix timestamp)
    $company_day_start = mktime(get_setup_info($db, 'OPENING_HOUR'), get_setup_info($db, 'OPENING_MINUTE'), 0, $schedule_start_month, $schedule_start_day, $schedule_start_year);
    $company_day_end   = mktime(get_setup_info($db, 'CLOSING_HOUR'), get_setup_info($db, 'CLOSING_MINUTE'), 0, $schedule_start_month, $schedule_start_day, $schedule_start_year);    
    /* Same as above but my code - Get the start and end time of the calendar schedule to be displayed, Office hours only - (unix timestamp)
    $company_day_start = datetime_to_timestamp($current_schedule_date, get_setup_info($db, 'OPENING_HOUR'), 0, 0, $clock = '24');
    $company_day_end   = datetime_to_timestamp($current_schedule_date, get_setup_info($db, 'CLOSING_HOUR'), 59, 0, $clock = '24');*/

    echo $company_day_start.'     '.$company_day_end."\n\n<br><br>";
    
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
    
    $matrixStartTime = $company_day_start;

    // Cycle through the Business day in 15 minute segments (set at the bottom)
    while($matrixStartTime <= $company_day_end) {        

        /*
         * There are 2 segment/row types: Whole Hour, Hour With minutes
         * Both have different Styles
         * Left Cells = Time
         * Right Cells = Blank||Clickable Links||Schedule Item
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
                $calendar .= "<td class=\"menutd2\" align=\"center\" onClick=\"window.location='?page=workorder:details&workorder_id=".$scheduleObject[$i]['WORKORDER_ID']."page_title=Work Order ID ".$scheduleObject[$i]['WORKORDER_ID']."'\">\n";

                // Schedule Item Title
                $calendar .= "<b><font color=\"red\">Work Order ".$scheduleObject[$i]['WORKORDER_ID']." for ". $scheduleObject[$i]['CUSTOMER_NAME']."</font></b><br>\n";

                // Time period of schedule
                $calendar .= "<b><font color=\"red\">".date("H:i ",$scheduleObject[$i]['SCHEDULE_START'])." - ".date("H:i ",$scheduleObject[$i]['SCHEDULE_END'])."</font></b><br>\n";

                // Schedule Notes
                $calendar .= "<div style=\"color: blue; font-weight: bold;\">NOTES:  ".$scheduleObject[$i]['SCHEDULE_NOTES']."</div><br>\n";

                // Links for schedule
                $calendar .= "<b><a href=\"index.php?page=schedule:edit&schedule_id=".$scheduleObject[$i]['SCHEDULE_ID']."\">Edit Schedule Item</a></b> -".
                            "<b><a href=\"index.php?page=schedule:sync&schedule_id=".$scheduleObject[$i]['SCHEDULE_ID']."&theme=off\">Sync</a></b> -".
                            "<b><a href=\"index.php?page=schedule:delete&schedule_id=".$scheduleObject[$i]['SCHEDULE_ID']."\">Delete</a></b>\n";

                // Close CELL
                $calendar .= "</td>\n";                
                
            }           
            
        /* Empty ROW */
            
        } else {  
            
            // If just viewing/no workorder_id -  no clickable links to create schedule items
            if(!$workorder_id) {
                if(date('i',$matrixStartTime) == 0) {
                    $calendar .= "<td class=\"olotd\"><b>&nbsp;".date("H:i ", $matrixStartTime)."</b></td>\n";
                    $calendar .= "<td class=\"olotd\"></td>\n";
                } else {
                    $calendar .= "<td class=\"olotd4\">&nbsp;".date("H:i ", $matrixStartTime)."</td>\n";
                    $calendar .= "<td class=\"olotd4\"></td>\n";
                }
            
            // If workorder_id is present enable clickable links
            } else {            
                if(date('i',$matrixStartTime) == 0) {
                    $calendar .= "<td class=\"olotd\" onClick=\"window.location='?page=schedule:new&schedule_start_year={$schedule_start_year}&schedule_start_month={$schedule_start_month}&schedule_start_day={$schedule_start_day}&schedule_start_time=".date("H:i ", $matrixStartTime)."&employee_id=".$employee_id."&workorder_id=".$workorder_id."'\"><b>&nbsp;".date("H:i ", $matrixStartTime)."</b></td>\n";
                    $calendar .= "<td class=\"olotd\" onClick=\"window.location='?page=schedule:new&schedule_start_year={$schedule_start_year}&schedule_start_month={$schedule_start_month}&schedule_start_day={$schedule_start_day}&schedule_start_time=".date("H:i ", $matrixStartTime)."&employee_id=".$employee_id."&workorder_id=".$workorder_id."'\"></td>\n";
                } else {
                    $calendar .= "<td class=\"olotd4\" onClick=\"window.location='?page=schedule:new&&schedule_start_year={$schedule_start_year}&schedule_start_month={$schedule_start_month}&schedule_start_day={$schedule_start_day}&schedule_start_time=".date("H:i ", $matrixStartTime)."&employee_id=".$employee_id."&workorder_id=".$workorder_id."'\">&nbsp;".date("H:i ", $matrixStartTime)."</td>\n";
                    $calendar .= "<td class=\"olotd4\" onClick=\"window.location='?page=schedule:new&&schedule_start_year={$schedule_start_year}&schedule_start_month={$schedule_start_month}&schedule_start_day={$schedule_start_day}&schedule_start_time=".date("H:i ", $matrixStartTime)."&employee_id=".$employee_id."&workorder_id=".$workorder_id."'\"></td>\n";
                }                
            }          
            
        }

        /* Close ROW */
        $calendar .= "</tr>\n";             
        
        /* Loop Advancement */        
        
        // Advance the schedule counter to the next item
        if($matrixStartTime >= $scheduleObject[$i]['SCHEDULE_END']) {$i++;}

        // Advance matrixStartTime by 15 minutes before restarting loop to create 15 minute segements        
        $matrixStartTime += 900;

    }

    // Close the Calendar Matrix Table
    $calendar .= "</table>\n";    
    
    // Return Calender HTML Matrix
    return $calendar;
    
}

##################################
#        Delete Schedule         #
##################################

function delete_schedule($db, $schedule_id) {
    
    $sql = "DELETE FROM ".PRFX."TABLE_SCHEDULE WHERE SCHEDULE_ID =".$db->qstr($schedule_id);

    if(!$rs = $db->execute($sql)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    } else {
        force_page('schedule', 'main');
        exit;
    }
}

############################################
#   validate scehdule start and end time   #
############################################

function validate_schedule_times($db, $schedule_start_date, $schedule_start_timestamp, $schedule_end_timestamp, $employee_id) {
    
    global $smarty;
    
    $company_day_start = datetime_to_timestamp($schedule_start_date, get_setup_info($db, 'OPENING_HOUR'), get_setup_info($db, 'OPENING_MINUTE'), '0', '24');
    $company_day_end   = datetime_to_timestamp($schedule_start_date, get_setup_info($db, 'CLOSING_HOUR'), get_setup_info($db, 'CLOSING_MINUTE'), '0', '24');
    
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
    $sql = "SELECT SCHEDULE_START,SCHEDULE_END, SCHEDULE_ID
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

        // Check if this schedule item ends after another item has started      
        if($schedule_start_timestamp <= $rs->fields["SCHEDULE_START"] && $schedule_end_timestamp >= $rs->fields["SCHEDULE_START"]) {            
            $smarty->assign('warning_msg', 'Schedule conflict - This schedule item ends after another schedule has started');    
            return false;
        }
        
        // Check if this schedule item starts before another item has finished
        if($schedule_start_timestamp >= $rs->fields["SCHEDULE_START"] && $schedule_start_timestamp <= $rs->fields["SCHEDULE_END"]) {            
            $smarty->assign('warning_msg', 'Schedule conflict - This schedule item starts before another schedule ends');            
            return false;
        }

        $rs->MoveNext();
    }
    
    return true;
    
}