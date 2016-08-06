<?php

// do I need this here
if(!xml2php('core')) {
    $smarty->assign('error_msg','Error in language file');
}

$error_type         = $VAR['error_type'];
$error_msg          = $VAR['error_msg'];
$error_location     = $VAR['error_location'];
$database_error     = $VAR['database_error']; 
$php_function       = $VAR['php_function'];

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

$smarty->assign('error_type',       $error_type         );
$smarty->assign('error_location',   $error_location     );
$smarty->assign('error_msg',        $error_msg          );
$smarty->assign('php_function',     $php_function       );
$smarty->assign('database_error',   $database_error     );

// I need to write this to an error log ? - add here
// when writing to the log i can use apache variable URL_REFER to get the page where the error occured and use a bit of regex on it to write it to the error log

$smarty->display('core'.SEP.'error.tpl');