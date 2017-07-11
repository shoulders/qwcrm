<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/customer.php');
require(INCLUDES_DIR.'modules/user.php');
require(INCLUDES_DIR.'modules/invoice.php');
require(INCLUDES_DIR.'modules/payment.php');

// check if we have a invoice_id and if so get details
if($invoice_id == '' || $invoice_id == '0'){
    force_page('core', 'error', 'error_msg=Invoice Not found: Invoice ID: '.$invoice_id.'&menu=1');
    exit;
}
    
$smarty->assign('company_details', get_company_details($db));
$smarty->assign('customer_details', get_customer_details($db, get_invoice_details($db, $invoice_id, 'CUSTOMER_ID')));
$smarty->assign('invoice_details', get_invoice_details($db, $invoice_id));
$smarty->assign('workorder_id', get_invoice_details($db, $invoice_id, 'WORKORDER_ID'));
$smarty->assign('labour_items', get_invoice_labour_items($db, $invoice_id));
$smarty->assign('parts_items', get_invoice_parts_items($db, $invoice_id));
$smarty->assign('transactions', get_invoice_transactions($db, $invoice_id));
$smarty->assign('labour_sub_total_sum', labour_sub_total($db, $invoice_id));
$smarty->assign('parts_sub_total_sum', parts_sub_total($db, $invoice_id));
$smarty->assign('employee_display_name',get_user_details($db, get_invoice_details($db, $invoice_id, 'EMPLOYEE_ID'),'EMPLOYEE_DISPLAY_NAME')     );
     
$BuildPage .= $smarty->fetch('invoice/details.tpl');