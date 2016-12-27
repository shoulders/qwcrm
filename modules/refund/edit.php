<?php

// Load the Refund Functions
require_once('include.php');

// Load PHP Language Translations
$langvals = gateway_xml2php('refund');

// Load refund details
$refund_details = display_refund_info($db, $VAR['refund_id']);

// If details submitted run update values, if not set load edit.tpl and populate values
if(isset($VAR['submit'])) {    
        
    if (!update_refund($db, $VAR)){

        force_page('refund', 'edit&error_msg=Falied to Update refund Information&refund_id='.$VAR['refund_id']);
        exit;
                
    } else {
            
        force_page('refund', 'refund_details&refund_id='.$VAR['refund_id'].'&page_title='.$langvals['refund_details_title']);
        exit;
    }

} else {
    $smarty->assign('refund_details', $refund_details);
    $smarty->display('refund'.SEP.'edit.tpl');
       }