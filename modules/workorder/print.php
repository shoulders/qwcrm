<?php

require(INCLUDES_DIR.'modules/workorder.php');

/* Error Catcher - if nothing is done run this - CHANGE MESSAGE */
if($VAR['print_content'] == '' || $VAR['print_type'] == '') {
    force_page('core', 'error', 'error_type=warning&error_location=workorder:print&php_function=&error_msg='.$smarty->get_template_vars('translate_workorder_error_message_print_loadpage_failed').'&php_error_msg='.$php_errormsg.'&database_error='.$db->ErrorMsg());
    exit;
}

/* get company Information - this might not be needed - should be a function */
// see index.php
$q = "SELECT * FROM ".PRFX."TABLE_COMPANY";
$rs = $db->execute($q);
$company = $rs->GetArray();

$smarty->assign('company',                  $company                                    );
$smarty->assign('single_work_order',        display_single_open_workorder($db, $workorder_id)  );
$smarty->assign('work_order_notes',         display_workorder_notes($db, $workorder_id)        );
$smarty->assign('work_order_schedule',      display_workorder_schedule($db, $workorder_id)     );
$smarty->assign('work_order_resolution',    display_resolution($db, $workorder_id)             ); 

/* Technician Workorder Slip Print Routine */
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

/* Customer Workorder Slip Print Routine */
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

/* Job Sheet Print Routine */
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