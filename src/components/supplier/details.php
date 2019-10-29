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
    $this->app->system->general->force_page('supplier', 'search');
}  

// Build the page
$this->app->smarty->assign('supplier_statuses',   $this->app->components->supplier->get_supplier_statuses()   );
$this->app->smarty->assign('supplier_types', $this->app->components->supplier->get_supplier_types());
$this->app->smarty->assign('supplier_details', $this->app->components->supplier->get_supplier_details(\CMSApplication::$VAR['supplier_id']));