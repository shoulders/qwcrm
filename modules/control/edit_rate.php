<?php
if(isset($VAR['upload'])&& $_FILES['userfile']['size'] >  0 ){
$target_path = "upload/";

$target_path = $target_path . basename( $_FILES['userfile']['name']);

if(move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path))
$fname = WWW_ROOT.SEP.$target_path ;
$handle = fopen ($fname , 'r');
//TODO check file size before uploading to avoid errors on no file loaded
		while (($data = fgetcsv($handle, 1000, ',', '"')) !== FALSE)
		{
			$query = "REPLACE INTO ".PRFX."TABLE_LABOR_RATE VALUES ('". implode("','", $data)
."')";
 			$query = @mysql_query($query);
		}
fclose($handle);
}
//Now if we edit/add a new item
if(isset($VAR['submit'])) {
	/* edit */
	if($VAR['submit'] == 'Edit') {
		$q = "UPDATE ".PRFX."TABLE_LABOR_RATE SET
				LABOR_RATE_NAME	=". $db->qstr($VAR['display']) .",
				LABOR_RATE_AMOUNT	=". $db->qstr($VAR['amount']) .",
				LABOR_RATE_COST	=". $db->qstr($VAR['cost']) .",
				LABOR_RATE_ACTIVE 	=". $db->qstr($VAR['active']) .",
                                LABOR_TYPE 	=". $db->qstr($VAR['type']) .",
                                LABOR_MANUF 	=". $db->qstr($VAR['manufacture']) ."
				WHERE LABOR_RATE_ID =".$db->qstr($VAR['id']);
		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
			exit;
		}
	}

	/* delete */
	if($VAR['submit'] == 'Delete') {
		$q="DELETE FROM ".PRFX."TABLE_LABOR_RATE WHERE LABOR_RATE_ID =".$db->qstr($VAR['id']);
		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
			exit;
		}

	}

	/* New */
	if($VAR['submit'] == 'New') {
		$q = "INSERT INTO ".PRFX."TABLE_LABOR_RATE SET
			 	LABOR_RATE_NAME	=". $db->qstr($VAR['display']) .",
				LABOR_RATE_AMOUNT	=". $db->qstr($VAR['amount']) .",
				LABOR_RATE_COST	=". $db->qstr($VAR['cost']) .",
                                LABOR_TYPE	=". $db->qstr($VAR['type']) .",
                                LABOR_MANUF 	=". $db->qstr($VAR['manufacture']) .",
				LABOR_RATE_ACTIVE 	=". $db->qstr(1);
		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
			exit;
		}
	}

	/* back to labor edit */
	force_page('control', 'edit_rate&page_title=Edit Billing Rates');
	exit;
} else {
	$q = "SELECT * FROM ".PRFX."TABLE_LABOR_RATE";
		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
			exit;
		}

	$arr = $rs->GetArray();
	$smarty->assign('rate', $arr);
	$smarty->display('control'.SEP.'edit_rate.tpl');
}
?>
