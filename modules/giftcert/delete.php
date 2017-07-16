<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/giftcert.php');

// Make sure there is a giftcert_id
if($giftcert_id == '') {
    force_page('core', 'error', 'error_msg=No Gift Certificate ID');
    exit;
}

// Delete the Gift Certificate - the giftcert is only deactivated
delete_giftcert($db, $giftcert_id);
    
// Reload the customers details page
force_page('customer', 'details&customer_id='.get_giftcert_details($db, $giftcert_id, 'CUSTOMER_ID'), 'information_msg=Gift Certificate blocked successfully.');