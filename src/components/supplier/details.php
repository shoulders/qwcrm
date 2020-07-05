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
    $this->app->system->page->force_page('supplier', 'search');
}  

// Build the page
$this->app->smarty->assign('supplier_statuses',   $this->app->components->supplier->getStatuses()   );
$this->app->smarty->assign('supplier_types', $this->app->components->supplier->getTypes());
$this->app->smarty->assign('supplier_details', $this->app->components->supplier->getRecord(\CMSApplication::$VAR['supplier_id']));