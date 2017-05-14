<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/schedule.php');
require(INCLUDES_DIR.'modules/employee.php');

// If new schedule item submitted
if(isset($VAR['submit'])) {    
    
    // If db insert fails send them an error and reload the page with submitted info or load the page with the schedule
    if (!update_schedule($db, $VAR['schedule_start_date'], $VAR['scheduleStartTime'], $VAR['schedule_end_date'], $VAR['scheduleEndTime'], $VAR['schedule_notes'], $schedule_id, $employee_id, $customer_id, $workorder_id)) {        
        
        $smarty->assign('schedule_start_date',      $VAR['schedule_start_date']                                                             );       
        $smarty->assign('schedule_start_time',      $VAR['scheduleStartTime']['Time_Hour'].":".$VAR['scheduleStartTime']['Time_Minute']     );                
        $smarty->assign('schedule_end_date',        $VAR['schedule_end_date']                                                               );        
        $smarty->assign('schedule_end_time',        $VAR['scheduleEndTime']['Time_Hour'].":".$VAR['scheduleEndTime']['Time_Minute']         );
        $smarty->assign('schedule_notes',           $VAR['schedule_notes']                                                                  );        
        $smarty->assign('active_employees',         get_active_employees($db)                                                               );                      
            
    } else {       
        
        // Load the schedule day with the updated schedule item        
        $schedule_start_year            = date('Y', date_to_timestamp($VAR['schedule_start_date'])  );
        $schedule_start_month           = date('m', date_to_timestamp($VAR['schedule_start_date'])  );
        $schedule_start_day             = date('d', date_to_timestamp($VAR['schedule_start_date'])  );    
    
        // Load the schedule day with the updated schedule item
        force_page('schedule', 'day', 'schedule_start_year='.$schedule_start_year.'&schedule_start_month='.$schedule_start_month.'&schedule_start_day='.$schedule_start_day.'&employee_id='.$employee_id.'&workorder_id='.$workorder_id.'&page_title=schedule&information_msg=Schedule Successfully Updated');
        exit;
        
    }

// If edit schedule form is loaded, get schedule item from the database and assign
} else {
    
    // Get the Schedule Record
    $schedule_item = get_schedule_details($db, $schedule_id);
    
    // Corrects the extra time segment issue    
    $schedule_end_time = $schedule_item['SCHEDULE_END'] + 1;      
    
    $smarty->assign('schedule_start_date',      timestamp_to_date($schedule_item['SCHEDULE_START'])    );       
    $smarty->assign('schedule_start_time',      date('H:i', $schedule_item['SCHEDULE_START'])          );         
    $smarty->assign('schedule_end_date',        timestamp_to_date($schedule_item['SCHEDULE_END'])      );         
    $smarty->assign('schedule_end_time',        date('H:i', $schedule_end_time)                        );   
    $smarty->assign('schedule_notes',           $schedule_item['SCHEDULE_NOTES']                       );
    $smarty->assign('schedule_id',              $schedule_item['SCHEDULE_ID']                          );
    $smarty->assign('customer_id',              $schedule_item['CUSTOMER_ID']                          );
    $smarty->assign('employee_id',              $schedule_item['EMPLOYEE_ID']                          );
    $smarty->assign('active_employees',         get_active_employees($db)                              );
    $smarty->assign('workorder_id',             $schedule_item['WORKORDER_ID']                         ); 
    
}

// Fetch the page
$BuildPage .= $smarty->fetch('schedule/edit.tpl');