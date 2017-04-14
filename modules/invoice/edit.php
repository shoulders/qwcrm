<?php

require(INCLUDES_DIR.'modules/customer.php');
require(INCLUDES_DIR.'modules/employee.php');
require(INCLUDES_DIR.'modules/invoice.php');
//require(INCLUDES_DIR.'modules/payment.php');
require('modules/payment/include.php');
require(INCLUDES_DIR.'modules/workorder.php');

// Check if we have an invoice_id and the retrieve the status
if($invoice_id == '' && $invoice_id != '0') {    
    force_page('core', 'error', 'error_msg=No Invoice ID');
    exit;
}

########################################################
#    Javascript Labour and Parts Deletion Endpoints    #
########################################################

// Delete Invoice Labour item
if(isset($VAR['deleteType']) && $VAR['deleteType'] == 'labourRecord') {
    delete_invoice_labour_item($db, $VAR['labour_id']);
    recalculate_invoice_totals($db, $invoice_id);
}

// Delete Parts Item Record
if(isset($VAR['deleteType']) && $VAR['deleteType'] == 'partsRecord') {
    delete_invoice_parts_item($db, $VAR['parts_id']);
    recalculate_invoice_totals($db, $invoice_id);
}

##################################
#      Update Invoice            #
##################################
if(isset($VAR['submit'])) {
    insert_labour_items($db, $invoice_id, $VAR['labour_description'], $VAR['labour_rate'], $VAR['labour_hour']);
    insert_parts_items($db, $invoice_id, $VAR['parts_description'], $VAR['parts_price'], $VAR['parts_qty']);
    update_invoice_small($db, $invoice_id, $VAR['date'], $VAR['due_date'], $VAR['discount_rate']);    
    recalculate_invoice_totals($db, $invoice_id);
}
    
##################################
#     Load invoice edit page     #
################################## 
    
$invoice = get_invoice_details($db, $invoice_id);

$smarty->assign('company',              get_company_details($db)                                    );
$smarty->assign('customer',             display_customer_info($db, $invoice['0']['CUSTOMER_ID'])    );      
$smarty->assign('invoice',              $invoice                                                    );
$smarty->assign('labour_rate_items',    get_active_labour_rate_items($db)                           );    
$smarty->assign('labour_items',         get_invoice_labour_items($db, $invoice_id)                  );
$smarty->assign('parts_items',          get_invoice_parts_items($db, $invoice_id)                   );
$smarty->assign('labour_sub_total',     labour_sub_total($db, $invoice_id)                          );
$smarty->assign('parts_sub_total',      parts_sub_total($db, $invoice_id)                           );
$smarty->assign('transactions',         get_invoice_transactions($db, $invoice_id)                  ); 
$smarty->assign('workorder_status',     get_workorder_status($db, $workorder_id)                    ); 
$smarty->assign('employee_display_name', get_employee_details($db, $invoice['0']['EMPLOYEE_ID'],'EMPLOYEE_DISPLAY_NAME')    );

// temp - these are needed for the record deltion routines - consider making all fields editable
$smarty->assign('workorder_id',             $invoice['0']['WORKORDER_ID']                           );
$smarty->assign('customer_id',              $invoice['0']['CUSTOMER_ID']                            );

// Fetch Page
$BuildPage .= $smarty->fetch('invoice/edit.tpl');


##################################
# If We have a Submit2           #  // something to do with the paypal payment method
##################################

if(isset($submit2) && $workorder_id != '0'){    
    update_workorder_status($db, $workorder_id, 8);
}