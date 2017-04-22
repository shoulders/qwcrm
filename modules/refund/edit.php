<?php

require(INCLUDES_DIR.'modules/refund.php');

// Load PHP Language Translations
$langvals = gateway_xml2php('refund');

// If details submitted run update values, if not set load edit.tpl and populate values
if(isset($VAR['submit'])) {    
        
    if (!update_refund($db, $refund_id, $VAR)){

        force_page('refund', 'edit','error_msg=Falied to Update refund Information&refund_id='.$refund_id);
        exit;
                
    } else {
            
        force_page('refund', 'details&refund_id='.$refund_id);
        exit;
    }

} else {
    $smarty->assign('refund_details', get_refund_details($db, $refund_id));
    $BuildPage .= $smarty->fetch('refund/edit.tpl');
}