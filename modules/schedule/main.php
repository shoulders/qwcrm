<?php
require('include.php');

// check if workorder status we don't want to reschedule a work order if it's closed
if(isset($workorder_id)) {    
    check_schedule_workorder_status($db, $workorder_id);
}

// is used to build swapping employee schedule feature
//$smarty->assign('scheduleDateArray',        array('schedule_start_year'=>$VAR['schedule_start_year'], 'schedule_start_day'=>$VAR['schedule_start_day'], 'schedule_start_month'=>$VAR['schedule_start_month'], 'workorder_id'=>$workorder_id));

$smarty->assign('schedule_start_year',      $VAR['schedule_start_year']                                                                                                                     );
$smarty->assign('schedule_start_month',     $VAR['schedule_start_month']                                                                                                                    );
$smarty->assign('schedule_start_day',       $VAR['schedule_start_day']                                                                                                                      );
$smarty->assign('employees',                display_employees_info($db)                                                                                                                     );  
$smarty->assign('current_schedule_date',    convert_year_month_day_to_date($VAR['schedule_start_year'], $VAR['schedule_start_month'], $VAR['schedule_start_day'])                           );
$smarty->assign('calendar',                 build_calendar_matrix($db, $VAR['schedule_start_year'], $VAR['schedule_start_month'], $VAR['schedule_start_day'], $employee_id, $workorder_id)  );
$smarty->assign('selected',                 $employee_id                                                                                                                                    );

$smarty->display('schedule/main.tpl');