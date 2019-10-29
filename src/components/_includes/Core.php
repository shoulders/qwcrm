<?php

/*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

/*
 * Mandatory Code - Code that is run upon the file being loaded
 * Workorders - public functions for wokorders
 * Invoices - public functions for invoices
 * Clients - public functions for clientsd
 */

defined('_QWEXEC') or die;

class Core extends Components {

    /** Home Page **/

    #########################################
    # Display Welcome Note                  #
    #########################################

    public function display_welcome_msg() {

        $sql = "SELECT welcome_msg FROM ".PRFX."company_record";

        if(!$rs = $this->db->Execute($sql)) {
            $this->app->system->general->force_error_page('database', __FILE__, __FUNCTION__, $this->db->ErrorMsg(), $sql, _gettext("Could not display the welcome message."));
        } else { 

            return $rs->fields['welcome_msg'];

        }

    }
    
}