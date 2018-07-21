<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'client.php');
require(INCLUDES_DIR.'workorder.php');
require(INCLUDES_DIR.'user.php');

// Check if we have a workorder_id
if(!isset($VAR['workorder_id']) || !$VAR['workorder_id']) {
    force_page('workorder', 'search', 'warning_msg='._gettext("No Workorder ID supplied."));
}

// Get the Id of the employee assigned to the workorder
$assigned_employee_id = get_workorder_details($VAR['workorder_id'], 'employee_id');

// Update Work Order Status
if(isset($VAR['change_status'])){
    update_workorder_status($VAR['workorder_id'], $VAR['assign_status']);    
    force_page('workorder', 'status&workorder_id='.$VAR['workorder_id']);
}

// Assign Work Order to another employee
if(isset($VAR['change_employee'])) {
    assign_workorder_to_employee($VAR['workorder_id'], $VAR['target_employee_id']);    
    force_page('workorder', 'status&workorder_id='.$VAR['workorder_id']);
}

// Delete a Work Order
if(isset($VAR['delete'])) {    
    force_page('workorder', 'delete', 'workorder_id='.$VAR['workorder_id']);
}

/* Remove unallowed status for manual change */

// Get status list
$statuses = get_workorder_statuses();

// Unset unwanted status
//unset($statuses[0]);  // 'unassigned'  
//unset($statuses[1]);  // 'assigned'  
//unset($statuses[2]);  // 'waiting_for_parts' 
//unset($statuses[3]);  // 'scheduled'    
//unset($statuses[4]);  // 'with_client'
//unset($statuses[5]);  // 'on_hold'
//unset($statuses[6]);  // 'management'
//unset($statuses[7]);  // 'closed_without_invoice'
unset($statuses[8]);    // 'closed_with_invoice'

//  Remaps the array ID's - Because of how smarty works you need to maintain the array internal number system
foreach($statuses as $status) {
    $edited_statuses[] = $status;
}        
 
/* -- */

// Build the page with the current status from the database
$smarty->assign('allowed_to_change_status',     !get_workorder_details($VAR['workorder_id'], 'invoice_id')        );
$smarty->assign('allowed_to_change_employee',   !get_workorder_details($VAR['workorder_id'], 'is_closed')         );
$smarty->assign('allowed_to_delete',            check_workorder_status_allows_for_deletion($VAR['workorder_id'])  );
$smarty->assign('active_employees',             get_active_users('employees')                              );
$smarty->assign('workorder_statuses',           $edited_statuses                                                );
$smarty->assign('workorder_status',             get_workorder_details($VAR['workorder_id'], 'status')             );
$smarty->assign('assigned_employee_id',         $assigned_employee_id                                           );
$smarty->assign('assigned_employee_details',    get_user_details($assigned_employee_id)                    );

$BuildPage .= $smarty->fetch('workorder/status.tpl');