<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

class Pdf extends System {
    
    /*
     * Local holder for the mPDF object
     */
    private $mpdf;
    
    /*
     * Processing Success flag;
     */
    private $success = true;

    /*
     * This is wrapper function whilst Pdf class is not autoloaded, when it is this should go in the contructore
     */
    private function getMpdf($pdf_template) {
               
        // mPDF Defines
        //define('_MPDF_TEMP_PATH', '../../common/tempfiles');         // Folders for temporary files
        //define('_MPDF_RRFONTDATAPATH', '../../common/tempfiles');    // if you wish to use a different folder for temporary files you should define this constant
        
        // Set mPDF configuration
        // https://mpdf.github.io/configuration/configuration-v7-x.html + All variables can be changed at runtime ($this->mpdf->SetTitle() etc...) or set in the constructor array ($mpdfConfig), see this link
        // Constructor Defaults are here: https://github.com/mpdf/mpdf/blob/development/src/Config/ConfigVariables.php + https://mpdf.github.io/reference/mpdf-functions/construct.html
        // Full defaults D:\websites\htdocs\projects\qwcrm\src\libraries\vendor\mpdf\mpdf\src\Config\ConfigVariables.php
        // $constructor from D:\websites\htdocs\projects\qwcrm\src\libraries\vendor\mpdf\mpdf\src\Mpdf.php
        // Debugging notes - https://mpdf.github.io/troubleshooting/corrupt-pdf-file.html
        /*Default Constructor which is merged with the user supplied one
         *$constructor = [
			'mode' => '',
			'format' => 'A4',
			'default_font_size' => 0,
			'default_font' => '',
			'margin_left' => 15,
			'margin_right' => 15,
			'margin_top' => 16,
			'margin_bottom' => 16,
			'margin_header' => 9,
			'margin_footer' => 9,
			'orientation' => 'P',
		];*/
        
        $mpdfConfig = [
            
            //'debug' => true,              // When enabled - this causes images to not load and throw exceptsion - https://github.com/mpdf/mpdf/issues/904   
            'tempDir' => TMP_DIR . 'mpdf'   // Folders for temporary files
		];
        
        // Build the PDF       
        try
        {          
            // Initialize mPDF    
            $this->mpdf = new \Mpdf\Mpdf($mpdfConfig);
                
            // Not needed when using full page import, should take it from the page - does not like parsing the header? not HTML5 compliant
            //$this->mpdf->SetTitle('My Title');

            // mPDF now supports setting curlAllowUnsafeSslRequests (prevents red crosses where images should be, when using https with old ROOT CA Store)
            $this->mpdf->curlAllowUnsafeSslRequests = true;
            
            // Set to true for optional error reporting for problems with Images.
            //$this->mpdf->showImageErrors = true;
        
            //$this->mpdf->setBasePath(QWCRM_FULL_URL);
            
            // Build the HTML payload
            $this->mpdf->WriteHTML($pdf_template);
        }        
        
        catch (\Mpdf\MpdfException $e)
        {               
            // Process the exception, log, print etc.
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("The PDF template has failed to build successfully."));
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("This is most likely an issue with the printing template."). ' <strong>`'.\CMSApplication::$VAR['component'].':'.\CMSApplication::$VAR['page_tpl'].':'.\CMSApplication::$VAR['commContent'].'`</strong>');
            $this->app->system->variables->systemMessagesWrite('danger', $e->getMessage());
            
            // Set process to failed
            $this->success = false;
        }
        
        return;
        
    }
    
    // Output a PDF in the browser
    public function mpdfOutputBrowser($pdf_filename, $pdf_template) {
        
        // Intialise mPDF
        $this->getMpdf($pdf_template);
            
        // If PDF Intialisation is successful
        if($this->success)
        {
            try
            {
                // Output the PDF to the browser (.pdf extension is automatically added)
                $this->mpdf->Output($pdf_filename, 'I');
            }
            
            catch (\Mpdf\MpdfException $e)
            {               
                // Process the exception, log, print etc.                
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("The PDF has failed to generate successfully."));
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("This is most likely an issue with the printing template."). ' <strong>`'.\CMSApplication::$VAR['component'].':'.\CMSApplication::$VAR['page_tpl'].':'.\CMSApplication::$VAR['commContent'].'`</strong>');
                $this->app->system->variables->systemMessagesWrite('danger', $e->getMessage());
                
                // Set process to failed
                $this->success = false;

            }
        }
        
        // Output based on success
        if($this->success)
        {
            return true;

        } else {         
            // Load 404 page with the error/system messages            
            die($this->app->system->page->loadPage('get_payload', 'core', '404', ''));
            //$this->app->system->page->force_error_page('file', __FILE__, __FUNCTION__, '', '', _gettext("Could not open the Setup Log to save the record."));
        }
        
    }
    
    // Output PDF as a downloadable file
    public function mpdfOutputDownload($pdf_filename, $pdf_template) {
        
        // Intialise mPDF
        $this->getMpdf($pdf_template);
            
        // If PDF Intialisation is successful
        if($this->success)
        {
            try
            {
                // Output the PDF to the browser (.pdf extension is automatically added)
                $this->mpdf->Output($pdf_filename, 'D');
            }
            
            catch (\Mpdf\MpdfException $e)
            {               
                // Process the exception, log, print etc.                
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("The PDF has failed to generate successfully."));
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("This is most likely an issue with the printing template."). ' <strong>`'.\CMSApplication::$VAR['component'].':'.\CMSApplication::$VAR['page_tpl'].':'.\CMSApplication::$VAR['commContent'].'`</strong>');
                $this->app->system->variables->systemMessagesWrite('danger', $e->getMessage());
                
                // Set process to failed
                $this->success = false;

            }
        }
        
        // Output based on success
        if($this->success)
        {
            return true;

        } else {         
            // Load 404 page with the error/system messages            
            die($this->app->system->page->loadPage('get_payload', 'core', '404', ''));            
        }
        
    }    

    // Return a PDF as a String
    public function mpdfOutputString($pdf_template) {
        
        // Intialise mPDF
        $this->getMpdf($pdf_template);
        
        // If PDF Intialisation is successful
        if($this->success)
        {            
            try
            {
                // Return the PDF as a string
                $pdfString = $this->mpdf->Output('FilenameIsIgnored', 'S');
            }
            
            catch (\Mpdf\MpdfException $e)
            {               
                // Process the exception, log, print etc.
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("The PDF has failed to generate successfully."));
                $this->app->system->variables->systemMessagesWrite('danger', _gettext("This is most likely an issue with the printing template."). ' <strong>`'.\CMSApplication::$VAR['component'].':'.\CMSApplication::$VAR['page_tpl'].':'.\CMSApplication::$VAR['commContent'].'`</strong>');
                $this->app->system->variables->systemMessagesWrite('danger', $e->getMessage());
                
                // Set process to failed
                $this->success = false;

            }

        }        
                    
        // Output based on success
        if($this->success)
        {
            return $pdfString;

        } else {               
            // Load error page with the messages via ajax // Output the system message to the browser (if allowed)
            $this->app->system->general->ajaxOutputSystemMessagesOnscreen();
            return false;
        }
            
    }
    
}