<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Generate the barcode (as html)
$bc_generator = new Picqer\Barcode\BarcodeGeneratorHTML();
$barcode = $bc_generator->getBarcode($this->app->components->voucher->get_voucher_details(\CMSApplication::$VAR['voucher_id'], 'voucher_code'), $bc_generator::TYPE_CODE_128);

// Check if we have an voucher_id
if(!isset(\CMSApplication::$VAR['voucher_id']) || !\CMSApplication::$VAR['voucher_id']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Voucher ID supplied."));
    $this->app->system->page->force_page('voucher', 'search');
}

// Check there is a print content and print type set
if(!isset(\CMSApplication::$VAR['print_content'], \CMSApplication::$VAR['print_type']) || !\CMSApplication::$VAR['print_content'] || !\CMSApplication::$VAR['print_type']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("Some or all of the Printing Options are not set."));
    $this->app->system->page->force_page('voucher', 'search');
}

// Get Voucher details
$voucher_details = $this->app->components->voucher->get_voucher_details(\CMSApplication::$VAR['voucher_id']);
$client_details = $this->app->components->client->get_client_details($voucher_details['client_id']);

// Assign Variables
$this->app->smarty->assign('company_details',  $this->app->components->company->get_company_details()       );
$this->app->smarty->assign('client_details',   $client_details             );
$this->app->smarty->assign('voucher_details', $voucher_details           );
$this->app->smarty->assign('barcode',          $barcode                    );

// Voucher Print Routine
if(\CMSApplication::$VAR['print_content'] == 'voucher') {    
    
    // Build the PDF filename
    $pdf_filename = _gettext("Voucher").' '.\CMSApplication::$VAR['voucher_id'];
    
    // Print HTML
    if (\CMSApplication::$VAR['print_type'] == 'print_html') {
        
        // Log activity
        $record = _gettext("Voucher").' '.\CMSApplication::$VAR['voucher_id'].' '._gettext("has been printed as html.");
        $this->app->system->general->write_record_to_activity_log($record, $voucher_details['employee_id'], $voucher_details['client_id'], $voucher_details['workorder_id'], $voucher_details['invoice_id']);
        
        // Assign the correct version of this page
        $this->app->smarty->assign('print_content', \CMSApplication::$VAR['print_content']);
    
    // Print PDF
    } elseif (\CMSApplication::$VAR['print_type'] == 'print_pdf') {        
        
        // Get Print Invoice as HTML into a variable
        $pdf_template = $this->app->smarty->fetch('voucher/printing/print_voucher.tpl');
        
        // Log activity
        $record = _gettext("Voucher").' '.\CMSApplication::$VAR['voucher_id'].' '._gettext("has been printed as a PDF.");
        $this->app->system->general->write_record_to_activity_log($record, $voucher_details['employee_id'], $voucher_details['client_id'], $voucher_details['workorder_id'], $voucher_details['invoice_id']);
        
        // Output PDF in brower
        $this->app->system->pdf->mpdf_output_in_browser($pdf_filename, $pdf_template);
        
    // Email PDF
    } elseif (\CMSApplication::$VAR['print_type'] == 'email_pdf') {
        
        // Get Print Invoice as HTML into a variable
        $pdf_template = $this->app->smarty->fetch('voucher/printing/print_voucher.tpl');
        
        // return the PDF in a variable
        $pdf_as_string = $this->app->system->pdf->mpdf_output_as_variable($pdf_filename, $pdf_template);
        
        // Build the PDF Attachment
        $attachments = array();
        $attachment['data'] = $pdf_as_string;
        $attachment['filename'] = $pdf_filename;
        $attachment['contentType'] = 'application/pdf';
        $attachments[] = $attachment;
        
        // Build the message body        
        $body = $this->app->system->email->get_email_message_body('email_msg_voucher', $client_details);  // This message does not currently exist
        
        // Log activity
        $record = _gettext("Voucher").' '.\CMSApplication::$VAR['voucher_id'].' '._gettext("has been emailed as a PDF.");
        $this->app->system->general->write_record_to_activity_log($record, $voucher_details['employee_id'], $voucher_details['client_id'], $voucher_details['workorder_id'], $voucher_details['invoice_id']);
        
        // Email the PDF
        $this->app->system->email->send_email($client_details['email'], _gettext("Voucher").' '.\CMSApplication::$VAR['voucher_id'], $body, $client_details['display_name'], $attachments);
        
        // End all other processing
        die();
        
    }
}