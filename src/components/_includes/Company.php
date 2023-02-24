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

    /** Insert Functions **/

    /** Get Functions **/

    ##########################
    #  Get Company details   #  // The extra trap code is probably not needed here but shou be moved to where it should be now
    ##########################

    /*
     * This combined function allows you to pull any of the company information individually
     * or return them all as an array
     * supply the required field name for a single item or all for all items as an array.
     */

    public function getRecord($item = null) {

        // This is a fallback to make diagnosing critical database failure - This is the first function loaded for $date_format
        if (!$this->app->db->isConnected()) {
            die('
                    <div style="color: red;">'.
                    _gettext("Something went wrong with your QWcrm database connection and it is not connected.").'<br><br>'.
                    _gettext("Check to see if your Prefix is correct, if not, you might have a").' <strong>configuration.php</strong> '._gettext("file that should not be present or is corrupt.").'<br><br>'.
                    _gettext("Error occured at").' <strong>'.__FUNCTION__.'()</strong><br><br>'.
                    '<strong>'._gettext("Database Error Message").':</strong> '.$this->app->db->ErrorMsg().
                    '</div>'
                );
        }

        $sql = "SELECT * FROM ".PRFX."company_record";

        if(!$rs = $this->app->db->execute($sql)) {          

            // Part of the fallback
            if($item == 'date_format') {            

                // This is first database Query that will fail if there are issues with the database connection          
                die('
                        <div style="color: red;">'.
                        _gettext("Something went wrong executing an SQL query.").'<br><br>'.
                        _gettext("Check to see if your Prefix is correct, if not, you might have a").' <strong>configuration.php</strong> '._gettext("file that should not be present or is corrupt.").'<br><br>'.
                        _gettext("Error occured at").' <strong>function '.__FUNCTION__.'()</strong> '._gettext("when trying to get the variable").' <strong>date_format</strong>'.'<br><br>'.
                        '<strong>'._gettext("Database Error Message").':</strong> '.$this->app->db->ErrorMsg().
                        '</div>'
                   );

            } else {        

                // Any other lookup error
                $this->app->system->page->forceErrorPage('system', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("This is the first function loaded for the variable date_format."));
            
            }

        }

        if($item === null) {

            return $rs->GetRowAssoc();            

        } else {

            return $rs->fields[$item];   

        }       

    }

    #####################################
    #    Get company tax systems        #
    #####################################

    public function getTaxSystems() {

        $sql = "SELECT * FROM ".PRFX."company_tax_systems";

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        return $rs->GetArray();           

    }

    #####################################
    #    Get VAT Tax Codes              # Editable is only used in company:edit
    #####################################

    public function getVatTaxCodes($hidden_status = null, $editable_status = null) {

        $sql = "SELECT * FROM ".PRFX."company_vat_tax_codes";

        // Only return enabled records
        $sql .= "\nWHERE enabled = 1";

        // Restrict by hidden_status - hidden codes canot be selected by users, these are only needed for displaying data
        if(!is_null($hidden_status)) {
            $sql .= "\nAND hidden = ".$this->app->db->qStr($hidden_status);
        }

        // Restrict by editable_status - only some of the VAT codes are editable
        if(!is_null($editable_status)) {
            $sql .= "\nAND editable = ".$this->app->db->qStr($editable_status);
        }

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        return $rs->GetArray();           

    }
    
    #####################################
    #   Get VAT rate for given tax_key  #
    #####################################

    public function getVatRate($vat_tax_code) {

        $sql = "SELECT rate FROM ".PRFX."company_vat_tax_codes
                WHERE tax_key = ".$this->app->db->qStr($vat_tax_code);

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}
        
        return $rs->fields['rate'];           

    }

    #####################################
    #    Get default VAT Code           # // This gets the default VAT Tax Code based on the company tax system or supplied tax_system
    #####################################

    public function getDefaultVatTaxCode($tax_system = null) {

        if(!$tax_system) {$tax_system = QW_TAX_SYSTEM;}

        if($tax_system == 'no_tax') { return 'T9'; }
        if($tax_system == 'sales_tax_cash') { return 'T9'; }     
        if($tax_system == 'vat_standard') { return 'T1'; }    
        if($tax_system == 'vat_cash') { return 'T1'; }
        if($tax_system == 'vat_flat_basic') { return 'T1'; }
        if($tax_system == 'vat_flat_cash') { return 'T1'; }       

    }

    ############################
    #  Get VAT Code Status     #  // Only return true is a valid configuration for the code
    ############################

    public function getVatTaxCodeStatus($vat_tax_code) {

        $sql = "SELECT enabled
                FROM ".PRFX."company_vat_tax_codes
                WHERE tax_key = ".$this->app->db->qStr($vat_tax_code);

        if(!$rs = $this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}     

        return $rs->fields['enabled'];          

    }



    ##########################################
    #      Get Company Opening Hours         # // return opening hours in smarty/datetime/timestamp with an optional specified date (2019-05-72)
    ##########################################

    public function getOpeningHours($event, $type, $date = null, $date_format = null) {

        // Convert Date to time stamp
        if($date) { 
            $date_timestamp = $this->app->system->general->dateToTimestamp($date, $date_format);
        }

        // Smarty Time Format
        if($type == 'smartytime') {

            // return opening time in correct format for smartytime builder
            if($event == 'opening_time') {
                return $this->getRecord('opening_hour').':'.$this->getRecord('opening_minute').':00';
            }

            // return closing time in correct format for smartytime builder
            if($event == 'closing_time') {
                return $this->getRecord('closing_hour').':'.$this->getRecord('closing_minute').':00';
            }   

        }

        // MySQL DATETIME format
        if($type == 'datetime') {

            // return opening time in correct format for smarty time builder
            if($event == 'opening_time') {            
                //return $date.' '.$this->get_company_details('opening_hour').':'.$this->get_company_details('opening_minute');  // This only allows the use of DATE and not DATETIME            
                return $this->app->system->general->buildMysqlDatetime($this->getRecord('opening_hour'), $this->getRecord('opening_minute'), 0, date('m', $date_timestamp), date('d', $date_timestamp), date('Y', $date_timestamp));            
            }

            // return closing time in correct format for smarty time builder
            if($event == 'closing_time') {
                //return $date.' '.$this->get_company_details('closing_hour').':'.$this->get_company_details('closing_minute');  // This only allows the use of DATE and not DATETIME

                return $this->app->system->general->buildMysqlDatetime($this->getRecord('closing_hour'), $this->getRecord('closing_minute'), 0, date('m', $date_timestamp), date('d', $date_timestamp), date('Y', $date_timestamp));                    
            }
        }

        // Unix Timestamp
        if($type == 'timestamp') {

            // return opening time in correct format for smarty time builder
            if($event == 'opening_time') {
                return mktime($this->getRecord('opening_hour'), $this->getRecord('opening_minute'), 0, date('m', $date_timestamp), date('d', $date_timestamp), date('Y', $date_timestamp));
            }

            // return closing time in correct format for smarty time builder
            if($event == 'closing_time') {
                return mktime($this->getRecord('closing_hour'), $this->getRecord('closing_minute'), 0, date('m', $date_timestamp), date('d', $date_timestamp), date('Y', $date_timestamp));
            }   

        }    

    }

    /** Update Functions **/

    #############################
    #  Update Company details   #
    #############################

    public function updateRecord($qform) {
            
        $sql = null;

        // Update VAT rates
        $this->updateVatRates($qform['vat_tax_codes']);

        // Prevent undefined variable errors
        $qform['delete_logo'] = $qform['delete_logo'] ?? null;
        
        // Delete logo if selected and no new logo is presented
        if($qform['delete_logo'] && !$_FILES['logo']['name']) {
            $this->deleteLogo();        
        }

        // A new logo is supplied, delete old and upload new
        if($_FILES['logo']['name']) {            
            $new_logo_filepath = $this->uploadLogo();
        }

        $sql .= "UPDATE ".PRFX."company_record SET
                company_name            =". $this->app->db->qStr( $qform['company_name']                     ).",";

        if($qform['delete_logo']) {
            $sql .="logo                =''                                                     ,";
        }

        if(!empty($_FILES['logo']['name'])) {
            $sql .="logo                =". $this->app->db->qStr( $new_logo_filepath                       ).",";
        }

        $sql .="address                 =". $this->app->db->qStr( $qform['address']                          ).",
                city                    =". $this->app->db->qStr( $qform['city']                             ).",
                state                   =". $this->app->db->qStr( $qform['state']                            ).",
                zip                     =". $this->app->db->qStr( $qform['zip']                              ).",
                country                 =". $this->app->db->qStr( $qform['country']                          ).",
                primary_phone           =". $this->app->db->qStr( $qform['primary_phone']                    ).",
                mobile_phone            =". $this->app->db->qStr( $qform['mobile_phone']                     ).",
                fax                     =". $this->app->db->qStr( $qform['fax']                              ).",
                email                   =". $this->app->db->qStr( $qform['email']                            ).",    
                website                 =". $this->app->db->qStr( $this->app->system->general->processInputtedUrl($qform['website'])    ).",
                company_number          =". $this->app->db->qStr( $qform['company_number']                   ).",                                        
                tax_system              =". $this->app->db->qStr( $qform['tax_system']                       ).",
                sales_tax_rate          =". $this->app->db->qStr( $qform['sales_tax_rate']                   ).",
                vat_number              =". $this->app->db->qStr( $qform['vat_number']                       ).",
                vat_flat_rate           =". $this->app->db->qStr( $qform['vat_flat_rate']                    ).",   
                year_start              =". $this->app->db->qStr( $this->app->system->general->dateToMysqlDate($qform['year_start'])   ).",
                year_end                =". $this->app->db->qStr( $this->app->system->general->dateToMysqlDate($qform['year_end'])     ).",
                voucher_expiry_offset   =". $this->app->db->qStr( $qform['voucher_expiry_offset']            ).",  
                welcome_msg             =". $this->app->db->qStr( $qform['welcome_msg']                      ).",
                currency_symbol         =". $this->app->db->qStr( htmlentities($qform['currency_symbol'])    ).",
                currency_code           =". $this->app->db->qStr( $qform['currency_code']                    ).",
                date_format             =". $this->app->db->qStr( $qform['date_format']                      ).",            
                email_signature         =". $this->app->db->qStr( $qform['email_signature']                  ).",
                email_signature_active  =". $this->app->db->qStr( $qform['email_signature_active']           ).",
                email_msg_invoice       =". $this->app->db->qStr( $qform['email_msg_invoice']                ).",
                email_msg_workorder     =". $this->app->db->qStr( $qform['email_msg_workorder']              ).",   
                email_msg_voucher       =". $this->app->db->qStr( $qform['email_msg_voucher']                );                          


        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}     

        // Refresh company logo
        //$this->app->smarty->assign('company_logo', QW_MEDIA_DIR . $this->get_company_details('logo'));

        // Assign success message
        $this->app->system->variables->systemMessagesWrite('success', _gettext("Company details updated."));

        // Log activity        
        $this->app->system->general->writeRecordToActivityLog(_gettext("Company details updated."));

        return;        

    }

    ##########################################
    #        Update Company Hours            #
    ##########################################

    public function updateOpeningHours($openingTime, $closingTime) {

        $sql = "UPDATE ".PRFX."company_record SET
                opening_hour    =". $this->app->db->qStr( $openingTime['Time_Hour']     ).",
                opening_minute  =". $this->app->db->qStr( $openingTime['Time_Minute']   ).",
                closing_hour    =". $this->app->db->qStr( $closingTime['Time_Hour']     ).",
                closing_minute  =". $this->app->db->qStr( $closingTime['Time_Minute']   );

        if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}

        // Assign success message
        $this->app->system->variables->systemMessagesWrite('success', _gettext("Business hours have been updated."));

        // Log activity        
        $this->app->system->general->writeRecordToActivityLog(_gettext("Business hours have been updated."));        

        return true;        

    }

    ##########################################
    #        Update VAT Rates                #
    ##########################################

    public function updateVatRates($vat_rates) {

        // Cycle through the submitted VAT rates and update the database
        foreach ($vat_rates as $tax_key => $rate) {
            
            $sql =  "UPDATE ".PRFX."company_vat_tax_codes SET
                    rate = ".$this->app->db->qStr($rate)."
                    WHERE tax_key = ".$this->app->db->qStr($tax_key);

            if(!$this->app->db->execute($sql)) {$this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql);}
            
        }
        
        // Assign success message
        //$this->app->system->variables->systemMessagesWrite('success', _gettext("VAT rates have been updated."));

        // Log activity        
        //$this->app->system->general->writeRecordToActivityLog(_gettext("VAT rates have been updated."));        

        return true;        

    }

    /** Close Functions **/

    /** Delete Functions **/
    
    ##########################
    #  Delete Company Logo   #
    ##########################

    public function deleteLogo() {

        // Only delete a logo if there is one set
        if($this->getRecord('logo')) {

            // Build the full logo file path (new)
            $logo_file = parse_url(MEDIA_DIR . $this->getRecord('logo'), PHP_URL_PATH);

            // Check the file exists
            if(file_exists($logo_file)) {

                // Perform the deletion
                unlink($logo_file);        

            }

        }

    }
    
    /** Check Functions **/
    
    ##########################################
    #  Check Start and End times are valid   #
    ##########################################

    public function checkOpeningHoursValid($start_time, $end_time) {

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

    /** Other Functions **/


    ##########################
    #  Upload Company Logo   #
    ##########################

    public function uploadLogo() {
        
        $error_flag = false;
        
        // Allowed extensions
        $allowedExt = array('png', 'jpg', 'jpeg', 'gif');
        
        // Allowed mime types
        $allowedMime = array('image/gif', 'image/jpeg', 'image/jpg', 'image/pjpeg', 'image/x-png', 'image/png');
        
        // Max Allowed Size (bytes) (2097152 = 2MB)
        $maxAllowedSize = 2097152;
        
        // Check there is an uplaoded file
        if($_FILES['logo']['size'] = 0) {            
            $error_flag = true;
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("There was no logo uploaded.")); 
        }

        // Check for file submission errors
        if ($_FILES['logo']['error'] > 0 ) {
            $error_flag = true;
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("Files submission error with Return Code").': ' . $_FILES['logo']['error'] . '<br />');
        }   

        // Get file extension
        $filename_info = pathinfo($_FILES['logo']['name']);
        $fileExtension = $filename_info['extension'];

        // Validate the uploaded file is an allowed file type
        if (!in_array($fileExtension, $allowedExt)) {
            $error_flag = true;
            $this->app->system->variables->systemMessagesWrite('warning', _gettext("Failed to upload the new logo because it does not have an allowed file extension."));
        }

        // Validate the uploaded file is allowed mime type
        if (!in_array($_FILES['logo']['type'], $allowedMime)) {          
            $error_flag = true;
            $this->app->system->variables->systemMessagesWrite('warning', _gettext("Failed to upload the new logo because it does not have an allowed mime type."));            
        }       
        
        // Validate the uploaded file is not to big
        if ($_FILES['logo']['size'] > $maxAllowedSize) {
            $error_flag = true;
            $this->app->system->variables->systemMessagesWrite('warning', _gettext("Failed to upload the new logo because it is too large.").' '._gettext("The maximum size is ").' '.($maxAllowedSize/1024/1024).'MB');
        }
                    
        // If no errors
        if(!$error_flag) {                
                    
            // Delete old logo
            $this->deleteLogo();

            // New Logo Filename logo.xxx (keeps original image extension)
            $new_logo_filename = 'logo.' . $fileExtension;  

            // Move the file from the PHP temporary storage to the logo location
            move_uploaded_file($_FILES['logo']['tmp_name'], MEDIA_DIR . $new_logo_filename);

            // Return the filename with a random query to bypass caching issues
            return $new_logo_filename . '?' . strtolower(\Joomla\CMS\User\UserHelper::genRandomPassword(3));
                    
        } else {

                /*
                echo "Upload: "    . $_FILES['company_logo']['name']           . '<br />';
                echo "Type: "      . $_FILES['company_logo']['type']           . '<br />';
                echo "Size: "      . ($_FILES['company_logo']['size'] / 1024)  . ' Kb<br />';
                echo "Temp file: " . $_FILES['company_logo']['tmp_name']       . '<br />';
                echo "Stored in: " . MEDIA_DIR . $_FILES['file']['name']       ;
                 */   

                //$this->app->system->variables->systemMessagesWrite('danger', _gettext("Failed to update logo because the submitted file was invalid."));                    
                $this->app->system->variables->systemMessagesWrite('warning', _gettext("The logo has not been changed."));
                
                // Return the orginal logo storage string
                return $this->app->components->company->getRecord('logo');

            }

        }

    

}