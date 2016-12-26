<?php

require(INCLUDES_DIR.'modules/schedule.php');

// No Workorder Specified
if ($workorder_id == '') { 

    // Should this be handled in a function?    
    force_page('workorder', 'open','warning_msg=ERROR : There was no Works Order number to schedule specified. Please select the works order from the below list and then schedule.');
    exit;
    
}

// If new schedule item submitted
if(isset($VAR['submit'])) {
    
    // If db insert fails send them an error and reload the page with submitted info or load the page with the schedule
    if (!insert_new_schedule($db, $VAR['schedule_start_date'], $VAR['scheduleStartTime'], $VAR['schedule_end_date'], $VAR['scheduleEndTime'], $VAR['schedule_notes'], $employee_id, $workorder_id)) {        
                 
        $smarty->assign('schedule_start_date',      $VAR['schedule_start_date']                                                                                                         );       
        $smarty->assign('schedule_start_time',      $VAR['scheduleStartTime']['Time_Hour'].":".$VAR['scheduleStartTime']['Time_Minute']." ".$VAR['scheduleStartTime']['Time_Meridian']  );                
        $smarty->assign('schedule_end_date',        $VAR['schedule_end_date']                                                                                                           );        
        $smarty->assign('schedule_end_time',        $VAR['scheduleEndTime']['Time_Hour'].":".$VAR['scheduleEndTime']['Time_Minute']." ".$VAR['scheduleEndTime']['Time_Meridian']        );
        $smarty->assign('schedule_notes',           $VAR['schedule_notes']                                                                                                              );       
        $smarty->assign('employee_id',              $employee_id                                                                                                                        );
        $smarty->assign('workorder_id',             $workorder_id                                                                                                                       );
        
        $smarty->display('schedule/new.tpl'                                                                                                                                             );            
            
    } else {       
            
        // Get the current schedule Year, Month and Day from the Date
        $schedule_start_date_timestamp  = date_to_timestamp($VAR['schedule_start_date'] );
        $schedule_start_year            = date('Y', $schedule_start_date_timestamp      );
        $schedule_start_month           = date('m', $schedule_start_date_timestamp      );
        $schedule_start_day             = date('d', $schedule_start_date_timestamp      );    
    
        // Load the schedule day with the newly submitted schdule item
        force_page('schedule','main', 'schedule_start_year='.$schedule_start_year.'&schedule_start_month='.$schedule_start_month.'&schedule_start_day='.$schedule_start_day.'&employee_id='.$employee_id.'&workorder_id='.$workorder_id.'&page_title=schedule');
        exit;
    }

// If new schedule form is intially loaded
} else {
    
    $smarty->assign('schedule_start_date',          convert_year_month_day_to_date($schedule_start_year, $schedule_start_month, $schedule_start_day)    );
    $smarty->assign('schedule_start_time',          $VAR['schedule_start_time']                                                                         );    
    $smarty->assign('schedule_end_date',            convert_year_month_day_to_date($schedule_start_year, $schedule_start_month, $schedule_start_day)    );
    $smarty->assign('schedule_end_time',            $VAR['schedule_start_time']                                                                         );    
    $smarty->assign('employee_id',                  $employee_id                                                                                        );
    $smarty->assign('workorder_id',                 $workorder_id                                                                                       );    

    $smarty->display('schedule/new.tpl');
}