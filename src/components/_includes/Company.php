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

                }        

            // Any other lookup error
            $this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to get company details."));        

        } else {

            if($item === null) {

                return $rs->GetRowAssoc();            

            } else {

                return $rs->fields[$item];   

            } 

        }

    }

    #####################################
    #    Get company tax systems        #
    #####################################

    public function getTaxSystems() {

        $sql = "SELECT * FROM ".PRFX."company_tax_systems";

        if(!$rs = $this->app->db->execute($sql)){        
            $this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to get tax types."));
        } else {

            return $rs->GetArray();

        }    

    }

    #####################################
    #    Get VAT Tax Codes              # Editable is only used in company:edit
    ##################################### system_tax_code is not currently used and might be removed

    public function getVatTaxCodes($hidden_status = null, $editable_status = null, $system_tax_code = null) {

        $sql = "SELECT * FROM ".PRFX."company_vat_tax_codes";

        // Restrict by enabled status
        $sql .= "\nWHERE enabled = 1";

        // Restrict by hidden status
        if(!is_null($hidden_status)) {
            $sql .= "\nAND hidden = ".$this->app->db->qstr($hidden_status);
        }

        // Restrict by editable status
        if(!is_null($editable_status)) {
            $sql .= "\nAND editable = ".$this->app->db->qstr($editable_status);
        }

        // Restrict by tax code type
        if(!is_null($system_tax_code)) {
            $sql .= "\nAND standard = ".$this->app->db->qstr($system_tax_code);
        }

        if(!$rs = $this->app->db->execute($sql)){        
            $this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to get VAT Taxx Codes."));
        } else {

            return $rs->GetArray();

        }    

    }
    
    
    #####################################
    #   Get VAT rate for given tax_key  #
    #####################################

    public function getVatRate($vat_tax_code) {

        $sql = "SELECT rate FROM ".PRFX."company_vat_tax_codes
                WHERE tax_key = ".$this->app->db->qstr($vat_tax_code);

        if(!$rs = $this->app->db->execute($sql)){        
            $this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to get VAT rate."));
        } else {

            return $rs->fields['rate'];

        }    

    }

    #####################################
    #    Get default VAT Code           # // This gets the default VAT Tax Code based on the company tax system or supplied tax_system
    #####################################

    public function getDefaultVatTaxCode($tax_system = null) {

        if(!$tax_system) {$tax_system = QW_TAX_SYSTEM;}

        if($tax_system == 'no_tax') { return 'TNA'; }
        if($tax_system == 'sales_tax_cash') { return 'TNA'; }     
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
                WHERE tax_key = ".$this->app->db->qstr($vat_tax_code);

        if(!$rs = $this->app->db->execute($sql)){        
            $this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to get VAT Tax Code status."));
        } else {       

            return $rs->fields['enabled'];

        }   

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
            $this->deleteLogo();
            $new_logo_filepath = $this->uploadLogo();
        }

        $sql .= "UPDATE ".PRFX."company_record SET
                company_name            =". $this->app->db->qstr( $qform['company_name']                     ).",";

        if($qform['delete_logo']) {
            $sql .="logo                =''                                                     ,";
        }

        if(!empty($_FILES['logo']['name'])) {
            $sql .="logo                =". $this->app->db->qstr( $new_logo_filepath                       ).",";
        }

        $sql .="address                 =". $this->app->db->qstr( $qform['address']                          ).",
                city                    =". $this->app->db->qstr( $qform['city']                             ).",
                state                   =". $this->app->db->qstr( $qform['state']                            ).",
                zip                     =". $this->app->db->qstr( $qform['zip']                              ).",
                country                 =". $this->app->db->qstr( $qform['country']                          ).",
                primary_phone           =". $this->app->db->qstr( $qform['primary_phone']                    ).",
                mobile_phone            =". $this->app->db->qstr( $qform['mobile_phone']                     ).",
                fax                     =". $this->app->db->qstr( $qform['fax']                              ).",
                email                   =". $this->app->db->qstr( $qform['email']                            ).",    
                website                 =". $this->app->db->qstr( $this->app->system->general->processInputtedUrl($qform['website'])    ).",
                company_number          =". $this->app->db->qstr( $qform['company_number']                   ).",                                        
                tax_system              =". $this->app->db->qstr( $qform['tax_system']                       ).",
                sales_tax_rate          =". $this->app->db->qstr( $qform['sales_tax_rate']                   ).",
                vat_number              =". $this->app->db->qstr( $qform['vat_number']                       ).",
                vat_flat_rate           =". $this->app->db->qstr( $qform['vat_flat_rate']                    ).",   
                year_start              =". $this->app->db->qstr( $this->app->system->general->dateToMysqlDate($qform['year_start'])   ).",
                year_end                =". $this->app->db->qstr( $this->app->system->general->dateToMysqlDate($qform['year_end'])     ).",
                welcome_msg             =". $this->app->db->qstr( $qform['welcome_msg']                      ).",
                currency_symbol         =". $this->app->db->qstr( htmlentities($qform['currency_symbol'])    ).",
                currency_code           =". $this->app->db->qstr( $qform['currency_code']                    ).",
                date_format             =". $this->app->db->qstr( $qform['date_format']                      ).",            
                email_signature         =". $this->app->db->qstr( $qform['email_signature']                  ).",
                email_signature_active  =". $this->app->db->qstr( $qform['email_signature_active']           ).",
                email_msg_invoice       =". $this->app->db->qstr( $qform['email_msg_invoice']                ).",
                email_msg_workorder     =". $this->app->db->qstr( $qform['email_msg_workorder']              );                          


        if(!$rs = $this->app->db->execute($sql)) {
            $this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to update the company details."));
        } else {       

            // Refresh company logo
            //$this->app->smarty->assign('company_logo', QW_MEDIA_DIR . $this->get_company_details('logo'));

            // Assign success message
            $this->app->system->variables->systemMessagesWrite('success', _gettext("Company details updated."));

            // Log activity        
            $this->app->system->general->writeRecordToActivityLog(_gettext("Company details updated."));

            return;

        }

    }

    ##########################################
    #        Update Company Hours            #
    ##########################################

    public function updateOpeningHours($openingTime, $closingTime) {

        $sql = "UPDATE ".PRFX."company_record SET
                opening_hour    =". $this->app->db->qstr( $openingTime['Time_Hour']     ).",
                opening_minute  =". $this->app->db->qstr( $openingTime['Time_Minute']   ).",
                closing_hour    =". $this->app->db->qstr( $closingTime['Time_Hour']     ).",
                closing_minute  =". $this->app->db->qstr( $closingTime['Time_Minute']   );

        if(!$rs = $this->app->db->execute($sql)) {
            $this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to update the company hours."));
        } else {

            // Assign success message
            $this->app->system->variables->systemMessagesWrite('success', _gettext("Business hours have been updated."));

            // Log activity        
            $this->app->system->general->writeRecordToActivityLog(_gettext("Business hours have been updated."));        

            return true;

        }

    }

    ##########################################
    #        Update VAT Rates                #
    ##########################################

    public function updateVatRates($vat_rates) {

        $error_flag = false;

        // Cycle through the submitted VAT rates and update the database
        foreach ($vat_rates as $tax_key => $rate) {
            $sql =  "UPDATE ".PRFX."company_vat_tax_codes SET
                    rate = ".$this->app->db->qstr($rate)."
                    WHERE tax_key = ".$this->app->db->qstr($tax_key);

            if(!$rs = $this->app->db->execute($sql)) {
                $error_flag = true;            
            }        
        }

        if($error_flag) {

            $this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to update the VAT rates."));
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

        $chicken = $_FILES;
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

                $this->app->system->page->forceErrorPage('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to update logo because the submitted file was invalid."));

            }

        }

    }

}