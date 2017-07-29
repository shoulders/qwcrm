<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/invoice.php');

// if something submitted
if(isset($VAR['submit'])) {

    // New invoice labour rates item
    if($VAR['submit'] == 'new') {
        insert_invoice_labour_rates_item($db, $VAR);
    }    
    
    // Update invoice labour rates item
    if($VAR['submit'] == 'update') {            
        update_invoice_labour_rates_item($db, $VAR['labour_rate_id'], $VAR);        
    }
    
    // Delete invoice labour rates item
    if($VAR['submit'] == 'delete') {        
        delete_invoice_rates_item($db, $VAR['labour_rate_id']);
    }
    
    // Upload CSV file of invoice labour rates items
    if($VAR['submit'] == 'csv_upload') {
        upload_invoice_labour_rates_csv($db, $VAR);
    }
    
}

// Build Page
$smarty->assign('invoice_labour_rates_items', get_invoice_labour_rates_items($db));
$BuildPage .= $smarty->fetch('invoice/labour_rates.tpl');