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
require(INCLUDES_DIR.'giftcert.php');
require(INCLUDES_DIR.'payment.php');
require(INCLUDES_DIR.'refund.php');
require(INCLUDES_DIR.'user.php');
require(INCLUDES_DIR.'workorder.php');

// Check if we have an invoice_id
if(!isset($VAR['invoice_id']) || !$VAR['invoice_id']) {
    force_page('invoice', 'search', 'warning_msg='._gettext("No Invoice ID supplied."));
}

$invoice_details = get_invoice_details($VAR['invoice_id']);

// Prefill Items
$smarty->assign('company_details',          get_company_details()                                                                    );
$smarty->assign('client_details',           get_client_details($invoice_details['client_id'])             );
$smarty->assign('workorder_details',        get_workorder_details($invoice_details['workorder_id'])           );
$smarty->assign('invoice_details',          get_invoice_details($VAR['invoice_id'])                                                       );

// Invoice Items
$smarty->assign('labour_items',             get_invoice_labour_items($VAR['invoice_id'])                                                  );
$smarty->assign('parts_items',              get_invoice_parts_items($VAR['invoice_id'])                                                   );
$smarty->assign('display_giftcerts',        display_giftcerts('giftcert_id', 'DESC', false, '25', null, null, null, null, null, null, null, $VAR['invoice_id']) );

// Invoice Totals
$smarty->assign('labour_sub_total',         labour_sub_total($VAR['invoice_id'])                                                          );
$smarty->assign('parts_sub_total',          parts_sub_total($VAR['invoice_id'])                                                           );
$smarty->assign('giftcerts_sub_total',      giftcerts_sub_total($VAR['invoice_id'])                                                       );

// Refund Details
if(get_invoice_details($VAR['invoice_id'], 'status') == 'refunded') {
    $smarty->assign('refund_details', get_refund_details($invoice_details['refund_id']));    
} else {
    $smarty->assign('refund_details', null);
}

// Payment Details
$smarty->assign('payment_types',            get_payment_types()                                                                                 );
$smarty->assign('payment_methods',          get_payment_methods()                                                             ); 
$smarty->assign('payment_statuses',         get_payment_statuses()                                                                              );
$smarty->assign('display_payments',         display_payments('payment_id', 'DESC', false, null, null, null, null, null, null, null, $VAR['invoice_id'])  );

// Misc
$smarty->assign('employee_display_name',    get_user_details($invoice_details['employee_id'], 'display_name')  );
$smarty->assign('invoice_statuses',         get_invoice_statuses()                                                                   );
$smarty->assign('giftcert_statuses',        get_giftcert_statuses()                                                                   );

// Build the page
$BuildPage .= $smarty->fetch('invoice/details.tpl');