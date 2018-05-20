<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'components/customer.php');

if(isset($VAR['submit'])) {

    // Create the new Customer
    $VAR['customer_id'] = insert_customer($db, $VAR);
    
    // Load the new Customer's Details page
    force_page('customer', 'details&customer_id='.$VAR['customer_id']);
    exit;               
    
} else {
    
    // Build the page
    $smarty->assign('customer_types', get_customer_types($db));
    $BuildPage .= $smarty->fetch('customer/new.tpl');

}