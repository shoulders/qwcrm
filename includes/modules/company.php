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

##########################################
#        Insert Company Hours            #
##########################################

function update_company_hours($db, $openingTime, $closingTime) {
    
    global $smarty;
    
    $sql = "UPDATE ".PRFX."company SET
            opening_hour    =". $db->qstr( $openingTime['Time_Hour']     ).",
            opening_minute  =". $db->qstr( $openingTime['Time_Minute']   ).",
            closing_hour    =". $db->qstr( $closingTime['Time_Hour']     ).",
            closing_minute  =". $db->qstr( $closingTime['Time_Minute']   );

    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to update the company hours."));
        exit;
    } else {
        
        $smarty->assign('information_msg', gettext("Business hours have been updated."));
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
    
    $sql = "SELECT opening_hour, opening_minute, closing_hour, closing_minute FROM ".PRFX."company";

   if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to get the company start and end times."));
        exit;
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

/** Update Functions **/

##########################
#  Update Company info   #
##########################

function update_company_details($db, $VAR) {
    
    global $smarty;
    
    $sql .= "UPDATE ".PRFX."company SET
            name                = ". $db->qstr( $VAR['name']               ).",";
                
    if(!empty($_FILES['logo']['name'])) {
        $sql .="logo                = ". $db->qstr( MEDIA_DIR . $new_logo_filename  ).",";
    }
    
    $sql .="company_number      = ". $db->qstr( $VAR['company_number']                  ).",
            address             = ". $db->qstr( $VAR['address']                         ).",
            city                = ". $db->qstr( $VAR['city']                            ).",
            state               = ". $db->qstr( $VAR['state']                           ).",
            zip                 = ". $db->qstr( $VAR['zip']                             ).",
            country             = ". $db->qstr( $VAR['country']                         ).",
            phone               = ". $db->qstr( $VAR['phone']                           ).",
            mobile              = ". $db->qstr( $VAR['mobile']                          ).",
            fax                 = ". $db->qstr( $VAR['fax']                             ).",
            email               = ". $db->qstr( $VAR['email']                           ).",    
            website             = ". $db->qstr( $VAR['website']                         ).",  
            tax_rate            = ". $db->qstr( $VAR['tax_rate']                        ).",
            welcome_msg         = ". $db->qstr( $VAR['welcome_msg']                     ).",
            currency_symbol     = ". $db->qstr( htmlentities($VAR['currency_symbol'])   ).",
            currency_code       = ". $db->qstr( $VAR['currency_code']                   ).",
            date_format         = ". $db->qstr( $VAR['date_format']                     );
                          

    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to update the company details."));
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
        $smarty->assign('warning_msg', gettext("Start Time is after End Time."));
        return false;
    }
        
    // If the start and end time are the same    
    if($start_time ==  $end_time) {        
        $smarty->assign('warning_msg', gettext("Start Time is the same as End Time."));
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
        unlink(get_company_info($db, 'logo'));        
        
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
                echo gettext("Return Code").': ' . $_FILES['company_logo']['error'] . '<br />';                
            
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
            
            force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to update logo because the submitted file was invalid."));
            
        }
        
    }
    
}