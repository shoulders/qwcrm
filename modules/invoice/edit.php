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

// Check if we have an invoice_id
if($invoice_id == '') {
    force_page('invoice', 'search', 'warning_msg='.gettext("No Invoice ID supplied."));
    exit;
}

##################################
#      Update Invoice            #
##################################

if(isset($VAR['submit'])) {
    
    // insert the parts and labour item arrays
    insert_labour_items($db, $invoice_id, $VAR['labour_description'], $VAR['labour_amount'], $VAR['labour_qty']);
    insert_parts_items($db, $invoice_id, $VAR['parts_description'], $VAR['parts_amount'], $VAR['parts_qty']);
    
    // update and recalculate the invoice
    update_invoice($db, $invoice_id, $VAR['date'], $VAR['due_date'], $VAR['discount_rate']);    
    recalculate_invoice_totals($db, $invoice_id);
    
}
    
##################################
#     Load invoice edit page     #
################################## 

// object details
$smarty->assign('company_details',          get_company_details($db)                                                                    );
$smarty->assign('customer_details',         get_customer_details($db, get_invoice_details($db, $invoice_id, 'customer_id'))             );
$smarty->assign('workorder_details',        get_workorder_details($db, get_invoice_details($db, $invoice_id, 'workorder_id'))           ); 
$smarty->assign('invoice_details',          get_invoice_details($db, $invoice_id)                                                       );

// prefill
$smarty->assign('labour_prefill_items',     get_invoice_prefill_items($db, 'Labour', '1')                                               ); 
$smarty->assign('parts_prefill_items',      get_invoice_prefill_items($db, 'Parts', '1')                                                ); 

// Items in the database
$smarty->assign('labour_items',             get_invoice_labour_items($db, $invoice_id)                                                  );
$smarty->assign('parts_items',              get_invoice_parts_items($db, $invoice_id)                                                   );

// Invoice totals
$smarty->assign('labour_sub_total',         labour_sub_total($db, $invoice_id)                                                          );
$smarty->assign('parts_sub_total',          parts_sub_total($db, $invoice_id)                                                           );
$smarty->assign('transactions',             get_invoice_transactions($db, $invoice_id)                                                  );

// Misc

$smarty->assign('employee_display_name',    get_user_details($db, get_invoice_details($db, $invoice_id, 'employee_id'), 'display_name') );
$smarty->assign('invoice_statuses',         get_invoice_statuses($db)                                                                   );

// these are needed for the record deletion routines - consider making all fields editable
$smarty->assign('workorder_id',             get_invoice_details($db, $invoice_id, 'workorder_id')                                       );
$smarty->assign('customer_id',              get_invoice_details($db, $invoice_id, 'customer_id')                                        );

// Fetch Page
$BuildPage .= $smarty->fetch('invoice/edit.tpl');