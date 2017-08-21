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

function check_database_connection($db, $db_host, $db_user, $db_pass, $db_name) {
    
    // create adodb database connection
    $db->Connect($db_host, $db_user, $db_pass, $db_name);
    if(!$db->isConnected()) {                
        return false;
    } else {    
        return true;
    }
    
}

############################################
#         submit config settings           #
############################################

function submit_qwcrm_config_settings($VAR) {
    
    // clear uneeded variables
    unset($VAR['page']);
    unset($VAR['submit']);
    unset($VAR['stage']);
    unset($VAR['theme']);
    
    update_qwcrm_config($VAR);
    
}

############################################
#   set workorder start number             #
############################################

function set_workorder_start_number($db, $start_number) {
    
    $sql = "ALTER TABLE ".PRFX."workorder auto_increment =".$start_number ;

    $db->execute($sql);
    
    return;
    
}

############################################
#   set invoice start number               #
############################################

function set_invoice_start_number($db, $start_number) {
    
    $sql = "ALTER TABLE ".PRFX."invoice auto_increment =".$start_number ;

    $db->execute($sql);
    
    return;
    
}

############################################
#   install database                       #
############################################

function install_database($db) {
    
    $sql = str_replace(file_get_contents(SQL_DIR.'install/install_qwcrm.sql'), PRFX, '#__');
    
    if(!$rs = $db->Execute($sql)) {
        //force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to insert the QWcrm database."));
        //exit;
        //echo force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to insert the QWcrm database."));
        return false;
    } else {
        
        return;
        
    }
    
    
}


/** migrate **/
function workorders_migrate($myitcrm_db, $qwcrm_db) {
    
}
function migrate_workorders($myitcrm_db, $qwcrm_db) {
    
}

/** upgrade **/