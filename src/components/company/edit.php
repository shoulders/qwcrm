<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Prevent undefined variable errors
\CMSApplication::$VAR['qform']['delete_logo'] = \CMSApplication::$VAR['qform']['delete_logo'] ?? null;

// Update Company details
if(isset(\CMSApplication::$VAR['submit'])) {

    // Submit data to the database
    $this->app->components->company->updateRecord(\CMSApplication::$VAR['qform']);    
    
    // Reload Company options
    $this->app->system->page->forcePage('company', 'edit');
    
}

// Build the page
$this->app->smarty->assign('date_formats', $this->app->system->general->getDateFormats());
$this->app->smarty->assign('tax_systems', $this->app->components->company->getTaxSystems() );
$this->app->smarty->assign('vat_tax_codes', $this->app->components->company->getVatTaxCodes(null, true) );
$this->app->smarty->assign('company_details', $this->app->components->company->getRecord() );
