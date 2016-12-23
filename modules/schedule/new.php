<?php
require_once ("include.php");
if(!xml2php("schedule")) {
    $smarty->assign('error_msg',"Error in language file");
}

// Schedule Due Date
$date_part2 = explode("/",$VAR['schedule_start_date']);
//$timestamp2 = mktime(0,0,0,$date_part2[1],$date_part2[0],$date_part2[2]);
if(DATE_FORMAT == "%d/%m/%Y"){$cur_date = $d."/".$m."/".$Y;}
if(DATE_FORMAT == "%m/%d/%Y"){$cur_date = $m."/".$d."/".$Y;}


$smarty->assign('Y',$Y);
$smarty->assign('m',$m);
$smarty->assign('d',$d);

// display Workorders for a given status
$smarty->assign('new',      display_workorders($db, $page_no, 1)    );
$smarty->assign('assigned', display_workorders($db, $page_no, 2)    );
$smarty->assign('awaiting', display_workorders($db, $page_no, 3)    );


if(isset($VAR['submit'])) {

    if (!insert_new_schedule($db, $workorder_id, $employee_id, $VAR['scheduleStart'], $VAR['scheduleEnd'], $VAR['scheduleNotes'])) {
        
        // If db insert fails send them an error and reload the page with submitted info           
        $smarty->assign('schedule_start_date',      $VAR['scheduleStart']['date']                                                                                           );
        $smarty->assign('schedule_start_time',      $VAR['scheduleStart']['Time_Hour'].":".$VAR['scheduleStart']['Time_Minute']." ".$VAR['scheduleStart']['Time_Meridian']  );
        $smarty->assign('schedule_end_date',        $VAR['scheduleEnd']['date']                                                                                           );
        $smarty->assign('schedule_end_time',        $VAR['scheduleEnd']['Time_Hour'].":".$VAR['scheduleEnd']['Time_Minute']." ".$VAR['scheduleEnd']['Time_Meridian']        );
        $smarty->assign('schedule_notes',           $VAR['scheduleNotes']                                                                                                   );
        $smarty->assign('workorder_id',             $workorder_id                                                                                                           );
        $smarty->assign('employee_id',              $login_id                                                                                                               );

        $smarty->display('schedule/new.tpl'                                                                                                                                 );            
            
    } else {
        
        // Display the page with the newly submitted Schedule
        list($schedule_start_month, $schedule_start_day, $schedule_start_year)  = split('[/.-]', $VAR['scheduleStart']['date']);
        force_page('schedule','main&y='.$schedule_start_year.'&d='.$schedule_start_month.'&m='.$schedule_start_day.'&workorder_id='.$workorder_id.'&page_title=schedule&employee_id='.$employee_id);
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

    $smarty->assign('workorder_id',         $workorder_id                   );
    $smarty->assign('employee_id',          $employee_id                    );
    $smarty->assign('schedule_start_date',  $VAR['schedule_start_date']     );
    $smarty->assign('schedule_start_time',  $VAR['schedule_start_time']     );    
    $smarty->assign('schedule_end_date',    $VAR['schedule_start_date']     ); // Prefills the start date making date selection easier
    $smarty->assign('schedule_end_time',    $VAR['schedule_start_time']     ); // Uses the start time for the end time making time selection easier
    
    $smarty->display('schedule/new.tpl');
}