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

// Check if we have a otherincome_id
if(!isset($VAR['otherincome_id']) || !$VAR['otherincome_id']) {
    force_page('otherincome', 'search', 'warning_msg='._gettext("No Refund ID supplied."));
} 

// If details submitted run update values, if not set load edit.tpl and populate values
if(isset($VAR['submit'])) {    
        
    // Update the otherincome in the database
    update_otherincome($VAR);
    recalculate_otherincome_totals($VAR['otherincome_id']);
    
    // load details page
    force_page('otherincome', 'details&otherincome_id='.$VAR['otherincome_id'], 'information_msg='._gettext("Refund updated successfully.")); 
} else {  

    // Check if payment can be edited
    if(!check_otherincome_can_be_edited($VAR['otherincome_id'])) {
        force_page('otherincome', 'details&otherincome_id='.$VAR['otherincome_id'], 'warning_msg='._gettext("You cannot edit this otherincome because its status does not allow it."));
    }
    
    // Build the page
    $smarty->assign('otherincome_statuses', get_otherincome_statuses());
    $smarty->assign('otherincome_types', get_otherincome_types());
    $smarty->assign('vat_tax_codes', get_vat_tax_codes(false) );    
    $smarty->assign('otherincome_details', get_otherincome_details($VAR['otherincome_id']));
    $BuildPage .= $smarty->fetch('otherincome/edit.tpl');

}
