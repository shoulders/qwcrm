<?php

require_once('include.php');

/* Get the page number we are on if first page set to 1 */
    if(!isset($VAR["page_no"])) {
        $page_no = 1;
    } else {
        $page_no = $VAR['page_no'];
    }    

/* display new Workorders */    
$smarty->assign('new', display_workorders($db, $page_no, 1));

/* display assigned Workorders */
$smarty->assign('assigned', display_workorders($db, $page_no, 2));

/* display Workorders awaiting parts  */    
$smarty->assign('awaiting', display_workorders($db, $page_no, 3));

/* display work orders that need payment */
$smarty->assign('payment', display_workorders($db, $page_no, 7));

$smarty->display('workorder'.SEP.'open.tpl');