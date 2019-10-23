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
if(isset(\QFactory::$VAR['submit']) || isset(\QFactory::$VAR['submitandnew'])) {
        
    // insert the supplier record and get the supplier_id
    \QFactory::$VAR['supplier_id'] = insert_supplier(\QFactory::$VAR['qform']);
            
    if (isset(\QFactory::$VAR['submitandnew'])) {

        // load the new supplier page
        force_page('supplier', 'new', 'msg_success='._gettext("Supplier added successfully.").' '._gettext("ID").': '.\QFactory::$VAR['supplier_id']); 

    } else {

        // load the supplier details page
        force_page('supplier', 'details&supplier_id='.\QFactory::$VAR['supplier_id'], 'msg_success='._gettext("Supplier added successfully.").' '._gettext("ID").': '.\QFactory::$VAR['supplier_id']); 

    }

}

// Build the page
$smarty->assign('supplier_types', get_supplier_types());