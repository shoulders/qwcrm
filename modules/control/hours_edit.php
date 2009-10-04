<?php
####################################################
#  This program is distributed under the terms and #
#  conditions of the GPL        		  							#
#  office hours file          											#
#  Version 0.0.1	Sat Nov 26 20:46:40 PST 2005		#
####################################################
if(isset($VAR['submit']) ) {

	if($VAR['startHour'] >  $VAR['endHour']) {
		force_page('control', 'hours_edit&error_msg=Start Time is after End Time');
		exit;
	} else if ($VAR['startHour'] ==  $VAR['endHour']) {
		force_page('control', 'hours_edit&error_msg=Start Time is the same as End Time');
		exit;
	} else {
		$q = 'UPDATE '.PRFX.'SETUP SET
		  		 OFFICE_HOUR_START 	='. $db->qstr( $VAR['startHour']).',
		  		 OFFICE_HOUR_END		='. $db->qstr( $VAR['endHour']  );

		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
			exit;
		} else {
			force_page('control', 'hours_edit&msg=Office hours have been updated.');
			exit;	
		}
	}



} else {
	$q = 'SELECT OFFICE_HOUR_START, OFFICE_HOUR_END FROM '.PRFX.'SETUP';
		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
			exit;
		}
	$arr = $rs->GetArray();
	
	$hour = array();
	while($count != 25) {
		array_push($hour,$count);
	$count++;
	}
	
	
	$smarty->assign('hour', $hour );
	$smarty->assign('arr', $arr);
	$smarty->display('control/hours_edit.tpl');
}
?>