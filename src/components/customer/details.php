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
require(INCLUDES_DIR.'components/giftcert.php');
require(INCLUDES_DIR.'components/schedule.php');
require(INCLUDES_DIR.'components/user.php');
require(INCLUDES_DIR.'components/workorder.php');

// Prevent undefined variable errors
$VAR['page_no'] = isset($VAR['page_no']) ? $VAR['page_no'] : null;

// Check if we have a customer_id
if($VAR['customer_id'] == '') {
    force_page('customer', 'search', 'warning_msg='._gettext("No Customer ID supplied."));
}

// Build the page
$smarty->assign('customer_types',           get_customer_types()                                                                                                 );
$smarty->assign('customer_details',         get_customer_details($VAR['customer_id'])                                                                                 );

$smarty->assign('workorder_statuses',       get_workorder_statuses()                                                                                             );
$smarty->assign('workorders_open',          display_workorders('workorder_id', 'DESC', false, '25', $VAR['page_no'], null, null, 'open', null, $VAR['customer_id'])          );
$smarty->assign('workorders_closed',        display_workorders('workorder_id', 'DESC', false, '25', $VAR['page_no'], null, null, 'closed', null, $VAR['customer_id'])       );

$smarty->assign('display_schedules',        display_schedules('schedule_id', 'DESC', false, null, null, null, null, null, null, $VAR['customer_id'])  );

$smarty->assign('invoices_pending',         display_invoices('invoice_id', 'DESC', false, '25', $VAR['page_no'], null, null, 'pending', null, $VAR['customer_id'])           );
$smarty->assign('invoices_unpaid',          display_invoices('invoice_id', 'DESC', false, '25', $VAR['page_no'], null, null, 'unpaid', null, $VAR['customer_id'])            );
$smarty->assign('invoices_partially_paid',  display_invoices('invoice_id', 'DESC', false, '25', $VAR['page_no'], null, null, 'partially_paid', null, $VAR['customer_id'])    );
$smarty->assign('invoices_paid',            display_invoices('invoice_id', 'DESC', false, '25', $VAR['page_no'], null, null, 'paid', null, $VAR['customer_id'])              );
$smarty->assign('invoice_statuses',         get_invoice_statuses()                                                                                               );

$smarty->assign('giftcerts_active',         display_giftcerts('giftcert_id', 'DESC', false, '25', $VAR['page_no'], null, null, null, '0', null, $VAR['customer_id'])         );
$smarty->assign('giftcerts_redeemed',       display_giftcerts('giftcert_id', 'DESC', false, '25', $VAR['page_no'], null, null, null, '1', null, $VAR['customer_id'])         );

$smarty->assign('GoogleMapString',          build_googlemap_directions_string($VAR['customer_id'], $user->login_user_id)                                                    );
$smarty->assign('customer_notes',           get_customer_notes($VAR['customer_id'])                                                                                   );

$BuildPage .= $smarty->fetch('customer/details.tpl');