<?php
require_once('include.php');

$smarty->assign('invoice', display_open_invoices($db, $page_no));
$BuildPage .= $smarty->fetch('invoice/view_unpaid.tpl');