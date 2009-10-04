<?php
if(isset($VAR['submit'])) {

	//print_r($_POST);
	foreach($_POST as $page=>$val){

		if($page != 'submit') {
			//print $page."<br>";
				
			foreach($val as $perm=>$acl) {
				$values .=$perm."='".$acl."',";
				//print $perm." = ".$acl."<br>";
			}

			$values .="Admin='1' ";

			$q = "UPDATE ".PRFX."ACL SET ".$values."WHERE page='".$page."'";

			if(!$rs = $db->execute($q)) {
				force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
				exit;	
			}

			$values ="";

		}

	}
	force_page('control', 'acl&msg=Permisions Updated');

} else {
	$q = "SELECT * FROM ".PRFX."ACL ORDER BY page";
		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
			exit;
		}
	$arr = $rs->GetArray();
	//print_r($arr);
	$smarty->assign( 'acl', $arr );
	$smarty->display('control'.SEP.'acl.tpl');
}
?>
