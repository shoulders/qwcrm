<?php

require(INCLUDES_DIR.'modules/refund.php');

// Load PHP Language Translations
$langvals = gateway_xml2php('refund');

// Predict the next refund_id
$new_record_id = last_refund_id_lookup($db) +1;

// If details submitted insert record, if non submitted load new.tpl and populate values
    if((isset($VAR['submit'])) || (isset($VAR['submitandnew']))) {
        
        if(!$refund_id = insert_refund($db, $VAR)){
            $smarty->assign('error_msg', 'Falied to insert Refund');
            $BuildPage .= $smarty->fetch('core/error.tpl');
            echo "refund insert error";

            } else {

                if (isset($VAR['submitandnew'])){

                     // Submit New Refund and reload page
                     force_page('refund', 'new');
                     exit;

                } else {

                    // Submit and load Refund Details
                    force_page('refund', 'details&refund_id='.$refund_id);
                    exit;

                 }
            }

} else {
            
    $smarty->assign('new_record_id', $new_record_id);
    $smarty->assign('tax_rate', get_company_details($db, 'TAX_RATE'));
    $BuildPage .= $smarty->fetch('refund/new.tpl');

}