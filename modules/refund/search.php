<?php

// Load the Refund Functions
require_once('include.php');

// Load the Translation for this Module
if(!xml2php('refund')) {
    $smarty->assign('error_msg',"Error in language file");
}

// This sets page to number. goto_page-->page--> sets as 1 if no value
if(isset($VAR['goto_page_no'])){$page_no = $VAR['goto_page_no'];}
        else {if(isset($VAR['page_no'])) {$page_no = $VAR['page_no'];} else {$page_no = 1;}}

// this allows the intial page display which is a search
if(!isset($VAR['refund_search_category'])) {$refund_search_category = "ID";}
        else { $refund_search_category = $VAR['refund_search_category'];}

$refund_search_term = $VAR['refund_search_term'];

// Search term validator (refund gateway), changes some variables appropiate to database values
$refund_gateway_search_term = refund_search_gateway($db, $refund_search_category, $refund_search_term);

// perform search with modified search term value - WORKS -for view page
$refund_search_result = display_refund_search($db, $refund_search_category, $refund_gateway_search_term, $page_no, $smarty);

$smarty->assign('refund_search_term', $refund_search_term);
$smarty->assign('refund_search_result', $refund_search_result);
$smarty->display('refund'.SEP.'search.tpl');

   ?>