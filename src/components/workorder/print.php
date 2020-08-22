<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Check if we have a workorder_id
if(!isset(\CMSApplication::$VAR['workorder_id']) || !\CMSApplication::$VAR['workorder_id']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Workorder ID supplied."));
    $this->app->system->page->forcePage('workorder', 'search');
}

// Check there is a print content and print type set
if(!isset(\CMSApplication::$VAR['print_content'], \CMSApplication::$VAR['print_type']) || !\CMSApplication::$VAR['print_content'] || !\CMSApplication::$VAR['print_type']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("Some or all of the Printing Options are not set."));
    $this->app->system->page->forcePage('workorder', 'search');
}

// Get Record Details
$workorder_details  = $this->app->components->workorder->getRecord(\CMSApplication::$VAR['workorder_id']);
$client_details   = $this->app->components->client->getRecord($workorder_details['client_id']);

/// Assign Variables
$this->app->smarty->assign('company_details',      $this->app->components->company->getRecord()                                        );
$this->app->smarty->assign('employee_details',     $this->app->components->user->getRecord($workorder_details['employee_id'])          );
$this->app->smarty->assign('client_details',       $client_details                                              );
$this->app->smarty->assign('workorder_details',    $workorder_details                                           );
$this->app->smarty->assign('client_types',         $this->app->components->client->getTypes()                                           );
$this->app->smarty->assign('workorder_statuses',   $this->app->components->workorder->getStatuses()                                     );
$this->app->smarty->assign('workorder_notes',      $this->app->components->workorder->getNotes(\CMSApplication::$VAR['workorder_id'])                );
$this->app->smarty->assign('workorder_schedules',  $this->app->components->schedule->getRecords('schedule_id', 'DESC', false, null, null, null, null, null, null, null, \CMSApplication::$VAR['workorder_id'])  );

// Technician Workorder Slip Print Routine
if(\CMSApplication::$VAR['print_content'] == 'technician_workorder_slip')
{    
    $templateFile = 'workorder/printing/print_technician_workorder_slip.tpl';
    $filename = _gettext("Technician-Workorder-Slip").'-'.\CMSApplication::$VAR['workorder_id'];   // should i add pdf here? not if i offer different formats
    
    // Print HTML
    if (\CMSApplication::$VAR['print_type'] == 'htmlBrowser')
    {        
        $record = _gettext("Technician Workorder Slip").' '.\CMSApplication::$VAR['workorder_id'].' '._gettext("has been printed as html."); 
    }
    
    // Print PDF
    if (\CMSApplication::$VAR['print_type'] == 'pdfBrowser')
    {
        $record = _gettext("Technician Workorder Slip").' '.\CMSApplication::$VAR['workorder_id'].' '._gettext("has been printed as a PDF.");        
    }
    
    // Email PDF
    if (\CMSApplication::$VAR['print_type'] == 'pdfEmail')
    {        
        $emailSubject =  _gettext("Work Order").' '.\CMSApplication::$VAR['workorder_id'];
        $emailBody = $this->app->system->email->getEmailMessageBody('email_msg_workorder', $client_details);
        $record = _gettext("Technician Workorder Slip").' '.\CMSApplication::$VAR['workorder_id'].' '._gettext("has been emailed as a PDF to the client.");
    }    
}

// Client Workorder Slip Print Routine
if(\CMSApplication::$VAR['print_content'] == 'client_workorder_slip')
{    
    $templateFile = 'workorder/printing/print_client_workorder_slip.tpl';
    $filename = _gettext("Client-Workorder-Slip").'-'.\CMSApplication::$VAR['workorder_id'];  
    
    // Print HTML
    if (\CMSApplication::$VAR['print_type'] == 'htmlBrowser')
    {
        $record = _gettext("Client Workorder Slip").' '.\CMSApplication::$VAR['workorder_id'].' '._gettext("has been printed as html.");
    }  
        
    // Print PDF
    if (\CMSApplication::$VAR['print_type'] == 'pdfBrowser')
    {        
        $record = _gettext("Client Workorder Slip").' '.\CMSApplication::$VAR['workorder_id'].' '._gettext("has been printed as a PDF.");     
    }        
        
    // Email PDF
    if (\CMSApplication::$VAR['print_type'] == 'pdfEmail')
    { 
        $emailSubject = _gettext("Work Order").' '.\CMSApplication::$VAR['workorder_id'];
        $emailBody = $this->app->system->email->getEmailMessageBody('email_msg_workorder', $client_details);
        $record = _gettext("Client Workorder Slip").' '.\CMSApplication::$VAR['workorder_id'].' '._gettext("has been emailed as a PDF.");   
    }
}

// Technician Job Sheet Print Routine
if(\CMSApplication::$VAR['print_content'] == 'technician_job_sheet')
{        
    $templateFile =  'workorder/printing/print_technician_job_sheet.tpl';
    $filename = _gettext("Technician-Job-Sheet").'-'.\CMSApplication::$VAR['workorder_id'];    
    
    // Print HTML
    if (\CMSApplication::$VAR['print_type'] == 'htmlBrowser')
    {
        $record = _gettext("Technician Job Sheet").' '.\CMSApplication::$VAR['workorder_id'].' '._gettext("has been printed as html.");
    }
                
    // Print PDF
    if (\CMSApplication::$VAR['print_type'] == 'pdfBrowser')
    {
        $record = _gettext("Technician Job Sheet").' '.\CMSApplication::$VAR['workorder_id'].' '._gettext("has been printed as a PDF.");
    }
        
    // Email PDF
    if (\CMSApplication::$VAR['print_type'] == 'pdfEmail')
    {        
        $emailSubject = _gettext("Work Order").' '.\CMSApplication::$VAR['workorder_id'];
        $emailBody = $this->app->system->email->getEmailMessageBody('email_msg_workorder', $client_details);
        $record = _gettext("Technician Job Sheet").' '.\CMSApplication::$VAR['workorder_id'].' '._gettext("has been emailed as a PDF.");
    }
}

// Log activity
$this->app->system->general->writeRecordToActivityLog($record, $workorder_details['employee_id'], $workorder_details['client_id'], $workorder_details['workorder_id'], $workorder_details['invoice_id']);

// Perform Communication Action - This also stops further processing (Logging currently done in this file, not this function which has an option for it)
$this->app->system->communication->performAction(\CMSApplication::$VAR['print_type'], $templateFile, null, $filename ?? null, $client_details ?? null, $emailSubject ?? null, $emailBody ?? null);
