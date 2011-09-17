<?php
/* load translation for this module */
if(!xml2php("employees")) {
	$smarty->assign('error_msg',"Error in language file");
}

$smarty->assign('id', $id);
$smarty->assign('employee_details', $employee_details);

#####################################
#	Display                     #
#####################################

function display_customer_info($db, $customer_id){

	$sql = "SELECT * FROM ".PRFX."TABLE_CUSTOMER WHERE CUSTOMER_ID=".$db->qstr($customer_id);
	
	if(!$result = $db->Execute($sql)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	} else {
		$customer_array = array();
	}
	
	while($row = $result->FetchRow()){
		 array_push($customer_array, $row);
	}
	
	return $customer_array;
}

#####################################
#	Display	Company Info        #
#####################################

function display_company_info($db, $customer_id){

	$sql = "SELECT * FROM ".PRFX."TABLE_COMPANY";
	
	if(!$result = $db->Execute($sql)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	} else {
		$company_array = array();
	}
	
	while($row = $result->FetchRow(1)){
		 array_push($company_array, $row);
	}
	
	return $company_array;
}


#####################################
#	Search                      #
#####################################

function display_customer_search($db, $name, $page_no, $smarty) {
	global $smarty;
        $safe_name = strip_tags($name);
	
	// Define the number of results per page
	$max_results = 25;
	
	// Figure out the limit for the Execute based
	// on the current page number.
	$from = (($page_no * $max_results) - $max_results);
	
	$sql = "SELECT * FROM ".PRFX."TABLE_CUSTOMER WHERE CUSTOMER_DISPLAY_NAME LIKE '%$safe_name%' ORDER BY CUSTOMER_DISPLAY_NAME LIMIT $from, $max_results";
	
	//print $sql;
	
	if(!$result = $db->Execute($sql)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	} else {
		$customer_search_result = array();
	}
	
	while($row = $result->FetchRow()){
		 array_push($customer_search_result, $row);
	}
	
	// Figure out the total number of results in DB: 
	$results = $db->Execute("SELECT COUNT(*) as Num FROM ".PRFX."TABLE_CUSTOMER WHERE CUSTOMER_DISPLAY_NAME LIKE ".$db->qstr("%$safe_name%") );
	
	if(!$total_results = $results->FetchRow()) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	} else {
		$smarty->assign('total_results', strip_tags($total_results['Num']));
	}
		
	// Figure out the total number of pages. Always round up using ceil()
	$total_pages = ceil($total_results["Num"] / $max_results); 
	$smarty->assign('total_pages', strip_tags($total_pages));
	
	// Assign the first page
	if($page_no > 1) {
    	$prev = ($page_no - 1);	 
	} 	

	// Build Next Link
	if($page_no < $total_pages){
    	$next = ($page_no + 1); 
	}
	
	$smarty->assign('name', strip_tags($name));
	$smarty->assign('page_no', strip_tags($page_no));
	$smarty->assign("previous", strip_tags($prev));
	$smarty->assign("next", strip_tags($next));
	
	return $customer_search_result;
}

#################################
#	Open Work Orders        #
#################################

function display_open_workorders($db, $customer_id){

$sql = "SELECT ".PRFX."TABLE_WORK_ORDER.*,
			 ".PRFX."TABLE_CUSTOMER.*,
			 ".PRFX."TABLE_EMPLOYEE.*,
			 ".PRFX."TABLE_SCHEDULE.SCHEDULE_START,
			 ".PRFX."TABLE_SCHEDULE.SCHEDULE_END, 
			 ".PRFX."TABLE_SCHEDULE.SCHEDULE_NOTES,
			 ".PRFX."CONFIG_WORK_ORDER_STATUS.CONFIG_WORK_ORDER_STATUS
			 FROM ".PRFX."TABLE_WORK_ORDER
			 LEFT JOIN ".PRFX."TABLE_CUSTOMER ON ".PRFX."TABLE_WORK_ORDER.CUSTOMER_ID 				= ".PRFX."TABLE_CUSTOMER.CUSTOMER_ID
			 LEFT JOIN ".PRFX."TABLE_EMPLOYEE ON ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_ASSIGN_TO 	= ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_ID
			 LEFT JOIN ".PRFX."TABLE_SCHEDULE ON ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_ID 				= ".PRFX."TABLE_SCHEDULE.WORK_ORDER_ID
			 LEFT JOIN ".PRFX."CONFIG_WORK_ORDER_STATUS ON ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_CURRENT_STATUS = ".PRFX."CONFIG_WORK_ORDER_STATUS.CONFIG_WORK_ORDER_STATUS_ID 
			 WHERE ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_STATUS ='10' AND ".PRFX."TABLE_WORK_ORDER.CUSTOMER_ID=".$db->qstr($customer_id)." ORDER BY ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_ID DESC";

	if(!$result = $db->Execute($sql)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	} else {
		$open_work_orders_array = $result->GetArray();
	}
	
	return $open_work_orders_array;
	
}
############################################
# Display closed works orders for customer #
############################################

function display_closed_workorders($db, $customer_id){

$sql = "SELECT ".PRFX."TABLE_WORK_ORDER.*,
			 ".PRFX."TABLE_CUSTOMER.*,
			 ".PRFX."TABLE_EMPLOYEE.*,
			 ".PRFX."TABLE_SCHEDULE.SCHEDULE_START,
			 ".PRFX."TABLE_SCHEDULE.SCHEDULE_END,
			 ".PRFX."TABLE_SCHEDULE.SCHEDULE_NOTES,
			 ".PRFX."CONFIG_WORK_ORDER_STATUS.CONFIG_WORK_ORDER_STATUS
			 FROM ".PRFX."TABLE_WORK_ORDER
			 LEFT JOIN ".PRFX."TABLE_CUSTOMER ON ".PRFX."TABLE_WORK_ORDER.CUSTOMER_ID 				= ".PRFX."TABLE_CUSTOMER.CUSTOMER_ID
			 LEFT JOIN ".PRFX."TABLE_EMPLOYEE ON ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_ASSIGN_TO 	= ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_ID
			 LEFT JOIN ".PRFX."TABLE_SCHEDULE ON ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_ID 				= ".PRFX."TABLE_SCHEDULE.WORK_ORDER_ID
			 LEFT JOIN ".PRFX."CONFIG_WORK_ORDER_STATUS ON ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_CURRENT_STATUS = ".PRFX."CONFIG_WORK_ORDER_STATUS.CONFIG_WORK_ORDER_STATUS_ID
			 WHERE ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_STATUS <>'10' AND ".PRFX."TABLE_WORK_ORDER.CUSTOMER_ID=".$db->qstr($customer_id)." ORDER BY ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_ID DESC";

	if(!$result = $db->Execute($sql)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	} else {
		$closed_work_orders_array = $result->GetArray();
	}

	return $closed_work_orders_array;
	
}

#####################################
#   Unpaid Invoices                 #
#####################################

function display_unpaid_invoices($db,$customer_id){
	$q = "SELECT ".PRFX."TABLE_INVOICE.*, ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_DISPLAY_NAME 
			FROM ".PRFX."TABLE_INVOICE
			LEFT JOIN ".PRFX."TABLE_EMPLOYEE ON (".PRFX."TABLE_INVOICE.EMPLOYEE_ID = ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_ID) WHERE CUSTOMER_ID=".$db->qstr($customer_id)." AND INVOICE_PAID='0' ORDER BY ".PRFX."TABLE_INVOICE.INVOICE_ID DESC";
	
	if(!$rs = $db->execute($q)){
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	} else {
		$unpaid_invoices = $rs->GetArray();
	}
	return $unpaid_invoices;
}

###################################
#   Paid Invoices	          #
###################################

function display_paid_invoices($db,$customer_id){

	$q = "SELECT ".PRFX."TABLE_INVOICE.*, ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_DISPLAY_NAME 
			FROM ".PRFX."TABLE_INVOICE
			LEFT JOIN ".PRFX."TABLE_EMPLOYEE ON (".PRFX."TABLE_INVOICE.EMPLOYEE_ID = ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_ID)
			WHERE CUSTOMER_ID=".$db->qstr($customer_id)." AND INVOICE_PAID='1' ORDER BY ".PRFX."TABLE_INVOICE.INVOICE_ID DESC";
	
	if(!$rs = $db->execute($q)){
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;	
	} else {
		$paid_invoices = $rs->GetArray();
	}
	
	return $paid_invoices;

}

#####################################
#	Validation		    #
#####################################

function checkPhone($phone){

	$match =  "/^(\d{3}\-\d{3}\-\d{4})$/";
	
	if(preg_match($match, $phone)) {
		return true;
	} else {
		return false;
	}
      
}
    
function checkZip($zip){

	$match = "/[^0-9]+$/ ";
	
	if(preg_match($match, $zip)) {
		return true;
	} else {
		return false;
	}
}

#####################################
#	Duplicate		    #
#####################################
	
function check_customer_ex($db, $displayName) {
	$sql = "SELECT COUNT(*) AS num_users FROM ".PRFX."TABLE_CUSTOMER WHERE CUSTOMER_DISPLAY_NAME=".$db->qstr($displayName);
	
	if(!$result = $db->Execute($sql)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	} else {
		$row = $result->FetchRow();
	}

	if ($row['num_users'] == 1) {
		return false;	
	} else {
		return true;
	}
}

#####################################
#	Add                         #
#####################################

function insert_new_customer($db,$VAR) {

//Remove Extra Slashes caused by Magic Quotes
$address_string = $VAR['address'];
$address_string = stripslashes($address_string);

$customerNotes_string = $VAR['customerNotes'];
$customerNotes_string = stripslashes($customerNotes_string);

//Display Name Logic
if ($VAR["displayName"] ==""){
 $displayname = $VAR["lastName"].", ".$VAR["firstName"] ;
} else {
$displayname =$VAR["displayName"] ;
}

	$sql = "INSERT INTO ".PRFX."TABLE_CUSTOMER SET
			CUSTOMER_DISPLAY_NAME           = ". $db->qstr( $displayname         ).",
			CUSTOMER_ADDRESS		= ". $db->qstr( $address_string      ).",
			CUSTOMER_CITY			= ". $db->qstr( $VAR["city"]         ).", 
			CUSTOMER_STATE			= ". $db->qstr( $VAR["state"]        ).", 
			CUSTOMER_ZIP			= ". $db->qstr( $VAR["zip"]          ).",
			CUSTOMER_PHONE			= ". $db->qstr( $VAR["homePhone"]    ).",
			CUSTOMER_WORK_PHONE             = ". $db->qstr( $VAR["workPhone"]    ).",
			CUSTOMER_MOBILE_PHONE           = ". $db->qstr( $VAR["mobilePhone"]  ).",
			CUSTOMER_EMAIL			= ". $db->qstr( $VAR["email"]        ).", 
			CUSTOMER_TYPE			= ". $db->qstr( $VAR["customerType"] ).", 
			CREATE_DATE			= ". $db->qstr( time()               ).",
			LAST_ACTIVE			= ". $db->qstr( time()               ).",
			CUSTOMER_FIRST_NAME		= ". $db->qstr( $VAR["firstName"]    ).", 
			DISCOUNT 			= ". $db->qstr( $VAR['discount']     ).",
			CUSTOMER_LAST_NAME		= ". $db->qstr( $VAR['lastName']     ).",
			CREDIT_TERMS                    = ". $db->qstr( $VAR['creditterms']  ).",
                        CUSTOMER_WWW                    = ". $db->qstr( $VAR['customerWww']  ).",
                        CUSTOMER_NOTES                  = ". $db->qstr( $customerNotes_string);
			
	if(!$result = $db->Execute($sql)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
    } else {
		$customer_id = $db->Insert_ID();
		return  $customer_id;
    }
	
} 

#####################################
#	Edit			    #
#####################################

function edit_info($db, $customer_id){
	$sql = "SELECT * FROM ".PRFX."TABLE_CUSTOMER WHERE CUSTOMER_ID=".$db->qstr($customer_id);
	
	if(!$result = $db->Execute($sql)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	} else {
		$row = $result->FetchRow();
		return $row;
	}
}

#####################################
#	Update			    #
#####################################

function update_customer($db,$VAR) {

//Remove Extra Slashes caused by Magic Quotes
$address_string = $VAR['address'];
$address_string = stripslashes($address_string);

$customerNotes_string = $VAR['customerNotes'];
$customerNotes_string = stripslashes($customerNotes_string);

	$sql = "UPDATE ".PRFX."TABLE_CUSTOMER SET
			CUSTOMER_DISPLAY_NAME           = ". $db->qstr( $VAR["displayName"]	).",
			CUSTOMER_ADDRESS		= ". $db->qstr( $address_string		).",
			CUSTOMER_CITY			= ". $db->qstr( $VAR["city"]		).", 
			CUSTOMER_STATE			= ". $db->qstr( $VAR["state"]		).", 
			CUSTOMER_ZIP			= ". $db->qstr( $VAR["zip"]		).",
			CUSTOMER_PHONE			= ". $db->qstr( $VAR["homePhone"]	).",
			CUSTOMER_WORK_PHONE             = ". $db->qstr( $VAR["workPhone"]	).",
			CUSTOMER_MOBILE_PHONE           = ". $db->qstr( $VAR["mobilePhone"]	).",
			CUSTOMER_EMAIL			= ". $db->qstr( $VAR["email"]		).", 
			CUSTOMER_TYPE			= ". $db->qstr( $VAR["customerType"]	).", 
			CUSTOMER_FIRST_NAME		= ". $db->qstr( $VAR["firstName"]	).", 
			CUSTOMER_LAST_NAME		= ". $db->qstr( $VAR["lastName"]	).",
			DISCOUNT                        = ". $db->qstr( $VAR['discount']	).",
                        CREDIT_TERMS                    = ". $db->qstr( $VAR['creditterms']     ).",
                        CUSTOMER_WWW                    = ". $db->qstr( $VAR['customerWww']     ).",
                        CUSTOMER_NOTES                  = ". $db->qstr( $customerNotes_string   )."
			WHERE CUSTOMER_ID		= ". $db->qstr( $VAR['customer_id']	);
			
	if(!$result = $db->Execute($sql)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
    } else {
      return true;
    }
	
} 

#####################################
#	Delete			    #
#####################################

function delete_customer($db,$customer_id){
	$sql = "DELETE FROM ".PRFX."TABLE_CUSTOMER WHERE CUSTOMER_ID=".$db->qstr($customer_id);
	
	if(!$rs = $db->Execute($sql)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;	
	} else {
		return true;
	}	
}

##################################################################
# The select array we will change this to database options later #
##################################################################

    $customer_type = array('Residential'=>'Residential', 'Comercial'=>'Comercial');

#####################################
#	          		    #
#####################################

function display_gift($db, $customer_id) {
	$q = "SELECT * FROM ".PRFX."GIFT_CERT WHERE CUSTOMER_ID=".$db->qstr( $customer_id );
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}
		
	$arr = $rs->GetArray();
	return $arr;
}

#####################################
#	       		            #
#####################################

function display_memo($db,$customer_id) {
	$q = "SELECT * FROM ".PRFX."CUSTOMER_NOTES WHERE CUSTOMER_ID=".$db->qstr( $customer_id );
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}
		
	$arr = $rs->GetArray();
	return $arr;
}

?>
