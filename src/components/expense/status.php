<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Check if we have a expense_id
if(!isset(\CMSApplication::$VAR['expense_id']) || !\CMSApplication::$VAR['expense_id']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Expense ID supplied."));
    $this->app->system->page->force_page('expense', 'search');
}

// Update Expense Status
if(isset(\CMSApplication::$VAR['change_status'])){
    $this->app->components->expense->updateStatus(\CMSApplication::$VAR['expense_id'], \CMSApplication::$VAR['assign_status']);    
    $this->app->system->page->force_page('expense', 'status&expense_id='.\CMSApplication::$VAR['expense_id']);
}

// Build the page with the current status from the database
$this->app->smarty->assign('allowed_to_change_status',        false       ); // I am not sure this is needed
$this->app->smarty->assign('expense_status',                  $this->app->components->expense->getRecord(\CMSApplication::$VAR['expense_id'], 'status')             );
$this->app->smarty->assign('expense_statuses',                $this->app->components->expense->getStatuses() );
$this->app->smarty->assign('allowed_to_cancel',               $this->app->components->expense->checkStatusAllowsCancel(\CMSApplication::$VAR['expense_id'])     );
$this->app->smarty->assign('allowed_to_delete',               $this->app->components->expense->checkStatusAllowsDelete(\CMSApplication::$VAR['expense_id'])              );
$this->app->smarty->assign('expense_selectable_statuses',     $this->app->components->expense->getStatuses(true) );