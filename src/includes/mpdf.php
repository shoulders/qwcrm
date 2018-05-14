<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Define mPDF Config
//define('_MPDF_TEMP_PATH', '../../common/templfiles');         // Folders for temporary files
//define('_MPDF_RRFONTDATAPATH', '../../common/templfiles');    // if you wish to use a different folder for temporaary files you should define this constant

// Load Dependency Manually (Not needed because it is loaded by Composer)
//require_once(LIBRARIES_DIR.'mpdf/vendor/autoload.php');

// Output a PDF in the browser
function mpdf_output_in_browser($pdf_filename, $pdf_template) {
    
    // Add .pdf extension
    $pdf_filename .= '.pdf';

    // Initialize mPDF    
    $mpdf = new \Mpdf\Mpdf();
    
    // Not needed when using full page import, should take it from the page - does not like parsing the header? not HTML5 compliant
    //$mpdf->SetTitle('My Title');

    // Build the PDF
    $mpdf->WriteHTML($pdf_template);
    
    // Output the PDF to the browser
    $mpdf->Output($pdf_filename, 'I');

    // I think this exit prevents issues
    exit;
    
}

// Return a PDF in a variable
function mpdf_output_as_varible($pdf_filename, $pdf_template) {
    
    // Add .pdf extension
    $pdf_filename .= '.pdf';    

    // Initialize mPDF
    $mpdf = new \Mpdf\Mpdf();

    // not needed when using full page import, should take it from the page - does not like parsing the header? not HTML5 compliant
    //$mpdf->SetTitle('My Title');
    
    // Output the PDF
    $mpdf->WriteHTML($pdf_template);
    
    // Return the PDF as a string
    return $mpdf->Output($pdf_filename, 'S');
    
}