<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'company.php');
require(INCLUDES_DIR.'client.php');
require(INCLUDES_DIR.'expense.php');
require(INCLUDES_DIR.'payment.php');
require(INCLUDES_DIR.'report.php');

// Check if we have an expense_id
if(!isset(\QFactory::$VAR['expense_id']) || !\QFactory::$VAR['expense_id']) {
    systemMessagesWrite('danger', _gettext("No Expense ID supplied."));
    force_page('expense', 'search');
}

// If details submitted run update values, if not set load edit.tpl and populate values
if(isset(\QFactory::$VAR['submit'])) {    
        
        update_expense(\QFactory::$VAR['qform']);
        recalculate_expense_totals(\QFactory::$VAR['qform']['expense_id']);
        force_page('expense', 'details&expense_id='.\QFactory::$VAR['qform']['expense_id'], 'msg_success='._gettext("Expense updated successfully.")); 

} else {
    
    // Check if expense can be edited
    if(!check_expense_can_be_edited(\QFactory::$VAR['expense_id'])) {
        systemMessagesWrite('danger', _gettext("You cannot edit this expense because its status does not allow it."));
        force_page('expense', 'details&expense_id='.\QFactory::$VAR['expense_id']);
    }
    
    // Build the page       
    $smarty->assign('expense_statuses', get_expense_statuses()            );
    $smarty->assign('expense_types', get_expense_types());
    $smarty->assign('vat_tax_codes', get_vat_tax_codes(false));
    $smarty->assign('expense_details', get_expense_details(\QFactory::$VAR['expense_id']));
    
}