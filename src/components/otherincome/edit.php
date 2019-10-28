<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Check if we have a otherincome_id
if(!isset(\CMSApplication::$VAR['otherincome_id']) || !\CMSApplication::$VAR['otherincome_id']) {
    systemMessagesWrite('danger', _gettext("No Refund ID supplied."));
    force_page('otherincome', 'search');
} 

// If details submitted run update values, if not set load edit.tpl and populate values
if(isset(\CMSApplication::$VAR['submit'])) {    
        
    // Update the otherincome in the database
    update_otherincome(\CMSApplication::$VAR['qform']);
    recalculate_otherincome_totals(\CMSApplication::$VAR['qform']['otherincome_id']);
    
    // load details page
    force_page('otherincome', 'details&otherincome_id='.\CMSApplication::$VAR['qform']['otherincome_id'], 'msg_success='._gettext("Otherincome updated successfully.")); 
} else {  

    // Check if payment can be edited
    if(!check_otherincome_can_be_edited(\CMSApplication::$VAR['otherincome_id'])) {
        systemMessagesWrite('danger', _gettext("You cannot edit this otherincome because its status does not allow it."));
        force_page('otherincome', 'details&otherincome_id='.\CMSApplication::$VAR['otherincome_id']);
    }
    
    // Build the page
    $smarty->assign('otherincome_statuses', get_otherincome_statuses());
    $smarty->assign('otherincome_types', get_otherincome_types());
    $smarty->assign('vat_tax_codes', get_vat_tax_codes(false) );    
    $smarty->assign('otherincome_details', get_otherincome_details(\CMSApplication::$VAR['otherincome_id']));

}
