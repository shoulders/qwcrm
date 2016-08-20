<?php

require('includes'.SEP.'modules'.SEP.'core.php');

$error_type         = $VAR['error_type'];
$error_location     = $VAR['error_location'];
$php_function       = $VAR['php_function'];
$error_msg          = $VAR['error_msg'];
$php_error_msg      = $VAR['php_error_msg'];
$database_error     = $VAR['database_error'];

// Regex the HTTP_REFERER to give the page the error occured on
preg_match('/.*\?page=(.*)&.*/', getenv('HTTP_REFERER'), $page_string);
$error_page = $page_string[1];

// This logs errors to the error log
if($qwcrm_error_log === 'on'){
    
    // can i use $VAR['error_msg'] as detection instead?
    
    // Error page when logged in - these variables have just been set in the error.php controller
    if(isset($_SESSION['login_hash']) && isset($_GET['error_msg']) && $module === 'core' && $page === 'error'){
        write_record_to_error_log($login_usr, $error_type, $error_location, $php_function, $error_msg, $php_error_msg, $database_error);
    }
    
    // Error page when NOT logged in - find out which ones are missing and perhaps do coding on them - most of these variables are not set
    elseif(!isset($_SESSION['login_hash']) && isset($_GET['error_msg']) && $module === 'core' && $page === 'error') {
        write_record_to_error_log('-', $_GET['error_type'], $error_location, $php_function, $error_msg, $php_error_msg, $database_error);
    }
    
}

$smarty->assign('error_type',       $error_type             );
$smarty->assign('error_location',   $error_location         );
$smarty->assign('error_page',       $error_page             );
$smarty->assign('php_function',     $php_function           );
$smarty->assign('error_msg',        $error_msg              );
$smarty->assign('php_error_msg',    $php_error_msg          );
$smarty->assign('database_error',   $database_error         );

// examine how i want this part to work and if i want to include the above block in the same place
// maybe on if advanced debug is turned on?
if($VAR['theme'] != 'off' && $login_account_type_id != 6){
    $smarty->display('core'.SEP.'error.tpl');
} else {
    echo 'an error has occured but you are not allowed to see it if you are a guest';
}
