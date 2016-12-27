<?php
require_once ("include.php");

if(isset($VAR['submit'])) {

    if (!$customer_id = insert_new_customer($db,$VAR)){
                $smarty->assign('error_msg', 'Falied to insert customer');
                $smarty->display('core'.SEP.'error.tpl');
            } else {
                force_page('customer', 'customer_details&customer_id='.$customer_id.'&msg=Added New Customer '.$VAR["displayName"].' &page_title='.$VAR["displayName"]);
                exit;    
            }            
        
    
} else {
    
    $smarty->display('customer'.SEP.'new.tpl');

}