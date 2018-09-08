<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'company.php');

// Prevent undefined variable errors
$VAR['delete_logo'] = isset($VAR['delete_logo']) ? $VAR['delete_logo'] : null;

// Update Company details
if(isset($VAR['submit'])) {

    // Submit data to the database
    update_company_details($VAR);
    
    // Reload Company options and display a success message
    force_page('company', 'options', 'information_msg='._gettext("Company details updated."));
    
}

// Build the page
$smarty->assign('date_formats', get_date_formats());
$smarty->assign('company_details', get_company_details() );
$BuildPage .= $smarty->fetch('company/edit.tpl');
