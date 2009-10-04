<?php
#########################################################
#  This program is distributed under the terms and 		#
#  conditions of the GPL								#
#  new.php												#
#  Version 0.0.1	Fri Sep 30 09:30:10 PDT 2005		#
#														#
#########################################################
require_once("include.php");
//require_once("js/emp_new.js");
if(!xml2php("employees")) {
	$smarty->assign('error_msg',"Error in language file");
}
$VAR['page_title'] = "Add New Employee";
 
if(isset($VAR['submit'])) {
	$smarty->assign('VAR', $VAR);
	
	if (!check_employee_ex($db,$VAR)) {
			$smarty->assign('error_msg', 'The employees Display Name, '.$VAR["displayName"].',  already exists! Please use a differnt name.');
			$smarty->display('employees'.SEP.'new.tpl');
		} else {
			if (!$employee_id = insert_new_employee($db,$VAR)){
				$smarty->assign('error_msg', 'Falied to insert Employee');
			} else {
				force_page('employees', 'employee_details&employee_id='.$employee_id.'&page_title=Employees');	
			}
			
		}

} else {

	$smarty->display('employees'.SEP.'new.tpl');

}


?>
