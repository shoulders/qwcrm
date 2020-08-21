<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// If details submitted insert record, if non submitted load new.tpl and populate values
if(isset(\CMSApplication::$VAR['submit']) || isset(\CMSApplication::$VAR['submitandnew'])) {
        
    // insert the supplier record and get the supplier_id
    \CMSApplication::$VAR['supplier_id'] = $this->app->components->supplier->insertRecord(\CMSApplication::$VAR['qform']);
            
    if (isset(\CMSApplication::$VAR['submitandnew'])) {

        // load the new supplier page
        $this->app->system->variables->systemMessagesWrite('success', _gettext("Supplier added successfully.").' '._gettext("ID").': '.\CMSApplication::$VAR['supplier_id']);
        $this->app->system->page->forcePage('supplier', 'new'); 

    } else {

        // load the supplier details page
        $this->app->system->variables->systemMessagesWrite('success', _gettext("Supplier added successfully.").' '._gettext("ID").': '.\CMSApplication::$VAR['supplier_id']);
        $this->app->system->page->forcePage('supplier', 'details&supplier_id='.\CMSApplication::$VAR['supplier_id']); 

    }

}

// Build the page
$this->app->smarty->assign('supplier_types', $this->app->components->supplier->getTypes());