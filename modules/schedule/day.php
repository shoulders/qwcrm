<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/schedule.php');
require(INCLUDES_DIR.'modules/user.php');
require(INCLUDES_DIR.'modules/workorder.php');

// Check the workorder status - We don't want to schedule/reschedule a workorder if it's closed
if(isset($workorder_id)) { 
    
    // If the workorder is closed, remove the workorder_id preventing further schedule creation for this workorder_id
    if(!check_workorder_is_open($db, $workorder_id)) {        
        $smarty->assign('warning_msg', gettext("Can not set a schedule for closed work orders - Work Order ID").' '.$workorder_id);
        unset($workorder_id);
    }
    
}

// if no employee_id set, use the logged in user
if(!$employee_id) {$employee_id = $login_user_id;}

// Assign the variables
$smarty->assign('start_year',               $start_year                                                                                                 );
$smarty->assign('start_month',              $start_month                                                                                                );
$smarty->assign('start_day',                $start_day                                                                                                  );
$smarty->assign('selected_date',            timestamp_to_calendar_format(convert_year_month_day_to_timestamp($start_year, $start_month, $start_day))    );
$smarty->assign('employees',                get_active_users($db, 'employees')                                                                          );  
$smarty->assign('current_schedule_date',    convert_year_month_day_to_timestamp($start_year, $start_month, $start_day)                                  );
$smarty->assign('calendar',                 build_calendar_matrix($db, $start_year, $start_month, $start_day, $employee_id, $workorder_id)              );
$smarty->assign('selected_employee',        $employee_id                                                                                                );

// Build the page
$BuildPage .= $smarty->fetch('schedule/day.tpl');