<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'components/customer.php');
require(INCLUDES_DIR.'components/workorder.php');
require(INCLUDES_DIR.'components/schedule.php');
require(INCLUDES_DIR.'components/user.php');
require(INCLUDES_DIR.'system/mpdf.php');

// Check if we have a workorder_id
if($VAR['workorder_id'] == '') {
    force_page('workorder', 'search', 'warning_msg='._gettext("No Workorder ID supplied."));
}

// Check there is a print content and print type set
if($VAR['print_content'] == '' || $VAR['print_type'] == '') {
    force_page('workorder', 'search', 'warning_msg='._gettext("Some or all of the Printing Options are not set."));
}

// Get Record Details
$workorder_details  = get_workorder_details($db, $VAR['workorder_id']);
$customer_details   = get_customer_details($db, $workorder_details['customer_id']);

/// Assign Variables
$smarty->assign('company_details',      get_company_details($db)                                        );
$smarty->assign('employee_details',     get_user_details($db, $workorder_details['employee_id'])        );
$smarty->assign('customer_details',     $customer_details                                               );
$smarty->assign('workorder_details',    $workorder_details                                              );
$smarty->assign('customer_types',       get_customer_types($db)                                         );
$smarty->assign('workorder_statuses',   get_workorder_statuses($db)                                     );
$smarty->assign('workorder_notes',      display_workorder_notes($db, $VAR['workorder_id'])                     );
$smarty->assign('workorder_schedules',  display_schedules($db, $order_by = 'schedule_id', 'DESC', false, null, null, null, null, null, null, null, $VAR['workorder_id'])  );

// Technician Workorder Slip Print Routine
if($VAR['print_content'] == 'technician_workorder_slip') {    
    
    // Build the PDF filename
    $pdf_filename = _gettext("Technician Workorder Slip").' '.$VAR['workorder_id'];
    
    // Print HTML
    if ($VAR['print_type'] == 'print_html') {
        
        // Log activity
        $record = _gettext("Technician Workorder Slip").' '.$VAR['workorder_id'].' '._gettext("has been printed as html.");
        write_record_to_activity_log($record, $workorder_details['employee_id'], $workorder_details['customer_id'], $workorder_details['workorder_id'], $workorder_details['invoice_id']);

        // Build the page
        $BuildPage .= $smarty->fetch('workorder/printing/print_technician_workorder_slip.tpl');
    
    // Print PDF
    } elseif ($VAR['print_type'] == 'print_pdf') {        
        
        // Get Print Invoice as HTML into a variable
        $pdf_template = $smarty->fetch('workorder/printing/print_technician_workorder_slip.tpl');
        
        // Log activity
        $record = _gettext("Technician Workorder Slip").' '.$VAR['workorder_id'].' '._gettext("has been printed as a PDF.");
        write_record_to_activity_log($record, $workorder_details['employee_id'], $workorder_details['customer_id'], $workorder_details['workorder_id'], $workorder_details['invoice_id']);

        // Output PDF in brower
        mpdf_output_in_browser($pdf_filename, $pdf_template);
        
    // Email PDF
    } elseif ($VAR['print_type'] == 'email_pdf') {
        
        // Get Print Invoice as HTML into a variable
        $pdf_template = $smarty->fetch('workorder/printing/print_technician_workorder_slip.tpl');
        
        // Return the PDF in a variable
        $pdf_as_string = mpdf_output_as_varible($pdf_filename, $pdf_template);
        
        // Build the PDF        
        $attachment['data'] = $pdf_as_string;
        $attachment['filename'] = $pdf_filename;
        $attachment['filetype'] = 'application/pdf';
        
        // Build the message body        
        $body = get_email_message_body($db, 'email_msg_workorder', $customer_details);
        
        // Log activity
        $record = _gettext("Technician Workorder Slip").' '.$VAR['workorder_id'].' '._gettext("has been emailed as a PDF.");
        write_record_to_activity_log($record, $workorder_details['employee_id'], $workorder_details['customer_id'], $workorder_details['workorder_id'], $workorder_details['invoice_id']);

        // Email the PDF
        send_email($customer_details['email'], _gettext("Work Order").' '.$VAR['workorder_id'], $body, $customer_details['display_name'], $attachment);
        
        // End all other processing
        die();
        
    }
}

// Customer Workorder Slip Print Routine
if($VAR['print_content'] == 'customer_workorder_slip') {
    
    // Build the PDF filename
    $pdf_filename = _gettext("Customer Workorder Slip").' '.$VAR['workorder_id'];    
    
    // Print HTML
    if ($VAR['print_type'] == 'print_html') {
        
        // Log activity
        $record = _gettext("Customer Workorder Slip").' '.$VAR['workorder_id'].' '._gettext("has been printed as html.");
        write_record_to_activity_log($record, $workorder_details['employee_id'], $workorder_details['customer_id'], $workorder_details['workorder_id'], $workorder_details['invoice_id']);
        
        // Build the page
        $BuildPage .= $smarty->fetch('workorder/printing/print_customer_workorder_slip.tpl');
    
    // Print PDF
    } elseif ($VAR['print_type'] == 'print_pdf') {        
        
        // Get Print Invoice as HTML into a variable
        $pdf_template = $smarty->fetch('workorder/printing/print_customer_workorder_slip.tpl');
        
        // Log activity
        $record = _gettext("Customer Workorder Slip").' '.$VAR['workorder_id'].' '._gettext("has been printed as a PDF.");
        write_record_to_activity_log($record, $workorder_details['employee_id'], $workorder_details['customer_id'], $workorder_details['workorder_id'], $workorder_details['invoice_id']);
        
        // Output PDF in brower
        mpdf_output_in_browser($pdf_filename, $pdf_template);       
        
    // Email PDF
    } elseif ($VAR['print_type'] == 'email_pdf') { 
        
        // Get Print Invoice as HTML into a variable
        $pdf_template = $smarty->fetch('workorder/printing/print_customer_workorder_slip.tpl');
        
        // Return the PDF in a variable
        $pdf_as_string = mpdf_output_as_varible($pdf_filename, $pdf_template);
        
        // Build the PDF        
        $attachment['data'] = $pdf_as_string;
        $attachment['filename'] = $pdf_filename;
        $attachment['filetype'] = 'application/pdf';
        
        // Build the message body        
        $body = get_email_message_body($db, 'email_msg_workorder', $customer_details);
        
        // Log activity
        $record = _gettext("Customer Workorder Slip").' '.$VAR['workorder_id'].' '._gettext("has been emailed as a PDF.");
        write_record_to_activity_log($record, $workorder_details['employee_id'], $workorder_details['customer_id'], $workorder_details['workorder_id'], $workorder_details['invoice_id']);
        
        // Email the PDF
        send_email($customer_details['email'], _gettext("Work Order").' '.$VAR['workorder_id'], $body, $customer_details['display_name'], $attachment);
        
        // End all other processing
        die();
        
    }
}

// Technician Job Sheet Print Routine
if($VAR['print_content'] == 'technician_job_sheet') {
    
    // Build the PDF filename
    $pdf_filename = _gettext("Technician Job Sheet").' '.$VAR['workorder_id'];        
    
    // Print HTML
    if ($VAR['print_type'] == 'print_html') {
        
        // Log activity
        $record = _gettext("Technician Job Sheet").' '.$VAR['workorder_id'].' '._gettext("has been printed as html.");
        write_record_to_activity_log($record, $workorder_details['employee_id'], $workorder_details['customer_id'], $workorder_details['workorder_id'], $workorder_details['invoice_id']);
                
        // Build the page
        $BuildPage .= $smarty->fetch('workorder/printing/print_technician_job_sheet.tpl');
        
    // Print PDF
    } elseif ($VAR['print_type'] == 'print_pdf') {
        
        // Get Print Invoice as HTML into a variable
        $pdf_template = $smarty->fetch('workorder/printing/print_technician_job_sheet.tpl');
        
        // Log activity
        $record = _gettext("Technician Job Sheet").' '.$VAR['workorder_id'].' '._gettext("has been printed as a PDF.");
        write_record_to_activity_log($record, $workorder_details['employee_id'], $workorder_details['customer_id'], $workorder_details['workorder_id'], $workorder_details['invoice_id']);
            
        // Output PDF in brower
        mpdf_output_in_browser($pdf_filename, $pdf_template);
        
    // Print HTML
    } elseif ($VAR['print_type'] == 'email_pdf') {  
        
        // Get Print Invoice as HTML into a variable
        $pdf_template = $smarty->fetch('workorder/printing/print_technician_job_sheet.tpl');
        
        // Return the PDF in a variable
        $pdf_as_string = mpdf_output_as_varible($pdf_filename, $pdf_template);
        
        // Build the PDF        
        $attachment['data'] = $pdf_as_string;
        $attachment['filename'] = $pdf_filename;
        $attachment['filetype'] = 'application/pdf';
        
        // Build the message body        
        $body = get_email_message_body($db, 'email_msg_workorder', $customer_details);
        
        // Log activity
        $record = _gettext("Technician Job Sheet").' '.$VAR['workorder_id'].' '._gettext("has been emailed as a PDF.");
        write_record_to_activity_log($record, $workorder_details['employee_id'], $workorder_details['customer_id'], $workorder_details['workorder_id'], $workorder_details['invoice_id']);
            
        // Email the PDF
        send_email($customer_details['email'], _gettext("Work Order").' '.$VAR['workorder_id'], $body, $customer_details['display_name'], $attachment);
        
        // End all other processing
        die();
        
    }
}