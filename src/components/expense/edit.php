<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'components/expense.php');
require(INCLUDES_DIR.'components/payment.php');

// Check if we have an expense_id
if(!isset($VAR['expense_id']) || !$VAR['expense_id']) {
    force_page('expense', 'search', 'warning_msg='._gettext("No Expense ID supplied."));
}

// If details submitted run update values, if not set load edit.tpl and populate values
if(isset($VAR['submit'])) {    
        
        update_expense($VAR['expense_id'], $VAR);        
        force_page('expense', 'details&expense_id='.$VAR['expense_id'], 'information_msg='._gettext("Expense updated successfully.")); 

} else {
    
    // Build the page
    $smarty->assign('expense_types', get_expense_types());
    $smarty->assign('payment_methods', get_payment_purchase_methods());
    $smarty->assign('expense_details', get_expense_details($VAR['expense_id']));
    $BuildPage .= $smarty->fetch('expense/edit.tpl');
    
}