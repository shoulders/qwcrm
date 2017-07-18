<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/giftcert.php');

// Prevent direct access to this file
if(!check_page_accessed_via_qwcrm()) {
    force_page('giftcert', 'search', 'warning_msg='.gettext("No Direct Access Allowed"));
}

// Check if we have an giftcert_id
if($giftcert_id == '') {
    force_page('giftcert', 'search', 'warning_msg='.gettext("No Gift Certificate ID supplied."));
    exit;
}

// Delete the Gift Certificate - the giftcert is only deactivated
delete_giftcert($db, $giftcert_id);
    
// Reload the customers details page
force_page('customer', 'details&customer_id='.get_giftcert_details($db, $giftcert_id, 'CUSTOMER_ID'), 'information_msg='.gettext("Gift Certificate blocked successfully."));
exit;