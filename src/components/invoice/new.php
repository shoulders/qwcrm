<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Create an invoice for the supplied workorder
if(isset(\CMSApplication::$VAR['workorder_id']) && \CMSApplication::$VAR['workorder_id'] && !$this->app->components->workorder->get_workorder_details(\CMSApplication::$VAR['workorder_id'], 'invoice_id')) {

    // Get client_id from the workorder    
    \CMSApplication::$VAR['client_id'] = $this->app->components->workorder->get_workorder_details(\CMSApplication::$VAR['workorder_id'], 'client_id');
    
    // Create the invoice and return the new invoice_id
    \CMSApplication::$VAR['invoice_id'] = $this->app->components->invoice->insert_invoice(\CMSApplication::$VAR['client_id'], \CMSApplication::$VAR['workorder_id'], $this->app->components->client->get_client_details(\CMSApplication::$VAR['client_id'], 'unit_discount_rate'));
    
    // Update the workorder with the new invoice_id
    $this->app->components->workorder->update_workorder_invoice_id(\CMSApplication::$VAR['workorder_id'], \CMSApplication::$VAR['invoice_id']);

    // Load the newly created invoice edit page
    $this->app->system->general->force_page('invoice', 'edit&invoice_id='.\CMSApplication::$VAR['invoice_id']);
    
} 

// Invoice only
if((isset(\CMSApplication::$VAR['client_id'], \CMSApplication::$VAR['invoice_type']) && \CMSApplication::$VAR['client_id'] && \CMSApplication::$VAR['invoice_type'] == 'invoice-only')) {
    
    // Create the invoice and return the new invoice_id
    \CMSApplication::$VAR['invoice_id'] = $this->app->components->invoice->insert_invoice(\CMSApplication::$VAR['client_id'], '', $this->app->components->client->get_client_details(\CMSApplication::$VAR['client_id'], 'unit_discount_rate'));

    // Load the newly created invoice edit page
    $this->app->system->general->force_page('invoice', 'edit&invoice_id='.\CMSApplication::$VAR['invoice_id']);
}    
  
// Fallback Error Control 
$this->app->system->general->force_page('workorder', 'search', 'msg_danger='._gettext("You cannot create an invoice by the method you just tried, report to admins."));
