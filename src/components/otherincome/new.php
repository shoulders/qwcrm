<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// If details submitted insert record, if non submitted load new.tpl and populate values
if(isset(\CMSApplication::$VAR['submit'])) {

    // insert the otherincome and get the otherincome_id
    $otherincome_id = $this->app->components->otherincome->insertRecord(\CMSApplication::$VAR['qform']);
    $this->app->components->otherincome->recalculateTotals($otherincome_id);
        
    if (\CMSApplication::$VAR['submit'] == 'submitandnew') {

        // Load New Refund page
        $this->app->system->page->forcePage('otherincome', 'new', 'msg_success='._gettext("Other Income added successfully.").' '._gettext("ID").': '.$otherincome_id); 

    } elseif (\CMSApplication::$VAR['submit'] == 'submitandpayment') {
         
        // Load the new payment page for otherincome
         $this->app->system->page->forcePage('payment', 'new&type=otherincome&otherincome_id='.$otherincome_id, 'msg_success='._gettext("Other Income added successfully.").' '._gettext("ID").': '.$otherincome_id);      
         
    } else {

        // Load Refund Details page
        $this->app->system->page->forcePage('otherincome', 'details&otherincome_id='.$otherincome_id, 'msg_success='._gettext("Other Income added successfully.").' '._gettext("ID").': '.$otherincome_id);      

    }
         
}

// Build the page
$this->app->smarty->assign('otherincome_types', $this->app->components->otherincome->getTypes());
$this->app->smarty->assign('vat_tax_codes', $this->app->components->company->getVatTaxCodes(false));
$this->app->smarty->assign('default_vat_tax_code', $this->app->components->company->getDefaultVatTaxCode());