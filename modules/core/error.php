<?php

//require('includes'.SEP.'modules'.SEP.'core.php');

$error_type         = $VAR['error_type'];
$error_location     = $VAR['error_location'];
$php_function       = $VAR['php_function'];
$error_msg          = $VAR['error_msg'];
$php_error_msg      = $VAR['php_error_msg'];
$database_error     = $VAR['database_error'];

// Regex the HTTP_REFERER to give the page the error occured on
preg_match('/.*\?page=(.*)&.*/', $_SERVER['HTTP_REFERER'], $page_string);
$error_page = $page_string[1];

/*
 * this is need to set the page title but doe snot work here, maybe make it a function called in index.php
 * it is not a big issue
 * split the assign and page title setup
 * this is also a list of error types
 */
if(isset($error_msg)) {
    
    if($error_type == 'error') {
        $smarty->assign('error_type', 'Error:');
        $VAR['page_title'] = 'Error';
        
    } elseif ($error_type == '') {
        $smarty->assign('error_type', 'Error:');
        $VAR['page_title'] = 'Error';             
        
    } elseif ($error_type == 'info') {
        $smarty->assign('error_type', 'Info:');
        $VAR['page_title'] = 'Info';
        
    } elseif ($error_type == 'warning') {
        $smarty->assign('error_type', 'Warning:');
        $VAR['page_title'] = "Warning";        
   
    } elseif ($error_type == 'database') {
        $smarty->assign('error_type', 'Database Error:');
        $VAR['page_title'] = "Database Error";
        
    } elseif ($error_type == 'system') {
        $smarty->assign('error_type', 'System Error');
        $VAR['page_title'] = "System Error";
    }
}






//$smarty->assign('page_title', $VAR['page_title']);

$smarty->assign('error_type',       $error_type             );
$smarty->assign('error_location',   $error_location         );
$smarty->assign('error_page',       $error_page             );
$smarty->assign('php_function',     $php_function           );
$smarty->assign('error_msg',        $error_msg              );
$smarty->assign('php_error_msg',    $php_error_msg          );
$smarty->assign('database_error',   $database_error         );


// I need to write this to an error log ? - add here

$smarty->display('core'.SEP.'error.tpl');