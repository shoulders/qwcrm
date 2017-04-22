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

/** Mandatory Code **/

/** Display Functions **/

/** New/Insert Functions **/

##########################################
#        Insert Company Hours            #
##########################################

function update_company_hours($db, $openingTime, $closingTime) {
    
    global $smarty;
    
    $sql = 'UPDATE '.PRFX.'COMPANY SET
            OPENING_HOUR    ='. $db->qstr( $openingTime['Time_Hour']     ).',
            OPENING_MINUTE  ='. $db->qstr( $openingTime['Time_Minute']   ).',
            CLOSING_HOUR    ='. $db->qstr( $closingTime['Time_Hour']     ).',
            CLOSING_MINUTE  ='. $db->qstr( $closingTime['Time_Minute']   );

    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_company_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        
        $smarty->assign('information_msg','Business hours have been updated.');
        return true;
        
    }
    
}


/** Get Functions **/

##########################################
#      get company details               #
##########################################

// This is in the main include.php file

##########################################
#      Get Start and End Times           #
##########################################

function get_company_start_end_times($db, $time_event) {
    
    global $smarty;
    
    $sql = 'SELECT OPENING_HOUR, OPENING_MINUTE, CLOSING_HOUR, CLOSING_MINUTE FROM '.PRFX.'COMPANY';

   if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_company_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {        
    
        $companyTime = $rs->GetArray();

        // return opening time in correct format for smarty time builder
        if($time_event == 'opening_time') {
            return $companyTime['0']['OPENING_HOUR'].':'.$companyTime['0']['OPENING_MINUTE'].':00';
        }

        // return closing time in correct format for smarty time builder
        if($time_event == 'closing_time') {
            return $companyTime['0']['CLOSING_HOUR'].':'.$companyTime['0']['CLOSING_MINUTE'].':00';
        }
        
    }
    
}

/** Update Functions **/

##########################
#  Update Company info   #
##########################

function update_company_details($db, $record) {
    
    global $smarty;
    
    $sql .= 'UPDATE '.PRFX.'COMPANY SET
            NAME                = '. $db->qstr( $record['company_name']               ).',
            NUMBER              = '. $db->qstr( $record['company_number']             ).',
            ADDRESS             = '. $db->qstr( $record['company_address']            ).',
            CITY                = '. $db->qstr( $record['company_city']               ).',
            STATE               = '. $db->qstr( $record['company_state']              ).',
            ZIP                 = '. $db->qstr( $record['company_zip']                ).',
            COUNTRY             = '. $db->qstr( $record['company_country']            ).',
            PHONE               = '. $db->qstr( $record['company_phone']              ).',
            MOBILE              = '. $db->qstr( $record['company_mobile']             ).',
            FAX                 = '. $db->qstr( $record['company_fax']                ).',
            EMAIL               = '. $db->qstr( $record['company_email']              ).',    
            CURRENCY_SYMBOL     = '. $db->qstr( $record['company_currency_sym']       ).',
            CURRENCY_CODE       = '. $db->qstr( $record['company_currency_code']      ).',
            DATE_FORMAT         = '. $db->qstr( $record['company_date_format']        ).',';
    
    if(!empty($_FILES['company_logo']['name'])) {
        $sql .='LOGO                = '. $db->qstr( MEDIA_DIR . $new_logo_filename        ).',';
    }         
        $sql .='WWW                 = '. $db->qstr( $record['company_www']                ).',
                OPENING_HOUR        = '. $db->qstr( $record['company_opening_hour']       ).',  
                OPENING_MINUTE      = '. $db->qstr( $record['company_opening_minute']     ).',
                CLOSING_HOUR        = '. $db->qstr( $record['company_closing_hour']       ).',
                CLOSING_MINUTE      = '. $db->qstr( $record['company_closing_minute']     ).',  
                TAX_RATE            = '. $db->qstr( $record['company_tax_rate']           ).',
                WELCOME_MSG         = '. $db->qstr( $record['company_welcome_msg']        );                           
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_company_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        
        // Assign success message
        $smarty->assign('information_msg', 'Company Details updated successfully');        
        return;
        
    }
    
}

/** Close Functions **/

/** Delete Functions **/

/** Other Functions **/

##########################################
#  Check Start and End times are valid   #
##########################################

function check_start_end_times($start_time, $end_time) {
    
    global $smarty; 
    
    // If start time is before end time
    if($start_time > $end_time) {        
        $smarty->assign('warning_msg', 'Start Time is after End Time');
        return false;
    }
        
    // If the start and end time are the same    
    if($start_time ==  $end_time) {        
        $smarty->assign('warning_msg', 'Start Time is the same as End Time');
        return false;
    }
    
    return true;
    
}

##########################
#  Upload Company Logo   #
##########################

function upload_company_logo($db) {
    
    // Logo - Only process if there is an image uploaded
    if($_FILES['company_logo']['size'] > 0) {
        
        // Delete current logo
        unlink(get_company_info($db, 'LOGO'));        
        
        // Allowed extensions
        $allowedExts = array('jpg', 'jpeg', 'gif', 'png');
        
        // Get file extension
        $filename_info = pathinfo($_FILES['company_logo']['name']);
        $extension = $filename_info['extension'];
        
        // Rename Logo Filename to logo.xxx (keeps original image extension)
        $new_logo_filename = 'logo.' . $extension;       
        
        // Validate the uploaded file is allowed (extension, mime type, 0 - 2mb)
        if ((($_FILES['company_logo']['type'] == 'image/gif')
                || ($_FILES['company_logo']['type'] == 'image/jpeg')
                || ($_FILES['company_logo']['type'] == 'image/jpg')
                || ($_FILES['company_logo']['type'] == 'image/pjpeg')
                || ($_FILES['company_logo']['type'] == 'image/x-png')
                || ($_FILES['company_logo']['type'] == 'image/png'))
                && ($_FILES['company_logo']['size'] < 2048000)
                && in_array($extension, $allowedExts)) {
    
            // Check for file submission errors and echo them
            if ($_FILES['company_logo']['error'] > 0 ) {
                echo 'Return Code: ' . $_FILES['company_logo']['error'] . '<br />';                
            
            // If no errors then move the file from the PHP temporary storage to the logo location
            } else {
                move_uploaded_file($_FILES['company_logo']['tmp_name'], MEDIA_DIR . $new_logo_filename);              
            }
            
        // If file is invalid then load the error page  
        } else {
            
            /*
            echo "Upload: "    . $_FILES['company_logo']['name']           . '<br />';
            echo "Type: "      . $_FILES['company_logo']['type']           . '<br />';
            echo "Size: "      . ($_FILES['company_logo']['size'] / 1024)  . ' Kb<br />';
            echo "Temp file: " . $_FILES['company_logo']['tmp_name']       . '<br />';
            echo "Stored in: " . MEDIA_DIR . $_FILES['file']['name']       ;
             */   
            force_page('core', 'error&error_msg=Invalid File');
            
        }
        
    }
    
}