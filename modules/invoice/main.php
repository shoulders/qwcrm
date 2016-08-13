<?php

require_once ('include.php');

/* Get the page number we are on if first page set to 1 */
if(!isset($VAR['page_no']))
{
    $page_no = 1;
} else {
    $page_no = $VAR['page_no'];
}
    
/* assign the smarty array */    
$smarty->assign('invoice_array', display_open_invoice($db, $page_no));
$smarty->display("workorder'.SEP.'main.tpl");