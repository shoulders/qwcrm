<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/administrator.php');

// Update the ACL permissions if submitted
if($VAR['submit'] == 'reset_default') {
    reset_acl_permissions($db);    
    $smarty->assign('information_msg', gettext("Permissions reset to default."));    
}

// Update the ACL permissions if submitted
if($VAR['submit'] == 'update') {
    update_acl($db, $VAR);    
    $smarty->assign('information_msg', gettext("Permissions Updated."));    
}
    
// Build the page with the permissions from the database 
$smarty->assign('acl', load_acl($db));
$BuildPage .= $smarty->fetch('administrator/acl.tpl');