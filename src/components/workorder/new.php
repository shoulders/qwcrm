<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'components/customer.php');
require(INCLUDES_DIR.'components/workorder.php');
require(INCLUDES_DIR.'components/user.php');

// Prevent undefined variable errors
$VAR['assign_to_employee'] = isset($VAR['assign_to_employee']) ? $VAR['assign_to_employee'] : null;

// Check if we have a customer_id
if(!isset($VAR['customer_id']) || !$VAR['customer_id']) {
    force_page('customer', 'search', 'warning_msg='._gettext("No Customer ID supplied."));
}

// If a workorder is submitted
if(isset($VAR['submit'])){
    
    // insert the submitted workorder and return it's id
    $VAR['workorder_id'] = insert_workorder($VAR['customer_id'], $VAR['scope'], $VAR['description'], $VAR['comment']);

    // If workorder is to be assigned to an employee
    if($VAR['assign_to_employee'] === '1') {       
        assign_workorder_to_employee($VAR['workorder_id'], $user->login_user_id);  
    }
    
    // load the workorder details page
    force_page('workorder', 'details&workorder_id='.$VAR['workorder_id'], 'information_msg='._gettext("New Work Order created."));
        
}

// Build the page
$smarty->assign('customer_display_name', get_customer_details($VAR['customer_id'], 'display_name'));
$BuildPage .= $smarty->fetch('workorder/new.tpl');