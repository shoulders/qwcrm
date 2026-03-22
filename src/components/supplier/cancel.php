<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Prevent direct access to this page
if(!$this->app->system->security->checkPageAccessedViaQwcrm('supplier', 'status')) {
    header('HTTP/1.1 403 Forbidden');
    die(_gettext("No Direct Access Allowed."));
}

// Check if we have a supplier_id
if(!isset(\CMSApplication::$VAR['supplier_id']) || !\CMSApplication::$VAR['supplier_id']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Supplier ID supplied."));
    $this->app->system->page->forcePage('supplier', 'search');
}

// Run the cancel function if allowed
if(!$this->app->components->supplier->checkRecordAllowsCancel(\CMSApplication::$VAR['supplier_id'])) {
    $this->app->system->page->forcePage('supplier', 'details&supplier_id='.\CMSApplication::$VAR['supplier_id']);
} else {
    $this->app->components->supplier->cancelRecord(\CMSApplication::$VAR['supplier_id']);   // TODO: Consider adding eason for cancelling
    $this->app->system->page->forcePage('supplier', 'search');
}
