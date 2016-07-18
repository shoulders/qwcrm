<?php
require_once ('include.php');
if(!xml2php("customer")) {
    $smarty->assign('error_msg',"Error in language file");
}

/* load customer details */
$customer_details = display_customer_info($db, $VAR['customer_id']);

if(isset($VAR['submit'])) {    
        
    if (!update_customer($db, $VAR)){
        force_page('customer', 'edit&error_msg=Falied to Update Customer Information&customer_id='.$VAR['customer_id']);
        exit;
    } else {
        force_page('customer', 'customer_details&msg=The Customers information was updated&customer_id='.$VAR['customer_id'].'&page_title='.$VAR['displayName']);
        exit;
    }

} else {    

    $smarty->assign('customer', $customer_details);
    $smarty->display('customer'.SEP.'edit.tpl');
}