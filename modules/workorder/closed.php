<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/workorder.php');

// Build the page
$smarty->assign('workorders', display_workorders($db, 'DESC', true, $page_no, '25', null, null, 'closed'));
$BuildPage .= $smarty->fetch('workorder/closed.tpl');