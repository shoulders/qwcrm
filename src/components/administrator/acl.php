<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Update the ACL permissions if submitted
if(isset(\CMSApplication::$VAR['submit']) && \CMSApplication::$VAR['submit'] == 'reset_default') {
    reset_acl_permissions();    
    systemMessagesWrite('success', _gettext("Permissions reset to default."));    
}

// Update the ACL permissions if submitted
if(isset(\CMSApplication::$VAR['submit']) && \CMSApplication::$VAR['submit'] == 'update') {
    update_acl(\CMSApplication::$VAR['qform']['permissions']);    
    systemMessagesWrite('success', _gettext("Permissions Updated."));    
}
    
// Build the page with the permissions from the database 
$smarty->assign('acl', get_acl_permissions());