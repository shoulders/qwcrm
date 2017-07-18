<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/customer.php');

// check if we have a customer_note_id
if($VAR['customer_note_id'] == '') {
    force_page('customer', 'search', 'warning_msg='.gettext("No Customer Note ID supplied."));
    exit;
}

// Get the customer_id before we delete the record
$customer_id = get_customer_note($db, $VAR['customer_note_id'], 'CUSTOMER_ID');

// Delete the customer note
delete_customer_note($db, $VAR['customer_note_id']);

// Reload the customers details page
force_page('customer', 'details&customer_id='.$customer_id);