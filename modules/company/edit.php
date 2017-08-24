<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/company.php');

// Update Company details
if(isset($VAR['submit'])) {

    // Submit data to the database
    update_company_details($db, $VAR);    

}
     
// Fetch page
$smarty->assign('company_details', get_company_details($db) );
$BuildPage .= $smarty->fetch('company/edit.tpl');
