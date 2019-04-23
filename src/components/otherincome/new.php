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

// If details submitted insert record, if non submitted load new.tpl and populate values
if(isset($VAR['submit'])) {

    // insert the otherincome and get the otherincome_id
    $VAR['otherincome_id'] = insert_otherincome($VAR);
        
    if ($VAR['submit'] == 'submitandnew') {

        // Load New Refund page
        force_page('otherincome', 'new', 'information_msg='._gettext("Other Income added successfully.").' '._gettext("ID").': '.$VAR['otherincome_id']); 

    } elseif ($VAR['submit'] == 'submitandpayment') {
         
        // Load the new payment page for otherincome
         force_page('payment', 'new&type=otherincome&otherincome_id='.$VAR['otherincome_id']);
         
    } else {

        // Load Refund Details page
        force_page('otherincome', 'details&otherincome_id='.$VAR['otherincome_id'], 'information_msg='._gettext("Other Income added successfully.").' '._gettext("ID").': '.$VAR['otherincome_id']);      

    }
         
}

// Build the page
$smarty->assign('otherincome_types', get_otherincome_types());
$smarty->assign('vat_tax_codes', get_vat_tax_codes(false));
$smarty->assign('default_vat_tax_code', get_default_vat_tax_code()); 
$BuildPage .= $smarty->fetch('otherincome/new.tpl');