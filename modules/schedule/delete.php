<?php

require(INCLUDES_DIR.'modules/schedule.php');

// Make sure we got an Expense ID number
if(!$schedule_id) {    
    force_page('schedule', 'main', 'warning_msg=Please go back and select a schedule to delete');
    exit;
}
  
if(delete_schedule($db, $schedule_id)) {
    force_page('schedule', 'main','information_msg=Schedule has been deleted');
    exit;
}


