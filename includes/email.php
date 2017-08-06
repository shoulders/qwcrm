<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

/** Main Include File **/

/*
 * Mandatory Code - Code that is run upon the file being loaded
 * Display Functions - Code that is used to primarily display records
 * New/Insert Functions - Creation of new records
 * Get Functions - Grabs specific records/fields ready for update
 * Update Functions - For updating records/fields
 * Close Functions - Closing Work Orders code
 * Delete Functions - Deleting Work Orders
 * Other Functions - All other functions not covered above
 */

defined('_QWEXEC') or die;

// Load the swiftmailer library
require(LIBRARIES_DIR.'swift/swift_required.php');

/* Other Functions */

#######################################
#   Basic email wrapper function      #
#######################################

function php_mail_fallback($to, $subject, $body, $attachment = null) {
    
    // this wrapper can be used as an intermedery so i can choose what email platform to use and also logging in the future
    
    //  PHP mail()
    $headers = 'From: no-reply@example.com' . "\r\n" .
    'Reply-To: no-reply@example.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
    
    //mail($to, $subject, $body, $headers);
    mail($to, $subject, $body, $headers);
    
}

#######################################
#   Basic email wrapper function      #
#######################################

function send_email($recipient_name, $recipient_email, $subject, $body, $attachment = null) {
    
    global $smarty;
    
    $config = new QConfig;
    $db = QFactory::getDbo();
    
    // If email is not enabled, do not send emails
    if($config->email_online != true) { die(); }
    
    /* Create the Transport */
    
    // Use SMTP
    if($config->email_mailer == 'smtp') {        
                 
        // Create SMTP Object 
        $transport = new Swift_SmtpTransport($config->email_smtp_host, $config->email_smtp_port);        

        // Enable encryption SSL/TLS if set
        if($config->email_smtp_security != '') {
            $transport->setEncryption($config->email_smtp_security);
        }

        // SMTP Authentication if set
        if($config->email_smtp_auth) {
            $transport->setUsername($config->email_smtp_username);
            $transport->setPassword($config->email_smtp_password); 
        }                               
    
    // Use Sendmail / Locally installed MTA - only works on Linux/Unix
    } elseif($config->email_mailer == 'sendmail') {
        
        // Standard sendmail
        $transport = new Swift_SendmailTransport($config->email_sendmail_path.' -bs');
        
        // Exim - The Swift_SendmailTransport supports the use of Exim
        //$transport = new Swift_SendmailTransport('/usr/sbin/exim -bs');
                 
    // Use PHP Mail
    } else {
        
        $transport = new Swift_MailTransport();   
        
    } 
    
    /* Create the object */
    
    // If you need to know early whether or not authentication has failed and an Exception is going to be thrown, call the start() method on the created Transport.
    //print_r($transport->start());
    
    // Create the Mailer using your created Transport
    $mailer = new Swift_Mailer($transport);
    
    /* Logging */                  // PHP Mail does not give logg messages, just success or fail
    
    // When Swift Mailer sends messages it will keep a log of all the interactions with the underlying Transport being used.
    // https://swiftmailer.symfony.com/docs/plugins.html#using-the-logger-plugin
    
    // ArrayLogger - Keeps a collection of log messages inside an array. The array content can be cleared or dumped out to the screen.
    $logger = new Swift_Plugins_Loggers_ArrayLogger();
    $mailer->registerPlugin(new Swift_Plugins_LoggerPlugin($logger));

    // Echo Logger - Prints output to the screen in realtime. Handy for very rudimentary debug output.
    //$logger = new Swift_Plugins_Loggers_EchoLogger();
    //$mailer->registerPlugin(new Swift_Plugins_LoggerPlugin($logger));
        
    /* Create a message */
    
    // Mandatory email settings
    $email = new Swift_Message();
    
    
    // Verify the supplied emails, then add them to the object
    try {
        $email->setTo([$recipient_email => $recipient_name]);
        $email->setFrom([$config->email_mailfrom => $config->email_fromname]);   
        
        // Only add 'Reply To' if the reply email address is present. this to prevents email errors
        if($config->email_replyto != '') {
            $email->setReplyTo([$config->email_replyto => $config->email_replytoname]);  
        }
        
    }
    
    // This will present any email RFC compliance issues
    catch(Swift_RfcComplianceException $RfcCompliance_exception) {
        //var_dump($RfcCompliance_exception);  // gets everything
        $record = gettext("Failed to send email to").' '.$recipient_email.' ('.$recipient_name.')';        
        write_record_to_activity_log($record);
        write_record_to_email_error_log($RfcCompliance_exception->getMessage());
        $smarty->assign('warning_msg', $record.'<br>'.$RfcCompliance_exception->getMessage());        
    }
    
    // Subject - prefix with the QWcrm company name to all emails
    $email->setSubject(get_company_details($db, 'name').' - '.$subject);    
    
    /* Build the message body */
    
    // Add the signature
    $body .= get_email_signature($db, $email);
    
    // Add Message Body
    $email->setBody($body, 'text/html');
    
    // Optional Alternative Body (useful for text fallback version) - use a library to change the message into plain text?   
    //$email->addPart('My amazing body in plain text', 'text/plain');    
    
    // Add Optional attachment
   if($attachment != null) {
        
        // Create the attachment with your data
        $attachment = new Swift_Attachment($attachment['data'], $attachment['filename'], $attachment['filetype']);

        // Attach it to the message
        $email->attach($attachment);
        
    }
    
    /* Send the message - and catch transport errors (delivery errors depend on the transport method)*/
    
    try {
        if (!$mailer->send($email))
        {
            /* Finding out Rejected Addresses - useful for batch emails
            if (!$mailer->send($email, $failures)) {
                echo "Failures:";
                print_r($failures);
            }
            
            Failures:
            Array (
                0 => receiver@bad-domain.org,
                1 => other-receiver@bad-domain.org
                )
            */            
            
            // If the email failed to send
            $record = gettext("Failed to send email to").' '.$recipient_email.' ('.$recipient_name.')';            
            write_record_to_activity_log($record);
            $smarty->assign('warning_msg', $record);

        } else {

            // Successfully sent the email
            $record = gettext("Successfully sent email to").' '.$recipient_email.' ('.$recipient_name.')';            
            write_record_to_activity_log($record);
            $smarty->assign('information_msg', $record);

        }
    }
    
    // This will present any transport errors
    catch(Swift_TransportException $Transport_exception) {
        //var_dump($transport_exception);  // gets everything
        $record = gettext("Failed to send email to").' '.$recipient_email.' ('.$recipient_name.')';
        write_record_to_activity_log($record);
        write_record_to_email_error_log($Transport_exception->getMessage());
        $smarty->assign('warning_msg', $record.'<br>'.$Transport_exception->getMessage());
    }
    
    // This will present any general swiftmailer issues
    catch(Exception $swift_exception) {
        //var_dump($swift_exception);  // gets everything
        $record = gettext("Failed to send email to").' '.$recipient_email.' ('.$recipient_name.')';
        write_record_to_activity_log($record);
        write_record_to_email_error_log($swift_exception->getMessage());
        $smarty->assign('warning_msg', $record.'<br>'.$swift_exception->getMessage());
    }
    
    // Write the Email Transport Record to the log
    write_record_to_email_transport_log($logger->dump());
   
}

##############################################
#  Write a record to the Email Error Log     #
##############################################

function write_record_to_email_error_log($record) {
    
    // if email error logging is not enabled exit
    if(QFactory::getConfig()->get('qwcrm_email_error_log') != true) { return; }    
    
    // Build log entry    
    $log_entry .= $_SERVER['REMOTE_ADDR'] . ',' . QFactory::getUser()->login_username . ',' . date("[d/M/Y:H:i:s O]", time())."\r\n\r\n";
    $log_entry .= $record . "\r\n\r\n";
    $log_entry .= '-----------------------------------------------------------------------------' . "\r\n\r\n";
    
    // Write log entry
    if(!$fp = fopen(EMAIL_ERROR_LOG, 'a')) {        
        force_error_page($_GET['page'], 'file', __FILE__, __FUNCTION__, '', '', gettext("Could not open the Email Error Log to save the record."));
        exit;
    }
    
    fwrite($fp, $log_entry);
    fclose($fp);
        
    return;
    
}

##############################################
#  Write a record to the Email Transport Log #
##############################################

function write_record_to_email_transport_log($record) {
    
    // if email transport logging is not enabled exit
    if(QFactory::getConfig()->get('qwcrm_email_transport_log') != true) { return; }
    
    // Build log entry
    $log_entry .= $_SERVER['REMOTE_ADDR'] . ',' . QFactory::getUser()->login_username . ',' . date("[d/M/Y:H:i:s O]", time())."\r\n\r\n";
    $log_entry .= $record . "\r\n\r\n";
    $log_entry .= '-----------------------------------------------------------------------------' . "\r\n\r\n";
    
    // Write log entry  
    if(!$fp = fopen(EMAIL_TRANSPORT_LOG, 'a')) {        
        force_error_page($_GET['page'], 'file', __FILE__, __FUNCTION__, '', '', gettext("Could not open the Email Transport Log to save the record."));
        exit;
    }
    
    fwrite($fp, $log_entry);
    fclose($fp);
        
    return;
    
}
