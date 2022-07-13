<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Check the request is valid
if
(
    !isset(\CMSApplication::$VAR['commContent'], \CMSApplication::$VAR['commType']) &&
    !in_array(\CMSApplication::$VAR['commContent'], array('technician_workorder_slip', 'client_workorder_slip', 'technician_job_sheet')) ||
    !in_array(\CMSApplication::$VAR['commType'], array('htmlBrowser', 'pdfBrowser', 'pdfDownload'))
)
{
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("The print request is not valid."));
    $this->app->system->page->forcePage('workorder', 'search');
}

// Printing Blank media is set
if(isset(\CMSApplication::$VAR['blankMedia']) && \CMSApplication::$VAR['blankMedia'] === 'true')
{
    $workorder_details = array(
                            'workorder_id' => null,
                            'scope' => '',
                            'description' => '',
                            'comment' => '',
                            'closed_on' => '',
                            'display_name' => '',
                            'resolution' => '',
                            'opened_on' => '',
                            'status' => '',
                            'last_active' => ''
                            );
    
    $client_details = array(
                            'display_name' => '',
                            'address' => '',
                            'city' => '',
                            'state' => '',
                            'zip' => '',
                            'country' => '',
                            'first_name' => '',
                            'last_name' => '',
                            'primary_phone' => '',
                            'mobile_phone' => '',
                            'fax' => '',
                            'email' => ''
                            );
    
    $company_details = array(
                            'company_name' => '',
                            'address' => '',
                            'city' => '',
                            'state' => '',
                            'zip' => '',
                            'country' => '',
                            'primary_phone' => '',
                            'mobile_phone' => '',
                            'fax' => '',
                            'website' => '',
                            'email' => ''
                            );
    
    $employee_details = array(
                            'display_name' => ''
                            );
    
    \CMSApplication::$VAR['workorder_id'] = '';
    $this->app->smarty->assign('date_format',          '');    
    $this->app->smarty->assign('company_details',      $company_details);
    $this->app->smarty->assign('employee_details',     $employee_details);
    $this->app->smarty->assign('client_details',       $client_details);
    $this->app->smarty->assign('workorder_details',    array());
    $this->app->smarty->assign('client_types',         array());
    $this->app->smarty->assign('workorder_details',    $workorder_details);        
    $this->app->smarty->assign('workorder_statuses',   array());
    $this->app->smarty->assign('workorder_notes',      array());
    $this->app->smarty->assign('workorder_schedules',  array());
    
    
} else {

    // Check if we have a workorder_id
    if(!isset(\CMSApplication::$VAR['workorder_id']) || !\CMSApplication::$VAR['workorder_id']) {
        $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Workorder ID supplied."));
        $this->app->system->page->forcePage('workorder', 'search');
    }
    
    // Get Record Details    
    $workorder_details  = $this->app->components->workorder->getRecord(\CMSApplication::$VAR['workorder_id']);
    $client_details = $this->app->components->client->getRecord($workorder_details['client_id']);

    // Assign Variables
    $this->app->smarty->assign('company_details',      $this->app->components->company->getRecord()                                        );
    $this->app->smarty->assign('employee_details',     $this->app->components->user->getRecord($workorder_details['employee_id'])          );
    $this->app->smarty->assign('client_details',       $client_details                                              );
    $this->app->smarty->assign('workorder_details',    $workorder_details                                           );
    $this->app->smarty->assign('client_types',         $this->app->components->client->getTypes()                                           );
    $this->app->smarty->assign('workorder_statuses',   $this->app->components->workorder->getStatuses()                                     );
    $this->app->smarty->assign('workorder_notes',      $this->app->components->workorder->getNotes(\CMSApplication::$VAR['workorder_id'])                );
    $this->app->smarty->assign('workorder_schedules',  $this->app->components->schedule->getRecords('schedule_id', 'DESC', 0, false, null, null, null, null, null, null, \CMSApplication::$VAR['workorder_id'])  );

}

// Technician Workorder Slip Print Routine
if(\CMSApplication::$VAR['commContent'] == 'technician_workorder_slip')
{    
    $templateFile = 'workorder/printing/print_technician_workorder_slip.tpl';
    $filename = _gettext("Technician Workorder Slip").' '.\CMSApplication::$VAR['workorder_id'];   // should i add pdf here? not if i offer different formats
    
    // Print HTML
    if (\CMSApplication::$VAR['commType'] == 'htmlBrowser')
    {        
        $record = _gettext("Technician Workorder Slip").' '.\CMSApplication::$VAR['workorder_id'].' '._gettext("has been printed as html."); 
    }
    
    // Print PDF (not currently used)
    if (\CMSApplication::$VAR['commType'] == 'pdfBrowser')
    {
        $record = _gettext("Technician Workorder Slip").' '.\CMSApplication::$VAR['workorder_id'].' '._gettext("has been printed as a PDF.");        
    }   
}

// Client Workorder Slip Print Routine
if(\CMSApplication::$VAR['commContent'] == 'client_workorder_slip')
{    
    $templateFile = 'workorder/printing/print_client_workorder_slip.tpl';
    $filename = _gettext("Client Workorder Slip").' '.\CMSApplication::$VAR['workorder_id'];  
    
    // Print HTML
    if (\CMSApplication::$VAR['commType'] == 'htmlBrowser')
    {
        $record = _gettext("Client Workorder Slip").' '.\CMSApplication::$VAR['workorder_id'].' '._gettext("has been printed as html.");
    }  
        
    // Print PDF (not currently used)
    if (\CMSApplication::$VAR['commType'] == 'pdfBrowser')
    {        
        $record = _gettext("Client Workorder Slip").' '.\CMSApplication::$VAR['workorder_id'].' '._gettext("has been printed as a PDF.");     
    }        
}

// Technician Job Sheet Print Routine
if(\CMSApplication::$VAR['commContent'] == 'technician_job_sheet')
{        
    $templateFile =  'workorder/printing/print_technician_job_sheet.tpl';
    $filename = _gettext("Technician Job Sheet").' '.\CMSApplication::$VAR['workorder_id'];    
    
    // Print HTML
    if (\CMSApplication::$VAR['commType'] == 'htmlBrowser')
    {
        $record = _gettext("Technician Job Sheet").' '.\CMSApplication::$VAR['workorder_id'].' '._gettext("has been printed as html.");
    }
                
    // Print PDF (not currently used)
    if (\CMSApplication::$VAR['commType'] == 'pdfBrowser')
    {
        $record = _gettext("Technician Job Sheet").' '.\CMSApplication::$VAR['workorder_id'].' '._gettext("has been printed as a PDF.");
    }
}

// Log activity - Not if printing blank pages
if(isset(\CMSApplication::$VAR['blankMedia']) && !\CMSApplication::$VAR['blankMedia']) {
    $this->app->system->general->writeRecordToActivityLog($record, $workorder_details['employee_id'], $workorder_details['client_id'], $workorder_details['workorder_id'], $workorder_details['invoice_id']);
}

// Perform Communication Action - This also stops further processing (Logging currently done in this file, not this function which has an option for it)
$this->app->system->communication->performAction(\CMSApplication::$VAR['commType'], $templateFile, null, $filename ?? null, $client_details ?? null, $emailSubject ?? null, $emailBody ?? null);
