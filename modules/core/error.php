<?php

// this code is messy and should be moved into a function out of the way

require('includes'.SEP.'modules'.SEP.'core.php');

$error_type         = $VAR['error_type'];
$error_location     = $VAR['error_location'];
$php_function       = $VAR['php_function'];
$database_error     = $VAR['database_error'];
$error_msg          = $VAR['error_msg'];

/* Process Values for sending to the log and displaying */

// add () to the end of the php function name
if(isset($php_function)){$php_function.= '()';}

// modify the location so it is displayed correctly
$error_location = str_replace(':','/',$error_location).'.php';

// Set the page the error occured on (not module?)  
preg_match('/.*\?page=(.*)&.*/', getenv('HTTP_REFERER'), $page_string);
$error_page = $page_string[1];    
if($error_page == ''){     
    // Must be Login or Home
    if(isset($_SESSION['login_hash'])){
        $error_page = 'home';
    } else {
        $error_page = 'login';
    }    
}
    
/* This logs errors to the error log */
if($qwcrm_error_log === 'on'){    
    
    // Error page when logged in - these variables have just been set in the error.php controller
    if(isset($_SESSION['login_hash']) && isset($error_type) && $module === 'core' && $page === 'error'){
        write_record_to_error_log($login_usr, $error_type, $error_location, $error_page, $php_function, $database_error, $error_msg);
    }
    
    // Error page when NOT logged in - find out which ones are missing and perhaps do coding on them - most of these variables are not set
    elseif(!isset($_SESSION['login_hash']) && isset($error_type) && $module === 'core' && $page === 'error') {
        write_record_to_error_log('-', $error_type , $error_location, $error_page, $php_function, $database_error, $error_msg);
    }
    
}

/* Smarty Template output */

// Assign variables to display on the  error page (core:error)
$smarty->assign('error_type',       $error_type             );
$smarty->assign('error_location',   $error_location         );
$smarty->assign('error_page',       $error_page             );
$smarty->assign('php_function',     $php_function           );
$smarty->assign('database_error',   $database_error         );
$smarty->assign('error_msg',        $error_msg              );
    
// Prevent guests/scapers accidentally seeing the errors
if($VAR['theme'] != 'off' && $login_account_type_id != 6){
    $smarty->display('core'.SEP.'error.tpl');
} else {
    echo 'an error has occured but you are not allowed to see it if you are a guest.';
    echo 'Time and date here - Give this to an admin and they can have a look at it for you';
}
