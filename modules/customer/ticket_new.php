<?php

// this loads an email page - not sure what it is for

require(INCLUDES_DIR.'modules/customer.php');

// check if we have a customer_id
if($customer_id == ''){
    force_page('core', 'error', 'error_msg=No Customer ID supplied.');
    exit;
}

$BuildPage .= $smarty->fetch('customer/ticket_new.tpl');

