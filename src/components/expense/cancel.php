<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Prevent direct access to this page
if(!$this->app->system->security->checkPageAccessedViaQwcrm('expense', 'status')) {
    header('HTTP/1.1 403 Forbidden');
    die(_gettext("No Direct Access Allowed."));
}

// Check if we have an expense_id
if(!isset(\CMSApplication::$VAR['expense_id']) || !\CMSApplication::$VAR['expense_id']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Expense ID supplied."));
    $this->app->system->page->forcePage('expense', 'search');
}

// Run the cancel function if allowed
if(!$this->app->components->expense->checkRecordAllowsCancel(\CMSApplication::$VAR['expense_id'])) {
    $this->app->system->page->forcePage('expense', 'details&expense_id='.\CMSApplication::$VAR['expense_id']);
} else {
    $this->app->components->expense->cancelRecord(\CMSApplication::$VAR['expense_id'], \CMSApplication::$VAR['qform']['reason_for_cancelling']);
    $this->app->system->page->forcePage('expense', 'search');
}
