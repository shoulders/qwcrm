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
require(INCLUDES_DIR.'components/workorder.php');

// Check if we have an invoice_id
if($VAR['invoice_id'] == '') {
    force_page('invoice', 'search', 'warning_msg='._gettext("No Invoice ID supplied."));
}

// Check if the invoice is closed
if(get_invoice_details($VAR['invoice_id'], 'is_closed')) {
    force_page('invoice', 'details&invoice_id='.$VAR['invoice_id'], 'warning_msg='._gettext("You cannot edit the invoice because it is closed."));
}

##################################
#      Update Invoice            #
##################################

if(isset($VAR['submit'])) {
    
    // insert the parts and labour item arrays
    insert_labour_items($VAR['invoice_id'], $VAR['labour_description'], $VAR['labour_amount'], $VAR['labour_qty']);
    insert_parts_items($VAR['invoice_id'], $VAR['parts_description'], $VAR['parts_amount'], $VAR['parts_qty']);
    
    // update and recalculate the invoice
    update_invoice($VAR['invoice_id'], $VAR['date'], $VAR['due_date'], $VAR['discount_rate']);    
    recalculate_invoice($VAR['invoice_id']);
    
}
    
##################################
#     Load invoice edit page     #
################################## 

// Invoice Details
$smarty->assign('company_details',          get_company_details()                                                                    );
$smarty->assign('customer_details',         get_customer_details(get_invoice_details($VAR['invoice_id'], 'customer_id'))             );
$smarty->assign('workorder_details',        get_workorder_details(get_invoice_details($VAR['invoice_id'], 'workorder_id'))           ); 
$smarty->assign('invoice_details',          get_invoice_details($VAR['invoice_id'])                                                       );

// Prefill Items
$smarty->assign('labour_prefill_items',     get_invoice_prefill_items('Labour', '1')                                               ); 
$smarty->assign('parts_prefill_items',      get_invoice_prefill_items('Parts', '1')                                                ); 

// Invoice Items
$smarty->assign('labour_items',             get_invoice_labour_items($VAR['invoice_id'])                                                  );
$smarty->assign('parts_items',              get_invoice_parts_items($VAR['invoice_id'])                                                   );

// Invoice Totals
$smarty->assign('labour_sub_total',         labour_sub_total($VAR['invoice_id'])                                                          );
$smarty->assign('parts_sub_total',          parts_sub_total($VAR['invoice_id'])                                                           );

// Misc
$smarty->assign('display_payments',         display_payments('payment_id', 'DESC', false, null, null, null, null, null, null, null, $VAR['invoice_id'])  );
$smarty->assign('payment_methods',          get_payment_accepted_methods()                                                             );
$smarty->assign('employee_display_name',    get_user_details(get_invoice_details($VAR['invoice_id'], 'employee_id'), 'display_name') );
$smarty->assign('invoice_statuses',         get_invoice_statuses()                                                                   );

// Build the page
$BuildPage .= $smarty->fetch('invoice/edit.tpl');