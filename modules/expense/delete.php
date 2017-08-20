<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/expense.php');

// Prevent direct access to this page
if(!check_page_accessed_via_qwcrm()) {
    force_page('expense', 'search', 'warning_msg='.gettext("No Direct Access Allowed"));
    exit;
}

// Check if we have an expense_id
if($expense_id == '') {
    force_page('expense', 'search', 'warning_msg='.gettext("No Expense ID supplied."));
    exit;
}   

// Delete the expense
delete_expense($db, $expense_id);

// Load the expense search page
force_page('expense', 'search');
exit;