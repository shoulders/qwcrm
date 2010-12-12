<?php

// Load the Expense Functions
require_once('include.php');

// Load the Translations for this Module
if(!xml2php('invoice')) {
	$smarty->assign('error_msg',"Error in language file");
}

$expenseID = $VAR['expenseID'];

// Load PHP Language Translations
$langvals = gateway_xml2php('invoice');

// Make sure we got an Expense ID number
if(!isset($expenseID) || $expenseID =="") {
	$smarty->assign('results', 'Please go back and select an expense record');
	die;
}

// Delete the expense function call
if(!delete_expense($db,$expenseID)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
} else {
        force_page('expense', 'search&page_title='.$langvals['expense_search_title']);
        exit;
}

?>