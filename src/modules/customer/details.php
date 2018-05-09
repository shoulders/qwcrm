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
require(INCLUDES_DIR.'modules/giftcert.php');
require(INCLUDES_DIR.'modules/user.php');
require(INCLUDES_DIR.'modules/workorder.php');


// Check if we have a customer_id
if($customer_id == '') {
    force_page('customer', 'search', 'warning_msg='._gettext("No Customer ID supplied."));
    exit;
}

// Build the page
$smarty->assign('customer_types',           get_customer_types($db)                                                                                                 );
$smarty->assign('customer_details',         get_customer_details($db, $customer_id)                                                                                 );

$smarty->assign('workorder_statuses',       get_workorder_statuses($db)                                                                                             );
$smarty->assign('workorders_open',          display_workorders($db, 'workorder_id', 'DESC', false, $page_no, '25', null, null, 'open', null, $customer_id)          );
$smarty->assign('workorders_closed',        display_workorders($db, 'workorder_id', 'DESC', false, $page_no, '25', null, null, 'closed', null, $customer_id)        );

$smarty->assign('invoices_pending',         display_invoices($db, 'invoice_id', 'DESC', false, $page_no, '25', null, null, 'pending', null, $customer_id)           );
$smarty->assign('invoices_unpaid',          display_invoices($db, 'invoice_id', 'DESC', false, $page_no, '25', null, null, 'unpaid', null, $customer_id)            );
$smarty->assign('invoices_partially_paid',  display_invoices($db, 'invoice_id', 'DESC', false, $page_no, '25', null, null, 'partially_paid', null, $customer_id)    );
$smarty->assign('invoices_paid',            display_invoices($db, 'invoice_id', 'DESC', false, $page_no, '25', null, null, 'paid', null, $customer_id)              );
$smarty->assign('invoice_statuses',         get_invoice_statuses($db)                                                                                               );

$smarty->assign('active_giftcerts',         display_giftcerts($db, 'giftcert_id', 'DESC', false, $page_no, '25', null, null, null, '0', null, $customer_id)         );
$smarty->assign('redeemed_giftcerts',       display_giftcerts($db, 'giftcert_id', 'DESC', false, $page_no, '25', null, null, null, '1', null, $customer_id)         );

$smarty->assign('GoogleMapString',          build_googlemap_directions_string($db, $customer_id, $login_user_id)                                                    );
$smarty->assign('customer_notes',           get_customer_notes($db, $customer_id)                                                                                   );

$BuildPage .= $smarty->fetch('customer/details.tpl');