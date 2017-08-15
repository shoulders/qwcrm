<?php

defined('_QWEXEC') or die;

// Prevent direct access to this page
if(!check_page_accessed_via_qwcrm()) {
    die(gettext("No Direct Access Allowed"));
}

/* Grab and Process Values befor sending to the log and displaying */
//$error_page         = prepare_error_data('error_page'); // only needed when using referrer
$error_page         = $VAR['error_page'];
$error_type         = $VAR['error_type'];
$error_location     = $VAR['error_location'];
$php_function       = $VAR['php_function'];
$database_error     = $VAR['database_error'];
$sql_query          = $VAR['sql_query'];
$error_msg          = $VAR['error_msg'];

// Is Logging of SQL enabled
if(QFactory::getConfig()->get('qwcrm_error_logging')) {
    
    // Prepare the SQL statement for the error log (already been prepared for output to screen)
    $sql_query_for_log = str_replace('<br>', '\r\n', $sql_query);
    
} else {    
    $sql_query_for_log = '';    
}

/* This logs errors to the error log (does not record the SQL Query */
if($qwcrm_error_log == true){    
    
    // Only log error if it exists and the error page has been loaded through the router
    if(isset($error_type) && $module === 'core' && $page_tpl === 'error'){
        write_record_to_error_log($login_username, $error_page, $error_type, $error_location, $php_function, $database_error, $sql_query_for_log, $error_msg);
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
if($login_usergroup_id <= 6){
    $BuildPage .= $smarty->fetch('core/error.tpl');
} else {
    $BuildPage .= gettext("An error has occured but you are not allowed to see it.").'<br>';
    $BuildPage .= gettext("Timestamp").': '.time().'<br>';
    $BuildPage .= gettext("Give this information to an admin and they can have a look at it for you.");
}
