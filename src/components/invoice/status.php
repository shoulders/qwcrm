<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'components/customer.php');
require(INCLUDES_DIR.'components/invoice.php');
require(INCLUDES_DIR.'components/payment.php');
require(INCLUDES_DIR.'components/user.php');
require(INCLUDES_DIR.'components/report.php');
require(INCLUDES_DIR.'components/workorder.php');

// Check if we have a invoice_id
if(!isset($VAR['invoice']) || !$VAR['invoice_id']) {
    force_page('invoice', 'search', 'warning_msg='._gettext("No Invoice ID supplied."));
}

// Get the Id of the employee assigned to the invoice
$assigned_employee_id = get_invoice_details($VAR['invoice_id'], 'employee_id');

// Update invoice Status
if(isset($VAR['change_status'])){
    update_invoice_status($VAR['invoice_id'], $VAR['assign_status']);    
    force_page('invoice', 'status&invoice_id='.$VAR['invoice_id']);
}

// Assign Work Order to another employee
if(isset($VAR['change_employee'])) {
    assign_invoice_to_employee($VAR['invoice_id'], $VAR['target_employee_id']);    
    force_page('invoice', 'status&invoice_id='.$VAR['invoice_id']);
}

// Delete a Work Order
if(isset($VAR['delete'])) {    
    force_page('invoice', 'delete', 'invoice_id='.$VAR['invoice_id']);
}

/* Remove dormant invoice statuses (for now) */

// Get status list
$statuses = get_invoice_statuses();

// Unset unwanted status
//unset($statuses[0]);  // 'pending'  
//unset($statuses[1]);  // 'unpaid'  
unset($statuses[2]);    // 'partially_paid' 
unset($statuses[3]);    // 'paid'    
unset($statuses[4]);    // 'in_dispute'
unset($statuses[5]);    // 'overdue'
unset($statuses[6]);    // 'cancelled'
unset($statuses[7]);    // 'refunded'
unset($statuses[8]);    // 'collections'

//  Remaps the array ID's - Because of how smarty works you need to maintain the array internal number system
foreach($statuses as $status) {
    $edited_statuses[] = $status;
}        
 
/* -- */

// Build the page with the current status from the database

$smarty->assign('allowed_to_change_status',     check_invoice_status_can_be_changed($VAR['invoice_id'])       );
$smarty->assign('allowed_to_change_employee',   !get_invoice_details($VAR['invoice_id'], 'is_closed')         );
$smarty->assign('allowed_to_delete',            check_invoice_can_be_deleted($VAR['invoice_id'])              );
$smarty->assign('active_employees',             get_active_users('employees')                          );
$smarty->assign('invoice_statuses',             $edited_statuses                                            );
$smarty->assign('invoice_status',               get_invoice_details($VAR['invoice_id'], 'status')             );
$smarty->assign('assigned_employee_id',         $assigned_employee_id                                       );
$smarty->assign('assigned_employee_details',    get_user_details($assigned_employee_id)                );

$BuildPage .= $smarty->fetch('invoice/status.tpl');