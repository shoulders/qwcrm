<?php

require(INCLUDES_DIR.'modules/customer.php');
require(INCLUDES_DIR.'modules/employee.php');
require(LIBRARIES_DIR.'swift/swift_required.php');

// Lets grab some variables we need
$email_username = $VAR['email_username'];
$email_password = $VAR['email_password'];
$email_server = $VAR['email_server2'];
$email_server_port = $VAR['email_server_port2'];
$customer_id = $VAR['customer_id'];
$c2 = $VAR['c2'];
$download_id = $VAR['download_id'];
$submit         = $VAR['submit'];
$email_to = $VAR['email_to'];
$email_from = $VAR['email_from'];
$email_subject = $VAR['email_subject'];
$message_body = $VAR['message_body'];
$attachment = $VAR['attachment'];
$rr_email = $VAR['rr'];
$cus_name = $VAR['cus_name'];
//$sig = "<br>Regards,<br>".$employee_details['EMPLOYEE_FIRST_NAME']."<br>MyIT CRM Test";

/*//Get All customer Emails
$q = "SELECT * FROM ".PRFX."TABLE_CUSTOMER_EMAILS WHERE CUSTOMER_ID ='".$customer_id."' ORDER BY CUSTOMER_EMAIL_ID DESC" ;
$rs = $db->Execute($q);
$customer_emails = $rs->GetArray();
$smarty->assign('customer_emails', $customer_emails);*/

// assign the arrays
$smarty->assign('company_details',      get_company_details($db)                                                );
$smarty->assign('customer_details',     get_customer_details($db, $customer_id)                                 );
$smarty->assign('open_work_orders',     display_workorders($db, 10, 'DESC', false, 1, 25, NULL, $customer_id)   );
$smarty->assign('closed_work_orders',   display_workorders($db, 6, 'DESC', false, 1, 25, NULL, $customer_id)    );
$smarty->assign('unpaid_invoices',      display_invoices($db, 0, 'DESC', false, 1, 25, NULL, $customer_id)      );
$smarty->assign('paid_invoices',        display_invoices($db, 1, 'DESC', false, 1, 25, NULL, $customer_id)      );
$smarty->assign('giftcert_details',     display_giftcerts($db, $customer_id)                                    );
$smarty->assign('GoogleMapString',      build_googlemap_directions_string($db, $customer_id, $login_id)         );

$smarty->assign('memo',                 display_memo($db,$customer_id)                                          ); // what is this

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
            CUSTOMER_EMAIL_ADDRESS    = ". $db->qstr( $VAR["email_to"]).",
            CUSTOMER_FROM_EMAIL_ADDRESS = ". $db->qstr( $VAR["email_from"]).",
            CUSTOMER_EMAIL_SENT_BY        = ". $db->qstr( $login_usr ).", 
            CUSTOMER_EMAIL_SENT_ON        = ". $db->qstr( time()).",
            CUSTOMER_EMAIL_SUBJECT        = ". $db->qstr( $VAR["email_subject"]).",
            CUSTOMER_EMAIL_BODY    = ". $db->qstr( $VAR["message_body"]).",
            CUSTOMER_EMAIL_ATT_NAME1    = ". $db->qstr( $_FILES['attachment1']['name']).",
            CUSTOMER_EMAIL_ATT_TYPE1        = ". $db->qstr( $_FILES['attachment1']['type']).",
            CUSTOMER_EMAIL_ATT_SIZE1        = ". $db->qstr( $_FILES['attachment1']['size']).",
            CUSTOMER_EMAIL_ATT_FILE1    = ". $db->qstr( $content1 ); 
            
            
            
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
        echo "The file ".  basename( $_FILES['attachment1']['name'])." has been uploaded";
    } else {
        echo "There was an error uploading the file, please try again!";
    }
    
    $fname2 = QWCRM_PHYSICAL_PATH.$target_path ;
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

        // Generate Replacements
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

        // Send the message
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
}

// Display the template we will use
$BuildPage .= $smarty->fetch('customer/email.tpl');