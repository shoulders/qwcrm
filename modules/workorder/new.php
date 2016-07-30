<?php

require_once('include.php');

$wo_id                  = $VAR['wo_id']; 
$submit                 = $VAR['submit'];
$email                  = $VAR['email'];
$customer_id            = $VAR['customer_id'];
$created_by             = $VAR['created_by'];
$scope                  = $VAR['scope'];
$workorder_description  = $VAR['workorder_description'];
$workorder_comments     = $VAR['workorder_comments'];
$workorder_note         = $VAR['workorder_note'];

/* Lets Grab Technicians Names */
$q = "SELECT EMPLOYEE_LOGIN, EMPLOYEE_ID FROM ".PRFX."TABLE_EMPLOYEE WHERE EMPLOYEE_STATUS=1";
if(!$rs = $db->execute($q)) {
    force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
    exit;
}
$tech = $rs->GetMenu2('created_by', $login, $login_id);
$smarty->assign('tech', $tech);

/* email the work order - not sure this is enabled, maybe put in the submit funciton below */
if (isset($VAR['email'])) {
    if (!email_new_workorder($db,$VAR)) {
        $smarty->display('workorder'.SEP.'new.tpl');
    }
}




/* If data submitted do this */
if (isset($VAR['submit'])){    
    $wo_id = insert_new_workorder($db, $customer_id, $created_by, $scope, $workorder_description, $workorder_comments, $workorder_note);        
    force_page('workorder', 'details&wo_id='.$wo_id.'&customer_id='.$customer_id.'&page_title='.$smarty->get_template_vars('translate_workorder_details_title'));
    exit;
    
} else {
    
    /* New Blank Page for submitting a new Work Order */

    if(!isset($VAR['customer_id'])) {        
        force_page('core', 'error&menu=1&error_msg=No Customer Information Set&type=error');
    } else {        
        $smarty->assign('customer_details', display_customer_info($db, $customer_id));
        $smarty->display('workorder'.SEP.'new.tpl');
    }

}