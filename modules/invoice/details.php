<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/customer.php');
require(INCLUDES_DIR.'modules/user.php');
require(INCLUDES_DIR.'modules/invoice.php');
require(INCLUDES_DIR.'modules/payment.php');

// Check if we have an invoice_id
if($invoice_id == '') {
    force_page('invoice', 'search', 'warning_msg='.gettext("No Invoice ID supplied."));
    exit;
}
    
$smarty->assign('company_details',          get_company_details($db)                                                                            );
$smarty->assign('customer_details',         get_customer_details($db, get_invoice_details($db, $invoice_id, 'CUSTOMER_ID'))                     );
$smarty->assign('invoice_details',          get_invoice_details($db, $invoice_id)                                                               );
$smarty->assign('workorder_id',             get_invoice_details($db, $invoice_id, 'WORKORDER_ID')                                               );
$smarty->assign('labour_items',             get_invoice_labour_items($db, $invoice_id)                                                          );
$smarty->assign('parts_items',              get_invoice_parts_items($db, $invoice_id)                                                           );
$smarty->assign('transactions',             get_invoice_transactions($db, $invoice_id)                                                          );
$smarty->assign('labour_sub_total_sum',     labour_sub_total($db, $invoice_id)                                                                  );
$smarty->assign('parts_sub_total_sum',      parts_sub_total($db, $invoice_id)                                                                   );
$smarty->assign('employee_display_name',    get_user_details($db, get_invoice_details($db, $invoice_id, 'EMPLOYEE_ID'),'EMPLOYEE_DISPLAY_NAME') );
     
$BuildPage .= $smarty->fetch('invoice/details.tpl');