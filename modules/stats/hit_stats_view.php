<?php
#########################################################
# 				#	
#	 				#
#  www.citecrm.com  dev@onsitecrm.com					#
#  This program is distributed under the terms and 		#
#  conditions of the GPL								#
#  stats.php											#
#  Version 0.0.1	Fri Sep 30 09:30:10 PDT 2005		#
#														#
#########################################################

$q = "SELECT * FROM ".PRFX."TRACKER WHERE ip=". $db->qstr( $VAR['ip'] ) ."ORDER BY date";
if(!$rs = $db->execute($q)) {
	force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
	exit;
}
$arr = $rs->GetArray();

$smarty->assign( 'hits',  $arr	);
$smarty->display('stats'.SEP.'hit_stats_view.tpl' );
?>