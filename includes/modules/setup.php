<?php

/*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

/*
 * Mandatory Code - Code that is run upon the file being loaded
 * Display Functions - Code that is used to primarily display records - linked tables
 * New/Insert Functions - Creation of new records
 * Get Functions - Grabs specific records/fields ready for update - no table linking
 * Update Functions - For updating records/fields
 * Close Functions - Closing Work Orders code
 * Delete Functions - Deleting Work Orders
 * Other Functions - All other functions not covered above
 */

defined('_QWEXEC') or die;

/** Mandatory Code **/

/** Display Functions **/

/** New/Insert Functions **/

/** Get Functions **/

/** Update Functions **/

/** Close Functions **/

/** Delete Functions **/

/** Other Functions **/



/** install **/

############################################
#  check the database connection works     #
############################################

function check_database_connection($db_host, $db_user, $db_pass, $db_name) {
    
    global $smarty;
    
    // create adodb database connection
    $db = ADONewConnection('mysqli');
    $db->Connect($db_host, $db_user, $db_pass, $db_name);
    if(!$db->isConnected()) {
        $smarty->assign('warning_msg', $db->ErrorMsg().'<br>'.gettext("There is a database connection issue. Check your settings."));
        
        return false;
    } else {    
        return true;
    }
    
}

/** migrate **/
function workorders_migrate($myitcrm_db, $qwcrm_db) {
    
}
function migrate_workorders($myitcrm_db, $qwcrm_db) {
    
}

/** upgrade **/