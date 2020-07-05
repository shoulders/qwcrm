<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Check if we have a otherincome_id
if(!isset(\CMSApplication::$VAR['otherincome_id']) || !\CMSApplication::$VAR['otherincome_id']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Refund ID supplied."));
    $this->app->system->page->force_page('otherincome', 'search');
} 

// If details submitted run update values, if not set load edit.tpl and populate values
if(isset(\CMSApplication::$VAR['submit'])) {    
        
    // Update the otherincome in the database
    $this->app->components->otherincome->updateRecord(\CMSApplication::$VAR['qform']);
    $this->app->components->otherincome->recalculateTotals(\CMSApplication::$VAR['qform']['otherincome_id']);
    
    // load details page
    $this->app->system->page->force_page('otherincome', 'details&otherincome_id='.\CMSApplication::$VAR['qform']['otherincome_id'], 'msg_success='._gettext("Otherincome updated successfully.")); 
} else {  

    // Check if payment can be edited
    if(!$this->app->components->otherincome->checkStatusAllowsEdit(\CMSApplication::$VAR['otherincome_id'])) {
        $this->app->system->variables->systemMessagesWrite('danger', _gettext("You cannot edit this otherincome because its status does not allow it."));
        $this->app->system->page->force_page('otherincome', 'details&otherincome_id='.\CMSApplication::$VAR['otherincome_id']);
    }
    
    // Build the page
    $this->app->smarty->assign('otherincome_statuses', $this->app->components->otherincome->getStatuses());
    $this->app->smarty->assign('otherincome_types', $this->app->components->otherincome->getTypes());
    $this->app->smarty->assign('vat_tax_codes', $this->app->components->company->getVatTaxCodes(false) );    
    $this->app->smarty->assign('otherincome_details', $this->app->components->otherincome->getRecord(\CMSApplication::$VAR['otherincome_id']));

}
