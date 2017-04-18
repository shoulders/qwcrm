<?php

require(INCLUDES_DIR.'modules/customer.php');

// check if we have a customer_id
if($customer_id == ''){
    force_page('core', 'error', 'error_msg=No Customer ID supplied.');
    exit;
}

if(isset($VAR['submit'])) {
    
    insert_customer_memo($db, $customer_id, $memo);
    force_page('customer', 'details&customer_id='.$customer_id);
    
    
    
} else {
    
    // Delete Memo
    if($VAR['action'] == 'delete') {
        
        delete_customer_memo($db, $VAR['note_id']);
        force_page('customer', 'details&customer_id='.$customer_id);

    } 

    $BuildPage .= $smarty->fetch('customer/memo.tpl');

}

