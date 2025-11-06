<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Check if we have an client_id
if(!isset(\CMSApplication::$VAR['client_id']) || !\CMSApplication::$VAR['client_id']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Client ID supplied."));
    $this->app->system->page->forcePage('client', 'search');
}

// Check the request is valid
if
(
    !isset(\CMSApplication::$VAR['commContent'], \CMSApplication::$VAR['commType']) &&
    !in_array(\CMSApplication::$VAR['commContent'], array('envelope')) ||
    !in_array(\CMSApplication::$VAR['commType'], array('htmlBrowser', 'pdfBrowser', 'pdfDownload'))
)
{
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("The print request is not valid."));
    $this->app->system->page->forcePage('client', 'search');
}

// Get Record Details
$client_details = $this->app->components->client->getRecord(\CMSApplication::$VAR['client_id']);

// Details
$this->app->smarty->assign('company_details', $this->app->components->company->getRecord());
$this->app->smarty->assign('client_details', $client_details);

// Client Envelope Print Routine
if(\CMSApplication::$VAR['commContent'] == 'envelope')
{
    $templateFile = 'client/printing/print_envelope.tpl';

    // Print HTML Client Envelope
    if (\CMSApplication::$VAR['commType'] == 'htmlBrowser')
    {
        $record = _gettext("Client Envelope").' '._gettext("for").' '.$client_details['display_name'].' '._gettext("has been printed as html.");
    }

}

// Log activity
$this->app->system->general->writeRecordToActivityLog($record, $this->app->user->login_user_id, $client_details['client_id']);

// Perform Communication Action - This also stops further processing (Logging currently done in this file, not this function which has an option for it)
$this->app->system->communication->performAction(\CMSApplication::$VAR['commType'], $templateFile, null, null, $client_details, $emailSubject ?? null, $emailBody ?? null);
