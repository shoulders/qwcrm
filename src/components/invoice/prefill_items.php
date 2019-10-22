<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'invoice.php');

// Prevent undefined variable errors
\QFactory::$VAR['empty_prefill_items_table'] = isset(\QFactory::$VAR['empty_prefill_items_table']) ? \QFactory::$VAR['empty_prefill_items_table'] : null;

// If the export of the invoice prefill items has been requested
if(isset(\QFactory::$VAR['export_invoice_prefill_items'])) {
    export_invoice_prefill_items_csv();
    die();
}

// if something submitted
if(isset(\QFactory::$VAR['submit'])) {

    // New invoice labour rates item
    if(\QFactory::$VAR['submit'] == 'new') {
        insert_invoice_prefill_item(\QFactory::$VAR['qform']);
    }    
    
    // Update invoice labour rates item
    if(\QFactory::$VAR['submit'] == 'update') {            
        update_invoice_prefill_item(\QFactory::$VAR['qform']);        
    }
    
    // Delete invoice labour rates item
    if(\QFactory::$VAR['submit'] == 'delete') {        
        delete_invoice_prefill_item(\QFactory::$VAR['qform']['invoice_prefill_id']);
    }
    
    // Upload CSV file of invoice labour rates items
    if(\QFactory::$VAR['submit'] == 'csv_upload') {
        upload_invoice_prefill_items_csv(\QFactory::$VAR['qform']);
    }
    
}

// Build Page
$smarty->assign('invoice_prefill_items', get_invoice_prefill_items());
