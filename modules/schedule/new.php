<?php
require_once ('include.php');
if(!xml2php("schedule")) {
    $smarty->assign('error_msg',"Error in language file");
}


if(!$VAR['schedule_start_year']){
    
}


if(isset($VAR['submit'])) {

    if (!insert_new_schedule($db, $VAR['schedule_start_date'], $VAR['scheduleStartTime'], $VAR['schedule_end_date'], $VAR['scheduleEndTime'], $VAR['schedule_notes'], $employee_id, $workorder_id)) {
        
        // If db insert fails send them an error and reload the page with submitted info           
        $smarty->assign('schedule_start_date',              $VAR['schedule_start_date']                                                                                           );
        $smarty->assign('schedule_start_time',              $VAR['scheduleStartTime']['Time_Hour'].":".$VAR['scheduleStart']['Time_Minute']." ".$VAR['scheduleStart']['Time_Meridian']  );
        $smarty->assign('schedule_end_date',                $VAR['schedule_end_date']                                                                                           );
        $smarty->assign('schedule_end_time',                $VAR['scheduleEndTime']['Time_Hour'].":".$VAR['scheduleEnd']['Time_Minute']." ".$VAR['scheduleEnd']['Time_Meridian']        );
        $smarty->assign('schedule_notes',                   $VAR['schedule_notes']                                                                                                   );
        $smarty->assign('workorder_id',                     $workorder_id                                                                                                           );
        $smarty->assign('employee_id',                      $login_id                                                                                                               );
        $smarty->assign('current_schedule_date',            display_current_schedule_date($VAR['schedule_start_year'], $VAR['schedule_start_month'], $VAR['schedule_start_day']));
        
        $smarty->display('schedule/new.tpl'                                                                                                                                 );            
            
    } else {
        
        // Display the page with the newly submitted Schedule
        //list($schedule_start_month, $schedule_start_day, $schedule_start_year)  = split('[/.-]', $VAR['schedule_start_date']);
        //list($schedule_start_day, $schedule_start_month, $schedule_start_year)  = split('[/.-]', $VAR['schedule_start_date']);
        force_page('schedule','main&schedule_start_year='.$schedule_start_year.'&schedule_start_month='.$schedule_start_month.'&schedule_start_day='.$schedule_start_day.'&employee_id='.$employee_id.'&workorder_id='.$workorder_id.'&page_title=schedule');
        exit;
    }

    
} elseif ($workorder_id == '') { 

    // workorder is missing - should this be handled in a function?
    
    //no information has been submitted and the new schedule pages has been accessed directly - currently not supporting full schdule creation you have to go in via a workorder.    
    // loads the open workoreders page with a warning message
    force_page('workorder', 'open','warning_msg=ERROR : There was no Works Order number to schedule specified. Please select the works order from the below list and then schedule.');
    exit;
    
} else {
    
    // if employee_id is empty then i should use the login_id - add code in includes - should this be done in the index.php?

    $smarty->assign('workorder_id',                     $workorder_id                   );
    $smarty->assign('employee_id',                      $employee_id                    );
    $smarty->assign('schedule_start_date',              $VAR['schedule_start_date']     );
    $smarty->assign('schedule_start_time',              $VAR['scheduleStartTime']['Time_Hour'].":".$VAR['scheduleStart']['Time_Minute']." ".$VAR['scheduleStart']['Time_Meridian']     );    
    $smarty->assign('schedule_end_date',                $VAR['schedule_start_date']     ); // Prefills the start date making date selection easier
    $smarty->assign('schedule_end_time',                $VAR['scheduleEndTime']['Time_Hour'].":".$VAR['scheduleEnd']['Time_Minute']." ".$VAR['scheduleEnd']['Time_Meridian']      ); // Uses the start time for the end time making time selection easier
    $smarty->assign('current_schedule_date',    display_current_schedule_date($VAR['schedule_start_year'], $VAR['schedule_start_month'], $VAR['schedule_start_day']));
    
    $smarty->display('schedule/new.tpl');
}