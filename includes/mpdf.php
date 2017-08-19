<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Define mPDF Config
//define('_MPDF_TEMP_PATH', '../../common/templfiles'); // Folders for temporary files
//define('_MPDF_RRFONTDATAPATH', '../../common/templfiles'); // if you wish to use a different folder for temporaary files you should define this constant

// Load Dependencies - something to do with Composer
require_once(LIBRARIES_DIR.'mpdf/vendor/autoload.php');

// Output a PDF in the browser
function mpdf_output_in_browser($filename, $pdf_template) {
    
    // Initialize mPDF
    $mpdf = new mPDF('c');  // c = only use core fonts - https://mpdf.github.io/fonts-languages/fonts-in-mpdf-6-x.html

    //not needed when using full page import, should take it from the page - does not like parsing the header? not HTML5 compliant
    //$mpdf->SetTitle('My Title');

    // Build the PDF
    $mpdf->WriteHTML($pdf_template);
    
    // Output the PDF to the browser
    $mpdf->Output($filename, 'I');

    // I think this exit prevents issues
    exit;
    
}

// Return a PDF in a variable
function mpdf_output_as_varible($filename, $pdf_template) {
    
    // Initialize mPDF
    $mpdf = new mPDF('c');  // c = only use core fonts - https://mpdf.github.io/fonts-languages/fonts-in-mpdf-6-x.html

    // not needed when using full page import, should take it from the page - does not like parsing the header? not HTML5 compliant
    //$mpdf->SetTitle('My Title');
    
    // Output the PDF
    $mpdf->WriteHTML($pdf_template);
    
    // Return the PDF as a string
    return $mpdf->Output($filename, 'S');
    
}