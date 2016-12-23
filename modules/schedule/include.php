<?php
######################################
# Insert New schedule                #
######################################

function insert_new_schedule($db, $workorder_id, $employee_id, $scheduleStart, $scheduleEnd, $schedule_notes){

    global $smarty;        

    // Get Schdule Start Time values
    list($schedule_start_month, $schedule_start_day, $schedule_start_year) = split('[/.-]', $scheduleStart['date']);
    $schedule_start_hour     = $scheduleStart['Time_Hour'];
    $schedule_start_min      = $scheduleStart['Time_Minute'];
    $schedule_start_ampm     = $scheduleStart['Time_Meridian'];

    // Get Schdule End Time values
    list($schedule_end_month, $schedule_end_day, $schedule_end_year)       = split('[/.-]', $scheduleEnd['date']);
    $schedule_end_hour       = $scheduleEnd['Time_Hour'];
    $schedule_end_min        = $scheduleEnd['Time_Minute'];
    $schedule_end_ampm       = $scheduleEnd['Time_Meridian'];

    // Set 0 seconds for both start and end times
    $secs   = "00";

    // Translate the date and time to a unix timestamp - this includes the additional smarty dropdown variables from the form
    if(DATE_FORMAT == "%d/%m/%Y" || DATE_FORMAT == "%d/%m/%y"){
        $schedule_start_time = strtotime("$schedule_start_day/$schedule_start_month/$schedule_start_year $schedule_start_hour:$schedule_start_min:$secs $schedule_start_ampm");
        $schedule_end_time   = strtotime("$schedule_end_day/$schedule_end_month/$schedule_end_year $schedule_end_hour:$schedule_end_min:$secs $schedule_end_ampm");
    } else if (DATE_FORMAT == "%m/%d/%Y" || DATE_FORMAT == "%m/%d/%y"){
        $schedule_start_time = strtotime("$schedule_start_month/$schedule_start_day/$schedule_start_year $schedule_start_hour:$schedule_start_min:$secs $schedule_start_ampm");
        $schedule_end_time   = strtotime("$schedule_end_month/$schedule_end_day/$schedule_end_year $schedule_end_hour:$schedule_end_min:$secs $schedule_end_ampm"); 
    }

    // If start time is after end time show message and stop further processing
    if($schedule_start_time > $schedule_end_time) {        
        $smarty->assign('warning_msg', 'Schedule ends before it starts.');
        return false;
    }

    // If the start time is the same as the end time show message and stop furhter processing
    if($schedule_start_time == $schedule_end_time) {       
        $smarty->assign('warning_msg', 'Start Time and End Time are the Same');
        return false;
    }

    // Get Todays Schedule
    $todays_schedule_start = mktime(0,0,0,date("m",$schedule_start_time),date("d",$schedule_start_time),date("Y",$schedule_start_time));
    $todays_schedule_end   = mktime(23,59,59,date("m",$schedule_start_time),date("d",$schedule_start_time),date("Y",$schedule_start_time));
    
    $q = "SELECT  SCHEDULE_START,SCHEDULE_END, SCHEDULE_ID  FROM ".PRFX."TABLE_SCHEDULE WHERE SCHEDULE_START >= ".$todays_schedule_start." AND SCHEDULE_END <=".$todays_schedule_end." AND  EMPLOYEE_ID ='".$employee_id."' ORDER BY SCHEDULE_START ASC";
    if(!$rs = $db->Execute($q)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    }

    
    
    
    // not sure what this does
    $counter = 1;

    while (!$rs->EOF){
        //print $schedule_start_time . '>= '.$rs->fields["SCHEDULE_START"].' AND '.$schedule_start_time <= $rs->fields["SCHEDULE_END"].'<br>';

        // Check if start time starts when another is already set
        if($schedule_start_time >= $rs->fields["SCHEDULE_START"] && $schedule_start_time <= $rs->fields["SCHEDULE_END"]) {            
            $smarty->assign('warning_msg', 'Start time starts before another schedule ends<br>');    
            return false;
        }

        // Check if start time starts before one ends
        //print $schedule_end_time.' >= '.$rs->fields["SCHEDULE_START"].' && '.$schedule_start_time.' <= '.$rs->fields["SCHEDULE_START"].'<br>';
        if($schedule_end_time >= $rs->fields["SCHEDULE_START"] && $schedule_start_time <= $rs->fields["SCHEDULE_START"]) {            
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

        
    $sql .="SCHEDULE_START      = '".$schedule_start_time."',
             SCHEDULE_END       = '".$schedule_end_time."',
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
    
function display_employee_info($db){
    
    $q = "SELECT  EMPLOYEE_ID, EMPLOYEE_TYPE, EMPLOYEE_LOGIN FROM ".PRFX."TABLE_EMPLOYEE";
    
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
