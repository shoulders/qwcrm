<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Create an invoice for the supplied workorder
if(isset(\CMSApplication::$VAR['workorder_id']) && \CMSApplication::$VAR['workorder_id'] && !$this->app->components->workorder->getRecord(\CMSApplication::$VAR['workorder_id'], 'invoice_id')) {

    // Get client_id from the workorder    
    \CMSApplication::$VAR['client_id'] = $this->app->components->workorder->getRecord(\CMSApplication::$VAR['workorder_id'], 'client_id');
    
    // Create the invoice and return the new invoice_id
    \CMSApplication::$VAR['invoice_id'] = $this->app->components->invoice->insertRecord(\CMSApplication::$VAR['client_id'], \CMSApplication::$VAR['workorder_id'], $this->app->components->client->getRecord(\CMSApplication::$VAR['client_id'], 'unit_discount_rate'));
    
    // Update the workorder with the new invoice_id
    $this->app->components->workorder->updateInvoiceId(\CMSApplication::$VAR['workorder_id'], \CMSApplication::$VAR['invoice_id']);

    // Load the newly created invoice edit page
    $this->app->system->page->forcePage('invoice', 'edit&invoice_id='.\CMSApplication::$VAR['invoice_id']);
    
} 

// Invoice only
if((isset(\CMSApplication::$VAR['client_id'], \CMSApplication::$VAR['invoice_type']) && \CMSApplication::$VAR['client_id'] && \CMSApplication::$VAR['invoice_type'] == 'invoice-only')) {
    
    // Create the invoice and return the new invoice_id
    \CMSApplication::$VAR['invoice_id'] = $this->app->components->invoice->insertRecord(\CMSApplication::$VAR['client_id'], '', $this->app->components->client->getRecord(\CMSApplication::$VAR['client_id'], 'unit_discount_rate'));

    // Load the newly created invoice edit page
    $this->app->system->page->forcePage('invoice', 'edit&invoice_id='.\CMSApplication::$VAR['invoice_id']);
}    
  
// Fallback Error Control 
$this->app->system->page->forcePage('workorder', 'search', 'msg_danger='._gettext("You cannot create an invoice by the method you just tried, report to admins."));
