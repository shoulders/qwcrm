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
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Workorder ID supplied."));
    $this->app->system->page->force_page('workorder', 'search');
}

// Check there is a print content and print type set
if(!isset(\CMSApplication::$VAR['print_content'], \CMSApplication::$VAR['print_type']) || !\CMSApplication::$VAR['print_content'] || !\CMSApplication::$VAR['print_type']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("Some or all of the Printing Options are not set."));
    $this->app->system->page->force_page('workorder', 'search');
}

// Get Record Details
$workorder_details  = $this->app->components->workorder->get_workorder_details(\CMSApplication::$VAR['workorder_id']);
$client_details   = $this->app->components->client->get_client_details($workorder_details['client_id']);

/// Assign Variables
$this->app->smarty->assign('company_details',      $this->app->components->company->get_company_details()                                        );
$this->app->smarty->assign('employee_details',     $this->app->components->user->get_user_details($workorder_details['employee_id'])          );
$this->app->smarty->assign('client_details',       $client_details                                              );
$this->app->smarty->assign('workorder_details',    $workorder_details                                           );
$this->app->smarty->assign('client_types',         $this->app->components->client->get_client_types()                                           );
$this->app->smarty->assign('workorder_statuses',   $this->app->components->workorder->get_workorder_statuses()                                     );
$this->app->smarty->assign('workorder_notes',      $this->app->components->workorder->display_workorder_notes(\CMSApplication::$VAR['workorder_id'])                );
$this->app->smarty->assign('workorder_schedules',  $this->app->components->schedule->display_schedules('schedule_id', 'DESC', false, null, null, null, null, null, null, null, \CMSApplication::$VAR['workorder_id'])  );

// Technician Workorder Slip Print Routine
if(\CMSApplication::$VAR['print_content'] == 'technician_workorder_slip')
{    
    // Build the PDF filename
    $pdf_filename = _gettext("Technician Workorder Slip").' '.\CMSApplication::$VAR['workorder_id'].'.pdf';
    
    // Print HTML
    if (\CMSApplication::$VAR['print_type'] == 'print_html')
    {        
        // Log activity
        $record = _gettext("Technician Workorder Slip").' '.\CMSApplication::$VAR['workorder_id'].' '._gettext("has been printed as html.");
        $this->app->system->general->write_record_to_activity_log($record, $workorder_details['employee_id'], $workorder_details['client_id'], $workorder_details['workorder_id'], $workorder_details['invoice_id']);

        // Assign the correct version of this page
        $this->app->smarty->assign('print_content', \CMSApplication::$VAR['print_content']);    
    }
    // Print PDF
    if (\CMSApplication::$VAR['print_type'] == 'print_pdf')
    {
        // Get Print Invoice as HTML into a variable
        $pdf_template = $this->app->smarty->fetch('workorder/printing/print_technician_workorder_slip.tpl');

        // Log activity
        $record = _gettext("Technician Workorder Slip").' '.\CMSApplication::$VAR['workorder_id'].' '._gettext("has been printed as a PDF.");
        $this->app->system->general->write_record_to_activity_log($record, $workorder_details['employee_id'], $workorder_details['client_id'], $workorder_details['workorder_id'], $workorder_details['invoice_id']);

        // Output PDF in brower
        $this->app->system->pdf->mpdf_output_in_browser($pdf_filename, $pdf_template);
    }
    
    // Email PDF
    if (\CMSApplication::$VAR['print_type'] == 'email_pdf')
    {        
        // Get Print Invoice as HTML into a variable
        $pdf_template = $this->app->smarty->fetch('workorder/printing/print_technician_workorder_slip.tpl');
        
        // Get the PDF in a variable
        $pdf_as_string = $this->app->system->pdf->mpdf_output_as_variable($pdf_template);
        
        // Build and Send email
        if($pdf_as_string)
        {        
            // Build the PDF Attachment
            $attachments = array();
            $attachment['data'] = $pdf_as_string;
            $attachment['filename'] = $pdf_filename;
            $attachment['contentType'] = 'application/pdf';
            $attachments[] = $attachment;
        
            // Build the message body        
            $body = $this->app->system->email->get_email_message_body('email_msg_workorder', $client_details);

            // Log activity
            $record = _gettext("Technician Workorder Slip").' '.\CMSApplication::$VAR['workorder_id'].' '._gettext("has been emailed as a PDF.");
            $this->app->system->general->write_record_to_activity_log($record, $workorder_details['employee_id'], $workorder_details['client_id'], $workorder_details['workorder_id'], $workorder_details['invoice_id']);

            // Email the PDF
            $this->app->system->email->send_email($client_details['email'], _gettext("Work Order").' '.\CMSApplication::$VAR['workorder_id'], $body, $client_details['display_name'], $attachments);

            // End all other processing
            die();
        }
        
    }
    
}

// Client Workorder Slip Print Routine
if(\CMSApplication::$VAR['print_content'] == 'client_workorder_slip')
{
    
    // Build the PDF filename
    $pdf_filename = _gettext("Client Workorder Slip").' '.\CMSApplication::$VAR['workorder_id'].'.pdf';  
    
    // Print HTML
    if (\CMSApplication::$VAR['print_type'] == 'print_html')
    {
        // Log activity
        $record = _gettext("Client Workorder Slip").' '.\CMSApplication::$VAR['workorder_id'].' '._gettext("has been printed as html.");
        $this->app->system->general->write_record_to_activity_log($record, $workorder_details['employee_id'], $workorder_details['client_id'], $workorder_details['workorder_id'], $workorder_details['invoice_id']);
        
        // Assign the correct version of this page
        $this->app->smarty->assign('print_content', \CMSApplication::$VAR['print_content']);
    }
        
        
    // Print PDF
    if (\CMSApplication::$VAR['print_type'] == 'print_pdf')
    {
        // Get Print Invoice as HTML into a variable
        $pdf_template = $this->app->smarty->fetch('workorder/printing/print_client_workorder_slip.tpl');

        // Log activity
        $record = _gettext("Client Workorder Slip").' '.\CMSApplication::$VAR['workorder_id'].' '._gettext("has been printed as a PDF.");
        $this->app->system->general->write_record_to_activity_log($record, $workorder_details['employee_id'], $workorder_details['client_id'], $workorder_details['workorder_id'], $workorder_details['invoice_id']);

        // Output PDF in brower
        $this->app->system->pdf->mpdf_output_in_browser($pdf_filename, $pdf_template);       
    }        
        
    // Email PDF
    if (\CMSApplication::$VAR['print_type'] == 'email_pdf')
    { 
        // Get Print Invoice as HTML into a variable
        $pdf_template = $this->app->smarty->fetch('workorder/printing/print_client_workorder_slip.tpl');

        // Get the PDF in a variable
        $pdf_as_string = $this->app->system->pdf->mpdf_output_as_variable($pdf_template);

        // Build and Send email
        if($pdf_as_string)
        {        
            // Build the PDF Attachment
            $attachments = array();
            $attachment['data'] = $pdf_as_string;
            $attachment['filename'] = $pdf_filename;
            $attachment['contentType'] = 'application/pdf';
            $attachments[] = $attachment;

            // Build the message body        
            $body = $this->app->system->email->get_email_message_body('email_msg_workorder', $client_details);

            // Log activity
            $record = _gettext("Client Workorder Slip").' '.\CMSApplication::$VAR['workorder_id'].' '._gettext("has been emailed as a PDF.");
            $this->app->system->general->write_record_to_activity_log($record, $workorder_details['employee_id'], $workorder_details['client_id'], $workorder_details['workorder_id'], $workorder_details['invoice_id']);

            // Email the PDF
            $this->app->system->email->send_email($client_details['email'], _gettext("Work Order").' '.\CMSApplication::$VAR['workorder_id'], $body, $client_details['display_name'], $attachment);

            // End all other processing
            die();
        }
        
    }
}

// Technician Job Sheet Print Routine
if(\CMSApplication::$VAR['print_content'] == 'technician_job_sheet')
{    
    // Build the PDF filename
    $pdf_filename = _gettext("Technician Job Sheet").' '.\CMSApplication::$VAR['workorder_id'].'.pdf';    
    
    // Print HTML
    if (\CMSApplication::$VAR['print_type'] == 'print_html')
    {
        // Log activity
        $record = _gettext("Technician Job Sheet").' '.\CMSApplication::$VAR['workorder_id'].' '._gettext("has been printed as html.");
        $this->app->system->general->write_record_to_activity_log($record, $workorder_details['employee_id'], $workorder_details['client_id'], $workorder_details['workorder_id'], $workorder_details['invoice_id']);
                
        // Assign the correct version of this page
        $this->app->smarty->assign('print_content', \CMSApplication::$VAR['print_content']);
    }
                
    // Print PDF
    if (\CMSApplication::$VAR['print_type'] == 'print_pdf')
    {
        // Get Print Invoice as HTML into a variable
        $pdf_template = $this->app->smarty->fetch('workorder/printing/print_technician_job_sheet.tpl');

        // Log activity
        $record = _gettext("Technician Job Sheet").' '.\CMSApplication::$VAR['workorder_id'].' '._gettext("has been printed as a PDF.");
        $this->app->system->general->write_record_to_activity_log($record, $workorder_details['employee_id'], $workorder_details['client_id'], $workorder_details['workorder_id'], $workorder_details['invoice_id']);

        // Output PDF in brower
        $this->app->system->pdf->mpdf_output_in_browser($pdf_filename, $pdf_template);
    }
        
    // Email PDF
    if (\CMSApplication::$VAR['print_type'] == 'email_pdf')
    {        
        // Get Print Invoice as HTML into a variable
        $pdf_template = $this->app->smarty->fetch('workorder/printing/print_technician_job_sheet.tpl');

        // Get the PDF in a variable
        $pdf_as_string = $this->app->system->pdf->mpdf_output_as_variable($pdf_template);

        
        // Build and Send email
        if($pdf_as_string)
        {
            // Build the PDF Attachment
            $attachments = array();
            $attachment['data'] = $pdf_as_string;
            $attachment['filename'] = $pdf_filename;
            $attachment['filetype'] = 'application/pdf';
            $attachments[] = $attachment;

            // Build the message body        
            $body = $this->app->system->email->get_email_message_body('email_msg_workorder', $client_details);

            // Log activity
            $record = _gettext("Technician Job Sheet").' '.\CMSApplication::$VAR['workorder_id'].' '._gettext("has been emailed as a PDF.");
            $this->app->system->general->write_record_to_activity_log($record, $workorder_details['employee_id'], $workorder_details['client_id'], $workorder_details['workorder_id'], $workorder_details['invoice_id']);

            // Email the PDF
            $this->app->system->email->send_email($client_details['email'], _gettext("Work Order").' '.\CMSApplication::$VAR['workorder_id'], $body, $client_details['display_name'], $attachment);

            // End all other processing
            die();
        }

    }
}