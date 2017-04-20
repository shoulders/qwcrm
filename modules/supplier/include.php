<?php





######################################################
# Search - Also returns records for intial view page #
######################################################

function display_supplier_search($db, $supplier_search_category, $supplier_search_term, $page_no) {
    
    global $smarty;

    // Define the number of results per page
    $max_results = 25;

    // Figure out the limit for the Execute based
    // on the current page number.
    $from = (($page_no * $max_results) - $max_results);

    $sql = "SELECT * FROM ".PRFX."TABLE_SUPPLIER WHERE SUPPLIER_$supplier_search_category LIKE '$supplier_search_term' ORDER BY SUPPLIER_ID LIMIT $from, $max_results";

    //print $sql;

    if(!$result = $db->Execute($sql)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    } else {
        $supplier_search_result = array();
    }

    while($row = $result->FetchRow()){
         array_push($supplier_search_result, $row);
    }

    // Figure out the total number of results in DB:
    $results = $db->Execute("SELECT COUNT(*) as Num FROM ".PRFX."TABLE_SUPPLIER WHERE SUPPLIER_$supplier_search_category LIKE ".$db->qstr("$supplier_search_term") );

    if(!$total_results = $results->FetchRow()) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    } else {
        $smarty->assign('total_results', $total_results['Num']);
    }

    // Figure out the total number of pages. Always round up using ceil()
    $total_pages = ceil($total_results["Num"] / $max_results);
    $smarty->assign('total_pages', $total_pages);

    // Assign the first page
    if($page_no > 1) {
        $prev = ($page_no - 1);
    }

    // Build Next Link
    if($page_no < $total_pages){
        $next = ($page_no + 1);
    }

    $smarty->assign('items', $items);
    $smarty->assign('page_no', $page_no);
    $smarty->assign('previous', $prev);
    $smarty->assign('next', $next);
        $smarty->assign('supplier_search_category', $supplier_search_category);
        $smarty->assign('supplier_search_term', $supplier_search_term);

    return $supplier_search_result;
}