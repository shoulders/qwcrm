<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Prevent direct access to this page
if(!check_page_accessed_via_qwcrm('expense', 'status')) {
    header('HTTP/1.1 403 Forbidden');
    die(_gettext("No Direct Access Allowed."));
}

// Check if we have an expense_id
if(!isset(\CMSApplication::$VAR['expense_id']) || !\CMSApplication::$VAR['expense_id']) {
    systemMessagesWrite('danger', _gettext("No Expense ID supplied."));
    force_page('expense', 'search');
}   

// Cancel the expense
cancel_expense(\CMSApplication::$VAR['expense_id']);

// Load the expense search page
force_page('expense', 'search', 'msg_success='._gettext("Expense cancelled successfully."));