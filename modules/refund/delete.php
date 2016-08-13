<?php

// Load the Refund Functions
require_once('include.php');

// Load the Translation for this Module
if(!xml2php('refund')) {
    $smarty->assign('error_msg',"Error in language file");
}

$refund_id = $VAR['refund_id'];

// Make sure we got an Refund ID number
if(!isset($refund_id) || $refund_id =="") {
    $smarty->assign('results', 'Please go back and select an refund record');
    die;
}    

// Delete the refund function call
if(!delete_refund($db,$refund_id)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
} else {
        force_page('refund', 'view&page_title=Refund');
        exit;
}