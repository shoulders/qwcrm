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
require(INCLUDES_DIR.'report.php');
require(INCLUDES_DIR.'schedule.php');
require(INCLUDES_DIR.'user.php');
require(INCLUDES_DIR.'payment.php');
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
$smarty->assign('client_notes',             get_client_notes($VAR['client_id'])                                                                                );

$smarty->assign('GoogleMapString',          build_googlemap_directions_string($VAR['client_id'], $user->login_user_id)                                                     );

$smarty->assign('workorder_statuses',       get_workorder_statuses()                                                                                             );
$smarty->assign('workorders_open',          display_workorders('workorder_id', 'DESC', false, '25', $VAR['page_no'], null, null, 'open', null, $VAR['client_id'])          );
$smarty->assign('workorders_closed',        display_workorders('workorder_id', 'DESC', false, '25', $VAR['page_no'], null, null, 'closed', null, $VAR['client_id'])       );

$smarty->assign('display_schedules',        display_schedules('schedule_id', 'DESC', false, null, null, null, null, null, null, $VAR['client_id'])  );

$smarty->assign('invoices_open',            display_invoices('invoice_id', 'DESC', false, '25', $VAR['page_no'], null, null, 'open', null, $VAR['client_id'])           );
$smarty->assign('invoices_closed',          display_invoices('invoice_id', 'DESC', false, '25', $VAR['page_no'], null, null, 'closed', null, $VAR['client_id'])            );

$smarty->assign('giftcert_statuses',        get_giftcert_statuses()                                                                                                        );
$smarty->assign('giftcerts_purchased',      display_giftcerts('giftcert_id', 'DESC', false, '25', $VAR['page_no'], null, null, null, null, $VAR['client_id'])              );
$smarty->assign('giftcerts_claimed',        display_giftcerts('giftcert_id', 'DESC', false, '25', $VAR['page_no'], null, null, 'redeemed', null, null, null, null, $VAR['client_id'])        );

$smarty->assign('payment_types',            get_payment_types()                                                                                 );
$smarty->assign('payment_methods',          get_payment_methods()                                                                               );
$smarty->assign('payment_statuses',         get_payment_statuses()                                                                              );
$smarty->assign('payments_received',        display_payments('payment_id', 'DESC', false, '25', $VAR['page_no'], null, null, 'received', null, null, null, $VAR['client_id'])        );
$smarty->assign('payments_transmitted',     display_payments('payment_id', 'DESC', false, '25', $VAR['page_no'], null, null, 'transmitted', null, null, null, $VAR['client_id'])        );
                                            
$smarty->assign('workorder_stats',          get_workorders_stats('all', null, null, null, $VAR['client_id'])  );
$smarty->assign('invoice_stats',            get_invoices_stats('all', null, null, null, $VAR['client_id'])  );
$smarty->assign('giftcert_stats',           get_giftcerts_stats('all', null, null, null, $VAR['client_id'])  );
$smarty->assign('payment_stats',            get_payments_stats('all', null, null, null, $VAR['client_id'])   );

$BuildPage .= $smarty->fetch('client/details.tpl');