<?php

require(INCLUDES_DIR.'modules/workorder.php');

// Check that there is a workorder_id set
if($workorder_id == '') {
    force_page('workorder', 'open', 'warning_msg='.$smarty->get_template_vars('translate_workorder_error_message_details_edit_description_loadpage_no_workorder_id'));
    exit;
}

// Check there is a print content and print type set
if($VAR['print_content'] == '' || $VAR['print_type'] == '') {
    force_page('workorder', 'open', 'warning_msg='.$smarty->get_template_vars('translate_workorder_error_message_print_loadpage_no_print_options'));
    exit;
}

// Assign Variables
$smarty->assign('company',                  get_company_info($db, 'all')                        );
$smarty->assign('single_work_order',        display_single_open_workorder($db, $workorder_id)   );
$smarty->assign('work_order_notes',         display_workorder_notes($db, $workorder_id)         );
$smarty->assign('work_order_schedule',      display_workorder_schedule($db, $workorder_id)      );
$smarty->assign('work_order_resolution',    display_resolution($db, $workorder_id)              );

/* Display Page */

// Technician Workorder Slip Print Routine
if($VAR['print_content'] == 'technician_workorder_slip') {
    if ($VAR['print_type'] == 'print_html') {
        $smarty->display('workorder/printing/print_technician_workorder_slip.tpl');
    } elseif ($VAR['print_type'] == 'print_pdf') {        
        $pdf_output = $smarty->fetch('workorder/printing/print_technician_workorder_slip.tpl');
        // add pdf creation routing here
    } elseif ($VAR['print_type'] == 'email_pdf') {        
        $pdf_output = $smarty->fetch('workorder/printing/print_technician_workorder_slip.tpl');
        // add pdf creation routing here
    }
}

// Customer Workorder Slip Print Routine
if($VAR['print_content'] == 'customer_workorder_slip') {
    if ($VAR['print_type'] == 'print_html') {
        $smarty->display('workorder/printing/print_customer_workorder_slip.tpl');
    } elseif ($VAR['print_type'] == 'print_pdf') {        
        $pdf_output = $smarty->fetch('workorder/printing/print_customer_workorder_slip.tpl');
        // add pdf creation routing here
    } elseif ($VAR['print_type'] == 'email_pdf') {        
        $pdf_output = $smarty->fetch('workorder/printing/print_customer_workorder_slip.tpl');
        // add pdf creation routing here
    }
}

// Job Sheet Print Routine
if($VAR['print_content'] == 'job_sheet') {
    if ($VAR['print_type'] == 'print_html') {
        $smarty->display('workorder/printing/print_job_sheet.tpl');
    } elseif ($VAR['print_type'] == 'print_pdf') {        
        $pdf_output = $smarty->fetch('workorder/printing/print_job_sheet.tpl');
        // add pdf creation routing here
    } elseif ($VAR['print_type'] == 'email_pdf') {        
        $pdf_output = $smarty->fetch('workorder/printing/print_job_sheet.tpl');
        // add pdf creation routing here
    }
}