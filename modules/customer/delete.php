<?php
require('include.php');
$customer_id = $VAR['customer_id'];


/* make sure we got an ID number */
if(!isset($customer_id) || $customer_id =="") { 
    $smarty->assign('results', 'Please go back and select a customer');
    die;
}    

$q = "SELECT count(*) as count FROM `".PRFX."TABLE_WORK_ORDER` LEFT JOIN ".PRFX."TABLE_INVOICE ON ".PRFX."TABLE_WORK_ORDER.CUSTOMER_ID = ".PRFX."TABLE_INVOICE.CUSTOMER_ID WHERE ".PRFX."TABLE_WORK_ORDER.CUSTOMER_ID=".$db->qstr($customer_id);
    if(!$rs = $db->execute($q)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    }
    
    if($rs->fields['count'] > 0 ) {
        force_page('customer', 'view&page_title=Customers&error_msg=You can not delete a customer who has work history.');
        exit;
    } else {
        /* run the function and return the results */
        if(!delete_customer($db,$customer_id)) {
            force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
            exit;    
        } else {
            force_page('customer', 'view&page_title=Customers');
            exit;
        }
    }