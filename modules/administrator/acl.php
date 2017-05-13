<?php

require(INCLUDES_DIR.'modules/administrator.php');

// Update the ACL permissions if submitted
if(isset($VAR['submit'])) {
    updateACL($db, $VAR);    
    $smarty->assign('information_msg', 'Permisions Updated');
    //print_r($_POST);echo'<br>';print_r($VAR);
}
    
// Fetch the page with the permissions from the database 
$smarty->assign('acl', loadACL($db));
$BuildPage .= $smarty->fetch('administrator/acl.tpl');