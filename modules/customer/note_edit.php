<?php

require(INCLUDES_DIR.'modules/customer.php');

// check if we have a customer_note_id
if($customer_note_id == ''){
    force_page('core', 'error', 'error_msg=No Customer Note ID supplied.');
    exit;
}

// If record submitted for updating
if(isset($VAR['submit'])) {
               
    update_customer_note($db, $customer_note_id, date_to_timestamp($VAR['date']), $VAR['note']);
    force_page('customer', 'details&customer_id='.$customer_id);   
    exit;
    
} else {    
    
    // Fetch and load the page
    $smarty->assign('customer_note', get_customer_note($db, $customer_note_id));
    $BuildPage .= $smarty->fetch('customer/note_edit.tpl');
    
}


