<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Check if we have a workorder_id
if(!isset(\CMSApplication::$VAR['workorder_id']) || !\CMSApplication::$VAR['workorder_id']) {
    systemMessagesWrite('danger', _gettext("No Workorder ID supplied."));
    force_page('workorder', 'search');
}

// Check there is a print content and print type set
if(!isset(\CMSApplication::$VAR['print_content'], \CMSApplication::$VAR['print_type']) || !\CMSApplication::$VAR['print_content'] || !\CMSApplication::$VAR['print_type']) {
    systemMessagesWrite('danger', _gettext("Some or all of the Printing Options are not set."));
    force_page('workorder', 'search');
}

// Get Record Details
$workorder_details  = get_workorder_details(\CMSApplication::$VAR['workorder_id']);
$client_details   = get_client_details($workorder_details['client_id']);

/// Assign Variables
$smarty->assign('company_details',      get_company_details()                                        );
$smarty->assign('employee_details',     get_user_details($workorder_details['employee_id'])          );
$smarty->assign('client_details',       $client_details                                              );
$smarty->assign('workorder_details',    $workorder_details                                           );
$smarty->assign('client_types',         get_client_types()                                           );
$smarty->assign('workorder_statuses',   get_workorder_statuses()                                     );
$smarty->assign('workorder_notes',      display_workorder_notes(\CMSApplication::$VAR['workorder_id'])                );
$smarty->assign('workorder_schedules',  display_schedules('schedule_id', 'DESC', false, null, null, null, null, null, null, null, \CMSApplication::$VAR['workorder_id'])  );

// Technician Workorder Slip Print Routine
if(\CMSApplication::$VAR['print_content'] == 'technician_workorder_slip') {    
    
    // Build the PDF filename
    $pdf_filename = _gettext("Technician Workorder Slip").' '.\CMSApplication::$VAR['workorder_id'];
    
    // Print HTML
    if (\CMSApplication::$VAR['print_type'] == 'print_html') {
        
        // Log activity
        $record = _gettext("Technician Workorder Slip").' '.\CMSApplication::$VAR['workorder_id'].' '._gettext("has been printed as html.");
        write_record_to_activity_log($record, $workorder_details['employee_id'], $workorder_details['client_id'], $workorder_details['workorder_id'], $workorder_details['invoice_id']);

        // Assign the correct version of this page
        $smarty->assign('print_content', \CMSApplication::$VAR['print_content']);
    
    // Print PDF
    } elseif (\CMSApplication::$VAR['print_type'] == 'print_pdf') {        
        
        // Get Print Invoice as HTML into a variable
        $pdf_template = $smarty->fetch('workorder/printing/print_technician_workorder_slip.tpl');
        
        // Log activity
        $record = _gettext("Technician Workorder Slip").' '.\CMSApplication::$VAR['workorder_id'].' '._gettext("has been printed as a PDF.");
        write_record_to_activity_log($record, $workorder_details['employee_id'], $workorder_details['client_id'], $workorder_details['workorder_id'], $workorder_details['invoice_id']);

        // Output PDF in brower
        mpdf_output_in_browser($pdf_filename, $pdf_template);
        
    // Email PDF
    } elseif (\CMSApplication::$VAR['print_type'] == 'email_pdf') {
        
        // Get Print Invoice as HTML into a variable
        $pdf_template = $smarty->fetch('workorder/printing/print_technician_workorder_slip.tpl');
        
        // Return the PDF in a variable
        $pdf_as_string = mpdf_output_as_variable($pdf_filename, $pdf_template);
        
        // Build the PDF        
        $attachment['data'] = $pdf_as_string;
        $attachment['filename'] = $pdf_filename;
        $attachment['filetype'] = 'application/pdf';
        
        // Build the message body        
        $body = get_email_message_body('email_msg_workorder', $client_details);
        
        // Log activity
        $record = _gettext("Technician Workorder Slip").' '.\CMSApplication::$VAR['workorder_id'].' '._gettext("has been emailed as a PDF.");
        write_record_to_activity_log($record, $workorder_details['employee_id'], $workorder_details['client_id'], $workorder_details['workorder_id'], $workorder_details['invoice_id']);

        // Email the PDF
        send_email($client_details['email'], _gettext("Work Order").' '.\CMSApplication::$VAR['workorder_id'], $body, $client_details['display_name'], $attachment);
        
        // End all other processing
        die();
        
    }
}

// Client Workorder Slip Print Routine
if(\CMSApplication::$VAR['print_content'] == 'client_workorder_slip') {
    
    // Build the PDF filename
    $pdf_filename = _gettext("Client Workorder Slip").' '.\CMSApplication::$VAR['workorder_id'];    
    
    // Print HTML
    if (\CMSApplication::$VAR['print_type'] == 'print_html') {
        
        // Log activity
        $record = _gettext("Client Workorder Slip").' '.\CMSApplication::$VAR['workorder_id'].' '._gettext("has been printed as html.");
        write_record_to_activity_log($record, $workorder_details['employee_id'], $workorder_details['client_id'], $workorder_details['workorder_id'], $workorder_details['invoice_id']);
        
        // Assign the correct version of this page
        $smarty->assign('print_content', \CMSApplication::$VAR['print_content']);
    
    // Print PDF
    } elseif (\CMSApplication::$VAR['print_type'] == 'print_pdf') {        
        
        // Get Print Invoice as HTML into a variable
        $pdf_template = $smarty->fetch('workorder/printing/print_client_workorder_slip.tpl');
        
        // Log activity
        $record = _gettext("Client Workorder Slip").' '.\CMSApplication::$VAR['workorder_id'].' '._gettext("has been printed as a PDF.");
        write_record_to_activity_log($record, $workorder_details['employee_id'], $workorder_details['client_id'], $workorder_details['workorder_id'], $workorder_details['invoice_id']);
        
        // Output PDF in brower
        mpdf_output_in_browser($pdf_filename, $pdf_template);       
        
    // Email PDF
    } elseif (\CMSApplication::$VAR['print_type'] == 'email_pdf') { 
        
        // Get Print Invoice as HTML into a variable
        $pdf_template = $smarty->fetch('workorder/printing/print_client_workorder_slip.tpl');
        
        // Return the PDF in a variable
        $pdf_as_string = mpdf_output_as_variable($pdf_filename, $pdf_template);
        
        // Build the PDF        
        $attachment['data'] = $pdf_as_string;
        $attachment['filename'] = $pdf_filename;
        $attachment['filetype'] = 'application/pdf';
        
        // Build the message body        
        $body = get_email_message_body('email_msg_workorder', $client_details);
        
        // Log activity
        $record = _gettext("Client Workorder Slip").' '.\CMSApplication::$VAR['workorder_id'].' '._gettext("has been emailed as a PDF.");
        write_record_to_activity_log($record, $workorder_details['employee_id'], $workorder_details['client_id'], $workorder_details['workorder_id'], $workorder_details['invoice_id']);
        
        // Email the PDF
        send_email($client_details['email'], _gettext("Work Order").' '.\CMSApplication::$VAR['workorder_id'], $body, $client_details['display_name'], $attachment);
        
        // End all other processing
        die();
        
    }
}

// Technician Job Sheet Print Routine
if(\CMSApplication::$VAR['print_content'] == 'technician_job_sheet') {
    
    // Build the PDF filename
    $pdf_filename = _gettext("Technician Job Sheet").' '.\CMSApplication::$VAR['workorder_id'];        
    
    // Print HTML
    if (\CMSApplication::$VAR['print_type'] == 'print_html') {
        
        // Log activity
        $record = _gettext("Technician Job Sheet").' '.\CMSApplication::$VAR['workorder_id'].' '._gettext("has been printed as html.");
        write_record_to_activity_log($record, $workorder_details['employee_id'], $workorder_details['client_id'], $workorder_details['workorder_id'], $workorder_details['invoice_id']);
                
        // Assign the correct version of this page
        $smarty->assign('print_content', \CMSApplication::$VAR['print_content']);
        
    // Print PDF
    } elseif (\CMSApplication::$VAR['print_type'] == 'print_pdf') {
        
        // Get Print Invoice as HTML into a variable
        $pdf_template = $smarty->fetch('workorder/printing/print_technician_job_sheet.tpl');
        
        // Log activity
        $record = _gettext("Technician Job Sheet").' '.\CMSApplication::$VAR['workorder_id'].' '._gettext("has been printed as a PDF.");
        write_record_to_activity_log($record, $workorder_details['employee_id'], $workorder_details['client_id'], $workorder_details['workorder_id'], $workorder_details['invoice_id']);
            
        // Output PDF in brower
        mpdf_output_in_browser($pdf_filename, $pdf_template);
        
    // Print HTML
    } elseif (\CMSApplication::$VAR['print_type'] == 'email_pdf') {  
        
        // Get Print Invoice as HTML into a variable
        $pdf_template = $smarty->fetch('workorder/printing/print_technician_job_sheet.tpl');
        
        // Return the PDF in a variable
        $pdf_as_string = mpdf_output_as_variable($pdf_filename, $pdf_template);
        
        // Build the PDF        
        $attachment['data'] = $pdf_as_string;
        $attachment['filename'] = $pdf_filename;
        $attachment['filetype'] = 'application/pdf';
        
        // Build the message body        
        $body = get_email_message_body('email_msg_workorder', $client_details);
        
        // Log activity
        $record = _gettext("Technician Job Sheet").' '.\CMSApplication::$VAR['workorder_id'].' '._gettext("has been emailed as a PDF.");
        write_record_to_activity_log($record, $workorder_details['employee_id'], $workorder_details['client_id'], $workorder_details['workorder_id'], $workorder_details['invoice_id']);
            
        // Email the PDF
        send_email($client_details['email'], _gettext("Work Order").' '.\CMSApplication::$VAR['workorder_id'], $body, $client_details['display_name'], $attachment);
        
        // End all other processing
        die();
        
    }
}