<?php
#####################################################
# IN                                                #
#                                                   #
#                                                   #
#  This program is distributed under the terms and  #
#  conditions of the GPL                            #
#  edit_comment                                     #
#  Version 0.0.1   Sat Nov 26 20:46:40 PST 2005     #
#                                                   #
#####################################################

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
$comment_string = $VAR['comment'];
$comment_string = stripslashes($comment_string);

	$q = "UPDATE ".PRFX."TABLE_WORK_ORDER SET
			WORK_ORDER_COMMENT	=".$db->qstr( $comment_string   ).",
			LAST_ACTIVE		=".$db->qstr( time()            )."
			WHERE  WORK_ORDER_ID    =".$db->qstr( $wo_id            );

	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	} 

	$msg = 'Comment has been Updated';
	$sql = "INSERT INTO ".PRFX."TABLE_WORK_ORDER_STATUS SET
			  WORK_ORDER_ID                 =". $db->qstr( $wo_id                   ).",
			  WORK_ORDER_STATUS_DATE        =". $db->qstr( time()                   ).",
			  WORK_ORDER_STATUS_NOTES       =". $db->qstr( $msg                     ).",
			  WORK_ORDER_STATUS_ENTER_BY    =". $db->qstr( $_SESSION['login_id']    );
		
	if(!$result = $db->Execute($sql)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}

	force_page('workorder', 'view&wo_id='.$wo_id);
	exit;
	
} else {

	$q = "SELECT WORK_ORDER_COMMENT FROM ".PRFX."TABLE_WORK_ORDER WHERE WORK_ORDER_ID=".$db->qstr( $wo_id );
		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
			exit;
		}

	$comment = $rs->fields['WORK_ORDER_COMMENT'];

	$smarty->assign('comment', $comment);
	$smarty->display('workorder'.SEP.'edit_comment.tpl');
	
}
?>