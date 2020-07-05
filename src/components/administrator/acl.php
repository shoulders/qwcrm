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
    $this->app->components->administrator->resetAclPermissions();    
    $this->app->system->variables->systemMessagesWrite('success', _gettext("Permissions reset to default."));    
}

// Update the ACL permissions if submitted
if(isset(\CMSApplication::$VAR['submit']) && \CMSApplication::$VAR['submit'] == 'update') {
    $this->app->components->administrator->updateAcl(\CMSApplication::$VAR['qform']['permissions']);    
    $this->app->system->variables->systemMessagesWrite('success', _gettext("Permissions Updated."));    
}
    
// Build the page with the permissions from the database 
$this->app->smarty->assign('acl', $this->app->components->administrator->getAclPermissions());