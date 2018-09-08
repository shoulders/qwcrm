<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'expense.php');

// Prevent direct access to this page
if(!check_page_accessed_via_qwcrm()) {
    header('HTTP/1.1 403 Forbidden');
    die(_gettext("No Direct Access Allowed."));
}

// Check if we have an expense_id
if(!isset($VAR['expense_id']) || !$VAR['expense_id']) {
    force_page('expense', 'search', 'warning_msg='._gettext("No Expense ID supplied."));
}   

// Delete the expense
delete_expense($VAR['expense_id']);

// Load the expense search page
force_page('expense', 'search', 'information_msg='._gettext("Expense deleted successfully."));
exit;