<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'administrator.php');

// Update the ACL permissions if submitted
if(isset($VAR['submit']) && $VAR['submit'] == 'reset_default') {
    reset_acl_permissions();    
    $smarty->assign('information_msg', _gettext("Permissions reset to default."));    
}

// Update the ACL permissions if submitted
if(isset($VAR['submit']) && $VAR['submit'] == 'update') {
    update_acl($VAR['qwpermissions']);    
    $smarty->assign('information_msg', _gettext("Permissions Updated."));    
}
    
// Build the page with the permissions from the database 
$smarty->assign('acl', get_acl_permissions());
$BuildPage .= $smarty->fetch('administrator/acl.tpl');