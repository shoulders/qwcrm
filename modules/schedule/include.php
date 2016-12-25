<?php

if(!xml2php("schedule")) {
    $smarty->assign('error_msg',"Error in language file");
}

// Make sure an employee is set - if not employee set use the logged in user
if(isset($VAR['employee_id'])) {
    $employee_id = $VAR['employee_id'];
} else {
    $employee_id = $_SESSION['login_id'];
}

######################################
# Insert New schedule                #
######################################

//$schedule_start_date and $schedule_end_date, add back into the time arrray would be neater?

function insert_new_schedule($db, $schedule_start_date, $scheduleStartTime, $schedule_end_date, $scheduleEndTime, $schedule_notes, $employee_id, $workorder_id){

    global $smarty;    

    // Get Scehdule Start Time values    
    $schedule_start_hour        = $scheduleStartTime['Time_Hour'];   echo $schedule_start_hour.'-';   
    $schedule_start_min         = $scheduleStartTime['Time_Minute']; echo $schedule_start_min.'-';    
    $schedule_start_meridian    = $scheduleStartTime['Time_Meridian']; echo $schedule_start_meridian.'-';   

    // Get Schedule End Time values    
    $schedule_end_hour          = $scheduleEndTime['Time_Hour']; echo $schedule_end_hour.'-';
    $schedule_end_min           = $scheduleEndTime['Time_Minute'];    echo $schedule_end_min.'-';
    $schedule_end_meridian      = $scheduleEndTime['Time_Meridian']; echo $schedule_end_meridian.'-<br /><br />';
    
    // Get Full Timestamps for the schedule item (date/hour/minute/second)
    $schedule_start_timestamp = datetime_to_timestamp($schedule_start_date, $schedule_start_hour, $schedule_start_min, '0', '12', $schedule_start_meridian);
    $schedule_end_timestamp = datetime_to_timestamp($schedule_end_date, $schedule_end_hour, $schedule_end_min, '0', '12', $schedule_end_meridian);
    
    // If start time is after end time show message and stop further processing
    if($schedule_start_timestamp > $schedule_end_timestamp) {        
        $smarty->assign('warning_msg', 'Schedule ends before it starts.');
        return false;
    }

    // If the start time is the same as the end time show message and stop furhter processing
    if($schedule_start_timestamp == $schedule_end_timestamp) {       
        $smarty->assign('warning_msg', 'Start Time and End Time are the Same');        
        return false;
    }

    // Get Todays Schedule
    $todays_schedule_start = mktime(0,0,0,date("m",$schedule_start_timestamp),date("d",$schedule_start_timestamp),date("Y",$schedule_start_timestamp));
    $todays_schedule_end   = mktime(23,59,59,date("m",$schedule_start_timestamp),date("d",$schedule_start_timestamp),date("Y",$schedule_start_timestamp));
    
    $q = "SELECT  SCHEDULE_START,SCHEDULE_END, SCHEDULE_ID  FROM ".PRFX."TABLE_SCHEDULE WHERE SCHEDULE_START >= ".$todays_schedule_start." AND SCHEDULE_END <=".$todays_schedule_end." AND  EMPLOYEE_ID ='".$employee_id."' ORDER BY SCHEDULE_START ASC";
    if(!$rs = $db->Execute($q)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    }    
    
    // not sure what this does
    $counter = 1;

    while (!$rs->EOF){
        //print $schedule_start_timestamp . '>= '.$rs->fields["SCHEDULE_START"].' AND '.$schedule_start_timestamp <= $rs->fields["SCHEDULE_END"].'<br>';

        // Check if start time starts when another is already set
        if($schedule_start_timestamp >= $rs->fields["SCHEDULE_START"] && $schedule_start_timestamp <= $rs->fields["SCHEDULE_END"]) {            
            $smarty->assign('warning_msg', 'Start time starts before another schedule ends<br>');    
            return false;
        }

        // Check if start time starts before one ends
        //print $schedule_end_timestamp.' >= '.$rs->fields["SCHEDULE_START"].' && '.$schedule_start_timestamp.' <= '.$rs->fields["SCHEDULE_START"].'<br>';
        if($schedule_end_timestamp >= $rs->fields["SCHEDULE_START"] && $schedule_start_timestamp <= $rs->fields["SCHEDULE_START"]) {            
            $smarty->assign('warning_msg', 'Schedule conflict. End time runs into next schedule');    
            return false;
        }

        $rs->MoveNext();
    }

    if($workorder_id != 0 ) {

        // Update work order and assign to employee
        $q = "UPDATE ".PRFX."TABLE_WORK_ORDER SET 
              WORK_ORDER_ASSIGN_TO        =".$db->qstr($employee_id).",        
              WORK_ORDER_CURRENT_STATUS    =".$db->qstr(2).",
              LAST_ACTIVE                 =".$db->qstr(time())."  
              WHERE  WORK_ORDER_ID=".$db->qstr($workorder_id);

        if(!$rs = $db->Execute($q)) {
            force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
            exit;
        }    

        
        // Update Notes
        $msg ="Work Order Assigned to ".$_SESSION['login_display_name'];        
        $q = "INSERT INTO ".PRFX."TABLE_WORK_ORDER_HISTORY SET
              WORK_ORDER_ID         = ".$db->qstr($workorder_id).",
              NOTE                  = ".$db->qstr($msg).",
              ENTERED_BY            = ".$db->qstr($_SESSION['login_id']).",
              DATE                  = ".$db->qstr(time());
        
        if(!$rs = $db->Execute($q)) {
            force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
            exit;
        }

        // Update Notes
        $msg ="Schedule has been set.";
        $q = "INSERT INTO ".PRFX."TABLE_WORK_ORDER_HISTORY SET
              WORK_ORDER_ID     = ".$db->qstr($workorder_id).",
              NOTE              = ".$db->qstr($msg).",
              ENTERED_BY        = ".$db->qstr($_SESSION['login_id']).",
              DATE              = ".$db->qstr(time());
        
        if(!$rs = $db->Execute($q)) {
            force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
            exit;
        }      

        // build query
        $q = "SELECT count(*) as count FROM ".PRFX."TABLE_SCHEDULE WHERE WORK_ORDER_ID='".$workorder_id."'";
        if(!$rs = $db->execute($q)) {
            force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
            exit;
        }

        $count = $rs->fields['count'];

        if($count != 0) {
            $sql = "UPDATE ".PRFX."TABLE_SCHEDULE SET ";
            $where = " WHERE WORK_ORDER_ID='".$workorder_id."'";
        } else {
            $sql = "INSERT INTO ".PRFX."TABLE_SCHEDULE SET ";
        }
    } else {
        $sql = "INSERT INTO ".PRFX."TABLE_SCHEDULE SET ";
    }    

        
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

######################################################################################
# display the current date for the schedule you are currently on from year/month/day #
######################################################################################

function convert_year_month_day_to_date($schedule_start_year, $schedule_start_month, $schedule_start_day) {
    
        switch(DATE_FORMAT) {
            
            case '%d/%m/%Y':
            return $schedule_start_day."/".$schedule_start_month."/".$schedule_start_year;

            case '%d/%m/%y':
            return $schedule_start_day."/".$schedule_start_month."/".substr($schedule_start_year, 2);

            case '%m/%d/%Y':
            return $schedule_start_month."/".$schedule_start_day."/".$schedule_start_year;

            case '%m/%d/%y':
            return $schedule_start_month."/".$schedule_start_day."/".substr($schedule_start_year, 2);
            
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
#   Build Calender Matrix                           #
#####################################################

function build_calendar_matrix($db, $schedule_start_year, $schedule_start_month, $schedule_start_day, $employee_id, $workorder_id = null) {
    
    // Get current schedule date in DATE_FORMAT
    $current_schedule_date = convert_year_month_day_to_date($schedule_start_year, $schedule_start_month, $schedule_start_day);
    
    // Create schedule calendar time range to be displayed, Office hours only -  (unix timestamp)
    $business_day_start = mktime(get_setup_info($db, 'OFFICE_HOUR_START'),0,0,$schedule_start_month,$schedule_start_day,$schedule_start_year);
    $business_day_end   = mktime(get_setup_info($db, 'OFFICE_HOUR_END'),0,0,$schedule_start_month,$schedule_start_day,$schedule_start_year);

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

    // Set Calendar Initial Values for the build loop
    $i = 0;
    $matrixStartTime = $business_day_start;

    // Open the Calendar Matrix Table
    $calendar .= "<table cellpadding=\"0\" cellspacing=\"0\" class=\"olotable\">\n
        <tr>\n
            <td class=\"olohead\" width=\"75\">&nbsp;</td>\n
            <td class=\"olohead\" width=\"600\">&nbsp;</td>\n
        </tr>\n";    
    
    

    /* Build the Calendar Matrix Table content */
    
    while($matrixStartTime <= $business_day_end){

        // If segment time is an hour with no minutes - Build the ROWS with no minutes (i.e. 11.00 , 23.00)
        if(date("i",$matrixStartTime) == 0) {

            // Start ROW and build time CELL
            $calendar .= "<tr><td class=\"olotd\" nowrap>&nbsp;<b>".date("h:i a", $matrixStartTime)."</b></td>\n";

            // if the segment is within the range of the schedule item
            if($matrixStartTime >= $scheduleObject[$i]['SCHEDULE_START'] && $matrixStartTime <= $scheduleObject[$i]['SCHEDULE_END']){

                // if the segment is the same as the schedule item's start time
                if($matrixStartTime == $scheduleObject[$i]['SCHEDULE_START']){
                    
                    // if the schedule item has a workorder_id build this CELL
                    if($scheduleObject[$i]['WORK_ORDER_ID'] != 0) {
                        $calendar .= "<td class=\"menutd2\" align=\"center\" onClick=\"window.location='?page=workorder:details&workorder_id=".$scheduleObject[$i]['WORK_ORDER_ID']."page_title=Work Order ID ".$scheduleObject[$i]['WORK_ORDER_ID ']."'\"><b>\n";
                        $calendar .= " <b><font color=\"red\">Work Order ". $scheduleObject[$i]['WORK_ORDER_ID']." for ". $scheduleObject[$i]['CUSTOMER_NAME']."<br>".date("h:i a",$scheduleObject[$i]['SCHEDULE_START'])." - ".date("h:i a",$scheduleObject[$i]['SCHEDULE_END'])."</font><br><font color=\"blue\">NOTES-  ".$scheduleObject[$i]['SCHEDULE_NOTES']."</font><br>
                        <a href=\"index.php?page=schedule:edit&schedule_id=".$scheduleObject[$i]['SCHEDULE_ID']."&schedule_start_year=".$schedule_start_year."&schedule_start_month=".$schedule_start_month."&schedule_start_day=".$schedule_start_day."&workorder_id=".$scheduleObject[$i]['WORK_ORDER_ID']."\">Edit Note</a> -
                        <a href=\"index.php?page=schedule:sync&workorder_id=".$scheduleObject[$i]['WORK_ORDER_ID']."&theme=off\">Sync</a> -
                        <a href=\"index.php?page=schedule:delete&schedule_id=".$scheduleObject[$i]['SCHEDULE_ID']."&schedule_start_year=".$schedule_start_year."&schedule_start_month=".$schedule_start_month."&schedule_start_day=".$schedule_start_day."&workorder_id=".$scheduleObject[$i]['WORK_ORDER_ID']."\">Delete</a>\n";
                        $calendar . "</b></td>\n";
                    
                    // if schedule has no workorder_id build this CELL      
                    } else {
                        $calendar .= "<td class=\"menutd2\" align=\"center\" onClick=\"window.location='?page=schedule:view&schedule_id=".$scheduleObject[$i]['SCHEDULE_ID']."&schedule_start_year=".$schedule_start_year."&schedule_start_month=".$schedule_start_month."&schedule_start_day=".$schedule_start_day."'\">";
                        $calendar .= " <b><font color=\"red\">Work Order ". $scheduleObject[$i]['WORK_ORDER_ID']."for ". $scheduleObject[$i]['CUSTOMER_NAME']."<br>".date("h:i a",$scheduleObject[$i]['SCHEDULE_START'])." - ".date("h:i a",$scheduleObject[$i]['SCHEDULE_END'])."</font><br><font color=\"blue\">NOTES-  ".$scheduleObject[$i]['SCHEDULE_NOTES']."</font><br>
                        <a href=\"index.php?page=schedule:edit&schedule_id=".$scheduleObject[$i]['SCHEDULE_ID']."&schedule_start_year=".$schedule_start_year."&schedule_start_month=".$schedule_start_month."&schedule_start_day=".$schedule_start_day."&workorder_id=".$scheduleObject[$i]['WORK_ORDER_ID']."\">Edit Note</a> -
                        <a href=\"index.php?page=schedule:sync&workorder_id=".$scheduleObject[$i]['WORK_ORDER_ID']."&theme=off\">Sync</a> -
                        <a href=\"index.php?page=schedule:delete&schedule_id=".$scheduleObject[$i]['SCHEDULE_ID']."&schedule_start_year=".$schedule_start_year."&schedule_start_month=".$schedule_start_month."&schedule_start_day=".$schedule_start_day."&workorder_id=".$scheduleObject[$i]['WORK_ORDER_ID']."\">Delete</a>\n";
                        $calendar . "</b></td>\n";
                    }

                } else {                
                    $calendar .= "<td class=\"menutd2\">&nbsp;</td>\n";
                }
                
            // If no schedule object build empty CELL
            } else {            
                //$calendar .= "<td class=\"olotd\" onClick=\"window.location='?page=schedule:new&schedule_start_time=".date("h:i a", $matrixStartTime)."&schedule_start_date=".$current_schedule_date."&workorder_id=".$workorder_id."&employee_id=".$employee_id."'\"></td>\n";
                  $calendar .= "<td class=\"olotd\" onClick=\"window.location='?page=schedule:new&schedule_start_time=".date("h:i a", $matrixStartTime)."&schedule_start_year=".$schedule_start_year."&schedule_start_month=".$schedule_start_month."&schedule_start_day=".$schedule_start_day."&workorder_id=".$workorder_id."&employee_id=".$employee_id."'\"></td>\n";
            }

            $calendar .= "</tr>";           
            
            
            
        /* Build the ROWS with minutes (i.e. 11.15 , 23.15) */
            
        } else {

            $calendar .= "<tr>\n<td></td>\n";
            
            // if the segment is within the range of the schedule item
            if($matrixStartTime >= $scheduleObject[$i]['SCHEDULE_START'] && $matrixStartTime <= $scheduleObject[$i]['SCHEDULE_END']){

                // if the segment is the same as the schedule item's start time
                if($matrixStartTime == $scheduleObject[$i]['SCHEDULE_START']) {

                    // if the schedule item has a workorder_id build this CELL
                    if($scheduleObject[$i]['WORK_ORDER_ID'] != 0) {
                        $calendar .= "<td class=\"menutd2\" align=\"center\" onClick=\"window.location='?page=workorder:details&workorder_id=".$scheduleObject[$i]['WORK_ORDER_ID']."page_title=Work Order ID ".$scheduleObject[$i]['WORK_ORDER_ID ']."'\"><b>\n";
                        $calendar .= " <b><font color=\"red\">Work Order ". $scheduleObject[$i]['WORK_ORDER_ID']." for ". $scheduleObject[$i]['CUSTOMER_NAME']."<br>".date("h:i a",$scheduleObject[$i]['SCHEDULE_START'])." - ".date("h:i a",$scheduleObject[$i]['SCHEDULE_END'])."</font><br><font color=\"blue\">NOTES-  ".$scheduleObject[$i]['SCHEDULE_NOTES']."</font><br>
                        <a href=\"index.php?page=schedule:edit&schedule_id=".$scheduleObject[$i]['SCHEDULE_ID']."&schedule_start_year=".$schedule_start_year."&schedule_start_month=".$schedule_start_month."&schedule_start_day=".$schedule_start_day."&workorder_id=".$scheduleObject[$i]['WORK_ORDER_ID']."\">Edit Note</a> -
                        <a href=\"index.php?page=schedule:sync&workorder_id=".$scheduleObject[$i]['WORK_ORDER_ID']."&theme=off\">Sync</a> -
                        <a href=\"index.php?page=schedule:delete&schedule_id=".$scheduleObject[$i]['SCHEDULE_ID']."&schedule_start_year=".$schedule_start_year."&schedule_start_month=".$schedule_start_month."&schedule_start_day=".$schedule_start_day."&workorder_id=".$scheduleObject[$i]['WORK_ORDER_ID']."\">Delete</a>\n";
                        $calendar . "</b></td>\n";
                        
                    // if schedule has no workorder_id build this CELL    
                    } else {
                        $calendar .= "<td class=\"menutd2\" align=\"center\" onClick=\"window.location='?page=schedule:view&schedule_id=".$scheduleObject[$i]['SCHEDULE_ID']."&schedule_start_year=".$schedule_start_year."&schedule_start_month=".$schedule_start_month."&schedule_start_day=".$schedule_start_day."'\">";
                        $calendar .= " <b><font color=\"red\">Work Order ". $scheduleObject[$i]['WORK_ORDER_ID']." for ". $scheduleObject[$i]['CUSTOMER_NAME']."<br>".date("h:i a",$scheduleObject[$i]['SCHEDULE_START'])." - ".date("h:i a",$scheduleObject[$i]['SCHEDULE_END'])."</font><br><font color=\"blue\">NOTES-  ".$scheduleObject[$i]['SCHEDULE_NOTES']."</font><br>
                        <a href=\"index.php?page=schedule:edit&schedule_id=".$scheduleObject[$i]['SCHEDULE_ID']."&schedule_start_year=".$schedule_start_year."&schedule_start_month=".$schedule_start_month."&schedule_start_day=".$schedule_start_day."&workorder_id=".$scheduleObject[$i]['WORK_ORDER_ID']."\">Edit Note</a> -
                        <a href=\"index.php?page=schedule:sync&workorder_id=".$scheduleObject[$i]['WORK_ORDER_ID']."&theme=off\">Sync</a> -
                        <a href=\"index.php?page=schedule:delete&schedule_id=".$scheduleObject[$i]['SCHEDULE_ID']."&schedule_start_year=".$schedule_start_year."&schedule_start_month=".$schedule_start_month."&schedule_start_day=".$schedule_start_day."&workorder_id=".$scheduleObject[$i]['WORK_ORDER_ID']."\">Delete</a>\n";
                        $calendar . "</b></td>\n";
                    }

                }  else {
                    $calendar .= "<td class=\"menutd2\"><br></td>\n</tr>";
                }
                
            // If no schedule object build empty CELL
            } else {
               
                // This builds the cell with the time in it (11.15am) and controls the clickable link - i think when no schedule present in this time slot
                //$calendar .= "<td class=\"olotd4\" onClick=\"window.location='?page=schedule:new&schedule_start_time=".date("h:i a", $matrixStartTime)."&schedule_start_date=".$current_schedule_date."&workorder_id=".$workorder_id."&employee_id=".$employee_id."'\">&nbsp; ".date("h:i a", $matrixStartTime)."</td>\n</tr>";
                $calendar .= "<td class=\"olotd4\" onClick=\"window.location='?page=schedule:new&schedule_start_date={$current_schedule_date}&schedule_start_year={$schedule_start_year}&schedule_start_month={$schedule_start_month}&schedule_start_day={$schedule_start_day}&schedule_start_time=".date("h:i a", $matrixStartTime)."&workorder_id=".$workorder_id."&employee_id=".$employee_id."'\">&nbsp; ".date("h:i a", $matrixStartTime)."</td>\n</tr>";
                //$calendar .= "<td class=\"olotd4\" onClick=\"window.location='?page=schedule:new&schedule_start_time=".date("h:i a", $matrixStartTime)."&schedule_start_year=".$schedule_start_year."&schedule_start_month=".$schedule_start_month."&schedule_start_day=".$schedule_start_day."&workorder_id=".$workorder_id."&employee_id=".$employee_id."'\"></td>\n</tr>";
            }

        }        
        
        /* Loop Advancement */
        
        // If schedule item's end time has been reached advance the scehdule item counter to allow processing of multiple schedule items
        if($matrixStartTime == $scheduleObject[$i]['SCHEDULE_END']) {
            $i++;
        }

        // Advance matrixStartTime by 15 minutes before restarting loop to create 15 minute segements
        $matrixStartTime = mktime(date("H",$matrixStartTime),date("i",$matrixStartTime)+15,0,$schedule_start_month,$schedule_start_day,$schedule_start_year);

    }

    // Close the Calendar Matrix Table
    $calendar .= "\n</table>";    
    
    // Return Calender HTML Matrix
    return $calendar;
}

#####################################################
#  Ensure Date passed is in the correct date format #
#####################################################

function set_date_to_correct_date_format($db, $date_to_convert) {
    
    switch(DATE_FORMAAT) {
        case '%d/%m/%Y':  
        echo '1';
        return DateTime::createFromFormat('d/m/Y', $date_to_convert)->getTimestamp();
        
        case '%d/%m/%y':  
        echo '2';
        return DateTime::createFromFormat('d/m/y', $date_to_convert)->getTimestamp();
        
        case '%m/%d/%Y':     
        echo '3';
        return DateTime::createFromFormat('m/d/Y', $date_to_convert)->getTimestamp();

        case '%m/%d/%y': 
        echo '4';
        return DateTime::createFromFormat('m/d/y', $date_to_convert)->getTimestamp(); 
        
    }     
    
}

##########################################
#     Timestamp to dates                 #
##########################################

// Create Timestamp for Start Time including Hours and Minutes
// only used in schedule at the minute

function datetime_to_timestamp($date, $hour, $minute, $second = '0', $clock = '24', $meridian = null) {
    
    // When using a 12 hour clock
    if($clock == '12') {
        
        // Create timestamp from date
        $timestamp = date_to_timestamp($date);

        // if hour is 12am set hour as 0 - for correct calculation as no zero hour
        if($hour == '12' && $meridian == 'am'){$hour = '0';}

        // Convert hours into seconds and then add - AM/PM aware
        if($meridian == 'pm'){$timestamp += ($hour * 60 * 60 + 43200 );} else {$timestamp += ($hour * 60 * 60);}    

        // Convert minutes into seconds and add
        $timestamp += ($minute * 60);
        
        // Add seconds
        $timestamp += $second;        
        
        return $timestamp;
    }
    
    // When using a 24 hour clock
    if($clock == '24') {
        
        // Create timestamp from date
        $timestamp = date_to_timestamp($date);        

        // Convert hours into seconds and then add
        $timestamp += ($hour * 60 * 60 );

        // Convert minutes into seconds and add
        $timestamp += ($minute * 60);
        
        // Add seconds
        $timestamp += $second;        
        
        return $timestamp;
    }
    
}