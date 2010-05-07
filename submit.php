<?php
require("conf.php");
$VAR = array_merge($_GET,$_POST);
//Translation
require('modules/core/translate.php');
if(!xml2php("core")) {
	$smarty->assign('error_msg',"Error in language file");
}
//Assign Some variables
$notes = "Time to Call is ".$VAR["time"]."<br>Severity is ".$VAR["priority"];
$email = $VAR["from"];
$smarty->assign('page_title', "{$translate_submit_page_title}");


//Display Any Errors
if(isset($_GET["error_msg"]))
{
	$smarty->assign('error_msg', $_GET["error_msg"]);
}
//Insert into new works order from submit page
if(isset($_POST['submit']))
    {
    //Echo "I clicked the button";
    /*Find Customer ID based on Email*/
    $q= "SELECT CUSTOMER_ID FROM ".PRFX."TABLE_CUSTOMER WHERE CUSTOMER_EMAIL=".$db->qstr($email) ;
	if(!$rs = $db->Execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}
	$customer = $rs->fields['CUSTOMER_ID'];
        //print $q;
        //print $customer ;
            if($customer!=''){

                $sql = "INSERT INTO ".PRFX."TABLE_WORK_ORDER  SET
                                    CUSTOMER_ID			=".$db->qstr($customer).",
                                    WORK_ORDER_OPEN_DATE	=".$db->qstr(time()).",
                                    WORK_ORDER_STATUS		=".$db->qstr(10).",
                                    WORK_ORDER_CURRENT_STATUS	=".$db->qstr(1).",
                                    WORK_ORDER_CREATE_BY	='Web Form',
                                    WORK_ORDER_SCOPE		=".$db->qstr($VAR["subject"]).",
                                    WORK_ORDER_DESCRIPTION	=".$db->qstr($VAR["description"]).",
                                    LAST_ACTIVE			=".$db->qstr(time()).",
                                    WORK_ORDER_COMMENT          =".$db->qstr($notes);

                if(!$result = $db->Execute($sql)) {
                            force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
                            exit;
                    }
                //Display how many messages were sent
                echo "<script>alert('Thankyou for your request. A confirmation email has been sent to $email')</script>";
                $smarty->assign('VERSION', MYIT_CRM_VERSION);
                $smarty->display('core'.SEP.'login.tpl');
            }
            if($customer ==''){
                echo "<script>alert('We didn\'t find your details.\\n Please click the blinking section \\n \\n \"First time using our service\, Please Click Here\" \\n \\n to provide us with some additional information about yourself.')</script>";
                echo "<script>history.go(-1)</script>";
                

            }
    }else{
    $smarty->assign('VERSION', MYIT_CRM_VERSION);
    $smarty->display('core'.SEP.'submit.tpl');
}
?>
