<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Generate the barcode (as html)
$bc_generator = new Picqer\Barcode\BarcodeGeneratorHTML();
$barcode = $bc_generator->getBarcode($this->app->components->voucher->getRecord(\CMSApplication::$VAR['voucher_id'], 'voucher_code'), $bc_generator::TYPE_CODE_128);

// Check if we have an voucher_id
if(!isset(\CMSApplication::$VAR['voucher_id']) || !\CMSApplication::$VAR['voucher_id']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Voucher ID supplied."));
    $this->app->system->page->forcePage('voucher', 'search');
}

// Check there is a print content and print type set
if(!isset(\CMSApplication::$VAR['print_content'], \CMSApplication::$VAR['print_type']) || !\CMSApplication::$VAR['print_content'] || !\CMSApplication::$VAR['print_type']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("Some or all of the Printing Options are not set."));
    $this->app->system->page->forcePage('voucher', 'search');
}

// Get Voucher details
$voucher_details = $this->app->components->voucher->getRecord(\CMSApplication::$VAR['voucher_id']);
$client_details = $this->app->components->client->getRecord($voucher_details['client_id']);

// Assign Variables
$this->app->smarty->assign('company_details',  $this->app->components->company->getRecord()       );
$this->app->smarty->assign('client_details',   $client_details             );
$this->app->smarty->assign('voucher_details', $voucher_details           );
$this->app->smarty->assign('barcode',          $barcode                    );

// Voucher Print Routine
if(\CMSApplication::$VAR['print_content'] == 'voucher')
{    
    $templateFile = 'voucher/printing/print_voucher.tpl';
    $filename = _gettext("Voucher").' '.\CMSApplication::$VAR['voucher_id'];
    
    // Print HTML
    if (\CMSApplication::$VAR['print_type'] == 'htmlBrowser')
    {
        $record = _gettext("Voucher").' '.\CMSApplication::$VAR['voucher_id'].' '._gettext("has been printed as html.");
    }
    
    // Print PDF
    if (\CMSApplication::$VAR['print_type'] == 'pdfBrowser')
    {        
       $record = _gettext("Voucher").' '.\CMSApplication::$VAR['voucher_id'].' '._gettext("has been printed as a PDF.");   
    }
    
    // Email PDF
    if (\CMSApplication::$VAR['print_type'] == 'pdfEmail')
    {
        $emailSubject = _gettext("Voucher").' '.\CMSApplication::$VAR['voucher_id'];
        $emailBody = $this->app->system->email->getEmailMessageBody('email_msg_voucher', $client_details);  // This message does not currently exist
        $record = _gettext("Voucher").' '.\CMSApplication::$VAR['voucher_id'].' '._gettext("has been emailed as a PDF.");        
    }
}

// Log activity
$this->app->system->general->writeRecordToActivityLog($record, $voucher_details['employee_id'], $voucher_details['client_id'], $voucher_details['workorder_id'], $voucher_details['invoice_id']);

// Perform Communication Action - This also stops further processing (Logging currently done in this file, not this function which has an option for it)
$this->app->system->communication->performAction(\CMSApplication::$VAR['print_type'], $templateFile, null, $filename ?? null, $client_details ?? null, $emailSubject ?? null, $emailBody ?? null);
