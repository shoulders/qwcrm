<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Check if we have an invoice_id
if(!isset(\CMSApplication::$VAR['invoice_id']) || !\CMSApplication::$VAR['invoice_id']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Invoice ID supplied."));
    $this->app->system->page->force_page('invoice', 'search');
}

// Check there is a print content and print type set
if(!isset(\CMSApplication::$VAR['print_content'], \CMSApplication::$VAR['print_type']) || !\CMSApplication::$VAR['print_content'] || !\CMSApplication::$VAR['print_type']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("Some or all of the Printing Options are not set."));
    $this->app->system->page->force_page('invoice', 'search');
}

// Get Record Details
$invoice_details = $this->app->components->invoice->get_invoice_details(\CMSApplication::$VAR['invoice_id']);
$client_details = $this->app->components->client->get_client_details($invoice_details['client_id']);

// Only show payment instruction if bank_transfer|cheque|PayPal is enabled, these are the only valid instructions you can put on an invoice
$payment_methods = $this->app->components->payment->get_payment_methods('receive', 'enabled');
$display_payment_instructions = false;
foreach ($payment_methods as $key => $value) {
    if(
        ($value['method_key'] == 'bank_transfer' && $value['enabled']) ||
        ($value['method_key'] == 'cheque' && $value['enabled']) ||
        ($value['method_key'] == 'paypal' && $value['enabled'])
    ) {
        $display_payment_instructions = true;
    }
}

// Details
$this->app->smarty->assign('company_details',                  $this->app->components->company->get_company_details()                                      );
$this->app->smarty->assign('client_details',                   $client_details                                            );
$this->app->smarty->assign('workorder_details',                $this->app->components->workorder->get_workorder_details($invoice_details['workorder_id'])    );
$this->app->smarty->assign('invoice_details',                  $invoice_details                                           );

// Prefill Items
$this->app->smarty->assign('vat_tax_codes',                    $this->app->components->company->get_vat_tax_codes(false)                                                               );

// Invoice Items
$this->app->smarty->assign('labour_items',                     $this->app->components->invoice->get_invoice_labour_items(\CMSApplication::$VAR['invoice_id'])               );
$this->app->smarty->assign('parts_items',                      $this->app->components->invoice->get_invoice_parts_items(\CMSApplication::$VAR['invoice_id'])                );
$this->app->smarty->assign('display_vouchers',                 $this->app->components->voucher->display_vouchers('voucher_id', 'DESC', false, '25', null, null, null, null, null, null, null, \CMSApplication::$VAR['invoice_id']) );

// Sub Totals
$this->app->smarty->assign('labour_items_sub_totals',          $this->app->components->invoice->get_labour_items_sub_totals(\CMSApplication::$VAR['invoice_id'])                                                          );
$this->app->smarty->assign('parts_items_sub_totals',           $this->app->components->invoice->get_parts_items_sub_totals(\CMSApplication::$VAR['invoice_id'])                                                           );
$this->app->smarty->assign('voucher_sub_totals',               $this->app->components->voucher->get_invoice_vouchers_sub_totals(\CMSApplication::$VAR['invoice_id'])                                                       );

// Payment Details
$this->app->smarty->assign('payment_options',                  $this->app->components->payment->get_payment_options()                                      );
$this->app->smarty->assign('payment_methods',                  $payment_methods                                           );

// Misc
$this->app->smarty->assign('display_payment_instructions',     $display_payment_instructions                              );
$this->app->smarty->assign('employee_display_name',            $this->app->components->user->get_user_details($invoice_details['employee_id'], 'display_name')  );
$this->app->smarty->assign('invoice_statuses',                 $this->app->components->invoice->get_invoice_statuses()                                     );

// Invoice Print Routine
if(\CMSApplication::$VAR['print_content'] == 'invoice') {
    
    // Build the PDF filename
    $pdf_filename = _gettext("Invoice").'-'.\CMSApplication::$VAR['invoice_id'];
    
    // Print HTML Invoice
    if (\CMSApplication::$VAR['print_type'] == 'print_html') {
        
        // Log activity
        $record = _gettext("Invoice").' '.\CMSApplication::$VAR['invoice_id'].' '._gettext("has been printed as html.");
        $this->app->system->general->write_record_to_activity_log($record, $invoice_details['employee_id'], $invoice_details['client_id'], $invoice_details['workorder_id'], $invoice_details['invoice_id']);
        
        // Assign the correct version of this page
        $this->app->smarty->assign('print_content', \CMSApplication::$VAR['print_content']);
        
    }
    
    // Print PDF Invoice
    if (\CMSApplication::$VAR['print_type'] == 'print_pdf') {
        
        // Get Print Invoice as HTML into a variable
        $pdf_template = $this->app->smarty->fetch('invoice/printing/print_invoice.tpl');
        
        // Log activity
        $record = _gettext("Invoice").' '.\CMSApplication::$VAR['invoice_id'].' '._gettext("has been printed as a PDF.");
        $this->app->system->general->write_record_to_activity_log($record, $invoice_details['employee_id'], $invoice_details['client_id'], $invoice_details['workorder_id'], $invoice_details['invoice_id']);
        
        // Output PDF in brower
        $this->app->system->pdf->mpdf_output_in_browser($pdf_filename, $pdf_template);
        
        // End all other processing
        die();
        
    }        
        
    // Email PDF Invoice
    if(\CMSApplication::$VAR['print_type'] == 'email_pdf') {  
                
        // Get Print Invoice as HTML into a variable
        $pdf_template = $this->app->smarty->fetch('invoice/printing/print_invoice.tpl');
        
        // Return the PDF in a variable
        $pdf_as_string = $this->app->system->pdf->mpdf_output_as_variable($pdf_filename, $pdf_template);
        
        // Build the PDF Attachment
        $attachments = array();
        $attachment['data'] = $pdf_as_string;
        $attachment['filename'] = $pdf_filename;
        $attachment['contentType'] = 'application/pdf';
        $attachments[] = $attachment;
        
        // Build the message body        
        $body = $this->app->system->email->get_email_message_body('email_msg_invoice', $client_details);
        
        // Log activity
        $record = _gettext("Invoice").' '.\CMSApplication::$VAR['invoice_id'].' '._gettext("has been emailed as a PDF.");
        $this->app->system->general->write_record_to_activity_log($record, $invoice_details['employee_id'], $invoice_details['client_id'], $invoice_details['workorder_id'], $invoice_details['invoice_id']);
        
        // Email the PDF        
        $this->app->system->email->send_email($client_details['email'], _gettext("Invoice").' '.\CMSApplication::$VAR['invoice_id'], $body, $client_details['display_name'], $attachments, $invoice_details['employee_id'], $invoice_details['client_id'], $invoice_details['workorder_id'], \CMSApplication::$VAR['invoice_id']);
                
        // End all other processing
        die();
        
    }
    
}

// Client Envelope Print Routine
if(\CMSApplication::$VAR['print_content'] == 'client_envelope') {
    
    // Print HTML Client Envelope
    if (\CMSApplication::$VAR['print_type'] == 'print_html') {
        
        // Log activity
        $record = _gettext("Address Envelope").' '._gettext("for").' '.$client_details['display_name'].' '._gettext("has been printed as html.");
        $this->app->system->general->write_record_to_activity_log($record, $invoice_details['employee_id'], $invoice_details['client_id'], $invoice_details['workorder_id'], $invoice_details['invoice_id']);
        
        // Assign the correct version of this page
        $this->app->smarty->assign('print_content', \CMSApplication::$VAR['print_content']);
        
    }
    
}