<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/invoice.php');
        
$smarty->assign('invoices', display_invoices($db, '1', 'DESC', true, $page_no));
$BuildPage .= $smarty->fetch('invoice/paid.tpl');