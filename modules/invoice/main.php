<?php

require_once ('include.php');
    
// Fetch page  
$smarty->assign('invoice_array', display_open_invoices($db, $page_no));
$BuildPage .= $smarty->fetch('workorder/main.tpl');