<?php

/*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

/*
 * Mandatory Code - Code that is run upon the file being loaded
 * Workorders - functions for wokorders
 * Invoices - functions for invoices
 * Customers - functions for customersd
 */

defined('_QWEXEC') or die;

/** Home Page **/

#########################################
# Display Welcome Note                  #
#########################################

function display_welcome_msg($db) {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT welcome_msg FROM ".PRFX."company_options";
       
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Could not display the welcome message."));
    } else { 
        
        return $rs->fields['welcome_msg'];
        
    }
    
}