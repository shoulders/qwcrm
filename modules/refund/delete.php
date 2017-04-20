<?php

require(INCLUDES_DIR.'modules/refund.php');


// Make sure we got an Refund ID number
if($refund_id == '') {
    $smarty->assign('results', 'Please go back and select an refund record');
    die;
}    

// Delete the refund function call
if(!delete_refund($db,$refund_id)) {
        force_page('core', 'error','error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
} else {
        force_page('refund', 'details');
        exit;
}