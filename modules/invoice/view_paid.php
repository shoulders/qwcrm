<?php

require_once('include.php');

if(!isset($VAR['page_no'])){
    $page_no = 1;
} else {
    $page_no = $VAR['page_no'];
}    
$invoice = display_paid_invoice($db,$page_no,$smarty);
    
    $smarty->assign('invoice', $invoice);
    $BuildPage .= $smarty->fetch('invoice'.SEP.'view_paid.tpl');