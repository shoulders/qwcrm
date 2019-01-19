<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'client.php');
require(INCLUDES_DIR.'invoice.php');
require(INCLUDES_DIR.'giftcert.php');
require(INCLUDES_DIR.'payment.php');
require(INCLUDES_DIR.'user.php');
require(INCLUDES_DIR.'report.php');
require(INCLUDES_DIR.'workorder.php');

// Check if we have a invoice_id
if(!isset($VAR['invoice_id']) || !$VAR['invoice_id']) {
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

// Get statuses that can be changed by the user
$statuses = get_invoice_statuses(true);

// Build the page with the current status from the database
$smarty->assign('allowed_to_change_status',     check_invoice_status_can_be_changed($VAR['invoice_id']) );
$smarty->assign('allowed_to_change_employee',   !get_invoice_details($VAR['invoice_id'], 'is_closed')   );
$smarty->assign('allowed_to_refund',            check_invoice_can_be_refunded($VAR['invoice_id'])       );
$smarty->assign('allowed_to_cancel',            check_invoice_can_be_cancelled($VAR['invoice_id'])      );
$smarty->assign('allowed_to_delete',            check_invoice_can_be_deleted($VAR['invoice_id'])        );
$smarty->assign('active_employees',             get_active_users('employees')                           );
$smarty->assign('invoice_statuses',             $statuses                                               );
$smarty->assign('invoice_status',               get_invoice_details($VAR['invoice_id'], 'status')       );
$smarty->assign('invoice_status_display_name',  get_invoice_status_display_name(get_invoice_details($VAR['invoice_id'], 'status')));
$smarty->assign('assigned_employee_id',         $assigned_employee_id                                   );
$smarty->assign('assigned_employee_details',    get_user_details($assigned_employee_id)                 );

$BuildPage .= $smarty->fetch('invoice/status.tpl');