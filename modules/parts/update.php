<?php

$workorder_id = $VAR['workorder_id'];

/* update order */
$q = "UPDATE ".PRFX."ORDERS SET STATUS='0' WHERE WO_ID=".$db->qstr($workorder_id);
    if(!$rs = $db->execute($q)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    }

/* update Status that we rec parts */
$memo = "Parts received";
    $q = "INSERT INTO ".PRFX."TABLE_WORK_ORDER_HISTORY SET
                WORK_ORDER_ID       = ".$db->qstr($workorder_id).",
                DATE                = ".$db->qstr(time()).",
                NOTES               = ".$db->qstr($memo).",
                ENTERED_BY          = ".$db->qstr($_SESSION['login_id']);
            
            if(!$rs = $db->execute($q)) {
                force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1');
                exit;
            }
/* check status */
$q = "SELECT WORK_ORDER_STATUS FROM ".PRFX."TABLE_WORK_ORDER WHERE WORK_ORDER_ID=". $db->qstr($workorder_id);
    if(!$rs = $db->execute($q)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    }


if($rs->fields['WORK_ORDER_STATUS'] != '6') {

        /* check if we have a schedule */
        $q = "SELECT count(*) as count  FROM ".PRFX."TABLE_SCHEDULE WHERE WORK_ORDER_ID=".$db->qstr($workorder_id);
            if(!$rs = $db->execute($q)) {
                force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
                exit;
            }
        
        if($rs->fields['count'] == 1) {
            $status ='2';
        } else {
            $status ='1';
        }
        
        $q = "UPDATE ".PRFX."TABLE_WORK_ORDER SET WORK_ORDER_CURRENT_STATUS =".$db->qstr($status).", LAST_ACTIVE=".$db->qstr(time())." WHERE WORK_ORDER_ID = ".$db->qstr($workorder_id) ;
            if(!$rs = $db->execute($q)) {
                force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
                exit;
        }
}
force_page('workorder', 'view&workorder_id='.$workorder_id.'&page_title=Work%20Order%20ID%20'.$workorder_id);