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

/** Insert Functions **/

/** Get Functions **/

/** Update Functions **/

/** Close Functions **/

/** Delete Functions **/

/** Other Functions **/

/** Common **/

#########################################################
#       update a value in a specified record            #
#########################################################

function update_record_value($select_table, $select_column, $record_identifier, $record_column, $record_new_value) {
    
    $db = QFactory::getDbo();    
    global $executed_sql_results;
    global $setup_error_flag;
    
    $sql = "UPDATE $select_table SET
            $record_column         =". $db->qstr( $record_new_value )."                      
            WHERE $select_column   =". $db->qstr( $record_identifier  );

    if(!$rs = $db->execute($sql)) { 
        
        // Set the setup global error flag
        $setup_error_flag = true;
        
        // Log message
        $record = _gettext("Failed to update the value").' '._gettext("for the record").' `'.$record_identifier.'` '._gettext("to").' `'.$record_new_value.'` '._gettext("in the columm").' `'.$record_column.'` '._gettext("from the table").' `'.$select_table.'` ';

        // Output message via smarty
        $executed_sql_results .= '<div style="color: red">'.$record.'</div>';
        $executed_sql_results .= '<div>&nbsp;</div>';
        
        // Log message to setup log        
        write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);
        
        return false;
        
    } else {
        
        // Log message
        $record = _gettext("Successfully updated the value").' '._gettext("for the record").' `'.$record_identifier.'` '._gettext("to").' `'.$record_new_value.'` '._gettext("in the columm").' `'.$record_column.'` '._gettext("from the table").' `'.$select_table.'` ';
                
        // Output message via smarty - to reduce onscreen output i have disabled success output, it is still logged
        //$executed_sql_results .= '<div style="color: green">'.$record.'</div>';
        //$executed_sql_results .= '<div>&nbsp;</div>';
        
        // Log message to setup log        
        write_record_to_setup_log('correction', $record);
        
        return true;
        
        
    }    
    
}

#########################################################
#   update all matching values in a column to new value #
#########################################################

function update_column_values($table, $column, $current_value, $new_value) {
    
    $db = QFactory::getDbo();    
    global $executed_sql_results;
    global $setup_error_flag;
    
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
        $setup_error_flag = true;
        
        // Log message
        $record = _gettext("Failed to update the values").' `'.$current_value.'` '._gettext("to").' `'.$new_value.'` '._gettext("in the columm").' `'.$column.'` '._gettext("from the table").' `'.$table.'` ';

        // Output message via smarty
        $executed_sql_results .= '<div style="color: red">'.$record.'</div>';
        $executed_sql_results .= '<div>&nbsp;</div>';        
        
        // Log message to setup log        
        write_record_to_setup_log('correction', $record, $db->ErrorMsg(), $sql);
        
        return false;
        
    } else {        
                
        // Affected Rows
        if(!$affected_rows = $db->affected_rows()) { $affected_rows = '0'; }
        
        // Log message
        $record = _gettext("Successfully updated the values").' `'.$current_value.'` '._gettext("to").' `'.$new_value.'` '._gettext("in the columm").' `'.$column.'` '._gettext("from the the table").' `'.$table.'` - '._gettext("Records Processed").': '.$affected_rows;
                
        // Output message via smarty
        $executed_sql_results .= '<div style="color: green">'.$record.'</div>';
        $executed_sql_results .= '<div>&nbsp;</div>';
        
        // Log message to setup log        
        write_record_to_setup_log('correction', $record);
        
        return true;
        
        
    }    
    
}

############################################
#   Execute SQL File (preg_match method)   # // this imports a phpMyAdmin .sql exported file
############################################

// https://stackoverflow.com/questions/19751354/how-to-import-sql-file-in-mysql-database-using-php

function execute_sql_file($sql_file) {
    
    $db = QFactory::getDbo();    
    global $executed_sql_results;
    global $setup_error_flag;    
    
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
    
    // Loop through preg_match() result
    foreach ($sql_statements['0'] as $sql) {       
        
        // Get rule name for output
        preg_match('/(^SET.*$|^.*`.*`)/U', $sql, $query_name);
        
       // Perform the query
        if(!$rs = $db->Execute($sql)) {
            
            // Set the setup global error flag
            $setup_error_flag = true;
            
            // Set the local error flag
            $error_flag = true;
            
            // Log message
            $record = _gettext("Error performing SQL query").' : '. $query_name['0'];
            
            // Output message via smarty
            $executed_sql_results .= '<div style="color: red">'.$record.'</div>';
            
            // Log message to setup log            
            write_record_to_setup_log('install', $record, $db->ErrorMsg(), $sql);
            
            
        } else {
            
            // Log message
            $record = _gettext("Performed SQL query successfully").' : '. $query_name['0'];
            
            // Output message via smarty
            $executed_sql_results .= '<div style="color: green">'.$record.'</div>';
            
            // Log message to setup log            
            write_record_to_setup_log('install', $record);

        }

    }

    // Closing result statement
    if($error_flag) {
        
        // Log message
        $record = _gettext("One or more SQL rule has failed. Check the logs.");
        
        // Output message via smarty
        $executed_sql_results .= '<div style="color: red;"><strong>'.$record.'</strong></div>';
        
        // Log message to setup log        
        write_record_to_setup_log('install', $record);
        
        return false;
        
    } else {
        
        // Log message
        $record = _gettext("All SQL rules have run successfully.");
        
        // Output message via smarty
        $executed_sql_results .= '<div style="color: green;"><strong>'.$record.'</strong></div>';
        
        // Log message to setup log        
        write_record_to_setup_log('install', $record);
        
        return true;
        
    }           
        
}

############################################
#   Execute SQL File (line by line)        #  //  file() loads line by line, good for large imports - not currently used
############################################

// https://stackoverflow.com/questions/19751354/how-to-import-sql-file-in-mysql-database-using-php

function execute_sql_file_lines($sql_file) {
    
    $db = QFactory::getDbo();
    global $executed_sql_results;
    global $setup_error_flag;    
    
    // Temporary variable, used to store current query
    $sql = '';
    
    // Read in entire file (will be line by because of below)
    $lines = file($sql_file);
    
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

                // Set the setup global error flag
                $setup_error_flag = true;
            
                // Set the local error flag
                $error_flag = true;

                // Log message
                $record = _gettext("Error performing SQL query").' : '. $query_name['0'];
                
                // Output message via smarty
                $executed_sql_results .= '<div style="color: red">'.$record.'</div>'; 

                // Log message to setup log                
                write_record_to_setup_log('upgrade', $record, $db->ErrorMsg(), $sql);                              

            } else {

                // Log message
                $record = _gettext("Performed SQL query successfully").' : '. $query_name['0'];

                // Output message via smarty
                $executed_sql_results .= '<div style="color: green">'.$record.'</div>';
                
                // Log message to setup log                
                write_record_to_setup_log('upgrade', $record);

            }            
                        
            // Reset templine variable to empty ready for the next line
            $sql = '';
            
        }        
        
    } 
    
    // Closing result statement
    if($error_flag) {

        // Log message
        $record = _gettext("One or more SQL rule has failed. Check the logs.");
                
        // Output message via smarty
        $executed_sql_results .= '<div style="color: red;">'.$record.'</div>';
        
        // Log message to setup log        
        write_record_to_setup_log('upgrade', $record);
        
        return false;

    } else {

        // Log message
        $record = _gettext("All SQL rules have run successfully.");
                
        // Output message via smarty
        $executed_sql_results .= '<div style="color: green;">'.$record.'</div>';
        
        // Log message to setup log        
        write_record_to_setup_log('upgrade', $record);
        
        return true;

    }
        
}

############################################
#  Write a record to the Setup Log         #    // Cannot be turned off - install/migrate/upgrade
############################################

function write_record_to_setup_log($setup_type, $record, $database_error = null, $sql_query = null) {
    
    // Install and migrate does not have username or login_user_id available
    if(defined('QWCRM_SETUP') && QWCRM_SETUP == 'install') {
        $username = '-';
        $login_user_id = '-';
    } else {
        $username = QFactory::getUser()->login_username;
        $login_user_id = QFactory::getUser()->login_user_id;
    }
    
    // prepare database error for the log
    $database_error = prepare_error_data('database_error', $database_error);   
    
    // prepare SQL statement for the log
    $sql_query = prepare_error_data('sql_query_for_log', $sql_query);    
    
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

############################################
#  Check the database connection works     #
############################################

function check_database_connection_details($db_host, $db_user, $db_pass, $db_name) {
    
    $db = QFactory::getDbo();
    $smarty = QFactory::getSmarty();
    
    // Get current PHP error reporting level
    $reporting_level = error_reporting();
    
    // Disable PHP error reporting (works globally)
    error_reporting(0);
    
    // Create ADOdb database connection - and collect exception if it occurs
    try
    {        
        $db->Connect($db_host, $db_user, $db_pass, $db_name);
    }    
    
    catch (Exception $e)
    {
        
        // Re-Enable PHP error reporting
        error_reporting($reporting_level);
        
        //echo $e->msg;
        //var_dump($e);
        //adodb_backtrace($e->gettrace());
        $smarty->assign('warning_msg', $e->msg);
        
        return false;
              
    }
    
    // Re-Enable PHP error reporting
    error_reporting($reporting_level);
    
    // Return the connection status
    if(!$db->isConnected()) {           
        
        $smarty->assign('warning_msg', prepare_error_data('database_connection_error', $db->ErrorMsg()));
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
#   Set workorder start number             #
############################################

function set_workorder_start_number($start_number) {
    
    $db = QFactory::getDbo();
    
    $sql = "ALTER TABLE ".PRFX."workorder_records auto_increment =".$db->qstr($start_number);

    $db->execute($sql);    
    
    return;
    
}

############################################
#   Set invoice start number               #
############################################

function set_invoice_start_number($start_number) {
    
    $db = QFactory::getDbo();
    
    $sql = "ALTER TABLE ".PRFX."invoice_records auto_increment =".$db->qstr($start_number);

    $db->execute($sql);   
    
    return;
    
}

############################################
#   Install database                       # // this imports a phpMyAdmin .sql exported file (preg_match method)
############################################

function install_database() {
    
    $smarty = QFactory::getSmarty();
    global $executed_sql_results;
    global $setup_error_flag;  
    
    // Run the install.sql
    execute_sql_file(SETUP_DIR.'install/install.sql');
    
    /* Final stuff */
    
    // Final statement
    if($setup_error_flag) {
        
        // Log message
        $record = _gettext("The database installation process failed, check the logs.");
        
        // Output message via smarty
        $executed_sql_results .= '<div>&nbsp;</div>';
        $executed_sql_results .= '<div style="color: red;"><strong>'.$record.'</strong></div>';
        
        // Log message to setup log        
        write_record_to_setup_log('install', $record);
        
    } else {
        
        // Log message
        $record = _gettext("The database installation process was successful.");
        
        // Output message via smarty
        $executed_sql_results .= '<div>&nbsp;</div>';
        $executed_sql_results .= '<div style="color: green;"><strong>'.$record.'</strong></div>';
        
        // Log message to setup log        
        write_record_to_setup_log('install', $record);
        
    }    

    // Return reflecting the installation status
    if($setup_error_flag) {
        
        /* installation failed */
        
        // Set setup_error_flag used in smarty templates
        $smarty->assign('setup_error_flag', true);
        
        
        return false;
        
    } else {
        
        /* installation successful */
        
        return true;
        
    }
   
}

/** Migrate **/

############################################
#   Migrate myitcrm database               #
############################################

function migrate_database($qwcrm_prefix, $myitcrm_prefix) {
    
    $smarty = QFactory::getSmarty();
    global $executed_sql_results;
    global $setup_error_flag;    
    
    /* Customer */
    
    // customer
    $column_mappings = array(
        'customer_id'       => 'CUSTOMER_ID',
        'display_name'      => 'CUSTOMER_DISPLAY_NAME',
        'first_name'        => 'CUSTOMER_FIRST_NAME',
        'last_name'         => 'CUSTOMER_LAST_NAME',
        'website'           => 'CUSTOMER_WWW',
        'email'             => 'CUSTOMER_EMAIL',
        'credit_terms'      => 'CREDIT_TERMS',
        'discount_rate'     => 'DISCOUNT',
        'type'              => 'CUSTOMER_TYPE',
        'active'            => '',
        'primary_phone'     => 'CUSTOMER_PHONE',
        'mobile_phone'      => 'CUSTOMER_MOBILE_PHONE',
        'fax'               => 'CUSTOMER_WORK_PHONE',
        'address'           => 'CUSTOMER_ADDRESS',
        'city'              => 'CUSTOMER_CITY',
        'state'             => 'CUSTOMER_STATE',
        'zip'               => 'CUSTOMER_ZIP',
        'country'           => '',
        'notes'             => 'CUSTOMER_NOTES',
        'create_date'       => 'CREATE_DATE',
        'last_active'       => 'LAST_ACTIVE'
        );
    migrate_table($qwcrm_prefix.'customer', $myitcrm_prefix.'TABLE_CUSTOMER', $column_mappings);
    
    // update customer types
    update_column_values($qwcrm_prefix.'customer', 'type', '1', 'residential');
    update_column_values($qwcrm_prefix.'customer', 'type', '2', 'commercial');
    update_column_values($qwcrm_prefix.'customer', 'type', '3', 'charity');
    update_column_values($qwcrm_prefix.'customer', 'type', '4', 'educational');
    update_column_values($qwcrm_prefix.'customer', 'type', '5', 'goverment');
    
    // update active status (all enabled)
    update_column_values($qwcrm_prefix.'customer', 'active', '*', '1');
    
    // customer_notes
    $column_mappings = array(
        'customer_note_id'  => 'ID',
        'employee_id'       => '',
        'customer_id'       => 'CUSTOMER_ID',
        'date'              => 'DATE',
        'note'              => 'NOTE'
        );    
    migrate_table($qwcrm_prefix.'customer_notes', $myitcrm_prefix.'CUSTOMER_NOTES', $column_mappings);    
            
    /* Expense */
    
    // expense
    $column_mappings = array(
        'expense_id'        => 'EXPENSE_ID',
        'invoice_id'        => '',
        'payee'             => 'EXPENSE_PAYEE',
        'date'              => 'EXPENSE_DATE',
        'type'              => 'EXPENSE_TYPE',
        'payment_method'    => 'EXPENSE_PAYMENT_METHOD',
        'net_amount'        => 'EXPENSE_NET_AMOUNT',
        'vat_rate'          => 'EXPENSE_TAX_RATE',
        'vat_amount'        => 'EXPENSE_TAX_AMOUNT',
        'gross_amount'      => 'EXPENSE_GROSS_AMOUNT',
        'items'             => 'EXPENSE_ITEMS',
        'notes'             => 'EXPENSE_NOTES'        
        );
    migrate_table($qwcrm_prefix.'expense', $myitcrm_prefix.'TABLE_EXPENSE', $column_mappings);
    
    // update expense types
    update_column_values($qwcrm_prefix.'expense', 'type', '1', 'advertising');
    update_column_values($qwcrm_prefix.'expense', 'type', '2', 'bank_charges');
    update_column_values($qwcrm_prefix.'expense', 'type', '3', 'broadband');
    update_column_values($qwcrm_prefix.'expense', 'type', '4', 'credit');
    update_column_values($qwcrm_prefix.'expense', 'type', '5', 'customer_refund');
    update_column_values($qwcrm_prefix.'expense', 'type', '6', 'customer_refund');
    update_column_values($qwcrm_prefix.'expense', 'type', '7', 'equipment');
    update_column_values($qwcrm_prefix.'expense', 'type', '8', 'gift_certificate');
    update_column_values($qwcrm_prefix.'expense', 'type', '9', 'landline');
    update_column_values($qwcrm_prefix.'expense', 'type', '10', 'mobile_phone');
    update_column_values($qwcrm_prefix.'expense', 'type', '11', 'office_supplies');
    update_column_values($qwcrm_prefix.'expense', 'type', '12', 'parts');
    update_column_values($qwcrm_prefix.'expense', 'type', '13', 'fuel');
    update_column_values($qwcrm_prefix.'expense', 'type', '14', 'postage');
    update_column_values($qwcrm_prefix.'expense', 'type', '15', 'tax');
    update_column_values($qwcrm_prefix.'expense', 'type', '16', 'rent');
    update_column_values($qwcrm_prefix.'expense', 'type', '17', 'transport');
    update_column_values($qwcrm_prefix.'expense', 'type', '18', 'utilities');
    update_column_values($qwcrm_prefix.'expense', 'type', '19', 'voucher');
    update_column_values($qwcrm_prefix.'expense', 'type', '20', 'wages');
    update_column_values($qwcrm_prefix.'expense', 'type', '21', 'other');
    
    // update expense payment method
    update_column_values($qwcrm_prefix.'expense', 'payment_method', '1', 'bank_transfer');
    update_column_values($qwcrm_prefix.'expense', 'payment_method', '2', 'card');
    update_column_values($qwcrm_prefix.'expense', 'payment_method', '3', 'cash');
    update_column_values($qwcrm_prefix.'expense', 'payment_method', '4', 'cheque');
    update_column_values($qwcrm_prefix.'expense', 'payment_method', '5', 'credit');
    update_column_values($qwcrm_prefix.'expense', 'payment_method', '6', 'direct_debit');
    update_column_values($qwcrm_prefix.'expense', 'payment_method', '7', 'gift_certificate');
    update_column_values($qwcrm_prefix.'expense', 'payment_method', '8', 'google_checkout');
    update_column_values($qwcrm_prefix.'expense', 'payment_method', '9', 'paypal');
    update_column_values($qwcrm_prefix.'expense', 'payment_method', '10', 'voucher');
    update_column_values($qwcrm_prefix.'expense', 'payment_method', '11', 'other');    
    
    /* Gifcert */
    
    // giftcert
    $column_mappings = array(
        'giftcert_id'       => 'GIFT_ID',
        'giftcert_code'     => 'GIFT_CODE',
        'employee_id'       => '',
        'customer_id'       => 'CUSTOMER_ID',
        'invoice_id'        => 'INVOICE_ID',
        'date_created'      => 'DATE_CREATE',
        'date_expires'      => 'EXPIRE',
        'date_redeemed'     => 'DATE_REDEMED',
        'is_redeemed'       => '',
        'amount'            => 'AMOUNT',
        'active'            => 'ACTIVE',
        'notes'             => 'MEMO'        
        );
    migrate_table($qwcrm_prefix.'giftcert', $myitcrm_prefix.'GIFT_CERT', $column_mappings);
    
    // update date_redeemed to remove incoreect zero dates
    update_column_values($qwcrm_prefix.'giftcert', 'date_redeemed', '0', '');
    
    /* Invoice */
    
    // invoice
    $column_mappings = array(
        'invoice_id'        => 'INVOICE_ID',
        'employee_id'       => 'EMPLOYEE_ID',
        'customer_id'       => 'CUSTOMER_ID',
        'workorder_id'      => 'WORKORDER_ID',
        'date'              => 'INVOICE_DATE',
        'due_date'          => 'INVOICE_DUE',
        'discount_rate'     => 'DISCOUNT_APPLIED',
        'tax_type'          => '',
        'tax_rate'          => 'TAX_RATE',
        'sub_total'         => 'SUB_TOTAL',
        'discount_amount'   => 'DISCOUNT',
        'net_amount'        => '',
        'tax_amount'        => 'TAX',
        'gross_amount'      => 'INVOICE_AMOUNT',
        'paid_amount'       => 'PAID_AMOUNT',
        'balance'           => 'BALANCE',
        'open_date'         => 'INVOICE_DATE',
        'close_date'        => 'PAID_DATE',
        'last_active'       => 'PAID_DATE',
        'status'            => '',
        'is_closed'         => 'INVOICE_PAID',
        'paid_date'         => 'PAID_DATE'     
        );
    migrate_table($qwcrm_prefix.'invoice', $myitcrm_prefix.'TABLE_INVOICE', $column_mappings);
    
    // Change tax_type to selected Company Tax Type for all migrated invoices - This is an assumption
    update_column_values($qwcrm_prefix.'invoice', 'tax_type', '', get_company_details('tax_type'));
    
    // change close dates from zero to ''
    update_column_values($qwcrm_prefix.'invoice', 'close_date', '0', '');
    update_column_values($qwcrm_prefix.'invoice', 'paid_date', '0', '');
    update_column_values($qwcrm_prefix.'invoice', 'last_active', '0', '');
    
    // correct null workorders
    update_column_values($qwcrm_prefix.'invoice', 'workorder_id', '0', '');
    
    // invoice_labour
    $column_mappings = array(
        'invoice_labour_id' => 'INVOICE_LABOR_ID',
        'invoice_id'        => 'INVOICE_ID',
        'description'       => 'INVOICE_LABOR_DESCRIPTION',
        'amount'            => 'INVOICE_LABOR_RATE',
        'qty'               => 'INVOICE_LABOR_UNIT',
        'sub_total'         => 'INVOICE_LABOR_SUBTOTAL'    
        );
    migrate_table($qwcrm_prefix.'invoice_labour', $myitcrm_prefix.'TABLE_INVOICE_LABOR', $column_mappings);
    
    // invoice_parts
    $column_mappings = array(
        'invoice_parts_id'  => 'INVOICE_PARTS_ID',
        'invoice_id'        => 'INVOICE_ID',
        'description'       => 'INVOICE_PARTS_DESCRIPTION',
        'amount'            => 'INVOICE_PARTS_AMOUNT',
        'qty'               => 'INVOICE_PARTS_COUNT',
        'sub_total'         => 'INVOICE_PARTS_SUBTOTAL'    
        );
    migrate_table($qwcrm_prefix.'invoice_parts', $myitcrm_prefix.'TABLE_INVOICE_PARTS', $column_mappings);        
    
    /* Payment / transactions */
    
    // payment_transactions
    $column_mappings = array(
        'transaction_id'    => 'TRANSACTION_ID',
        'employee_id'       => '',
        'customer_id'       => 'CUSTOMER_ID',
        'workorder_id'      => 'WORKORDER_ID',
        'invoice_id'        => 'INVOICE_ID',
        'date'              => 'DATE',
        'method'            => 'TYPE',
        'amount'            => 'AMOUNT',
        'note'              => 'MEMO'  
        );
    migrate_table($qwcrm_prefix.'payment_transactions', $myitcrm_prefix.'TABLE_TRANSACTION', $column_mappings);
    
    // update payment types
    update_column_values($qwcrm_prefix.'payment_transactions', 'method', '1', 'credit_card');
    update_column_values($qwcrm_prefix.'payment_transactions', 'method', '2', 'cheque');
    update_column_values($qwcrm_prefix.'payment_transactions', 'method', '3', 'cash');
    update_column_values($qwcrm_prefix.'payment_transactions', 'method', '4', 'gift_certificate');
    update_column_values($qwcrm_prefix.'payment_transactions', 'method', '5', 'paypal');    
    
    /* Refund */
    
    // refund
    $column_mappings = array(
        'refund_id'         => 'REFUND_ID',
        'payee'             => 'REFUND_PAYEE',
        'date'              => 'REFUND_DATE',
        'type'              => 'REFUND_TYPE',
        'payment_method'    => 'REFUND_PAYMENT_METHOD',
        'net_amount'        => 'REFUND_NET_AMOUNT',
        'vat_rate'          => 'REFUND_TAX_RATE',
        'vat_amount'        => 'REFUND_TAX_AMOUNT',
        'gross_amount'      => 'REFUND_GROSS_AMOUNT',
        'items'             => 'REFUND_ITEMS',
        'notes'             => 'REFUND_NOTES'        
        );
    migrate_table($qwcrm_prefix.'refund', $myitcrm_prefix.'TABLE_REFUND', $column_mappings);
    
    // update refund types
    update_column_values($qwcrm_prefix.'refund', 'type', '1', 'credit_note');
    update_column_values($qwcrm_prefix.'refund', 'type', '2', 'proxy_invoice');
    update_column_values($qwcrm_prefix.'refund', 'type', '3', 'returned_goods');
    update_column_values($qwcrm_prefix.'refund', 'type', '4', 'returned_services');
    update_column_values($qwcrm_prefix.'refund', 'type', '5', 'other');
    
    // update refund payment methods
    update_column_values($qwcrm_prefix.'refund', 'payment_method', '1', 'bank_transfer');
    update_column_values($qwcrm_prefix.'refund', 'payment_method', '2', 'card');
    update_column_values($qwcrm_prefix.'refund', 'payment_method', '3', 'cash');
    update_column_values($qwcrm_prefix.'refund', 'payment_method', '4', 'cheque');
    update_column_values($qwcrm_prefix.'refund', 'payment_method', '5', 'credit');
    update_column_values($qwcrm_prefix.'refund', 'payment_method', '6', 'direct_debit');
    update_column_values($qwcrm_prefix.'refund', 'payment_method', '7', 'gift_certificate');
    update_column_values($qwcrm_prefix.'refund', 'payment_method', '8', 'google_checkout');
    update_column_values($qwcrm_prefix.'refund', 'payment_method', '9', 'paypal');
    update_column_values($qwcrm_prefix.'refund', 'payment_method', '10', 'voucher');
    update_column_values($qwcrm_prefix.'refund', 'payment_method', '11', 'other');    
    
    /* Schedule */
    
    // schedule
    $column_mappings = array(
        'schedule_id'       => 'SCHEDULE_ID',
        'employee_id'       => 'EMPLOYEE_ID',
        'customer_id'       => '',
        'workorder_id'      => 'WORK_ORDER_ID',
        'start_time'        => 'SCHEDULE_START',
        'end_time'          => 'SCHEDULE_END',
        'notes'             => 'SCHEDULE_NOTES'    
        );
    migrate_table($qwcrm_prefix.'schedule', $myitcrm_prefix.'TABLE_SCHEDULE', $column_mappings);
    
    /* Supplier */
    
    // supplier
    $column_mappings = array(
        'supplier_id'       => 'SUPPLIER_ID',
        'display_name'      => 'SUPPLIER_NAME',
        'first_name'        => '',
        'last_name'         => 'SUPPLIER_CONTACT',
        'website'           => 'SUPPLIER_WWW',
        'email'             => 'SUPPLIER_EMAIL',
        'type'              => 'SUPPLIER_TYPE',
        'primary_phone'     => 'SUPPLIER_PHONE',
        'mobile_phone'      => 'SUPPLIER_MOBILE',
        'fax'               => 'SUPPLIER_FAX',
        'address'           => 'SUPPLIER_ADDRESS',
        'city'              => 'SUPPLIER_CITY',
        'state'             => 'SUPPLIER_STATE',
        'zip'               => 'SUPPLIER_ZIP',
        'country'           => '',
        'description'       => 'SUPPLIER_DESCRIPTION',
        'notes'             => 'SUPPLIER_NOTES'           
        );
    migrate_table($qwcrm_prefix.'supplier', $myitcrm_prefix.'TABLE_SUPPLIER', $column_mappings);
    
    // update supplier types
    update_column_values($qwcrm_prefix.'supplier', 'type', '1', 'affiliate_marketing');
    update_column_values($qwcrm_prefix.'supplier', 'type', '2', 'advertising');
    update_column_values($qwcrm_prefix.'supplier', 'type', '3', 'drop_shipping');
    update_column_values($qwcrm_prefix.'supplier', 'type', '4', 'courier');
    update_column_values($qwcrm_prefix.'supplier', 'type', '5', 'general');
    update_column_values($qwcrm_prefix.'supplier', 'type', '6', 'parts');
    update_column_values($qwcrm_prefix.'supplier', 'type', '7', 'services');
    update_column_values($qwcrm_prefix.'supplier', 'type', '8', 'software');
    update_column_values($qwcrm_prefix.'supplier', 'type', '9', 'wholesale');
    update_column_values($qwcrm_prefix.'supplier', 'type', '10', 'online');
    update_column_values($qwcrm_prefix.'supplier', 'type', '11', 'other');
    
    /* user / Employee */
    
    // user
    $column_mappings = array(
        'user_id'           => 'EMPLOYEE_ID',
        'customer_id'       => '',
        'username'          => 'EMPLOYEE_LOGIN',
        'password'          => 'EMPLOYEE_PASSWD',
        'email'             => 'EMPLOYEE_EMAIL',
        'usergroup'         => 'EMPLOYEE_TYPE',
        'active'            => 'EMPLOYEE_STATUS',
        'last_active'       => '',
        'register_date'     => '',
        'require_reset'     => '',
        'last_reset_time'   => '',
        'reset_count'       => '',
        'is_employee'       => '',
        'display_name'      => 'EMPLOYEE_DISPLAY_NAME',
        'first_name'        => 'EMPLOYEE_FIRST_NAME',
        'last_name'         => 'EMPLOYEE_LAST_NAME',
        'work_primary_phone'=> 'EMPLOYEE_WORK_PHONE',
        'work_mobile_phone' => 'EMPLOYEE_MOBILE_PHONE',
        'work_fax'          => '',
        'home_primary_phone'=> 'EMPLOYEE_HOME_PHONE',
        'home_mobile_phone' => '',
        'home_email'        => '',
        'home_address'      => 'EMPLOYEE_ADDRESS',
        'home_city'         => 'EMPLOYEE_CITY',
        'home_state'        => 'EMPLOYEE_STATE',
        'home_zip'          => 'EMPLOYEE_ZIP',
        'home_country'      => '',
        'based'             => 'EMPLOYEE_BASED',
        'notes'             => ''
        );
    migrate_table($qwcrm_prefix.'user', $myitcrm_prefix.'TABLE_EMPLOYEE', $column_mappings);
    
    // Set all users to have create date of now 
    update_column_values($qwcrm_prefix.'user', 'register_date', '*', time());
    
    // Set all users to employees
    update_column_values($qwcrm_prefix.'user', 'is_employee', '*', '1');
    
    // Set all users to technicians
    update_column_values($qwcrm_prefix.'user', 'usergroup', '*', '4');
    
    // Set password reset required for all users
    update_column_values($qwcrm_prefix.'user', 'require_reset', '*', '1');
    
    // Reset all user passwords (passwords will all be random and unknown)
    reset_all_user_passwords();
    
    /* Workorder */
    
    // workorder
    $column_mappings = array(
        'workorder_id'      => 'WORK_ORDER_ID',
        'employee_id'       => 'WORK_ORDER_ASSIGN_TO',
        'customer_id'       => 'CUSTOMER_ID',
        'invoice_id'        => '',
        'created_by'        => 'WORK_ORDER_CREATE_BY',
        'closed_by'         => 'WORK_ORDER_CLOSE_BY',
        'open_date'         => 'WORK_ORDER_OPEN_DATE',
        'close_date'        => 'WORK_ORDER_CLOSE_DATE',
        'last_active'       => 'LAST_ACTIVE',
        'status'            => '',
        'is_closed'         => '',
        'scope'             => 'WORK_ORDER_SCOPE',
        'description'       => 'WORK_ORDER_DESCRIPTION',
        'comments'          => 'WORK_ORDER_COMMENT',
        'resolution'        => 'WORK_ORDER_RESOLUTION'           
        );   // WORK_ORDER_CURRENT_STATUS - WORK_ORDER_STATUS    
    migrate_table($qwcrm_prefix.'workorder', $myitcrm_prefix.'TABLE_WORK_ORDER', $column_mappings);
    
    // workorder_history
    $column_mappings = array(
        'history_id'        => 'WORK_ORDER_STATUS_ID',
        'employee_id'       => 'WORK_ORDER_STATUS_ENTER_BY',
        'workorder_id'      => 'WORK_ORDER_ID',
        'date'              => 'WORK_ORDER_STATUS_DATE',
        'note'              => 'WORK_ORDER_STATUS_NOTES'         
        ); 
    migrate_table($qwcrm_prefix.'workorder_history', $myitcrm_prefix.'TABLE_WORK_ORDER_STATUS', $column_mappings);    
    
    // workorder_notes
    $column_mappings = array(
        'workorder_note_id' => 'WORK_ORDER_NOTES_ID',
        'employee_id'       => 'WORK_ORDER_NOTES_ENTER_BY',
        'workorder_id'      => 'WORK_ORDER_ID',
        'date'              => 'WORK_ORDER_NOTES_DATE',
        'description'       => 'WORK_ORDER_NOTES_DESCRIPTION'         
        ); 
    migrate_table($qwcrm_prefix.'workorder_notes', $myitcrm_prefix.'TABLE_WORK_ORDER_NOTES', $column_mappings);
    
    /* Corrections */
    
    // Workorder
    migrate_database_correction_workorder($qwcrm_prefix, $myitcrm_prefix);
    
    // Invoice
    migrate_database_correction_invoice($qwcrm_prefix);
    
    // Giftcert
    migrate_database_correction_giftcert($qwcrm_prefix);
    
    // Schedule
    migrate_database_correction_schedule($qwcrm_prefix, $myitcrm_prefix);
    
    // User
    migrate_database_correction_user($qwcrm_prefix);
    
    /* Final stuff */

    // Final statement
    if($setup_error_flag) {
        
        // Setup error flag uses in smarty templates
        $smarty->assign('setup_error_flag', true);
        
        // Log message
        $record = _gettext("The database migration process failed, check the logs.");
        
        // Output message via smarty
        $executed_sql_results .= '<div>&nbsp;</div>';
        $executed_sql_results .= '<div style="color: red;"><strong>'.$record.'</strong></div>';
        
        // Log message to setup log        
        write_record_to_setup_log('migrate', $record);
        
    } else {
        
        // Log message
        $record = _gettext("The database migration process was successful.");
        
        // Output message via smarty
        $executed_sql_results .= '<div>&nbsp;</div>';
        $executed_sql_results .= '<div style="color: green;"><strong>'.$record.'</strong></div>';
        
        // Log message to setup log        
        write_record_to_setup_log('migrate', $record);
        
    } 
    
    // return reflecting the installation status
    if($setup_error_flag) {
        
        /* Migration Failed */
        
        // Set setup_error_flag used in smarty templates
        $smarty->assign('setup_error_flag', true);        
        
        return false;
        
    } else {
        
        /* migration Successful */
        
        return true;
        
    }
    
}

/* Corrections */

############################################
#   Correct migrated workorder data        #
############################################

function migrate_database_correction_workorder($qwcrm_prefix, $myitcrm_prefix) {
    
    $db = QFactory::getDbo();
    global $executed_sql_results;
    
    // Add division to seperate table migration function results
    $executed_sql_results .= '<div>&nbsp;</div>';
    
    // Log message
    $record = _gettext("Starting the correction of the migrated `workorder` data in QWcrm.");       
                
    // Result message
    $executed_sql_results .= '<div><strong><span style="color: green">'.$record.'</span></strong></div>';
    
    // Log message to setup log                
    write_record_to_setup_log('migrate', $record);
    
    // old MyITCRM workorder status
    // 1 - created
    // 2 - assigned
    // 3 - waiting for parts
    // n/a
    // n/a
    // 6 - closed
    // 7 - awaiting payment
    // 8 - payment made
    // 9 - pending
    // 10 - open
   
    $sql =  "SELECT            
            ".$qwcrm_prefix."workorder.workorder_id AS qw_workorder_id,

            ".$myitcrm_prefix."TABLE_WORK_ORDER.WORK_ORDER_ID AS my_work_order_id,
            ".$myitcrm_prefix."TABLE_WORK_ORDER.WORK_ORDER_STATUS AS my_work_order_status,
            ".$myitcrm_prefix."TABLE_WORK_ORDER.WORK_ORDER_CURRENT_STATUS AS my_work_order_current_status,

            ".$myitcrm_prefix."TABLE_INVOICE.INVOICE_ID AS my_invoice_id            

            FROM ".$qwcrm_prefix."workorder
            LEFT JOIN ".$myitcrm_prefix."TABLE_WORK_ORDER ON ".$qwcrm_prefix."workorder.workorder_id = ".$myitcrm_prefix."TABLE_WORK_ORDER.WORK_ORDER_ID
            LEFT JOIN ".$myitcrm_prefix."TABLE_INVOICE ON ".$myitcrm_prefix."TABLE_WORK_ORDER.WORK_ORDER_ID = ".$myitcrm_prefix."TABLE_INVOICE.WORKORDER_ID";

    /* Processs the records */

    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to return the matching Work Orders."));

    } else {

        while(!$rs->EOF) {            

            $myitcrm_record = $rs->GetRowAssoc(); 

            /* status and is_closed */

            // WORK_ORDER_STATUS = 6 (closed), WORK_ORDER_CURRENT_STATUS = 6 (closed)
            if($myitcrm_record['my_work_order_status'] == '6' && $myitcrm_record['my_work_order_current_status'] == '6') {                    
                update_record_value($qwcrm_prefix.'workorder', 'workorder_id', $myitcrm_record['qw_workorder_id'], 'status', 'closed_without_invoice');
                update_record_value($qwcrm_prefix.'workorder', 'workorder_id', $myitcrm_record['qw_workorder_id'], 'is_closed', '1');
            }

            // WORK_ORDER_STATUS = 6 (closed), WORK_ORDER_CURRENT_STATUS = 8 (payment made)
            elseif($myitcrm_record['my_work_order_status'] == '6' && $myitcrm_record['my_work_order_current_status'] == '8') {                    
                update_record_value($qwcrm_prefix.'workorder', 'workorder_id', $myitcrm_record['qw_workorder_id'], 'status', 'closed_with_invoice');
                update_record_value($qwcrm_prefix.'workorder', 'workorder_id', $myitcrm_record['qw_workorder_id'], 'is_closed', '1');
            }

            // WORK_ORDER_STATUS = 9 (pending), WORK_ORDER_CURRENT_STATUS = 7 (awaiting payment)
            elseif($myitcrm_record['my_work_order_status'] == '9' && $myitcrm_record['my_work_order_current_status'] == '7') {                    
                update_record_value($qwcrm_prefix.'workorder', 'workorder_id', $myitcrm_record['qw_workorder_id'], 'status', 'closed_with_invoice');
                update_record_value($qwcrm_prefix.'workorder', 'workorder_id', $myitcrm_record['qw_workorder_id'], 'is_closed', '1');
            }

            // WORK_ORDER_STATUS = 10 (open), WORK_ORDER_CURRENT_STATUS = 1 (created)
            elseif($myitcrm_record['my_work_order_status'] == '10' && $myitcrm_record['my_work_order_current_status'] == '1') {                    
                update_record_value($qwcrm_prefix.'workorder', 'workorder_id', $myitcrm_record['qw_workorder_id'], 'status', 'unassigned');
                update_record_value($qwcrm_prefix.'workorder', 'workorder_id', $myitcrm_record['qw_workorder_id'], 'is_closed', '0');
            }

            // WORK_ORDER_STATUS = 10 (open), WORK_ORDER_CURRENT_STATUS = 2 (assigned)
            elseif($myitcrm_record['my_work_order_status'] == '10' && $myitcrm_record['my_work_order_current_status'] == '2') {                    
                update_record_value($qwcrm_prefix.'workorder', 'workorder_id', $myitcrm_record['qw_workorder_id'], 'status', 'assigned');
                update_record_value($qwcrm_prefix.'workorder', 'workorder_id', $myitcrm_record['qw_workorder_id'], 'is_closed', '0');
            }

            // Uncaught records / default
            else {                    
                update_record_value($qwcrm_prefix.'workorder', 'workorder_id', $myitcrm_record['qw_workorder_id'], 'status', 'failed_to_migrate');
                update_record_value($qwcrm_prefix.'workorder', 'workorder_id', $myitcrm_record['qw_workorder_id'], 'is_closed', '0');
            }

            /* invoice_id */

            if($myitcrm_record['my_invoice_id'] != '') {
                update_record_value($qwcrm_prefix.'workorder', 'workorder_id', $myitcrm_record['qw_workorder_id'], 'invoice_id', $myitcrm_record['my_invoice_id']);                
            }

            // Advance the INSERT loop to the next record
            $rs->MoveNext();           

        }//EOF While loop

    }
    
    /* Final Stuff */

    // Log message
    $record = _gettext("Finished the correction of the migrated `workorder` data in QWcrm."); 
     
    // Result message
    $executed_sql_results .= '<div><strong><span style="color: green">'.$record.'</span></strong></div>';

    // Add division to seperate table migration function results
    $executed_sql_results .= '<div>&nbsp;</div>';

    // Log message to setup log                
    write_record_to_setup_log('migrate', $record);

    return;

}

############################################
#   Correct migrated invoice data          #
############################################

function migrate_database_correction_invoice($qwcrm_prefix) {
    
    $db = QFactory::getDbo();
    global $executed_sql_results;
    
    // Add division to seperate table migration function results
    $executed_sql_results .= '<div>&nbsp;</div>';
    
    // Log message
    $record = _gettext("Starting the correction of the migrated `invoice` data in QWcrm.");       
                
    // Result message
    $executed_sql_results .= '<div><strong><span style="color: green">'.$record.'</span></strong></div>';
    
    // Log message to setup log                
    write_record_to_setup_log('migrate', $record);
    
    $sql =  "SELECT * FROM ".$qwcrm_prefix."invoice";                       

    /* Processs the records */

    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to return the matching Invoices."));

    } else {

        while(!$rs->EOF) {            

            $qwcrm_record = $rs->GetRowAssoc();
            
            /* net_amount */
            $net_amount = $qwcrm_record['sub_total'] - $qwcrm_record['discount'];
            update_record_value($qwcrm_prefix.'invoice', 'invoice_id', $qwcrm_record['invoice_id'], 'net_amount', $net_amount);            

            /* status and is_closed*/
            
            // no amount on invoice
            if($qwcrm_record['gross_amount'] == '0') {                    
                update_record_value($qwcrm_prefix.'invoice', 'invoice_id', $qwcrm_record['invoice_id'], 'status', 'pending');
                update_record_value($qwcrm_prefix.'invoice', 'invoice_id', $qwcrm_record['invoice_id'], 'is_closed', '0'); 
            }
            
            // if unpaid
            elseif($qwcrm_record['paid_amount'] == '0') {                    
                update_record_value($qwcrm_prefix.'invoice', 'invoice_id', $qwcrm_record['invoice_id'], 'status', 'unpaid');
                update_record_value($qwcrm_prefix.'invoice', 'invoice_id', $qwcrm_record['invoice_id'], 'is_closed', '0');
            }
            
            // if there are partial payments
            elseif($qwcrm_record['paid_amount'] < $qwcrm_record['gross_amount'] && $qwcrm_record['paid_amount'] != '0') {                    
                update_record_value($qwcrm_prefix.'invoice', 'invoice_id', $qwcrm_record['invoice_id'], 'status', 'partially_paid');
                update_record_value($qwcrm_prefix.'invoice', 'invoice_id', $qwcrm_record['invoice_id'], 'is_closed', '0');
            }
            
            // if fully paid
            elseif($qwcrm_record['paid_amount'] == $qwcrm_record['gross_amount']) {                    
                update_record_value($qwcrm_prefix.'invoice', 'invoice_id', $qwcrm_record['invoice_id'], 'status', 'paid');
                update_record_value($qwcrm_prefix.'invoice', 'invoice_id', $qwcrm_record['invoice_id'], 'is_closed', '1');
            }            

            // Uncaught records / default
            else {                    
                update_record_value($qwcrm_prefix.'invoice', 'invoice_id', $qwcrm_record['invoice_id'], 'status', 'failed_to_migrate');
                update_record_value($qwcrm_prefix.'invoice', 'invoice_id', $qwcrm_record['invoice_id'], 'is_closed', '0');
            }

            // Advance the INSERT loop to the next record
            $rs->MoveNext();           

        }//EOF While loop

    }
    
    /* Final Stuff */

    // Log message
    $record = _gettext("Finished the correction of the migrated `invoice` data in QWcrm."); 
     
    // Result message
    $executed_sql_results .= '<div><strong><span style="color: green">'.$record.'</span></strong></div>';

    // Add division to seperate table migration function results
    $executed_sql_results .= '<div>&nbsp;</div>';

    // Log message to setup log                
    write_record_to_setup_log('migrate', $record);

    return;

}

############################################
#   Correct migrated giftcert data         #
############################################

function migrate_database_correction_giftcert($qwcrm_prefix) {
    
    $db = QFactory::getDbo();
    global $executed_sql_results;
    
    // Add division to seperate table migration function results
    $executed_sql_results .= '<div>&nbsp;</div>';
    
    // Log message
    $record = _gettext("Starting the correction of the migrated `giftcert` data in QWcrm.");       
                
    // Result message
    $executed_sql_results .= '<div><strong><span style="color: green">'.$record.'</span></strong></div>';
    
    // Log message to setup log                
    write_record_to_setup_log('migrate', $record);
    
    $sql =  "SELECT * FROM ".$qwcrm_prefix."giftcert";                       

    /* Processs the records */

    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to return the matching Gift Certificates."));

    } else {

        while(!$rs->EOF) {            

            $qwcrm_record = $rs->GetRowAssoc();
            
            /* is_redeemed */
            
            // no amount on invoice
            if($qwcrm_record['date_redeemed'] == '') {                    
                update_record_value($qwcrm_prefix.'giftcert', 'giftcert_id', $qwcrm_record['giftcert_id'], 'is_redeemed', '0');                               
            } else {
                update_record_value($qwcrm_prefix.'giftcert', 'giftcert_id', $qwcrm_record['giftcert_id'], 'is_redeemed', '1');
            }
            
            // Advance the INSERT loop to the next record
            $rs->MoveNext();           

        }//EOF While loop

    }
    
    /* Final Stuff */

    // Log message
    $record = _gettext("Finished the correction of the migrated `giftcert` data in QWcrm."); 
     
    // Result message
    $executed_sql_results .= '<div><strong><span style="color: green">'.$record.'</span></strong></div>';

    // Add division to seperate table migration function results
    $executed_sql_results .= '<div>&nbsp;</div>';

    // Log message to setup log                
    write_record_to_setup_log('migrate', $record);

    return;

}

############################################
#   Correct migrated schedule data         #
############################################

function migrate_database_correction_schedule($qwcrm_prefix, $myitcrm_prefix) {
    
    $db = QFactory::getDbo();
    global $executed_sql_results;
    
    // Add division to seperate table migration function results
    $executed_sql_results .= '<div>&nbsp;</div>';
    
    // Log message
    $record = _gettext("Starting the correction of the migrated `schedule` data in QWcrm.");       
                
    // Result message
    $executed_sql_results .= '<div><strong><span style="color: green">'.$record.'</span></strong></div>';
    
    // Log message to setup log                
    write_record_to_setup_log('migrate', $record);
    
    $sql =  "SELECT            
            ".$qwcrm_prefix."schedule.schedule_id AS qw_schedule_id,

            ".$myitcrm_prefix."TABLE_SCHEDULE.SCHEDULE_ID AS my_schedule_id,
            ".$myitcrm_prefix."TABLE_SCHEDULE.WORK_ORDER_ID AS my_work_order_id,
            
            ".$myitcrm_prefix."TABLE_WORK_ORDER.CUSTOMER_ID AS my_customer_id

            FROM ".$qwcrm_prefix."schedule
            LEFT JOIN ".$myitcrm_prefix."TABLE_SCHEDULE ON ".$qwcrm_prefix."schedule.schedule_id = ".$myitcrm_prefix."TABLE_SCHEDULE.SCHEDULE_ID  
            LEFT JOIN ".$myitcrm_prefix."TABLE_WORK_ORDER ON ".$myitcrm_prefix."TABLE_SCHEDULE.WORK_ORDER_ID = ".$myitcrm_prefix."TABLE_WORK_ORDER.WORK_ORDER_ID";

    /* Processs the records */

    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to return the matching Schedules."));

    } else {

        while(!$rs->EOF) {            

            $myitcrm_record = $rs->GetRowAssoc(); 

            /* customer_id */
            update_record_value($qwcrm_prefix.'schedule', 'schedule_id', $myitcrm_record['qw_schedule_id'], 'customer_id', $myitcrm_record['my_customer_id']);
            
            // Advance the INSERT loop to the next record
            $rs->MoveNext();           

        }//EOF While loop

    }
    
    /* Final Stuff */

    // Log message
    $record = _gettext("Finished the correction of the migrated `schedule` data in QWcrm."); 
     
    // Result message
    $executed_sql_results .= '<div><strong><span style="color: green">'.$record.'</span></strong></div>';

    // Add division to seperate table migration function results
    $executed_sql_results .= '<div>&nbsp;</div>';

    // Log message to setup log                
    write_record_to_setup_log('migrate', $record);

    return;

}

############################################
#   Correct migrated user data             #
############################################

function migrate_database_correction_user($qwcrm_prefix, $myitcrm_prefix) {
    
    $db = QFactory::getDbo();
    global $executed_sql_results;
    
    // Add division to seperate table migration function results
    $executed_sql_results .= '<div>&nbsp;</div>';
    
    // Log message
    $record = _gettext("Starting the correction of the migrated `user` data in QWcrm.");       
                
    // Result message
    $executed_sql_results .= '<div><strong><span style="color: green">'.$record.'</span></strong></div>';
    
    // Log message to setup log                
    write_record_to_setup_log('migrate', $record);
    
    $sql = "SELECT * FROM ".$qwcrm_prefix."user";

    /* Processs the records */

    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to return the matching Users."));

    } else {

        while(!$rs->EOF) {            

            $qwcrm_record = $rs->GetRowAssoc(); 

            // Sanitise user's usernames - remove all spaces
            update_record_value($qwcrm_prefix.'user', 'user_id', $qwcrm_record['user_id'], 'username', str_replace(' ', '.', $qwcrm_record['username']));            
            
            // Advance the INSERT loop to the next record
            $rs->MoveNext();           

        }//EOF While loop

    }
    
    /* Final Stuff */

    // Log message
    $record = _gettext("Finished the correction of the migrated `user` data in QWcrm."); 
     
    // Result message
    $executed_sql_results .= '<div><strong><span style="color: green">'.$record.'</span></strong></div>';

    // Add division to seperate table migration function results
    $executed_sql_results .= '<div>&nbsp;</div>';

    // Log message to setup log                
    write_record_to_setup_log('migrate', $record);

    return;

}

##################################
#  Get MyITCRM company details   #
##################################

function get_myitcrm_company_details($item = null) {
    
    $config = QFactory::getConfig();
    $db = QFactory::getDbo();
    
    $sql = "SELECT * FROM ".$config->myitcrm_prefix."TABLE_COMPANY";
    
    if(!$rs = $db->execute($sql)) {        
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get MyITCRM company details."));        
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

function migrate_table($qwcrm_table, $myitcrm_table, $column_mappings) {
    
    $db = QFactory::getDbo();
    global $executed_sql_results;
    global $setup_error_flag;
    
    // Add division to seperate table migration function results
    $executed_sql_results .= '<div>&nbsp;</div>';
    
    // Log message
    $record = _gettext("Beginning the migration of MyITCRM data into the QWcrm table").': `'.$qwcrm_table.'`';       
                
    // Result message
    $executed_sql_results .= '<div><strong><span style="color: green">'.$record.'</span></strong></div>';
    
    // Log message to setup log                
    write_record_to_setup_log('migrate', $record);        
    
   /* load the records from MyITCRM */
    
    $sql = "SELECT * FROM $myitcrm_table";
    
    if(!$rs = $db->execute($sql)) {
        
        // set error flag
        $error_flag = true; 
        
        // Log message
        $record = _gettext("Error reading the MyITCRM table").' `'.$myitcrm_table.'` - SQL: '.$sql.' - SQL Error: '.$db->ErrorMsg();        
                
        // Result message
        $executed_sql_results .= '<div><span style="color: red">'.$record.'</span></div>';
        
        // Log message to setup log                
        write_record_to_setup_log('migrate', $record);        
                
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
                $error_flag = true;
                
                // Advance the records_failed counter
                ++$records_failed;
                
                // Log message
                $record = _gettext("Error migrating a MyITCRM record into QWcrm");
                
                // Result message
                $executed_sql_results .= '<div><span style="color: red">'.$record.' - SQL Error: '.$db->ErrorMsg().'</span></div>';                
                
                // Log message to setup log                
                write_record_to_setup_log('migrate', $record, $db->ErrorMsg(), $sql);                
                
                
                
            } else {
                 
                // Advance the records_successful counter
                ++$records_successful;
                
                // NO logging, otherwise log file would be huge
                
                /* success  
             
                // Log message
                $record = _gettext("Successfully migrated a MyITCRM record into QWcrm");
                
                // Result message
                $executed_sql_results .= '<div><span style="color: green">'.$record.'</span></div>';
                                
                // Log message to setup log                
                write_record_to_setup_log('migrate', $record);
               
                */                
                
            }
            
            // Advance the records_processed counter
            ++$records_processed;
            
            // Advance the INSERT loop to the next record            
            $rs->MoveNext();
        
        }// EOF While Loop
        
        // Output Record counters        
        $executed_sql_results .= '<div><span style="color: blue">'._gettext("MyITCRM Records Processed").': '.$records_processed.'</span></div>';
        $executed_sql_results .= '<div><span style="color: red">'._gettext("Records Failed To Migrate").': '.$records_failed.'</span></div>';
        $executed_sql_results .= '<div><span style="color: green">'._gettext("Records Successfuly Migrated").': '.$records_successful.'</span></div>';        
        
        // if there has been an error
        if($error_flag) {
            
            // Set the setup global error flag
            $setup_error_flag = true;
            
            // Log message
            $record = _gettext("Error migrating some records into QWcrm table").': `'.$qwcrm_table.'`';
            $record_additional = ' - '._gettext("MyITCRM Records Processed").': '.$records_processed.' - '._gettext("Records Failed To Migrate").': '.$records_failed.' - '._gettext("Records Successfuly Migrated").': '.$records_successful;
            
            // Result message
            $executed_sql_results .= '<div><strong><span style="color: red">'.$record.'</span></strong></div>';
            
            // Add division to seperate table migration function results
            $executed_sql_results .= '<div>&nbsp;</div>';

            // Log message to setup log                
            write_record_to_setup_log('migrate', $record.$record_additional);
                
            return false;
        
        // if all ran successfully
        } else {
            
            // Log message
            $record = _gettext("Successfully migrated all records into QWcrm table").': `'.$qwcrm_table.'`';
            $record_additional = ' - '._gettext("MyITCRM Records Processed").': '.$records_processed.' - '._gettext("Records Failed To Migrate").': '.$records_failed.' - '._gettext("Records Successfuly Migrated").': '.$records_successful;
            
            // Result message
            $executed_sql_results .= '<div><strong><span style="color: green">'.$record.'</span></strong></div>';
            
            // Add division to seperate table migration function results
            $executed_sql_results .= '<div>&nbsp;</div>';

            // Log message to setup log                
            write_record_to_setup_log('migrate', $record.$record_additional);
            
            return true;
            
        }             
    
    }

}

#########################################################
#   check myitcrm database is accessible and is 2.9.3   #
#########################################################
// 
function check_myitcrm_database_connection($myitcrm_prefix) {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT VERSION_ID FROM ".$myitcrm_prefix."VERSION WHERE VERSION_ID = '293'";
    
    if(!$rs = $db->execute($sql)) {        
        
        // output message failed to connect to the myitcrm database
        return false;
    
    } else {
        
        if($rs->RecordCount() != 1) {
            
            //output error message database is not 293
            return false;
            
        } else {
         
            // myitcrm database is sutiable for migration
            return true;
            
        }
            
    }
    
}


##############################################
#  Merge QWcrm and MyITCRM company details   #
##############################################

function get_merged_company_details() {
    
    $qwcrm_company_details              = get_company_details();
    $myitcrm_company_details            = get_myitcrm_company_details();
    
    $merged['display_name']             = $myitcrm_company_details['COMPANY_NAME'];
    $merged['logo']                     = '';
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
    $merged['company_number']           = $myitcrm_company_details['COMPANY_ABN'];    
    $merged['tax_type']                 = $qwcrm_company_details['tax_type'];
    $merged['tax_rate']                 = $qwcrm_company_details['tax_rate'];
    $merged['vat_number']               = '';
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

/** upgrade **/

############################################
#   Upgrade database                       #
############################################

function upgrade_database() {

    $db = QFactory::getDbo();
    
    // not done yet
       
}