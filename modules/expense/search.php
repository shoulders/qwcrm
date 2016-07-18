<?php

// Load the Expense Functions
require_once('include.php');

// Load the Translations for this Module
if(!xml2php('expense')) {
    $smarty->assign('error_msg',"Error in language file");
}

// This sets page to number. goto_page-->page--> sets as 1 if no value
if(isset($VAR['goto_page_no'])){$page_no = $VAR['goto_page_no'];}
        else {if(isset($VAR['page_no'])) {$page_no = $VAR['page_no'];} else {$page_no = 1;}}

// this allows the intial page display which is a search
if(!isset($VAR['expense_search_category'])) {$expense_search_category = "ID";}
        else { $expense_search_category = $VAR['expense_search_category'];}

$expense_search_term = $VAR['expense_search_term'];

// Search term validator (expense gateway), changes some variables appropiate to database values
$expense_gateway_search_term = expense_search_gateway($db, $expense_search_category, $expense_search_term);

// perform search with modified search term value - WORKS -for view page
$expense_search_result = display_expense_search($db, $expense_search_category, $expense_gateway_search_term, $page_no, $smarty);

$smarty->assign('expense_search_term', $expense_search_term);
$smarty->assign('expense_search_result', $expense_search_result);
$smarty->display('expense'.SEP.'search.tpl');