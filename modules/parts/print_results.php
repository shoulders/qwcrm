<?php
####################################################
# IN 			#	
#	 				#
#  				#
#  This program is distributed under the terms and 	#
#  conditions of the GPL										#
#  Print Results												#
#  Version 0.0.1	Sat Nov 26 20:46:40 PST 2005		#
#																	#
####################################################
if(!xml2php("parts")) {
	$smarty->assign('error_msg',"Error in language file");
}
$q = "SELECT * FROM ".PRFX."ORDERS WHERE  WO_ID=".$db->qstr( $VAR['wo_id']);
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}

$order = $rs->FetchRow();

$order_id = $order['ORDER_ID'];



$q = "SELECT * FROM ".PRFX."ORDERS_DETAILS WHERE ORDER_ID=".$db->qstr( $order_id );
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}

$order_details = $rs->GetArray();

$smarty->assign( 'order', $order );
$smarty->assign( 'details', $order_details);
$smarty->display('parts'.SEP.'print_results.tpl' );
?>