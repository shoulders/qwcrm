<?php

require('includes'.SEP.'modules'.SEP.'workorder.php');

/* Get the page number we are on if first page set to 1 */
    if(!isset($VAR["page_no"])) {
        $page_no = 1;
    } else {
        $page_no = $VAR['page_no'];
    }    

$smarty->assign('new',      display_workorders($db, $page_no, 1)    );
$smarty->assign('assigned', display_workorders($db, $page_no, 2)    );
$smarty->assign('awaiting', display_workorders($db, $page_no, 3)    );
$smarty->assign('payment',  display_workorders($db, $page_no, 7)    );

$smarty->display('workorder'.SEP.'open.tpl');