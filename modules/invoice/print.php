<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/customer.php');
require(INCLUDES_DIR.'modules/user.php');
require(INCLUDES_DIR.'modules/invoice.php');
require(INCLUDES_DIR.'modules/payment.php');
require(INCLUDES_DIR.'modules/workorder.php');

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
$smarty->assign('customer_details',         get_customer_details($db, get_invoice_details($db, $invoice_id, 'CUSTOMER_ID'))                             );
$smarty->assign('invoice_details',          get_invoice_details($db, $invoice_id)                                                                       );
$smarty->assign('workorder_details',        get_workorder_details($db, get_invoice_details($db, $invoice_id, 'WORKORDER_ID'))                           );
$smarty->assign('payment_details',          get_payment_details($db)                                                                                    );
$smarty->assign('active_payment_methods',   get_active_payment_methods($db)                                                                             );
$smarty->assign('labour_items',             get_invoice_labour_items($db, $invoice_id)                                                                  );
$smarty->assign('parts_items',              get_invoice_parts_items($db, $invoice_id)                                                                   );
$smarty->assign('labour_sub_total',         labour_sub_total($db, $invoice_id)                                                                          );
$smarty->assign('parts_sub_total',          parts_sub_total($db, $invoice_id)                                                                           );
$smarty->assign('employee_display_name',    get_user_details($db, get_invoice_details($db, $invoice_id, 'EMPLOYEE_ID'), 'EMPLOYEE_DISPLAY_NAME')        );


/* Invoice Print Routine */
if($VAR['print_content'] == 'invoice') {
    
    // Print HTML Invoice
    if ($VAR['print_type'] == 'print_html') {        
        $BuildPage .= $smarty->fetch('invoice/printing/print_invoice.tpl');    
        
    // Print PDF Invoice
    } elseif ($VAR['print_type'] == 'print_pdf') {        
        // Get Print Invoice as HTML into a variable
        $pdf_output = $smarty->fetch('invoice/printing/print_invoice.tpl');    
        // Call mPDF and output as PDF to page      
        require_once(INCLUDES_DIR.'mpdf.php');         
        
    // Email PDF Invoice
    } elseif($VAR['print_type'] == 'email_pdf') {        
        // add pdf creation routing here  
    }
}

/* Address Only Print Routine */
if($VAR['print_content'] == 'address') {
    
    // Print HTML Address
    if ($VAR['print_type'] == 'print_html') {        
        $BuildPage .= $smarty->fetch('invoice/printing/print_address.tpl');     
    }
    
}