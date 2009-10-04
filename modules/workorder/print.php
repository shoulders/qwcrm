<?php

require_once("include.php");

if(!$single_work_order = display_single_open_workorder($db, $VAR['wo_id'])){
	force_page('core', "error&menu=1&error_msg=The Work Order you Requested was not found&type=error");
	exit;
} else {

	/* get company Information */
	$q = "SELECT * FROM ".PRFX."TABLE_COMPANY";
	$rs = $db->execute($q);
	$company = $rs->GetArray();

	/* Work order notes */
	$work_order_notes = display_workorder_notes($db, $VAR['wo_id']);

	/* work order Status */
	$work_order_status = display_workorder_status($db, $VAR['wo_id']);
	
	/* work order schedule */
	$work_order_sched = get_work_order_schedule ($db, $VAR['wo_id']);
	
	/* work order resolution */
	$work_order_res = display_resolution($db,$VAR['wo_id']);

}

/* get printing options */
$q = "SELECT  HTML_PRINT, PDF_PRINT FROM ".PRFX."SETUP";
$rs = $db->execute($q);
$html_print = $rs->fields['HTML_PRINT'];
$pdf_print  = $rs->fields['PDF_PRINT'];

if($html_print == 1) {
	/* htm print page */
	$smarty->assign('company', 						$company);
	$smarty->assign('single_workorder_array', 	$single_work_order);
	$smarty->assign('work_order_notes', 			$work_order_notes );
	$smarty->assign('work_order_status', 			$work_order_status);
	$smarty->assign('work_order_sched', 			$work_order_sched);
	$smarty->assign('work_order_res',				$work_order_res);		
	$smarty->display('workorder/print.tpl');
} else if($pdf_print == 1) {
	/* create pdf */
	require(INCLUDE_URL.SEP.'fpdf'.SEP.'fpdf.php');
	class PDF extends FPDF {
	//Page header
	function Header() {
		$this->SetFont('Arial','B',15);
	}

//Page footer
	function Footer(){
		//Position at 1.5 cm from bottom
		$this->SetY(-15);
		//Arial italic 8
		$this->SetFont('Arial','I',8);
		//Page number
		$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	}
}

//Instanciation of inherited class
$pdf=new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);

	$pdf->Cell(0,10,'',1,1);
	$pdf->Cell(10,0,$work_order_notes,1,1);
$pdf->Output();	

	
} else {
	force_page('core', "error&menu=1&error_msg=No Printing Options set. Please set up printing options in the Control Center.&type=error");
	exit;
}


?>
