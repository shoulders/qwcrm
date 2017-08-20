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
    
$smarty->assign('company_details',          get_company_details($db)                                                                    );
$smarty->assign('customer_details',         get_customer_details($db, get_invoice_details($db, $invoice_id, 'customer_id'))             );
$smarty->assign('workorder_details',        get_workorder_details($db, get_invoice_details($db, $invoice_id, 'workorder_id'))           ); 
$smarty->assign('invoice_details',          get_invoice_details($db, $invoice_id)                                                       );
$smarty->assign('workorder_id',             get_invoice_details($db, $invoice_id, 'workorder_id')                                       );
$smarty->assign('labour_items',             get_invoice_labour_items($db, $invoice_id)                                                  );
$smarty->assign('parts_items',              get_invoice_parts_items($db, $invoice_id)                                                   );
$smarty->assign('transactions',             get_invoice_transactions($db, $invoice_id)                                                  );
$smarty->assign('labour_sub_total',         labour_sub_total($db, $invoice_id)                                                          );
$smarty->assign('parts_sub_total',          parts_sub_total($db, $invoice_id)                                                           );
$smarty->assign('employee_display_name',    get_user_details($db, get_invoice_details($db, $invoice_id, 'employee_id'),'display_name')  );
     
$BuildPage .= $smarty->fetch('invoice/details.tpl');