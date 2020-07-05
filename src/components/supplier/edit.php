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

// If details submitted run update values, if not set load edit.tpl and populate values
if(isset(\CMSApplication::$VAR['submit'])) {    
        
    // update the supplier record
    $this->app->components->supplier->updateRecord(\CMSApplication::$VAR['qform']);
    
    // load the supplier details apge
    $this->app->system->page->force_page('supplier', 'details&supplier_id='.\CMSApplication::$VAR['supplier_id'], 'msg_success='._gettext("Supplier updated successfully."));     
    
} else {
    
    // Check if supplier can be edited
    if(!$this->app->components->supplier->checkStatusAllowsEdit(\CMSApplication::$VAR['supplier_id'])) {
        $this->app->system->variables->systemMessagesWrite('danger', _gettext("You cannot edit this supplier because its status does not allow it."));
        $this->app->system->page->force_page('supplier', 'details&supplier_id='.\CMSApplication::$VAR['supplier_id']);
    }

    // Build the page
    $this->app->smarty->assign('supplier_statuses',   $this->app->components->supplier->getStatuses()   );
    $this->app->smarty->assign('supplier_types', $this->app->components->supplier->getTypes());
    $this->app->smarty->assign('supplier_details', $this->app->components->supplier->getRecord(\CMSApplication::$VAR['supplier_id']));

}