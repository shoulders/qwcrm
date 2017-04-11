<?php

require_once('include.php');

$smarty->assign('invoice', display_paid_invoice($db,$page_no,$smarty));
$BuildPage .= $smarty->fetch('invoice/view_paid.tpl');