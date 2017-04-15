<?php

require(INCLUDES_DIR.'modules/customer.php');
require(INCLUDES_DIR.'modules/invoice.php');
require(INCLUDES_DIR.'modules/payment.php');

/* check if we have a customer id and if so get details */
if($invoice_id == '' || $invoice_id == '0'){
    force_page('core', 'error&error_msg=Invoice Not found: Invoice ID: '.$invoice_id.'&menu=1');
    exit;
}
    
$smarty->assign('company', get_company_details($db));
$smarty->assign('customer_details', display_customer_info($db, $customer_id));
$smarty->assign('invoice', get_invoice_details($db, $invoice_id));
$smarty->assign('workorder_id', get_invoice_details($db, $invoice_id, 'WORKORDER_ID'));
$smarty->assign('labor', get_invoice_labour_items($db, $invoice_id));
$smarty->assign('parts', get_invoice_parts_items($db, $invoice_id));
$smarty->assign('trans', get_invoice_transactions($db, $invoice_id));
$smarty->assign('labour_sub_total_sum', labour_sub_total($db, $invoice_id));
$smarty->assign('parts_sub_total_sum', parts_sub_total($db, $invoice_id));
     
$BuildPage .= $smarty->fetch('invoice/details.tpl');