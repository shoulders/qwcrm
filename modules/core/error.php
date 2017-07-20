<?php

defined('_QWEXEC') or die;

/* Grab and Process Values befor sending to the log and displaying */
//$error_page         = prepare_error_data('error_page'); // only needed when using referrer
$error_page         = $VAR['error_page'];
$error_type         = $VAR['error_type'];
$error_location     = $VAR['error_location'];
$php_function       = $VAR['php_function'];
$database_error     = $VAR['database_error'];
$sql_query          = $VAR['sql_query'];
$error_msg          = $VAR['error_msg'];

// Prepare the SQL statement for the error log
$sql_query_for_log = str_replace('<br>', '', $sql_query);


/* This logs errors to the error log (does not record the SQL Query */
if($qwcrm_error_log == true){    
    
    // Error page when logged in - these variables have just been set in the error.php controller
    if(isset($login_token) && isset($error_type) && $module === 'core' && $page_tpl === 'error'){
        write_record_to_error_log($login_username, $error_page, $error_type, $error_location, $php_function, $database_error, $error_msg);
    }
    
    // Error page when NOT logged in - find out which ones are missing and perhaps do coding on them - most of these variables are not set
    elseif(!isset($login_token) && isset($error_type) && $module === 'core' && $page_tpl === 'error') {
        write_record_to_error_log('-', $error_page, $error_type , $error_location, $php_function, $database_error, $error_msg);
    }
    
}

/* Smarty Template output */

// Assign variables to display on the error page (core:error)
$smarty->assign('error_page',       $error_page             );
$smarty->assign('error_type',       $error_type             );
$smarty->assign('error_location',   $error_location         );
$smarty->assign('php_function',     $php_function           );
$smarty->assign('database_error',   $database_error         );
$smarty->assign('sql_query',        $sql_query              );
$smarty->assign('error_msg',        $error_msg              );
    
// Prevent Customers/Guests/Public users and scapers accidentally seeing the errors
if($VAR['theme'] != 'off' && $login_usergroup_id != 8 && $login_usergroup_id != 8 && $login_usergroup_id != 9){
    $BuildPage .= $smarty->fetch('core'.SEP.'error.tpl');
} else {
    echo 'an error has occured but you are not allowed to see it if you are a Customer, Guest or Public user.';
    echo 'Time and date here - Give this to an admin and they can have a look at it for you';
}
