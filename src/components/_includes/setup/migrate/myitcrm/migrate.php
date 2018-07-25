<?php

/*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

/** Migrate MyITCRM v2.9.3 **/

// This file contains all specific routines for the migration

/** Misc **/

#########################################################
#   check myitcrm database is accessible and is 2.9.3   #
#########################################################

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

/** Migration Routines **/

############################################
#   Migrate myitcrm database               #
############################################

function myitcrm_migrate_database($qwcrm_prefix, $myitcrm_prefix) {
    
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
    myitcrm_migrate_database_correction_workorder($qwcrm_prefix, $myitcrm_prefix);
    
    // Invoice
    myitcrm_migrate_database_correction_invoice($qwcrm_prefix);
    
    // Giftcert
    myitcrm_migrate_database_correction_giftcert($qwcrm_prefix);
    
    // Schedule
    myitcrm_migrate_database_correction_schedule($qwcrm_prefix, $myitcrm_prefix);
    
    // User
    myitcrm_migrate_database_correction_user($qwcrm_prefix);
    
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

function myitcrm_migrate_database_correction_workorder($qwcrm_prefix, $myitcrm_prefix) {
    
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

function myitcrm_migrate_database_correction_invoice($qwcrm_prefix) {
    
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

function myitcrm_migrate_database_correction_giftcert($qwcrm_prefix) {
    
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

function myitcrm_migrate_database_correction_schedule($qwcrm_prefix, $myitcrm_prefix) {
    
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

function myitcrm_migrate_database_correction_user($qwcrm_prefix, $myitcrm_prefix) {
    
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