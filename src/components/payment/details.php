<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'customer.php');
require(INCLUDES_DIR.'payment.php');
require(INCLUDES_DIR.'user.php');

// Check if we have an expense_id
if(!isset($VAR['payment']) || !$VAR['payment_id']) {
    force_page('payment', 'search', 'warning_msg='._gettext("No Payment ID supplied."));
}

// Build the page
$payment_details = get_payment_details($VAR['payment_id']);
$smarty->assign('employee_display_name', get_user_details($payment_details['employee_id'], 'display_name'));
$smarty->assign('customer_display_name', get_customer_details($payment_details['customer_id'], 'display_name'));
$smarty->assign('payment_methods', get_payment_accepted_methods());
$smarty->assign('payment_details', $payment_details);
$BuildPage .= $smarty->fetch('payment/details.tpl');