<?php

require(INCLUDES_DIR.'modules/refund.php');

// Assign the arrays
$smarty->assign('refund_details', get_refund_details($db, $refund_id));
$BuildPage .= $smarty->fetch('refund/details.tpl');