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
require(INCLUDES_DIR.'payment.php');
require(INCLUDES_DIR.'user.php');
require(INCLUDES_DIR.'workorder.php');

// Prevent undefined variable errors
$VAR['labour_description'] = isset($VAR['labour_description']) ? $VAR['labour_description'] : null;
$VAR['labour_amount'] = isset($VAR['labour_amount']) ? $VAR['labour_amount'] : null;
$VAR['labour_qty'] = isset($VAR['labour_qty']) ? $VAR['labour_qty'] : null;
$VAR['parts_description'] = isset($VAR['parts_description']) ? $VAR['parts_description'] : null;
$VAR['parts_amount'] = isset($VAR['parts_amount']) ? $VAR['parts_amount'] : null;
$VAR['parts_qty'] = isset($VAR['parts_qty']) ? $VAR['parts_qty'] : null;

// Check if we have an invoice_id
if(!isset($VAR['invoice_id']) || !$VAR['invoice_id']) {
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
    update_invoice_static_values($VAR['invoice_id'], $VAR['date'], $VAR['due_date'], $VAR['discount_rate']);    
    recalculate_invoice($VAR['invoice_id']);
    
}
    
##################################
#     Load invoice edit page     #
################################## 

// Invoice Details
$smarty->assign('company_details',          get_company_details()                                                                  );
$smarty->assign('client_details',           get_client_details(get_invoice_details($VAR['invoice_id'], 'client_id'))               );
$smarty->assign('workorder_details',        get_workorder_details(get_invoice_details($VAR['invoice_id'], 'workorder_id'))         ); 
$smarty->assign('invoice_details',          get_invoice_details($VAR['invoice_id'])                                                );

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