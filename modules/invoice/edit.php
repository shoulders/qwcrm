<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/customer.php');
require(INCLUDES_DIR.'modules/user.php');
require(INCLUDES_DIR.'modules/invoice.php');
require(INCLUDES_DIR.'modules/payment.php');
require(INCLUDES_DIR.'modules/workorder.php');

// Check if we have an invoice_id and the retrieve the status
if($invoice_id == '' && $invoice_id != '0') {    
    force_page('core', 'error', 'error_msg=No Invoice ID');
    exit;
}

##################################
#      Update Invoice            #
##################################

if(isset($VAR['submit'])) {
    insert_labour_items($db, $invoice_id, $VAR['labour_description'], $VAR['labour_rate'], $VAR['labour_hour']);
    insert_parts_items($db, $invoice_id, $VAR['parts_description'], $VAR['parts_price'], $VAR['parts_qty']);
    update_invoice($db, $invoice_id, $VAR['date'], $VAR['due_date'], $VAR['discount_rate']);    
    recalculate_invoice_totals($db, $invoice_id);
}
    
##################################
#     Load invoice edit page     #
################################## 
    
$smarty->assign('company_details',      get_company_details($db)                                                                                    );
$smarty->assign('customer_details',     get_customer_details($db, get_invoice_details($db, $invoice_id, 'CUSTOMER_ID'))                             );      
$smarty->assign('invoice_details',      get_invoice_details($db, $invoice_id)                                                                       );
$smarty->assign('labour_rate_items',    get_active_labour_rate_items($db)                                                                           );    
$smarty->assign('labour_items',         get_invoice_labour_items($db, $invoice_id)                                                                  );
$smarty->assign('parts_items',          get_invoice_parts_items($db, $invoice_id)                                                                   );
$smarty->assign('labour_sub_total',     labour_sub_total($db, $invoice_id)                                                                          );
$smarty->assign('parts_sub_total',      parts_sub_total($db, $invoice_id)                                                                           );
$smarty->assign('transactions',         get_invoice_transactions($db, $invoice_id)                                                                  ); 
$smarty->assign('workorder_status',     get_workorder_details($db, $workorder_id, 'WORK_ORDER_STATUS')                                              ); 
$smarty->assign('employee_display_name',get_user_details($db, get_invoice_details($db, $invoice_id, 'EMPLOYEE_ID'),'EMPLOYEE_DISPLAY_NAME')         );

// temp - these are needed for the record deltion routines - consider making all fields editable
$smarty->assign('workorder_id',         get_invoice_details($db, $invoice_id, 'WORKORDER_ID')                                                       );
$smarty->assign('customer_id',          get_invoice_details($db, $invoice_id, 'CUSTOMER_ID')                                                        );

// Fetch Page
$BuildPage .= $smarty->fetch('invoice/edit.tpl');