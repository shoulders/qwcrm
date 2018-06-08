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

##########################################
#      Get Start and End Times           #
##########################################

function get_company_start_end_times($db, $time_event) {
    
    $sql = "SELECT opening_hour, opening_minute, closing_hour, closing_minute FROM ".PRFX."company";

   if(!$rs = $db->Execute($sql)) {
        force_error_page('database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, _gettext("Failed to get the company start and end times."));
    } else {        
    
        $companyTime = $rs->GetRowAssoc();

        // return opening time in correct format for smarty time builder
        if($time_event == 'opening_time') {
            return $companyTime['opening_hour'].':'.$companyTime['opening_minute'].':00';
        }

        // return closing time in correct format for smarty time builder
        if($time_event == 'closing_time') {
            return $companyTime['closing_hour'].':'.$companyTime['closing_minute'].':00';
        }
        
    }
    
}

##########################################
#  Get email signature                   #
##########################################

function get_email_signature($db, $swift_emailer = null) {
    
    // only add email signature if enabled
    if(!get_company_details($db, 'email_signature_active')) { return; }
    
    // Load the signature from the database
    $email_signature = get_company_details($db, 'email_signature');
    
    // If swiftmailer is going to be used to add image via CID
    if($swift_emailer != null) {         
        $logo_string = '<img src="'.$swift_emailer->embed(Swift_Image::fromPath(get_company_details($db, 'logo'))).'" alt="'.get_company_details($db, 'display_name').'" width="150">'; 
        
        
    // Load the logo as a standard base64 string image
    } else {        
        $logo_string  = '<img src="data:image/jpeg;base64,'.base64_encode(file_get_contents(get_company_details($db, 'logo'))).'" alt="'.get_company_details($db, 'display_name').'" width="150">'; 
    }    
        
    // Swap the logo placeholders with the new logo string
    $email_signature  = replace_placeholder($email_signature, '{logo}', $logo_string);
        
    // Return the processed signature
    return $email_signature ;
    
}

##########################################
#  Get email message body                #
##########################################

function get_email_message_body($db, $message_name, $customer_details = null) {
    
    // get the message from the database
    $content = get_company_details($db, $message_name);
    
    // Process placeholders
    if($message_name == 'email_msg_invoice') {        
        $content = replace_placeholder($content, '{customer_display_name}', $customer_details['display_name']);
        $content = replace_placeholder($content, '{customer_first_name}', $customer_details['first_name']);
        $content = replace_placeholder($content, '{customer_last_name}', $customer_details['last_name']);
        $content = replace_placeholder($content, '{customer_credit_terms}', $customer_details['credit_terms']);
    }
    if($message_name == 'email_msg_workorder') {
        // not currently used
    }
    
    // return the process email
    return $content;
    
}

/** Update Functions **/

#############################
#  Update Company details   #
#############################

function update_company_details($db, $VAR) {

    $smarty = QSmarty::getInstance();
    
    // compensate for installation and migration
    if(!defined(DATE_FORMAT)) {
        define('DATE_FORMAT', get_company_details($db, 'date_format'));
    } 
           
    // Delete logo if selected and no new logo is presented
    if($VAR['delete_logo'] && !$_FILES['logo']['name']) {
        delete_logo($db);        
    }
    
    // A new logo is supplied, delete old and upload new
    if($_FILES['logo']['name']) {
        delete_logo($db);
        $new_logo_filepath = upload_logo($db);
    }
    
    $sql .= "UPDATE ".PRFX."company SET
            display_name            = ". $db->qstr( $VAR['display_name']                   ).",";
    
    if($VAR['delete_logo']) {
        $sql .="logo                = ''                                                   ,";
    }
                
    if(!empty($_FILES['logo']['name'])) {
        $sql .="logo                = ". $db->qstr( $new_logo_filepath  ).",";
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
            website                 =". $db->qstr( $VAR['website']                          ).",
            company_number          =". $db->qstr( $VAR['company_number']                   ).",                                        
            tax_type                =". $db->qstr( $VAR['tax_type']                         ).",
            tax_rate                =". $db->qstr( $VAR['tax_rate']                         ).",
            vat_number              =". $db->qstr( $VAR['vat_number']                       ).",
            year_start              =". date_to_timestamp($VAR['year_start'])               .",
            year_end                =". date_to_timestamp($VAR['year_end'])                 .",
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
        $smarty->assign('company_logo', QW_MEDIA_DIR . get_company_details($db, 'logo'));
        
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

function update_company_hours($db, $openingTime, $closingTime) {
    
    $smarty = QSmarty::getInstance();
    
    $sql = "UPDATE ".PRFX."company SET
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

/** Close Functions **/

/** Delete Functions **/

/** Other Functions **/

##########################################
#  Check Start and End times are valid   #
##########################################

function check_start_end_times($start_time, $end_time) {
    
    $smarty = QSmarty::getInstance(); 
    
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

function delete_logo($db) {
    
    // Only delete a logo if there is one set
    if(get_company_details($db, 'logo')) {
        
        // Prepare the correct file name from the entry in the database
        $logo_file = parse_url(MEDIA_DIR . get_company_details($db, 'logo'), PHP_URL_PATH);
        
        // Perform the deletion
        unlink($logo_file);
        
    }
    
}
##########################
#  Upload Company Logo   #
##########################

function upload_logo($db) {
    
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

###########################################
#  Replace placeholders with new content  #
###########################################

function replace_placeholder($content, $placeholder, $replacement) {
    
    return preg_replace('/'.$placeholder.'/', $replacement, $content);
    
}