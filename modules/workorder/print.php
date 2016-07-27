<?php
require_once('include.php');

if(!xml2php('workorder')) {
    $smarty->assign('error_msg',"Error in language file");
}

if(!$single_work_order = display_single_open_workorder($db, $VAR['wo_id'])){
    force_page('core', "error&menu=1&error_msg=The Work Order you Requested was not found&type=error");
    exit;
} else {

    /* get company Information */
    $q = "SELECT * FROM ".PRFX."TABLE_COMPANY";
    $rs = $db->execute($q);
    $company = $rs->GetArray();

    /* work order notes */
    $work_order_notes = display_workorder_notes($db, $VAR['wo_id']);

    /* work order Status */
    $work_order_status = display_workorder_status($db, $VAR['wo_id']);
    
    /* work order schedule */
    $work_order_schedule = get_work_order_schedule($db, $VAR['wo_id']);
    
    /* work order resolution */
    $work_order_resolution = display_resolution($db,$VAR['wo_id']);

}

$smarty->assign('company',                  $company                );
$smarty->assign('single_work_order',        $single_work_order      );
$smarty->assign('work_order_notes',         $work_order_notes       );
$smarty->assign('work_order_status',        $work_order_status      );
$smarty->assign('work_order_schedule',      $work_order_schedule    );
$smarty->assign('work_order_resolution',    $work_order_resolution  ); 

/* Technician Work Order Slip Print Routine */
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

/* Customer Work Order Slip Print Routine */
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
    force_page('core', "error&menu=1&error_msg=No Printing Options set. Please set up printing options in the Control Center.&type=error");
    exit;
}


/* remove all of this pdf shite - THROUGH MYITCRM AND THE DATABASE */












/* get printing options 
$q = "SELECT  HTML_PRINT, PDF_PRINT FROM ".PRFX."SETUP";
$rs = $db->execute($q);
$html_print = $rs->fields['HTML_PRINT'];
$pdf_print  = $rs->fields['PDF_PRINT'];
//if($html_print == 1) */


/*
} else if ($pdf_print == 1) {
    
    /* create pdf */
 /*   require(INCLUDES_DIR.SEP.'fpdf'.SEP.'fpdf.php');
    class PDF extends FPDF {
        
        // Page header
        function Header() {
            $this->SetFont('Arial','B',15);
        }

        // Page footer
        function Footer(){
            
            //Position at 1.5 cm from bottom
            $this->SetY(-15);
            
            //Arial italic 8
            $this->SetFont('Arial','I',8);
            
            //Page number
            $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
        }
    }

    // Instantiation of inherited class
    $pdf = new PDF();
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->SetFont('Times','',12);
    $pdf->Cell(0,10,'',1,1);
    $pdf->Cell(10,0,$work_order_notes,1,1);
    $pdf->Output();*/


/*
} else {
    force_page('core', "error&menu=1&error_msg=No Printing Options set. Please set up printing options in the Control Center.&type=error");
    exit;
}*/