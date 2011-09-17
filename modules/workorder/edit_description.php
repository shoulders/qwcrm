<?php
if(!xml2php("workorder")) {
	$smarty->assign('error_msg',"Error in language file");
}
$wo_id = $VAR['wo_id'];
$smarty->assign('wo_id', $wo_id);

if($wo_id == '') {
force_page('core', 'error&error_msg=No Work Order ID');
		exit;
}

if(isset($VAR['submit'])) {

//Remove Extra Slashes caused by Magic Quotes
$description_string = $VAR['description'];
$description_string = stripslashes($description_string);

	$q = "UPDATE ".PRFX."TABLE_WORK_ORDER SET
                        WORK_ORDER_SCOPE		=".$db->qstr( $VAR['scope']         ).",
			WORK_ORDER_DESCRIPTION		=".$db->qstr( $description_string   ).",
			LAST_ACTIVE			=".$db->qstr( time()                )."
			WHERE  WORK_ORDER_ID=".$db->qstr( $wo_id                            );

	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	} 

	/* add note */
	$msg = 'Description has been Updated';
	$sql = "INSERT INTO ".PRFX."TABLE_WORK_ORDER_STATUS SET
			  WORK_ORDER_ID				=". $db->qstr( $wo_id                  ).",
			  WORK_ORDER_STATUS_DATE		=". $db->qstr( time()                  ).",
			  WORK_ORDER_STATUS_NOTES		=". $db->qstr( $msg                    ).",
			  WORK_ORDER_STATUS_ENTER_BY            =". $db->qstr( $_SESSION['login_id']   );
		
	if(!$result = $db->Execute($sql)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}

	force_page('workorder', 'view&wo_id='.$wo_id);
	exit;
	
} else {

	$q = "SELECT WORK_ORDER_DESCRIPTION, WORK_ORDER_SCOPE FROM ".PRFX."TABLE_WORK_ORDER WHERE WORK_ORDER_ID=".$db->qstr( $wo_id );
		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
			exit;
		}

	$description = $rs->fields['WORK_ORDER_DESCRIPTION'];
        $scope = $rs->fields['WORK_ORDER_SCOPE'];

	$smarty->assign('description', $description);
        $smarty->assign('scope', $scope);
	$smarty->display('workorder'.SEP.'edit_description.tpl');
	
}
?>