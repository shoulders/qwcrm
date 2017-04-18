<?php

require(INCLUDES_DIR.'modules/workorder.php');

// Load PHP Language Translations
$langvals = gateway_xml2php();

// Make sure we got an Expense ID number
if($expense_id == '') {
    $smarty->assign('results', 'Please go back and select an expense record');
    die;
}    

// Delete the expense function call
if(!delete_expense($db,$expense_id)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
} else {
        force_page('expense', 'search&page_title='.$langvals['expense_search_title']);
        exit;
}