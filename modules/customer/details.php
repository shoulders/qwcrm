<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/customer.php');
require(INCLUDES_DIR.'modules/user.php');
require(INCLUDES_DIR.'modules/giftcert.php');
require(INCLUDES_DIR.'modules/invoice.php');
require(INCLUDES_DIR.'modules/workorder.php');

// Check if we have a customer_id
if($customer_id == '') {
    force_page('customer', 'search', 'warning_msg='.gettext("No Customer ID supplied."));
    exit;
}

// Build the page
$smarty->assign('customer_details',     get_customer_details($db, $customer_id)                                                             );
$smarty->assign('open_workorders',      display_workorders($db, 'DESC', false, $page_no, '25', null, null, '10', null, $customer_id)        );
$smarty->assign('closed_workorders',    display_workorders($db, 'DESC', false, $page_no, '25', null, null, '6', null, $customer_id)         );
$smarty->assign('unpaid_invoices',      display_invoices($db, 'DESC', false, $page_no, '25', null, null, '0', null, $customer_id)           );
$smarty->assign('paid_invoices',        display_invoices($db, 'DESC', false, $page_no, '25', null, null, '1', null, $customer_id)           );

$smarty->assign('active_giftcerts',     display_giftcerts($db, 'DESC', false, $page_no, '25', null, null, null, '0', null, $customer_id)    );
$smarty->assign('redeemed_giftcerts',   display_giftcerts($db, 'DESC', false, $page_no, '25', null, null, null, '1', null, $customer_id)    );

$smarty->assign('GoogleMapString',      build_googlemap_directions_string($db, $customer_id, $login_user_id)                                );
$smarty->assign('customer_notes',       get_customer_notes($db, $customer_id)                                                               );

$BuildPage .= $smarty->fetch('customer/details.tpl');