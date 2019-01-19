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

// Load Dependency Manually (Not needed because it is loaded by Composer)
//require(LIBRARIES_DIR.'swift/swift_required.php')

/* Other Functions */

#######################################
#   Basic email wrapper function      #  // not currently used
#######################################

function php_mail_fallback($to, $subject, $body, $attachment = null) {
    
    // this wrapper can be used as an intermediary so i can choose what email platform to use and also logging in the future
    
    //  PHP mail()
    $headers = 'From: no-reply@example.com' . "\r\n" .
    'Reply-To: no-reply@example.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
    
    //mail($to, $subject, $body, $headers);
    mail($to, $subject, $body, $headers);
    
}

#######################################
#   Basic email wrapper function      #  // Silent option is need for password reset
#######################################

function send_email($recipient_email, $subject, $body, $recipient_name = null, $attachment = null, $employee_id = null, $client_id = null, $workorder_id = null, $invoice_id = null, $silent = false) {
        
    $config = QFactory::getConfig();
    //$smarty = QFactory::getSmarty();
    
    // Clear any onscreen notifications - this allows for mutiple errors to be displayed (if allowed)
    if (!$silent) {
        ajax_clear_onscreen_notifications();
    }
    
    // If email is not enabled, do not send emails
    if(!$config->get('email_online')) {
        
        // Log activity 
        $record = _gettext("Failed to send email to").' '.$recipient_email.' ('.$recipient_name.')';        
        write_record_to_activity_log($record, $employee_id, $client_id, $workorder_id, $invoice_id);
        
        // Output the system message to the browser (if allowed)
        if (!$silent) {
            $system_message = $record.'<br>'._gettext("The email system is not enabled, contact the administrators.");
            //$smarty->assign('warning_msg', $system_message);
            ajax_output_notifications_onscreen('', $system_message);
        }
        
        return false;
        
    }    
   
    /* Create the Transport */
    
    // Use SMTP
    if($config->get('email_mailer') == 'smtp') {        
                 
        // Create SMTP Object 
        $transport = new Swift_SmtpTransport($config->get('email_smtp_host'), $config->get('email_smtp_port'));        

        // Enable encryption SSL/TLS if set
        if($config->get('email_smtp_security')) {
            $transport->setEncryption($config->get('email_smtp_security'));
        }

        // SMTP Authentication if set
        if($config->get('email_smtp_auth')) {
            $transport->setUsername($config->get('email_smtp_username'));
            $transport->setPassword($config->get('email_smtp_password')); 
        }                               
    
    // Use Sendmail / Locally installed MTA - only works on Linux/Unix
    } elseif($config->get('email_mailer') == 'sendmail') {
        
        // Standard sendmail
        $transport = new Swift_SendmailTransport($config->get('email_sendmail_path').' -bs');
        
        // Exim - The Swift_SendmailTransport also supports the use of Exim (same ninary wrapper as sendmail)
        //$transport = new Swift_SendmailTransport('/usr/sbin/exim -bs');
                 
    // Use PHP Mail
    } else {
        
        $transport = new Swift_MailTransport();   
        
    } 
    
    /* Create the mailer object */
    
    // If you need to know early whether or not authentication has failed and an Exception is going to be thrown, call the start() method on the created Transport.
    //print_r($transport->start());
    
    // Create the Mailer using your created Transport
    $mailer = new Swift_Mailer($transport);
    
    /* Logging */                  // PHP Mail does not give log messages, just success or fail
    
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
        $email->setFrom([$config->get('email_mailfrom') => $config->get('email_fromname')]);   
        
        // Only add 'Reply To' if the reply email address is present. This prevents errors.
        if($config->get('email_replyto')) {
            $email->setReplyTo([$config->get('email_replyto') => $config->get('email_replytoname')]);  
        }
        
    }
    
    // This will present any email RFC compliance issues
    catch(Swift_RfcComplianceException $RfcCompliance_exception) {
        
        //var_dump($RfcCompliance_exception);
        
        // Log activity 
        $record = _gettext("Failed to send email to").' '.$recipient_email.' ('.$recipient_name.')';
        write_record_to_email_error_log($RfcCompliance_exception->getMessage());
        write_record_to_activity_log($record, $employee_id, $client_id, $workorder_id, $invoice_id);        
        
        // Output the system message to the browser (if allowed)
        if (!$silent) {
            $system_message = $record.'<br>'.$RfcCompliance_exception->getMessage();
            //$smarty->assign('warning_msg', $system_message);
            ajax_output_notifications_onscreen('', $system_message);
        }
        
        return false;
        
    }
    
    // Subject - prefix with the QWcrm company name to all emails
    $email->setSubject(get_company_details('company_name').' - '.$subject);    
    
    /* Build the message body */
    
    // Add the email signature if enabled (if not a reset email)
    if(get_company_details('email_signature_active') && !check_page_accessed_via_qwcrm('user', 'reset') && !check_page_accessed_via_qwcrm('administrator', 'config')) {
        $body .= add_email_signature($email);
    } 
    
    // Parse message body and convert links to SEF (if enabled)
    if ($config->get('sef')) { email_links_to_sef($body); }  
    
    // Add Message Body
    $email->setBody($body, 'text/html');
    
    // Optional Alternative Body (useful for text fallback version) - use a library to change the message into plain text?   
    //$email->addPart('My amazing body in plain text', 'text/plain');    
    
    // Add Optional attachment
    if($attachment) {
        
        // Create the attachment with your data
        $attachment = new Swift_Attachment($attachment['data'], $attachment['filename'], $attachment['filetype']);

        // Attach it to the message
        $email->attach($attachment);
        
    }
    
    /* Send the message - and catch transport errors (delivery errors depend on the transport method)*/
    
    try {
        if (!$mailer->send($email))
        {
            
            // If the email failed to send
            
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
            
            // Log activity             
            $record = _gettext("Failed to send email to").' '.$recipient_email.' ('.$recipient_name.')';            
            write_record_to_activity_log($record, $employee_id, $client_id, $workorder_id, $invoice_id);
            
            // Output the system message to the browser (if allowed)
            if (!$silent) {
                $system_message = $record;
                //$smarty->assign('warning_msg', $system_message);
                ajax_output_notifications_onscreen('', $system_message);
            }

        } else {
            
            // Successfully sent the email 
            
            // Log activity
            $record = _gettext("Successfully sent email to").' '.$recipient_email.' ('.$recipient_name.')'.' '._gettext("with the subject").' : '.$subject; 
            if($workorder_id) {insert_workorder_history_note($workorder_id, $record.' : '._gettext("and was sent by").' '.QFactory::getUser()->login_display_name);}
            write_record_to_activity_log($record, $employee_id, $client_id, $workorder_id, $invoice_id);            
            
            // Output the system message to the browser (if allowed)
            if (!$silent) {
                $system_message = $record;
                //$smarty->assign('information_msg', $system_message);
                ajax_output_notifications_onscreen($system_message, '');
            }
            
            // Update last active record (will not error if no invoice_id sent )
            update_user_last_active($employee_id);
            if($client_id) {update_client_last_active($client_id);}  
            if($workorder_id) {update_workorder_last_active($workorder_id);}
            if($invoice_id) {update_invoice_last_active($invoice_id);}

        }
        
    }
    
    // This will present any transport errors
    catch(Swift_TransportException $Transport_exception) {
        
        // this one is faulty when no transport available
        
        //var_dump($Transport_exception);
        
        // Log activity 
        $record = _gettext("Failed to send email to").' '.$recipient_email.' ('.$recipient_name.')';        
        write_record_to_email_error_log($Transport_exception->getMessage());
        write_record_to_activity_log($record, $employee_id, $client_id, $workorder_id, $invoice_id);

        // Output the system message to the browser (if allowed)
        if (!$silent) {
            preg_match('/^(.*)$/m', $Transport_exception->getMessage(), $matches);  // output the first line of the error message only
            $system_message = $record.'<br>'.$matches[0];
            //$smarty->assign('warning_msg', $system_message);
            ajax_output_notifications_onscreen('', $system_message);
        }
        
    }
    
    // Write the Email Transport Record to the log
    write_record_to_email_transport_log($logger->dump());
    
    return;
   
}

##########################################
#  Get email message body                #
##########################################

function get_email_message_body($message_name, $client_details) {
    
    // get the message from the database
    $content = get_company_details($message_name);
    
    // Process placeholders
    if($message_name == 'email_msg_invoice') {        
        $content = replace_placeholder($content, '{client_display_name}', $client_details['display_name']);
        $content = replace_placeholder($content, '{client_first_name}', $client_details['first_name']);
        $content = replace_placeholder($content, '{client_last_name}', $client_details['last_name']);
        $content = replace_placeholder($content, '{client_credit_terms}', $client_details['credit_terms']);
    }
    if($message_name == 'email_msg_workorder') {
        // not currently used
    }
    
    // return the process email
    return $content;
    
}

##########################################
#  Add email signature                   #
##########################################

function add_email_signature($swift_emailer = null) {
    
    $company_details = get_company_details();
        
    // Load the signature from the database
    $email_signature = $company_details['email_signature'];
        
    /* Build Company Logo */
    
    // Build the full logo file path
    $logo_file = parse_url(MEDIA_DIR . $company_details['logo'], PHP_URL_PATH);    

    // If swiftmailer is going to be used to add image via CID
    if($swift_emailer) {         
        $logo_string = '<img src="'.$swift_emailer->embed(Swift_Image::fromPath($logo_file)).'" alt="'.$company_details['display_name'].'" width="100">'; 

    // Load the logo as a standard base64 string image
    } else {        
        $logo_string  = '<img src="data:image/jpeg;base64,'.base64_encode(file_get_contents($logo_file)).'" alt="'.$company_details['display_name'].'" width="100">'; 
    } 
    
    /* */
    
    // Build Company Address (html)
    $company_address = $company_details['address'].'<br>'.$company_details['city'].'<br>'.$company_details['state'].'<br>'.$company_details['zip'];        
    
    // Build Company Website (html)
    $company_website = rtrim($company_details['website'], '/');
    $company_website = preg_replace("(^https?://)", "", $company_website);
    $company_website = '<a href="'.$company_details['website'].'">'.$company_website.'</a>';        
    
    // Swap placeholders
    $email_signature  = replace_placeholder($email_signature, '{company_logo}', $logo_string);
    $email_signature  = replace_placeholder($email_signature, '{company_name}', $company_details['company_name']);
    $email_signature  = replace_placeholder($email_signature, '{company_address}', $company_address);
    $email_signature  = replace_placeholder($email_signature, '{company_telephone}', $company_details['telephone']);
    $email_signature  = replace_placeholder($email_signature, '{company_website}', $company_website);
            
    // Return the processed signature
    return $email_signature ;
    
}

###########################################
#  Replace placeholders with new content  #
###########################################

function replace_placeholder($content, $placeholder, $replacement) {
    
    return preg_replace('/'.$placeholder.'/', $replacement, $content);
    
}

###########################################
#  Change all internal page links to SEF  #
###########################################

function email_links_to_sef(&$body) {
    
    // Replace nonsef links within "" and '' and ><
    $body = preg_replace_callback('|(["\'>])http(.*index\.php.*)+(["\'<])|U',
        function($matches) {
            
            return $matches[1].build_sef_url($matches[2], 'absolute').$matches[3];

        }, $body);
    
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
        force_error_page('file', __FILE__, __FUNCTION__, '', '', _gettext("Could not open the Email Error Log to save the record."));
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
        force_error_page('file', __FILE__, __FUNCTION__, '', '', _gettext("Could not open the Email Transport Log to save the record."));
    }
    
    fwrite($fp, $log_entry);
    fclose($fp);
        
    return;
    
}