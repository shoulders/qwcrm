<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/expense.php');
require(INCLUDES_DIR.'modules/payment.php');

// Check if we have an expense_id
if($expense_id == '') {
    force_page('expense', 'search', 'warning_msg='._gettext("No Expense ID supplied."));
    exit;
}

// Build the page
$smarty->assign('expense_types', get_expense_types($db));
$smarty->assign('payment_methods', get_payment_manual_methods($db));
$smarty->assign('expense_details', get_expense_details($db, $expense_id));
$BuildPage .= $smarty->fetch('expense/details.tpl');