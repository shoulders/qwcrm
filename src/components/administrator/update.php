<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'components/administrator.php');

if(isset($VAR['submit'])) {
    
    // Check for updates
    check_for_qwcrm_update();

}

// Build the page
$smarty->assign('current_version', QWCRM_VERSION); 
$BuildPage .= $smarty->fetch('administrator/update.tpl');

