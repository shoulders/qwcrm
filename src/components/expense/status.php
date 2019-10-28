<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Check if we have a expense_id
if(!isset(\CMSApplication::$VAR['expense_id']) || !\CMSApplication::$VAR['expense_id']) {
    systemMessagesWrite('danger', _gettext("No Expense ID supplied."));
    force_page('expense', 'search');
}

// Update Expense Status
if(isset(\CMSApplication::$VAR['change_status'])){
    update_expense_status(\CMSApplication::$VAR['expense_id'], \CMSApplication::$VAR['assign_status']);    
    force_page('expense', 'status&expense_id='.\CMSApplication::$VAR['expense_id']);
}

// Build the page with the current status from the database
$smarty->assign('allowed_to_change_status',        false       ); // I am not sure this is needed
$smarty->assign('expense_status',                  get_expense_details(\CMSApplication::$VAR['expense_id'], 'status')             );
$smarty->assign('expense_statuses',                get_expense_statuses() );
$smarty->assign('allowed_to_cancel',               check_expense_can_be_cancelled(\CMSApplication::$VAR['expense_id'])     );
$smarty->assign('allowed_to_delete',               check_expense_can_be_deleted(\CMSApplication::$VAR['expense_id'])              );
$smarty->assign('expense_selectable_statuses',     get_expense_statuses(true) );