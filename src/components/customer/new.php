<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'customer.php');

if(isset($VAR['submit'])) {

    // Create the new Customer
    $VAR['customer_id'] = insert_customer($VAR);
    
    // Load the new Customer's Details page
    force_page('customer', 'details&customer_id='.$VAR['customer_id']);
    
} else {
    
    // Build the page
    $smarty->assign('customer_types', get_customer_types());
    $BuildPage .= $smarty->fetch('customer/new.tpl');

}