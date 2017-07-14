<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/customer.php');
require(INCLUDES_DIR.'modules/user.php');
require(INCLUDES_DIR.'modules/giftcert.php');
require(INCLUDES_DIR.'modules/invoice.php');
require(INCLUDES_DIR.'modules/workorder.php');

// check if we have a customer_id
if($customer_id == ''){
    force_page('core', 'error', 'error_msg=No Customer ID supplied.');
    exit;
}

// assign the arrays
$smarty->assign('customer_details',     get_customer_details($db, $customer_id)                                                             );
$smarty->assign('open_workorders',      display_workorders($db, 'DESC', false, $page_no, '25', null, null, '10', null, $customer_id)        );
$smarty->assign('closed_workorders',    display_workorders($db, 'DESC', false, $page_no, '25', null, null, '6', null, $customer_id)         );
$smarty->assign('unpaid_invoices',      display_invoices($db, 'DESC', false, $page_no, '25', null, null, '0', null, $customer_id)           );
$smarty->assign('paid_invoices',        display_invoices($db, 'DESC', false, $page_no, '25', null, null, '1', null, $customer_id)           );
$smarty->assign('giftcert_details',     display_giftcerts($db, 'DESC', false, $page_no, '25', null, null, null, null, null, $customer_id)   );
$smarty->assign('GoogleMapString',      build_googlemap_directions_string($db, $customer_id, $login_user_id)                                );
$smarty->assign('customer_notes',       get_customer_notes($db, $customer_id)                                                               );

$BuildPage .= $smarty->fetch('customer/details.tpl');