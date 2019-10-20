<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'client.php');
require(INCLUDES_DIR.'voucher.php');

// Generate the barcode (as html)
$bc_generator = new Picqer\Barcode\BarcodeGeneratorHTML();
$barcode = $bc_generator->getBarcode(get_voucher_details($VAR['voucher_id'], 'voucher_code'), $bc_generator::TYPE_CODE_128);

// Check if we have an voucher_id
if(!isset($VAR['voucher_id']) || !$VAR['voucher_id']) {
    force_page('voucher', 'search', 'warning_msg='._gettext("No Voucher ID supplied."));
}

// Check there is a print content and print type set
if(!isset($VAR['print_content'], $VAR['print_type']) || !$VAR['print_content'] || !$VAR['print_type']) {
    force_page('voucher', 'search', 'warning_msg='._gettext("Some or all of the Printing Options are not set."));
}

// Get Voucher details
$voucher_details = get_voucher_details($VAR['voucher_id']);
$client_details = get_client_details($voucher_details['client_id']);

// Assign Variables
$smarty->assign('company_details',  get_company_details()       );
$smarty->assign('client_details',   $client_details             );
$smarty->assign('voucher_details', $voucher_details           );
$smarty->assign('barcode',          $barcode                    );

// Voucher Print Routine
if($VAR['print_content'] == 'voucher') {    
    
    // Build the PDF filename
    $pdf_filename = _gettext("Voucher").' '.$VAR['voucher_id'];
    
    // Print HTML
    if ($VAR['print_type'] == 'print_html') {
        
        // Log activity
        $record = _gettext("Voucher").' '.$VAR['voucher_id'].' '._gettext("has been printed as html.");
        write_record_to_activity_log($record, $voucher_details['employee_id'], $voucher_details['client_id'], $voucher_details['workorder_id'], $voucher_details['invoice_id']);
        
        // Build the page
        \QFactory::$BuildPage .= $smarty->fetch('voucher/printing/print_voucher.tpl');
    
    // Print PDF
    } elseif ($VAR['print_type'] == 'print_pdf') {        
        
        // Get Print Invoice as HTML into a variable
        $pdf_template = $smarty->fetch('voucher/printing/print_voucher.tpl');
        
        // Log activity
        $record = _gettext("Voucher").' '.$VAR['voucher_id'].' '._gettext("has been printed as a PDF.");
        write_record_to_activity_log($record, $voucher_details['employee_id'], $voucher_details['client_id'], $voucher_details['workorder_id'], $voucher_details['invoice_id']);
        
        // Output PDF in brower
        mpdf_output_in_browser($pdf_filename, $pdf_template);
        
    // Email PDF
    } elseif ($VAR['print_type'] == 'email_pdf') {
        
        // Get Print Invoice as HTML into a variable
        $pdf_template = $smarty->fetch('voucher/printing/print_voucher.tpl');
        
        // return the PDF in a variable
        $pdf_as_string = mpdf_output_as_variable($pdf_filename, $pdf_template);
        
        // Build the PDF        
        $attachment['data'] = $pdf_as_string;
        $attachment['filename'] = $pdf_filename;
        $attachment['filetype'] = 'application/pdf';
        
        // Build the message body        
        $body = get_email_message_body('email_msg_voucher', $client_details);  // This message does not currently exist
        
        // Log activity
        $record = _gettext("Voucher").' '.$VAR['voucher_id'].' '._gettext("has been emailed as a PDF.");
        write_record_to_activity_log($record, $voucher_details['employee_id'], $voucher_details['client_id'], $voucher_details['workorder_id'], $voucher_details['invoice_id']);
        
        // Email the PDF
        send_email($client_details['email'], _gettext("Voucher").' '.$VAR['voucher_id'], $body, $client_details['display_name'], $attachment);
        
        // End all other processing
        die();
        
    }
}