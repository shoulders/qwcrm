<?php

/*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

/** Migrate MyITCRM v2.9.3 **/

// This file contains all specific routines for the migrationmodified for the QWcrm v3.0.0 database

defined('_QWEXEC') or die;

class MigrateMyitcrm extends Setup {
    
    public function __construct(&$VAR) {
        
        // Call parent's constructor
        parent::__construct($VAR);
                
    } 

    /** Mandatory Code **/

    /** Display Functions **/

    /** Insert Functions **/

    #####################################
    #    Insert new user                #
    #####################################

    public function insert_user($VAR) {

        $db = \Factory::getDbo();

        $sql = "INSERT INTO ".PRFX."user SET
                customer_id         =". $db->qstr( $VAR['customer_id']                          ).", 
                username            =". $db->qstr( $VAR['username']                             ).",
                password            =". $db->qstr( \Joomla\CMS\User\UserHelper::hashPassword($VAR['password'])  ).",
                email               =". $db->qstr( $VAR['email']                                ).",
                usergroup           =". $db->qstr( $VAR['usergroup']                            ).",
                active              =". $db->qstr( $VAR['active']                               ).",
                register_date       =". $db->qstr( time()                                       ).",   
                require_reset       =". $db->qstr( $VAR['require_reset']                        ).",
                is_employee         =". $db->qstr( $VAR['is_employee']                          ).",              
                display_name        =". $db->qstr( $VAR['display_name']                         ).",
                first_name          =". $db->qstr( $VAR['first_name']                           ).",
                last_name           =". $db->qstr( $VAR['last_name']                            ).",
                work_primary_phone  =". $db->qstr( $VAR['work_primary_phone']                   ).",
                work_mobile_phone   =". $db->qstr( $VAR['work_mobile_phone']                    ).",
                work_fax            =". $db->qstr( $VAR['work_fax']                             ).",                    
                home_primary_phone  =". $db->qstr( $VAR['home_primary_phone']                   ).",
                home_mobile_phone   =". $db->qstr( $VAR['home_mobile_phone']                    ).",
                home_email          =". $db->qstr( $VAR['home_email']                           ).",
                home_address        =". $db->qstr( $VAR['home_address']                         ).",
                home_city           =". $db->qstr( $VAR['home_city']                            ).",  
                home_state          =". $db->qstr( $VAR['home_state']                           ).",
                home_zip            =". $db->qstr( $VAR['home_zip']                             ).",
                home_country        =". $db->qstr( $VAR['home_country']                         ).", 
                based               =". $db->qstr( $VAR['based']                                ).",  
                notes               =". $db->qstr( $VAR['notes']                                );                 

        if(!$rs = $db->Execute($sql)) {
            $this->app->system->page->force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to insert the user record into the database."));
        } else {

            // Get user_id
            $user_id = $db->Insert_ID();

            // Log activity
            $record = _gettext("Administrator Account").' '.$user_id.' ('.$this->get_user_details($user_id, 'username').') '._gettext("for").' '.$this->get_user_details($user_id, 'display_name').' '._gettext("created").'.';
            $this->write_record_to_setup_log('migrate', $record);

            return $user_id;

        }

    }

    /** Get Functions **/

    ##########################
    #  Get Company details   #
    ##########################

    /*
     * This combined public static function allows you to pull any of the company information individually
     * or return them all as an array
     * supply the required field name for a single item or all for all items as an array.
     */

    public function get_company_details($item = null) {

        $db = \Factory::getDbo();

        $sql = "SELECT * FROM ".PRFX."company";

        if(!$rs = $db->execute($sql)) { 

            // If the company lookup fails
            die('
                    <div style="color: red;">'.
                    '<strong>'._gettext("NB: This is the MyITCRM migrate version of this function.").'</strong><br>'.
                    _gettext("Something went wrong executing an SQL query.").'<br>'.
                    _gettext("Check to see if your Prefix is correct, if not you might have a configuration.php file that should not be present or is corrupt.").'<br>'.
                    _gettext("Error occured at").' <strong>'.__FUNCTION__.'()</strong> '._gettext("when trying to get the variable").' <strong>date_format</strong>'.'<br>'.
                    '</div>'
               );        

            // Any other lookup error
            $this->app->system->page->force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get company details."));        

        } else {

            if($item === null) {

                return $rs->GetRowAssoc();            

            } else {

                return $rs->fields[$item];   

            } 

        }

    }

    ##################################
    #  Get MyITCRM company details   #
    ##################################

    public function get_company_details_myitcrm($item = null) {

        $config = \Factory::getConfig();
        $db = \Factory::getDbo();

        $sql = "SELECT * FROM ".$config->get('myitcrm_prefix')."TABLE_COMPANY";

        if(!$rs = $db->execute($sql)) {        
            $this->app->system->page->force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get MyITCRM company details."));        
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

    public function get_company_details_merged() {

        $qwcrm_company_details              = $this->get_company_details();
        $myitcrm_company_details            = $this->get_company_details_myitcrm();

        $merged['display_name']             = $myitcrm_company_details['COMPANY_NAME'];
        $merged['logo']                     = 'logo.png';
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
        $merged['year_start']               = time();
        $merged['year_end']                 = strtotime('+1 year');
        //$merged['welcome_msg']              = $qwcrm_company_details['welcome_msg'];
        $merged['currency_symbol']          = $myitcrm_company_details['COMPANY_CURRENCY_SYMBOL'];
        $merged['currency_code']            = $myitcrm_company_details['COMPANY_CURRENCY_CODE'];
        $merged['date_format']              = $myitcrm_company_details['COMPANY_DATE_FORMAT'];
        //$merged['email_signature']          = $qwcrm_company_details['email_signature'];
        //$merged['email_signature_active']   = $qwcrm_company_details['email_signature_active'];
        //$merged['email_msg_invoice']        = $qwcrm_company_details['email_msg_invoice'];
        //$merged['email_msg_workorder']      = $qwcrm_company_details['email_msg_workorder'];

        // NB: the remmed out items are not on the setup company_details page so are added in via myitcrrm/migrate_routines.php

        return $merged;

    }
    
    #####################################
    #     Get User Details              # 
    #####################################

    public function get_user_details($user_id = null, $item = null) {

        $db = \Factory::getDbo();

        // This allows for workorder:status to work
        if(!$user_id){
            return;        
        }

        $sql = "SELECT * FROM ".PRFX."user WHERE user_id =".$db->qstr($user_id);

        if(!$rs = $db->execute($sql)){        
            $this->app->system->page->force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get the user details."));
        } else {

            if($item === null) {

                return $rs->GetRowAssoc();

            } else {

                if($item === null){

                    return $rs->GetRowAssoc();

                } else {

                    return $rs->fields[$item];   

                } 

            } 

        }

    }

    /** Update Functions **/
    
    #############################
    #  Update Company details   #
    #############################

    public function update_company_details($VAR) {

        $db = \Factory::getDbo();
        $sql = null;
        
        // Prevent undefined variable errors
        $VAR['delete_logo'] = isset($VAR['delete_logo']) ? $VAR['delete_logo'] : null;

        // Delete logo if selected and no new logo is presented
        if($VAR['delete_logo'] && !$_FILES['logo']['name']) {
            $this->delete_logo();        
        }

        // A new logo is supplied, delete old and upload new
        if($_FILES['logo']['name']) {
            $this->delete_logo();
            $new_logo_filepath = $this->upload_logo();
        }
    
        $sql .= "UPDATE ".PRFX."company SET
                display_name            =". $db->qstr( $VAR['display_name']                     ).",";
                    
        if($VAR['delete_logo']) {
            $sql .="logo                =''                                                     ,";
        }

        if(!empty($_FILES['logo']['name'])) {
            $sql .="logo                =". $db->qstr( $new_logo_filepath                       ).",";
        }
        
        /*if(isset($VAR['logo']) && is_string($VAR['logo'])) {
            $sql .="logo                =". $db->qstr( $VAR['logo']                             ).",";
        }*/

        $sql .="address                 =". $db->qstr( $VAR['address']                          ).",
                city                    =". $db->qstr( $VAR['city']                             ).",
                state                   =". $db->qstr( $VAR['state']                            ).",
                zip                     =". $db->qstr( $VAR['zip']                              ).",
                country                 =". $db->qstr( $VAR['country']                          ).",
                primary_phone           =". $db->qstr( $VAR['primary_phone']                    ).",
                mobile_phone            =". $db->qstr( $VAR['mobile_phone']                     ).",
                fax                     =". $db->qstr( $VAR['fax']                              ).",
                email                   =". $db->qstr( $VAR['email']                            ).",    
                website                 =". $db->qstr( process_inputted_url($VAR['website'])    ).",
                company_number          =". $db->qstr( $VAR['company_number']                   ).",                                        
                tax_type                =". $db->qstr( $VAR['tax_type']                         ).",
                tax_rate                =". $db->qstr( $VAR['tax_rate']                         ).",
                vat_number              =". $db->qstr( $VAR['vat_number']                       ).",
                year_start              =". $db->qstr( date_to_timestamp($VAR['year_start'])    ).",
                year_end                =". $db->qstr( date_to_timestamp($VAR['year_end'])      ).",
                welcome_msg             =". $db->qstr( $VAR['welcome_msg']                      ).",
                currency_symbol         =". $db->qstr( htmlentities($VAR['currency_symbol'])    ).",
                currency_code           =". $db->qstr( $VAR['currency_code']                    ).",
                date_format             =". $db->qstr( $VAR['date_format']                      ).",
                email_signature         =". $db->qstr( $VAR['email_signature']                  ).",
                email_signature_active  =". $db->qstr( $VAR['email_signature_active']           ).",
                email_msg_invoice       =". $db->qstr( $VAR['email_msg_invoice']                ).",
                email_msg_workorder     =". $db->qstr( $VAR['email_msg_workorder']              );                          

        if(!$rs = $db->Execute($sql)) {
            $this->app->system->page->force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to update the company details."));
        } else {       

            // Assign success message
            $this->app->smarty->assign('msg_success', _gettext("Company details updated."));

            // Log activity
            $qsetup = new Setup($VAR);
            $qsetup->write_record_to_setup_log('migrate', _gettext("Company details updated."));
            
            return;

        }

    }

    /** Close Functions **/

    /** Delete Functions **/

    /** Migration Routines **/
    
    ############################################
    #   Migrate myitcrm database               #
    ############################################

    public function migrate_myitcrm_database($qwcrm_prefix, $myitcrm_prefix) {

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
        $this->migrate_table($qwcrm_prefix.'customer', $myitcrm_prefix.'TABLE_CUSTOMER', $column_mappings);

        // update customer types
        $this->update_column_values($qwcrm_prefix.'customer', 'type', '1', 'residential');
        $this->update_column_values($qwcrm_prefix.'customer', 'type', '2', 'commercial');
        $this->update_column_values($qwcrm_prefix.'customer', 'type', '3', 'charity');
        $this->update_column_values($qwcrm_prefix.'customer', 'type', '4', 'educational');
        $this->update_column_values($qwcrm_prefix.'customer', 'type', '5', 'goverment');

        // update active status (all enabled)
        $this->update_column_values($qwcrm_prefix.'customer', 'active', '*', '1');

        // customer_notes
        $column_mappings = array(
            'customer_note_id'  => 'ID',
            'employee_id'       => '',
            'customer_id'       => 'CUSTOMER_ID',
            'date'              => 'DATE',
            'note'              => 'NOTE'
            );    
        $this->migrate_table($qwcrm_prefix.'customer_notes', $myitcrm_prefix.'CUSTOMER_NOTES', $column_mappings);    

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
        $this->migrate_table($qwcrm_prefix.'expense', $myitcrm_prefix.'TABLE_EXPENSE', $column_mappings);

        // update expense types
        $this->update_column_values($qwcrm_prefix.'expense', 'type', '1', 'advertising');
        $this->update_column_values($qwcrm_prefix.'expense', 'type', '2', 'bank_charges');
        $this->update_column_values($qwcrm_prefix.'expense', 'type', '3', 'broadband');
        $this->update_column_values($qwcrm_prefix.'expense', 'type', '4', 'credit');
        $this->update_column_values($qwcrm_prefix.'expense', 'type', '5', 'customer_refund');
        $this->update_column_values($qwcrm_prefix.'expense', 'type', '6', 'customer_refund');
        $this->update_column_values($qwcrm_prefix.'expense', 'type', '7', 'equipment');
        $this->update_column_values($qwcrm_prefix.'expense', 'type', '8', 'gift_certificate');
        $this->update_column_values($qwcrm_prefix.'expense', 'type', '9', 'landline');
        $this->update_column_values($qwcrm_prefix.'expense', 'type', '10', 'mobile_phone');
        $this->update_column_values($qwcrm_prefix.'expense', 'type', '11', 'office_supplies');
        $this->update_column_values($qwcrm_prefix.'expense', 'type', '12', 'parts');
        $this->update_column_values($qwcrm_prefix.'expense', 'type', '13', 'fuel');
        $this->update_column_values($qwcrm_prefix.'expense', 'type', '14', 'postage');
        $this->update_column_values($qwcrm_prefix.'expense', 'type', '15', 'tax');
        $this->update_column_values($qwcrm_prefix.'expense', 'type', '16', 'rent');
        $this->update_column_values($qwcrm_prefix.'expense', 'type', '17', 'transport');
        $this->update_column_values($qwcrm_prefix.'expense', 'type', '18', 'utilities');
        $this->update_column_values($qwcrm_prefix.'expense', 'type', '19', 'voucher');
        $this->update_column_values($qwcrm_prefix.'expense', 'type', '20', 'wages');
        $this->update_column_values($qwcrm_prefix.'expense', 'type', '21', 'other');

        // update expense payment method
        $this->update_column_values($qwcrm_prefix.'expense', 'payment_method', '1', 'bank_transfer');
        $this->update_column_values($qwcrm_prefix.'expense', 'payment_method', '2', 'card');
        $this->update_column_values($qwcrm_prefix.'expense', 'payment_method', '3', 'cash');
        $this->update_column_values($qwcrm_prefix.'expense', 'payment_method', '4', 'cheque');
        $this->update_column_values($qwcrm_prefix.'expense', 'payment_method', '5', 'credit');
        $this->update_column_values($qwcrm_prefix.'expense', 'payment_method', '6', 'direct_debit');
        $this->update_column_values($qwcrm_prefix.'expense', 'payment_method', '7', 'gift_certificate');
        $this->update_column_values($qwcrm_prefix.'expense', 'payment_method', '8', 'google_checkout');
        $this->update_column_values($qwcrm_prefix.'expense', 'payment_method', '9', 'paypal');
        $this->update_column_values($qwcrm_prefix.'expense', 'payment_method', '10', 'voucher');
        $this->update_column_values($qwcrm_prefix.'expense', 'payment_method', '11', 'other');    

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
        $this->migrate_table($qwcrm_prefix.'giftcert', $myitcrm_prefix.'GIFT_CERT', $column_mappings);

        // update date_redeemed to remove incoreect zero dates
        $this->update_column_values($qwcrm_prefix.'giftcert', 'date_redeemed', '0', '');

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
        $this->migrate_table($qwcrm_prefix.'invoice', $myitcrm_prefix.'TABLE_INVOICE', $column_mappings);

        // Change tax_type to selected Company Tax Type for all migrated invoices - This is an assumption
        $this->update_column_values($qwcrm_prefix.'invoice', 'tax_type', '', $this->get_company_details('tax_type'));

        // change close dates from zero to ''
        $this->update_column_values($qwcrm_prefix.'invoice', 'close_date', '0', '');
        $this->update_column_values($qwcrm_prefix.'invoice', 'paid_date', '0', '');
        $this->update_column_values($qwcrm_prefix.'invoice', 'last_active', '0', '');

        // correct null workorders
        $this->update_column_values($qwcrm_prefix.'invoice', 'workorder_id', '0', '');

        // invoice_labour
        $column_mappings = array(
            'invoice_labour_id' => 'INVOICE_LABOR_ID',
            'invoice_id'        => 'INVOICE_ID',
            'description'       => 'INVOICE_LABOR_DESCRIPTION',
            'amount'            => 'INVOICE_LABOR_RATE',
            'qty'               => 'INVOICE_LABOR_UNIT',
            'sub_total'         => 'INVOICE_LABOR_SUBTOTAL'    
            );
        $this->migrate_table($qwcrm_prefix.'invoice_labour', $myitcrm_prefix.'TABLE_INVOICE_LABOR', $column_mappings);

        // invoice_parts
        $column_mappings = array(
            'invoice_parts_id'  => 'INVOICE_PARTS_ID',
            'invoice_id'        => 'INVOICE_ID',
            'description'       => 'INVOICE_PARTS_DESCRIPTION',
            'amount'            => 'INVOICE_PARTS_AMOUNT',
            'qty'               => 'INVOICE_PARTS_COUNT',
            'sub_total'         => 'INVOICE_PARTS_SUBTOTAL'    
            );
        $this->migrate_table($qwcrm_prefix.'invoice_parts', $myitcrm_prefix.'TABLE_INVOICE_PARTS', $column_mappings);        

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
        $this->migrate_table($qwcrm_prefix.'payment_transactions', $myitcrm_prefix.'TABLE_TRANSACTION', $column_mappings);

        // update payment types
        $this->update_column_values($qwcrm_prefix.'payment_transactions', 'method', '1', 'credit_card');
        $this->update_column_values($qwcrm_prefix.'payment_transactions', 'method', '2', 'cheque');
        $this->update_column_values($qwcrm_prefix.'payment_transactions', 'method', '3', 'cash');
        $this->update_column_values($qwcrm_prefix.'payment_transactions', 'method', '4', 'gift_certificate');
        $this->update_column_values($qwcrm_prefix.'payment_transactions', 'method', '5', 'paypal');    

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
        $this->migrate_table($qwcrm_prefix.'refund', $myitcrm_prefix.'TABLE_REFUND', $column_mappings);

        // update refund types
        $this->update_column_values($qwcrm_prefix.'refund', 'type', '1', 'credit_note');
        $this->update_column_values($qwcrm_prefix.'refund', 'type', '2', 'proxy_invoice');
        $this->update_column_values($qwcrm_prefix.'refund', 'type', '3', 'returned_goods');
        $this->update_column_values($qwcrm_prefix.'refund', 'type', '4', 'returned_services');
        $this->update_column_values($qwcrm_prefix.'refund', 'type', '5', 'other');

        // update refund payment methods
        $this->update_column_values($qwcrm_prefix.'refund', 'payment_method', '1', 'bank_transfer');
        $this->update_column_values($qwcrm_prefix.'refund', 'payment_method', '2', 'card');
        $this->update_column_values($qwcrm_prefix.'refund', 'payment_method', '3', 'cash');
        $this->update_column_values($qwcrm_prefix.'refund', 'payment_method', '4', 'cheque');
        $this->update_column_values($qwcrm_prefix.'refund', 'payment_method', '5', 'credit');
        $this->update_column_values($qwcrm_prefix.'refund', 'payment_method', '6', 'direct_debit');
        $this->update_column_values($qwcrm_prefix.'refund', 'payment_method', '7', 'gift_certificate');
        $this->update_column_values($qwcrm_prefix.'refund', 'payment_method', '8', 'google_checkout');
        $this->update_column_values($qwcrm_prefix.'refund', 'payment_method', '9', 'paypal');
        $this->update_column_values($qwcrm_prefix.'refund', 'payment_method', '10', 'voucher');
        $this->update_column_values($qwcrm_prefix.'refund', 'payment_method', '11', 'other');    

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
        $this->migrate_table($qwcrm_prefix.'schedule', $myitcrm_prefix.'TABLE_SCHEDULE', $column_mappings);

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
        $this->migrate_table($qwcrm_prefix.'supplier', $myitcrm_prefix.'TABLE_SUPPLIER', $column_mappings);

        // update supplier types
        $this->update_column_values($qwcrm_prefix.'supplier', 'type', '1', 'affiliate_marketing');
        $this->update_column_values($qwcrm_prefix.'supplier', 'type', '2', 'advertising');
        $this->update_column_values($qwcrm_prefix.'supplier', 'type', '3', 'drop_shipping');
        $this->update_column_values($qwcrm_prefix.'supplier', 'type', '4', 'courier');
        $this->update_column_values($qwcrm_prefix.'supplier', 'type', '5', 'general');
        $this->update_column_values($qwcrm_prefix.'supplier', 'type', '6', 'parts');
        $this->update_column_values($qwcrm_prefix.'supplier', 'type', '7', 'services');
        $this->update_column_values($qwcrm_prefix.'supplier', 'type', '8', 'software');
        $this->update_column_values($qwcrm_prefix.'supplier', 'type', '9', 'wholesale');
        $this->update_column_values($qwcrm_prefix.'supplier', 'type', '10', 'online');
        $this->update_column_values($qwcrm_prefix.'supplier', 'type', '11', 'other');

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
        $this->migrate_table($qwcrm_prefix.'user', $myitcrm_prefix.'TABLE_EMPLOYEE', $column_mappings);

        // Set all users to have create date of now 
        $this->update_column_values($qwcrm_prefix.'user', 'register_date', '*', time());

        // Set all users to employees
        $this->update_column_values($qwcrm_prefix.'user', 'is_employee', '*', '1');

        // Set all users to technicians
        $this->update_column_values($qwcrm_prefix.'user', 'usergroup', '*', '4');

        // Set password reset required for all users
        $this->update_column_values($qwcrm_prefix.'user', 'require_reset', '*', '1');

        // Reset all user passwords (passwords will all be random and unknown)
        $this->reset_all_user_passwords();

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
        $this->migrate_table($qwcrm_prefix.'workorder', $myitcrm_prefix.'TABLE_WORK_ORDER', $column_mappings);

        // workorder_history
        $column_mappings = array(
            'history_id'        => 'WORK_ORDER_STATUS_ID',
            'employee_id'       => 'WORK_ORDER_STATUS_ENTER_BY',
            'workorder_id'      => 'WORK_ORDER_ID',
            'date'              => 'WORK_ORDER_STATUS_DATE',
            'note'              => 'WORK_ORDER_STATUS_NOTES'         
            ); 
        $this->migrate_table($qwcrm_prefix.'workorder_history', $myitcrm_prefix.'TABLE_WORK_ORDER_STATUS', $column_mappings);    

        // workorder_notes
        $column_mappings = array(
            'workorder_note_id' => 'WORK_ORDER_NOTES_ID',
            'employee_id'       => 'WORK_ORDER_NOTES_ENTER_BY',
            'workorder_id'      => 'WORK_ORDER_ID',
            'date'              => 'WORK_ORDER_NOTES_DATE',
            'description'       => 'WORK_ORDER_NOTES_DESCRIPTION'         
            ); 
        $this->migrate_table($qwcrm_prefix.'workorder_notes', $myitcrm_prefix.'TABLE_WORK_ORDER_NOTES', $column_mappings);

        /* Corrections */

        // Workorder
        $this->database_correction_workorder($qwcrm_prefix, $myitcrm_prefix);

        // Invoice
        $this->database_correction_invoice($qwcrm_prefix);

        // Giftcert
        $this->database_correction_giftcert($qwcrm_prefix);

        // Schedule
        $this->database_correction_schedule($qwcrm_prefix, $myitcrm_prefix);

        // User
        $this->database_correction_user($qwcrm_prefix);

        /* Final stuff */

        // Final statement
        if(self::$setup_error_flag) {

            // Setup error flag uses in smarty templates
            $this->app->smarty->assign('setup_error_flag', true);

            // Log message
            $record = _gettext("The database migration process failed, check the logs.");

            // Output message via smarty
            self::$executed_sql_results .= '<div>&nbsp;</div>';
            self::$executed_sql_results .= '<div style="color: red;"><strong>'.$record.'</strong></div>';

            // Log message to setup log        
            $this->write_record_to_setup_log('migrate', $record);

        } else {

            // Log message
            $record = _gettext("The database migration process was successful.");

            // Output message via smarty
            self::$executed_sql_results .= '<div>&nbsp;</div>';
            self::$executed_sql_results .= '<div style="color: green;"><strong>'.$record.'</strong></div>';

            // Log message to setup log        
            $this->write_record_to_setup_log('migrate', $record);

        } 

        // return reflecting the installation status
        if(self::$setup_error_flag) {

            /* Migration Failed */

            // Set setup_error_flag used in smarty templates
            $this->app->smarty->assign('setup_error_flag', true);        

            return false;

        } else {

            /* migration Successful */

            return true;

        }

    }

    /** Database Corrections **/

    ############################################
    #   Correct migrated workorder data        #
    ############################################

    public function database_correction_workorder($qwcrm_prefix, $myitcrm_prefix) {

        $db = \Factory::getDbo();
        
        // Add division to seperate table migration function results
        self::$executed_sql_results .= '<div>&nbsp;</div>';

        // Log message
        $record = _gettext("Starting the correction of the migrated `workorder` data in QWcrm.");       

        // Result message
        self::$executed_sql_results .= '<div><strong><span style="color: green">'.$record.'</span></strong></div>';

        // Log message to setup log                
        $this->write_record_to_setup_log('migrate', $record);

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
            $this->app->system->page->force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to return the matching Work Orders."));

        } else {

            while(!$rs->EOF) {            

                $myitcrm_record = $rs->GetRowAssoc(); 

                /* status and is_closed */

                // WORK_ORDER_STATUS = 6 (closed), WORK_ORDER_CURRENT_STATUS = 6 (closed)
                if($myitcrm_record['my_work_order_status'] == '6' && $myitcrm_record['my_work_order_current_status'] == '6') {                    
                    $this->update_record_value($qwcrm_prefix.'workorder', 'status', 'closed_without_invoice', 'workorder_id', $myitcrm_record['qw_workorder_id']);
                    $this->update_record_value($qwcrm_prefix.'workorder', 'is_closed', '1', 'workorder_id', $myitcrm_record['qw_workorder_id']);
                }

                // WORK_ORDER_STATUS = 6 (closed), WORK_ORDER_CURRENT_STATUS = 8 (payment made)
                elseif($myitcrm_record['my_work_order_status'] == '6' && $myitcrm_record['my_work_order_current_status'] == '8') {                    
                    $this->update_record_value($qwcrm_prefix.'workorder', 'status', 'closed_with_invoice', 'workorder_id', $myitcrm_record['qw_workorder_id']);
                    $this->update_record_value($qwcrm_prefix.'workorder', 'is_closed', '1', 'workorder_id', $myitcrm_record['qw_workorder_id']);
                }

                // WORK_ORDER_STATUS = 9 (pending), WORK_ORDER_CURRENT_STATUS = 7 (awaiting payment)
                elseif($myitcrm_record['my_work_order_status'] == '9' && $myitcrm_record['my_work_order_current_status'] == '7') {                    
                    $this->update_record_value($qwcrm_prefix.'workorder', 'status', 'closed_with_invoice', 'workorder_id', $myitcrm_record['qw_workorder_id']);
                    $this->update_record_value($qwcrm_prefix.'workorder', 'is_closed', '1', 'workorder_id', $myitcrm_record['qw_workorder_id']);
                }

                // WORK_ORDER_STATUS = 10 (open), WORK_ORDER_CURRENT_STATUS = 1 (created)
                elseif($myitcrm_record['my_work_order_status'] == '10' && $myitcrm_record['my_work_order_current_status'] == '1') {                    
                    $this->update_record_value($qwcrm_prefix.'workorder', 'status', 'unassigned', 'workorder_id', $myitcrm_record['qw_workorder_id']);
                    $this->update_record_value($qwcrm_prefix.'workorder', 'is_closed', '0', 'workorder_id', $myitcrm_record['qw_workorder_id']);
                }

                // WORK_ORDER_STATUS = 10 (open), WORK_ORDER_CURRENT_STATUS = 2 (assigned)
                elseif($myitcrm_record['my_work_order_status'] == '10' && $myitcrm_record['my_work_order_current_status'] == '2') {                    
                    $this->update_record_value($qwcrm_prefix.'workorder', 'status', 'assigned', 'workorder_id', $myitcrm_record['qw_workorder_id']);
                    $this->update_record_value($qwcrm_prefix.'workorder', 'is_closed', '0', 'workorder_id', $myitcrm_record['qw_workorder_id']);
                }

                // Uncaught records / default
                else {                    
                    $this->update_record_value($qwcrm_prefix.'workorder', 'status', 'failed_to_migrate', 'workorder_id', $myitcrm_record['qw_workorder_id']);
                    $this->update_record_value($qwcrm_prefix.'workorder', 'is_closed', '0', 'workorder_id', $myitcrm_record['qw_workorder_id']);
                }

                /* invoice_id */

                if($myitcrm_record['my_invoice_id'] != '') {
                    $this->update_record_value($qwcrm_prefix.'workorder', 'invoice_id', $myitcrm_record['my_invoice_id'], 'workorder_id', $myitcrm_record['qw_workorder_id']);                
                }

                // Advance the INSERT loop to the next record
                $rs->MoveNext();           

            }//EOF While loop

        }

        /* Final Stuff */

        // Log message
        $record = _gettext("Finished the correction of the migrated `workorder` data in QWcrm."); 

        // Result message
        self::$executed_sql_results .= '<div><strong><span style="color: green">'.$record.'</span></strong></div>';

        // Add division to seperate table migration function results
        self::$executed_sql_results .= '<div>&nbsp;</div>';

        // Log message to setup log                
        $this->write_record_to_setup_log('migrate', $record);

        return;

    }

    ############################################
    #   Correct migrated invoice data          #
    ############################################

    public function database_correction_invoice($qwcrm_prefix) {

        $db = \Factory::getDbo();        

        // Add division to seperate table migration function results
        self::$executed_sql_results .= '<div>&nbsp;</div>';

        // Log message
        $record = _gettext("Starting the correction of the migrated `invoice` data in QWcrm.");       

        // Result message
        self::$executed_sql_results .= '<div><strong><span style="color: green">'.$record.'</span></strong></div>';

        // Log message to setup log                
        $this->write_record_to_setup_log('migrate', $record);

        $sql =  "SELECT * FROM ".$qwcrm_prefix."invoice";                       

        /* Processs the records */

        if(!$rs = $db->Execute($sql)) {
            $this->app->system->page->force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to return the matching Invoices."));

        } else {

            while(!$rs->EOF) {            

                $qwcrm_record = $rs->GetRowAssoc();

                /* net_amount */
                $net_amount = $qwcrm_record['sub_total'] - $qwcrm_record['discount_amount'];
                $this->update_record_value($qwcrm_prefix.'invoice', 'net_amount', $net_amount, 'invoice_id', $qwcrm_record['invoice_id']);            

                /* status and is_closed*/

                // no amount on invoice
                if($qwcrm_record['gross_amount'] == '0') {                    
                    $this->update_record_value($qwcrm_prefix.'invoice', 'status', 'pending', 'invoice_id', $qwcrm_record['invoice_id']);
                    $this->update_record_value($qwcrm_prefix.'invoice', 'is_closed', '0', 'invoice_id', $qwcrm_record['invoice_id']); 
                }

                // if unpaid
                elseif($qwcrm_record['paid_amount'] == '0') {                    
                    $this->update_record_value($qwcrm_prefix.'invoice', 'status', 'unpaid', 'invoice_id', $qwcrm_record['invoice_id']);
                    $this->update_record_value($qwcrm_prefix.'invoice', 'is_closed', '0', 'invoice_id', $qwcrm_record['invoice_id']);
                }

                // if there are partial payments
                elseif($qwcrm_record['paid_amount'] < $qwcrm_record['gross_amount'] && $qwcrm_record['paid_amount'] != '0') {                    
                    $this->update_record_value($qwcrm_prefix.'invoice', 'status', 'partially_paid', 'invoice_id', $qwcrm_record['invoice_id']);
                    $this->update_record_value($qwcrm_prefix.'invoice', 'is_closed', '0', 'invoice_id', $qwcrm_record['invoice_id']);
                }

                // if fully paid
                elseif($qwcrm_record['paid_amount'] == $qwcrm_record['gross_amount']) {                    
                    $this->update_record_value($qwcrm_prefix.'invoice', 'status', 'paid', 'invoice_id', $qwcrm_record['invoice_id']);
                    $this->update_record_value($qwcrm_prefix.'invoice', 'is_closed', '1', 'invoice_id', $qwcrm_record['invoice_id']);
                }            

                // Uncaught records / default
                else {                    
                    $this->update_record_value($qwcrm_prefix.'invoice', 'status', 'failed_to_migrate', 'invoice_id', $qwcrm_record['invoice_id']);
                    $this->update_record_value($qwcrm_prefix.'invoice', 'is_closed', '0', 'invoice_id', $qwcrm_record['invoice_id']);
                }

                // Advance the INSERT loop to the next record
                $rs->MoveNext();           

            }//EOF While loop

        }

        /* Final Stuff */

        // Log message
        $record = _gettext("Finished the correction of the migrated `invoice` data in QWcrm."); 

        // Result message
        self::$executed_sql_results .= '<div><strong><span style="color: green">'.$record.'</span></strong></div>';

        // Add division to seperate table migration function results
        self::$executed_sql_results .= '<div>&nbsp;</div>';

        // Log message to setup log                
        $this->write_record_to_setup_log('migrate', $record);

        return;

    }

    ############################################
    #   Correct migrated giftcert data         #
    ############################################

    public function database_correction_giftcert($qwcrm_prefix) {

        $db = \Factory::getDbo();        

        // Add division to seperate table migration function results
        self::$executed_sql_results .= '<div>&nbsp;</div>';

        // Log message
        $record = _gettext("Starting the correction of the migrated `giftcert` data in QWcrm.");       

        // Result message
        self::$executed_sql_results .= '<div><strong><span style="color: green">'.$record.'</span></strong></div>';

        // Log message to setup log                
        $this->write_record_to_setup_log('migrate', $record);

        $sql =  "SELECT * FROM ".$qwcrm_prefix."giftcert";                       

        /* Processs the records */

        if(!$rs = $db->Execute($sql)) {
            $this->app->system->page->force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to return the matching Gift Certificates."));

        } else {

            while(!$rs->EOF) {            

                $qwcrm_record = $rs->GetRowAssoc();

                /* is_redeemed */

                // no amount on invoice
                if($qwcrm_record['date_redeemed'] == '') {                    
                    $this->update_record_value($qwcrm_prefix.'giftcert', 'is_redeemed', '0', 'giftcert_id', $qwcrm_record['giftcert_id']);                               
                } else {
                    $this->update_record_value($qwcrm_prefix.'giftcert', 'is_redeemed', '1', 'giftcert_id', $qwcrm_record['giftcert_id']);
                }

                // Advance the INSERT loop to the next record
                $rs->MoveNext();           

            }//EOF While loop

        }

        /* Final Stuff */

        // Log message
        $record = _gettext("Finished the correction of the migrated `giftcert` data in QWcrm."); 

        // Result message
        self::$executed_sql_results .= '<div><strong><span style="color: green">'.$record.'</span></strong></div>';

        // Add division to seperate table migration function results
        self::$executed_sql_results .= '<div>&nbsp;</div>';

        // Log message to setup log                
        $this->write_record_to_setup_log('migrate', $record);

        return;

    }

    ############################################
    #   Correct migrated schedule data         #
    ############################################

    public function database_correction_schedule($qwcrm_prefix, $myitcrm_prefix) {

        $db = \Factory::getDbo();        

        // Add division to seperate table migration function results
        self::$executed_sql_results .= '<div>&nbsp;</div>';

        // Log message
        $record = _gettext("Starting the correction of the migrated `schedule` data in QWcrm.");       

        // Result message
        self::$executed_sql_results .= '<div><strong><span style="color: green">'.$record.'</span></strong></div>';

        // Log message to setup log                
        $this->write_record_to_setup_log('migrate', $record);

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
            $this->app->system->page->force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to return the matching Schedules."));

        } else {

            while(!$rs->EOF) {            

                $myitcrm_record = $rs->GetRowAssoc(); 

                /* customer_id */
                $this->update_record_value($qwcrm_prefix.'schedule', 'customer_id', $myitcrm_record['my_customer_id'], 'schedule_id', $myitcrm_record['qw_schedule_id']);

                // Advance the INSERT loop to the next record
                $rs->MoveNext();           

            }//EOF While loop

        }

        /* Final Stuff */

        // Log message
        $record = _gettext("Finished the correction of the migrated `schedule` data in QWcrm."); 

        // Result message
        self::$executed_sql_results .= '<div><strong><span style="color: green">'.$record.'</span></strong></div>';

        // Add division to seperate table migration function results
        self::$executed_sql_results .= '<div>&nbsp;</div>';

        // Log message to setup log                
        $this->write_record_to_setup_log('migrate', $record);

        return;

    }

    ############################################
    #   Correct migrated user data             #
    ############################################

    public function database_correction_user($qwcrm_prefix) {

        $db = \Factory::getDbo();        

        // Add division to seperate table migration function results
        self::$executed_sql_results .= '<div>&nbsp;</div>';

        // Log message
        $record = _gettext("Starting the correction of the migrated `user` data in QWcrm.");       

        // Result message
        self::$executed_sql_results .= '<div><strong><span style="color: green">'.$record.'</span></strong></div>';

        // Log message to setup log                
        $this->write_record_to_setup_log('migrate', $record);

        $sql = "SELECT * FROM ".$qwcrm_prefix."user";

        /* Processs the records */

        if(!$rs = $db->Execute($sql)) {
            $this->app->system->page->force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to return the matching Users."));

        } else {

            while(!$rs->EOF) {            

                $qwcrm_record = $rs->GetRowAssoc(); 

                // Sanitise user's usernames - remove all spaces
                $this->update_record_value($qwcrm_prefix.'user', 'username', str_replace(' ', '.', $qwcrm_record['username']), 'user_id', $qwcrm_record['user_id']);            

                // Advance the INSERT loop to the next record
                $rs->MoveNext();           

            }//EOF While loop

        }

        /* Final Stuff */

        // Log message
        $record = _gettext("Finished the correction of the migrated `user` data in QWcrm."); 

        // Result message
        self::$executed_sql_results .= '<div><strong><span style="color: green">'.$record.'</span></strong></div>';

        // Add division to seperate table migration function results
        self::$executed_sql_results .= '<div>&nbsp;</div>';

        // Log message to setup log                
        $this->write_record_to_setup_log('migrate', $record);

        return;

    }
    

    /** Other Functions **/
    
    #########################################################
    #   check myitcrm database is accessible and is 2.9.3   #
    #########################################################

    public function check_myitcrm_database_connection($myitcrm_prefix) {

        $db = \Factory::getDbo();

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
    
    #####################################
    #    Reset all user's passwords     #   // database structure is different in 3.0.1
    #####################################

    public function reset_all_user_passwords() { 

        $db = \Factory::getDbo();

        $sql = "SELECT user_id FROM ".PRFX."user";

        if(!$rs = $db->Execute($sql)) {
            $this->app->system->page->force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to read all users from the database."));

        } else {

            // Loop through all users
            while(!$rs->EOF) { 

                // Reset User's password
                $this->reset_user_password($rs->fields['user_id']);

                // Advance the INSERT loop to the next record            
                $rs->MoveNext();            

            }

            // Log activity        
            $this->write_record_to_setup_log('migrate', _gettext("All User Account passwords have been reset."));            

            return;

        }      

    }

    #####################################
    #    Reset a user's password        #    
    #####################################

    public function reset_user_password($user_id, $password = null) { 

        $db = \Factory::getDbo();

        // if no password supplied generate a random one
        if($password == null) { $password = \Joomla\CMS\User\UserHelper::genRandomPassword(16); }

        $sql = "UPDATE ".PRFX."user SET
                password        =". $db->qstr( \Joomla\CMS\User\UserHelper::hashPassword($password) ).",
                require_reset   =". $db->qstr( 0                                    ).",   
                last_reset_time =". $db->qstr( time()                               ).",
                reset_count     =". $db->qstr( 0                                    )."
                WHERE user_id   =". $db->qstr( $user_id                             );

        if(!$rs = $db->Execute($sql)) {
            $this->app->system->page->force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to add password reset authorization."));

        } else {

            // Log activity 
            // n/a

            return;

        }      

    }

    #################################################
    #    Check if username already exists           #
    #################################################

    public function check_user_username_exists($username, $current_username = null) {

        $db = \Factory::getDbo();

        // This prevents self-checking of the current username of the record being edited
        if ($current_username != null && $username === $current_username) {return false;}

        $sql = "SELECT username FROM ".PRFX."user WHERE username =". $db->qstr($username);

        if(!$rs = $db->Execute($sql)) {
            $this->app->system->page->force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to check if the username exists."));
        } else {

            $result_count = $rs->RecordCount();

            if($result_count >= 1) {

                $this->app->smarty->assign('msg_danger', _gettext("The Username")." `".$username."` "._gettext("already exists! Please use a different one."));

                return true;

            } else {

                return false;

            }        

        } 

    } 
    
    ######################################################
    #  Check if an email address has already been used   #
    ######################################################

    public function check_user_email_exists($email, $current_email = null) {

        $db = \Factory::getDbo();

        // This prevents self-checking of the current username of the record being edited
        if ($current_email != null && $email === $current_email) {return false;}

        $sql = "SELECT email FROM ".PRFX."user WHERE email =". $db->qstr($email);

        if(!$rs = $db->Execute($sql)) {

            $this->app->system->page->force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to check if the email address has been used."));

        } else {

            $result_count = $rs->RecordCount();

            if($result_count >= 1) {

                $this->app->smarty->assign('msg_danger', _gettext("The email address has already been used. Please use a different one."));

                return true;

            } else {

                return false;

            }        

        } 

    }

    ##########################
    #  Delete Company Logo   #
    ##########################

    public function delete_logo() {

        // Only delete a logo if there is one set
        if($this->get_company_details('logo')) {

            // Build the full logo file path
            $logo_file = parse_url(MEDIA_DIR . $this->get_company_details('logo'), PHP_URL_PATH);

            // Perform the deletion
            unlink($logo_file);

        }

    }

    ##########################
    #  Upload Company Logo   #
    ##########################

    public function upload_logo() {

        $db = \Factory::getDbo();

        // Logo - Only process if there is an image uploaded
        if($_FILES['logo']['size'] > 0) {

            // Allowed extensions
            $allowedExts = array('png', 'jpg', 'jpeg', 'gif');

            // Get file extension
            $filename_info = pathinfo($_FILES['logo']['name']);
            $extension = $filename_info['extension'];

            // Rename Logo Filename to logo.xxx (keeps original image extension)
            $new_logo_filename = 'logo.' . $extension;       

            // Validate the uploaded file is allowed (extension, mime type, 0 - 2mb)
            if ((($_FILES['logo']['type'] == 'image/gif')
                    || ($_FILES['logo']['type'] == 'image/jpeg')
                    || ($_FILES['logo']['type'] == 'image/jpg')
                    || ($_FILES['logo']['type'] == 'image/pjpeg')
                    || ($_FILES['logo']['type'] == 'image/x-png')
                    || ($_FILES['logo']['type'] == 'image/png'))
                    && ($_FILES['logo']['size'] < 2048000)
                    && in_array($extension, $allowedExts)) {

                // Check for file submission errors and echo them
                if ($_FILES['logo']['error'] > 0 ) {
                    echo _gettext("Return Code").': ' . $_FILES['logo']['error'] . '<br />';                

                // If no errors then move the file from the PHP temporary storage to the logo location
                } else {
                    move_uploaded_file($_FILES['logo']['tmp_name'], MEDIA_DIR . $new_logo_filename);              
                }

                // return the filename with a random query to allow for caching issues
                return $new_logo_filename . '?' . strtolower(\Joomla\CMS\User\UserHelper::genRandomPassword(3));

            // If file is invalid then load the error page  
            } else {

                /*
                echo "Upload: "    . $_FILES['company_logo']['name']           . '<br />';
                echo "Type: "      . $_FILES['company_logo']['type']           . '<br />';
                echo "Size: "      . ($_FILES['company_logo']['size'] / 1024)  . ' Kb<br />';
                echo "Temp file: " . $_FILES['company_logo']['tmp_name']       . '<br />';
                echo "Stored in: " . MEDIA_DIR . $_FILES['file']['name']       ;
                 */   

                $this->app->system->page->force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), '', _gettext("Failed to update logo because the submitted file was invalid."));

            }

        }

    }   

}

