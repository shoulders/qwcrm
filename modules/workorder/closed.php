<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/workorder.php');

/* Temp workaround - show all different status that could be classed as closed */

// this breaks stuff - i will remove payment from workorders anyway

/*closed = display_workorders($db, 'DESC', true, $page_no, '25', null, null, '6');
$waiting_for_payment = display_workorders($db, 'DESC', true, $page_no, '25', null, null, '7');
$payment_made = display_workorders($db, 'DESC', true, $page_no, '25', null, null, '8');
$pending = display_workorders($db, 'DESC', true, $page_no, '25', null, null, '9');
if(!is_array($closed)) { $closed = array(); }
if(!is_array($waiting_for_payment)) { $waiting_for_payment = array(); }
if(!is_array($payment_made)) { $payment_made = array(); }
if(!is_array($pending)) { $pending = array(); }
$workorders = array_merge($closed, $waiting_for_payment, $payment_made, $pending);*/

// Build the page
$smarty->assign('workorders', display_workorders($db, 'DESC', true, $page_no, '25', null, null, '6'));
$BuildPage .= $smarty->fetch('workorder/closed.tpl');