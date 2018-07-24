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
require(INCLUDES_DIR.'schedule.php');
require(INCLUDES_DIR.'user.php');
require(INCLUDES_DIR.'workorder.php');

// Prevent undefined variable errors
$VAR['page_no'] = isset($VAR['page_no']) ? $VAR['page_no'] : null;

// Check if we have a client_id
if(!isset($VAR['client_id']) || !$VAR['client_id']) {
    force_page('client', 'search', 'warning_msg='._gettext("No Client ID supplied."));
}

// Build the page
$smarty->assign('client_types',             get_client_types()                                                                                                    );
$smarty->assign('client_details',           get_client_details($VAR['client_id'])                                                                                 );
$smarty->assign('client_notes',             get_client_notes($VAR['client_id'])                                                                                   );

$smarty->assign('workorder_statuses',       get_workorder_statuses()                                                                                             );
$smarty->assign('workorders_open',          display_workorders('workorder_id', 'DESC', false, '25', $VAR['page_no'], null, null, 'open', null, $VAR['client_id'])          );
$smarty->assign('workorders_closed',        display_workorders('workorder_id', 'DESC', false, '25', $VAR['page_no'], null, null, 'closed', null, $VAR['client_id'])       );

$smarty->assign('display_schedules',        display_schedules('schedule_id', 'DESC', false, null, null, null, null, null, null, $VAR['client_id'])  );

$smarty->assign('invoices_pending',         display_invoices('invoice_id', 'DESC', false, '25', $VAR['page_no'], null, null, 'pending', null, $VAR['client_id'])           );
$smarty->assign('invoices_unpaid',          display_invoices('invoice_id', 'DESC', false, '25', $VAR['page_no'], null, null, 'unpaid', null, $VAR['client_id'])            );
$smarty->assign('invoices_partially_paid',  display_invoices('invoice_id', 'DESC', false, '25', $VAR['page_no'], null, null, 'partially_paid', null, $VAR['client_id'])    );
$smarty->assign('invoices_paid',            display_invoices('invoice_id', 'DESC', false, '25', $VAR['page_no'], null, null, 'paid', null, $VAR['client_id'])              );
$smarty->assign('invoice_statuses',         get_invoice_statuses()                                                                                               );

$smarty->assign('giftcerts_unused',         display_giftcerts('giftcert_id', 'DESC', false, '25', $VAR['page_no'], null, null, 'unused', null, $VAR['client_id'])         );
$smarty->assign('giftcerts_redeemed',       display_giftcerts('giftcert_id', 'DESC', false, '25', $VAR['page_no'], null, null, 'redeemed', null, $VAR['client_id'])         );

$smarty->assign('GoogleMapString',          build_googlemap_directions_string($VAR['client_id'], $user->login_user_id)                                                    );


$BuildPage .= $smarty->fetch('client/details.tpl');