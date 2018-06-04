<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'components/supplier.php');

// Check if we have a supplier_id
if($VAR['supplier_id'] == '') {
    force_page('supplier', 'search', 'warning_msg='._gettext("No Supplier ID supplied."));
} 

// If details submitted run update values, if not set load edit.tpl and populate values
if(isset($VAR['submit'])) {    
        
    // update the supplier record
    update_supplier($db, $VAR['supplier_id'], $VAR);
    
    // load the supplier details apge
    force_page('supplier', 'details&supplier_id='.$VAR['supplier_id'], 'information_msg='._gettext("Supplier updated successfully."));     
    
}

// Build the page
$smarty->assign('supplier_types', get_supplier_types($db));
$smarty->assign('supplier_details', get_supplier_details($db, $VAR['supplier_id']));
$BuildPage .= $smarty->fetch('supplier/edit.tpl');
