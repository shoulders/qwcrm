<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Prevent undefined variable errors
\CMSApplication::$VAR['empty_prefill_items_table'] = isset(\CMSApplication::$VAR['empty_prefill_items_table']) ? \CMSApplication::$VAR['empty_prefill_items_table'] : null;

// If the export of the invoice prefill items has been requested
if(isset(\CMSApplication::$VAR['export_invoice_prefill_items'])) {
    $this->app->components->invoice->export_invoice_prefill_items_csv();
    die();
}

// if something submitted
if(isset(\CMSApplication::$VAR['submit'])) {

    // New invoice labour rates item
    if(\CMSApplication::$VAR['submit'] == 'new') {
        $this->app->components->invoice->insert_invoice_prefill_item(\CMSApplication::$VAR['qform']);
    }    
    
    // Update invoice labour rates item
    if(\CMSApplication::$VAR['submit'] == 'update') {            
        $this->app->components->invoice->update_invoice_prefill_item(\CMSApplication::$VAR['qform']);        
    }
    
    // Delete invoice labour rates item
    if(\CMSApplication::$VAR['submit'] == 'delete') {        
        $this->app->components->invoice->delete_invoice_prefill_item(\CMSApplication::$VAR['qform']['invoice_prefill_id']);
    }
    
    // Upload CSV file of invoice labour rates items
    if(\CMSApplication::$VAR['submit'] == 'csv_upload') {
        $this->app->components->invoice->upload_invoice_prefill_items_csv(\CMSApplication::$VAR['qform']);
    }
    
}

// Build Page
$this->app->smarty->assign('invoice_prefill_items', $this->app->components->invoice->get_invoice_prefill_items());
