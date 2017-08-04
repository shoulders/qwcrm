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

/* Check if we have a customer_id
if($customer_id == '') {
    force_page('core', 'dashboard', 'warning_msg='.gettext("No Customer ID supplied."));
    exit;
}  */

/* Other Functions */

#######################################
#   Basic email wrapper function      #
#######################################

function send_email($to, $subject, $message, $attachment = null) {
    
    // this wrapper can be used as an intermedery so i can choose what email platform to use and also logging in the future
    
    //  PHP mail()
    $headers = 'From: no-reply@example.com' . "\r\n" .
    'Reply-To: no-reply@example.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
    
    //mail($to, $subject, $message, $headers);
    mail($to, $subject, $message, $headers);
    
}

#######################################
#   Basic email wrapper function      #
#######################################

function send_email_swift($recipient_name, $recipient_email, $subject, $message, $attachment = null) {
    
    $config = new QConfig;
    
    /* Create the Transport */
    
    // Use smtp server
    if($config->email_use_smtp) {        
        
        // Use smtp with encryption SSL/TLS   
        if($config->email_encryption) {
            $transport = (new Swift_SmtpTransport($config->email_server_host, $config->email_server_port, $config->email_encryption))         
                ->setUsername($config->email_username)
                ->setPassword($config->email_password)
            ;
            
        // smtp with no encryption    
        } else {           
            $transport = (new Swift_SmtpTransport($config->email_server_host, $config->email_server_port))
                ->setUsername($config->email_username)
                ->setPassword($config->email_password)
            ;
        }
    
    // Create the Mailer using your created Transport
    } else {        
        $transport = new Swift_SendmailTransport('/usr/sbin/exim -bs');
    }

    
    // If you need to know early whether or not authentication has failed and an Exception is going to be thrown, call the start() method on the created Transport.
    $mailer = new Swift_Mailer($transport); 


    /* Create a message */
    
    // Core settings
    $message = (new Swift_Message())        
        ->setFrom([$config->email_no_reply => 'QWcrm'])
        ->setTo([$recipient_email => $recipient_name])
        ->setSubject($subject)    
    ;
    
    // Build the message
    $message->setBody($message);                // use a library to change the message into plain text
    $message->addPart($message, 'text/html');
    
    
    // Add Optional attachment
    if($attachment != null) {
        
        // Create the attachment with your data
        $attachment = new Swift_Attachment($attachment['data'], $attachment['filename'], $attachment['filetype']);

        // Attach it to the message
        $message->attach($attachment);
        
    }
    
    /* Send the message */
    
    // Pass a variable name to the send() method
    if (!$mailer->send($message, $failures))
    {
      echo "Failures:";
      print_r($failures);
    }
    /*
    Failures:
    Array (
      0 => receiver@bad-domain.org,
      1 => other-receiver@bad-domain.org
    )
    */
    
}