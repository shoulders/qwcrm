<?php
$error = $VAR['error_msg'];
$menu  = $VAR['menu'];
$type  = $VAR['type'];
if(isset($error)) {
	if($type == 'error') {
		$smarty->assign('type', 'Error:');
		$VAR['page_title'] = 'Error';
	} elseif ($type == 'info') {
		$smarty->assign('type', 'Info:');
		$VAR['page_title'] = 'Info';
	} elseif ($type == 'warning') {
		$smarty->assign('type', 'Warning:');
		$VAR['page_title'] = "Warning";
	} elseif ($type == '') {
		$smarty->assign('type', 'Error:');
		$VAR['page_title'] = "Error";
	} elseif ($type == 'database') {
		$smarty->assign('type', 'Database Error:');
		$VAR['page_title'] = "Database Error";
	} elseif ($type == 'system') {
		$smarty->assign('type', 'System Error');
		$VAR['page_title'] = "System Error";
}

$smarty->assign('error_msg', $error);

}


?>
