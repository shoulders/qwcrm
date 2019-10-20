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
require(INCLUDES_DIR.'report.php');

// If details submitted insert record, if non submitted load new.tpl and populate values
if(isset(\QFactory::$VAR['submit'])) {

    // insert the otherincome and get the otherincome_id
    $otherincome_id = insert_otherincome(\QFactory::$VAR);
    recalculate_otherincome_totals($otherincome_id);
        
    if (\QFactory::$VAR['submit'] == 'submitandnew') {

        // Load New Refund page
        force_page('otherincome', 'new', 'information_msg='._gettext("Other Income added successfully.").' '._gettext("ID").': '.$otherincome_id); 

    } elseif (\QFactory::$VAR['submit'] == 'submitandpayment') {
         
        // Load the new payment page for otherincome
         force_page('payment', 'new&type=otherincome&otherincome_id='.$otherincome_id, 'information_msg='._gettext("Other Income added successfully.").' '._gettext("ID").': '.$otherincome_id);      
         
    } else {

        // Load Refund Details page
        force_page('otherincome', 'details&otherincome_id='.$otherincome_id, 'information_msg='._gettext("Other Income added successfully.").' '._gettext("ID").': '.$otherincome_id);      

    }
         
}

// Build the page
$smarty->assign('otherincome_types', get_otherincome_types());
$smarty->assign('vat_tax_codes', get_vat_tax_codes(false));
$smarty->assign('default_vat_tax_code', get_default_vat_tax_code()); 
\QFactory::$BuildPage .= $smarty->fetch('otherincome/new.tpl');