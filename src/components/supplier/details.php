<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Check if we have a supplier_id
if(!isset(\CMSApplication::$VAR['supplier_id']) || !\CMSApplication::$VAR['supplier_id']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Supplier ID supplied."));
    $this->app->system->page->forcePage('supplier', 'search');
}

// Build the page
$this->app->smarty->assign('supplier_statuses',   $this->app->components->supplier->getStatuses()   );
$this->app->smarty->assign('supplier_types', $this->app->components->supplier->getTypes());
$this->app->smarty->assign('supplier_details', $this->app->components->supplier->getRecord(\CMSApplication::$VAR['supplier_id']));
$this->app->smarty->assign('allowed_to_create_creditnote', $this->app->components->creditnote->checkRecordCanBeCreated(null, null, \CMSApplication::$VAR['supplier_id'], null));

$this->app->smarty->assign('creditnote_types',            $this->app->components->creditnote->getTypes());
$this->app->smarty->assign('creditnote_statuses',         $this->app->components->creditnote->getStatuses());
$this->app->smarty->assign('display_creditnotes',        $this->app->components->creditnote->getRecords('creditnote_id', 'DESC', 25, false, null, null, null, null, null, null, \CMSApplication::$VAR['supplier_id']));


$this->app->smarty->assign('expense_types',            $this->app->components->expense->getTypes());
$this->app->smarty->assign('expense_statuses',         $this->app->components->expense->getStatuses());
$this->app->smarty->assign('display_expenses',        $this->app->components->expense->getRecords('expense_id', 'DESC', 25, false, null, null, null, null, null, \CMSApplication::$VAR['supplier_id']));
