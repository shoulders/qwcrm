<?php

require_once("include.php");
//require_once("js/emp_new.js");

$VAR['page_title'] = "Add New Employee";
 
if(isset($VAR['submit'])) {
    $smarty->assign('VAR', $VAR);
    
    if (!check_employee_ex($db,$VAR)) {
            $smarty->assign('error_msg', 'The employees Display Name, '.$VAR["displayName"].',  already exists! Please use a differnt name.');
            $smarty->display('employee'.SEP.'new.tpl');
        } else {
            if (!$employee_id = insert_new_employee($db,$VAR)){
                $smarty->assign('error_msg', 'Falied to insert Employee');
            } else {
                force_page('employee', 'employee_details&employee_id='.$employee_id.'&page_title=Employees');    
            }
            
        }

} else {

    $smarty->display('employee'.SEP.'new.tpl');

}