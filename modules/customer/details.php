<?php

require(INCLUDES_DIR.'modules/customer.php');
require(INCLUDES_DIR.'modules/employee.php');
require(INCLUDES_DIR.'modules/giftcert.php');
require(INCLUDES_DIR.'modules/invoice.php');
require(INCLUDES_DIR.'modules/workorder.php');

// check if we have a customer_id
if($customer_id == ''){
    force_page('core', 'error', 'error_msg=No Customer ID supplied.');
    exit;
}

// assign the arrays
$smarty->assign('company_details',      get_company_details($db)                                                );
$smarty->assign('customer_details',     get_customer_details($db, $customer_id)                                 );
$smarty->assign('open_work_orders',     display_workorders($db, '10', 'DESC', false, 1, 25, NULL, $customer_id) );
$smarty->assign('closed_work_orders',   display_workorders($db, '6', 'DESC', false, 1, 25, NULL, $customer_id)  );
$smarty->assign('unpaid_invoices',      display_invoices($db, '0', 'DESC', false, 1, 25, NULL, $customer_id)    );
$smarty->assign('paid_invoices',        display_invoices($db, '1', 'DESC', false, 1, 25, NULL, $customer_id)    );
$smarty->assign('giftcert_details',     display_giftcerts($db, $customer_id)                                    );
$smarty->assign('GoogleMapString',      build_googlemap_directions_string($db, $customer_id, $login_id)         );
$smarty->assign('customer_notes',       get_customer_notes($db, $customer_id)                                   );

$BuildPage .= $smarty->fetch('customer/details.tpl');