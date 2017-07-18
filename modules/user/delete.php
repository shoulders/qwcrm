<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/user.php');

// Check if we have an user_id
if($user_id == '') {
    force_page('user', 'search', 'warning_msg='.gettext("No User ID supplied."));
    exit;
}

// Run the delete function
if(!delete_user($db, $user_id)) {
    
    // load the user details page
    force_page('user', 'details&user_id='.$user_id);    
    exit;
    
} else {
    
    // load the user search page
    force_page('user', 'search');
    exit;
    
}