<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/customer.php');
require(INCLUDES_DIR.'modules/workorder.php');
require(INCLUDES_DIR.'modules/schedule.php');
require(INCLUDES_DIR.'modules/user.php');
require(INCLUDES_DIR.'mpdf.php');

// Check if we have a workorder_id
if($workorder_id == '') {
    force_page('workorder', 'search', 'warning_msg='.gettext("No Workorder ID supplied."));
    exit;
}

// Check there is a print content and print type set
if($VAR['print_content'] == '' || $VAR['print_type'] == '') {
    force_page('workorder', 'overview', 'warning_msg='.gettext("Some or all of the Printing Options are not set."));
    exit;
}

// Assign Variables
$smarty->assign('company_details',      get_company_details($db)                                                            );
$smarty->assign('employee_details',     get_user_details($db, get_workorder_details($db, $workorder_id, 'employee_id'))     );
$smarty->assign('customer_details',     get_customer_details($db, get_workorder_details($db, $workorder_id, 'customer_id')) );
$smarty->assign('customer_types',       get_customer_types($db)                                                             );
$smarty->assign('workorder_details',    get_workorder_details($db, $workorder_id)                                           );
$smarty->assign('workorder_notes',      display_workorder_notes($db, $workorder_id)                                         );
$smarty->assign('workorder_schedules',  display_workorder_schedules($db, $workorder_id)                                     );


// Technician Workorder Slip Print Routine
if($VAR['print_content'] == 'technician_workorder_slip') {    
    
    // Build the PDF filename
    $pdf_filename = gettext("Technician Workorder Slip").' '.$workorder_id;
    
    // Print HTML
    if ($VAR['print_type'] == 'print_html') {
        $BuildPage .= $smarty->fetch('workorder/printing/print_technician_workorder_slip.tpl');
    
    // Print PDF
    } elseif ($VAR['print_type'] == 'print_pdf') {        
        
        // Get Print Invoice as HTML into a variable
        $pdf_template = $smarty->fetch('workorder/printing/print_technician_workorder_slip.tpl');
        
        // output PDF in brower
        mpdf_output_in_browser($pdf_filename, $pdf_template);
        
    // Email PDF
    } elseif ($VAR['print_type'] == 'email_pdf') {
        
        // Get Print Invoice as HTML into a variable
        $pdf_template = $smarty->fetch('workorder/printing/print_technician_workorder_slip.tpl');
        
        // return the PDF in a variable
        $pdf_as_string = mpdf_output_as_varible($pdf_filename, $pdf_template);
        
        // Build the PDF        
        $attachment['data'] = $pdf_as_string;
        $attachment['filename'] = $pdf_filename;
        $attachment['filetype'] = 'application/pdf';
        
        // Build the message body
        $customer_details = get_customer_details($db, get_workorder_details($db, $workorder_id, 'customer_id'));
        $body = get_email_message_body($db, 'email_msg_workorder', $customer_details);
                      
        // Email the PDF
        send_email($customer_details['email'], gettext("Work Order").' '.$workorder_id, $body, $customer_details['display_name'], $attachment);
        
        // End all other processing
        die();
        
    }
}

// Customer Workorder Slip Print Routine
if($VAR['print_content'] == 'customer_workorder_slip') {
    
    // Build the PDF filename
    $pdf_filename = gettext("Customer Workorder Slip").' '.$workorder_id;    
    
    // Print HTML
    if ($VAR['print_type'] == 'print_html') {
        $BuildPage .= $smarty->fetch('workorder/printing/print_customer_workorder_slip.tpl');
    
    // Print PDF
    } elseif ($VAR['print_type'] == 'print_pdf') {        
        
        // Get Print Invoice as HTML into a variable
        $pdf_template = $smarty->fetch('workorder/printing/print_customer_workorder_slip.tpl');
        
        // output PDF in brower
        mpdf_output_in_browser($pdf_filename, $pdf_template);       
        
    // Email PDF
    } elseif ($VAR['print_type'] == 'email_pdf') { 
        
        // Get Print Invoice as HTML into a variable
        $pdf_template = $smarty->fetch('workorder/printing/print_customer_workorder_slip.tpl');
        
        // return the PDF in a variable
        $pdf_as_string = mpdf_output_as_varible($pdf_filename, $pdf_template);
        
        // Build the PDF        
        $attachment['data'] = $pdf_as_string;
        $attachment['filename'] = $pdf_filename;
        $attachment['filetype'] = 'application/pdf';
        
        // Build the message body
        $customer_details = get_customer_details($db, get_workorder_details($db, $workorder_id, 'customer_id'));
        $body = get_email_message_body($db, 'email_msg_workorder', $customer_details);
                      
        // Email the PDF
        send_email($customer_details['email'], gettext("Work Order").' '.$workorder_id, $body, $customer_details['display_name'], $attachment);
        
        // End all other processing
        die();
        
    }
}

// Technician Job Sheet Print Routine
if($VAR['print_content'] == 'technician_job_sheet') {
    
    // Build the PDF filename
    $pdf_filename = gettext("Technician Job Sheet").' '.$workorder_id;        
    
    // Print HTML
    if ($VAR['print_type'] == 'print_html') {
        $BuildPage .= $smarty->fetch('workorder/printing/print_technician_job_sheet.tpl');
        
    // Print PDF
    } elseif ($VAR['print_type'] == 'print_pdf') {
        
        // Get Print Invoice as HTML into a variable
        $pdf_template = $smarty->fetch('workorder/printing/print_technician_job_sheet.tpl');
        
        // output PDF in brower
        mpdf_output_in_browser($pdf_filename, $pdf_template);
        
    // Print HTML
    } elseif ($VAR['print_type'] == 'email_pdf') {  
        
        // Get Print Invoice as HTML into a variable
        $pdf_template = $smarty->fetch('workorder/printing/print_technician_job_sheet.tpl');
        
        // return the PDF in a variable
        $pdf_as_string = mpdf_output_as_varible($pdf_filename, $pdf_template);
        
        // Build the PDF        
        $attachment['data'] = $pdf_as_string;
        $attachment['filename'] = $pdf_filename;
        $attachment['filetype'] = 'application/pdf';
        
        // Build the message body
        $customer_details = get_customer_details($db, get_workorder_details($db, $workorder_id, 'customer_id'));
        $body = get_email_message_body($db, 'email_msg_workorder', $customer_details);
                      
        // Email the PDF
        send_email($customer_details['email'], gettext("Work Order").' '.$workorder_id, $body, $customer_details['display_name'], $attachment);
        
        // End all other processing
        die();
        
    }
}