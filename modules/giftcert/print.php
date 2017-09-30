<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/customer.php');
require(INCLUDES_DIR.'modules/giftcert.php');

// Load the Barcode library
require(LIBRARIES_DIR.'php-barcode-generator/BarcodeGenerator.php');
require(LIBRARIES_DIR.'php-barcode-generator/BarcodeGeneratorHTML.php');
//require(LIBRARIES_DIR.'php-barcode-generator/BarcodeGeneratorJPG.php');
//require(LIBRARIES_DIR.'php-barcode-generator/BarcodeGeneratorPNG.php');
//require(LIBRARIES_DIR.'php-barcode-generator/BarcodeGeneratorSVG.php');

// Generate the barcode (as html)
$bc_generator = new Picqer\Barcode\BarcodeGeneratorHTML();
$barcode = $bc_generator->getBarcode(get_giftcert_details($db, $giftcert_id, 'giftcert_code'), $bc_generator::TYPE_CODE_128);

// Check if we have an giftcert_id
if($giftcert_id == '') {
    force_page('giftcert', 'search', 'warning_msg='._gettext("No Gift Certificate ID supplied."));
    exit;
}

// Check there is a print content and print type set
if($VAR['print_content'] == '' || $VAR['print_type'] == '') {
    force_page('giftcert', 'search', 'warning_msg='._gettext("Some or all of the Printing Options are not set."));
    exit;
}

// Get required details
$smarty->assign('company_details',  get_company_details($db)                                                            );
$smarty->assign('customer_details', get_customer_details($db, get_giftcert_details($db, $giftcert_id, 'customer_id'))   );
$smarty->assign('giftcert_details', get_giftcert_details($db, $giftcert_id)                                             );
$smarty->assign('barcode',          $barcode                                                                            );


// Gift Certificate Print Routine
if($VAR['print_content'] == 'gift_certificate') {    
    
    // Build the PDF filename
    $pdf_filename = _gettext("Gift Certificate").' '.$giftcert_id;
    
    // Print HTML
    if ($VAR['print_type'] == 'print_html') {
        $BuildPage .= $smarty->fetch('giftcert/printing/print_gift_certificate.tpl');
    
    // Print PDF
    } elseif ($VAR['print_type'] == 'print_pdf') {        
        
        // Get Print Invoice as HTML into a variable
        $pdf_template = $smarty->fetch('giftcert/printing/print_gift_certificate.tpl');
        
        // output PDF in brower
        mpdf_output_in_browser($pdf_filename, $pdf_template);
        
    // Email PDF
    } elseif ($VAR['print_type'] == 'email_pdf') {
        
        // Get Print Invoice as HTML into a variable
        $pdf_template = $smarty->fetch('giftcert/printing/print_gift_certificate.tpl');
        
        // return the PDF in a variable
        $pdf_as_string = mpdf_output_as_varible($pdf_filename, $pdf_template);
        
        // Build the PDF        
        $attachment['data'] = $pdf_as_string;
        $attachment['filename'] = $pdf_filename;
        $attachment['filetype'] = 'application/pdf';
        
        // Build the message body
        $customer_details = get_customer_details($db, get_workorder_details($db, $workorder_id, 'customer_id'));
        $body = get_email_message_body($db, 'email_msg_giftcert', $customer_details);
                      
        // Email the PDF
        send_email($customer_details['email'], _gettext("Gift Certificate").' '.$workorder_id, $body, $customer_details['display_name'], $attachment);
        
        // End all other processing
        die();
        
    }
}