<?php

require_once ('include.php');

if(isset($VAR['submit'])) {    
        
    if (!update_customer($db, $VAR)){
        force_page('customer', 'details_edit', 'error_msg=Falied to Update Customer Information&customer_id='.$VAR['customer_id']);
        exit;
    } else {
        force_page('customer', 'details', 'msg=The Customers information was updated&customer_id='.$VAR['customer_id'].'&page_title='.$VAR['displayName']);
        exit;
    }

} else {    

    $smarty->assign('customer', display_customer_info($db, $VAR['customer_id']));
    $BuildPage .= $smarty->fetch('customer'.SEP.'details_edit.tpl');
}