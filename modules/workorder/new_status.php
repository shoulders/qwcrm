<?php
require_once ("include.php");

if(empty($VAR['wo_id'])){
	force_page('core', 'error&error_msg=No Work Order ID');
	exit;
}
	
if(isset($VAR['submit'])){

	if (!update_status($db,$VAR)){
		force_page('core', 'error&error_msg=Falied to update work order status');
		exit;
	} else {
		force_page('workorder', 'view&wo_id='.$VAR['wo_id'].'&page_title=Work%20Order%20ID%20'.$VAR['wo_id']);
		exit;
	}

} else {
		$smarty->assign('wo_id', $VAR['wo_id']);
		$smarty->display('workorder'.SEP.'new_status.tpl');
}

?>