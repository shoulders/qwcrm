<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/refund.php');

// Check if we have a refund_id
if($refund_id == '') {
    force_page('refund', 'search', 'warning_msg='.gettext("No Refund ID supplied."));
    exit;
} 

// Assign the arrays
$smarty->assign('refund_details', get_refund_details($db, $refund_id));
$BuildPage .= $smarty->fetch('refund/details.tpl');