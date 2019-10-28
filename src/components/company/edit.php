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
if(isset(\CMSApplication::$VAR['qform']['submit'])) {

    // Submit data to the database
    update_company_details(\CMSApplication::$VAR['qform']);    
    
    // Reload Company options and display a success message
    systemMessagesWrite('success', _gettext("Company details updated."));
    force_page('company', 'edit');
    
}

// Build the page
$smarty->assign('date_formats', get_date_formats());
$smarty->assign('tax_systems', get_tax_systems() );
$smarty->assign('vat_tax_codes', get_vat_tax_codes(null, true) );
$smarty->assign('company_details', get_company_details() );
