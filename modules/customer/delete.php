<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/customer.php');

// check if we have a customer_id
if($customer_id == ''){
    force_page('core', 'error', 'error_msg=No Customer ID supplied.');
    exit;
}

// run the delete function and return the results
if(!delete_customer($db, $customer_id)) {
    force_page('customer', 'details&customer_id='.$customer_id);
    exit;    
} else {
    force_page('customer', 'search');
    exit;
}