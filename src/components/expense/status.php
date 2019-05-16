<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'expense.php');
require(INCLUDES_DIR.'report.php');

// Check if we have a expense_id
if(!isset($VAR['expense_id']) || !$VAR['expense_id']) {
    force_page('expense', 'search', 'warning_msg='._gettext("No Expense ID supplied."));
}

// Update Expense Status
if(isset($VAR['change_status'])){
    update_expense_status($VAR['expense_id'], $VAR['assign_status']);    
    force_page('expense', 'status&expense_id='.$VAR['expense_id']);
}

// Build the page with the current status from the database
$smarty->assign('allowed_to_change_status',        false       ); // I am not sure this is needed
$smarty->assign('expense_status',                  get_expense_details($VAR['expense_id'], 'status')             );
$smarty->assign('expense_statuses',                get_expense_statuses() );
$smarty->assign('allowed_to_cancel',               check_expense_can_be_cancelled($VAR['expense_id'])     );
$smarty->assign('allowed_to_delete',               check_expense_can_be_deleted($VAR['expense_id'])              );
$smarty->assign('expense_selectable_statuses',     get_expense_statuses(true) );

$BuildPage .= $smarty->fetch('expense/status.tpl');