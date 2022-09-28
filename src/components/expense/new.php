<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

/* Check if we have a supplier_id
if(!isset(\CMSApplication::$VAR['supplier_id']) || !\CMSApplication::$VAR['supplier_id_id']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Supplier ID supplied."));
    $this->app->system->page->forcePage('expense', 'search');
}*/

// This is a workaround whilst supplier IDs are not enforced
\CMSApplication::$VAR['supplier_id'] = null;

// Create the expense record and return the new expense_id
\CMSApplication::$VAR['expense_id'] = $this->app->components->expense->insertRecord(\CMSApplication::$VAR['supplier_id']);

// Load the newly created invoice edit page
$this->app->system->page->forcePage('expense', 'edit&expense_id='.\CMSApplication::$VAR['expense_id']);