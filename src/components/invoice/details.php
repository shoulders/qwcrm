<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Check if we have an invoice_id
if(!isset(\CMSApplication::$VAR['invoice_id']) || !\CMSApplication::$VAR['invoice_id']) {
    systemMessagesWrite('danger', _gettext("No Invoice ID supplied."));
    force_page('invoice', 'search');
}

$invoice_details = get_invoice_details(\CMSApplication::$VAR['invoice_id']);

// Invoice Details
$smarty->assign('company_details',          get_company_details()                                                                    );
$smarty->assign('client_details',           get_client_details($invoice_details['client_id'])             );
$smarty->assign('workorder_details',        get_workorder_details($invoice_details['workorder_id'])           );
$smarty->assign('invoice_details',          get_invoice_details(\CMSApplication::$VAR['invoice_id'])                                                       );

// Prefill Items
$smarty->assign('vat_tax_codes',            get_vat_tax_codes()                                                               );

// Invoice Items
$smarty->assign('labour_items',             get_invoice_labour_items(\CMSApplication::$VAR['invoice_id'])                                                  );
$smarty->assign('parts_items',              get_invoice_parts_items(\CMSApplication::$VAR['invoice_id'])                                                   );
$smarty->assign('display_vouchers',        display_vouchers('voucher_id', 'DESC', false, '25', null, null, null, null, null, null, null, \CMSApplication::$VAR['invoice_id']) );

// Sub Totals
$smarty->assign('labour_items_sub_totals',         get_labour_items_sub_totals(\CMSApplication::$VAR['invoice_id'])                                                          );
$smarty->assign('parts_items_sub_totals',          get_parts_items_sub_totals(\CMSApplication::$VAR['invoice_id'])                                                           );
$smarty->assign('voucher_sub_totals',            get_invoice_vouchers_sub_totals(\CMSApplication::$VAR['invoice_id'])                                                       );

/* Refund Details - This is not used and should be deleted when i am sure I will not use it. I might use something like this to include the refund block
if(get_invoice_details(\CMSApplication::$VAR['invoice_id'], 'status') == 'refunded') {
    $smarty->assign('refund_details', get_refund_details($invoice_details['refund_id']));    
} else {
    $smarty->assign('refund_details', null);
}*/

// Payment Details
$smarty->assign('payment_types',            get_payment_types()                                                                                 );
$smarty->assign('payment_methods',          get_payment_methods()                                                             ); 
$smarty->assign('payment_statuses',         get_payment_statuses()                                                                              );
$smarty->assign('display_payments',         display_payments('payment_id', 'DESC', false, null, null, null, null, null, null, null, null, null, \CMSApplication::$VAR['invoice_id'])  );

// Misc
$smarty->assign('employee_display_name',    get_user_details($invoice_details['employee_id'], 'display_name')  );
$smarty->assign('invoice_statuses',         get_invoice_statuses()                                                                   );
$smarty->assign('voucher_statuses',        get_voucher_statuses()                                                                   );