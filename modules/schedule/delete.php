<?php

require(INCLUDES_DIR.'modules/schedule.php');

// should these be here or in the main index.php
$y =    $VAR['y'];
$m =    $VAR['m'];
$d =    $VAR['d'];

$q = "DELETE FROM ".PRFX."TABLE_SCHEDULE WHERE SCHEDULE_ID =".$db->qstr($schedule_id);

    if(!$rs = $db->execute($q)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    } else {
        force_page('schedule', 'main&schedule_start_year='.$y.'&schedule_start_month='.$m.'&schedule_start_day='.$d.'&workorder_id='.$workorder_id);
        exit;
    }