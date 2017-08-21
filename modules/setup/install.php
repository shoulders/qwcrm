<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/setup.php'); // || QWCRM_SETUP != 'install'

//echo check_page_accessed_via_qwcrm('setup:install');
echo getenv('HTTP_REFERER');

// Prevent direct access to this page
if(!check_page_accessed_via_qwcrm('setup:install', 'setup') && QWCRM_SETUP == 'install') {
    die(gettext("No Direct Access Allowed"));
}

// Close/remove any $db connections
unset($db);

// Stage 1 - Database connection and test -->
if($VAR['stage'] == '1' || !isset($VAR['stage'])) {
    
    if($VAR['submit'] == 'stage1') {
        
        if(check_database_connection($VAR['db_host'], $VAR['db_user'], $VAR['db_pass'], $VAR['db_name'])) {
            
            // Record details into the config file (or temp-config-file)
            
        } else {
            // reload the page with the details and error messgae
        }
    }
}

// Stage 2
if($VAR['stage'] == '2') {
}

// Stage 3
if($VAR['stage'] == '3') {
}

// Stage 4
if($VAR['stage'] == '4') {
}

// Build the page
$smarty->assign('stage', $stage);
$smarty->assign('setup_details', $VAR);
$BuildPage .= $smarty->fetch('setup/install.tpl');