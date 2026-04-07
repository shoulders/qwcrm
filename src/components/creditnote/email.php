<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Check if we have an creditnote_id
if(!isset(\CMSApplication::$VAR['creditnote_id']) || !\CMSApplication::$VAR['creditnote_id']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Credit Note ID supplied."));
    $this->app->system->page->forcePage('creditnote', 'search');
}

// Check the request is valid
if
(
    !isset(\CMSApplication::$VAR['commContent'], \CMSApplication::$VAR['commType']) &&
    !in_array(\CMSApplication::$VAR['commContent'], array('creditnote')) ||
    !in_array(\CMSApplication::$VAR['commType'], array('pdfEmail'))
)
{
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("The email request is not valid."));
    $this->app->system->page->forcePage('creditnote', 'search');
}

// Get Record Details
$creditnote_details = $this->app->components->creditnote->getRecord(\CMSApplication::$VAR['creditnote_id']);

$client_details = $creditnote_details['client_id'] ? $this->app->components->client->getRecord($creditnote_details['client_id']) : null;
$supplier_details = $creditnote_details['supplier_id'] ? $this->app->components->supplier->getRecord($creditnote_details['supplier_id']) : null;
$cr_owner_details = $client_details ?? $supplier_details;

// Details
$this->app->smarty->assign('company_details',                  $this->app->components->company->getRecord()                                      );
$this->app->smarty->assign('cr_owner_details',                   $cr_owner_details                                           );
$this->app->smarty->assign('creditnote_details',                  $creditnote_details                                           );
$this->app->smarty->assign('creditnote_items',                     $this->app->components->creditnote->getItems(\CMSApplication::$VAR['creditnote_id'])               );

// Misc
$this->app->smarty->assign('employee_display_name',            $this->app->components->user->getRecord($creditnote_details['employee_id'], 'display_name')  );
$this->app->smarty->assign('creditnote_statuses',                 $this->app->components->creditnote->getStatuses()                                     );
$this->app->smarty->assign('vat_tax_codes',                    $this->app->components->company->getVatTaxCodes(false)                                                               );
$this->app->smarty->assign('creditnote_footer_msg',               $this->app->components->payment->getOptions('creditnote_footer_msg'));

// Credit Note Email Routine
if(\CMSApplication::$VAR['commContent'] == 'creditnote')
{
    $templateFile = 'creditnote/printing/print_creditnote.tpl';
    $filename = _gettext("Credit Note").' '.\CMSApplication::$VAR['creditnote_id'];

    // Email PDF Credit Note
    if(\CMSApplication::$VAR['commType'] == 'pdfEmail')
    {
        $emailSubject = _gettext("Credit Note").' '.\CMSApplication::$VAR['creditnote_id'];
        $emailBody = $this->app->system->email->getEmailMessageBody('creditnote', $cr_owner_details['client_id'] ?? $cr_owner_details['supplier_id']);
        $logMessage = _gettext("Credit Note").' '.\CMSApplication::$VAR['creditnote_id'].' '._gettext("has been emailed as a PDF.");
    }

}

// Log activity
//$recordIds = array('employee_id' => $this->app->user->login_user_id, 'client_id' => $creditnote_details['client_id'], 'invoice_id' => $creditnote_details['invoice_id'], 'supplier_id' => $creditnote_details['supplier_id'], 'expense_id' => $creditnote_details['expense_id']);
$recordIds = $cr_owner_details;
$this->app->system->general->writeRecordToActivityLog($logMessage, $recordIds);

// Perform Communication Action - This also stops further processing (Logging currently done in this file, not this function which has an option for it)
$this->app->system->communication->performAction(\CMSApplication::$VAR['commType'], $templateFile, null, $filename ?? null, $cr_owner_details, $emailSubject, $emailBody);
