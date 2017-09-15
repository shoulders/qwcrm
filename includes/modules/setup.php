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
    
    global $executed_sql_results; 
    
    $sql = "UPDATE $table SET
            $column         =". $db->qstr( $new_value )."                      
            WHERE $column   =". $db->qstr( $current_value  );

    if(!$rs = $db->execute($sql)) { 
        
        $record = gettext("Failed to update the value").' `'.$current_value.'` '.gettext("to").' `'.$new_value.'` '.gettext("in the columm").' `'.$column.'` '.gettext("from the table").' `'.$table.'` ';

        // Result message
        $executed_sql_results .= '<div><span style="color: red">'.$record.'</span></div>';        
        
        // Log mesage to setup log        
        write_record_to_setup_log('migrate', $record, $db->ErrorMsg(), $sql);
        
        return false;
        
    } else {
        
        $record = gettext("Successfully updated the value").' `'.$current_value.'` '.gettext("to").' `'.$new_value.'` '.gettext("in the columm").' `'.$column.'` '.gettext("from the the table").' `'.$table.'` ';

        // Result message
        $executed_sql_results .= '<div><span style="color: green">'.$record.'</span></div>';
               
        // Log mesage to setup log        
        write_record_to_setup_log('install', $record);
        
        return true;
        
        
    }
    
    
    
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
    $executed_sql_results .= '<div>';
    
    // Loop through preg_match() result
    foreach ($sql_statements['0'] as $sql)
    {
        
        // Get rule name for output
        preg_match('/(^SET.*$|^.*`.*`)/U', $sql, $query_name);
        
       // Perform the query
        if(!$rs = $db->Execute($sql)) {
            
            // Start result message
            $executed_sql_results .= '<span style="color: red">';
                        
            // Log mesage to setup log
            $record = gettext("Error performing SQL query").' : '. $query_name['0'];
            write_record_to_setup_log('install', $record, $db->ErrorMsg(), $sql);
            
            // Finish result message
            $executed_sql_results .= $record;
            $executed_sql_results .= '</span><br />';
            $error_flag = true;
            
        } else {
            
            // Start result message
            $executed_sql_results .= '<span style="color: green">';
            
            // Log mesage to setup log            
            $record = gettext("Performed SQL query successfully").' : '. $query_name['0'];
            write_record_to_setup_log('install', $record);
            
            // Finish result message
            $executed_sql_results .= $record;
            $executed_sql_results .= '</span><br />';

        }

    }
    
    // Close results container
    $executed_sql_results .= '</div>';
    
    if($error_flag) {
        
        // Start final message
        $executed_sql_results .= '<br><div style="color: red;">';
        
        // Log mesage to setup log
        $record = gettext("One or more SQL rule has failed. Check the logs.");
        write_record_to_setup_log('install', $record);
        
        // Finish result message
        $executed_sql_results .= $record;
        $executed_sql_results .= '</div>';
        
        // Output message via smarty
        $smarty->assign('executed_sql_results', $executed_sql_results);
        
        return false;
        
    } else {
        
        // Start final message
        $executed_sql_results .= '<br><div style="color: green;">';
                
        // Log mesage to setup log
        $record = gettext("All SQL rules have run successfully.");
        write_record_to_setup_log('install', $record);
        
        // Finish result message
        $executed_sql_results .= $record;
        $executed_sql_results .= '</div>';
        
        // Output message via smarty
        $smarty->assign('executed_sql_results', $executed_sql_results);
        
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
    $executed_sql_results .= '<div>';    
    
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
                $executed_sql_results .= '<span style="color: red">';

                // Log mesage to setup log
                $record = gettext("Error performing SQL query").' : '. $query_name['0'];
                write_record_to_setup_log('upgrade', $record, $db->ErrorMsg(), $sql);

                // Finish result message
                $executed_sql_results .= $record;
                $executed_sql_results .= '</span><br />';
                $error_flag = true;

            } else {

                // Start result message
                $executed_sql_results .= '<span style="color: green">';

                // Log mesage to setup log            
                $record = gettext("Performed SQL query successfully").' : '. $query_name['0'];
                write_record_to_setup_log('upgrade', $record);

                // Finish result message
                $executed_sql_results .= $record;
                $executed_sql_results .= '</span><br />';

            }            
                        
            // Reset templine variable to empty ready for the next line
            $sql = '';
            
        }        
        
    } 
    
    // Close results container
    $executed_sql_results .= '</div>';

    if($error_flag) {

        // Start final message
        $executed_sql_results .= '<br><div style="color: red;">';
        
        // Log mesage to setup log
        $record = gettext("One or more SQL rule has failed. Check the logs.");
        write_record_to_setup_log('upgrade', $record);
        
        // Finish result message
        $executed_sql_results .= $record;
        $executed_sql_results .= '</div>';
        
        // Output message via smarty
        $smarty->assign('executed_sql_results', $executed_sql_results);
        
        return false;

    } else {

        // Start final message
        $executed_sql_results .= '<br><div style="color: green;">';
                
        // Log mesage to setup log
        $record = gettext("All SQL rules have run successfully.");
        write_record_to_setup_log('upgrade', $record);
        
        // Finish result message
        $executed_sql_results .= $record;
        $executed_sql_results .= '</div>';
        
        // Output message via smarty
        $smarty->assign('executed_sql_results', $executed_sql_results);
        
        return true;

    }
        
}

############################################
#  Write a record to the Setup Log         #    // cannot be turned off - install/migrate/upgrade
############################################

function write_record_to_setup_log($setup_type, $record, $database_error = null, $sql_query = null) {
    
    // Install and migrate does not have username or login_user_id available
    if(QWCRM_SETUP == 'install') {
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
    
    $acceptedChars = 'abcdefghijklmnopqrstuvwxyz';
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

function migrate_database($db, $qwcrm_prefix, $myitcrm_prefix) {
    
    global $smarty;
    global $executed_sql_results;   
    
    //migrate_table($db, $qwcrm_prefix, $qwcrm_table, $myitcrm_prefix, $myitcrm_table, $column_mappings)
    // array('username' => 'username')   
        
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
    migrate_table($db, $qwcrm_prefix.'customer', $myitcrm_prefix.'TABLE_CUSTOMER', $column_mappings);
    
    // update customer types
    update_values($db, $qwcrm_prefix.'customer', 'type', '1', 'residential');
    update_values($db, $qwcrm_prefix.'customer', 'type', '2', 'commercial');
    update_values($db, $qwcrm_prefix.'customer', 'type', '3', 'charity');
    update_values($db, $qwcrm_prefix.'customer', 'type', '4', 'educational');
    update_values($db, $qwcrm_prefix.'customer', 'type', '5', 'goverment');
    
    // update active status (all enabled)
    update_values($db, $qwcrm_prefix.'customer', 'active', '*', '1');
    
    // customer_notes
    $column_mappings = array(
        'customer_note_id'  => 'ID',
        'employee_id'       => '',
        'customer_id'       => 'CUSTOMER_ID',
        'date'              => 'DATE',
        'note'              => 'NOTE'
        );    
    migrate_table($db, $qwcrm_prefix.'customer_notes', $myitcrm_prefix.'CUSTOMER_NOTES', $column_mappings);    
            
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
        'tax_rate'          => 'EXPENSE_TAX_RATE',
        'tax_amount'        => 'EXPENSE_TAX_AMOUNT',
        'gross_amount'      => 'EXPENSE_GROSS_AMOUNT',
        'items'             => 'EXPENSE_ITEMS',
        'notes'             => 'EXPENSE_NOTES'        
        );
    migrate_table($db, $qwcrm_prefix.'expense', $myitcrm_prefix.'TABLE_EXPENSE', $column_mappings);
    
    // update expense types
    update_values($db, $qwcrm_prefix.'expense', 'type', '1', 'advertising');
    update_values($db, $qwcrm_prefix.'expense', 'type', '2', 'bank_charges');
    update_values($db, $qwcrm_prefix.'expense', 'type', '3', 'broadband');
    update_values($db, $qwcrm_prefix.'expense', 'type', '4', 'credit');
    update_values($db, $qwcrm_prefix.'expense', 'type', '5', 'customer_refund');
    update_values($db, $qwcrm_prefix.'expense', 'type', '6', 'customer_refund');
    update_values($db, $qwcrm_prefix.'expense', 'type', '7', 'equipment');
    update_values($db, $qwcrm_prefix.'expense', 'type', '8', 'gift_certificate');
    update_values($db, $qwcrm_prefix.'expense', 'type', '9', 'landline');
    update_values($db, $qwcrm_prefix.'expense', 'type', '10', 'mobile_phone');
    update_values($db, $qwcrm_prefix.'expense', 'type', '11', 'office_supplies');
    update_values($db, $qwcrm_prefix.'expense', 'type', '12', 'parts');
    update_values($db, $qwcrm_prefix.'expense', 'type', '13', 'fuel');
    update_values($db, $qwcrm_prefix.'expense', 'type', '14', 'postage');
    update_values($db, $qwcrm_prefix.'expense', 'type', '15', 'tax');
    update_values($db, $qwcrm_prefix.'expense', 'type', '16', 'rent');
    update_values($db, $qwcrm_prefix.'expense', 'type', '17', 'transport');
    update_values($db, $qwcrm_prefix.'expense', 'type', '18', 'utilities');
    update_values($db, $qwcrm_prefix.'expense', 'type', '19', 'voucher');
    update_values($db, $qwcrm_prefix.'expense', 'type', '20', 'wages');
    update_values($db, $qwcrm_prefix.'expense', 'type', '21', 'other');
    
    // update expense payment method
    update_values($db, $qwcrm_prefix.'expense', 'payment_method', '1', 'bank_transfer');
    update_values($db, $qwcrm_prefix.'expense', 'payment_method', '2', 'card');
    update_values($db, $qwcrm_prefix.'expense', 'payment_method', '3', 'cash');
    update_values($db, $qwcrm_prefix.'expense', 'payment_method', '4', 'cheque');
    update_values($db, $qwcrm_prefix.'expense', 'payment_method', '5', 'credit');
    update_values($db, $qwcrm_prefix.'expense', 'payment_method', '6', 'direct_debit');
    update_values($db, $qwcrm_prefix.'expense', 'payment_method', '7', 'gift_certificate');
    update_values($db, $qwcrm_prefix.'expense', 'payment_method', '8', 'google_checkout');
    update_values($db, $qwcrm_prefix.'expense', 'payment_method', '9', 'paypal');
    update_values($db, $qwcrm_prefix.'expense', 'payment_method', '10', 'voucher');
    update_values($db, $qwcrm_prefix.'expense', 'payment_method', '11', 'other');    
    
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
    migrate_table($db, $qwcrm_prefix.'giftcert', $myitcrm_prefix.'GIFT_CERT', $column_mappings);
    
    /* Invoice */
    
    // invoice
    $column_mappings = array(
        'invoice_id'        => 'INVOICE_ID',
        'employee_id'       => 'EMPLOYEE_ID',
        'customer_id'       => 'CUSTOMER_ID',
        'workorder_id'      => 'WORKORDER_ID',
        'date'              => 'INVOICE_DATE',
        'due_date'          => 'INVOICE_DUE',
        'discount_rate'     => 'DISCOUNT',
        'tax_rate'          => 'TAX_RATE',
        'sub_total'         => 'SUB_TOTAL',
        'discount_amount'   => 'DISCOUNT_APPLIED',
        'net_amount'        => '',
        'tax_amount'        => 'TAX',
        'gross_amount'      => 'INVOICE_AMOUNT',
        'paid_amount'       => 'PAID_AMOUNT',
        'balance'           => 'BALANCE',
        'open_date'         => '',
        'close_date'        => '',
        'last_active'       => '',
        'status'            => '',
        'is_closed'         => 'INVOICE_PAID',
        'paid_date'         => 'PAID_DATE'     
        );
    migrate_table($db, $qwcrm_prefix.'invoice', $myitcrm_prefix.'TABLE_INVOICE', $column_mappings);
    
    // invoice_labour
    $column_mappings = array(
        'invoice_labour_id' => 'INVOICE_LABOR_ID',
        'invoice_id'        => 'INVOICE_ID',
        'description'       => 'INVOICE_LABOR_DESCRIPTION',
        'amount'            => 'INVOICE_LABOR_RATE',
        'qty'               => 'INVOICE_LABOR_UNIT',
        'sub_total'         => 'INVOICE_LABOR_SUBTOTAL'    
        );
    migrate_table($db, $qwcrm_prefix.'invoice_labour', $myitcrm_prefix.'TABLE_INVOICE_LABOR', $column_mappings);
    
    // invoice_parts
    $column_mappings = array(
        'invoice_parts_id'  => 'INVOICE_PARTS_ID',
        'invoice_id'        => 'INVOICE_ID',
        'description'       => 'INVOICE_PARTS_DESCRIPTION',
        'amount'            => 'INVOICE_PARTS_AMOUNT',
        'qty'               => 'INVOICE_PARTS_COUNT',
        'sub_total'         => 'INVOICE_PARTS_SUBTOTAL'    
        );
    migrate_table($db, $qwcrm_prefix.'invoice_parts', $myitcrm_prefix.'TABLE_INVOICE_PARTS', $column_mappings);        
    
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
    migrate_table($db, $qwcrm_prefix.'payment_transactions', $myitcrm_prefix.'TABLE_TRANSACTION', $column_mappings);
    
    // update payment types
    update_values($db, $qwcrm_prefix.'payment_transactions', 'method', '1', 'credit_card');
    update_values($db, $qwcrm_prefix.'payment_transactions', 'method', '2', 'cheque');
    update_values($db, $qwcrm_prefix.'payment_transactions', 'method', '3', 'cash');
    update_values($db, $qwcrm_prefix.'payment_transactions', 'method', '4', 'gift_certificate');
    update_values($db, $qwcrm_prefix.'payment_transactions', 'method', '5', 'paypal');    
    
    /* Refund */
    
    // refund
    $column_mappings = array(
        'refund_id'         => 'REFUND_ID',
        'payee'             => 'REFUND_PAYEE',
        'date'              => 'REFUND_DATE',
        'type'              => 'REFUND_TYPE',
        'payment_method'    => 'REFUND_PAYMENT_METHOD',
        'net_amount'        => 'REFUND_NET_AMOUNT',
        'tax_rate'          => 'REFUND_TAX_RATE',
        'tax_amount'        => 'REFUND_TAX_AMOUNT',
        'gross_amount'      => 'REFUND_GROSS_AMOUNT',
        'items'             => 'REFUND_ITEMS',
        'notes'             => 'REFUND_NOTES'        
        );
    migrate_table($db, $qwcrm_prefix.'refund', $myitcrm_prefix.'TABLE_REFUND', $column_mappings);
    
    // update refund types
    update_values($db, $qwcrm_prefix.'refund', 'type', '1', 'credit_note');
    update_values($db, $qwcrm_prefix.'refund', 'type', '2', 'proxy_invoice');
    update_values($db, $qwcrm_prefix.'refund', 'type', '3', 'returned_goods');
    update_values($db, $qwcrm_prefix.'refund', 'type', '4', 'returned_services');
    update_values($db, $qwcrm_prefix.'refund', 'type', '5', 'other');
    
    // update refund payment methods
    update_values($db, $qwcrm_prefix.'refund', 'payment_method', '1', 'bank_transfer');
    update_values($db, $qwcrm_prefix.'refund', 'payment_method', '2', 'card');
    update_values($db, $qwcrm_prefix.'refund', 'payment_method', '3', 'cash');
    update_values($db, $qwcrm_prefix.'refund', 'payment_method', '4', 'cheque');
    update_values($db, $qwcrm_prefix.'refund', 'payment_method', '5', 'credit');
    update_values($db, $qwcrm_prefix.'refund', 'payment_method', '6', 'direct_debit');
    update_values($db, $qwcrm_prefix.'refund', 'payment_method', '7', 'gift_certificate');
    update_values($db, $qwcrm_prefix.'refund', 'payment_method', '8', 'google_checkout');
    update_values($db, $qwcrm_prefix.'refund', 'payment_method', '9', 'paypal');
    update_values($db, $qwcrm_prefix.'refund', 'payment_method', '10', 'voucher');
    update_values($db, $qwcrm_prefix.'refund', 'payment_method', '11', 'other');    
    
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
    migrate_table($db, $qwcrm_prefix.'schedule', $myitcrm_prefix.'TABLE_SCHEDULE', $column_mappings);
    
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
    migrate_table($db, $qwcrm_prefix.'supplier', $myitcrm_prefix.'TABLE_SUPPLIER', $column_mappings);
    
    // update supplier types
    update_values($db, $qwcrm_prefix.'supplier', 'type', '1', 'affiliate_marketing');
    update_values($db, $qwcrm_prefix.'supplier', 'type', '2', 'advertising');
    update_values($db, $qwcrm_prefix.'supplier', 'type', '3', 'drop_shipping');
    update_values($db, $qwcrm_prefix.'supplier', 'type', '4', 'courier');
    update_values($db, $qwcrm_prefix.'supplier', 'type', '5', 'general');
    update_values($db, $qwcrm_prefix.'supplier', 'type', '6', 'parts');
    update_values($db, $qwcrm_prefix.'supplier', 'type', '7', 'services');
    update_values($db, $qwcrm_prefix.'supplier', 'type', '8', 'software');
    update_values($db, $qwcrm_prefix.'supplier', 'type', '9', 'wholesale');
    update_values($db, $qwcrm_prefix.'supplier', 'type', '10', 'online');
    update_values($db, $qwcrm_prefix.'supplier', 'type', '11', 'other');
    
    /* user / Employee */
    
    // supplier
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
    migrate_table($db, $qwcrm_prefix.'user', $myitcrm_prefix.'TABLE_EMPLOYEE', $column_mappings);
    
    // update require_reset - enabled for all
    update_values($db, $qwcrm_prefix.'user', 'require_reset', '*', '1');
    
    // update is_employee - set all import users to employees
    update_values($db, $qwcrm_prefix.'user', 'is_employee', '*', '1');
    
    //reset_all_passwords($db);  // or enable require reset code - is this not done already
    
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
    migrate_table($db, $qwcrm_prefix.'workorder', $myitcrm_prefix.'TABLE_WORK_ORDER', $column_mappings);
    
    // update status types
    update_values($db, $qwcrm_prefix.'workorder', 'status', '1', 'unassigned');          // created
    update_values($db, $qwcrm_prefix.'workorder', 'status', '2', 'assigned');            // assinged
    update_values($db, $qwcrm_prefix.'workorder', 'status', '3', 'waiting_for_parts');   // waiting for parts
    // update_values($db, $qwcrm_prefix.'workorder', 'status', '4', '');                 // n/a
    // update_values($db, $qwcrm_prefix.'workorder', 'status', '5', '');                 // n/a
    // update_values($db, $qwcrm_prefix.'workorder', 'status', '6', '');                 // closed
    // update_values($db, $qwcrm_prefix.'workorder', 'status', '7', '');                 // awaiting payment
    // update_values($db, $qwcrm_prefix.'workorder', 'status', '8', '');                 // payment made
    update_values($db, $qwcrm_prefix.'workorder', 'status', '9', 'on_hold');             // pending
    update_values($db, $qwcrm_prefix.'workorder', 'status', '10', 'unassigned');         // open
    
    // workorder_history
    $column_mappings = array(
        'history_id'        => 'WORK_ORDER_STATUS_ID',
        'employee_id'       => 'WORK_ORDER_STATUS_ENTER_BY',
        'workorder_id'      => 'WORK_ORDER_ID',
        'date'              => 'WORK_ORDER_STATUS_DATE',
        'note'              => 'WORK_ORDER_STATUS_NOTES'         
        ); 
    migrate_table($db, $qwcrm_prefix.'workorder_history', $myitcrm_prefix.'TABLE_WORK_ORDER_STATUS', $column_mappings);    
    
    // workorder_notes
    $column_mappings = array(
        'workorder_note_id' => 'WORK_ORDER_NOTES_ID',
        'employee_id'       => 'WORK_ORDER_NOTES_ENTER_BY',
        'workorder_id'      => 'WORK_ORDER_ID',
        'date'              => 'WORK_ORDER_NOTES_DATE',
        'description'       => 'WORK_ORDER_NOTES_DESCRIPTION'         
        ); 
    migrate_table($db, $qwcrm_prefix.'workorder_notes', $myitcrm_prefix.'TABLE_WORK_ORDER_NOTES', $column_mappings); 

    /* Final stuff */

    $smarty->assign('executed_sql_results' ,$executed_sql_results);

    return;
    
}

/* corrections */ // can only be done now i.e. miussing info and calculations etc...


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

function migrate_table($db, $qwcrm_table, $myitcrm_table, $column_mappings) {
    
    global $executed_sql_results;
    
    // Add division to seperate table migration function results
    $executed_sql_results .= '<div>&nbsp;</div>';
    
    $record = gettext("Beginning the migration of MyITCRM data into the QWcrm table").': `'.$qwcrm_table.'`';       
                
    // Result message
    $executed_sql_results .= '<div><strong><span style="color: green">'.$record.'</span></strong></div>';
    
    // Log mesage to setup log                
    write_record_to_setup_log('migrate', $record);        
    
   /* load the records from MyITCRM */
    
    $sql = "SELECT * FROM $myitcrm_table";
    
    if(!$rs = $db->execute($sql)) {
        
        $record = gettext("Error reading the MyITCRM table").' `'.$myitcrm_table.'` - SQL: '.$sql.' - SQL Error: '.$db->ErrorMsg();        
                
        // Result message
        $executed_sql_results .= '<div><span style="color: red">'.$record.'</span></div>';
        
        // Log mesage to setup log                
        write_record_to_setup_log('migrate', $record);

        // set error flag
        $error_flag = true; 
                
        // output error, could not load table so all of this table was skipped
        return false;
    
    } else {
        
        /* Load each single records and insert into QWcrm */ 
        
        // Error Flag
        $error_flag = false;
        
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
                
                $record = gettext("Error migrating a MyITCRM record into QWcrm");
                
                // Result message
                $executed_sql_results .= '<div><span style="color: red">'.$record.' - SQL Error: '.$db->ErrorMsg().'</span></div>';                
                
                // Log mesage to setup log                
                write_record_to_setup_log('migrate', $record, $db->ErrorMsg(), $sql);
                
                // set error flag
                $error_flag = true;            
                
            } else {
                
                // if a successfull INSERT, NO LOGGINBG, otherwise log would be huge
                
                /* success  
             
                $record = gettext("Successfully migrated a MyITCRM record into QWcrm");
                
                // Result message
                $executed_sql_results .= '<div><span style="color: green">'.$record.'</span></div>';
                                
                // Log mesage to setup log                
                write_record_to_setup_log('migrate', $record);
               
                */             
                
            }
            
            // Advance the INSERT loop to the next record
            $rs->MoveNext();
        
        }// EOF While Loop
        
        // if there has been an error
        if($error_flag) {
            
            $record = gettext("Error migrating some records into QWcrm table").': `'.$qwcrm_table.'`';
                
            // Result message
            $executed_sql_results .= '<div><strong><span style="color: red">'.$record.'</span></strong></div>';
            
            // Add division to seperate table migration function results
            $executed_sql_results .= '<div>&nbsp;</div>';

            // Log mesage to setup log                
            write_record_to_setup_log('migrate', $record);
                
            return false;
        
        // if all ran successfully
        } else {
            
            $record = gettext("Successfully migrated all records into QWcrm table").': `'.$qwcrm_table.'`';
                
            // Result message
            $executed_sql_results .= '<div><strong><span style="color: green">'.$record.'</span></strong></div>';
            
            // Add division to seperate table migration function results
            $executed_sql_results .= '<div>&nbsp;</div>';

            // Log mesage to setup log                
            write_record_to_setup_log('migrate', $record);
            
            return true;
            
        }             
    
    }

}

#########################################################
#   check myitcrm database is accessible and is 2.9.3   #
#########################################################
// 
function check_myitcrm_database_connection($db, $myitcrm_prefix) {
    
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