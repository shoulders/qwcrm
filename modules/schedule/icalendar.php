<?php

require(INCLUDES_DIR.'modules/customer.php');
require(INCLUDES_DIR.'modules/schedule.php');
require(INCLUDES_DIR.'modules/workorder.php');

// add routines here to decide what is returned ie multi schedule, single schedule or a live calendar 

// set the filename
if($VAR['ics_type'] == 'day') {
    $filename = 'EmployeeID-'.$employee_id.'-Date-'.$schedule_start_year.$schedule_start_month.$schedule_start_day.'.ics';
} else {
    //$filename   = str_replace(' ', '_', $single_workorder['0']['CUSTOMER_DISPLAY_NAME']).'-Workorder-'.$single_schedule['0']['WORKORDER_ID'].'-Schedule-'.$schedule_id.'.ics';
    $filename   = 'schedule.ics';    
}

// Set the correct headers for this file
header('Content-type: text/calendar; charset=utf-8');
header('Content-Disposition: attachment; filename=' . $filename);

// Output the .ics file

// Build Schedule day for employee as an .ics
if($VAR['ics_type'] == 'day') {
    echo build_ics_schedule_day($db, $employee_id, $schedule_start_year, $schedule_start_month, $schedule_start_day);
    
// Output just the single schedule item as an .ics
} else {
    echo build_single_schedule_ics($db, $schedule_id);
}
