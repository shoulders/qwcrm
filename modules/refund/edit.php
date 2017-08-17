<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/refund.php');

// Check if we have a refund_id
if($refund_id == '') {
    force_page('refund', 'search', 'warning_msg='.gettext("No Refund ID supplied."));
    exit;
} 

// If details submitted run update values, if not set load edit.tpl and populate values
if(isset($VAR['submit'])) {    
        
    // Update the refund in the database
    update_refund($db, $refund_id, $VAR);
    
    // load details page
    force_page('refund', 'details&refund_id='.$refund_id);
}   

// Build the page
$smarty->assign('refund_details', get_refund_details($db, $refund_id));
$BuildPage .= $smarty->fetch('refund/edit.tpl');
