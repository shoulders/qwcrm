<?php
require_once("include.php");
//Required for swift mailer
require_once (INCLUDE_URL.'/swift/lib/swift_required.php');
if(!xml2php("customer")) {
	$smarty->assign('error_msg',"Error in language file");
}
// Lets grab some variables we need
$customer_id = $VAR['customer_id'];
$submit		 = $VAR['submit'];
$email_to = $VAR['email_to'];
$email_from = $VAR['email_from'];
$email_subject = $VAR['email_subject'];
$message_body = $VAR['message_body'];
$attachment = $VAR['attachment'];
$rr_email = $VAR['rr'];
//Get Employee Info
$q = "SELECT * FROM ".PRFX."TABLE_EMPLOYEE WHERE EMPLOYEE_DISPLAY_NAME ='".$login."'" ;
$rs = $db->Execute($q);
$employee_details = $rs->FetchRow();
$smarty->assign('employee_details', $employee_details);
//Get Customer Info
$q = "SELECT * FROM ".PRFX."TABLE_CUSTOMER WHERE CUSTOMER_ID ='".$customer_id."'" ;
$rs = $db->Execute($q);
$customer_details = $rs->FetchRow();
$smarty->assign('customer_details', $customer_details);
//Get Company Info
$q = "SELECT * FROM ".PRFX."TABLE_COMPANY";
$rs = $db->Execute($q);
$company_details2 = $rs->FetchRow(1);
$smarty->assign('company_details2', $employee_details2);
//Determine if Employee Works from Home or Office for Google Maps URL former
if ($employee_details['EMPLOYEE_BASED'] > 0){
$caddress2 = $employee_details['EMPLOYEE_ADDRESS'];
$ccity2 = $employee_details['EMPLOYEE_CITY'];
$czip2 = $employee_details['EMPLOYEE_ZIP'];
} else {
$caddress2 = $company_details2['COMPANY_ADDRESS'];
$ccity2 = $company_details2['COMPANY_CITY'];
$czip2 = $company_details2['COMPANY_ZIP'];
}
//Employee Details for parsing to Google Maps URL
$smarty->assign('caddress2',$caddress2);
$smarty->assign('ccity2',$ccity2);
$smarty->assign('czip2',$czip2);
//Customer Address for parsing to google maps URL
$cusaddress3 = $customer_details['CUSTOMER_ADDRESS'];
$cuscity3 = $customer_details['CUSTOMER_CITY'];
$cuszip3 = $customer_details['CUSTOMER_ZIP'];
//Assign these to Smarty
$smarty->assign('cusaddress3',$cusaddress3);
$smarty->assign('cuscity3',$cuscity3);
$smarty->assign('cuszip3',$cuszip3);
//Google Maps URL for IFrame in customer_details.tpl
$src= "http://maps.google.com.au/maps?f=d&source=s_d&hl=en&geocode=&saddr=$caddress2,$ccity2,$czip2&daddr=$cusaddress3,$cuscity3,$cuszip3";
$smarty->assign('src',$src);
// BOF Email Message details
//Mail
if(isset ($submit)){
$transport = Swift_smtpTransport::newInstance('127.0.0.1', 25)
        ->setUsername('root')
        ->setPassword('root');
if($rr < 1 ){
    $rr_email = "";
    } else {
     $rr_email = $email_from ;
    }
//Do we have an attachment to include and upload?
if($_FILES['attachment']['size'] >  0 ){
$target_path = "upload/";
$target_path = $target_path . basename( $_FILES['attachment']['name']);

        if(move_uploaded_file($_FILES['attachment']['tmp_name'], $target_path)){
            echo "The file ".  basename( $_FILES['attachment']['name']).
            " has been uploaded";
        } else {
            echo "There was an error uploading the file, please try again!";
        }
$fname2 = FILE_ROOT.$target_path ;
$mailer = Swift_Mailer::newInstance($transport);
//Create a message
$message = Swift_Message::newInstance($email_subject)
  ->setFrom($email_from)
  ->setTo($email_to)
  ->setBody($message_body , 'text/html' )
  ->addPart($message_body, 'text/plain')
  ->attach(Swift_Attachment::fromPath($target_path))
  ;
//Send the message
$numSent = $mailer->send($message);
//Display how many messages were sent
printf("Sent %d messages\n", $numSent);
//Show what file was uploaded
//printf("File Location", $fname2);
//Assign the variables with smarty
$smarty->assign('email_subject',$email_subject);
$smarty->assign('email_from',$email_from);
$smarty->assign('email_to',$email_to);
$smarty->assign('message_body',$message_body);
$smarty->assign('attachment',$attachment);
// EOF Email Message details
force_page('customer' ,"view&page_title=Customers");
//Delete uploaded files
/*
chown($target_path , 777);
unset($target_path);
unlink($target_path);*/
} else {
$mailer = Swift_Mailer::newInstance($transport);
//Create a message
$message = Swift_Message::newInstance($email_subject)
  ->setFrom($email_from)
  ->setTo($email_to)
  ->setBody($message_body, 'text/html')
  ->addPart($message_body, 'text/plain')
  ;
//Send the message
$numSent = $mailer->send($message);
//Display how many messages were sent
printf("Sent %d messages\n", $numSent);
//Show what file was uploaded
//printf("File Location", $fname2);
//Assign the variables with smarty
$smarty->assign('email_subject',$email_subject);
$smarty->assign('email_from',$email_from);
$smarty->assign('email_to',$email_to);
$smarty->assign('message_body',$message_body);
$smarty->assign('rr',$rr);
// EOF Email Message details
force_page('customer' ,"view&page_title=Customers");
}
}
// assign the arrays
$smarty->assign('open_work_orders',	display_open_workorders($db, $customer_id));
$smarty->assign('closed_work_orders',	display_closed_workorders($db, $customer_id));
$smarty->assign('customer_details',	display_customer_info($db, $customer_id));
$smarty->assign('unpaid_invoices', display_unpaid_invoices($db,$customer_id));
$smarty->assign('paid_invoices', display_paid_invoices($db,$customer_id));
$smarty->assign('memo',	display_memo($db,$customer_id));
$smarty->assign('gift',	display_gift($db, $customer_id));
$smarty->assign('company_details',display_company_info($db, $company_id));
//Display the template we will use
$smarty->display('customer'.SEP.'email.tpl');

?>
