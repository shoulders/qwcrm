<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Check if we have an voucher_id
if(!isset(\CMSApplication::$VAR['voucher_id']) || !\CMSApplication::$VAR['voucher_id']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Voucher ID supplied."));
    $this->app->system->page->forcePage('voucher', 'search');
}

// Check the request is valid
if
(
    !isset(\CMSApplication::$VAR['commContent'], \CMSApplication::$VAR['commType']) &&
    !in_array(\CMSApplication::$VAR['commContent'], array('voucher')) ||
    !in_array(\CMSApplication::$VAR['commType'], array('pdfEmail'))
)
{
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("The email request is not valid."));
    $this->app->system->page->forcePage('voucher', 'search');
}

// Generate the barcode (as html)
$bc_generator = new Picqer\Barcode\BarcodeGeneratorSVG();
$voucher_barcode = $bc_generator->getBarcode($this->app->components->voucher->getRecord(\CMSApplication::$VAR['voucher_id'], 'voucher_code'), $bc_generator::TYPE_CODE_128);

// Get details
$company_details = $this->app->components->company->getRecord();
$voucher_details = $this->app->components->voucher->getRecord(\CMSApplication::$VAR['voucher_id']);
$client_details = $this->app->components->client->getRecord($voucher_details['client_id']);

// Assign Variables
$this->app->smarty->assign('company_details',  $company_details       );
$this->app->smarty->assign('client_details',   $client_details             );
$this->app->smarty->assign('voucher_details', $voucher_details           );
$this->app->smarty->assign('voucher_barcode', $voucher_barcode                    );

// Voucher Print Routine
if(\CMSApplication::$VAR['commContent'] == 'voucher')
{    
    $templateFile = 'voucher/printing/print_voucher.tpl';
    $filename = $company_details['company_name'].' '._gettext("Voucher");
    
    // Email PDF
    if (\CMSApplication::$VAR['commType'] == 'pdfEmail')
    {
        $emailSubject = _gettext("Voucher");
        $emailBody = $this->app->system->email->getEmailMessageBody('voucher', $client_details['client_id']);  // This message does not currently exist
        $record = _gettext("Voucher").' '.\CMSApplication::$VAR['voucher_id'].' '._gettext("has been emailed as a PDF.");        
    }
}

// Log activity
$this->app->system->general->writeRecordToActivityLog($record, $voucher_details['employee_id'], $voucher_details['client_id'], $voucher_details['workorder_id'], $voucher_details['invoice_id']);

// Perform Communication Action - This also stops further processing (Logging currently done in this file, not this function which has an option for it)
$this->app->system->communication->performAction(\CMSApplication::$VAR['commType'], $templateFile, null, $filename ?? null, $client_details ?? null, $emailSubject ?? null, $emailBody ?? null);
