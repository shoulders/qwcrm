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

// Only allow the use of these functions when the /setup/ folder exists.
if (!is_dir(SETUP_DIR)) {      
    die(_gettext("You cannot use these functions without the setup folder."));        
}

class QSetup {

    public static $setup_error_flag = null;
    public static $executed_sql_results = null;    
    protected $smarty = null;    
    
    public function __construct(&$VAR) {
        
        $this->smarty = QFactory::getSmarty();
                
        // Prevent undefined variable errors && Get 'stage' from the submit button
        $VAR['stage'] = isset($VAR['submit']) ? $VAR['submit'] : null;
        $this->smarty->assign('stage', $VAR['stage']);
        $this->smarty->assign('setup_error_flag', self::$setup_error_flag);
        $this->smarty->assign('executed_sql_results', self::$executed_sql_results);
        
        // Set Max Execution time to 5 Minutes
        ini_set('max_execution_time', 300);
        
    }
    
    /** Common (not database) **/
    
    ############################
    # Get MySQL Column Comment #
    ############################
    
    public function get_column_comment($table, $column) {
        
        $db = QFactory::getDbo();
    
        $sql = "SELECT column_comment
                FROM information_schema.columns
                WHERE table_name = '$table'
                AND column_name LIKE '$column'";
        
        if(!$rs = $db->execute($sql)) { 
            
            return false;      
            
        } else {
            
            return $rs->fields['column_comment'];
            
        }
        
    }

    ############################################
    #   Clean up after setup process           #
    ############################################

    public function setup_finished() {

        // Clear the Smarty cache
        $this->smarty->clearAllCache();

        // Clear the compiled templates directory
        $this->smarty->clearCompiledTemplate();

        return;

    }

    ############################################
    #  Write a record to the Setup Log         #  // Cannot be turned off - install/migrate/upgrade
    ############################################

    public function write_record_to_setup_log($setup_type, $record, $database_error = null, $sql_query = null) {

        // Install and migrate does not have username or login_user_id available
        if(defined('QWCRM_SETUP')) {
            $username = '-';
            $login_user_id = '-';
        } else {
            $username = QFactory::getUser()->login_username;
            $login_user_id = QFactory::getUser()->login_user_id;
        }

        // prepare database error for the log
        $database_error = prepare_error_data('error_database', $database_error);   

        // prepare SQL statement for the log (I have disabled logging SQL for security reasons)
        //$sql_query = prepare_error_data('sql_query_for_log', $sql_query);    
        $sql_query = '';

        // Build log entry - perhaps use the apache time stamp below
        $log_entry = $_SERVER['REMOTE_ADDR'].','.$username.','.date("[d/M/Y:H:i:s O]", time()).','.$login_user_id.','.QWCRM_VERSION.','.$setup_type.',"'.$record.'","'.$database_error.'","'.$sql_query.'"'."\r\n";

        // Write log entry  
        if(!$fp = fopen(SETUP_LOG, 'a')) {        
            force_error_page('file', __FILE__, __FUNCTION__, '', '', _gettext("Could not open the Setup Log to save the record."));
        }

        fwrite($fp, $log_entry);
        fclose($fp);    

        return;

    }


    #############################################################
    #   Create a config file from the appropriate setup file    #
    #############################################################

    public function create_config_file_from_default($config_file) {

        $newfile = 'configuration.php';

        if (!copy($config_file, $newfile)) {
            die(_gettext("Failed to copy configuration file."));
        }

    }

    ############################################
    #   Delete Setup Directory                 #
    ############################################

    public function delete_setup_folder() {

        // Clear any onscreen notifications        
        ajax_clear_onscreen_notifications();

        // Build a success or failure message
        if(removeDirectory(SETUP_DIR)) {        

            // Build success messgae
            $record = _gettext("The Setup folder has been deleted successfully.");
            $system_message = _gettext("The Setup folder has been deleted successfully.");

            // Hide the delete button
            toggle_element_by_id('delete_setup_folder', 'hide');

            // Display the success message and login button
            toggle_element_by_id('setup_folder_removed', 'show');

            // Output the system message to the browser
            ajax_output_notifications_onscreen($system_message, '');

        } else {

            // Build failure message
            $record = _gettext("The Setup folder failed to be deleted.");
            $system_message = _gettext("The Setup folder has not been deleted. You need to delete the folder manually.");

            // Hide the delete button
            toggle_element_by_id('delete_setup_folder_button', 'hide');

            // Output the system message to the browser
            ajax_output_notifications_onscreen('', $system_message);

        }

        // Log activity
        write_record_to_activity_log($record);    

        // Ajax has been done so die
        die();

    }

    ############################################
    #   Remove directory recusively            #
    ############################################

    public function removeDirectory($directory) {

        // Safety first
        if(!$directory || $directory = '' || $directory == '/') {
            die(_gettext("Do not delete the root folder and files!!!"));        
        }            

        // This pattern scans within the folder for objects + GLOB_MARK adds a slash to directories returned
        $objects = glob($directory . '*', GLOB_MARK);

        // Cycle through the objects discovered in the directory
        foreach ($objects as $object) {        

            is_dir($object) ? removeDirectory($object) : unlink($object);

        }

        // Remove the supplied directory now it is empty
        return rmdir($directory) ? true : false;   

    }

    ############################################
    #         Submit config settings           #  // not currently used
    ############################################

    public function submit_qwcrm_config_settings($VAR) {

        // clear uneeded variables
        unset($VAR['page']);
        unset($VAR['submit']);
        unset($VAR['stage']);
        unset($VAR['theme']);    
        unset($VAR['component']);
        unset($VAR['page_tpl']);
        unset($VAR['information_msg']);
        unset($VAR['warning_msg']);   

        update_qwcrm_config_settings_file($VAR);

    }

    /** Database Common **/

    #########################################################
    #       Update a value in a specified record            #  // with and without 'WHERE' clause
    #########################################################

    public function update_record_value($select_table, $select_column, $record_new_value, $where_column = null, $where_record = null, $where_record_not_flag = null) {

        $db = QFactory::getDbo();    
        
        $sql = "UPDATE $select_table SET
                $select_column =". $db->qstr($record_new_value);

        if($where_column) {    
            $sql .=  "\nWHERE $where_column ".$where_record_not_flag."=".$db->qstr($where_record);
        }

        if(!$rs = $db->execute($sql)) { 

            // Set the setup global error flag
            self::$setup_error_flag = true;

            // Log message
            if($where_column) {
                $record = _gettext("Failed to update the record value").' `'.$select_column.'` '._gettext("where the records were matched in the columm").' `'.$where_column.'` '._gettext("by").' `'.$where_record.'` '.'` '._gettext("from the table").' `'.$select_table.'` ';
            } else {            
                $record = _gettext("Failed to update the value for the record").' `'.$select_column.'` '._gettext("to").' `'.$record_new_value.'` '._gettext("in the table").' `'.$select_table.'` ';
            }

            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
            self::$executed_sql_results .= '<div>&nbsp;</div>';

            // Log message to setup log        
            $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);

            return false;

        } else {

            // Log message             
            if($where_column) {
                $record = _gettext("Successfully updated the record value").' `'.$select_column.'` '._gettext("where the records were matched in the columm").' `'.$where_column.'` '._gettext("by").' `'.$where_record.'` '.'` '._gettext("from the table").' `'.$select_table.'` ';            
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

    public function update_column_values($table, $column, $current_value, $new_value) {

        $db = QFactory::getDbo();    
        
        if($current_value === '*') {

            $sql = "UPDATE $table SET
                    $column         =". $db->qstr( $new_value       );

        } else {

            $sql = "UPDATE $table SET
                    $column         =". $db->qstr( $new_value       )."                      
                    WHERE $column   =". $db->qstr( $current_value   );

        }

        if(!$rs = $db->execute($sql)) { 

            // Set the setup global error flag
            self::$setup_error_flag = true;

            // Log message
            $record = _gettext("Failed to update the values").' `'.$current_value.'` '._gettext("to").' `'.$new_value.'` '._gettext("in the columm").' `'.$column.'` '._gettext("from the table").' `'.$table.'` ';

            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
            self::$executed_sql_results .= '<div>&nbsp;</div>';        

            // Log message to setup log        
            $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);

            return false;

        } else {        

            // Affected Rows
            if(!$affected_rows = $db->affected_rows()) { $affected_rows = '0'; }

            // Log message
            $record = _gettext("Successfully updated the values").' `'.$current_value.'` '._gettext("to").' `'.$new_value.'` '._gettext("in the columm").' `'.$column.'` '._gettext("from the the table").' `'.$table.'` - '._gettext("Records Processed").': '.$affected_rows;

            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: green">'.$record.'</div>';
            self::$executed_sql_results .= '<div>&nbsp;</div>';

            // Log message to setup log        
            $this->write_record_to_setup_log('correction', $record);

            return true;        

        }    

    }

    ############################################
    #   Execute SQL File (preg_match method)   # // this imports a phpMyAdmin .sql exported file (loads all the SQL file into memory)
    ############################################

    // https://stackoverflow.com/questions/19751354/how-to-import-sql-file-in-mysql-database-using-php

    public function execute_sql_file($sql_file) {

        $db = QFactory::getDbo();    
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
            if(!$db->Execute($sql)) {

                // Set the setup global error flag
                self::$setup_error_flag = true;

                // Set the local error flag
                $local_error_flag = true;

                // Log message
                $record = _gettext("Error performing SQL query").' : '. $query_name['0'];

                // Output message via smarty
                self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';

                // Log message to setup log            
                $this->write_record_to_setup_log('install', $record, $db->ErrorMsg(), $sql);


            } else {

                // Log message
                $record = _gettext("Performed SQL query successfully").' : '. $query_name['0'];

                // Output message via smarty
                self::$executed_sql_results .= '<div style="color: green">'.$record.'</div>';

                // Log message to setup log            
                $this->write_record_to_setup_log('install', $record);

            }

        }

        // Closing result statement
        if($local_error_flag) {

            // Log message
            $record = _gettext("One or more SQL rule has failed. Check the logs.");

            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: red;"><strong>'.$record.'</strong></div>';

            // Log message to setup log        
            $this->write_record_to_setup_log('install', $record);

            return false;

        } else {

            // Log message
            $record = _gettext("All SQL rules have run successfully.");

            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: green;"><strong>'.$record.'</strong></div>';

            // Log message to setup log        
            $this->write_record_to_setup_log('install', $record);

            return true;

        }           

    }

    ############################################
    #   Execute SQL File (line by line)        #  //  file() loads line by line, good for large imports (loads all the SQL file into memory)
    ############################################

    // https://stackoverflow.com/questions/19751354/how-to-import-sql-file-in-mysql-database-using-php

    public function execute_sql_file_lines($sql_file) {

        $db = QFactory::getDbo();
        
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
                
                // Generic- Gives short and neat Rule Names
                if(preg_match('/^(DROP|RENAME|CREATE|INSERT|ALTER|UPDATE|DELETE)/U', $sql)) {
                    //$query_name = rtrim($sql, ';');  //'Unrecognised SQL Command' 'Unnamed SQL Query'
                    $query_name = preg_match('/^.*`'.PRFX.'.*`/U', $sql, $matches) ? $matches[0] : _gettext("Unrecognised SQL Command");                    
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
                if(!$db->Execute($sql)) {
                    
                    // Set the setup global error flag
                    self::$setup_error_flag = true;

                    // Set the local error flag
                    $local_error_flag = true;

                    // Log message
                    $record = _gettext("Error performing SQL query").' : '. $query_name;
                    
                    // Output message via smarty
                    self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>'; 

                    // Log message to setup log                
                    $this->write_record_to_setup_log('upgrade', $record, $db->ErrorMsg(), $sql);

                } else {

                    // Log message
                    $record = _gettext("Performed SQL query successfully").' : '. $query_name;

                    // Output message via smarty
                    self::$executed_sql_results .= '<div style="color: green">'.$record.'</div>';

                    // Log message to setup log                
                    $this->write_record_to_setup_log('upgrade', $record);

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
            $this->write_record_to_setup_log('upgrade', $record);

            return false;

        } else {

            // Log message
            $record = _gettext("All SQL rules have run successfully.");

            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: green;">'.$record.'</div>';

            // Log message to setup log        
            $this->write_record_to_setup_log('upgrade', $record);

            return true;

        }

    }

    ####################################################################
    #  Verify the database connection works with the supplied details  #
    ####################################################################

    public function verify_database_connection_details($db_host, $db_user, $db_pass, $db_name) {

        $conf = QFactory::getConfig();

        // This allows me to re-use config-registry to test the database connection
        $conf->set('db_host', $db_host);
        $conf->set('db_user', $db_user);
        $conf->set('db_pass', $db_pass);
        $conf->set('db_name', $db_name);

        // Set an error trap
        $conf->set('test_db_connection', 'test');

        // Fire up the database connection
        QFactory::getDbo();

        // This function will generate the error messages upstream as needed
        if($conf->get('test_db_connection') == 'passed') {

            return true;

        } else {  

            return false;  

        }

    }

    #########################################################
    #  Check the MySQL version is high enough to run QWcrm  #
    #########################################################

    public function validate_qwcrm_minimum_mysql_version() {

        if (version_compare(get_mysql_version(), QWCRM_MINIMUM_MYSQL, '<')) {
            $msg = '<div style="color: red;">'._gettext("QWcrm requires MySQL").' '.QWCRM_MINIMUM_MYSQL.' '.'or later to run.'.' '._gettext("Your current version is").' '.get_mysql_version().'</div>';
            $this->smarty->assign('warning_msg', $msg);
            return false;
            //die($msg);
        }

        return true;

    }

    ############################################
    #  Generate Random Database prefix         #
    ############################################

    public function generate_database_prefix($not_this_prefix = null) {

        $acceptedChars = 'abcdefghijklmnopqrstuvwxyz';  // Lowercase to allow for Windows and Apache setups
        $max_offset = strlen($acceptedChars)-1;
        $prefix = '';

        for($i=0; $i < 4; $i++) {
            $prefix .= $acceptedChars{mt_rand(0, $max_offset)};
        }

        $prefix .= '_';    

        // This is to prevent using the MyITCRM prefix
        if($not_this_prefix) {
            if($prefix == $not_this_prefix) {
                $prefix = generate_database_prefix($not_this_prefix);
            }
        }

        return $prefix;

    }

    /** Install **/

    ############################################
    #   Install database                       # // this imports a phpMyAdmin .sql exported file (preg_match method)
    ############################################

    public function install_database($database_file) {

        // Execute the database SQL
        $this->execute_sql_file($database_file);

        /* Final stuff */

        // Final statement
        if(self::$setup_error_flag) {

            // Log message
            $record = _gettext("The database installation process failed, check the logs.");

            // Output message via smarty
            self::$executed_sql_results .= '<div>&nbsp;</div>';
            self::$executed_sql_results .= '<div style="color: red;"><strong>'.$record.'</strong></div>';

            // Log message to setup log        
            $this->write_record_to_setup_log('install', $record);

        } else {

            // Log message
            $record = _gettext("The database installation process was successful.");

            // Output message via smarty
            self::$executed_sql_results .= '<div>&nbsp;</div>';
            self::$executed_sql_results .= '<div style="color: green;"><strong>'.$record.'</strong></div>';

            // Log message to setup log        
            $this->write_record_to_setup_log('install', $record);

        }    

        // Return reflecting the installation status
        if(self::$setup_error_flag) {

            /* installation failed */

            // Set setup_error_flag used in smarty templates
            $this->smarty->assign('setup_error_flag', true);


            return false;

        } else {

            /* installation successful */

            return true;

        }

    }

    ############################################
    #   Set workorder start number             #
    ############################################

    public function set_workorder_start_number($start_number) {

        $db = QFactory::getDbo();

        $sql = "ALTER TABLE ".PRFX."workorder_records auto_increment =".$db->qstr($start_number);

        $db->execute($sql);    

        return;

    }

    ############################################
    #   Set invoice start number               #
    ############################################

    public function set_invoice_start_number($start_number) {

        $db = QFactory::getDbo();

        $sql = "ALTER TABLE ".PRFX."invoice_records auto_increment =".$db->qstr($start_number);

        $db->execute($sql);   

        return;

    }

    /** Migrate **/

    ################################################
    #   migrate data from myitcrm (insert method)  #    // build 1 SQL statement and then execute - this can also be used to migrate from other systems
    ################################################

    public function migrate_table($qwcrm_table, $myitcrm_table, $column_mappings) {

        $db = QFactory::getDbo();        
        $local_error_flag = null;

        // Add division to seperate table migration function results
        self::$executed_sql_results .= '<div>&nbsp;</div>';

        // Log message
        $record = _gettext("Beginning the migration of MyITCRM data into the QWcrm table").': `'.$qwcrm_table.'`';       

        // Result message
        self::$executed_sql_results .= '<div><strong><span style="color: green">'.$record.'</span></strong></div>';

        // Log message to setup log                
        $this->write_record_to_setup_log('migrate', $record);        

       /* load the records from MyITCRM */

        $sql = "SELECT * FROM $myitcrm_table";

        if(!$rs = $db->execute($sql)) {

            // set error flag
            $local_error_flag = true; 

            // Log message
            $record = _gettext("Error reading the MyITCRM table").' `'.$myitcrm_table.'` - SQL: '.$sql.' - SQL Error: '.$db->ErrorMsg();        

            // Result message
            self::$executed_sql_results .= '<div><span style="color: red">'.$record.'</span></div>';

            // Log message to setup log                
            $this->write_record_to_setup_log('migrate', $record);        

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
                            $values_sql .= $db->qstr($myitcrm_record_val).', ';
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
                if(!$db->execute($sql)) {  

                    /* Fail */

                    // set error flag
                    $local_error_flag = true;

                    // Advance the records_failed counter
                    ++$records_failed;

                    // Log message
                    $record = _gettext("Error migrating a MyITCRM record into QWcrm");

                    // Result message
                    self::$executed_sql_results .= '<div><span style="color: red">'.$record.' - SQL Error: '.$db->ErrorMsg().'</span></div>';                

                    // Log message to setup log                
                    $this->write_record_to_setup_log('migrate', $record, $db->ErrorMsg(), $sql);                



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

                // Add division to seperate table migration function results
                self::$executed_sql_results .= '<div>&nbsp;</div>';

                // Log message to setup log                
                $this->write_record_to_setup_log('migrate', $record.$record_additional);

                return false;

            // if all ran successfully
            } else {

                // Log message
                $record = _gettext("Successfully migrated all records into QWcrm table").': `'.$qwcrm_table.'`';
                $record_additional = ' - '._gettext("MyITCRM Records Processed").': '.$records_processed.' - '._gettext("Records Failed To Migrate").': '.$records_failed.' - '._gettext("Records Successfuly Migrated").': '.$records_successful;

                // Result message
                self::$executed_sql_results .= '<div><strong><span style="color: green">'.$record.'</span></strong></div>';

                // Add division to seperate table migration function results
                self::$executed_sql_results .= '<div>&nbsp;</div>';

                // Log message to setup log                
                $this->write_record_to_setup_log('migrate', $record.$record_additional);

                return true;

            }             

        }

    }

    /** Upgrade **/    
        
    ############################################
    #   Get upgrade steps                      #
    ############################################

    public static function get_upgrade_steps() {
        
        $upgrade_steps = array();
        $current_db_version = get_qwcrm_database_version_number();

        // This pattern scans within the folder for objects (files and directories)
        $directories = glob(SETUP_DIR.'upgrade/' . '*', GLOB_ONLYDIR);
        
        // Convert directory names into numbers (not needed because version_compare() does this on the fly)
        //$directories = str_replace('_', '.', $directories);

        // Cycle through the directories discovered
        foreach ($directories as $directory) {
            
            // Remove path from directory and just leave the directory name
            $directoryNoPath = basename($directory);
            
            // Remove uneeded upgrade steps) - Is the version number less than or equal to the Current DB Version
            if(version_compare($directoryNoPath, $current_db_version, '>')) {
                
                // Add to the new array
                $upgrade_steps[] = $directory;
                
            }           
            
           // If break.txt exists stop adding further stages (to prevent timeouts on large upgrades)
           if(file_exists($directory.'/break.txt')) {
               break;
           }
            
        }
        
        // Sort version numbers in to ascending order
        usort($upgrade_steps, 'version_compare');
        
        // Convert directory names back into strings (not needed because version_compare() does this on the fly)
        //$upgrade_steps = str_replace('_', '.', $directories);
        
        return $upgrade_steps;
        
    }
    
    ############################################
    #   Process upgrade steps                  #
    ############################################

    public static function process_upgrade_steps(&$VAR, $upgrade_steps = null) {
        
        // Cycle through each step
        foreach ($upgrade_steps as $upgrade_step) {        

            // Include the upgrade.php
            require(SETUP_DIR.'upgrade/'.$upgrade_step.'/upgrade_routines.php');
            
            // Build Class name
            $class_name = 'Upgrade'.$upgrade_step;
            
            // Instantiate the step's class (this runs the upgrade routines)
            $upgrade_process = new $class_name($VAR);
            
            // Unset the class to save memory
            unset($upgrade_process);
           
        }
        
        return;
        
    }
    
    ##############################################################
    #   Convert a timestamp `column` to a MySQL DATE `column`    #
    ##############################################################
    
     public function column_timestamp_to_mysql_date($table, $column_timestamp, $column_primary_key) {
        
        $db = QFactory::getDbo();
        $mysql_date = null;
        $temp_prfx = 'temp_';
        $local_error_flag = null;
        $column_comment = null;
        
        // Get Column Comment if present
        if ($column_comment = $this->get_column_comment($table, $column_timestamp)) {
            $column_comment = "COMMENT '$column_comment' ";
        }
        
        // Create a temp column for the new DATE values
        $sql = "ALTER TABLE `".$table."` ADD `".$temp_prfx.$column_timestamp."` DATE NOT NULL AFTER `".$column_timestamp."`";        
        if(!$rs = $db->execute($sql)) { 
                        
            // Set the setup global error flag
            self::$setup_error_flag = true;
            
            // Set the local error flag
            $local_error_flag = true;
            
            // Log Message
            $record = _gettext("Failed to create a temporary column called").' `'.$temp_prfx.$column_timestamp.'` '._gettext("in the table").' `'.$table.'`.';
            
            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';

            // Log message to setup log
            $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);            
           
            // The process has failed so stop any further proccesing
            goto process_end;
            
        }              
        
        // Loop through all of the timestamps, calculate the correct Date and enter it into the temporary timestamp column
        $sql = "SELECT * FROM ".$table;
        if(!$rs = $db->Execute($sql)) {
                        
            // Set the setup global error flag
            self::$setup_error_flag = true;
            
            // Set the local error flag
            $local_error_flag = true;
            
            // Log Message
            $record = _gettext("Failed to select the records from the column").' `'.$temp_prfx.$column_timestamp.'` '._gettext("in the table").' `'.$table.'`.';
            
            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
            
            // Log message to setup log
            $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);
            
            // The process has failed so stop any further proccesing
            goto process_end;
            
            
        } else {

            // Loop through all records and 
            while(!$rs->EOF) { 

                // Convert the timestamp into the correct MySQL DATE
                $mysql_date = $this->timestamp_to_mysql_date_offset_aware($rs->fields[$column_timestamp]);

                // Update the temporary column record
                $sql = "UPDATE `".$table."` SET `".$temp_prfx.$column_timestamp."` = '".$mysql_date."' WHERE `".$table."`.`".$column_primary_key."` = '".$rs->fields[$column_primary_key]."';";
                if(!$temp_rs = $db->execute($sql)) { 
                                        
                    // Set the setup global error flag
                    self::$setup_error_flag = true;
                    
                    // Set the local error flag
                    $local_error_flag = true;
                    
                    // Log Message
                    $record = _gettext("Failed to update the record").' `'.$column_primary_key.'` '._gettext("in the column").' `'.$temp_prfx.$column_timestamp.'` '._gettext("in the table").' `'.$table.'`.';

                    // Output message via smarty
                    self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>'; 
                    
                    // Log message to setup log
                    $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);
                    
                    // The process has failed so stop any further proccesing
                    goto process_end;                    
                    
                } 
                                
                // Advance the INSERT loop to the next record            
                $rs->MoveNext();            

            }        
                
            // Remove the orginal timestamp column
            $sql = "ALTER TABLE `".$table."` DROP `".$column_timestamp."`";
            if(!$rs = $db->execute($sql)) { 
                                
                // Set the setup global error flag
                self::$setup_error_flag = true;
                
                // Set the local error flag
                $local_error_flag = true;
                
                // Log Message
                $record = _gettext("Failed to remove the original column").' `'.$column_timestamp.'` '._gettext("in the table").' `'.$table.'`.';

                // Output message via smarty
                self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
                
                // Log message to setup log
                $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);
                
                // The process has failed so stop any further proccesing
                goto process_end;
                
            }

            // Rename temporary column (temp_xxx) to the original column name
            $sql = "ALTER TABLE `".$table."` CHANGE `temp_".$column_timestamp."` `".$column_timestamp."` DATE NOT NULL ".$column_comment;
            if(!$rs = $db->execute($sql)) { 
                                
                // Set the setup global error flag
                self::$setup_error_flag = true;
                
                // Set the local error flag
                $local_error_flag = true;
                
                // Log Message
                $record = _gettext("Failed to rename the temporary column").' `'.$temp_prfx.$column_timestamp.'` '._gettext("to").' `'.$column_timestamp.'` '._gettext("in the table").' `'.$table.'`.';

                // Output message via smarty
                self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
                
                // Log message to setup log
                $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);
                
                // The process has failed so stop any further proccesing
                goto process_end;
                
            }            
        
        }
        
        process_end:
        
        // The conversion of the column, success and failed messages. 
        if($local_error_flag) {            
            
            // Log Message
            $record = _gettext("Failed to covert the column").' `'.$column_timestamp.'` '._gettext("from `timestamp` to MySQL `DATE`").' '._gettext("in the table").' `'.$table.'`. '._gettext("Check the previous error for the cause.");
            
            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
            self::$executed_sql_results .= '<div>&nbsp;</div>';
            
            // Log message to setup log
            $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);
            
            return false;
            
        } else {
            
            // Log Message
            $record = _gettext("Successfully converted the column").' `'.$column_timestamp.'` '._gettext("from `timestamp` to MySQL `DATE`").' '._gettext("in the table").' `'.$table.'`.';
            
            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: green">'.$record.'</div>';
            self::$executed_sql_results .= '<div>&nbsp;</div>';
            
            // Log message to setup log
            $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);
            
            return true;
            
        }        
    
    }
    
    ##############################################################
    #  Convert a timestamp `column` to a MySQL DATETIME `column` #
    ##############################################################
    
    public function column_timestamp_to_mysql_datetime($table, $column_timestamp, $column_primary_key) {
        
        $db = QFactory::getDbo();
        $mysql_datetime = null;
        $temp_prfx = 'temp_';
        $local_error_flag = null;
        $column_comment = null;
        
        // Get Column Comment if present
        if ($column_comment = $this->get_column_comment($table, $column_timestamp)) {
            $column_comment = "COMMENT '$column_comment' ";
        }
        
        // Create a new temp column for the new DATETIME values
        $sql = "ALTER TABLE `".$table."` ADD `".$temp_prfx.$column_timestamp."` DATETIME NOT NULL AFTER `".$column_timestamp."`";        
        if(!$rs = $db->execute($sql)) { 
            
            // Set the setup global error flag
            self::$setup_error_flag = true;
            
            // Set the local error flag
            $local_error_flag = true;
            
            // Log Message
            $record = _gettext("Failed to create a temporary column called").' `'.$temp_prfx.$column_timestamp.'` '._gettext("in the table").' `'.$table.'`.';
            
            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
            
            // Log message to setup log
            $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);
           
            // The process has failed so stop any further proccesing
            goto process_end;
            
        }              
        
        // Loop through all of the timestamps, calculate the correct Datetime and enter them into the temporary column
        $sql = "SELECT * FROM ".$table;
        if(!$rs = $db->Execute($sql)) {
            
            // Set the setup global error flag
            self::$setup_error_flag = true;
            
            // Set the local error flag
            $local_error_flag = true;
            
            // Log Message
            $record = _gettext("Failed to select all the records from the table").' `'.$table.'`.';
            
            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
            
            // Log message to setup log
            $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);
            
            // The process has failed so stop any further proccesing
            goto process_end;
            
        } else {

            // Loop through all records and 
            while(!$rs->EOF) { 

                // Convert the timestamp into the correct MySQL DATETIME
                $mysql_datetime = timestamp_mysql_datetime($rs->fields[$column_timestamp]);

                // Update the temporary column record
                $sql = "UPDATE `".$table."` SET `".$temp_prfx.$column_timestamp."` = '".$mysql_datetime."' WHERE `".$table."`.`".$column_primary_key."` = '".$rs->fields[$column_primary_key]."';";
                if(!$temp_rs = $db->execute($sql)) {
                    
                    // Set the setup global error flag
                    self::$setup_error_flag = true;
                    
                    // Log Message
                    $record = _gettext("Failed to update the record").' `'.$column_primary_key.'` '._gettext("in the column").' `'.$temp_prfx.$column_timestamp.'` '._gettext("in the table").' `'.$table.'`.';

                    // Output message via smarty
                    self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
                    
                    // Log message to setup log
                    $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);
                    
                    // The process has failed so stop any further proccesing
                    goto process_end;
                    
                } 
                                
                // Advance the INSERT loop to the next record            
                $rs->MoveNext();            

            }        
                
            // Remove the orginal timestamp column
            $sql = "ALTER TABLE `".$table."` DROP `".$column_timestamp."`";
            if(!$rs = $db->execute($sql)) {
                
                // Set the setup global error flag
                self::$setup_error_flag = true;
                
                // Log Message
                $record = _gettext("Failed to remove the original column").' `'.$column_timestamp.'` '._gettext("in the table").' `'.$table.'`.';

                // Output message via smarty
                self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
                
                // Log message to setup log
                $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);
                
                // The process has failed so stop any further proccesing
                goto process_end;   
                
            }

            // Rename temporary column (temp_xxx) to the original column name
            $sql = "ALTER TABLE `".$table."` CHANGE `temp_".$column_timestamp."` `".$column_timestamp."` DATETIME NOT NULL ".$column_comment;
            if(!$rs = $db->execute($sql)) {
                
                // Set the setup global error flag
                self::$setup_error_flag = true;
                
                // Set the local error flag
                $local_error_flag = true;
                
                // Log Message
                $record = _gettext("Failed to rename the temporary column").' `'.$temp_prfx.$column_timestamp.'` '._gettext("to").' `'.$column_timestamp.'` '._gettext("in the table").' `'.$table.'`.';

                // Output message via smarty
                self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
                
                // Log message to setup log
                $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);
                
                // The process has failed so stop any further proccesing
                goto process_end;
                
            }

        }
        
        process_end:
        
        // The conversion of the column, success and failed messages. 
        if($local_error_flag) {            
            
            // Log Message
            $record = _gettext("Failed to covert the column").' `'.$column_timestamp.'` '._gettext("from `timestamp` to MySQL `DATETIME`").' '._gettext("in the table").' `'.$table.'`. '._gettext("Check the previous error for the cause.");
            
            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
            self::$executed_sql_results .= '<div>&nbsp;</div>';
            
            // Log message to setup log
            $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);
            
            return false;
            
        } else {
            
            // Log Message
            $record = _gettext("Successfully converted the column").' `'.$column_timestamp.'` '._gettext("from `timestamp` to MySQL `DATETIME`").' '._gettext("in the table").' `'.$table.'`.';
            
            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: green">'.$record.'</div>';
            self::$executed_sql_results .= '<div>&nbsp;</div>';
            
            // Log message to setup log
            $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);
            
            return true;
            
        }          
    
    } 
    
    ##################################################################################################
    #  Convert a timestamp a MySQL DATE whilst compensating for (GMT/BST) || (Winter/Summer) offsets #
    ##################################################################################################
    
    public function timestamp_to_mysql_date_offset_aware($timestamp) {
        
        // If there is no timestamp return an empty MySQL DATE
        if(!$timestamp) {
            return '0000-00-00';
        }
        
        // If the timestamp already is a proper date in the format xxxx/xx/xx 00:00 then timestamp is correct 'as is' (there is no offset)
        if(preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2} 00:00:00$/', date('Y-m-d H:i:s', $timestamp))) {              
            return timestamp_mysql_date($timestamp);           
        }
        
        // Calculate backward difference
        $backward = $timestamp - 86400;         // This removes 24 hours from the timestamp - This ensures that a difference exists for calculations
        $backward = date('Y-m-d', $backward);   // Convert the timestamp into a string which removes the hours and minutes
        $backward = strtotime($backward);       // Convert back to a timestamp, but now it is a perfect date xx/xx/xx 00:00 (with no hours or minutes i.e. 00:00)      
        $backward = $backward - $timestamp;     // This calculates the difference in seconds between the backwards date and the timestamp date
        $backward = $backward % 86400;          // This removes all full days (86400 seconds) from the difference and leaves the remainder which is the offset
        
        // Calculate the forward difference
        $forward = $timestamp + 86400;          // This adds 24 hours to the timestamp - This ensures that a difference exists for calculations
        $forward = date('Y-m-d', $forward);     // Convert the timestamp into a string which removes the hours and minutes
        $forward = strtotime($forward);         // Convert back to a timestamp, but now it is a perfect date xx/xx/xx 00:00 (with no hours or minutes i.e. 00:00)       
        $forward = $forward - $timestamp;       // This calculates the difference in seconds between the backwards date and the timestamp date
        $forward = $forward % 86400;            // This removes all full days (86400 seconds) from the difference and leaves the remainder which is the offset
        
        // The correct direction will have the smallest difference and therefore will have the correct offset (ignores -ve & +ve)
        if(abs($backward) < abs($forward)) {            
            $offset = $backward;
        } elseif(abs($backward) > abs($forward))  {            
            $offset = $forward;          
        } else {            
            // If there is no difference, the date is already correct (I have already checked for this with preg_match() at the top) 
            $offset = 0;
        }

        // Apply the selected time offset too correct the timestamp
        $corrected_timestamp = $timestamp + $offset;
     
        // Return the correct date in MySQL DATE format
        return timestamp_mysql_date($corrected_timestamp);        
        
    } 
    
    ###########################################################
    # Test Server Enviroment for compatibility to setup QWcrm #
    ###########################################################
    
    public function test_server_enviroment_compatibility() {
        
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

        // Check the PHP Version.
        $option = new stdClass;
        $option->label  = _gettext("PHP Version").' >=  '.QWCRM_MINIMUM_PHP;
        $option->state  = version_compare(PHP_VERSION, QWCRM_MINIMUM_PHP, '>=');
        $option->notice = null;
        $options[] = $option;

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

            // Check for MB function overload.
            $option = new stdClass;
            $option->label  = _gettext("MB String Overload Off");
            $option->state  = ini_get('mbstring.func_overload') == 0;
            $option->notice = $option->state ? null : _gettext("PHP mbstring function overload is set. This can be turned off locally by entering <strong>php_value mbstring.func_overload 0</strong> in your <code>.htaccess</code> file.");
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
        
        // Check for allow_url_fopen support        
        $option = new stdClass;
        $option->label  = _gettext("allow_url_fopen On");
        $option->state  = (bool) ini_get('allow_url_fopen');
        $option->notice = $option->state ? null : _gettext("The PHP Setting 'allow_url_fopen' needs to be enabled.");
        $options[] = $option;
        
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
     
    #######################################################################
    #  Parse Voucher records and populate with appropriate workorder_id  #
    #######################################################################

    function voucher_correct_workorder_id() {
        
        $db = QFactory::getDbo();        
        
        $local_error_flag = null;                     
        
        // Loop through all of the Voucher records
        $sql = "SELECT * FROM ".PRFX."voucher_records";
        if(!$rs = $db->Execute($sql)) {
            
            // Set the setup global error flag
            self::$setup_error_flag = true;
            
            // Set the local error flag
            $local_error_flag = true;
            
            // Log Message
            $record = _gettext("Failed to select all the records from the table").' `voucher_records`.';
            
            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
            
            // Log message to setup log
            $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);
            
            // The process has failed so stop any further proccesing
            goto process_end;
            
        } else {

            // Loop through all records, decide and set each Voucher's status
            while(!$rs->EOF) { 
                
                // Get the workorder_id from the relevant invoice record
                $sql = "SELECT workorder_id FROM ".PRFX."invoice_records WHERE invoice_id = ".$rs->fields['invoice_id'];
                               
                // Run the SQL
                if(!$temp_rs = $db->execute($sql)) {
                    
                    // Set the setup global error flag
                    self::$setup_error_flag = true;
                    
                    // Set the local error flag
                    $local_error_flag = true;
                    
                    // Log Message                    
                    $record = _gettext("Failed to update the `workorder_id` for the Voucher record").' '.$rs->fields['voucher_id'];
                    
                    // Output message via smarty
                    self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
                    
                    // Log message to setup log
                    $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);
                    
                    // The process has failed so stop any further proccesing
                    goto process_end;
                    
                } else {
                    
                    // Update gifcert record with the new workorder_id
                    $this->update_record_value(PRFX.'voucher_records', 'workorder_id', $temp_rs->fields['workorder_id'], 'voucher_id', $rs->fields['voucher_id']);
                    
                }
                                
                // Advance the INSERT loop to the next record            
                $rs->MoveNext();            

            }               

        }
        
        process_end:
        
        // Success and fail messages for this whole process (i.e. not one record)
        if($local_error_flag) {            
            
            // Log Message
            $record = _gettext("Failed to complete assigning `workorder_id` to all Voucher records.");            
            
            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
            self::$executed_sql_results .= '<div>&nbsp;</div>';
            
            // Log message to setup log
            $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);
            
            return false;
            
        } else {
            
            // Log Message
            $record = _gettext("Successfully completed assigning `workorder_id` to all Voucher records.");
            
            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: green">'.$record.'</div>';
            self::$executed_sql_results .= '<div>&nbsp;</div>';
            
            // Log message to setup log
            $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);
            
            return true;
            
        }          
    
    } 

    
    #################################
    #  Voucher correct expiry date  #
    #################################

    function voucher_correct_expiry_date() {
        
        $db = QFactory::getDbo();        
        
        $local_error_flag = null;

        // Loop through all of the Voucher records
        $sql = "SELECT * FROM ".PRFX."voucher_records";
        if(!$rs = $db->Execute($sql)) {
            
            // Set the setup global error flag
            self::$setup_error_flag = true;
            
            // Set the local error flag
            $local_error_flag = true;
            
            // Log Message
            $record = _gettext("Failed to select all the records from the table").' `voucher_records`.';
            
            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
            
            // Log message to setup log
            $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);
            
            // The process has failed so stop any further proccesing
            goto process_end;
            
        } else {

            // Loop through all records, decide and set each Voucher's status
            while(!$rs->EOF) { 
                                
                // Correct the Voucher expiry date - 00:00:00 to 23:59:59
                $correct_expiry_date = preg_replace("00:00:00", "23:59:59", $rs->fields['workorder_id']);
                                 
                // Update gifcert with the new expiry date
                $this->update_record_value(PRFX.'voucher_records', 'expiry_date', $correct_expiry_date , 'voucher_id', $rs->fields['voucher_id']);
                    
            }
                                
            // Advance the INSERT loop to the next record            
            $rs->MoveNext();            

        }               

        process_end:
        
        // Success and fail messages for this whole process (i.e. not one record)
        if($local_error_flag) {            
            
            // Log Message
            $record = _gettext("Failed to complete converting MySQL `date` column to `datetime`");            
            
            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
            self::$executed_sql_results .= '<div>&nbsp;</div>';
            
            // Log message to setup log
            $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);
            
            return false;
            
        } else {
            
            // Log Message
            $record = _gettext("Successfully completed converting MySQL `date` column to `datetime`");
            
            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: green">'.$record.'</div>';
            self::$executed_sql_results .= '<div>&nbsp;</div>';
            
            // Log message to setup log
            $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);
            
            return true;
            
        }          
    
    }  
    
    #################################################################
    #  Parse Voucher records and populate with appropriate status   #  // This is after conversion to mysql DATE
    #################################################################

    function voucher_correct_status() {
        
        $db = QFactory::getDbo();        
        
        $local_error_flag = null;                     
        
        // Loop through all of the Voucher records
        $sql = "SELECT * FROM ".PRFX."voucher_records";
        if(!$rs = $db->Execute($sql)) {
            
            // Set the setup global error flag
            self::$setup_error_flag = true;
            
            // Set the local error flag
            $local_error_flag = true;
            
            // Log Message
            $record = _gettext("Failed to select all the records from the table").' `voucher_records`.';
            
            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
            
            // Log message to setup log
            $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);
            
            // The process has failed so stop any further proccesing
            goto process_end;
            
        } else {

            // Loop through all records, decide and set each Voucher's status
            while(!$rs->EOF) { 

                // Set qualifying Vouchers to redeemed status
                if($rs->fields['redeem_date'] != '0000-00-00 00:00:00') {
                    
                    $close_date = $rs->fields['redeem_date'];
                    $status = 'redeemed';                    
                    $blocked = 1;
                
                // Set qualifying Vouchers to expired status
                } elseif (mysql_datetime() > $rs->fields['expiry_date']) {
                    
                    $close_date = $rs->fields['expiry_date'];
                    $status = 'expired';                    
                    $blocked = 1;
                
                // If not redeemed or expired it must be unused
                } else {
                    
                    $close_date = '0000-00-00 00:00:00';
                    $status = 'unused';                    
                    $blocked = 0;
                    
                }
                
                $sql = "UPDATE `".PRFX."voucher_records` SET
                        `close_date` = ".$status.",
                        `status` = ".$status.",                        
                        `blocked` = ".$blocked."
                        WHERE `gifcert_id` = ".$rs->fields['voucher_id'];
                
                
                // Run the SQL
                if(!$temp_rs = $db->execute($sql)) {
                    
                    // Set the setup global error flag
                    self::$setup_error_flag = true;
                    
                    // Set the local error flag
                    $local_error_flag = true;
                    
                    // Log Message                    
                    $record = _gettext("Failed to update the `status` for the Voucher record").' '.$rs->fields['voucher_id'];
                    
                    // Output message via smarty
                    self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
                    
                    // Log message to setup log
                    $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);
                    
                    // The process has failed so stop any further proccesing
                    goto process_end;
                    
                } 
                                
                // Advance the INSERT loop to the next record            
                $rs->MoveNext();            

            }               

        }
        
        process_end:
        
        // Success and fail messages for this whole process (i.e. not one record)
        if($local_error_flag) {            
            
            // Log Message
            $record = _gettext("Failed to complete assigning `status` to all Voucher records.");            
            
            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
            self::$executed_sql_results .= '<div>&nbsp;</div>';
            
            // Log message to setup log
            $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);
            
            return false;
            
        } else {
            
            // Log Message
            $record = _gettext("Successfully completed assigning `status` to all Voucher records.");
            
            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: green">'.$record.'</div>';
            self::$executed_sql_results .= '<div>&nbsp;</div>';
            
            // Log message to setup log
            $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);
            
            return true;
            
        }          
    
    } 
    
    #################################################################
    #  Recalculate Labour totals because of the new VAT system      #
    #################################################################

    function invoice_labour_correct_totals() {
        
        $db = QFactory::getDbo();        
        
        $local_error_flag = null;                     
        
        // Loop through all of the labour records
        $sql = "SELECT *
                FROM ".PRFX."invoice_labour";                

        if(!$rs = $db->Execute($sql)) {
            
            // Set the setup global error flag
            self::$setup_error_flag = true;
            
            // Set the local error flag
            $local_error_flag = true;
            
            // Log Message
            $record = _gettext("Failed to select all the records from the table").' `invoice_labour`.';
            
            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
            
            // Log message to setup log
            $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);
            
            // The process has failed so stop any further proccesing
            goto process_end;
            
        } else {

            // Loop through all records, decide and set each labour items's status
            while(!$rs->EOF) { 
                
                $invoice_details = get_invoice_details($rs->fields['invoice_id']);          
                
                $vat_type = $invoice_details['tax_system'] == 'vat_standard' ? 'standard' : 'none';
                $vat_rate = $invoice_details['tax_system'] == 'vat_standard' ? $rs->fields['sales_tax_rate'] : 0.00;
                
                $item_totals = calculate_invoice_item_sub_totals($invoice_details['tax_system'], $rs->fields['unit_qty'], $rs->fields['unit_net'], $rs->fields['sales_tax_rate'], $vat_rate);
                
                $sql = "UPDATE `".PRFX."invoice_labour` SET
                        `tax_system`          = ".$db->qstr($invoice_details['tax_system']).",
                        `vat_type`          = ".$db->qstr($vat_type)."                       
                        `vat_rate`          = ".$vat_rate."                           
                        `unit_vat`          = ".$item_totals['unit_vat'].",
                        `unit_gross`        = ".$item_totals['unit_gross'].",                        
                        `sub_total_net`     = ".$item_totals['sub_total_net'].",
                        `sub_total_vat`     = ".$item_totals['sub_total_vat'].",
                        `sub_total_gross`   = ".$item_totals['sub_total_gross']."
                        WHERE `gifcert_id`  = ".$rs->fields['invoice_labour_id'];                
                
                // Run the SQL
                if(!$temp_rs = $db->execute($sql)) {
                    
                    // Set the setup global error flag
                    self::$setup_error_flag = true;
                    
                    // Set the local error flag
                    $local_error_flag = true;
                    
                    // Log Message                    
                    $record = _gettext("Failed to update the `totals` for the labour record").' '.$rs->fields['invoice_labour_id'];
                    
                    // Output message via smarty
                    self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
                    
                    // Log message to setup log
                    $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);
                    
                    // The process has failed so stop any further proccesing
                    goto process_end;
                    
                } 
                                
                // Advance the INSERT loop to the next record            
                $rs->MoveNext();            

            }               

        }
        
        process_end:
        
        // Success and fail messages for this whole process (i.e. not one record)
        if($local_error_flag) {            
            
            // Log Message
            $record = _gettext("Failed to complete updating `totals` for all labour records.");            
            
            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
            self::$executed_sql_results .= '<div>&nbsp;</div>';
            
            // Log message to setup log
            $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);
            
            return false;
            
        } else {
            
            // Log Message
            $record = _gettext("Successfully completed updating `totals` for all labour records.");
            
            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: green">'.$record.'</div>';
            self::$executed_sql_results .= '<div>&nbsp;</div>';
            
            // Log message to setup log
            $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);
            
            return true;
            
        }          
    
    } 
    
    #################################################################
    #  Recalculate Parts totals because of the new VAT system       #
    #################################################################

    function invoice_parts_correct_totals() {
        
        $db = QFactory::getDbo();        
        
        $local_error_flag = null;                     
        
        // Loop through all of the labour records
        $sql = "SELECT *
                FROM ".PRFX."invoice_parts";                

        if(!$rs = $db->Execute($sql)) {
            
            // Set the setup global error flag
            self::$setup_error_flag = true;
            
            // Set the local error flag
            $local_error_flag = true;
            
            // Log Message
            $record = _gettext("Failed to select all the records from the table").' `invoice_parts`.';
            
            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
            
            // Log message to setup log
            $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);
            
            // The process has failed so stop any further proccesing
            goto process_end;
            
        } else {

            // Loop through all records, decide and set each parts items's status
            while(!$rs->EOF) { 
                
                $invoice_details = get_invoice_details($rs->fields['invoice_id']);          
                
                $vat_type = $invoice_details['tax_system'] == 'vat_standard' ? 'standard' : 'none';
                $vat_rate = $invoice_details['tax_system'] == 'vat_standard' ? $rs->fields['sales_tax_rate'] : 0.00;
                
                $item_totals = calculate_invoice_item_sub_totals($invoice_details['tax_system'], $rs->fields['unit_qty'], $rs->fields['unit_net'], $rs->fields['sales_tax_rate'], $vat_rate);
                
                $sql = "UPDATE `".PRFX."invoice_parts` SET
                        `tax_system`          = ".$db->qstr($invoice_details['tax_system']).",
                        `vat_type`          = ".$db->qstr($vat_type)."                       
                        `vat_rate`          = ".$vat_rate."                           
                        `unit_vat`          = ".$item_totals['unit_vat'].",
                        `unit_gross`        = ".$item_totals['unit_gross'].",                        
                        `sub_total_net`     = ".$item_totals['sub_total_net'].",
                        `sub_total_vat`     = ".$item_totals['sub_total_vat'].",
                        `sub_total_gross`   = ".$item_totals['sub_total_gross']."
                        WHERE `gifcert_id`  = ".$rs->fields['invoice_labour_id'];                
                
                // Run the SQL
                if(!$temp_rs = $db->execute($sql)) {
                    
                    // Set the setup global error flag
                    self::$setup_error_flag = true;
                    
                    // Set the local error flag
                    $local_error_flag = true;
                    
                    // Log Message                    
                    $record = _gettext("Failed to update the `totals` for the parts record").' '.$rs->fields['invoice_labour_id'];
                    
                    // Output message via smarty
                    self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
                    
                    // Log message to setup log
                    $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);
                    
                    // The process has failed so stop any further proccesing
                    goto process_end;
                    
                } 
                                
                // Advance the INSERT loop to the next record            
                $rs->MoveNext();            

            }               

        }
        
        process_end:
        
        // Success and fail messages for this whole process (i.e. not one record)
        if($local_error_flag) {            
            
            // Log Message
            $record = _gettext("Failed to complete updating `totals` for all parts records.");            
            
            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: red">'.$record.'</div>';
            self::$executed_sql_results .= '<div>&nbsp;</div>';
            
            // Log message to setup log
            $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);
            
            return false;
            
        } else {
            
            // Log Message
            $record = _gettext("Successfully completed updating `totals` for all parts records.");
            
            // Output message via smarty
            self::$executed_sql_results .= '<div style="color: green">'.$record.'</div>';
            self::$executed_sql_results .= '<div>&nbsp;</div>';
            
            // Log message to setup log
            $this->write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);
            
            return true;
            
        }          
    
    }     
    
}