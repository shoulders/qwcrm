<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/customer.php');

// check if we have a customer_id
if($customer_id == ''){
    force_page('core', 'error', 'error_msg=No Customer ID supplied.');
    exit;
}

// Insert the customer note
if(isset($VAR['submit'])) {   
    
    insert_customer_note($db, $customer_id, $VAR['customer_note']);    
    force_page('customer', 'details&customer_id='.$customer_id);    

// Load the new note page    
} else {  

    $BuildPage .= $smarty->fetch('customer/note_new.tpl');

}

