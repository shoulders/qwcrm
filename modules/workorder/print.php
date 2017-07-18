<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/workorder.php');
require(INCLUDES_DIR.'modules/schedule.php');

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
$smarty->assign('company_details',      get_company_details($db)                        );
$smarty->assign('single_workorder',     display_single_workorder($db, $workorder_id)    );
$smarty->assign('workorder_notes',      display_workorder_notes($db, $workorder_id)     );
$smarty->assign('workorder_schedules',  display_workorder_schedules($db, $workorder_id) );

/* Display Page */

// Technician Workorder Slip Print Routine
if($VAR['print_content'] == 'technician_workorder_slip') {
    if ($VAR['print_type'] == 'print_html') {
        $BuildPage .= $smarty->fetch('workorder/printing/print_technician_workorder_slip.tpl');
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
        $BuildPage .= $smarty->fetch('workorder/printing/print_customer_workorder_slip.tpl');
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
        $BuildPage .= $smarty->fetch('workorder/printing/print_job_sheet.tpl');
    } elseif ($VAR['print_type'] == 'print_pdf') {        
        $pdf_output = $smarty->fetch('workorder/printing/print_job_sheet.tpl');
        // add pdf creation routing here
    } elseif ($VAR['print_type'] == 'email_pdf') {        
        $pdf_output = $smarty->fetch('workorder/printing/print_job_sheet.tpl');
        // add pdf creation routing here
    }
}