<?php
require_once("include.php");
//Required for swift mailer
require_once (INCLUDE_URL.'/swift/lib/swift_required.php');
if(!xml2php("customer")) {
	$smarty->assign('error_msg',"Error in language file");
}
// Lets grab some variables we need
$email_username = $VAR['email_username'];
$email_password = $VAR['email_password'];
$email_server = $VAR['email_server2'];
$email_server_port = $VAR['email_server_port2'];
$customer_id = $VAR['customer_id'];
$c2 = $VAR['c2'];
$download_id = $VAR['download_id'];
$submit		 = $VAR['submit'];
$email_to = $VAR['email_to'];
$email_from = $VAR['email_from'];
$email_subject = $VAR['email_subject'];
$message_body = $VAR['message_body'];
$attachment = $VAR['attachment'];
$rr_email = $VAR['rr'];
$cus_name = $VAR['cus_name'];
//$sig = "<br>Regards,<br>".$employee_details['EMPLOYEE_FIRST_NAME']."<br>MyIT CRM Test";

//Get All customer Emails
$q = "SELECT * FROM ".PRFX."TABLE_CUSTOMER_EMAILS WHERE CUSTOMER_ID ='".$customer_id."' ORDER BY CUSTOMER_EMAIL_ID DESC" ;
$rs = $db->Execute($q);
$customer_emails = $rs->GetArray();
$smarty->assign('customer_emails', $customer_emails);
/*Get Customer Info */
$q = "SELECT * FROM ".PRFX."TABLE_CUSTOMER WHERE CUSTOMER_ID ='".$customer_id."'" ;
$rs = $db->Execute($q);
$customer_details = $rs->GetArray();
$smarty->assign('customer_details', $customer_details);
/*Get Employee Info */
$q = "SELECT * FROM ".PRFX."TABLE_EMPLOYEE WHERE EMPLOYEE_DISPLAY_NAME ='".$login."'" ;
$rs = $db->Execute($q);
$employee_details = $rs->FetchRow();
$smarty->assign('employee_details', $employee_details);
// assign the arrays
$smarty->assign('open_work_orders',	display_open_workorders($db, $customer_id));
$smarty->assign('closed_work_orders',	display_closed_workorders($db, $customer_id));
//$smarty->assign('customer_details',	display_customer_info($db, $customer_id));
$smarty->assign('customer_details',$customer_details);
$smarty->assign('unpaid_invoices', display_unpaid_invoices($db,$customer_id));
$smarty->assign('paid_invoices', display_paid_invoices($db,$customer_id));
$smarty->assign('memo',	display_memo($db,$customer_id));
$smarty->assign('gift',	display_gift($db, $customer_id));
$smarty->assign('company_details',display_company_info($db, $company_id));
//Lets Get the file downloaded to have a look at it from the database
if(isset ($download_id)){
 /*Get All customer Emails */
$q = "SELECT CUSTOMER_EMAIL_ATT_NAME1, CUSTOMER_EMAIL_ATT_TYPE1, CUSTOMER_EMAIL_ATT_SIZE1, CUSTOMER_EMAIL_ATT_FILE1,  FROM ".PRFX."TABLE_CUSTOMER_EMAILS WHERE CUSTOMER_EMAIL_ID ='".$download_id."'" ;
$rs = $db->Execute($q);
//header("Content-length: $rs->fields['CUSTOMER_EMAIL_ATT_SIZE1']");
//header("Content-type: $rs->fields['CUSTOMER_EMAIL_ATT_TYPE1']");
//header("Content-Disposition: attachment; filename=$rs->fields['CUSTOMER_EMAIL_ATT_NAME1']");
$file_download= $rs->fields['CUSTOMER_EMAIL_ATT_FILE1'];
$smarty->assign('file_download', $file_download);
//Print $CUSTOMER_EMAIL_ATT_NAME1;
 exit;

 }
// BOF Email Message details
//Mail
if(isset ($submit)){
    if($_FILES['attachment1']['size'] >  0 ){
    $fp      = fopen($_FILES['attachment1']['tmp_name'], 'r');
    $content1 = fread($fp, filesize($_FILES['attachment1']['tmp_name']));
    $content1 = addslashes($content1);
    fclose($fp);
    }
    $sql = "INSERT INTO ".PRFX."TABLE_CUSTOMER_EMAILS SET
			CUSTOMER_ID             = ". $db->qstr($VAR["c2"]).",
			CUSTOMER_EMAIL_ADDRESS	= ". $db->qstr( $VAR["email_to"]).",
			CUSTOMER_FROM_EMAIL_ADDRESS = ". $db->qstr( $VAR["email_from"]).",
			CUSTOMER_EMAIL_SENT_BY		= ". $db->qstr( $login ).", 
			CUSTOMER_EMAIL_SENT_ON		= ". $db->qstr( time()).",
			CUSTOMER_EMAIL_SUBJECT		= ". $db->qstr( $VAR["email_subject"]).",
			CUSTOMER_EMAIL_BODY	= ". $db->qstr( $VAR["message_body"]).",
			CUSTOMER_EMAIL_ATT_NAME1	= ". $db->qstr( $_FILES['attachment1']['name']).",
			CUSTOMER_EMAIL_ATT_TYPE1		= ". $db->qstr( $_FILES['attachment1']['type']).",
			CUSTOMER_EMAIL_ATT_SIZE1		= ". $db->qstr( $_FILES['attachment1']['size']).",
			CUSTOMER_EMAIL_ATT_FILE1	= ". $db->qstr( $content1 ); 
			
			
			
	if(!$result = $db->Execute($sql)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
    }
//print $sql ;
$transport = Swift_smtpTransport::newInstance( "$email_server" , $email_server_port );
$mailer = Swift_Mailer::newInstance($transport);
//Do we have an attachment to include and upload?
if($_FILES['attachment1']['size'] >  0 ){
$target_path = "upload/";
$target_path = $target_path . basename( $_FILES['attachment1']['name']);

        if(move_uploaded_file($_FILES['attachment1']['tmp_name'], $target_path)){
            echo "The file ".  basename( $_FILES['attachment1']['name']).
            " has been uploaded";
        } else {
            echo "There was an error uploading the file, please try again!";
        }
$fname2 = FILE_ROOT.$target_path ;
$strip = stripslashes($message_body);
//$mailer = Swift_Mailer::newInstance($transport);
//Create a message
$message = Swift_Message::newInstance($email_subject)
  ->setFrom(array($email_from => $employee_details['EMPLOYEE_FIRST_NAME']))
  ->setTo(array($email_to => $cus_name))
  ->setBody($strip )
  ->addPart('Hello '.$cus_name , 'text/html')
  ->addPart($message_body, 'text/plain')
  ->attach(Swift_Attachment::fromPath($target_path))
  ;
//Send the message
$numSent = $mailer->send($message);
//Display how many messages were sent
echo "<script>alert('Email Information')</script>";
echo "Sent %d messages\n", $numSent;
exit(); 

//Show what file was uploaded
//printf("File Location", $fname2);
//Assign the variables with smarty
$smarty->assign('email_subject',$email_subject);
$smarty->assign('email_from',$email_from);
$smarty->assign('email_to',$email_to);
$smarty->assign('message_body',$message_body);
$smarty->assign('attachment1',$attachment1);
// EOF Email Message details
force_page('customer' ,"view&page_title=Customers");
//Delete uploaded files
/*
chown($target_path , 777);
unset($target_path);
unlink($target_path);*/
} else {
$users = array($email_from => $employee_details['EMPLOYEE_FIRST_NAME']);
//Generate Replacements
    $replacements = array();
   foreach($users as $email => $user) {
      $replacements[$email] = array(
        '{name}' => $employee_details['EMPLOYEE_FIRST_NAME'],
        '{sig}' => $sig
      );
    }
    $decorator = new Swift_Plugins_DecoratorPlugin($replacements);
    $mailer->registerPlugin($decorator);
$message = Swift_Message::newInstance($email_subject)
  ->setFrom(array($email_from => $employee_details['EMPLOYEE_FIRST_NAME']))
  ->setTo($users)
  ->setBody($message_body, 'text/html')
  //->addPart($message_body, 'text/plain')
  ;
//Send the message
    $numSent = $mailer->send($message);
//Display how many messages were sent
    echo "<script>alert('Successfully Sent $numSent message')</script>";
    echo "<script>navigate('?page=customer:email&customer_id=".$c2."&page_title=Email Customer')</script>"; 
//Show what file was uploaded
//printf("File Location", $fname2);
//Assign the variables with smarty
    $smarty->assign('email_subject',$email_subject);
    $smarty->assign('email_from',$email_from);
    $smarty->assign('email_to',$email_to);
    $smarty->assign('message_body',$message_body);
    $smarty->assign('rr',$rr);
    $smarty->assign('file_download',$file_download);
// EOF Email Message details
    force_page('customer' ,"email&customer_id=".$c2."&page_title=Email Customer");
}
}///Display the template we will use
    $smarty->display('customer'.SEP.'email.tpl');

?>
