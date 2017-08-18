<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/customer.php');

// Check if we have a customer_id
if($customer_id == '') {
    force_page('customer', 'search', 'warning_msg='.gettext("No Customer ID supplied."));
    exit;
}

// Insert the customer note
if(isset($VAR['submit'])) {   
    
    insert_customer_note($db, $customer_id, $VAR['note']);    
    force_page('customer', 'details&customer_id='.$customer_id);    

// Build the page  
} else {  

    $BuildPage .= $smarty->fetch('customer/note_new.tpl');

}

