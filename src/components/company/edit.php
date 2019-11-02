<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Prevent undefined variable errors
\CMSApplication::$VAR['qform']['delete_logo'] = isset(\CMSApplication::$VAR['qform']['delete_logo']) ? \CMSApplication::$VAR['qform']['delete_logo'] : null;

// Update Company details
if(isset(\CMSApplication::$VAR['submit'])) {

    // Submit data to the database
    $this->app->components->company->update_company_details(\CMSApplication::$VAR['qform']);    
    
    // Reload Company options and display a success message
    $this->app->system->variables->systemMessagesWrite('success', _gettext("Company details updated."));
    $this->app->system->page->force_page('company', 'edit');
    
}

// Build the page
$this->app->smarty->assign('date_formats', $this->app->system->general->get_date_formats());
$this->app->smarty->assign('tax_systems', $this->app->components->company->get_tax_systems() );
$this->app->smarty->assign('vat_tax_codes', $this->app->components->company->get_vat_tax_codes(null, true) );
$this->app->smarty->assign('company_details', $this->app->components->company->get_company_details() );
