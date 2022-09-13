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
    !in_array(\CMSApplication::$VAR['commContent'], array('creditnote', 'client_envelope')) ||
    !in_array(\CMSApplication::$VAR['commType'], array('htmlBrowser', 'pdfBrowser', 'pdfDownload'))
)
{
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("The print request is not valid."));
    $this->app->system->page->forcePage('creditnote', 'search');
}

// Get Record Details
$creditnote_details = $this->app->components->creditnote->getRecord(\CMSApplication::$VAR['creditnote_id']);
$client_details = $this->app->components->client->getRecord($creditnote_details['client_id']);

// Details
$this->app->smarty->assign('company_details',                  $this->app->components->company->getRecord()                                      );
$this->app->smarty->assign('client_details',                   $client_details                                            );
$this->app->smarty->assign('creditnote_details',               $creditnote_details                                           );
$this->app->smarty->assign('creditnote_items',                 $this->app->components->creditnote->getItems(\CMSApplication::$VAR['creditnote_id'])               );

// Prefill Items
$this->app->smarty->assign('vat_tax_codes',                    $this->app->components->company->getVatTaxCodes(false)                                                               );

// Misc
$this->app->smarty->assign('creditnote_statuses',                 $this->app->components->creditnote->getStatuses()                                     );
$this->app->smarty->assign('creditnote_footer_msg',               $this->app->components->payment->getOptions('creditnote_footer_msg'));

// Invoice Print Routine
if(\CMSApplication::$VAR['commContent'] == 'creditnote')
{    
    $templateFile = 'creditnote/printing/print_creditnote.tpl';
    $filename = _gettext("Invoice").' '.\CMSApplication::$VAR['creditnote_id'];
    
    // Print HTML Invoice
    if (\CMSApplication::$VAR['commType'] == 'htmlBrowser')
    {        
        $record = _gettext("Invoice").' '.\CMSApplication::$VAR['creditnote_id'].' '._gettext("has been printed as html.");       
    }
    
    // Print PDF Invoice
    if (\CMSApplication::$VAR['commType'] == 'pdfBrowser')
    {        
        $record = _gettext("Invoice").' '.\CMSApplication::$VAR['creditnote_id'].' '._gettext("has been printed as a PDF.");
    } 
    
    // Download PDF Invoice
    if (\CMSApplication::$VAR['commType'] == 'pdfDownload')
    {        
        $record = _gettext("Invoice").' '.\CMSApplication::$VAR['creditnote_id'].' '._gettext("has been dowloaded as a PDF.");      
    } 
    
}

// Client Envelope Print Routine
if(\CMSApplication::$VAR['commContent'] == 'client_envelope')
{    
    $templateFile = 'client/printing/print_client_envelope.tpl';
    $filename = _gettext("Invoice Envelope").' '.\CMSApplication::$VAR['creditnote_id'];
    
    // Print HTML Client Envelope
    if (\CMSApplication::$VAR['commType'] == 'htmlBrowser')
    {        
        $record = _gettext("Invoice Envelope").' '.\CMSApplication::$VAR['creditnote_id'].' '._gettext("for").' '.$client_details['display_name'].' '._gettext("has been printed as html.");
    }    
}

// Log activity
$this->app->system->general->writeRecordToActivityLog($record, $creditnote_details['employee_id'], $creditnote_details['client_id'], null, $creditnote_details['invoice_id']);

// Perform Communication Action - This also stops further processing (Logging currently done in this file, not this function which has an option for it)
$this->app->system->communication->performAction(\CMSApplication::$VAR['commType'], $templateFile, null, $filename ?? null, $client_details ?? null, $emailSubject ?? null, $emailBody ?? null);
