<?php

// Load the Refund Functions
require_once('include.php');

// Assign the arrays
$smarty->assign('refund_details', display_refund_info($db, $VAR['refund_id']));
$BuildPage .= $smarty->fetch('refund'.SEP.'details.tpl');