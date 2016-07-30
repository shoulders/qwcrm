<?php

require_once('include.php');

// Grab the page number to show
if(!isset($VAR["page_no"])){
    $page_no = 1;
} else {
    $page_no = $VAR['page_no'];
}    

$work_order = display_closed($db, $page_no, $smarty);

$smarty->assign('work_order', $work_order);

$smarty->display('workorder'.SEP.'closed.tpl');