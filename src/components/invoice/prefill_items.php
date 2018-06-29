<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'components/invoice.php');

// Prevent undefined variable errors
$VAR['empty_prefill_items_table'] = isset($VAR['empty_prefill_items_table']) ? $VAR['empty_prefill_items_table'] : null;

// If the export of the invoice prefill items has been requested
if(isset($VAR['export_invoice_prefill_items'])) {
    export_invoice_prefill_items_csv();
    die();
}

// if something submitted
if(isset($VAR['submit'])) {

    // New invoice labour rates item
    if($VAR['submit'] == 'new') {
        insert_invoice_prefill_item($VAR);
    }    
    
    // Update invoice labour rates item
    if($VAR['submit'] == 'update') {            
        update_invoice_prefill_item($VAR);        
    }
    
    // Delete invoice labour rates item
    if($VAR['submit'] == 'delete') {        
        delete_invoice_prefill_item($VAR['invoice_prefill_id']);
    }
    
    // Upload CSV file of invoice labour rates items
    if($VAR['submit'] == 'csv_upload') {
        upload_invoice_prefill_items_csv($VAR);
    }
    
}

// Build Page
$smarty->assign('invoice_prefill_items', get_invoice_prefill_items());
$BuildPage .= $smarty->fetch('invoice/prefill_items.tpl');