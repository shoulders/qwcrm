<?php

// Load the Refund Functions
require_once('include.php');

// Load PHP Language Translations
$langvals = gateway_xml2php('refund');

$last_record_id = last_record_id_lookup($db);
$new_record_id = $last_record_id + 1;

// If details submitted insert record, if non submitted load new.tpl and populate values
    if((isset($VAR['submit'])) || (isset($VAR['submitandnew']))) {
        
                    if($run != insert_new_refund($db,$VAR)){
                            $smarty->assign('error_msg', 'Falied to insert Refund');
                            $BuildPage .= $smarty->fetch('core'.SEP.'error.tpl');
                            echo "refund insert error";

                            } else {

                                   if (isset($VAR['submitandnew'])){

                                                // Submit New Refund and reload page
                                                force_page('refund', 'new&page_title=');
                                                exit;

                                                }

                                                        else {

                                                            // Submit and load Refund View Details
                                                            force_page('refund', 'details', 'refund_id='.$new_record_id.'&page_title='.$langvals['refund_details_title']);
                                                            exit;

                                                }
                                 }

} else {
            
            $smarty->assign('new_record_id', $new_record_id);
            $smarty->assign('tax_rate', tax_rate($db));
            $BuildPage .= $smarty->fetch('refund'.SEP.'new.tpl');

       }