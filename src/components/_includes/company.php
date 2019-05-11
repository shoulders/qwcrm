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

##########################################
#      get company details               #
##########################################

// This is in the main include.php file

#####################################
#    Get company tax systems        #
#####################################

function get_tax_systems() {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT * FROM ".PRFX."company_tax_systems";

    if(!$rs = $db->execute($sql)){        
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get tax types."));
    } else {
        
        return $rs->GetArray();
        
    }    
    
}

#####################################
#   Get VAT rate for given tax_key  #
#####################################

function get_vat_rate($vat_tax_code) {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT rate FROM ".PRFX."company_vat_tax_codes
            WHERE tax_key = ".$db->qstr($vat_tax_code);
    
    if(!$rs = $db->execute($sql)){        
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get VAT rate."));
    } else {
        
        return $rs->fields['rate'];
        
    }    
    
}

#####################################
#    Get VAT Tax Codes              # Editable is only used in company:edit
##################################### system_tax_code is not currently used and might be removed

function get_vat_tax_codes($hidden_status = null, $editable_status = null, $system_tax_code = null) {
    
    $db = QFactory::getDbo();
    
    $sql = "SELECT * FROM ".PRFX."company_vat_tax_codes";
    
    // Restrict by enabled status
    $sql .= "\nWHERE enabled = 1";
        
    // Restrict by hidden status
    if(!is_null($hidden_status)) {
        $sql .= "\nAND hidden = ".$db->qstr($hidden_status);
    }
    
    // Restrict by editable status
    if(!is_null($editable_status)) {
        $sql .= "\nAND editable = ".$db->qstr($editable_status);
    }
    
    // Restrict by tax code type
    if(!is_null($system_tax_code)) {
        $sql .= "\nAND standard = ".$db->qstr($system_tax_code);
    }
        
    if(!$rs = $db->execute($sql)){        
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get VAT Taxx Codes."));
    } else {
        
        return $rs->GetArray();
        
    }    
    
}

#####################################
#    Get default VAT Code           # // This gets the default VAT Tax Code based on the company tax system or supplied tax_system
#####################################

function get_default_vat_tax_code($tax_system = null) {
    
    if(!$tax_system) {$tax_system = QW_TAX_SYSTEM;}
    
    if($tax_system == 'none') { return 'TNA'; }
    if($tax_system == 'vat_standard') { return 'T1'; }
    if($tax_system == 'vat_flat') { return 'T1'; }
    if($tax_system == 'vat_cash') { return 'T1'; }
    if($tax_system == 'sales_tax') { return 'TNA'; }    
    
}

#####################################  // This gets the Voucher VAT Tax Code based on the company tax system or supplied tax_system
#    Get Voucher default VAT Code   #  // not currently using '$tax_system = null'
#####################################  // move to vouchers?

function get_voucher_vat_tax_code($type, $tax_system = null) {
    
    if(!$tax_system) {$tax_system = QW_TAX_SYSTEM;}
    
    if($type == 'multi_purpose') {
        if($tax_system == 'none') { return 'TNA'; }
        if($tax_system == 'vat_standard') { return 'T0'; }
        if($tax_system == 'vat_flat') { return 'T0'; }
        if($tax_system == 'vat_cash') { return 'T0'; }
        if($tax_system == 'sales_tax') { return 'TNA'; } 
    }
    
    if($type == 'single_purpose') {
        if($tax_system == 'none') { return 'TNA'; }
        if($tax_system == 'vat_standard') { return 'T1'; }
        if($tax_system == 'vat_flat') { return 'T1'; }
        if($tax_system == 'vat_cash') { return 'T1'; }
        if($tax_system == 'sales_tax') { return 'TNA'; } 
    }    
    
}

##########################################
#      Get Company Opening Hours         # // smarty/datetime/timestamp
##########################################

function get_company_opening_hours($event, $type, $date = null) {
    
    // Convert Date to time stamp
    if($date) { 
        $date_timestamp = strtotime($date);        
    }

    // Smarty Time Format
    if($type == 'smartytime') {
        
        // return opening time in correct format for smartytime builder
        if($event == 'opening_time') {
            return get_company_details('opening_hour').':'.get_company_details('opening_minute').':00';
        }

        // return closing time in correct format for smartytime builder
        if($event == 'closing_time') {
            return get_company_details('closing_hour').':'.get_company_details('closing_minute').':00';
        }   
        
    }
    
    // MySQL DATETIME format
    if($type == 'datetime') {

        // return opening time in correct format for smarty time builder
        if($event == 'opening_time') {            
            //return $date.' '.get_company_details('opening_hour').':'.get_company_details('opening_minute');  // This only allows the use of DATE and not DATETIME            
            return build_mysql_datetime(get_company_details('opening_hour'), get_company_details('opening_minute'), 0, date('m', $date_timestamp), date('d', $date_timestamp), date('Y', $date_timestamp));            
        }

        // return closing time in correct format for smarty time builder
        if($event == 'closing_time') {
            //return $date.' '.get_company_details('closing_hour').':'.get_company_details('closing_minute');  // This only allows the use of DATE and not DATETIME
            
            return build_mysql_datetime(get_company_details('closing_hour'), get_company_details('closing_minute'), 0, date('m', $date_timestamp), date('d', $date_timestamp), date('Y', $date_timestamp));                    
        }
    }
    
    // Unix Timestamp
    if($type == 'timestamp') {

        // return opening time in correct format for smarty time builder
        if($event == 'opening_time') {
            return mktime(get_company_details('opening_hour'), get_company_details('opening_minute'), 0, date('m', $date_timestamp), date('d', $date_timestamp), date('Y', $date_timestamp));
        }

        // return closing time in correct format for smarty time builder
        if($event == 'closing_time') {
            return mktime(get_company_details('closing_hour'), get_company_details('closing_minute'), 0, date('m', $date_timestamp), date('d', $date_timestamp), date('Y', $date_timestamp));
        }   
        
    }    
    
}

/** Update Functions **/

#############################
#  Update Company details   #
#############################

function update_company_details($VAR) {

    $db = QFactory::getDbo();
    $smarty = QFactory::getSmarty();    
    $sql = null;
    
    // Update VAT rates
    update_vat_rates($VAR['vat_tax_codes']);
    
    // Prevent undefined variable errors
    $VAR['delete_logo'] = isset($VAR['delete_logo']) ? $VAR['delete_logo'] : null;    
           
    // Delete logo if selected and no new logo is presented
    if($VAR['delete_logo'] && !$_FILES['logo']['name']) {
        delete_logo();        
    }
    
    // A new logo is supplied, delete old and upload new
    if($_FILES['logo']['name']) {
        delete_logo();
        $new_logo_filepath = upload_logo();
    }
    
    $sql .= "UPDATE ".PRFX."company_record SET
            company_name            =". $db->qstr( $VAR['company_name']                     ).",";
    
    if($VAR['delete_logo']) {
        $sql .="logo                =''                                                     ,";
    }
                
    if(!empty($_FILES['logo']['name'])) {
        $sql .="logo                =". $db->qstr( $new_logo_filepath                       ).",";
    }
    
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
            tax_system              =". $db->qstr( $VAR['tax_system']                       ).",
            sales_tax_rate          =". $db->qstr( $VAR['sales_tax_rate']                   ).",
            vat_number              =". $db->qstr( $VAR['vat_number']                       ).",
            year_start              =". $db->qstr( date_to_mysql_date($VAR['year_start'])   ).",
            year_end                =". $db->qstr( date_to_mysql_date($VAR['year_end'])     ).",
            welcome_msg             =". $db->qstr( $VAR['welcome_msg']                      ).",
            currency_symbol         =". $db->qstr( htmlentities($VAR['currency_symbol'])    ).",
            currency_code           =". $db->qstr( $VAR['currency_code']                    ).",
            date_format             =". $db->qstr( $VAR['date_format']                      ).",            
            email_signature         =". $db->qstr( $VAR['email_signature']                  ).",
            email_signature_active  =". $db->qstr( $VAR['email_signature_active']           ).",
            email_msg_invoice       =". $db->qstr( $VAR['email_msg_invoice']                ).",
            email_msg_workorder     =". $db->qstr( $VAR['email_msg_workorder']              );                          

    
    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to update the company details."));
    } else {       
            
        // Refresh company logo
        //$smarty->assign('company_logo', QW_MEDIA_DIR . get_company_details('logo'));
        
        // Assign success message
        $smarty->assign('information_msg', _gettext("Company details updated."));
        
        // Log activity        
        write_record_to_activity_log(_gettext("Company details updated."));

        return;
        
    }
    
}

##########################################
#        Update Company Hours            #
##########################################

function update_company_hours($openingTime, $closingTime) {
    
    $db = QFactory::getDbo();
    $smarty = QFactory::getSmarty();
    
    $sql = "UPDATE ".PRFX."company_record SET
            opening_hour    =". $db->qstr( $openingTime['Time_Hour']     ).",
            opening_minute  =". $db->qstr( $openingTime['Time_Minute']   ).",
            closing_hour    =". $db->qstr( $closingTime['Time_Hour']     ).",
            closing_minute  =". $db->qstr( $closingTime['Time_Minute']   );

    if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to update the company hours."));
    } else {
        
        // Assign success message
        $smarty->assign('information_msg', _gettext("Business hours have been updated."));
        
        // Log activity        
        write_record_to_activity_log(_gettext("Business hours have been updated."));        
        
        return true;
        
    }
    
}

##########################################
#        Update VAT Rates                #
##########################################

function update_vat_rates($vat_rates) {
    
    $db = QFactory::getDbo();
    //$smarty = QFactory::getSmarty();
    $error_flag = false;
    
    // Cycle through the submitted VAT rates and update the database
    foreach ($vat_rates as $tax_key => $rate) {
        $sql =  "UPDATE ".PRFX."company_vat_tax_codes SET
                rate = ".$db->qstr($rate)."
                WHERE tax_key = ".$db->qstr($tax_key);
        
        if(!$rs = $db->Execute($sql)) {
            $error_flag = true;            
        }        
    }
    
    if($error_flag) {
        
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to update the VAT rates."));
        //return false;      
        
    } else {
        // Assign success message
        //$smarty->assign('information_msg', _gettext("VAT rates have been updated."));
        
        // Log activity        
        //write_record_to_activity_log(_gettext("VAT rates have been updated."));        
        
        return true;
        
    }
    
}

/** Close Functions **/

/** Delete Functions **/

/** Other Functions **/

##########################################
#  Check Start and End times are valid   #
##########################################

function check_start_end_times($start_time, $end_time) {
    
    $smarty = QFactory::getSmarty(); 
    
    // If start time is before end time
    if($start_time > $end_time) {        
        $smarty->assign('warning_msg', _gettext("Start Time is after End Time."));
        return false;
    }
        
    // If the start and end time are the same    
    if($start_time ==  $end_time) {        
        $smarty->assign('warning_msg', _gettext("Start Time is the same as End Time."));
        return false;
    }
    
    return true;
    
}

##########################
#  Delete Company Logo   #
##########################

function delete_logo() {
    
    // Only delete a logo if there is one set
    if(get_company_details('logo')) {
        
        // Build the full logo file path
        $logo_file = parse_url(MEDIA_DIR . get_company_details('logo'), PHP_URL_PATH);
        
        // Perform the deletion
        unlink($logo_file);
        
    }
    
}

##########################
#  Upload Company Logo   #
##########################

function upload_logo() {
    
    $db = QFactory::getDbo();
    
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
            return $new_logo_filename . '?' . strtolower(JUserHelper::genRandomPassword(3));
            
        // If file is invalid then load the error page  
        } else {
            
            /*
            echo "Upload: "    . $_FILES['company_logo']['name']           . '<br />';
            echo "Type: "      . $_FILES['company_logo']['type']           . '<br />';
            echo "Size: "      . ($_FILES['company_logo']['size'] / 1024)  . ' Kb<br />';
            echo "Temp file: " . $_FILES['company_logo']['tmp_name']       . '<br />';
            echo "Stored in: " . MEDIA_DIR . $_FILES['file']['name']       ;
             */   
            
            force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to update logo because the submitted file was invalid."));
            
        }
        
    }
    
}

