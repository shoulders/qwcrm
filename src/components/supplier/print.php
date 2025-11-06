<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Check if we have an supplier_id
if(!isset(\CMSApplication::$VAR['supplier_id']) || !\CMSApplication::$VAR['supplier_id']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Supplier ID supplied."));
    $this->app->system->page->forcePage('supplier', 'search');
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
    $this->app->system->page->forcePage('supplier', 'search');
}

// Get Record Details
$supplier_details = $this->app->components->supplier->getRecord(\CMSApplication::$VAR['supplier_id']);

// Details
$this->app->smarty->assign('company_details', $this->app->components->company->getRecord());
$this->app->smarty->assign('supplier_details', $supplier_details);

// supplier Envelope Print Routine
if(\CMSApplication::$VAR['commContent'] == 'envelope')
{
    $templateFile = 'supplier/printing/print_envelope.tpl';

    // Print HTML supplier Envelope
    if (\CMSApplication::$VAR['commType'] == 'htmlBrowser')
    {
        $record = _gettext("supplier Envelope").' '._gettext("for").' '.$supplier_details['display_name'].' '._gettext("has been printed as html.");
    }

}

// Log activity
$this->app->system->general->writeRecordToActivityLog($record, $this->app->user->login_user_id, $supplier_details['supplier_id']);

// Perform Communication Action - This also stops further processing (Logging currently done in this file, not this function which has an option for it)
$this->app->system->communication->performAction(\CMSApplication::$VAR['commType'], $templateFile, null, null, $supplier_details, $emailSubject ?? null, $emailBody ?? null);
