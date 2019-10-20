<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'supplier.php');

// If details submitted insert record, if non submitted load new.tpl and populate values
if(isset($VAR['submit']) || isset($VAR['submitandnew'])) {
        
    // insert the supplier record and get the supplier_id
    $VAR['supplier_id'] = insert_supplier($VAR);
            
    if (isset($VAR['submitandnew'])) {

        // load the new supplier page
        force_page('supplier', 'new', 'information_msg='._gettext("Supplier added successfully.").' '._gettext("ID").': '.$VAR['supplier_id']); 

    } else {

        // load the supplier details page
        force_page('supplier', 'details&supplier_id='.$VAR['supplier_id'], 'information_msg='._gettext("Supplier added successfully.").' '._gettext("ID").': '.$VAR['supplier_id']); 

    }

}

// Build the page
$smarty->assign('supplier_types', get_supplier_types());
\QFactory::$BuildPage .= $smarty->fetch('supplier/new.tpl');