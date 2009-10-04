<?php

#############################
# Display Work Order Status	#
#############################
function display_workorder_status2($db, $wo_id){
	$sql = "SELECT ".PRFX."TABLE_WORK_ORDER_STATUS.*, ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_DISPLAY_NAME 
				FROM ".PRFX."TABLE_WORK_ORDER_STATUS, ".PRFX."TABLE_EMPLOYEE 
				WHERE  ".PRFX."TABLE_WORK_ORDER_STATUS.WORK_ORDER_ID=".$db->qstr($wo_id)." 
				AND ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_ID = ".PRFX."TABLE_WORK_ORDER_STATUS.WORK_ORDER_STATUS_ENTER_BY ORDER BY ".PRFX."TABLE_WORK_ORDER_STATUS.WORK_ORDER_STATUS_ID";
	
	if(!$result = $db->Execute($sql)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}
	
	$work_order_status2 = $result->GetArray();
	return $work_order_status2;
	
}

?>
