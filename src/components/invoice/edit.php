<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(CINCLUDES_DIR.'client.php');
require(CINCLUDES_DIR.'company.php');
require(CINCLUDES_DIR.'invoice.php');
require(CINCLUDES_DIR.'payment.php');
require(CINCLUDES_DIR.'report.php');
require(CINCLUDES_DIR.'user.php');
require(CINCLUDES_DIR.'voucher.php');
require(CINCLUDES_DIR.'workorder.php');

// Prevent undefined variable errors
\CMSApplication::$VAR['qform']['labour_items'] = isset(\CMSApplication::$VAR['qform']['labour_items']) ? \CMSApplication::$VAR['qform']['labour_items'] : null;
\CMSApplication::$VAR['qform']['parts_items'] = isset(\CMSApplication::$VAR['qform']['parts_items']) ? \CMSApplication::$VAR['qform']['parts_items'] : null;

// Check if we have an invoice_id
if(!isset(\CMSApplication::$VAR['invoice_id']) || !\CMSApplication::$VAR['invoice_id']) {
    systemMessagesWrite('danger', _gettext("No Invoice ID supplied."));
    force_page('invoice', 'search');
}

// Check if invoice can be edited
if(!check_invoice_can_be_edited(\CMSApplication::$VAR['invoice_id'])) {
    systemMessagesWrite('danger', _gettext("You cannot edit this invoice because its status does not allow it."));
    force_page('invoice', 'details&invoice_id='.\CMSApplication::$VAR['invoice_id']);
}

##################################
#      Update Invoice            #
##################################

if(isset(\CMSApplication::$VAR['submit'])) {
    
    // insert the parts and labour item arrays
    insert_labour_items(\CMSApplication::$VAR['qform']['invoice_id'], \CMSApplication::$VAR['qform']['labour_items']);
    insert_parts_items(\CMSApplication::$VAR['qform']['invoice_id'], \CMSApplication::$VAR['qform']['parts_items']);
    
    // update and recalculate the invoice
    update_invoice_static_values(\CMSApplication::$VAR['qform']['invoice_id'], \CMSApplication::$VAR['qform']['date'], \CMSApplication::$VAR['qform']['due_date'], \CMSApplication::$VAR['qform']['unit_discount_rate']);    
    recalculate_invoice_totals(\CMSApplication::$VAR['qform']['invoice_id']);
    
}
    
##################################
#     Load invoice edit page     #
################################## 

// Invoice Details
$smarty->assign('company_details',          get_company_details()                                                                  );
$smarty->assign('client_details',           get_client_details(get_invoice_details(\CMSApplication::$VAR['invoice_id'], 'client_id'))               );
$smarty->assign('workorder_details',        get_workorder_details(get_invoice_details(\CMSApplication::$VAR['invoice_id'], 'workorder_id'))         ); 
$smarty->assign('invoice_details',          get_invoice_details(\CMSApplication::$VAR['invoice_id'])                                                );

// Prefill Items
$smarty->assign('labour_prefill_items',     get_invoice_prefill_items('Labour', '1')                                               ); 
$smarty->assign('parts_prefill_items',      get_invoice_prefill_items('Parts', '1')                                                );
$smarty->assign('vat_tax_codes',            get_vat_tax_codes(false)                                                               );
$smarty->assign('default_vat_tax_code',     get_default_vat_tax_code(get_invoice_details(\CMSApplication::$VAR['invoice_id'], 'tax_system'))        );

// Invoice Items
$smarty->assign('labour_items',             get_invoice_labour_items(\CMSApplication::$VAR['invoice_id'])                                                  );
$smarty->assign('parts_items',              get_invoice_parts_items(\CMSApplication::$VAR['invoice_id'])                                                   );
$smarty->assign('display_vouchers',        display_vouchers('voucher_id', 'DESC', false, '25', null, null, null, null, null, null, null, \CMSApplication::$VAR['invoice_id']) );

// Sub Totals
$smarty->assign('labour_items_sub_totals',     get_labour_items_sub_totals(\CMSApplication::$VAR['invoice_id'])                                                          );
$smarty->assign('parts_items_sub_totals',      get_parts_items_sub_totals(\CMSApplication::$VAR['invoice_id'])                                                           );
$smarty->assign('voucher_items_sub_totals',    get_invoice_vouchers_sub_totals(\CMSApplication::$VAR['invoice_id'])                                                       );

// Payment Details
$smarty->assign('payment_types',            get_payment_types()                                                                                 );
$smarty->assign('payment_methods',          get_payment_methods()                                                             ); 
$smarty->assign('payment_statuses',         get_payment_statuses()                                                                              );
$smarty->assign('display_payments',         display_payments('payment_id', 'DESC', false, null, null, null, null, null, null, null, null, null, \CMSApplication::$VAR['invoice_id'])  );

// Misc
$smarty->assign('employee_display_name',    get_user_details(get_invoice_details(\CMSApplication::$VAR['invoice_id'], 'employee_id'), 'display_name') );
$smarty->assign('invoice_statuses',         get_invoice_statuses()                                                                   );
$smarty->assign('voucher_statuses',        get_voucher_statuses()                                                                   );