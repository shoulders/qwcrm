<?php

require(INCLUDES_DIR.'modules/invoice.php');

$smarty->assign('invoice', display_invoices($db, '0', 'DESC', true, $page_no));
$BuildPage .= $smarty->fetch('invoice/unpaid.tpl');