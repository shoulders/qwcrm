<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/schedule.php');
require(INCLUDES_DIR.'modules/employee.php');
require(INCLUDES_DIR.'modules/workorder.php');

// Check the workorder status - We don't want to schedule/reschedule a workorder if it's closed
if(isset($workorder_id)) { 
    
    // If the workorder is closed, remove the workorder_id preventing further schedule creation for this workorder_id
    if(!check_workorder_is_open($db, $workorder_id)) {        
        $smarty->assign('warning_msg', 'Can not set a schedule for closed work orders - Work Order ID '.$workorder_id);
        unset($workorder_id);
    }
    
}

// Assign the variables
$smarty->assign('schedule_start_year',      $schedule_start_year                                                                                                                );
$smarty->assign('schedule_start_month',     $schedule_start_month                                                                                                               );
$smarty->assign('schedule_start_day',       $schedule_start_day                                                                                                                 );
$smarty->assign('selected_date',            timestamp_to_calendar_format(convert_year_month_day_to_timestamp($schedule_start_year, $schedule_start_month, $schedule_start_day)) );
$smarty->assign('employees',                get_active_employees($db)                                                                                                           );  
$smarty->assign('current_schedule_date',    convert_year_month_day_to_timestamp($schedule_start_year, $schedule_start_month, $schedule_start_day)                               );
$smarty->assign('calendar',                 build_calendar_matrix($db, $schedule_start_year, $schedule_start_month, $schedule_start_day, $employee_id, $workorder_id)           );
$smarty->assign('selected_employee',        $employee_id                                                                                                                        );

// Fetch the page
$BuildPage .= $smarty->fetch('schedule/day.tpl');