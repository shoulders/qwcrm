<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'company.php');
require(INCLUDES_DIR.'client.php');
require(INCLUDES_DIR.'invoice.php');
require(INCLUDES_DIR.'payment.php');
require(INCLUDES_DIR.'user.php');
require(INCLUDES_DIR.'voucher.php');
require(INCLUDES_DIR.'workorder.php');

// Check if we have an invoice_id
if(!isset($VAR['invoice_id']) || !$VAR['invoice_id']) {
    force_page('invoice', 'search', 'warning_msg='._gettext("No Invoice ID supplied."));
}

// Check there is a print content and print type set
if(!isset($VAR['print_content'], $VAR['print_type']) || !$VAR['print_content'] || !$VAR['print_type']) {
    force_page('invoice', 'search', 'warning_msg='._gettext("Some or all of the Printing Options are not set."));
}

// Get Record Details
$invoice_details = get_invoice_details($VAR['invoice_id']);
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

// Invoice Items
$smarty->assign('labour_items',                     get_invoice_labour_items($VAR['invoice_id'])               );
$smarty->assign('parts_items',                      get_invoice_parts_items($VAR['invoice_id'])                );
$smarty->assign('display_vouchers',                display_vouchers('voucher_id', 'DESC', false, '25', null, null, null, null, null, null, null, $VAR['invoice_id']) );

// Sub Totals
$smarty->assign('labour_items_sub_totals',     get_labour_items_sub_totals($VAR['invoice_id'])                                                          );
$smarty->assign('parts_items_sub_totals',      get_parts_items_sub_totals($VAR['invoice_id'])                                                           );
$smarty->assign('vouchers_items_sub_total',    get_vouchers_items_sub_total($VAR['invoice_id'])                                                       );

// Payment Details
$smarty->assign('payment_options',                  get_payment_options()                                      );
$smarty->assign('payment_methods',                  $payment_methods                                           );

// Misc
$smarty->assign('display_payment_instructions',     $display_payment_instructions                              );
$smarty->assign('employee_display_name',            get_user_details($invoice_details['employee_id'], 'display_name')  );
$smarty->assign('invoice_statuses',                 get_invoice_statuses()                                     );

// Invoice Print Routine
if($VAR['print_content'] == 'invoice') {
    
    // Build the PDF filename
    $pdf_filename = _gettext("Invoice").'-'.$VAR['invoice_id'];
    
    // Print HTML Invoice
    if ($VAR['print_type'] == 'print_html') {
        
        // Log activity
        $record = _gettext("Invoice").' '.$VAR['invoice_id'].' '._gettext("has been printed as html.");
        write_record_to_activity_log($record, $invoice_details['employee_id'], $invoice_details['client_id'], $invoice_details['workorder_id'], $invoice_details['invoice_id']);
        
        // Build the page
        $BuildPage = $smarty->fetch('invoice/printing/print_invoice.tpl'); 
    }
    
    // Print PDF Invoice
    if ($VAR['print_type'] == 'print_pdf') {
        
        // Get Print Invoice as HTML into a variable
        $pdf_template = $smarty->fetch('invoice/printing/print_invoice.tpl');
        
        // Log activity
        $record = _gettext("Invoice").' '.$VAR['invoice_id'].' '._gettext("has been printed as a PDF.");
        write_record_to_activity_log($record, $invoice_details['employee_id'], $invoice_details['client_id'], $invoice_details['workorder_id'], $invoice_details['invoice_id']);
        
        // Output PDF in brower
        mpdf_output_in_browser($pdf_filename, $pdf_template);
        
    }        
        
    // Email PDF Invoice
    if($VAR['print_type'] == 'email_pdf') {  
                
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
        $record = _gettext("Invoice").' '.$VAR['invoice_id'].' '._gettext("has been emailed as a PDF.");
        write_record_to_activity_log($record, $invoice_details['employee_id'], $invoice_details['client_id'], $invoice_details['workorder_id'], $invoice_details['invoice_id']);
        
        // Email the PDF        
        send_email($client_details['email'], _gettext("Invoice").' '.$VAR['invoice_id'], $body, $client_details['display_name'], $attachment, $invoice_details['employee_id'], $invoice_details['client_id'], $invoice_details['workorder_id'], $VAR['invoice_id']);
                
        // End all other processing
        die();
        
    }
    
}

// Client Envelope Print Routine
if($VAR['print_content'] == 'client_envelope') {
    
    // Print HTML Client Envelope
    if ($VAR['print_type'] == 'print_html') {
        
        // Log activity
        $record = _gettext("Address Envelope").' '._gettext("for").' '.$client_details['display_name'].' '._gettext("has been printed as html.");
        write_record_to_activity_log($record, $invoice_details['employee_id'], $invoice_details['client_id'], $invoice_details['workorder_id'], $invoice_details['invoice_id']);
        
        // Build the page
        $BuildPage = $smarty->fetch('invoice/printing/print_client_envelope.tpl');
        
    }
    
}