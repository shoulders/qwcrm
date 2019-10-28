<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(CINCLUDES_DIR.'company.php');
require(CINCLUDES_DIR.'client.php');
require(CINCLUDES_DIR.'expense.php');
require(CINCLUDES_DIR.'payment.php');
require(CINCLUDES_DIR.'report.php');

// Check if we have an expense_id
if(!isset(\CMSApplication::$VAR['expense_id']) || !\CMSApplication::$VAR['expense_id']) {
    systemMessagesWrite('danger', _gettext("No Expense ID supplied."));
    force_page('expense', 'search');
}

// If details submitted run update values, if not set load edit.tpl and populate values
if(isset(\CMSApplication::$VAR['submit'])) {    
        
        update_expense(\CMSApplication::$VAR['qform']);
        recalculate_expense_totals(\CMSApplication::$VAR['qform']['expense_id']);
        force_page('expense', 'details&expense_id='.\CMSApplication::$VAR['qform']['expense_id'], 'msg_success='._gettext("Expense updated successfully.")); 

} else {
    
    // Check if expense can be edited
    if(!check_expense_can_be_edited(\CMSApplication::$VAR['expense_id'])) {
        systemMessagesWrite('danger', _gettext("You cannot edit this expense because its status does not allow it."));
        force_page('expense', 'details&expense_id='.\CMSApplication::$VAR['expense_id']);
    }
    
    // Build the page       
    $smarty->assign('expense_statuses', get_expense_statuses()            );
    $smarty->assign('expense_types', get_expense_types());
    $smarty->assign('vat_tax_codes', get_vat_tax_codes(false));
    $smarty->assign('expense_details', get_expense_details(\CMSApplication::$VAR['expense_id']));
    
}