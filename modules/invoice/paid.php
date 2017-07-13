<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/invoice.php');
        
$smarty->assign('invoices', display_invoices($db, 'DESC', true, $page_no, '25', null, null, '1'));
$BuildPage .= $smarty->fetch('invoice/paid.tpl');