<?php
require_once("include.php");

if(!xml2php("workorder")) {
	$smarty->assign('error_msg',"Error in language file");
}
$submit = $VAR['submit'];
$wo_id = $VAR['wo_id'];

/* check for open part Orders */
$q = "SELECT count(*) as count  FROM ".PRFX."ORDERS WHERE WO_ID=".$db->qstr($wo_id);
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}
$smarty->assign('part', $rs->fields['count']);

if(!$single_work_order = display_single_open_workorder($db, $VAR['wo_id'])){
	force_page('core', "error&menu=1&error_msg=The Work Order you requested was not found&type=error");
	exit;
}
	$smarty->assign('single_workorder_array', 	$single_work_order);
	$smarty->assign('work_order_notes', 			display_workorder_notes($db, $VAR['wo_id']));
	$smarty->assign('order',							display_parts($db,$VAR['wo_id'])				);;				
	$smarty->assign('work_order_status', 			display_workorder_status($db, $VAR['wo_id']));
	$smarty->assign('work_order_sched', 			get_work_order_schedule ($db,$VAR['wo_id']));	
	$smarty->assign('resolution', 					display_resolution($db,$VAR['wo_id']));


if (isset($VAR['submit'])) {
		$sql = "DELETE FROM ".PRFX."TABLE_WORK_ORDER WHERE WORK_ORDER_ID=".$db->qstr($wo_id) ;
	if(!$result = $db->Execute($sql)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}

    //Write comment to log
    $month = date("M");
    $day = date("d");
    $year = date("Y");
    $time =  date("H").":".date("i").":".date("s");
    //get environment variables
    $hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);

    // Create entry
    $data = "Workorder ".$wo_id." has been deleted ,".$login.",".$hostname.",".$month."-".$day."-".$year.",".$time.",\n";
    // Write File
    $fp = fopen(ACCESS_LOG,'a') or die("can't open access.log: $php_errormsg");
    fwrite($fp, $data);
    fclose($fp);

	echo("
		<script type=\"text/javascript\">
			<!--
			window.location = \"index.php\"
			//-->
		</script>");
  } ELSE {
$smarty->display('workorder'.SEP.'view.tpl');
  }


$smarty->assign('submit', $submit);


?>