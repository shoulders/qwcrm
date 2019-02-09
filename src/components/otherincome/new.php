<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'company.php');
require(INCLUDES_DIR.'otherincome.php');
require(INCLUDES_DIR.'payment.php');

// Predict the next otherincome_id
$new_record_id = last_otherincome_id_lookup() +1;

// If details submitted insert record, if non submitted load new.tpl and populate values
if((isset($VAR['submit'])) || (isset($VAR['submitandnew']))) {

    // insert the otherincome and get the otherincome_id
    $VAR['otherincome_id'] = insert_otherincome($VAR);
        
    if (isset($VAR['submitandnew'])){

        // Load New Refund page
        force_page('otherincome', 'new', 'information_msg='._gettext("Refund added successfully.")); 

    } else {

        // Load Refund Details page
        force_page('otherincome', 'details&otherincome_id='.$VAR['otherincome_id'], 'information_msg='._gettext("Refund added successfully."));        

    }
         
}

// Build the page
$smarty->assign('otherincome_types', get_otherincome_types());
$smarty->assign('vat_tax_codes', get_vat_tax_codes(false) );
$smarty->assign('payment_methods', get_payment_methods('receive', 'enabled'));
$smarty->assign('new_record_id', $new_record_id);
$smarty->assign('vat_rate', get_company_details('sales_tax_rate'));
$BuildPage .= $smarty->fetch('otherincome/new.tpl');