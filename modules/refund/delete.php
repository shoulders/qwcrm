<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/refund.php');

// Prevent direct access to this file
if(!check_page_accessed_via_qwcrm()) {
    force_page('refund', 'search', 'warning_msg='.gettext("No Direct Access Allowed"));
}

// Check if we have a refund_id
if($refund_id == '') {
    force_page('refund', 'search', 'warning_msg='.gettext("No Refund ID supplied."));
    exit;
} 

// Delete the refund function call
delete_refund($db, $refund_id);

// Load the refund search page
force_page('refund', 'search', 'information_msg='.gettext("Refund deleted successfully."));
exit;
