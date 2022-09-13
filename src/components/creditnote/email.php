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

// Check if we have an client_id
if(!isset(\CMSApplication::$VAR['client_id']) || !\CMSApplication::$VAR['cliwnt_id']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Client ID supplied."));
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
$client_details = $this->app->components->client->getRecord($creditnote_details['client_id']);

// Details
$this->app->smarty->assign('company_details',                  $this->app->components->company->getRecord()                                      );
$this->app->smarty->assign('client_details',                   $client_details                                            );
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
        $emailBody = $this->app->system->email->getEmailMessageBody('creditnote', $client_details['client_id']);
        $record = _gettext("Credit Note").' '.\CMSApplication::$VAR['creditnote_id'].' '._gettext("has been emailed as a PDF.");       
    }
  
}

// Log activity
$this->app->system->general->writeRecordToActivityLog($record, $creditnote_details['employee_id'], $creditnote_details['client_id'], null, $creditnote_details['invoice_id']);

// Perform Communication Action - This also stops further processing (Logging currently done in this file, not this function which has an option for it)
$this->app->system->communication->performAction(\CMSApplication::$VAR['commType'], $templateFile, null, $filename ?? null, $client_details ?? null, $emailSubject ?? null, $emailBody ?? null);
