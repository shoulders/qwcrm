<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Create the expense record and return the new expense_id
\CMSApplication::$VAR['expense_id'] = $this->app->components->expense->insertRecord(\CMSApplication::$VAR['supplier_id'] ?? null);

// Load the newly created invoice edit page
$this->app->system->page->forcePage('expense', 'edit&expense_id='.\CMSApplication::$VAR['expense_id']);
