<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'client.php');
require(INCLUDES_DIR.'user.php');
require(INCLUDES_DIR.'voucher.php');

// Check if we have an voucher_id
if(!isset(\QFactory::$VAR['voucher_id']) || !\QFactory::$VAR['voucher_id']) {
    systemMessagesWrite('danger', _gettext("No Voucher ID supplied."));
    force_page('voucher', 'search');
}

$voucher_details = get_voucher_details(\QFactory::$VAR['voucher_id']);
$redeemed_client_display_name = $voucher_details['redeemed_client_id'] ? get_client_details($voucher_details['redeemed_client_id'], 'display_name') : null;

// Build the page
$smarty->assign('client_details',               get_client_details($voucher_details['client_id'])                          );
$smarty->assign('redeemed_client_display_name', $redeemed_client_display_name                                               );
$smarty->assign('employee_display_name',        get_user_details($voucher_details['employee_id'], 'display_name')          );
$smarty->assign('voucher_statuses',            get_voucher_statuses()                                                     );
$smarty->assign('voucher_types',               get_voucher_types()                                                     );
$smarty->assign('voucher_details',             $voucher_details                                                           );