<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/user.php');

// check if we have an user_id
if($user_id == ''){
    force_page('core', 'error', 'error_msg=No Customer ID supplied.');
    exit;
}

// run the delete function and return the results
if(!delete_user($db, $user_id)) {    
    force_page('user', 'details&user_id='.$user_id);
    exit;    
} else {
    force_page('user', 'search');
    exit;
}