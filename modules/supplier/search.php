<?php

// Load the Supplier classes
require_once('include.php');

// Load the Translation for this Module
if(!xml2php('supplier')) {
    $smarty->assign('error_msg',"Error in language file");
}

// This sets page to number. goto_page-->page--> sets as 1 if no value
if(isset($VAR['goto_page_no'])){$page_no = $VAR['goto_page_no'];}
        else {if(isset($VAR['page_no'])) {$page_no = $VAR['page_no'];} else {$page_no = 1;}}

// this allows the intial page display which is a search
if(!isset($VAR['supplier_search_category'])) {$supplier_search_category = "ID";}
        else { $supplier_search_category = $VAR['supplier_search_category'];}

$supplier_search_term = $VAR['supplier_search_term'];

// Search term validator (supplier gateway), changes some variables appropiate to database values
$supplier_gateway_search_term = supplier_search_gateway($db, $supplier_search_category, $supplier_search_term);

// perform search with modified search term value - WORKS -for view page
$supplier_search_result = display_supplier_search($db, $supplier_search_category, $supplier_gateway_search_term, $page_no, $smarty);

$smarty->assign('supplier_search_term', $supplier_search_term);
$smarty->assign('supplier_search_result', $supplier_search_result);
$smarty->display('supplier'.SEP.'search.tpl');

   ?>