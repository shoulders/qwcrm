<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/customer.php');
require(INCLUDES_DIR.'modules/company.php');
require(INCLUDES_DIR.'modules/user.php');
require(INCLUDES_DIR.'modules/invoice.php');
require(INCLUDES_DIR.'modules/payment.php');
require(INCLUDES_DIR.'modules/workorder.php');
require(INCLUDES_DIR.'mpdf.php');

// Check if we have an invoice_id
if($invoice_id == '') {
    force_page('invoice', 'search', 'warning_msg='.gettext("No Invoice ID supplied."));
    exit;
}

// Check there is a print content and print type set
if($VAR['print_content'] == '' || $VAR['print_type'] == '') {
    force_page('workorder', 'overview', 'warning_msg='.gettext("Some or all of the Printing Options are not set."));
    exit;
}

$smarty->assign('company_details',          get_company_details($db)                                                                                    );
$smarty->assign('customer_details',         get_customer_details($db, get_invoice_details($db, $invoice_id, 'customer_id'))                             );
$smarty->assign('invoice_details',          get_invoice_details($db, $invoice_id)                                                                       );
$smarty->assign('workorder_details',        get_workorder_details($db, get_invoice_details($db, $invoice_id, 'workorder_id'))                           );
$smarty->assign('payment_details',          get_payment_details($db)                                                                                    );
$smarty->assign('active_payment_methods',   get_active_payment_methods($db)                                                                             );
$smarty->assign('labour_items',             get_invoice_labour_items($db, $invoice_id)                                                                  );
$smarty->assign('parts_items',              get_invoice_parts_items($db, $invoice_id)                                                                   );
$smarty->assign('labour_sub_total',         labour_sub_total($db, $invoice_id)                                                                          );
$smarty->assign('parts_sub_total',          parts_sub_total($db, $invoice_id)                                                                           );
$smarty->assign('employee_display_name',    get_user_details($db, get_invoice_details($db, $invoice_id, 'employee_id'), 'employee_display_name')        );


// Invoice Print Routine
if($VAR['print_content'] == 'invoice') {
    
    // Build the PDF filename
    $pdf_filename = gettext("Invoice").'-'.$invoice_id;
    
    // Print HTML Invoice
    if ($VAR['print_type'] == 'print_html') {        
        $BuildPage .= $smarty->fetch('invoice/printing/print_invoice.tpl'); 
    }
    
    // Print PDF Invoice
    if ($VAR['print_type'] == 'print_pdf') {
        
        // Get Print Invoice as HTML into a variable
        $pdf_template = $smarty->fetch('invoice/printing/print_invoice.tpl');
        
        // output PDF in brower
        mpdf_output_in_browser($pdf_filename, $pdf_template);
        
    }        
        
    // Email PDF Invoice
    if($VAR['print_type'] == 'email_pdf') {  
        
        // Get Print Invoice as HTML into a variable
        $pdf_template = $smarty->fetch('invoice/printing/print_invoice.tpl');
        
        // return the PDF in a variable
        $pdf_as_string = mpdf_output_as_varible($pdf_filename, $pdf_template);
        
        // Build the PDF        
        $attachment['data'] = $pdf_as_string;
        $attachment['filename'] = $pdf_filename;
        $attachment['filetype'] = 'application/pdf';
        
        // Build the message body
        $customer_details = get_customer_details($db, get_invoice_details($db, $invoice_id, 'customer_id'));
        $body = get_email_message_body($db, 'email_msg_invoice', $customer_details);
                      
        // Email the PDF
        send_email($customer_details['email'], gettext("Invoice").' '.$invoice_id, $body, $customer_details['display_name'], $attachment);
        
        // End all other processing
        die();
        
    }
    
}

// Customer Envelope Print Routine
if($VAR['print_content'] == 'customer_envelope') {
    
    // Print HTML Customer Envelope
    if ($VAR['print_type'] == 'print_html') {        
        $BuildPage .= $smarty->fetch('invoice/printing/print_customer_envelope.tpl');     
    }
    
}