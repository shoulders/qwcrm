<?php

require('includes'.SEP.'modules'.SEP.'workorder.php');

// Grab the page number to show else set page 1
if(!isset($VAR["page_no"])){
    $page_no = 1;
} else {
    $page_no = $VAR['page_no'];
}    

$smarty->assign('work_order', display_closed($db, $page_no));

$smarty->display('workorder'.SEP.'closed.tpl');