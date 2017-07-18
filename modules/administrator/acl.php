<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/administrator.php');

// Update the ACL permissions if submitted
if(isset($VAR['submit'])) {
    updateACL($db, $VAR);    
    $smarty->assign('information_msg', 'Permisions Updated');    
}
    
// Build the page with the permissions from the database 
$smarty->assign('acl', loadACL($db));
$BuildPage .= $smarty->fetch('administrator/acl.tpl');