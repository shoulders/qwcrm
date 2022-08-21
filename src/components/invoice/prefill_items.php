<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Prevent undefined variable errors
\CMSApplication::$VAR['empty_prefill_items_table'] = \CMSApplication::$VAR['empty_prefill_items_table'] ?? null;

// If the export of the invoice prefill items has been requested
if(isset(\CMSApplication::$VAR['export_invoice_prefill_items'])) {
    $this->app->components->invoice->exportPrefillItemsCsv();
    die();
}

// if something submitted
if(isset(\CMSApplication::$VAR['submit'])) {

    // If the export of the invoice prefill items has been requested
    if(\CMSApplication::$VAR['submit'] == 'export') {
        $this->app->components->invoice->exportPrefillItemsCsv();
        die();
    }
    
    // New invoice prefill item
    if(\CMSApplication::$VAR['submit'] == 'submit') {
        $this->app->components->invoice->insertPrefillItems(\CMSApplication::$VAR['qform']['prefill_items']);
    }    
    
    // Upload CSV file of prefill items
    if(\CMSApplication::$VAR['submit'] == 'import') {
        $this->app->components->invoice->uploadPrefillItemsCsv(\CMSApplication::$VAR['empty_prefill_items_table']);
    }
    
}

// Build Page
$this->app->smarty->assign('invoice_prefill_items_json', json_encode($this->app->components->invoice->getPrefillItems()));
