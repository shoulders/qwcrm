<?php

######################################
# Insert New schedule                #
######################################

//$schedule_start_date and $schedule_end_date, add back into the time arrray would be neater?

function insert_new_schedule($db, $schedule_start_date, $scheduleStartTime, $schedule_end_date, $scheduleEndTime, $schedule_notes, $employee_id, $workorder_id){

    global $smarty;    

    // Get Schedule Start Time values    
    $schedule_start_hour        = $scheduleStartTime['Time_Hour']; 
    $schedule_start_min         = $scheduleStartTime['Time_Minute'];   
    //$schedule_start_meridian    = $scheduleStartTime['Time_Meridian'];  // 12 hour only

    // Get Schedule End Time values    
    $schedule_end_hour          = $scheduleEndTime['Time_Hour'];
    $schedule_end_min           = $scheduleEndTime['Time_Minute'];
    //$schedule_end_meridian      = $scheduleEndTime['Time_Meridian'];  // 12 hour only
    
    // Get Full Timestamps for the schedule item (date/hour/minute/second) - 12 Hour
    //$schedule_start_timestamp = datetime_to_timestamp($schedule_start_date, $schedule_start_hour, $schedule_start_min, '0', '12', $schedule_start_meridian);
    //$schedule_end_timestamp   = datetime_to_timestamp($schedule_end_date, $schedule_end_hour, $schedule_end_min, '0', '12', $schedule_end_meridian);
    
    // Get Full Timestamps for the schedule item (date/hour/minute/second) - 24 Hour
    $schedule_start_timestamp = datetime_to_timestamp($schedule_start_date, $schedule_start_hour, $schedule_start_min, '0', '24');
    $schedule_end_timestamp   = datetime_to_timestamp($schedule_end_date, $schedule_end_hour, $schedule_end_min, '0', '24');
    
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

    // Get Todays Schedule (this ignores the company hours and returns the whole day)
    $todays_schedule_start = mktime(0,0,0,date("m",$schedule_start_timestamp),date("d",$schedule_start_timestamp),date("Y",$schedule_start_timestamp));
    $todays_schedule_end   = mktime(23,59,59,date("m",$schedule_end_timestamp),date("d",$schedule_end_timestamp),date("Y",$schedule_end_timestamp));    
    
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
    
    // Not sure why checking for workorder here as there must be one set
    if($workorder_id != 0 ) {

        // Update work order and assign to employee
        $q = "UPDATE ".PRFX."TABLE_WORK_ORDER SET 
              WORK_ORDER_ASSIGN_TO          =".$db->qstr( $employee_id   ).",        
              WORK_ORDER_CURRENT_STATUS     =".$db->qstr( 2              ).",
              LAST_ACTIVE                   =".$db->qstr( time()         )."  
              WHERE WORK_ORDER_ID           =".$db->qstr( $workorder_id  );

        if(!$rs = $db->Execute($q)) {
            force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
            exit;
        }
        
        // Update Workorder Notes - change this to the displayname of the employee_id not login display name
        insert_new_workorder_history_note($db, $workorder_id, 'Work Order Assigned to '.$_SESSION['login_display_name']);        

        // Update Notes
        insert_new_workorder_history_note($db, $workorder_id, 'Schedule has been set.'); 
        
        
        

        // Count the number of schedule items for the specified workorder_id
        $q = "SELECT count(*) as count FROM ".PRFX."TABLE_SCHEDULE WHERE WORK_ORDER_ID='".$workorder_id."'";
        
        if(!$rs = $db->execute($q)) {
            force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
            exit;
        }
        $count = $rs->fields['count'];
        
        // if there are schedule items for the workorder_id - is this an attempt to UPDATE rather than INSERT
        if($count != 0) {
            $sql = "UPDATE ".PRFX."TABLE_SCHEDULE SET ";
            $where = " WHERE WORK_ORDER_ID='".$workorder_id."'";
        } else {
            $sql = "INSERT INTO ".PRFX."TABLE_SCHEDULE SET ";
        }
    } else {
        $sql = "INSERT INTO ".PRFX."TABLE_SCHEDULE SET ";
    }
    
    
    
    
    
    // Insert schedule item into the database
    $sql .="SCHEDULE_START      = '".$schedule_start_timestamp."',
             SCHEDULE_END       = '".$schedule_end_timestamp."',
             WORK_ORDER_ID      = '".$workorder_id."',
             EMPLOYEE_ID        = '".$employee_id."',
             SCHEDULE_NOTES     = '".$schedule_notes."'
            " .$where;

    if(!$rs = $db->Execute($sql)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    }
         
    return true;

}
    
######################################
# View New schedule                  #
######################################

    function view_schedule($db, $schedule_id) {
    
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

#####################################################
# Display all open Work orders for the given status #  // taken from workorder.php
#####################################################

function display_workorders($db, $page_no, $status){
    
    global $smarty;
    
    $max_results = 5;
    
    $from = (($page_no * $max_results) - $max_results);
 
    $rs = $db->Execute("SELECT COUNT(*) as Num FROM ".PRFX."TABLE_WORK_ORDER WHERE WORK_ORDER_STATUS =".$db->qstr($status));
                                                  
    $where = "WHERE ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_CURRENT_STATUS= ".$db->qstr($status);    
    
    $total_results = $rs->FetchRow();
    
    $total_pages = ceil($total_results['Num'] / $max_results);
    
    if($page_no > 1){
        $prev = ($page_no - 1);
        $smarty->assign('previous', $prev);
    } 

    if($page_no < $total_pages){
        $next = ($page_no + 1);
        $smarty->assign('next', $next);
    }    
    
    $sql = "SELECT 
            ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_ID, 
            ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_OPEN_DATE,
            ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_ASSIGN_TO,
            ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_SCOPE,
            ".PRFX."TABLE_CUSTOMER.CUSTOMER_ID,
            ".PRFX."TABLE_CUSTOMER.CUSTOMER_DISPLAY_NAME,
            ".PRFX."TABLE_CUSTOMER.CUSTOMER_ADDRESS,
            ".PRFX."TABLE_CUSTOMER.CUSTOMER_CITY,
            ".PRFX."TABLE_CUSTOMER.CUSTOMER_STATE,
            ".PRFX."TABLE_CUSTOMER.CUSTOMER_ZIP,
            ".PRFX."TABLE_CUSTOMER.CUSTOMER_PHONE,
            ".PRFX."TABLE_CUSTOMER.CUSTOMER_WORK_PHONE,
            ".PRFX."TABLE_CUSTOMER.CUSTOMER_MOBILE_PHONE,
            ".PRFX."TABLE_CUSTOMER.CUSTOMER_EMAIL,
            ".PRFX."TABLE_CUSTOMER.CUSTOMER_TYPE,
            ".PRFX."TABLE_CUSTOMER.CUSTOMER_FIRST_NAME,
            ".PRFX."TABLE_CUSTOMER.CUSTOMER_LAST_NAME,
            ".PRFX."TABLE_CUSTOMER.DISCOUNT,
            ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_ID,
            ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_DISPLAY_NAME,
            ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_WORK_PHONE,
            ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_HOME_PHONE,
            ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_MOBILE_PHONE,
            ".PRFX."CONFIG_WORK_ORDER_STATUS.CONFIG_WORK_ORDER_STATUS
            FROM ".PRFX."TABLE_WORK_ORDER
            LEFT JOIN ".PRFX."TABLE_CUSTOMER ON ".PRFX."TABLE_WORK_ORDER.CUSTOMER_ID                            = ".PRFX."TABLE_CUSTOMER.CUSTOMER_ID
            LEFT JOIN ".PRFX."TABLE_EMPLOYEE ON ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_ASSIGN_TO                   = ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_ID
            LEFT JOIN ".PRFX."CONFIG_WORK_ORDER_STATUS ON ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_CURRENT_STATUS    = ".PRFX."CONFIG_WORK_ORDER_STATUS.CONFIG_WORK_ORDER_STATUS_ID
            ".$where." GROUP BY ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_ID ORDER BY ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_ID DESC";
     
    if(!$rs = $db->Execute($sql)) {
        force_page('core', 'error', 'error_page='.prepare_error_data('error_page', $_GET['page']).'&error_type=database&error_location='.prepare_error_data('error_location', __FILE__).'&php_function='.prepare_error_data('php_function', __FUNCTION__).'&database_error='.prepare_error_data('database_error',$db->ErrorMsg()).'&error_msg='.$smarty->get_template_vars('translate_workorder_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
    
        $workorders_array = $rs->GetArray();

        if(empty($workorders_array)) {
            force_page('core', 'error', 'error_page='.prepare_error_data('error_page', $_GET['page']).'&error_type=database&error_location='.prepare_error_data('error_location', __FILE__).'&php_function='.prepare_error_data('php_function', __FUNCTION__).'&database_error='.prepare_error_data('database_error',$db->ErrorMsg()).'&error_msg='.$smarty->get_template_vars('translate_workorder_error_message_function_'.__FUNCTION__.'_notfound'));
            exit;
        } else {
            return $workorders_array;
        }
    }
}



################################################
#  Get setup info - individual items           # // translate this, maybe move to root or get rid of setup and add to company
################################################

/*
 * This combined function allows you to pull any of the setup information individually
 * or return them all as an array
 * supply the required field name or all to return all of them as an array
 */

function get_setup_info($db, $item){
    
    global $smarty;

    $q = 'SELECT * FROM '.PRFX.'SETUP';
    
    if(!$rs = $db->execute($q)){        
        force_page('core', 'error', 'error_page='.prepare_error_data('error_page', $_GET['page']).'&error_type=database&error_location='.prepare_error_data('error_location', __FILE__).'&php_function='.prepare_error_data('php_function', __FUNCTION__).'&database_error='.prepare_error_data('database_error',$db->ErrorMsg()).'&error_msg='.$smarty->get_template_vars('translate_system_include_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {        
        if($item === 'all'){            
            return $rs->GetArray();            
        } else {
            return $rs->fields[$item];          
        }        
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
    $business_day_start = mktime(get_setup_info($db, 'OPENING_HOUR'), 0, 0, $schedule_start_month, $schedule_start_day, $schedule_start_year);
    $business_day_end   = mktime(get_setup_info($db, 'CLOSING_HOUR'), 59, 0, $schedule_start_month, $schedule_start_day, $schedule_start_year);
    
    /* Get the start and end time of the calendar schedule to be displayed, Office hours only - (unix timestamp) - Same as above but my code
    $business_day_start = datetime_to_timestamp($current_schedule_date, get_setup_info($db, 'OPENING_HOUR'), 0, 0, $clock = '24');
    $business_day_end   = datetime_to_timestamp($current_schedule_date, get_setup_info($db, 'CLOSING_HOUR'), 59, 0, $clock = '24');*/

    // Look in the database for a scheduled events for the current schedule day (within business hours)
    $q = "SELECT ".PRFX."TABLE_SCHEDULE.*,
        ".PRFX."TABLE_CUSTOMER.CUSTOMER_DISPLAY_NAME
        FROM ".PRFX."TABLE_SCHEDULE
        INNER JOIN ".PRFX."TABLE_WORK_ORDER
        ON ".PRFX."TABLE_SCHEDULE.WORK_ORDER_ID = ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_ID
        INNER JOIN ".PRFX."TABLE_CUSTOMER
        ON ".PRFX."TABLE_WORK_ORDER.CUSTOMER_ID = ".PRFX."TABLE_CUSTOMER.CUSTOMER_ID
        WHERE ".PRFX."TABLE_SCHEDULE.SCHEDULE_START >= " . $business_day_start. " AND ".PRFX."TABLE_SCHEDULE.SCHEDULE_START <= " .$business_day_end. "
        AND ".PRFX."TABLE_SCHEDULE.EMPLOYEE_ID ='".$employee_id."' ORDER BY ".PRFX."TABLE_SCHEDULE.SCHEDULE_START ASC";

    if(!$rs = $db->Execute($q)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    }

    // Add any scheduled events found into the $scheduleObject
    $scheduleObject = array();
    while (!$rs->EOF ){
        array_push($scheduleObject, array(
            "SCHEDULE_ID"      => $rs->fields["SCHEDULE_ID"],
            "SCHEDULE_START"   => $rs->fields["SCHEDULE_START"],
            "SCHEDULE_END"     => $rs->fields["SCHEDULE_END"],
            "SCHEDULE_NOTES"   => $rs->fields["SCHEDULE_NOTES"],
            "CUSTOMER_NAME"    => $rs->fields["CUSTOMER_DISPLAY_NAME"],
            "WORK_ORDER_ID"    => $rs->fields["WORK_ORDER_ID"]
            ));
        $rs->MoveNext();
    }
    
    /* Build the Calendar Matrix Table Content */    
    
    // Set Calendar Initial Values for the build loop
    $i = 0;
    $matrixStartTime = $business_day_start;

    // Open the Calendar Matrix Table - Blue Header Bar
    $calendar .= "<table cellpadding=\"0\" cellspacing=\"0\" class=\"olotable\">\n
        <tr>\n
            <td class=\"olohead\" width=\"75\">&nbsp;</td>\n
            <td class=\"olohead\" width=\"600\">&nbsp;</td>\n
        </tr>\n";    

    // Cycle through the Business day in 15 minute segments
    while($matrixStartTime <= $business_day_end){

        /*
         * There are 2 segment/row types: Whole Hour, Hour With minutes
         * Both have different Styles
         * Left Cells = Time
         * Right Cells = Blank||Clickable Links||Schedule Item
         * each ROW is assigned a date and are seperated by 15 minutes
         */
        

        /* Start ROW */
        $calendar .= "<tr>\n";

        /* LEFT CELL*/
        if(date("i",$matrixStartTime) == 0){
            $calendar .= "<td class=\"olotd\" nowrap>&nbsp;<b>".date("h:i a", $matrixStartTime)."</b></td>\n";
        } else {
            $calendar .= "<td></td>\n";
        }

        /* RIGHT CELL */

        // If the ROW is within the time range of the schedule item            
        if($matrixStartTime >= $scheduleObject[$i]['SCHEDULE_START'] && $matrixStartTime <= $scheduleObject[$i]['SCHEDULE_END']){

            // Build the schedule CELL (If the ROW is the same as the schedule item's start time)
            if($matrixStartTime == $scheduleObject[$i]['SCHEDULE_START']){

                // Open CELL and add clickable link (to workorder) for CELL
                $calendar .= "<td class=\"menutd2\" align=\"center\" onClick=\"window.location='?page=workorder:details&workorder_id=".$scheduleObject[$i]['WORK_ORDER_ID']."page_title=Work Order ID ".$scheduleObject[$i]['WORK_ORDER_ID']."'\">\n";

                // Schedule Item Title
                $calendar .= "<b><font color=\"red\">Work Order ".$scheduleObject[$i]['WORK_ORDER_ID']." for ". $scheduleObject[$i]['CUSTOMER_NAME']."</font></b><br>\n";

                // Time period of schedule
                $calendar .= "<b><font color=\"red\">".date("h:i a",$scheduleObject[$i]['SCHEDULE_START'])." - ".date("h:i a",$scheduleObject[$i]['SCHEDULE_END'])."</font></b><br>\n";

                // Schedule Notes
                $calendar .= "<div style=\"color: blue; font-weight: bold;\">NOTES-  ".$scheduleObject[$i]['SCHEDULE_NOTES']."</div><br>\n";

                // Links for schedule
                $calendar .= "<b><a href=\"index.php?page=schedule:edit&schedule_id=".$scheduleObject[$i]['SCHEDULE_ID']."&schedule_start_year=".$schedule_start_year."&schedule_start_month=".$schedule_start_month."&schedule_start_day=".$schedule_start_day."&workorder_id=".$scheduleObject[$i]['WORK_ORDER_ID']."\">Edit Note</a></b> -".
                            "<b><a href=\"index.php?page=schedule:sync&workorder_id=".$scheduleObject[$i]['WORK_ORDER_ID']."&theme=off\">Sync</a></b> -".
                            "<b><a href=\"index.php?page=schedule:delete&schedule_id=".$scheduleObject[$i]['SCHEDULE_ID']."&schedule_start_year=".$schedule_start_year."&schedule_start_month=".$schedule_start_month."&schedule_start_day=".$schedule_start_day."&workorder_id=".$scheduleObject[$i]['WORK_ORDER_ID']."\">Delete</a></b>\n";

                // Close CELL
                $calendar .= "</td>\n";            
            }
            
        // Build empty Right CELL If not within a schedule item's time range
        } else {  
            
            // If just viewing the schedule day disable create schedule item clickable links in the blank right cells
            if(!$workorder_id) {
                if(date("i",$matrixStartTime) == 0) {
                    $calendar .= "<td class=\"olotd4\">&nbsp;</td>\n";
                } else {
                    $calendar .= "<td class=\"olotd4\">&nbsp;".date("h:i a", $matrixStartTime)."</td>\n";
                }
            
            // If workorder_id is present enable clickable links for blank right cells
            } else {            
                if(date("i",$matrixStartTime) == 0) {
                    $calendar .= "<td class=\"olotd4\" onClick=\"window.location='?page=schedule:new&schedule_start_year={$schedule_start_year}&schedule_start_month={$schedule_start_month}&schedule_start_day={$schedule_start_day}&schedule_start_time=".date("h:i a", $matrixStartTime)."&employee_id=".$employee_id."&workorder_id=".$workorder_id."'\">&nbsp;</td>\n";
                } else {
                    $calendar .= "<td class=\"olotd4\" onClick=\"window.location='?page=schedule:new&&schedule_start_year={$schedule_start_year}&schedule_start_month={$schedule_start_month}&schedule_start_day={$schedule_start_day}&schedule_start_time=".date("h:i a", $matrixStartTime)."&employee_id=".$employee_id."&workorder_id=".$workorder_id."'\">&nbsp;".date("h:i a", $matrixStartTime)."</td>\n";
                }
            }
        }

        /* Close ROW */
        $calendar .= "</tr>\n";             
        
        /* Loop Advancement */
        
        // If schedule item's end time has been reached advance to the schedule item
        if($matrixStartTime == $scheduleObject[$i]['SCHEDULE_END']) {
            $i++;
        }

        // Advance matrixStartTime by 15 minutes before restarting loop to create 15 minute segements
        $matrixStartTime = mktime(date("H",$matrixStartTime),date("i",$matrixStartTime)+15,0,$schedule_start_month,$schedule_start_day,$schedule_start_year);

    }

    // Close the Calendar Matrix Table
    $calendar .= "</table>\n";    
    
    // Return Calender HTML Matrix
    return $calendar;
}

######################################
# Insert New Work Order History Note # // taken from workorder
######################################

// this might be go in the main include as diffferent modules add work order history notes

function insert_new_workorder_history_note($db, $workorder_id, $workorder_history_note){
    
    global $smarty;
    
    $sql = "INSERT INTO ".PRFX."TABLE_WORK_ORDER_HISTORY SET
            WORK_ORDER_ID   = " . $db->qstr( $workorder_id              ).",
            DATE            = " . $db->qstr( time()                     ).",
            NOTE            = " . $db->qstr( $workorder_history_note    ).",
            ENTERED_BY      = " . $db->qstr( $_SESSION['login_id']      );
    
    if(!$rs = $db->Execute($sql)) {        
        force_page('core', 'error', 'error_page='.prepare_error_data('error_page', $_GET['page']).'&error_type=database&error_location='.prepare_error_data('error_location', __FILE__).'&php_function='.prepare_error_data('php_function', __FUNCTION__).'&database_error='.prepare_error_data('database_error',$db->ErrorMsg()).'&error_msg='.$smarty->get_template_vars('translate_workorder_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {        
        update_last_active($db, $workorder_id);        
        return true;        
    }  
}

#################################
#    Update Last Active         # // taken from workorder.php
#################################

function update_last_active($db, $workorder_id){
    
    global $smarty;
    
    $sql = "UPDATE ".PRFX."TABLE_WORK_ORDER SET LAST_ACTIVE=".$db->qstr(time())." WHERE WORK_ORDER_ID=".$db->qstr($workorder_id);
    
    if(!$rs = $db->execute($sql)) {    
        force_page('core', 'error', 'error_page='.prepare_error_data('error_page', $_GET['page']).'&error_type=database&error_location='.prepare_error_data('error_location', __FILE__).'&php_function='.prepare_error_data('php_function', __FUNCTION__).'&database_error='.prepare_error_data('database_error',$db->ErrorMsg()).'&error_msg='.$smarty->get_template_vars('translate_workorder_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        return;
    }
}