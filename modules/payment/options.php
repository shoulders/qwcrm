<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/payment.php');

// If changes submited
if(isset($VAR['submit'])) {
    
    // Update enabled payment methods
    update_payment_system_methods_status($db, $VAR);
    
    // Update Payment details
    update_payment_settings($db, $VAR);

    // Assign success message    
    $smarty->assign('information_msg', gettext("Payment Options Updated.")  );    
    
}

// Build the page
$smarty->assign('payment_system_methods',   get_payment_system_methods($db) );
$smarty->assign('payment_settings',         get_payment_details($db)        );
$BuildPage .= $smarty->fetch('payment/options.tpl');
