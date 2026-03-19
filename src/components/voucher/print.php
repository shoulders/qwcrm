<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Generate the barcode (as html)
$bc_generator = new Picqer\Barcode\BarcodeGeneratorSVG();
$voucher_barcode = $bc_generator->getBarcode($this->app->components->voucher->getRecord(\CMSApplication::$VAR['voucher_id'], 'voucher_code'), $bc_generator::TYPE_CODE_128);

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
    !in_array(\CMSApplication::$VAR['commType'], array('htmlBrowser', 'pdfBrowser'))
)
{
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("The print request is not valid."));
    $this->app->system->page->forcePage('voucher', 'search');
}

// Get Voucher details
$voucher_details = $this->app->components->voucher->getRecord(\CMSApplication::$VAR['voucher_id']);
$client_details = $this->app->components->client->getRecord($voucher_details['client_id']);

// Assign Variables
$this->app->smarty->assign('company_details',  $this->app->components->company->getRecord()       );
$this->app->smarty->assign('client_details',   $client_details             );
$this->app->smarty->assign('voucher_details', $voucher_details           );
$this->app->smarty->assign('voucher_barcode',  $voucher_barcode                    );

// Voucher Print Routine
if(\CMSApplication::$VAR['commContent'] == 'voucher')
{
    $templateFile = 'voucher/printing/print_voucher.tpl';
    $filename = _gettext("Voucher").' '.\CMSApplication::$VAR['voucher_id'];

    // Print HTML
    if (\CMSApplication::$VAR['commType'] == 'htmlBrowser')
    {
        $logMessage = _gettext("Voucher").' '.\CMSApplication::$VAR['voucher_id'].' '._gettext("has been printed as html.");
    }

    // Print PDF (not currently used)
    if (\CMSApplication::$VAR['commType'] == 'pdfBrowser')
    {
       $logMessage = _gettext("Voucher").' '.\CMSApplication::$VAR['voucher_id'].' '._gettext("has been printed as a PDF.");
    }
}

// Log activity
$recordIds = array('employee_id' => $this->app->user->login_user_id, 'client_id' => $voucher_details['client_id'], 'workorder_id' => $voucher_details['workorder_id'], 'invoice_id' => $voucher_details['invoice_id'], $voucher_details['voucher_id'] => $voucher_id);
$this->app->system->general->writeRecordToActivityLog($logMessage, $recordIds);

// Perform Communication Action - This also stops further processing (Logging currently done in this file, not this function which has an option for it)
$this->app->system->communication->performAction(\CMSApplication::$VAR['commType'], $templateFile, null, $filename ?? null, $client_details ?? null, $emailSubject ?? null, $emailBody ?? null);
