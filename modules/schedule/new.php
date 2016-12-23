<?php
require_once ("include.php");
if(!xml2php("schedule")) {
    $smarty->assign('error_msg',"Error in language file");
}

// Schedule Due Date
$date_part2 = explode("/",$VAR['schedule_date']);
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

    if (!insert_new_schedule($db, $workorder_id, $employee_id, $scheduleStart, $scheduleEnd, $schedule_notes)) {
        
        // If db insert fails send them an error and reload the page with submitted info           
        $smarty->assign('schedule_start_date',      $VAR['scheduleStart']['date']                                                                                           );
        $smarty->assign('schedule_start_time',      $VAR['scheduleStart']['Time_Hour'].":".$VAR['scheduleStart']['Time_Minute']." ".$VAR['scheduleStart']['Time_Meridian']  );
        $smarty->assign('schedule_end_time',        $VAR['scheduleEnd']['Time_Hour'].":".$VAR['scheduleEnd']['Time_Minute']." ".$VAR['scheduleEnd']['Time_Meridian']        );
        $smarty->assign('schedule_notes',           $VAR['scheduleNotes']                                                                                                   );
        $smarty->assign('workorder_id',             $workorder_id                                                                                                           );
        $smarty->assign('employee_id',              $login_id                                                                                                               );

        $smarty->display('schedule/new.tpl'                                                                                                                                 );            
            
    } else {            
        list($scheduleEnd_month, $scheduleEnd_day, $scheduleEnd_year)  = split('[/.-]', $VAR['scheduleStart']['date']);

        force_page('schedule','main&y='.$scheduleEnd_year.'&d='.$scheduleEnd_month.'&m='.$scheduleEnd_day.'&workorder_id='.$workorder_id.'&page_title=schedule&employee_id='.$employee_id);
        exit;
    }

    
} else {
        
        /*$smarty->assign('workorder_id',         $workorder_id           );
        $smarty->assign('login_id',             $employee_id            );
        $smarty->assign('schedule_start_date',  $VAR['schedule_date']   );
        $smarty->assign('schedule_start_time',  $VAR['starttime']       );
        
        $smarty->display('schedule/new.tpl');  */
    
    // no information has been submitted and the new schedule pages has been accessed directly - currently not supporting full schdule creation you have to go in via a workorder.
    
    force_page('workorder', 'open','warning_msg=ERROR : There was no Works Order number to schedule specified. Please select the works order from the below list and then schedule.');
    exit;
}