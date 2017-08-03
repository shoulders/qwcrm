<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/workorder.php');
require(INCLUDES_DIR.'modules/customer.php');

// Check if we have a customer_id
if($customer_id == '') {
    force_page('customer', 'search', 'warning_msg='.gettext("No Customer ID supplied."));
    exit;
}

// If a workorder is submitted
if(isset($VAR['submit'])){
    
    // insert the submitted workorder and return it's id
    $workorder_id = insert_workorder($db, $customer_id, $VAR['employee_id'], $VAR['scope'], $VAR['description'], $VAR['comments']);      
    
    // load the workorder details page
    force_page('workorder', 'details&workorder_id='.$workorder_id, 'information_msg='.gettext("New Work Order created."));
    exit;
        
}

// Build the page
$smarty->assign('customer_details', get_customer_details($db, $customer_id));
$BuildPage .= $smarty->fetch('workorder/new.tpl');