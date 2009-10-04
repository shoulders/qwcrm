<?php
require_once ("include.php");
if(!xml2php("customer")) {
	$smarty->assign('error_msg',"Error in language file");
}
if(isset($VAR['submit'])) {

	if (!check_customer_ex($db, $VAR['displayName'])){
			$smarty->assign('VAR', $VAR);
			$smarty->assign('error_msg', 'The customer Display Name, '.$VAR["displayName"].',  already exists! Please use a differnt name.');
			$smarty->display('customer'.SEP.'new.tpl');
		} else {
			if (!$customer_id = insert_new_customer($db,$VAR)){
				$smarty->assign('error_msg', 'Falied to insert customer');
				$smarty->display('core'.SEP.'error.tpl');
			} else {
				force_page('customer', 'customer_details&customer_id='.$customer_id.'&msg=Added New Customer '.$VAR["displayName"].' &page_title='.$VAR["displayName"]);
				exit;	
			}
			
		}
	
} else {
	
	$smarty->display('customer'.SEP.'new.tpl');

}


	


?>