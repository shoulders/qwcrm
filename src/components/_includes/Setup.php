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
 * Other Functions - All other public functions not covered above
 */

defined('_QWEXEC') or die;

/* Only allow the use of these public functions when the /setup/ folder exists.
if (!is_dir(SETUP_DIR)) {      
    die(_gettext("You cannot use these public functions without the setup folder."));        
}*/

class Setup extends Components {

    public static $setup_error_flag = null;     /// ??? should the be 'protected'?
    public static $executed_sql_results = null;
    public static $split_database_upgrade = null;     
    
    public function __construct() {
        
        parent::__construct();
        
        // Create a User object without starting the Session
        // This prevents undefined errors in get_page_content()
        $this->app->user = new Joomla\CMS\User\User;
        
        // Prevent undefined variable errors && Get 'stage' from the submit button
        \CMSApplication::$VAR['stage'] = \CMSApplication::$VAR['submit'] ?? null;
        $this->app->smarty->assign('stage', \CMSApplication::$VAR['stage']);
        $this->app->smarty->assign('setup_error_flag', self::$setup_error_flag);
        $this->app->smarty->assign('executed_sql_results', self::$executed_sql_results);
        
        // Set Max Execution time to 5 Minutes
        ini_set('max_execution_time', 300);
        
    }
   
    /** Database Common **/

    #########################################################
    #       Update a value in a specified record            #  // with and without 'WHERE' clause
    #########################################################

    public function updateRecordValue($select_table, $select_column, $record_new_value, $where_column = null, $where_record = null, $where_record_not_flag = null) {

        $sql = "UPDATE $select_table SET
                $select_column =". $this->app->db->qStr($record_new_value);

        if($where_column) {    
            $sql .=  "\nWHERE $where_column ".$where_record_not_flag."=".$this->app->db->qStr($where_record);
        }

        if(!$this->app->db->execute($sql)) { 

            // Set the setup global error flag
            self::$setup_error_flag = true;

            // Log message
            if($where_column) {
                $record = _gettext("Failed to update the record value").' `'.$select_column.'` '._gettext("where the records were matched in the column").' `'.$where_column.'` '._gettext("by").' `'.$where_record.'` '.'` '._gettext("from the table").' `'.$select_table.'` ';
            } else {            
                $record = _gettext("Failed to update the value for the record").' `'.$select_column.'` '._gettext("to").' `'.$record_new_value.'` '._gettext("in the table").' `'.$select_table.'` ';
            }

            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
            self::$executed_sql_results .= '<div>&nbsp;</div>';

            // Log message to setup log        
            $this->writeRecordToSetupLog('correction', $record, $this->app->db->ErrorMsg(), $sql);

            return false;

        } else {

            // Log message             
            if($where_column) {
                $record = _gettext("Successfully updated the record value").' `'.$select_column.'` '._gettext("where the records were matched in the column").' `'.$where_column.'` '._gettext("by").' `'.$where_record.'` '.'` '._gettext("from the table").' `'.$select_table.'` ';            
            } else {            
                $record = _gettext("Successfully updated the value for the record").' `'.$select_column.'` '._gettext("to").' `'.$record_new_value.'` '._gettext("in the table").' `'.$select_table.'` ';
            }

            // Output message via smarty - to reduce onscreen output i have disabled success output, it is still logged
            //self::$executed_sql_results .= '<div style="color: green">'.$record.'</div>';
            //self::$executed_sql_results .= '<div>&nbsp;</div>';

            // Log message to setup log (if enabled this can cause setup to take much longer to run)   
            //$this->write_record_to_setup_log('correction', $record);

            return true;

        }    

    }

    #########################################################
    #   update all matching values in a column to new value #
    #########################################################

    public function updateColumnValues($table, $column, $current_value, $new_value) {

        if($current_value === '*') {

            $sql = "UPDATE $table SET
                    $column         =". $this->app->db->qStr( $new_value       );

        } else {

            $sql = "UPDATE $table SET
                    $column         =". $this->app->db->qStr( $new_value       )."                      
                    WHERE $column   =". $this->app->db->qStr( $current_value   );

        }

        if(!$this->app->db->execute($sql)) { 

            // Set the setup global error flag
            self::$setup_error_flag = true;

            // Log message
            $record = _gettext("Failed to update the values").' `'.$current_value.'` '._gettext("to").' `'.$new_value.'` '._gettext("in the column").' `'.$column.'` '._gettext("from the table").' `'.$table.'` ';

            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
            self::$executed_sql_results .= '<div>&nbsp;</div>';        

            // Log message to setup log        
            $this->writeRecordToSetupLog('correction', $record, $this->app->db->ErrorMsg(), $sql);

            return false;

        } else {        

            // Affected Rows
            if(!$affected_rows = $this->app->db->affected_rows()) { $affected_rows = '0'; }

            // Log message
            $record = _gettext("Successfully updated the values").' `'.$current_value.'` '._gettext("to").' `'.$new_value.'` '._gettext("in the column").' `'.$column.'` '._gettext("from the the table").' `'.$table.'` - '._gettext("Records Processed").': '.$affected_rows;

            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: green">'.$record.'</div>';
            self::$executed_sql_results .= '<div>&nbsp;</div>';

            // Log message to setup log        
            $this->writeRecordToSetupLog('correction', $record);

            return true;        

        }    

    }

    ############################################
    #   Execute SQL File (preg_match method)   # // this imports a phpMyAdmin .sql exported file (loads all the SQL file into memory)
    ############################################ // This is used to install the initial database from SQL file, only

    // https://stackoverflow.com/questions/19751354/how-to-import-sql-file-in-mysql-database-using-php

    public function executeSqlFile($sql_file) {

        $local_error_flag = null;    

        // Load the SQL file into memory as string
        $sql_file = file_get_contents($sql_file);

        // Replace database prefix placeholder with required prefix
        $sql_file = str_replace('#__', PRFX, $sql_file);    

        // Remove comment lines starting with /*
        $sql_file = preg_replace('/^\/\*.*\n/m', '', $sql_file);

        // Remove comment lines starting with --
        $sql_file = preg_replace('/^--.*\n/m', '', $sql_file);

        // Parse the SQL commands
        preg_match_all('/^[A-Z].*;\n/msU', $sql_file, $sql_statements);

        // Loop through preg_match() result
        foreach ($sql_statements['0'] as $sql) {       

            // Get rule name for output
            preg_match('/(^SET.*$|^.*`.*`)/U', $sql, $query_name);

            // Perform the query
            if(!$this->app->db->execute($sql)) {

                // Set the setup global error flag
                self::$setup_error_flag = true;

                // Set the local error flag
                $local_error_flag = true;

                // Log message
                $record = _gettext("Error performing SQL query").' : '. $query_name['0'];

                // Output message via smarty
                self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';

                // Log message to setup log            
                $this->writeRecordToSetupLog('install', $record, $this->app->db->ErrorMsg(), $sql);


            } else {

                // Log message
                $record = _gettext("Performed SQL query successfully").' : '. $query_name['0'];

                // Output message via smarty
                self::$executed_sql_results .= '<div style="color: green">'.$record.'</div>';

                // Log message to setup log            
                $this->writeRecordToSetupLog('install', $record);

            }

        }

        // Closing result statement
        if($local_error_flag) {

            // Log message
            $record = _gettext("One or more SQL rule has failed. Check the logs.");

            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: red;"><strong>'.$record.'</strong></div>';

            // Log message to setup log        
            $this->writeRecordToSetupLog('install', $record);

            return false;

        } else {

            // Log message
            $record = _gettext("All SQL rules have run successfully.");

            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: green;"><strong>'.$record.'</strong></div>';

            // Log message to setup log        
            $this->writeRecordToSetupLog('install', $record);

            return true;

        }           

    }

    ############################################
    #   Execute SQL File (line by line)        #  //  file() loads line by line, good for large imports (loads all the SQL file into memory)
    ############################################  // this is used for the upgrade SQL files, only

    // https://stackoverflow.com/questions/19751354/how-to-import-sql-file-in-mysql-database-using-php

    public function executeSqlFileLines($sql_file) {

        // Prevent undefined variable errors
        $local_error_flag = null; 
        $sql = null;
        $query_name = null;

        // Read in entire file (will be loaded from file line by because of below)
        $lines = file($sql_file);

        // Loop through each line  - file() loads each line in one by one
        foreach ($lines as $line)
        {        
            // Skip if the line is empty
            if ($line == '') {
                continue;
            }
            
            // Skip if the line just has newline characters
            if (preg_match("/^[\r|\n]+$/U", $line)) {
                continue;
            }
            
            // Skip if only spaces with optional newline characters
            if (preg_match('/^[ ]+[\r\n]$/U', $line)) {
                continue;
            }
            
            // Skip it if it's a comment ( -- or /* )
            if(substr($line, 0, 2) == '--' || substr($line, 1, 1) == '*' || substr($line, 2, 2) == '*/') {
                continue;            
            }
            
            // Replace new lines with a space
            $line = str_replace("\r", ' ', $line);
            $line = str_replace("\n", ' ', $line);

            // Replace database prefix placeholder with required prefix
            $line = str_replace('#__', PRFX, $line);

            // Add this line to the current segment
            $sql .= $line;

            // If it has a semicolon at the end, it's the end of the query
            if (substr(trim($line), -1, 1) == ';')
            {            
                
                /* Build a Query Name from the SQL query  */
                
                // Generic - Gives short and neat Rule Names
                if(preg_match('/^(DROP|RENAME|CREATE|INSERT|ALTER|UPDATE|DELETE)/U', $sql)) {
                    //$query_name = rtrim($sql, ';');  //'Unrecognised SQL Command' 'Unnamed SQL Query'
                    $query_name = preg_match('/^.*`'.PRFX.'.*`/U', $sql, $matches) ? $matches[0] : _gettext("Unusual SQL Command");                    
                    goto eof_query_name_building;
                }
                
                /* Not using separate rule matching at the minute, I will delete this if i dont use it.
                
                // DROP
                if(preg_match('/^DROP/U', $sql)) {
                    $query_name = $sql;
                    goto eof_query_name_building;
                }
                
                // RENAME
                if(preg_match('/^RENAME/U', $sql)) {
                    $query_name = $sql;
                    goto eof_query_name_building;
                }
                
                // CREATE
                if(preg_match('/^CREATE/U', $sql)) {
                    $query_name = $sql;
                    goto eof_query_name_building;
                }
                
                // INSERT
                if(preg_match('/^INSERT/U', $sql)) {
                    $query_name = $sql;
                    goto eof_query_name_building;
                }
                
                // ALTER
                if(preg_match('/^ALTER/U', $sql)) {
                    $query_name = $sql;
                    goto eof_query_name_building;
                }
                
                // UPDATE
                if(preg_match('/^UPDATE/U', $sql)) {
                    $query_name = $sql;
                    goto eof_query_name_building;
                }
                
                // DELETE
                if(preg_match('/^DELETE/U', $sql)) {
                    $query_name = $sql;
                    goto eof_query_name_building;
                }
                
                // Default Rule Name
                if(!isset($query_name)) {
                    $query_name = _gettext("Unrecognised SQL Command");   
                }*/
                
                eof_query_name_building:
                    
                /* EOF Rule Name Building */                
                 
                // Perform the query
                if(!$this->app->db->execute($sql)) {
                    
                    // Set the setup global error flag
                    self::$setup_error_flag = true;

                    // Set the local error flag
                    $local_error_flag = true;

                    // Log message
                    $record = _gettext("Error performing SQL query").' : '. $query_name;
                    
                    // Output message via smarty
                    self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>'; 

                    // Log message to setup log                
                    $this->writeRecordToSetupLog('upgrade', $record, $this->app->db->ErrorMsg(), $sql);

                } else {

                    // Log message
                    $record = _gettext("Performed SQL query successfully").' : '. $query_name;

                    // Output message via smarty
                    self::$executed_sql_results .= '<div style="color: green">'.$record.'</div>';

                    // Log message to setup log                
                    $this->writeRecordToSetupLog('upgrade', $record);

                }            

                // Reset temp SQL variable to empty ready for the next line
                $sql = '';
                
                // Reset Query name
                $query_name = '';

            }        

            continue;
            
        } 

        // Closing result statement
        if($local_error_flag) {

            // Log message
            $record = _gettext("One or more SQL rule has failed. Check the logs.");

            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: red;">'.$record.'</div>';

            // Log message to setup log        
            $this->writeRecordToSetupLog('upgrade', $record);

            return false;

        } else {

            // Log message
            $record = _gettext("All SQL rules have run successfully.");

            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: green;">'.$record.'</div>';

            // Log message to setup log        
            $this->writeRecordToSetupLog('upgrade', $record);

            return true;

        }

    }

    ############################################
    #  Generate Random Database prefix         #
    ############################################

    public function generateDatabasePrefix($not_this_prefix = null) {

        $acceptedChars = 'abcdefghijklmnopqrstuvwxyz';  // Lowercase to allow for Windows and Apache setups
        $max_offset = strlen($acceptedChars)-1;
        $prefix = '';

        for($i=0; $i < 4; $i++) {
            $prefix .= $acceptedChars[mt_rand(0, $max_offset)];
        }

        $prefix .= '_';    

        // This is to prevent using the MyITCRM prefix
        if($not_this_prefix) {
            if($prefix == $not_this_prefix) {
                $prefix = $this->generateDatabasePrefix($not_this_prefix);
            }
        }

        return $prefix;

    }
    
    ############################
    # Get MySQL Column Comment #
    ############################
    
    public function getColumnComment($table, $column) {
        
        $sql = "SELECT column_comment
                FROM information_schema.columns
                WHERE table_name = '$table'
                AND column_name LIKE '$column'";
        
        if(!$rs = $this->app->db->execute($sql)) { 
            
            // Set the setup global error flag
            self::$setup_error_flag = true;

            // Log message
            $record = _gettext("Failed to get Database Column Comment");

            // Log message to setup log            
            $this->writeRecordToSetupLog('install', $record, $this->app->db->ErrorMsg(), $sql);
            
            return false;      
            
        } else {
            
            return $rs->fields['column_comment'];
            
        }
        
    }    

    /** Install **/

    ############################################
    #   Install database                       # // this imports a phpMyAdmin .sql exported file (preg_match method)
    ############################################

    public function installDatabase($database_file) {

        // Execute the database SQL
        $this->executeSqlFile($database_file);

        /* Final stuff */

        // Final statement
        if(self::$setup_error_flag) {

            // Log message
            $record = _gettext("The database installation process failed, check the logs.");

            // Output message via smarty
            self::$executed_sql_results .= '<div>&nbsp;</div>';
            self::$executed_sql_results .= '<div style="color: red;"><strong>'.$record.'</strong></div>';

            // Log message to setup log        
            $this->writeRecordToSetupLog('install', $record);

        } else {

            // Log message
            $record = _gettext("The database installation process was successful.");

            // Output message via smarty
            self::$executed_sql_results .= '<div>&nbsp;</div>';
            self::$executed_sql_results .= '<div style="color: green;"><strong>'.$record.'</strong></div>';

            // Log message to setup log        
            $this->writeRecordToSetupLog('install', $record);

        }    

        // Return reflecting the installation status
        if(self::$setup_error_flag) {

            /* installation failed */

            // Set setup_error_flag used in smarty templates
            $this->app->smarty->assign('setup_error_flag', true);

            return false;

        } else {

            /* installation successful */

            return true;

        }

    }

    ############################################
    #   Set workorder start number             #
    ############################################

    public function setWorkorderStartNumber($start_number) {

        $sql = "ALTER TABLE ".PRFX."workorder_records auto_increment =".$this->app->db->qStr($start_number);

        // Perform the query
        if(!$this->app->db->execute($sql)) {

            // Set the setup global error flag
            self::$setup_error_flag = true;

            // Log message
            $record = _gettext("Error setting Work Order Start Number.");

            // Log message to setup log            
            $this->writeRecordToSetupLog('install', $record, $this->app->db->ErrorMsg(), $sql);

        } else {

            // Log message
            $record = _gettext("Work Order Start Number set successfully");

            // Log message to setup log            
            $this->writeRecordToSetupLog('install', $record);

        }   

        return;

    }

    ############################################
    #   Set invoice start number               #
    ############################################

    public function setInvoiceStartNumber($start_number) {

        $sql = "ALTER TABLE ".PRFX."invoice_records auto_increment =".$this->app->db->qStr($start_number);

        // Perform the query
        if(!$this->app->db->execute($sql)) {

            // Set the setup global error flag
            self::$setup_error_flag = true;

            // Log message
            $record = _gettext("Error setting Invoice Start Number.");

            // Log message to setup log            
            $this->writeRecordToSetupLog('install', $record, $this->app->db->ErrorMsg(), $sql);

        } else {

            // Log message
            $record = _gettext("Invoice Start Number set successfully");

            // Log message to setup log            
            $this->writeRecordToSetupLog('install', $record);

        }
        
        return;

    }

    /** Migrate **/

    ################################################
    #   migrate data from myitcrm (insert method)  #    // build 1 SQL statement and then execute - this can also be used to migrate from other systems
    ################################################

    public function migrateTable($qwcrm_table, $myitcrm_table, $column_mappings) {

        $local_error_flag = null;

        // Add division to seperate table migration public function results
        self::$executed_sql_results .= '<div>&nbsp;</div>';

        // Log message
        $record = _gettext("Beginning the migration of MyITCRM data into the QWcrm table").': `'.$qwcrm_table.'`';       

        // Result message
        self::$executed_sql_results .= '<div><strong><span style="color: green">'.$record.'</span></strong></div>';

        // Log message to setup log                
        $this->writeRecordToSetupLog('migrate', $record);        

       /* load the records from MyITCRM */

        $sql = "SELECT * FROM $myitcrm_table";

        if(!$rs = $this->app->db->execute($sql)) {

            // set error flag
            $local_error_flag = true; 

            // Log message
            $record = _gettext("Error reading the MyITCRM table").' `'.$myitcrm_table.'` - SQL: '.$sql.' - SQL Error: '.$this->app->db->ErrorMsg();        

            // Result message
            self::$executed_sql_results .= '<div><span style="color: red">'.$record.'</span></div>';

            // Log message to setup log                
            $this->writeRecordToSetupLog('migrate', $record);        

            // output error, could not load table so all of this table was skipped
            return false;

        } else {

            /* Load each single records and insert into QWcrm */ 

            // Record counters
            $records_processed  = 0;
            $records_failed     = 0;
            $records_successful = 0;

            // Loop through the MyITCRM records (single record, single insert)
            while(!$rs->EOF) {               

                $myitcrm_record = $rs->GetRowAssoc();

                /* Build the 'INSERT' part of the SQL statement */

                $insert_sql = "INSERT INTO `$qwcrm_table` (";
                foreach($column_mappings as $qwcrm_column => $myitcrm_column) {
                    $insert_sql .= "`$qwcrm_column`, ";            
                }
                $insert_sql = rtrim($insert_sql, ', ');           // remove the last ', '        
                $insert_sql .= ") VALUES" . "\n";

                /* Build 'VALUES' part of the SQL statement by mapping the MyITCRM record data to the QWcrm values */

                $values_sql = '(';
                foreach($column_mappings as $qwcrm_column => $myitcrm_column) {

                    // Skip looking for data in MyITCRM record if there is no corresponding field
                    if($myitcrm_column == '') {
                        $values_sql .= "'', ";
                        continue;                    
                    }

                    foreach($myitcrm_record as $myitcrm_record_column => $myitcrm_record_val) {

                        if($myitcrm_column == $myitcrm_record_column) {

                            // if the value is null set it to '' - This is a fix specific to MyITCRM database becvause it is dirty
                            if($myitcrm_record_val === null) { $myitcrm_record_val = ''; }

                            //$values_sql .= "'$myitcrm_record_val', ";
                            $values_sql .= $this->app->db->qStr($myitcrm_record_val).', ';
                            break;

                        }    

                    }                         

                }

                // Close the 'VALUES' SQL statement
                $values_sql = rtrim($values_sql, ', ');
                $values_sql .= ");";                

                /* Build and execute statement */

                // combine the 'INSERT' and 'VALUES' sections
                $sql = $insert_sql.$values_sql;

                // insert the migrated record into qwcrm
                if(!$this->app->db->execute($sql)) {  

                    /* Fail */

                    // set error flag
                    $local_error_flag = true;

                    // Advance the records_failed counter
                    ++$records_failed;

                    // Log message
                    $record = _gettext("Error migrating a MyITCRM record into QWcrm");

                    // Result message
                    self::$executed_sql_results .= '<div><span style="color: red">'.$record.' - SQL Error: '.$this->app->db->ErrorMsg().'</span></div>';                

                    // Log message to setup log                
                    $this->writeRecordToSetupLog('migrate', $record, $this->app->db->ErrorMsg(), $sql);                



                } else {

                    // Advance the records_successful counter
                    ++$records_successful;

                    // NO logging, otherwise log file would be huge

                    /* success  

                    // Log message
                    $record = _gettext("Successfully migrated a MyITCRM record into QWcrm");

                    // Result message
                    self::$executed_sql_results .= '<div><span style="color: green">'.$record.'</span></div>';

                    // Log message to setup log                
                    $this->write_record_to_setup_log('migrate', $record);

                    */                

                }

                // Advance the records_processed counter
                ++$records_processed;

                // Advance the INSERT loop to the next record            
                $rs->MoveNext();

            }// EOF While Loop

            // Output Record counters        
            self::$executed_sql_results .= '<div><span style="color: blue">'._gettext("MyITCRM Records Processed").': '.$records_processed.'</span></div>';
            self::$executed_sql_results .= '<div><span style="color: red">'._gettext("Records Failed To Migrate").': '.$records_failed.'</span></div>';
            self::$executed_sql_results .= '<div><span style="color: green">'._gettext("Records Successfuly Migrated").': '.$records_successful.'</span></div>';        

            // if there has been an error
            if($local_error_flag) {

                // Set the setup global error flag
                self::$setup_error_flag = true;

                // Log message
                $record = _gettext("Error migrating some records into QWcrm table").': `'.$qwcrm_table.'`';
                $record_additional = ' - '._gettext("MyITCRM Records Processed").': '.$records_processed.' - '._gettext("Records Failed To Migrate").': '.$records_failed.' - '._gettext("Records Successfuly Migrated").': '.$records_successful;

                // Result message
                self::$executed_sql_results .= '<div><strong><span style="color: red">'.$record.'</span></strong></div>';

                // Add division to seperate table migration public function results
                self::$executed_sql_results .= '<div>&nbsp;</div>';

                // Log message to setup log                
                $this->writeRecordToSetupLog('migrate', $record.$record_additional);

                return false;

            // if all ran successfully
            } else {

                // Log message
                $record = _gettext("Successfully migrated all records into QWcrm table").': `'.$qwcrm_table.'`';
                $record_additional = ' - '._gettext("MyITCRM Records Processed").': '.$records_processed.' - '._gettext("Records Failed To Migrate").': '.$records_failed.' - '._gettext("Records Successfuly Migrated").': '.$records_successful;

                // Result message
                self::$executed_sql_results .= '<div><strong><span style="color: green">'.$record.'</span></strong></div>';

                // Add division to seperate table migration public function results
                self::$executed_sql_results .= '<div>&nbsp;</div>';

                // Log message to setup log                
                $this->writeRecordToSetupLog('migrate', $record.$record_additional);

                return true;

            }             

        }

    }

    /** Upgrade **/    
        
    ############################################
    #   Get upgrade steps                      #
    ############################################

    public function getUpgradeSteps() {
        
        $upgrade_steps = array();
        $current_db_version = $this->app->system->general->getQwcrmDatabaseVersionNumber();
        $targetVersion = null;

        // This pattern scans within the folder for objects (files and directories)
        $directories = glob(SETUP_DIR.'upgrade/' . '*', GLOB_ONLYDIR);
        
        // Cycle through the directories discovered
        foreach ($directories as $directory) {
            
            // Remove path from directory and just leave the directory name (aka version number)
            $stepVersionNumber = basename($directory);
            
            // Convert version numbers from xx_xx_xx format to xx.xx.xx (this is so the numbers can be used through QWcrm. Comparison works for both formats)
            //$stepVersionNumber = str_replace('_', '.', $stepVersionNumber);
            
            // Add only the required upgrade steps - Is the version number less than or equal to the Current DB Version
            if(version_compare($current_db_version, $stepVersionNumber, '<')) {
                
                // Add to the new array
                $upgrade_steps[] = $stepVersionNumber;
                
                // Set the target version
                $targetVersion = $stepVersionNumber;
                
                // If break.txt exists stop adding further stages (to prevent timeouts on large upgrades)
                if(file_exists($directory.'/break.txt')) {
                    Setup::$split_database_upgrade = true;
                    $record  = _gettext("The upgrade process has been split to prevent server timeouts.").'<br>';
                    $record .= _gettext("This stage will upgrade QWcrm to version").' '.$targetVersion.'<br>';
                    $record .= _gettext("If there are more upgrade stages to perform, they will start immediately after this one.");
                    $this->writeRecordToSetupLog('upgrade', $record);
                    $this->app->system->variables->systemMessagesWrite('success', $record);
                    break;
                }
           
            }
            
        }
        
        // Sort version numbers in to ascending order
        usort($upgrade_steps, 'version_compare');
                
        return $upgrade_steps;
        
    }
    
    ############################################
    #   Process upgrade steps                  #
    ############################################

    public function processUpgradeSteps(&$VAR, $upgrade_steps = null) {
        
        // Cycle through each step
        foreach ($upgrade_steps as $upgrade_step) { 
            
            // Convert version numbers from xx.xx.xx format to xx_xx_xx (to allow building the correct file path)
            //$upgrade_step = str_replace('.', '_', $upgrade_step);

            // Include the upgrade.php
            require(SETUP_DIR.'upgrade/'.$upgrade_step.'/upgrade_routines.php');
            
            // Build Class name
            $class_name = 'Upgrade'.$upgrade_step;
            
            // Instantiate the step's class (this runs the upgrade routines)
            $upgrade_process = new $class_name($VAR);
            
            // Unset the class to save memory
            unset($upgrade_process);
           
        }
        
        /* Final stuff */

        // Final statement
        if(self::$setup_error_flag) {

            // Log message
            $record = _gettext("The database upgrade process failed, check the logs.");

            // Output message via smarty
            self::$executed_sql_results .= '<div>&nbsp;</div>';
            self::$executed_sql_results .= '<div style="color: red;"><strong>'.$record.'</strong></div>';

            // Log message to setup log        
            $this->writeRecordToSetupLog('upgrade', $record);

        } else {

            // Log message
            $record = _gettext("The database upgrade process was successful.");

            // Output message via smarty
            self::$executed_sql_results .= '<div>&nbsp;</div>';
            self::$executed_sql_results .= '<div style="color: green;"><strong>'.$record.'</strong></div>';

            // Log message to setup log        
            $this->writeRecordToSetupLog('upgrade', $record);

        }    

        // Return reflecting the installation status
        if(self::$setup_error_flag) {

            /* Upgrade failed */

            // Set setup_error_flag used in smarty templates
            $this->app->smarty->assign('setup_error_flag', true);

            return false;

        } else {

            /* Upgrade successful */

            return true;

        }
        
    }
    
    #######################################
    #  Databse - copy ColumnA To ColumnB  #
    #######################################

    public function copyColumnAToColumnB($table, $columnA, $columnB) {
        
        // Loop through all of the labour records
        $sql = "UPDATE `".PRFX.$table."` SET `".$columnB."` = `".$columnA."`";          

        if(!$this->app->db->execute($sql)) {
            
            // Set the setup global error flag
            self::$setup_error_flag = true;
            
            // Log Message
            $record = _gettext("Failed to copy ColumnA to ColumnB in the table").' `'.$table.'`.';
            
            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
            self::$executed_sql_results .= '<div>&nbsp;</div>';
            
            // Log message to setup log
            $this->writeRecordToSetupLog('correction', $record, $this->app->db->ErrorMsg(), $sql);
            
            return false;
            
        } else {

            // Log Message
            $record = _gettext("Successfully copied ColumnA to ColumnB in the table").' `'.$table.'`.';

            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: green">'.$record.'</div>';
            self::$executed_sql_results .= '<div>&nbsp;</div>';

            // Log message to setup log
            $this->writeRecordToSetupLog('correction', $record, $this->app->db->ErrorMsg(), $sql);

            return true;

        }   
                      
    }    

    /** Insert **/

    #############################################################
    #   Create a config file from the appropriate setup file    #
    #############################################################

    public function createConfigFileFromDefault($config_file) {

        $newfile = 'configuration.php';

        if (!copy($config_file, $newfile)) {
            die(_gettext("Failed to copy configuration file."));
        }

    }
    
    /** Delete Functions **/
    
    ############################################
    #   Delete Setup Directory                 #
    ############################################

    public function deleteSetupFolder() {

        // Build a success or failure message
        if($this->removeDirectory(SETUP_DIR)) {        

            // Build success messgae
            $record = _gettext("The Setup folder has been deleted successfully.");
            $message = $record;

            // Hide the delete button
            $this->app->system->general->toggleElementById('delete_setup_folder', 'hide');

            // Display the success message and login button
            $this->app->system->general->toggleElementById('setup_folder_removed', 'show');

            // Output the system message to the browser
            $this->app->system->variables->systemMessagesWrite('success', $message);
            $this->app->system->general->ajaxOutputSystemMessagesOnscreen();

        } else {

            // Build failure message
            $record = _gettext("The Setup folder failed to be deleted.");
            $message = $record.' '._gettext("You need to delete the folder manually.");

            // Hide the delete button
            $this->app->system->general->toggleElementById('delete_setup_folder_button', 'hide');

            // Output the system message to the browser
            $this->app->system->variables->systemMessagesWrite('danger', $message);
            $this->app->system->general->ajaxOutputSystemMessagesOnscreen();

        }

        // Log activity
        $this->app->system->general->writeRecordToActivityLog($record);    

        // Ajax has been done so die
        die();

    }

    ############################################
    #   Remove directory recusively            #
    ############################################

    public function removeDirectory($directory) {

        // Safety first
        if(!$directory || $directory == '' || $directory == '/') {
            die(_gettext("Do not delete the root folder and files!!!"));        
        }            

        // This pattern scans within the folder for objects + GLOB_MARK adds a slash to directories returned
        $objects = glob($directory . '*', GLOB_MARK);

        // Cycle through the objects discovered in the directory
        foreach ($objects as $object) {        

            is_dir($object) ? $this->removeDirectory($object) : unlink($object);

        }

        // Remove the supplied directory now it is empty
        return rmdir($directory) ? true : false;   

    }
    
    /** Checks **/
    
        /**
     * Gets PHP options.
     *
     * @return  array  Array of PHP config options
     *
     * @since   3.1
     */
    public function getPhpOptions()
    {
        $options = array();

        // Check the minimum PHP Version is met
        $option = new stdClass;
        $option->label  = _gettext("PHP Version").' >=  '.QWCRM_MINIMUM_PHP;
        $option->state  = version_compare(PHP_VERSION, QWCRM_MINIMUM_PHP, '>=');
        $option->notice = null;
        $options[] = $option;
        
        /* Check the maximum PHP Version is not exceeded
        $option = new stdClass;
        $option->label  = _gettext("PHP Version").' <= '.QWCRM_MAXIMUM_PHP;
        $option->state  = version_compare(PHP_VERSION, QWCRM_MAXIMUM_PHP, '<=');
        $option->notice = null;
        $options[] = $option;*/

        // Check for magic quotes gpc.
        $option = new stdClass;
        $option->label  = _gettext("Magic Quotes GPC Off");
        $option->state  = (ini_get('magic_quotes_gpc') == false);
        $option->notice = null;
        $options[] = $option;
        
        // Check for register globals.
        $option = new stdClass;
        $option->label  = _gettext("Register Globals Off");
        $option->state  = (ini_get('register_globals') == false);
        $option->notice = null;
        $options[] = $option;
        
        /* Check for zlib support.
        $option = new stdClass;
        $option->label  = _gettext("Native ZIP support");
        $option->state  = extension_loaded('zlib');
        $option->notice = null;
        $options[] = $option;*/
        
        // Check for XML support.
        $option = new stdClass;
        $option->label  = _gettext("XML Support");
        $option->state  = extension_loaded('xml');
        $option->notice = null;
        $options[] = $option;
        
        /* Check for database support.
        // We are satisfied if there is at least one database driver available.
        $available = JDatabaseDriver::getConnectors();
        $option = new stdClass;
        $option->label  = _gettext("Database Support:");
        $option->label .= '<br />(' . implode(', ', $available) . ')';
        $option->state  = count($available);
        $option->notice = null;
        $options[] = $option;*/

        // Check for mbstring options.
        if (extension_loaded('mbstring'))
        {
            // Check for default MB language.
            $option = new stdClass;
            $option->label  = _gettext("MB Language is Default");
            $option->state  = strtolower(ini_get('mbstring.language')) === 'neutral';
            $option->notice = $option->state ? null : _gettext("PHP mbstring language is not set to neutral. This can be set locally by entering <strong>php_value mbstring.language neutral</strong> in your <code>.htaccess</code> file.");
            $options[] = $option;

            // Check for MB public function overload.
            $option = new stdClass;
            $option->label  = _gettext("MB String Overload Off");
            $option->state  = ini_get('mbstring.func_overload') == 0;
            $option->notice = $option->state ? null : _gettext("PHP mbstring public function overload is set. This can be turned off locally by entering <strong>php_value mbstring.func_overload 0</strong> in your <code>.htaccess</code> file.");
            $options[] = $option;
        }

        /* Check for a missing native parse_ini_file implementation.
        $option = new stdClass;
        $option->label  = _gettext("INI Parser Support");
        $option->state  = $this->getIniParserAvailability();
        $option->notice = null;
        $options[] = $option;*/

        // Check for missing native json_encode / json_decode support.
        $option = new stdClass;
        $option->label  = _gettext("JSON Support");
        $option->state  = function_exists('json_encode') && function_exists('json_decode');
        $option->notice = null;
        $options[] = $option;

        /* Check for configuration file writable.
        $writable = (is_writable('configuration.php')
            || (!file_exists('configuration.php') && is_writable(QWCRM_BASE_PATH)));

        $option = new stdClass;
        $option->label  = 'configuration.php '._gettext("Writeable");
        $option->state  = $writable;
        $option->notice = $option->state ? null : _gettext("The 'configuration.php' file is either present and cannot be written too or you do not have permission to the 'configuration.php' in your QWcrm directory.");
        $options[] = $option;*/
                
        // MY Extensions        
        
        // Check for OpenSSL (openssl)
        $option = new stdClass;
        $option->label  = _gettext("OpenSSL Support");
        $option->state  = extension_loaded('openssl');
        $option->notice = $option->state ? null : _gettext("The PHP Extension 'openssl' needs to be enabled. OpenSSL is required for https:// protocol. ");
        $options[] = $option;
        
        // Check for cURL (curl)
        $option = new stdClass;
        $option->label  = _gettext("cURL Support");
        $option->state  = extension_loaded('curl');
        $option->notice = $option->state ? null : _gettext("The PHP Extension 'curl' needs to be enabled.");
        $options[] = $option;        
               
        // MY Settings
        
        /* Check for allow_url_fopen support        
        $option = new stdClass;
        $option->label  = _gettext("allow_url_fopen On");
        $option->state  = (bool) ini_get('allow_url_fopen');
        $option->notice = $option->state ? null : _gettext("The PHP Setting 'allow_url_fopen' needs to be enabled.");
        $options[] = $option;*/
        
        /* Check for max_execution_time support (seconds)
        $minimum_max_execution_time = 300;
        $option = new stdClass;        
        $option->label  = _gettext("max_execution_time").' >= '.$minimum_max_execution_time.'s';
        $option->state  = version_compare((int) ini_get('max_execution_time'), $minimum_max_execution_time, '>=');
        $option->notice = $option->state ? null : _gettext("The current Maximum Execution Time is").': '.(int) ini_get('max_execution_time').'s';
        $options[] = $option;*/
        
        /* Check for Minimum RAM (MB)
        $minimum_memory_limit = 32;
        $option = new stdClass;        
        $option->label  = _gettext("Minimum RAM").' >= '.$minimum_memory_limit.'MB';
        $option->state  = version_compare((int) ini_get('memory_limit'), $minimum_memory_limit, '>=');
        $option->notice = $option->state ? null : _gettext("The current Memory Limit is").': '.(int) ini_get('memory_limit').'MB';
        $options[] = $option;*/  
        
        // MY Functions        
        
        // Check for file_get_contents() support
        $option = new stdClass;
        $option->label  = _gettext("file_get_contents() Enabled");
        $option->state  = function_exists('file_get_contents');
        $option->notice = $option->state ? null : _gettext("The PHP Function 'file_get_contents()' needs to be enabled.");
        $options[] = $option;
        
        return $options;

    }

    /**
     * Checks if all of the mandatory PHP options are met.
     *
     * @return  boolean  True on success.
     *
     * @since   3.1
     */
    public function getPhpOptionsSufficient()
    {
        $result  = true;
        $options = $this->getPhpOptions();

        foreach ($options as $option)
        {
            if (!$option->state)
            {
                $result  = false;
            }
        }

        return $result;
        
    }
    
    /**
     * Gets PHP Settings.
     *
     * @return  array
     *
     * @since   3.1
     */
    public function getPhpSettings()
    {
        $settings = array();

        // Check for safe mode.
        $setting = new stdClass;
        $setting->label = _gettext("Safe Mode");
        $setting->state = (bool) ini_get('safe_mode');
        $setting->recommended = false;
        $setting->notice = null;
        $settings[] = $setting;

        // Check for display errors.
        $setting = new stdClass;
        $setting->label = _gettext("Display Errors");
        $setting->state = (bool) ini_get('display_errors');
        $setting->recommended = false;
        $setting->notice = null;
        $settings[] = $setting;

        // Check for file uploads.
        $setting = new stdClass;
        $setting->label = _gettext("File Uploads");
        $setting->state = (bool) ini_get('file_uploads');
        $setting->recommended = true;
        $setting->notice = null;
        $settings[] = $setting;

        // Check for magic quotes runtimes.
        $setting = new stdClass;
        $setting->label = _gettext("Magic Quotes Runtime");
        $setting->state = (bool) ini_get('magic_quotes_runtime');
        $setting->recommended = false;
        $setting->notice = null;
        $settings[] = $setting;

        // Check for output buffering.
        $setting = new stdClass;
        $setting->label = _gettext("Output Buffering");
        $setting->state = (int) ini_get('output_buffering') !== 0;
        $setting->recommended = false;
        $setting->notice = null;
        $settings[] = $setting;

        // Check for session auto-start.
        $setting = new stdClass;
        $setting->label = _gettext("Session Auto Start");
        $setting->state = (bool) ini_get('session.auto_start');
        $setting->recommended = false;
        $setting->notice = null;
        $settings[] = $setting;

        // Check for native ZIP support.
        $setting = new stdClass;
        $setting->label = _gettext("Zlib Compression Support");
        $setting->state = function_exists('zip_open') && function_exists('zip_read');
        $setting->recommended = true;
        $setting->notice = null;
        $settings[] = $setting;
        
        // My Extensions
        
        // Check for Internationalization (intl)
        $setting = new stdClass;
        $setting->label  = _gettext("Internationalization Support");
        $setting->state  = extension_loaded('intl');
        $setting->recommended = true;
        $setting->notice = $setting->state ? null : _gettext("Internationalization support is required for automatic language detection. ");
        $settings[] = $setting;
        
        // My Settings
        
        // My Functions
        
        // Check for locale_accept_from_http() support
        $setting = new stdClass;
        $setting->label  = _gettext("locale_accept_from_http()");
        $setting->state  = function_exists('locale_accept_from_http');        
        $setting->recommended = true;
        $setting->notice = $setting->state ? null : _gettext("The PHP Function 'locale_accept_from_http()' is required for automatic language detection. ");
        $settings[] = $setting;
        
        return $settings;
        
    }
    
    ###########################################################
    # Test Server Enviroment for compatibility to setup QWcrm #
    ###########################################################
    
    public function checkServerEnviromentCompatibility() {
        
        $compatibility_results = array();
        
        // https://www.w3schools.com/php/func_array_walk.asp
        
        // Walk through php_options and convert objects into arrays
        $php_options = $this->getPhpOptions();
        array_walk($php_options, function(&$value) {
            $value = get_object_vars($value);
        });
        
        // Walk through php_options and convert objects into arrays
        $php_settings = $this->getPhpSettings();
        array_walk($php_settings, function(&$value) {
            $value = get_object_vars($value);
        });
        
        // Build the result to be returned       
        $compatibility_results['php_options'] = $php_options;
        $compatibility_results['php_settings'] = $php_settings;        
        $compatibility_results['compatibility_status'] = $this->getPhpOptionsSufficient();
        
        return $compatibility_results;
        
    }
    
    ####################################################################
    #  Verify the database connection works with the supplied details  #
    ####################################################################

    public function checkDatabaseConnectionDetailsValid($db_host, $db_user, $db_pass, $db_name) {
        
        // This allows me to re-use config-registry to test the database connection
        $this->app->config->set('db_host', $db_host);
        $this->app->config->set('db_user', $db_user);
        $this->app->config->set('db_pass', $db_pass);
        $this->app->config->set('db_name', $db_name);

        // Set an error trap
        $this->app->config->set('test_db_connection', 'test');
        
        // Restart the database
        \Factory::$database = null;
        $this->app->db = \Factory::getDbo();

        // This public function will generate the error messages upstream as needed
        if($this->app->config->get('test_db_connection') == 'passed') {

            return true;

        } else {  

            return false;  

        }

    }

    #########################################################
    #  Check the MySQL version is high enough to run QWcrm  #  // Does not seem to be used
    #########################################################

    public function checkQwcrmMinimumMysqlVersionValid() {
        
        $mysqlVersion = $this->app->system->general->getMysqlVersion();

        if (version_compare($mysqlVersion, QWCRM_MINIMUM_MYSQL, '<')) {
            $msg = '<div style="color: red;">'._gettext("QWcrm requires MySQL").' '.QWCRM_MINIMUM_MYSQL.' '.'or later to run.'.' '._gettext("Your current version is").' '.$mysqlVersion.'</div>';
            $this->app->smarty->assign('msg_danger', $msg);
            return false;
            //die($msg);
        }

        return true;

    }
    
 
    
    /** Other **/
    


    ############################################
    #   Clean up after setup process           #
    ############################################

    public function setupFinished() {

        // Clear the Smarty cache
        $this->app->smarty->clearAllCache();

        // Clear the compiled templates directory
        $this->app->smarty->clearCompiledTemplate();

        return;

    }

    ############################################
    #  Write a record to the Setup Log         #  // Cannot be turned off - install/migrate/upgrade
    ############################################

    public function writeRecordToSetupLog($setup_type, $record, $database_error = null, $sql_query = null) {

        // Install and migrate does not have username or login_user_id available
        if(defined('QWCRM_SETUP')) {
            $username = '-';
            $login_user_id = '-';
        } else {
            $username = $this->app->user->login_username;
            $login_user_id = $this->app->user->login_user_id;
        }

        // prepare database error for the log
        $database_error = $this->app->system->general->prepareErrorData('error_database', $database_error);   

        // prepare SQL statement for the log (I have disabled logging SQL for security reasons)
        //$sql_query = $this->app->system->general->prepare_error_data('sql_query_for_log', $sql_query);    
        $sql_query = '';

        // Build log entry - perhaps use the apache time stamp below
        $log_entry = $_SERVER['REMOTE_ADDR'].','.$username.','.date("[d/M/Y:H:i:s O]", time()).','.$login_user_id.','.QWCRM_VERSION.','.$setup_type.',"'.$record.'","'.$database_error.'","'.$sql_query.'"'."\r\n";

        // Write log entry  
        if(!$fp = fopen(SETUP_LOG, 'a')) {        
            $this->app->system->page->forceErrorPage('file', __FILE__, __FUNCTION__, '', '', _gettext("Could not open the Setup Log to save the record."));
        }

        fwrite($fp, $log_entry);
        fclose($fp);    

        return;

    }
    
}