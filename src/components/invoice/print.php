<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(CINCLUDES_DIR.'company.php');
require(CINCLUDES_DIR.'client.php');
require(CINCLUDES_DIR.'invoice.php');
require(CINCLUDES_DIR.'payment.php');
require(CINCLUDES_DIR.'user.php');
require(CINCLUDES_DIR.'voucher.php');
require(CINCLUDES_DIR.'workorder.php');

// Check if we have an invoice_id
if(!isset(\CMSApplication::$VAR['invoice_id']) || !\CMSApplication::$VAR['invoice_id']) {
    systemMessagesWrite('danger', _gettext("No Invoice ID supplied."));
    force_page('invoice', 'search');
}

// Check there is a print content and print type set
if(!isset(\CMSApplication::$VAR['print_content'], \CMSApplication::$VAR['print_type']) || !\CMSApplication::$VAR['print_content'] || !\CMSApplication::$VAR['print_type']) {
    systemMessagesWrite('danger', _gettext("Some or all of the Printing Options are not set."));
    force_page('invoice', 'search');
}

// Get Record Details
$invoice_details = get_invoice_details(\CMSApplication::$VAR['invoice_id']);
$client_details = get_client_details($invoice_details['client_id']);

// Only show payment instruction if bank_transfer|cheque|PayPal is enabled, these are the only valid instructions you can put on an invoice
$payment_methods = get_payment_methods('receive', 'enabled');
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
$smarty->assign('company_details',                  get_company_details()                                      );
$smarty->assign('client_details',                   $client_details                                            );
$smarty->assign('workorder_details',                get_workorder_details($invoice_details['workorder_id'])    );
$smarty->assign('invoice_details',                  $invoice_details                                           );

// Prefill Items
$smarty->assign('vat_tax_codes',                    get_vat_tax_codes(false)                                                               );

// Invoice Items
$smarty->assign('labour_items',                     get_invoice_labour_items(\CMSApplication::$VAR['invoice_id'])               );
$smarty->assign('parts_items',                      get_invoice_parts_items(\CMSApplication::$VAR['invoice_id'])                );
$smarty->assign('display_vouchers',                 display_vouchers('voucher_id', 'DESC', false, '25', null, null, null, null, null, null, null, \CMSApplication::$VAR['invoice_id']) );

// Sub Totals
$smarty->assign('labour_items_sub_totals',          get_labour_items_sub_totals(\CMSApplication::$VAR['invoice_id'])                                                          );
$smarty->assign('parts_items_sub_totals',           get_parts_items_sub_totals(\CMSApplication::$VAR['invoice_id'])                                                           );
$smarty->assign('voucher_sub_totals',               get_invoice_vouchers_sub_totals(\CMSApplication::$VAR['invoice_id'])                                                       );

// Payment Details
$smarty->assign('payment_options',                  get_payment_options()                                      );
$smarty->assign('payment_methods',                  $payment_methods                                           );

// Misc
$smarty->assign('display_payment_instructions',     $display_payment_instructions                              );
$smarty->assign('employee_display_name',            get_user_details($invoice_details['employee_id'], 'display_name')  );
$smarty->assign('invoice_statuses',                 get_invoice_statuses()                                     );

// Invoice Print Routine
if(\CMSApplication::$VAR['print_content'] == 'invoice') {
    
    // Build the PDF filename
    $pdf_filename = _gettext("Invoice").'-'.\CMSApplication::$VAR['invoice_id'];
    
    // Print HTML Invoice
    if (\CMSApplication::$VAR['print_type'] == 'print_html') {
        
        // Log activity
        $record = _gettext("Invoice").' '.\CMSApplication::$VAR['invoice_id'].' '._gettext("has been printed as html.");
        write_record_to_activity_log($record, $invoice_details['employee_id'], $invoice_details['client_id'], $invoice_details['workorder_id'], $invoice_details['invoice_id']);
        
        // Assign the correct version of this page
        $smarty->assign('print_content', \CMSApplication::$VAR['print_content']);
        
    }
    
    // Print PDF Invoice
    if (\CMSApplication::$VAR['print_type'] == 'print_pdf') {
        
        // Get Print Invoice as HTML into a variable
        $pdf_template = $smarty->fetch('invoice/printing/print_invoice.tpl');
        
        // Log activity
        $record = _gettext("Invoice").' '.\CMSApplication::$VAR['invoice_id'].' '._gettext("has been printed as a PDF.");
        write_record_to_activity_log($record, $invoice_details['employee_id'], $invoice_details['client_id'], $invoice_details['workorder_id'], $invoice_details['invoice_id']);
        
        // Output PDF in brower
        mpdf_output_in_browser($pdf_filename, $pdf_template);
        
        // End all other processing
        die();
        
    }        
        
    // Email PDF Invoice
    if(\CMSApplication::$VAR['print_type'] == 'email_pdf') {  
                
        // Get Print Invoice as HTML into a variable
        $pdf_template = $smarty->fetch('invoice/printing/print_invoice.tpl');
        
        // Return the PDF in a variable
        $pdf_as_string = mpdf_output_as_variable($pdf_filename, $pdf_template);
        
        // Build the PDF        
        $attachment['data'] = $pdf_as_string;
        $attachment['filename'] = $pdf_filename;
        $attachment['filetype'] = 'application/pdf';
        
        // Build the message body        
        $body = get_email_message_body('email_msg_invoice', $client_details);
        
        // Log activity
        $record = _gettext("Invoice").' '.\CMSApplication::$VAR['invoice_id'].' '._gettext("has been emailed as a PDF.");
        write_record_to_activity_log($record, $invoice_details['employee_id'], $invoice_details['client_id'], $invoice_details['workorder_id'], $invoice_details['invoice_id']);
        
        // Email the PDF        
        send_email($client_details['email'], _gettext("Invoice").' '.\CMSApplication::$VAR['invoice_id'], $body, $client_details['display_name'], $attachment, $invoice_details['employee_id'], $invoice_details['client_id'], $invoice_details['workorder_id'], \CMSApplication::$VAR['invoice_id']);
                
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
        write_record_to_activity_log($record, $invoice_details['employee_id'], $invoice_details['client_id'], $invoice_details['workorder_id'], $invoice_details['invoice_id']);
        
        // Assign the correct version of this page
        $smarty->assign('print_content', \CMSApplication::$VAR['print_content']);
        
    }
    
}