<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'client.php');
require(INCLUDES_DIR.'company.php');
require(INCLUDES_DIR.'invoice.php');
require(INCLUDES_DIR.'payment.php');
require(INCLUDES_DIR.'report.php');
require(INCLUDES_DIR.'user.php');
require(INCLUDES_DIR.'voucher.php');
require(INCLUDES_DIR.'workorder.php');

// Prevent undefined variable errors
$VAR['labour_items'] = isset($VAR['labour_items']) ? $VAR['labour_items'] : null;
$VAR['parts_items'] = isset($VAR['parts_items']) ? $VAR['parts_items'] : null;

// Check if we have an invoice_id
if(!isset($VAR['invoice_id']) || !$VAR['invoice_id']) {
    force_page('invoice', 'search', 'warning_msg='._gettext("No Invoice ID supplied."));
}

// Check if invoice can be edited
if(!check_invoice_can_be_edited($VAR['invoice_id'])) {
    force_page('invoice', 'details&invoice_id='.$VAR['invoice_id'], 'warning_msg='._gettext("You cannot edit this invoice because its status does not allow it."));
}

##################################
#      Update Invoice            #
##################################

if(isset($VAR['submit'])) {
    
    // insert the parts and labour item arrays
    insert_labour_items($VAR['invoice_id'], $VAR['labour_items']);
    insert_parts_items($VAR['invoice_id'], $VAR['parts_items']);
    
    // update and recalculate the invoice
    update_invoice_static_values($VAR['invoice_id'], $VAR['date'], $VAR['due_date'], $VAR['unit_discount_rate']);    
    recalculate_invoice_totals($VAR['invoice_id']);
    
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
$smarty->assign('vat_tax_codes',            get_vat_tax_codes(false)                                                               );
$smarty->assign('default_vat_tax_code',     get_default_vat_tax_code(get_invoice_details($VAR['invoice_id'], 'tax_system'))        );

// Invoice Items
$smarty->assign('labour_items',             get_invoice_labour_items($VAR['invoice_id'])                                                  );
$smarty->assign('parts_items',              get_invoice_parts_items($VAR['invoice_id'])                                                   );
$smarty->assign('display_vouchers',        display_vouchers('voucher_id', 'DESC', false, '25', null, null, null, null, null, null, null, $VAR['invoice_id']) );

// Sub Totals
$smarty->assign('labour_items_sub_totals',     get_labour_items_sub_totals($VAR['invoice_id'])                                                          );
$smarty->assign('parts_items_sub_totals',      get_parts_items_sub_totals($VAR['invoice_id'])                                                           );
$smarty->assign('voucher_items_sub_totals',    get_invoice_vouchers_sub_totals($VAR['invoice_id'])                                                       );

// Payment Details
$smarty->assign('payment_types',            get_payment_types()                                                                                 );
$smarty->assign('payment_methods',          get_payment_methods()                                                             ); 
$smarty->assign('payment_statuses',         get_payment_statuses()                                                                              );
$smarty->assign('display_payments',         display_payments('payment_id', 'DESC', false, null, null, null, null, null, null, null, null, null, $VAR['invoice_id'])  );

// Misc
$smarty->assign('employee_display_name',    get_user_details(get_invoice_details($VAR['invoice_id'], 'employee_id'), 'display_name') );
$smarty->assign('invoice_statuses',         get_invoice_statuses()                                                                   );
$smarty->assign('voucher_statuses',        get_voucher_statuses()                                                                   );


// Build the page
$BuildPage .= $smarty->fetch('invoice/edit.tpl');