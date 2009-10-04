<?php
if(!xml2php("workorder")) {
	$smarty->assign('error_msg',"Error in language file");
}
require_once ("include.php");

$submit      = $VAR['submit'];
$customer_id = $VAR['customer_id'];


if (isset($VAR['submit'])) {
		if (!insert_new_workorder($db,$VAR)) {
			$smarty->display('workorder'.SEP.'new.tpl');
		} 
	
} else {	
		// Grab customers Information
		if(!isset($customer_id)){
			// redirect to customer search page
			//header ("location", "?page=customer:view");
		} else {
			$smarty->assign('customer_details', display_customer_info($db, $customer_id));
		}
		
		$smarty->display('workorder'.SEP.'new.tpl');
}
	


?>
