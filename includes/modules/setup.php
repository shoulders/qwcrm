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
#   install database                       # // this imports a phpMyAdmin .sql exported file
############################################

function install_database($db) {
    
    global $smarty;
    
    // Load the SQL file into memory as string
    $sql_file = file_get_contents(SQL_DIR.'install/install_qwcrm.sql');
    
    // Replace database prefix placeholder with required prefix
    $sql_file = str_replace('#__', PRFX, $sql_file);
    
    // Remove comment lines starting with /*
    $sql_file = preg_replace('/^\/\*.*\n/m', '', $sql_file);
        
    // Remove comment lines starting with //
    $sql_file = preg_replace('/^--.*\n/m', '', $sql_file);
    
    // Psrse the SQL commands
    preg_match_all('/^[A-Z].*;\n/msU', $sql_file, $sql_statements);
    
    // Error Flag
    $error_flag = false;
    
    // open results container
    $database_installations_results .= '<div>';
    
    // Loop through each line
    foreach ($sql_statements['0'] as $sql)
    {
        
        // Get rule name for output
        preg_match('/(^SET.*$|^.*`.*`)/U', $sql, $query_name);
        
       // Perform the query
        if(!$rs = $db->Execute($sql)) {
            
            $database_installations_results .= '<span style="color: red">'.gettext("Error performing query").' : '. $query_name['0'].' : '.$db->ErrorMsg().'</span><br />';
            $error_flag = true;
            
        } else {
            
            $database_installations_results .= '<span style="color: green">'.gettext("Performed query successfully").' : '. $query_name['0']. '</span><br />';

        }

    }
    
    // close results container
    $database_installations_results .= '</div>';
    
    if($error_flag) {
        echo '<div style="color: red;">'.gettext("Database import failed. Check the logs.").'</div>';
        $smarty->assign('database_installations_results', $database_installations_results);
        return false;
    } else {
        echo '<div style="color: green;">'.gettext("All Tables imported successfully").'</div>';
        $smarty->assign('database_installations_results', $database_installations_results);
        return true;
    }       
    
        
}
     
    
    



/** migrate **/
function workorders_migrate($myitcrm_db, $qwcrm_db) {
    
}
function migrate_workorders($myitcrm_db, $qwcrm_db) {
    
}

/** upgrade **/

############################################
#   upgrade database                       #  // this can be finished to go through single line upgrade files
############################################

function upgrade_database($db) {
    
    //echo file_get_contents(SQL_DIR.'install/install_qwcrm.sql');
    
    //$sql = str_replace('#__', PRFX, file_get_contents(SQL_DIR.'install/install_qwcrm.sql'));
    
    // Temporary variable, used to store current query
    $templine = '';
    
    // Read in entire file (replacing the Prefix)
    $lines = str_replace('#__', PRFX, file_get_contents(SQL_DIR.'install/install_qwcrm.sql'));
    
    // Loop through each line  - file() loads each line in one by one
    foreach ($lines as $line)
    {
        echo $line;
        // Skip it if it's a comment
        if (substr($line, 0, 2) == '--' || $line == '') {
            continue;
        }
        
        /* Skip it if it's a comment
        if(substr($line,0,2) == "/*") {
            continue;            
        }*/

        // Add this line to the current segment
        $templine .= $line;
        
        // If it has a semicolon at the end, it's the end of the query
        if (substr(trim($line), -1, 1) == ';')
        {
            // Perform the query
            if(!$rs = $db->Execute($line)) {
                //force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to insert the QWcrm database."));
                //exit;
                //echo force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to insert the QWcrm database."));
                //return false;
                echo (gettext("Error performing query").' '.'<strong>' . $templine . ' : ' . $db->ErrorMsg() . '<br /><br />');
            } else {
                echo (gettext("Performed query successfully").' '.'<strong>' . $templine . ' : ' . $db->ErrorMsg() . '<br /><br />');
                
            }
                        
            // Reset temp variable to empty
            $templine = '';
        }
        
    }
     echo gettext("All Tables imported successfully");
    
    
}