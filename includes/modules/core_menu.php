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

/*
 * These are copied from includes/report.php but with menu added on the front of the name
 * These are only used to show numbers in the menu and could be removed
 */

defined('_QWEXEC') or die;

/** Mandatory Code **/

/** Workorders **/

##########################################
# Get single Work Order status           #
##########################################

function menu_get_single_workorder_is_closed($db, $workorder_id)
{
    $sql = "SELECT is_closed FROM ".PRFX."workorder WHERE workorder_id=".$db->qstr($workorder_id);
    
    if (!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to a get a single workorder status."));
        exit;
    } else {
        return $rs->fields['is_closed'];
    }
}
