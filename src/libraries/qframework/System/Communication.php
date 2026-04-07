<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

/*
 * Mandatory Code - Code that is run upon the file being loaded
 * Display Functions - Code that is used to primarily display records
 * New/Insert Functions - Creation of new records
 * Get Functions - Grabs specific records/fields ready for update
 * Update Functions - For updating records/fields
 * Close Functions - Closing Work Orders code
 * Delete Functions - Deleting Work Orders
 * Other Functions - All other functions not covered above
 */

defined('_QWEXEC') or die;

class Communication extends System {

    private $templatePayload = '';
    private $logMessage = null;
    private $filename = null;
    private $recipient_details = null;
    private $emailSubject = null;
    private $emailBody = null;

    // this might need renaming - This loads a user configarble template (ie invoice) and they are a complete page
    public function performAction($action, $templateFile = null, $logMessage = null, $filename = null, array $recipient_details = null, $emailSubject = null, $emailBody = null)
    {
        // Load Class Variables
        $this->templatePayload = $this->app->smarty->fetch($templateFile);
        $this->logMessage = $logMessage;
        $this->filename = $filename;
        $this->recipient_details = $recipient_details;
        $this->emailSubject = $emailSubject;
        $this->emailBody = $emailBody;

        // Check Communication Action exists
        if(!method_exists($this, $action))
        {
            // Invalid Communication Action - Return to the page sending page
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("Invalid Communication Action."));

            // Load 404 page with the error/system messages
            die($this->app->system->page->loadPage('get_payload', 'core', '404', ''));
        }

        // Run Communication Action
        if($this->$action())
        {
           // Log Activity - onyl trigger if a log message is passes, but currently nothing does pass this
            if($this->$logMessage)
            {
                $recordIds = $recipient_details;
                $this->app->system->general->writeRecordToActivityLog($logMessage, $recordIds);
                $this->app->system->general->updateLastActive($recordIds);
            }
        }

        // All Actions Completed successfully
        die();

    }

    // Render the page as normal
    private function htmlBrowser()
    {
        echo $this->templatePayload;
    }

    // Output PDF in browser
    private function pdfBrowser()
    {
        $this->app->system->pdf->mpdfOutputBrowser($this->filename, $this->templatePayload);
    }

    // Output PDF as a downloadable file
    private function pdfDownload()
    {
        $this->app->system->pdf->mpdfOutputDownload($this->filename, $this->templatePayload);
    }

    // Email PDF as an attachment
    private function pdfEmail()
    {
        // Get the PDF in as a string
        $pdf_as_string = $this->app->system->pdf->mpdfOutputString($this->templatePayload);

        // Build and Send email
        if($pdf_as_string)
        {
            // Build the PDF Attachment
            $attachments = array();
            $attachment['data'] = $pdf_as_string;
            $attachment['filename'] = $this->filename.'.pdf';
            $attachment['contentType'] = 'application/pdf';
            $attachments[] = $attachment;

            // Email the PDF
            $this->app->system->email->send($this->recipient_details['email'], $this->emailSubject, $this->emailBody, $this->recipient_details['display_name'], $attachments);

            // End all other processing
            die();

        } else {
            // Fallback
            die(_gettext("Failed to Email asset."));
        }
    }

}
