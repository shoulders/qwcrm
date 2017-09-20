<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/customer.php');
require(INCLUDES_DIR.'modules/invoice.php');
require(INCLUDES_DIR.'modules/payment.php');
require(INCLUDES_DIR.'modules/user.php');
require(INCLUDES_DIR.'modules/workorder.php');

// Check if we have a invoice_id
if($invoice_id == '') {
    force_page('invoice', 'search', 'warning_msg='.gettext("No Invoice ID supplied."));
    exit;
}

// Get the Id of the employee assigned to the invoice
$assigned_employee_id = get_invoice_details($db, $invoice_id, 'employee_id');

// Update invoice Status
if(isset($VAR['change_status'])){
    update_invoice_status($db, $invoice_id, $VAR['assign_status']);    
    force_page('invoice', 'status', 'invoice_id='.$invoice_id.'&information_msg='.gettext("Invoice status updated."));
    exit; 
}

// Assign Work Order to another employee
if(isset($VAR['change_employee'])) {
    assign_invoice_to_employee($db, $invoice_id, $VAR['target_employee_id']);    
    force_page('invoice', 'status', 'invoice_id='.$invoice_id.'&information_msg='.gettext("Assigned employee updated."));
    exit; 
}

// Delete a Work Order
if(isset($VAR['delete'])) {    
    force_page('invoice', 'delete', 'invoice_id='.$invoice_id);
    exit;
}

/* Remove dormant invoice statuses (for now) */

// Get status list
$statuses = get_invoice_statuses($db);

// Unset unwanted status
//unset($statuses[0]);  // 'pending'  
//unset($statuses[1]);  // 'unpaid'  
//unset($statuses[2]);    // 'partially_paid' 
//unset($statuses[3]);    // 'paid'//    
unset($statuses[4]);  // 'in_dispute'
unset($statuses[5]);    // 'overdue'
unset($statuses[6]);    // 'cancelled'
unset($statuses[7]);    // 'refunded'
unset($statuses[8]);    // 'collections'


       
//  Remaps the array ID's - Because of how smarty works you need to maintain the arrary internal number system
foreach($statuses as $status) {
    $edited_statuses[] = $status;
}        
 
/* -- */



// Build the page with the current status from the database
$smarty->assign('allowed_to_delete',            check_invoice_can_be_deleted($db, $invoice_id)              );
$smarty->assign('active_employees',             get_active_users($db, 'employees')                          );
$smarty->assign('invoice_statuses',             $edited_statuses                                            );
$smarty->assign('invoice_status',               get_invoice_details($db, $invoice_id, 'status')             );
$smarty->assign('assigned_employee_id',         $assigned_employee_id                                       );
$smarty->assign('assigned_employee_details',    get_user_details($db, $assigned_employee_id)                );

$BuildPage .= $smarty->fetch('invoice/status.tpl');