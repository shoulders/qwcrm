<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Check if the record can be created
if(!$this->app->components->expense->checkRecordCanBeCreated(\CMSApplication::$VAR['supplier_id'] ?? null)) {
    $this->app->system->page->forcePage('expense', 'search');
} else {

    // Create the expense record and return the new expense_id
    \CMSApplication::$VAR['expense_id'] = $this->app->components->expense->insertRecord(\CMSApplication::$VAR['supplier_id'] ?? null);

    // Load the newly created expense edit page
    $this->app->system->page->forcePage('expense', 'edit&expense_id='.\CMSApplication::$VAR['expense_id']);

}
