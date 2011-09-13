<?php
if(isset($VAR['upload'])&& $_FILES['userfile']['size'] > 0 ){

//check extension for csv
$fname = $_FILES['userfile']['name'];
$chk_ext = explode(".",$fname);
if(strtolower($chk_ext[1]) == "csv"){}
else{force_page('core', 'error&error_msg=Error: Only CSV files accepted');
exit;}

$filename = $_FILES['userfile']['tmp_name'];
$handle = fopen($filename, "r");

while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)
{

$query = "INSERT INTO ".PRFX."TABLE_LABOR_RATE(LABOR_RATE_NAME,LABOR_RATE_AMOUNT,LABOR_RATE_COST,LABOR_RATE_ACTIVE,LABOR_TYPE,LABOR_MANUF) VALUES ('$data[1]','$data[2]','$data[3]','$data[4]','$data[5]','$data[6]')";

if(!$rs = $db->execute($query)) {
force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
exit;}
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
                                LABOR_MANUF 	=". $db->qstr($VAR['manufacturer']) ."
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
                                LABOR_MANUF 	=". $db->qstr($VAR['manufacturer']) .",
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
	$q = "SELECT * FROM ".PRFX."TABLE_LABOR_RATE ORDER BY LABOR_RATE_ID ASC";
		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
			exit;
		}

	$arr = $rs->GetArray();
	$smarty->assign('rate', $arr);
	$smarty->display('control'.SEP.'edit_rate.tpl');
}
?>
