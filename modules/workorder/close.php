<?php
if(!xml2php("workorder")) {
	$smarty->assign('error_msg',"Error in language file");
}
require_once ("include.php");

if(empty($VAR['wo_id'])){
	force_page('core', 'error&error_msg=No Work Order ID');
	exit;
}
$wo_id = $VAR['wo_id'];

/* Check if work Order Is already Closed*/
$q = "SELECT WORK_ORDER_STATUS,WORK_ORDER_CURRENT_STATUS FROM ".PRFX."TABLE_WORK_ORDER WHERE WORK_ORDER_ID=".$db->qstr($wo_id);
if(!$rs = $db->execute($q)) {
	force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
	exit;
}

if($rs->fields['WORK_ORDER_STATUS'] == 9) {
	force_page('workorder', "view&wo_id=$wo_id&error_msg=Work Order Is already Closed. Please Create an Invoice.&page_title=Work Order ID $wo_id&type=info");
} elseif ($rs->fields['WORK_ORDER_CURRENT_STATUS'] == 3) {
	force_page('workorder', "view&wo_id=$wo_id&error_msg=Can not close a work order if it is Waiting For Parts. Please Adjust the status.&page_title=Work Order ID $wo_id&type=warning");
}

// loads resolution if it exists from the database
    $q = "SELECT WORK_ORDER_RESOLUTION FROM ".PRFX."TABLE_WORK_ORDER WHERE WORK_ORDER_ID=".$db->qstr( $wo_id );
            if(!$rs = $db->execute($q)) {
            force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
            exit;
            }

// Loads work resolution if present for editing into text area
    $close = $rs->fields['WORK_ORDER_RESOLUTION'];
    $smarty->assign('close', $close);



// Update Work Resolution Only
    if(isset($VAR['submitchangesonly'])) {

    //Remove Extra Slashes caused by Magic Quotes
    $resolution_string = $VAR['resolution'];
    $resolution_string = stripslashes($resolution_string);

            $q = "UPDATE ".PRFX."TABLE_WORK_ORDER SET
                            WORK_ORDER_RESOLUTION   =".$db->qstr( $resolution_string    ).",
                            LAST_ACTIVE             =".$db->qstr( time()                )."
                            WHERE  WORK_ORDER_ID    =".$db->qstr( $wo_id                );

            if(!$rs = $db->execute($q)) {
                    force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
                    exit;
                    }

            $msg = 'Resolution has been Updated';
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

                        }


// Close without invoice
    if(isset($VAR["closewithoutinvoice"])){

            if (!close_work_order_no_invoice($db,$VAR)){
                    force_page('workorder', "view&wo_id=$wo_id&error_msg=Failed to Close Work Order.&page_title=Work Order ID $wo_id");

                } else {

                        $q = "SELECT CUSTOMER_ID FROM ".PRFX."TABLE_WORK_ORDER WHERE WORK_ORDER_ID=".$db->qstr($wo_id);
                        if(!$rs = $db->execute($q)) {
                                force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
                                exit;
                                                    }
                    force_page('workorder', "main&page_title=Work Orders");
                       }
         }

// Close with invoice
    if(isset($VAR["closewithinvoice"])){

            if (!close_work_order($db,$VAR)){
                    force_page('workorder', "view&wo_id=$wo_id&error_msg=Failed to Close Work Order.&page_title=Work Order ID $wo_id");

                } else {

                        $q = "SELECT CUSTOMER_ID FROM ".PRFX."TABLE_WORK_ORDER WHERE WORK_ORDER_ID=".$db->qstr($wo_id);
                        if(!$rs = $db->execute($q)) {
                                force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
                                exit;
                        }

                        $customer_id = $rs->fields['CUSTOMER_ID'];
                        force_page('invoice', 'new&wo_id='.$wo_id.'&customer_id='.$customer_id.'&page_title=Create Invoice for Work Order# wo_id='.$wo_id);
                }

    }

// If nothing else it loads the work order resolution page
else {
		$smarty->assign('wo_id', $VAR['wo_id']);
		$smarty->display('workorder'.SEP.'close.tpl');
     }
	
?>
