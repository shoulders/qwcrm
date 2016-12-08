<?php

/* Define mPDF Config */
//define('_MPDF_TEMP_PATH', '../../common/templfiles'); // Folders for temporary files
//define('_MPDF_RRFONTDATAPATH', '../../common/templfiles'); // if you wish to use a different folder for temporaary files you should define this constant

// Load Dependencies - something to do with Composer
require_once(LIBRARIES_DIR.'mpdf/vendor/autoload.php');

// Initialize mPDF
$mpdf = new mPDF('c');

//$mpdf->SetTitle('My Title');  //not needed when using full page import, should take it from the page - does not like parsing the header? not HTML5 compliant
$mpdf->WriteHTML($html);
$mpdf->Output(); // outputs to the screen
// $mpdf->Output('invoice.pdf'); outputs to a file but does not prompt
exit;