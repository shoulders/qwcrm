<?php

require_once('include.php');

$wo_id = $VAR['wo_id'];

if(!$single_work_order = display_single_open_workorder($db, $VAR['wo_id'])){
    force_page('core', "error&menu=1&error_msg='.$smarty->get_template_vars('translate_workorder_error_message_the_work_order_you_requested_was_not_found').'&type=error");
    exit;
} else {

    /* get company Information */
    $q = "SELECT * FROM ".PRFX."TABLE_COMPANY";
    $rs = $db->execute($q);
    $company = $rs->GetArray();

    /* work order notes */
    $work_order_notes = display_workorder_notes($db, $wo_id);

    /* work order Status */
    $work_order_status = display_workorder_status($db, $wo_id);
    
    /* work order schedule */
    $work_order_schedule = display_work_order_schedule($db, $wo_id);
    
    /* work order resolution */
    $work_order_resolution = display_resolution($db, $wo_id);

}

$smarty->assign('company',                  $company                );
$smarty->assign('single_work_order',        $single_work_order      );
$smarty->assign('work_order_notes',         $work_order_notes       );
$smarty->assign('work_order_status',        $work_order_status      );
$smarty->assign('work_order_schedule',      $work_order_schedule    );
$smarty->assign('work_order_resolution',    $work_order_resolution  ); 

/* Technician Workorder Slip Print Routine */
if($VAR['print_content'] == 'technician_workorder_slip') {
    if ($VAR['print_output_method'] == 'html') {
        $smarty->display('workorder/print_technician_workorder_slip.tpl');
    } elseif ($VAR['print_output_method'] == 'pdf') {        
        $pdf_output = $smarty->fetch('workorder/print_technician_workorder_slip.tpl');
        // add pdf creation routing here
    } elseif ($VAR['print_output_method'] == 'email_pdf') {        
        $pdf_output = $smarty->fetch('workorder/print_technician_workorder_slip.tpl');
        // add pdf creation routing here
    }
}

/* Customer Workorder Slip Print Routine */
if($VAR['print_content'] == 'customer_workorder_slip') {
    if ($VAR['print_output_method'] == 'html') {
        $smarty->display('workorder/print_customer_workorder_slip.tpl');
    } elseif ($VAR['print_output_method'] == 'pdf') {        
        $pdf_output = $smarty->fetch('workorder/print_customer_workorder_slip.tpl');
        // add pdf creation routing here
    } elseif ($VAR['print_output_method'] == 'email_pdf') {        
        $pdf_output = $smarty->fetch('workorder/print_customer_workorder_slip.tpl');
        // add pdf creation routing here
    }
}

/* Job Sheet Print Routine */
if($VAR['print_content'] == 'job_sheet') {
    if ($VAR['print_output_method'] == 'html') {
        $smarty->display('workorder/print_job_sheet.tpl');
    } elseif ($VAR['print_output_method'] == 'pdf') {        
        $pdf_output = $smarty->fetch('workorder/print_job_sheet.tpl');
        // add pdf creation routing here
    } elseif ($VAR['print_output_method'] == 'email_pdf') {        
        $pdf_output = $smarty->fetch('workorder/print_job_sheet.tpl');
        // add pdf creation routing here
    }
}

/* Error Catcher - if nothing is done run this - CHANGE MESSAGE */
if($VAR['print_content'] == '' || $VAR['print_output_method'] == '') {
    force_page('core', 'error&menu=1&error_msg='.$smarty->get_template_vars('translate_workorder_error_message_no_printing_options_set').'&type=error');
    exit;
}