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

// Check if we have an invoice_id
if(!isset($VAR['invoice_id']) || !$VAR['invoice_id']) {
    force_page('invoice', 'search', 'warning_msg='._gettext("No Invoice ID supplied."));
}

// Prefill Items
$smarty->assign('company_details',          get_company_details()                                                                    );
$smarty->assign('client_details',           get_client_details(get_invoice_details($VAR['invoice_id'], 'client_id'))             );
$smarty->assign('workorder_details',        get_workorder_details(get_invoice_details($VAR['invoice_id'], 'workorder_id'))           );
$smarty->assign('invoice_details',          get_invoice_details($VAR['invoice_id'])                                                       );

// Invoice Items
$smarty->assign('labour_items',             get_invoice_labour_items($VAR['invoice_id'])                                                  );
$smarty->assign('parts_items',              get_invoice_parts_items($VAR['invoice_id'])                                                   );

// Invoice Totals
$smarty->assign('labour_sub_total',         labour_sub_total($VAR['invoice_id'])                                                          );
$smarty->assign('parts_sub_total',          parts_sub_total($VAR['invoice_id'])                                                           );

// Misc
$smarty->assign('display_payments',         display_payments('payment_id', 'DESC', false, null, null, null, null, null, null, null, $VAR['invoice_id'])                                                  );
$smarty->assign('payment_methods',          get_payment_accepted_methods()                                                             );
$smarty->assign('employee_display_name',    get_user_details(get_invoice_details($VAR['invoice_id'], 'employee_id'),'display_name')  );
$smarty->assign('invoice_statuses',         get_invoice_statuses()                                                                   );

// Build the page
$BuildPage .= $smarty->fetch('invoice/details.tpl');