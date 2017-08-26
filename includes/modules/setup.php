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

/** Common **/

############################################
#   Execute SQL File (preg_match method)   # // this imports a phpMyAdmin .sql exported file
############################################

// https://stackoverflow.com/questions/19751354/how-to-import-sql-file-in-mysql-database-using-php

function execute_sql_file($db, $sql_file) {
    
    global $smarty;
    
    // Load the SQL file into memory as string
    $sql_file = file_get_contents($sql_file);
    
    // Replace database prefix placeholder with required prefix
    $sql_file = str_replace('#__', PRFX, $sql_file);
    
    // Remove comment lines starting with /*
    $sql_file = preg_replace('/^\/\*.*\n/m', '', $sql_file);
        
    // Remove comment lines starting with --
    $sql_file = preg_replace('/^--.*\n/m', '', $sql_file);
    
    // Psrse the SQL commands
    preg_match_all('/^[A-Z].*;\n/msU', $sql_file, $sql_statements);
    
    // Error Flag
    $error_flag = false;
    
    // Open results container
    $execute_sql_file_results .= '<div>';
    
    // Loop through preg_match() result
    foreach ($sql_statements['0'] as $sql)
    {
        
        // Get rule name for output
        preg_match('/(^SET.*$|^.*`.*`)/U', $sql, $query_name);
        
       // Perform the query
        if(!$rs = $db->Execute($sql)) {
            
            $execute_sql_file_results .= '<span style="color: red">'.gettext("Error performing query").' : '. $query_name['0'].' : '.$db->ErrorMsg().'</span><br />';
            $error_flag = true;
            
        } else {
            
            $execute_sql_file_results .= '<span style="color: green">'.gettext("Performed query successfully").' : '. $query_name['0']. '</span><br />';

        }

    }
    
    // Close results container
    $execute_sql_file_results .= '</div>';
    
    if($error_flag) {
        
        $execute_sql_file_results .= '<br><div style="color: red;">'.gettext("Database import failed. Check the logs.").'</div>';
        $smarty->assign('execute_sql_file_results', $execute_sql_file_results);
        return false;
        
    } else {
        
        $execute_sql_file_results .= '<br><div style="color: green;">'.gettext("All Tables imported successfully").'</div>';
        $smarty->assign('execute_sql_file_results', $execute_sql_file_results);
        return true;
        
    }           
        
}

############################################
#   Execute SQL File (line by line)        #  //  file() loads line by line, good for large imports
############################################

// https://stackoverflow.com/questions/19751354/how-to-import-sql-file-in-mysql-database-using-php

function execute_sql_file_lines($db, $sql_file) {
    
    global $smarty;
    
    // Temporary variable, used to store current query
    $sql = '';
    
    // Read in entire file (will be line by because of below)
    $lines = file($sql_file);
    
    // Error Flag
    $error_flag = false;
    
    // Open results container
    $execute_sql_file_results .= '<div>';    
    
    // Loop through each line  - file() loads each line in one by one
    foreach ($lines as $line)
    {        
        // Skip it if the line is empty
        if ($line == '') {
            continue;
        }
        
        // Skip it if it's a comment ( -- or /* )
        if(substr($line, 0, 2) == '--' || substr($line,0,2) == '/*') {
            continue;            
        }
        
        // Replace database prefix placeholder with required prefix
        $line = str_replace('#__', PRFX, $line);

        // Add this line to the current segment
        $sql .= $line;
        
        // If it has a semicolon at the end, it's the end of the query
        if (substr(trim($line), -1, 1) == ';')
        {            
            // Get rule name for output
            preg_match('/(^SET.*$|^.*`.*`)/U', $sql, $query_name);

            // Perform the query
            if(!$rs = $db->Execute($sql)) {

                $execute_sql_file_results .= '<span style="color: red">'.gettext("Error performing query").' : '. $query_name['0'].' : '.$db->ErrorMsg().'</span><br />';
                $error_flag = true;

            } else {

                $execute_sql_file_results .= '<span style="color: green">'.gettext("Performed query successfully").' : '. $query_name['0']. '</span><br />';

            }            
                        
            // Reset templine variable to empty ready for the next line
            $sql = '';
            
        }        
        
    } 
    
    // Close results container
    $execute_sql_file_results .= '</div>';

    if($error_flag) {

        $execute_sql_file_results .= '<br><div style="color: red;">'.gettext("Database import failed. Check the logs.").'</div>';
        $smarty->assign('execute_sql_file_results', $execute_sql_file_results);
        return false;

    } else {

        $execute_sql_file_results .= '<br><div style="color: green;">'.gettext("All Tables imported successfully").'</div>';
        $smarty->assign('execute_sql_file_results', $execute_sql_file_results);
        return true;

    }
        
}

############################################
#  Check the database connection works     #
############################################

function check_database_connection($db, $db_host, $db_user, $db_pass, $db_name) {
    
    // create ADOdb database connection
    $db->Connect($db_host, $db_user, $db_pass, $db_name);
    
    if(!$db->isConnected()) {                
        return false;
    } else {    
        return true;
    }
    
}

############################################
#         Submit config settings           #
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
#  Generate Random Database prefix         #
############################################

function generate_database_prefix() {
    
    // generate a random string for the gift certificate
    
    $acceptedChars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $max_offset = strlen($acceptedChars)-1;
    $prefix = '';
    
    for($i=0; $i < 4; $i++) {
        $prefix .= $acceptedChars{mt_rand(0, $max_offset)};
    }
    
    $prefix .= '_';    
    
    return $prefix;
    
}

/** Install **/

############################################
#   Set workorder start number             #
############################################

function set_workorder_start_number($db, $start_number) {
    
    $sql = "ALTER TABLE ".PRFX."workorder auto_increment =".$start_number ;

    $db->execute($sql);
    
    return;
    
}

############################################
#   Set invoice start number               #
############################################

function set_invoice_start_number($db, $start_number) {
    
    $sql = "ALTER TABLE ".PRFX."invoice auto_increment =".$start_number ;

    $db->execute($sql);
    
    return;
    
}

############################################
#   Install database                       # // this imports a phpMyAdmin .sql exported file (preg_match method)
############################################

function install_database($db) {
    
    return execute_sql_file($db, SQL_DIR.'install/install_qwcrm.sql');
    
}

/** Migrate **/

function migrate_myitcrm_to_qwcrm() {
}

function workorders_migrate($myitcrm_db, $qwcrm_db) {
    
}
function migrate_workorders($myitcrm_db, $qwcrm_db) {
    
}

/** upgrade **/

############################################
#   Upgrade database                       #
############################################

function upgrade_database($db) {
    
       
}