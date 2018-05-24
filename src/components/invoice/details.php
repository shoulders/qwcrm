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
    exit;
}

// Prefill Items
$smarty->assign('company_details',          get_company_details($db)                                                                    );
$smarty->assign('customer_details',         get_customer_details($db, get_invoice_details($db, $VAR['invoice_id'], 'customer_id'))             );
$smarty->assign('workorder_details',        get_workorder_details($db, get_invoice_details($db, $VAR['invoice_id'], 'workorder_id'))           );
$smarty->assign('invoice_details',          get_invoice_details($db, $VAR['invoice_id'])                                                       );

// Invoice Items
$smarty->assign('labour_items',             get_invoice_labour_items($db, $VAR['invoice_id'])                                                  );
$smarty->assign('parts_items',              get_invoice_parts_items($db, $VAR['invoice_id'])                                                   );

// Invoice Totals
$smarty->assign('labour_sub_total',         labour_sub_total($db, $VAR['invoice_id'])                                                          );
$smarty->assign('parts_sub_total',          parts_sub_total($db, $VAR['invoice_id'])                                                           );

$smarty->assign('display_transactions',     display_transactions($db, $VAR['invoice_id'])                                                  );
$smarty->assign('transaction_statuses',     get_payment_system_methods($db)                                                             );

// Misc
$smarty->assign('employee_display_name',    get_user_details($db, get_invoice_details($db, $VAR['invoice_id'], 'employee_id'),'display_name')  );
$smarty->assign('invoice_statuses',         get_invoice_statuses($db)                                                                   );

// Build the page
$BuildPage .= $smarty->fetch('invoice/details.tpl');