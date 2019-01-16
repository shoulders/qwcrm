<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'client.php');
require(INCLUDES_DIR.'payment.php');
require(INCLUDES_DIR.'user.php');

// Check if we have an payment_id
if(!isset($VAR['payment_id']) || !$VAR['payment_id']) {
    force_page('payment', 'search', 'warning_msg='._gettext("No Payment ID supplied."));
}
    
$payment_details = get_payment_details($VAR['payment_id']);

// Prevent undefined variable errors
$client_display_name = $payment_details['client_id'] ? get_client_details($payment_details['client_id'], 'display_name') : null;
$employee_display_name = $payment_details['employee_id'] ? get_user_details($payment_details['employee_id'], 'display_name') : null;

// Build the page
$smarty->assign('client_display_name',      $client_display_name);
$smarty->assign('employee_display_name',    $employee_display_name);
$smarty->assign('payment_types',            get_payment_types()    );
$smarty->assign('payment_methods',          get_payment_methods()  ); 
$smarty->assign('payment_statuses',         get_payment_statuses() );
$smarty->assign('payment_details', $payment_details);
$BuildPage .= $smarty->fetch('payment/details.tpl');