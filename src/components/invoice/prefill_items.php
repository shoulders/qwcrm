<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(CINCLUDES_DIR.'invoice.php');

// Prevent undefined variable errors
\CMSApplication::$VAR['empty_prefill_items_table'] = isset(\CMSApplication::$VAR['empty_prefill_items_table']) ? \CMSApplication::$VAR['empty_prefill_items_table'] : null;

// If the export of the invoice prefill items has been requested
if(isset(\CMSApplication::$VAR['export_invoice_prefill_items'])) {
    export_invoice_prefill_items_csv();
    die();
}

// if something submitted
if(isset(\CMSApplication::$VAR['submit'])) {

    // New invoice labour rates item
    if(\CMSApplication::$VAR['submit'] == 'new') {
        insert_invoice_prefill_item(\CMSApplication::$VAR['qform']);
    }    
    
    // Update invoice labour rates item
    if(\CMSApplication::$VAR['submit'] == 'update') {            
        update_invoice_prefill_item(\CMSApplication::$VAR['qform']);        
    }
    
    // Delete invoice labour rates item
    if(\CMSApplication::$VAR['submit'] == 'delete') {        
        delete_invoice_prefill_item(\CMSApplication::$VAR['qform']['invoice_prefill_id']);
    }
    
    // Upload CSV file of invoice labour rates items
    if(\CMSApplication::$VAR['submit'] == 'csv_upload') {
        upload_invoice_prefill_items_csv(\CMSApplication::$VAR['qform']);
    }
    
}

// Build Page
$smarty->assign('invoice_prefill_items', get_invoice_prefill_items());
