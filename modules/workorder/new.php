<?php

require_once('include.php');

$submit         = $VAR['submit'];
$email          = $VAR['email'];
$customer_id    = $VAR['customer_id'];

/* Lets Grab Technicians Names */
$q = "SELECT EMPLOYEE_LOGIN, EMPLOYEE_ID FROM ".PRFX."TABLE_EMPLOYEE WHERE EMPLOYEE_STATUS=1";
if(!$rs = $db->execute($q)) {
    force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
    exit;
}
$tech = $rs->GetMenu2('created_by', $login,$login_id);
$smarty->assign('tech', $tech);

/* email the work order - not sure this is enabled, maybe put in the submit funciton below */
if (isset($VAR['email'])) {
    if (!email_new_workorder($db,$VAR)) {
        $smarty->display('workorder'.SEP.'new.tpl');
    }
}

/* If data submitted do this */
if (isset($VAR['submit'])) {

    $response = insert_new_workorder($db,$VAR);

    if ($response['wo_id'] == '') {
        
        // add error page here
        echo 'There has been an error';
        $smarty->display('workorder'.SEP.'new.tpl');    
        
    } else {        
        force_page('workorder', 'details&wo_id='.$response['wo_id'].'&customer_id='.$response['customer_ID'].'&page_title='.$translate_workorder_page_title).' '.$response['wo_id'];
    }

} else {
    
    /* New Blank Page for submitting a new Work Order */

    // Grab customers Information
    if(!isset($customer_id)) {
        // redirect to customer search page
        // header ("location", "?page=customer:view");
    } else {
        $smarty->assign('customer_details', display_customer_info($db, $customer_id));
    }

    $smarty->display('workorder'.SEP.'new.tpl');
    
}