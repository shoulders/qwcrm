<?php

require(INCLUDES_DIR.'modules/employee.php');
require(INCLUDES_DIR.'modules/schedule.php');
require(INCLUDES_DIR.'modules/workorder.php');

// No Workorder Specified
if ($workorder_id == '') { 
    // Should this be handled in a function?    
    force_page('workorder', 'overview','warning_msg=ERROR : There was no Works Order number to schedule specified. Please select the works order from the below list and then schedule.');
    exit;    
}

// Get customer_id - if not set, might not be needed
if($customer_id == '') {$customer_id =  get_workorder_details($db, $workorder_id, 'CUSTOMER_ID');}

// If new schedule item submitted
if(isset($VAR['submit'])) {
    
    // If db insert fails send them an error and reload the page with submitted info or load the page with the schedule
    if (!insert_schedule($db, $VAR['schedule_start_date'], $VAR['scheduleStartTime'], $VAR['schedule_end_date'], $VAR['scheduleEndTime'], $VAR['schedule_notes'], $employee_id, $customer_id, $workorder_id)) {        
                 
        $smarty->assign('schedule_start_date',      $VAR['schedule_start_date']                                                           );       
        $smarty->assign('schedule_start_time',      $VAR['scheduleStartTime']['Time_Hour'].":".$VAR['scheduleStartTime']['Time_Minute']   );              
        $smarty->assign('schedule_end_date',        $VAR['schedule_end_date']                                                             );        
        $smarty->assign('schedule_end_time',        $VAR['scheduleEndTime']['Time_Hour'].":".$VAR['scheduleEndTime']['Time_Minute']       );
        $smarty->assign('schedule_notes',           $VAR['schedule_notes']                                                                );                
            
    } else {       
            
        // Load the schedule day with the updated schedule item        
        $schedule_start_year            = date('Y', date_to_timestamp($VAR['schedule_start_date'])  );
        $schedule_start_month           = date('m', date_to_timestamp($VAR['schedule_start_date'])  );
        $schedule_start_day             = date('d', date_to_timestamp($VAR['schedule_start_date'])  );    
    
        // Load the schedule day with the newly submitted schedule item
        force_page('schedule', 'day', 'schedule_start_year='.$schedule_start_year.'&schedule_start_month='.$schedule_start_month.'&schedule_start_day='.$schedule_start_day.'&employee_id='.$employee_id.'&workorder_id='.$workorder_id.'&page_title=schedule&information_msg=Schedule Successfully Created');
        exit;
    }

// If new schedule form is intially loaded, load schedule item from the database and assign
} else {
    
    $smarty->assign('schedule_start_date',          convert_year_month_day_to_date($schedule_start_year, $schedule_start_month, $schedule_start_day)    );
    $smarty->assign('schedule_start_time',          $VAR['schedule_start_time']                                                                         );    
    $smarty->assign('schedule_end_date',            convert_year_month_day_to_date($schedule_start_year, $schedule_start_month, $schedule_start_day)    );
    $smarty->assign('schedule_end_time',            $VAR['schedule_start_time']                                                                         ); 
    
}

// Fetch the page
$BuildPage .= $smarty->fetch('schedule/new.tpl');