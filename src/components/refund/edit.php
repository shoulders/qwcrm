<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Check if we have a refund_id
if(!isset(\CMSApplication::$VAR['refund_id']) || !\CMSApplication::$VAR['refund_id']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Refund ID supplied."));
    $this->app->system->page->forcePage('refund', 'search');
} 

// If details submitted run update values, if not set load edit.tpl and populate values
if(isset(\CMSApplication::$VAR['submit'])) {    
        
    // Update the refund in the database
    $this->app->components->refund->updateRecord(\CMSApplication::$VAR['qform']);
    $this->app->components->refund->recalculateTotals(\CMSApplication::$VAR['refund_id']);
    
    // load details page
    $this->app->system->variables->systemMessagesWrite('success', _gettext("Refund updated successfully."));
    $this->app->system->page->forcePage('refund', 'details&refund_id='.\CMSApplication::$VAR['refund_id']); 
} else {
    
    // Check if refund can be edited
    if(!$this->app->components->refund->checkRecordAllowsEdit(\CMSApplication::$VAR['refund_id'])) {
        $this->app->system->variables->systemMessagesWrite('danger', _gettext("You cannot edit this refund because its status does not allow it."));
        $this->app->system->page->forcePage('refund', 'details&refund_id='.\CMSApplication::$VAR['refund_id']);
    }

    // Build the page
    $refund_details = $this->app->components->refund->getRecord(\CMSApplication::$VAR['refund_id']);
    $this->app->smarty->assign('refund_statuses', $this->app->components->refund->getStatuses());
    $this->app->smarty->assign('refund_types', $this->app->components->refund->getTypes());        
    $this->app->smarty->assign('refund_details', $refund_details);
    $this->app->smarty->assign('client_display_name', $this->app->components->client->getRecord($refund_details['client_id'], 'display_name'));

}
