<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'supplier.php');

// Check if we have a supplier_id
if(!isset(\QFactory::$VAR['supplier_id']) || !\QFactory::$VAR['supplier_id']) {
    force_page('supplier', 'search', 'warning_msg='._gettext("No Supplier ID supplied."));
} 

// If details submitted run update values, if not set load edit.tpl and populate values
if(isset(\QFactory::$VAR['submit'])) {    
        
    // update the supplier record
    update_supplier(\QFactory::$VAR);
    
    // load the supplier details apge
    force_page('supplier', 'details&supplier_id='.\QFactory::$VAR['supplier_id'], 'information_msg='._gettext("Supplier updated successfully."));     
    
} else {
    
    // Check if supplier can be edited
    if(!check_supplier_can_be_edited(\QFactory::$VAR['supplier_id'])) {
        force_page('supplier', 'details&supplier_id='.\QFactory::$VAR['supplier_id'], 'warning_msg='._gettext("You cannot edit this supplier because its status does not allow it."));
    }

    // Build the page
    $smarty->assign('supplier_statuses',   get_supplier_statuses()   );
    $smarty->assign('supplier_types', get_supplier_types());
    $smarty->assign('supplier_details', get_supplier_details(\QFactory::$VAR['supplier_id']));
    \QFactory::$BuildPage .= $smarty->fetch('supplier/edit.tpl');

}