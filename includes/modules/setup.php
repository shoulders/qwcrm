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
#   update values to new version           #
############################################

function update_values($db, $table, $column, $current_value, $new_value) {
    
    $sql = "UPDATE ".PRFX."$table SET
            $column         =". $db->qstr( $new_value )."                      
            WHERE $column   =". $db->qstr( $current_value  );

    if(!$rs = $db->execute($sql)){        
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to update column values."));
        exit;
    }
    
    return;
    
}

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
            
            // Start result message
            $execute_sql_file_results .= '<span style="color: red">';
                        
            // Log mesage to setup log
            $record = gettext("Error performing SQL query").' : '. $query_name['0'].' : '.$db->ErrorMsg();
            write_record_to_setup_log($record, 'install');
            
            // Finish result message
            $execute_sql_file_results .= $record;
            $execute_sql_file_results .= '</span><br />';
            $error_flag = true;
            
        } else {
            
            // Start result message
            $execute_sql_file_results .= '<span style="color: green">';
            
            // Log mesage to setup log            
            $record = gettext("Performed SQL query successfully").' : '. $query_name['0'];
            write_record_to_setup_log($record, 'install');
            
            // Finish result message
            $execute_sql_file_results .= $record;
            $execute_sql_file_results .= '</span><br />';

        }

    }
    
    // Close results container
    $execute_sql_file_results .= '</div>';
    
    if($error_flag) {
        
        // Start final message
        $execute_sql_file_results .= '<br><div style="color: red;">';
        
        // Log mesage to setup log
        $record = gettext("One or more SQL rule has failed. Check the logs.");
        write_record_to_setup_log($record, 'install');
        
        // Finish result message
        $execute_sql_file_results .= $record;
        $execute_sql_file_results .= '</div>';
        
        // Output message via smarty
        $smarty->assign('execute_sql_file_results', $execute_sql_file_results);
        
        return false;
        
    } else {
        
        // Start final message
        $execute_sql_file_results .= '<br><div style="color: green;">';
                
        // Log mesage to setup log
        $record = gettext("All SQL rules have run successfully.");
        write_record_to_setup_log($record, 'install');
        
        // Finish result message
        $execute_sql_file_results .= $record;
        $execute_sql_file_results .= '</div>';
        
        // Output message via smarty
        $smarty->assign('execute_sql_file_results', $execute_sql_file_results);
        
        return true;
        
    }           
        
}

############################################
#   Execute SQL File (line by line)        #  //  file() loads line by line, good for large imports - not currently used
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

                // Start result message
                $execute_sql_file_results .= '<span style="color: red">';

                // Log mesage to setup log
                $record = gettext("Error performing SQL query").' : '. $query_name['0'].' : '.$db->ErrorMsg();
                write_record_to_setup_log($record, 'install');

                // Finish result message
                $execute_sql_file_results .= $record;
                $execute_sql_file_results .= '</span><br />';
                $error_flag = true;

            } else {

                // Start result message
                $execute_sql_file_results .= '<span style="color: green">';

                // Log mesage to setup log            
                $record = gettext("Performed SQL query successfully").' : '. $query_name['0'];
                write_record_to_setup_log($record, 'install');

                // Finish result message
                $execute_sql_file_results .= $record;
                $execute_sql_file_results .= '</span><br />';

            }            
                        
            // Reset templine variable to empty ready for the next line
            $sql = '';
            
        }        
        
    } 
    
    // Close results container
    $execute_sql_file_results .= '</div>';

    if($error_flag) {

        // Start final message
        $execute_sql_file_results .= '<br><div style="color: red;">';
        
        // Log mesage to setup log
        $record = gettext("One or more SQL rule has failed. Check the logs.");
        write_record_to_setup_log($record, 'install');
        
        // Finish result message
        $execute_sql_file_results .= $record;
        $execute_sql_file_results .= '</div>';
        
        // Output message via smarty
        $smarty->assign('execute_sql_file_results', $execute_sql_file_results);
        
        return false;

    } else {

        // Start final message
        $execute_sql_file_results .= '<br><div style="color: green;">';
                
        // Log mesage to setup log
        $record = gettext("All SQL rules have run successfully.");
        write_record_to_setup_log($record, 'install');
        
        // Finish result message
        $execute_sql_file_results .= $record;
        $execute_sql_file_results .= '</div>';
        
        // Output message via smarty
        $smarty->assign('execute_sql_file_results', $execute_sql_file_results);
        
        return true;

    }
        
}

############################################
#  Write a record to the Setup Log         #    // cannot be turned off - install/migrate/upgrade
############################################

function write_record_to_setup_log($record, $setup_type) {
    
    // Login User - substituting qwcrm user for the traditional apache HTTP Authentication
    if(!QFactory::getUser()->login_username) {
        $username = '-';
    } else {
        $username = QFactory::getUser()->login_username;
    }
    
    // Build log entry - perhaps use the apache time stamp below
    $log_entry = $_SERVER['REMOTE_ADDR'].','.$username.','.date("[d/M/Y:H:i:s O]", time()).','.QFactory::getUser()->login_user_id.','.QWCRM_VERSION.','.$setup_type.','.$record."\r\n";
    
    // Write log entry  
    if(!$fp = fopen(SETUP_LOG, 'a')) {        
        force_error_page($_GET['page'], 'file', __FILE__, __FUNCTION__, '', '', gettext("Could not open the Setup Log to save the record."));
        exit;
    }
    
    fwrite($fp, $log_entry);
    fclose($fp);
    
    return;
    
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

function generate_database_prefix($not_this_prefix = null) {
    
    // generate a random string for the gift certificate
    
    $acceptedChars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $max_offset = strlen($acceptedChars)-1;
    $prefix = '';
    
    for($i=0; $i < 4; $i++) {
        $prefix .= $acceptedChars{mt_rand(0, $max_offset)};
    }
    
    $prefix .= '_';    
    
    // this is to prevent using the MyITCRM prefix
    if($not_this_prefix) {
        if($prefix == $not_this_prefix) {
            $prefix = generate_database_prefix($not_this_prefix);
        }
    }
    
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
    
    return execute_sql_file($db, SETUP_DIR.'install/install.sql');
   
}

/** Migrate **/



############################################
#   Migrate myitcrm database               #
############################################

function migrate_database($db) {
    
    $config = new QConfig;  // i.e. $config->myitcrm_prefix
        
    // Customer
    
    // Expense
    
    // Gifcert ?
    
    // Invoice
    
    // Payment / transactions
    
    // Refund
    
    // Schedule ?
    
    // Supplier
    
    // user / Employee
    
    // Workorder
    
    
}


##################################
#  Get MyITCRM company details   #
##################################

/*
 * This combined function allows you to pull any of the company information individually
 * or return them all as an array
 * supply the required field name or all to return all of them as an array
 */

function get_myitcrm_company_details($db, $item = null) {
    
    $config = new QConfig;
    
    $sql = "SELECT * FROM ".$config->myitcrm_prefix."table_company";
    
    if(!$rs = $db->execute($sql)) {        
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to get MyITCRM company details."));        
        exit;
    } else {
        
        if($item === null) {
            
            return $rs->GetRowAssoc();            
            
        } else {
            
            return $rs->fields[$item];   
            
        } 
        
    }
    
}

################################################
#   migrate data from myitcrm (insert method)  #    // build 1 SQL statement and then execute
################################################

function migrate_table_insert($db, $qwcrm_prefix, $qwcrm_table, $myitcrm_prefix, $myitcrm_table, $column_mappings) {
    
     //         qwcrm         myitcrm
     // array('username' => 'username')            
    
    /* load the records from MyITCRM */
    
    $sql = "SELECT * FROM $myitcrm_prefix$myitcrm_table";
    
    if(!$rs = $db->execute($sql)){        
        //force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to migrate a table."));
        //exit;   
        
        // output error, could not load table so all of this table was skipped
        return false;
    
    } else {
        
        /* Load each single records and insert into QWcrm */        
        
        while($record = $rs->fetchRow()) {            
              
            /* Build the 'INSERT' part of the SQL statement */
            
            $insert_sql = "INSERT INTO `$qwcrm_prefix$qwcrm_table` (";
            foreach($column_mappings as $qwcrm_column) {
                $insert_sql .= "`$qwcrm_column`, ";            
            }
            rtrim($insert_sql, ', ');           // remove the last ', '        
            $insert_sql .= ") VALUES" . "\n";
            
            /* Build 'VALUES' part of the SQL statement by mapping the MyITCRM record data to the QWcrm values */
            
            $values_sql = '(';
            foreach($column_mappings as $qwcrm_column => $myitcrm_column) {
                
                // Skip looking for data in MyITCRM record if there is no corresponding field
                if($myitcrm_column == '') { continue; }
                
                foreach($record as $record_myitcrm_column => $record_myitcrm_val) {
                    
                    if($myitcrm_column == $record_myitcrm_column) {
                        $values_sql .= "'$record_myitcrm_val', ";
                        break;
                    }    
                
                }

                // Close the 'VALUES' SQL statement
                rtrim($values_sql, ', ');
                $values_sql .= ");";            
            
            }
            
            /* Build and execute statement */
        
            // combine the 'INSERT' and 'VALUES' sections
            $sql = $insert_sql.$values_sql;

            // insert the migrated record into qwcrm
            if(!$rs = $db->execute($sql)){        
                //force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to migrate a table."));
                //exit;
                
                // output error to screen
                
            } else {
             
                // output success to screen
            }
        
        }// EOF While Loop
    
        return;    
    
    }

}

#########################################################
#   check myitcrm database is accessible and is 2.9.3   #
#########################################################
// 
function check_myitcrm_database_connection($db, $myitcrm_prefix) {
    
    $sql = "SELECT VERSION_ID FROM ".$myitcrm_prefix."VERSION WHERE VERSION_ID = '293'";
    
    if(!$rs = $db->execute($sql)){        
        
        // output message failed to connect to the myitcrm database
        return false;
    
    } else {
        
        if($rs->RecordCount() != 1) {
            
            //output error message database is not 293
            
        } else {
         
            // myitcrm database is sutiable for migration
        }
            
    }
    
}


##############################################
#  merge QWcrm and MyITCRM compnay details   #
##############################################

function get_merged_company_details($db) {
    
    $qwcrm_company_details              = get_company_details($db);
    $myitcrm_company_details            = get_myitcrm_company_details($db);
    
    $merged['display_name']             = $myitcrm_company_details['COMPANY_NAME'];
    $merged['logo']                     = '';
    $merged['company_number']           = $myitcrm_company_details['COMPANY_ABN'];
    $merged['vat_number']               = '';
    $merged['address']                  = $myitcrm_company_details['COMPANY_ADDRESS'];
    $merged['city']                     = $myitcrm_company_details['COMPANY_CITY'];
    $merged['state']                    = $myitcrm_company_details['COMPANY_STATE'];
    $merged['zip']                      = $myitcrm_company_details['COMPANY_ZIP'];
    $merged['country']                  = $myitcrm_company_details['COMPANY_COUNTRY'];
    $merged['primary_phone']            = $myitcrm_company_details['COMPANY_PHONE'];
    $merged['mobile_phone']             = $myitcrm_company_details['COMPANY_MOBILE'];
    $merged['fax']                      = $myitcrm_company_details['COMPANY_FAX'];
    $merged['email']                    = $myitcrm_company_details['COMPANY_EMAIL'];
    $merged['website']                  = '';
    $merged['tax_rate']                 = '';
    $merged['year_start']               = '';
    $merged['year_end']                 = '';
    $merged['welcome_msg']              = $qwcrm_company_details['welcome_msg'];
    $merged['currency_symbol']          = $myitcrm_company_details['COMPANY_CURRENCY_SYMBOL'];
    $merged['currency_code']            = $myitcrm_company_details['COMPANY_CURRENCY_CODE'];
    $merged['date_format']              = $myitcrm_company_details['COMPANY_DATE_FORMAT'];
    $merged['email_signature']          = $qwcrm_company_details['email_signature'];
    $merged['email_signature_active']   = $qwcrm_company_details['email_signature_active'];
    $merged['email_msg_invoice']        = $qwcrm_company_details['email_msg_invoice'];
    $merged['email_msg_workorder']      = $qwcrm_company_details['email_msg_workorder'];
    
    return $merged;
    
}


function workorders_migrate($myitcrm_db, $qwcrm_db) {
    
}
function migrate_workorders($myitcrm_db, $qwcrm_db) {
    
}



################################################
#   remove myitcrm database prefix             #
################################################

function remove_myitcrm_prefix_config() {
}






















/** upgrade **/

############################################
#   Upgrade database                       #
############################################

function upgrade_database($db) {
    
       
}