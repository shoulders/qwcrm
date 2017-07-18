<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/refund.php');

// Predict the next refund_id
$new_record_id = last_refund_id_lookup($db) +1;

// If details submitted insert record, if non submitted load new.tpl and populate values
if((isset($VAR['submit'])) || (isset($VAR['submitandnew']))) {

    // insert the refund and get the refund_id
    $refund_id = insert_refund($db, $VAR);
        
    if (isset($VAR['submitandnew'])){

        // Load New Refund page
        force_page('refund', 'new');
        exit;

    } else {

        // Load Refund Details page
        force_page('refund', 'details&refund_id='.$refund_id);
        exit;

    }
         
}

// Build the page
$smarty->assign('new_record_id', $new_record_id);
$smarty->assign('tax_rate', get_company_details($db, 'TAX_RATE'));
$BuildPage .= $smarty->fetch('refund/new.tpl');