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

class Company extends Components {

    /** Mandatory Code **/

    /** Display Functions **/

    /** Insert Functions **/

    /** Get Functions **/

    ##########################################
    #      get company details               #
    ##########################################

    // This is in the main general.php file

    #####################################
    #    Get company tax systems        #
    #####################################

    public function get_tax_systems() {

        $sql = "SELECT * FROM ".PRFX."company_tax_systems";

        if(!$rs = $this->db->execute($sql)){        
            $this->app->system->general->force_error_page('database', __FILE__, __FUNCTION__, $this->db->ErrorMsg(), $sql, _gettext("Failed to get tax types."));
        } else {

            return $rs->GetArray();

        }    

    }

    #####################################
    #   Get VAT rate for given tax_key  #
    #####################################

    public function get_vat_rate($vat_tax_code) {

        $sql = "SELECT rate FROM ".PRFX."company_vat_tax_codes
                WHERE tax_key = ".$this->db->qstr($vat_tax_code);

        if(!$rs = $this->db->execute($sql)){        
            $this->app->system->general->force_error_page('database', __FILE__, __FUNCTION__, $this->db->ErrorMsg(), $sql, _gettext("Failed to get VAT rate."));
        } else {

            return $rs->fields['rate'];

        }    

    }

    #####################################
    #    Get VAT Tax Codes              # Editable is only used in company:edit
    ##################################### system_tax_code is not currently used and might be removed

    public function get_vat_tax_codes($hidden_status = null, $editable_status = null, $system_tax_code = null) {

        $sql = "SELECT * FROM ".PRFX."company_vat_tax_codes";

        // Restrict by enabled status
        $sql .= "\nWHERE enabled = 1";

        // Restrict by hidden status
        if(!is_null($hidden_status)) {
            $sql .= "\nAND hidden = ".$this->db->qstr($hidden_status);
        }

        // Restrict by editable status
        if(!is_null($editable_status)) {
            $sql .= "\nAND editable = ".$this->db->qstr($editable_status);
        }

        // Restrict by tax code type
        if(!is_null($system_tax_code)) {
            $sql .= "\nAND standard = ".$this->db->qstr($system_tax_code);
        }

        if(!$rs = $this->db->execute($sql)){        
            $this->app->system->general->force_error_page('database', __FILE__, __FUNCTION__, $this->db->ErrorMsg(), $sql, _gettext("Failed to get VAT Taxx Codes."));
        } else {

            return $rs->GetArray();

        }    

    }

    ############################
    #  Get VAT Code Status     #  // Only return true is a valid configuration for the code
    ############################

    public function get_vat_tax_code_status($vat_tax_code) {

        $sql = "SELECT enabled
                FROM ".PRFX."company_vat_tax_codes
                WHERE tax_key = ".$this->db->qstr($vat_tax_code);

        if(!$rs = $this->db->execute($sql)){        
            $this->app->system->general->force_error_page('database', __FILE__, __FUNCTION__, $this->db->ErrorMsg(), $sql, _gettext("Failed to get VAT Tax Code status."));
        } else {       

            return $rs->fields['enabled'];

        }   

    }

    #####################################
    #    Get default VAT Code           # // This gets the default VAT Tax Code based on the company tax system or supplied tax_system
    #####################################

    public function get_default_vat_tax_code($tax_system = null) {

        if(!$tax_system) {$tax_system = QW_TAX_SYSTEM;}

        if($tax_system == 'no_tax') { return 'TNA'; }
        if($tax_system == 'sales_tax_cash') { return 'TNA'; }     
        if($tax_system == 'vat_standard') { return 'T1'; }    
        if($tax_system == 'vat_cash') { return 'T1'; }
        if($tax_system == 'vat_flat_basic') { return 'T1'; }
        if($tax_system == 'vat_flat_cash') { return 'T1'; }       

    }

    ##########################################
    #      Get Company Opening Hours         # // return opening hours in smarty/datetime/timestamp with an optional specified date (2019-05-72)
    ##########################################

    public function get_company_opening_hours($event, $type, $date = null, $date_format = null) {

        // Convert Date to time stamp
        if($date) { 
            $date_timestamp = $this->app->components->general->date_to_timestamp($date, $date_format);
        }

        // Smarty Time Format
        if($type == 'smartytime') {

            // return opening time in correct format for smartytime builder
            if($event == 'opening_time') {
                return $this->get_company_details('opening_hour').':'.$this->get_company_details('opening_minute').':00';
            }

            // return closing time in correct format for smartytime builder
            if($event == 'closing_time') {
                return $this->get_company_details('closing_hour').':'.$this->get_company_details('closing_minute').':00';
            }   

        }

        // MySQL DATETIME format
        if($type == 'datetime') {

            // return opening time in correct format for smarty time builder
            if($event == 'opening_time') {            
                //return $date.' '.$this->get_company_details('opening_hour').':'.$this->get_company_details('opening_minute');  // This only allows the use of DATE and not DATETIME            
                return $this->app->system->general->build_mysql_datetime($this->get_company_details('opening_hour'), $this->get_company_details('opening_minute'), 0, date('m', $date_timestamp), date('d', $date_timestamp), date('Y', $date_timestamp));            
            }

            // return closing time in correct format for smarty time builder
            if($event == 'closing_time') {
                //return $date.' '.$this->get_company_details('closing_hour').':'.$this->get_company_details('closing_minute');  // This only allows the use of DATE and not DATETIME

                return $this->app->system->general->build_mysql_datetime($this->get_company_details('closing_hour'), $this->get_company_details('closing_minute'), 0, date('m', $date_timestamp), date('d', $date_timestamp), date('Y', $date_timestamp));                    
            }
        }

        // Unix Timestamp
        if($type == 'timestamp') {

            // return opening time in correct format for smarty time builder
            if($event == 'opening_time') {
                return mktime($this->get_company_details('opening_hour'), $this->get_company_details('opening_minute'), 0, date('m', $date_timestamp), date('d', $date_timestamp), date('Y', $date_timestamp));
            }

            // return closing time in correct format for smarty time builder
            if($event == 'closing_time') {
                return mktime($this->get_company_details('closing_hour'), $this->get_company_details('closing_minute'), 0, date('m', $date_timestamp), date('d', $date_timestamp), date('Y', $date_timestamp));
            }   

        }    

    }

    /** Update Functions **/

    #############################
    #  Update Company details   #
    #############################

    public function update_company_details($qform) {
            
        $sql = null;

        // Update VAT rates
        $this->update_vat_rates($qform['vat_tax_codes']);

        // Prevent undefined variable errors
        $qform['delete_logo'] = isset($qform['delete_logo']) ? $qform['delete_logo'] : null;    

        // Delete logo if selected and no new logo is presented
        if($qform['delete_logo'] && !$_FILES['logo']['name']) {
            $this->delete_logo();        
        }

        // A new logo is supplied, delete old and upload new
        if($_FILES['logo']['name']) {
            $this->delete_logo();
            $new_logo_filepath = $this->upload_logo();
        }

        $sql .= "UPDATE ".PRFX."company_record SET
                company_name            =". $this->db->qstr( $qform['company_name']                     ).",";

        if($qform['delete_logo']) {
            $sql .="logo                =''                                                     ,";
        }

        if(!empty($_FILES['logo']['name'])) {
            $sql .="logo                =". $this->db->qstr( $new_logo_filepath                       ).",";
        }

        $sql .="address                 =". $this->db->qstr( $qform['address']                          ).",
                city                    =". $this->db->qstr( $qform['city']                             ).",
                state                   =". $this->db->qstr( $qform['state']                            ).",
                zip                     =". $this->db->qstr( $qform['zip']                              ).",
                country                 =". $this->db->qstr( $qform['country']                          ).",
                primary_phone           =". $this->db->qstr( $qform['primary_phone']                    ).",
                mobile_phone            =". $this->db->qstr( $qform['mobile_phone']                     ).",
                fax                     =". $this->db->qstr( $qform['fax']                              ).",
                email                   =". $this->db->qstr( $qform['email']                            ).",    
                website                 =". $this->db->qstr( $this->app->components->general->process_inputted_url($qform['website'])    ).",
                company_number          =". $this->db->qstr( $qform['company_number']                   ).",                                        
                tax_system              =". $this->db->qstr( $qform['tax_system']                       ).",
                sales_tax_rate          =". $this->db->qstr( $qform['sales_tax_rate']                   ).",
                vat_number              =". $this->db->qstr( $qform['vat_number']                       ).",
                vat_flat_rate           =". $this->db->qstr( $qform['vat_flat_rate']                    ).",   
                year_start              =". $this->db->qstr( $this->app->system->general->date_to_mysql_date($qform['year_start'])   ).",
                year_end                =". $this->db->qstr( $this->app->system->general->date_to_mysql_date($qform['year_end'])     ).",
                welcome_msg             =". $this->db->qstr( $qform['welcome_msg']                      ).",
                currency_symbol         =". $this->db->qstr( htmlentities($qform['currency_symbol'])    ).",
                currency_code           =". $this->db->qstr( $qform['currency_code']                    ).",
                date_format             =". $this->db->qstr( $qform['date_format']                      ).",            
                email_signature         =". $this->db->qstr( $qform['email_signature']                  ).",
                email_signature_active  =". $this->db->qstr( $qform['email_signature_active']           ).",
                email_msg_invoice       =". $this->db->qstr( $qform['email_msg_invoice']                ).",
                email_msg_workorder     =". $this->db->qstr( $qform['email_msg_workorder']              );                          


        if(!$rs = $this->db->Execute($sql)) {
            $this->app->system->general->force_error_page('database', __FILE__, __FUNCTION__, $this->db->ErrorMsg(), $sql, _gettext("Failed to update the company details."));
        } else {       

            // Refresh company logo
            //$this->smarty->assign('company_logo', QW_MEDIA_DIR . $this->get_company_details('logo'));

            // Assign success message
            $this->app->system->variables->systemMessagesWrite('success', _gettext("Company details updated."));

            // Log activity        
            $this->app->system->general->write_record_to_activity_log(_gettext("Company details updated."));

            return;

        }

    }

    ##########################################
    #        Update Company Hours            #
    ##########################################

    public function update_company_hours($openingTime, $closingTime) {

        $sql = "UPDATE ".PRFX."company_record SET
                opening_hour    =". $this->db->qstr( $openingTime['Time_Hour']     ).",
                opening_minute  =". $this->db->qstr( $openingTime['Time_Minute']   ).",
                closing_hour    =". $this->db->qstr( $closingTime['Time_Hour']     ).",
                closing_minute  =". $this->db->qstr( $closingTime['Time_Minute']   );

        if(!$rs = $this->db->Execute($sql)) {
            $this->app->system->general->force_error_page('database', __FILE__, __FUNCTION__, $this->db->ErrorMsg(), $sql, _gettext("Failed to update the company hours."));
        } else {

            // Assign success message
            $this->app->system->variables->systemMessagesWrite('success', _gettext("Business hours have been updated."));

            // Log activity        
            $this->app->system->general->write_record_to_activity_log(_gettext("Business hours have been updated."));        

            return true;

        }

    }

    ##########################################
    #        Update VAT Rates                #
    ##########################################

    public function update_vat_rates($vat_rates) {

        $error_flag = false;

        // Cycle through the submitted VAT rates and update the database
        foreach ($vat_rates as $tax_key => $rate) {
            $sql =  "UPDATE ".PRFX."company_vat_tax_codes SET
                    rate = ".$this->db->qstr($rate)."
                    WHERE tax_key = ".$this->db->qstr($tax_key);

            if(!$rs = $this->db->Execute($sql)) {
                $error_flag = true;            
            }        
        }

        if($error_flag) {

            $this->app->system->general->force_error_page('database', __FILE__, __FUNCTION__, $this->db->ErrorMsg(), $sql, _gettext("Failed to update the VAT rates."));
            //return false;      

        } else {
            // Assign success message
            //$this->app->system->variables->systemMessagesWrite('success', _gettext("VAT rates have been updated."));

            // Log activity        
            //$this->app->system->general->write_record_to_activity_log(_gettext("VAT rates have been updated."));        

            return true;

        }

    }

    /** Close Functions **/

    /** Delete Functions **/

    /** Other Functions **/

    ##########################################
    #  Check Start and End times are valid   #
    ##########################################

    public function check_start_end_times($start_time, $end_time) {

        // If start time is before end time
        if($start_time > $end_time) {        
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("Start Time is after End Time."));
            return false;
        }

        // If the start and end time are the same    
        if($start_time ==  $end_time) {        
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("Start Time is the same as End Time."));
            return false;
        }

        return true;

    }

    ##########################
    #  Delete Company Logo   #
    ##########################

    public function delete_logo() {

        // Only delete a logo if there is one set
        if($this->get_company_details('logo')) {

            // Build the full logo file path (new)
            $logo_file = parse_url(MEDIA_DIR . $this->get_company_details('logo'), PHP_URL_PATH);

            // Check the file exists
            if(file_exists($logo_file)) {

                // Perform the deletion
                unlink($logo_file);        

            }

        }

    }

    ##########################
    #  Upload Company Logo   #
    ##########################

    public function upload_logo() {

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

                $this->app->system->general->force_error_page('database', __FILE__, __FUNCTION__, $this->db->ErrorMsg(), $sql, _gettext("Failed to update logo because the submitted file was invalid."));

            }

        }

    }

}