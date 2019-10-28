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
 * Clients - functions for clientsd
 */

defined('_QWEXEC') or die;

class Core {

    /** Home Page **/

    #########################################
    # Display Welcome Note                  #
    #########################################

    function display_welcome_msg() {

        $db = \Factory::getDbo();

        $sql = "SELECT welcome_msg FROM ".PRFX."company_record";

        if(!$rs = $db->Execute($sql)) {
            force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Could not display the welcome message."));
        } else { 

            return $rs->fields['welcome_msg'];

        }

    }
    
}