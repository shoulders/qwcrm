<?php

##########################################
#      Get Start and End Times           #
##########################################

function get_company_start_end_times($db, $time_event) {
    
    $sql = 'SELECT OPENING_HOUR, OPENING_MINUTE, CLOSING_HOUR, CLOSING_MINUTE FROM '.PRFX.'TABLE_COMPANY';

    if(!$rs = $db->execute($sql)) {
        force_page('core', 'error','error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    }
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

##########################################
#        Insert Company Hours            #
##########################################

function update_company_hours($db, $openingTime, $closingTime) {
    
    global $smarty;
    
    $sql = 'UPDATE '.PRFX.'TABLE_COMPANY SET
            OPENING_HOUR    ='. $db->qstr( $openingTime['Time_Hour']     ).',
            OPENING_MINUTE  ='. $db->qstr( $openingTime['Time_Minute']   ).',
            CLOSING_HOUR    ='. $db->qstr( $closingTime['Time_Hour']     ).',
            CLOSING_MINUTE  ='. $db->qstr( $closingTime['Time_Minute']   );

    if(!$rs = $db->execute($sql)) {
        force_page('core', 'error','error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    } else {            
        $smarty->assign('information_msg','Business hours have been updated.');
        return true;
    }
    
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

##########################
#  Update Company info   #
##########################

function update_company_details($db, $record) {
    
    global $smarty;
    
        $sql .= 'UPDATE '.PRFX.'TABLE_COMPANY SET
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
                INVOICE_TAX_RATE    = '. $db->qstr( $record['company_invoice_tax_rate']   ).',
                WELCOME_MSG         = '. $db->qstr( $record['company_welcome_msg']        ).',      
                INVOICE_MSG         = '. $db->qstr( $record['company_invoice_msg']        );             
    
    if(!$rs = $db->execute($sql)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    } else {
        
        // Assign success message
        $smarty->assign('information_msg', 'Company Details updated successfully');        
        return;
        
    }
    
}

################################################
#  Get payment info - individual items         #  // sort translation
################################################

/*
 * This combined function allows you to pull any of the setup information individually
 * or return them all as an array
 * supply the required field name or all to return all of them as an array
 */

function get_payment_settings($db, $item = null) {
    
    global $smarty;

    $sql = 'SELECT * FROM '.PRFX.'PAYMENT';
    
    if(!$rs = $db->execute($sql)){        
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_system_include_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {        
        if($item === null){
            
            return $rs->GetArray(); 
            
        } else {
            
            return $rs->fields[$item]; 
            
        }    
        
    }
    
}

#####################################
#    Update Payment details         #
#####################################

function update_payment_settings($db, $record) {
    
    $sql = "UPDATE ".PRFX."PAYMENT SET 
            
            BANK_ACCOUNT_NAME       =". $db->qstr( $record['bank_account_name']        ).",
            BANK_NAME               =". $db->qstr( $record['bank_name']                ).",
            BANK_ACCOUNT_NUMBER     =". $db->qstr( $record['bank_account_number']      ).",
            BANK_SORT_CODE          =". $db->qstr( $record['bank_sort_code']           ).",
            BANK_IBAN               =". $db->qstr( $record['bank_iban']                ).",        
            BANK_TRANSACTION_MSG    =". $db->qstr( $record['bank_transaction_message'] ).",
            CHECK_PAYABLE_TO_MSG    =". $db->qstr( $record['check_payable_to_msg']     ).",
            PAYPAL_EMAIL            =". $db->qstr( $record['paypal_email']             );

    if(!$rs = $db->execute($sql)) {
        echo $db->ErrorMsg();
    } else {
        
        return;
        
    }
    
}

#####################################
#   Update Payment Methods status   #
#####################################

function update_payment_methods_status($db, $record) {
    
    global $smarty;

    // Array of all valid payment methods
    $payment_methods = array(
                                array('smarty_tpl_key'=>'credit_card_active',       'payment_method_status'=>$record['credit_card_active']      ),
                                array('smarty_tpl_key'=>'cheque_active',            'payment_method_status'=>$record['cheque_active']           ),
                                array('smarty_tpl_key'=>'cash_active',              'payment_method_status'=>$record['cash_active']             ),
                                array('smarty_tpl_key'=>'gift_certificate_active',  'payment_method_status'=>$record['gift_certificate_active'] ),
                                array('smarty_tpl_key'=>'paypal_active',            'payment_method_status'=>$record['paypal_active']           ),
                                array('smarty_tpl_key'=>'direct_deposit_active',    'payment_method_status'=>$record['direct_deposit_active']   )    
                            );
   
    // Loop throught the various payment methods and update the database
    foreach($payment_methods as $payment_method) {
        
        // make empty status = zero (not nessasary but neater)
        if ($payment_method['payment_method_status'] == ''){$payment_method['payment_method_status'] = '0';}
        
        $sql = "UPDATE ".PRFX."PAYMENT_METHODS SET ACTIVE=". $db->qstr( $payment_method['payment_method_status'] )." WHERE SMARTY_TPL_KEY=". $db->qstr( $payment_method['smarty_tpl_key'] ); 
        
        if(!$rs = $db->execute($sql)) {
            force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_company_error_message_function_'.__FUNCTION__.'_failed'));
            exit;
        }
    }
    
}
    

#####################################
#    Get Payment methods status     #
#####################################

function get_payment_methods_status($db) {
    
    $sql = "SELECT * FROM ".PRFX."PAYMENT_METHODS";

    if(!$rs = $db->execute($sql)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    }

    return $rs->GetArray();
    
}

#####################################
#   Upload invoice rate CSV file    #
#####################################

function upload_invoice_rates_csv($db, $VAR) {

    // Allowed extensions
    $allowedExts = array('csv');
    
    // Get file extension
    $filename_info = pathinfo($_FILES['invoice_rates_csv']['name']);
    $extension = $filename_info['extension'];
    
    // Validate the uploaded file is allowed (extension, mime type, 0 - 2mb)
    if ((($_FILES['invoice_rates_csv']['type'] == 'text/csv'))            
            || ($_FILES['invoice_rates_csv']['type'] == 'application/vnd.ms-excel') // CSV files created by excel - i might remove this
            //|| ($_FILES['invoice_rates_csv']['type'] == 'text/plain')               // this seems a bit dangerous   
            && ($_FILES['invoice_rates_csv']['size'] > 0)   
            && ($_FILES['invoice_rates_csv']['size'] < 2048000)
            && in_array($extension, $allowedExts)) {

        // Check for file submission errors and echo them
        if ($_FILES['invoice_rates_csv']['error'] > 0 ) {
            echo 'Return Code: ' . $_FILES['invoice_rates_csv']['error'] . '<br />';                

        // If no errors then move the file from the PHP temporary storage to the logo location
        } else {        

            // Empty Current Invoice Rates Table (if set)
            if($VAR['empty_invoice_rates'] === '1'){
                
                $sql = "TRUNCATE ".PRFX."TABLE_LABOR_RATE";
                
                if(!$rs = $db->execute($sql)) {
                force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
                exit;}
            }
            
            // Open CSV file            
            $handle = fopen($_FILES['invoice_rates_csv']['tmp_name'], 'r');

            // Read CSV data and insert into database
            while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {

                $sql = "INSERT INTO ".PRFX."TABLE_LABOR_RATE(LABOR_RATE_NAME,LABOR_RATE_AMOUNT,LABOR_RATE_COST,LABOR_RATE_ACTIVE,LABOR_TYPE,LABOR_MANUF) VALUES ('$data[1]','$data[2]','$data[3]','$data[4]','$data[5]','$data[6]')";

                if(!$rs = $db->execute($sql)) {
                force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
                exit;}

            }

            // Close CSV file
            fclose($handle);

            // Delete CSV file - not sure this is needed becaus eit is temp
            unlink($_FILES['invoice_rates_csv']['tmp_name']);

        }

    // If file is invalid then load the error page  
    } else {
        
        /*
        echo "Upload: "    . $_FILES['invoice_rates_csv']['name']           . '<br />';
        echo "Type: "      . $_FILES['invoice_rates_csv']['type']           . '<br />';
        echo "Size: "      . ($_FILES['invoice_rates_csv']['size'] / 1024)  . ' Kb<br />';
        echo "Temp file: " . $_FILES['invoice_rates_csv']['tmp_name']       . '<br />';
        echo "Stored in: " . MEDIA_DIR . $_FILES['file']['name']       ;
         */
        force_page('core', 'error&error_msg=Invalid File');

    }
    
}

#####################################
#     edit invoice rate item        #
#####################################

function edit_invoice_rates_item($db, $VAR){
    
    $sql = "UPDATE ".PRFX."TABLE_LABOR_RATE SET
        LABOR_RATE_NAME     =". $db->qstr( $VAR['display']      ).",
        LABOR_RATE_AMOUNT   =". $db->qstr( $VAR['amount']       ).",
        LABOR_RATE_COST     =". $db->qstr( $VAR['cost']         ).",
        LABOR_RATE_ACTIVE   =". $db->qstr( $VAR['active']       ).",
        LABOR_TYPE          =". $db->qstr( $VAR['type']         ).",
        LABOR_MANUF         =". $db->qstr( $VAR['manufacturer'] )."
        WHERE LABOR_RATE_ID =". $db->qstr( $VAR['id']           );

    if(!$rs = $db->execute($sql)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    }
    
}

#####################################
#     delete invoice rate item      #
#####################################

function delete_invoice_rates_item($db, $VAR){
    
    $sql = "DELETE FROM ".PRFX."TABLE_LABOR_RATE WHERE LABOR_RATE_ID =".$db->qstr($VAR['id']);

    if(!$rs = $db->execute($sql)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    }
    
}

#####################################
#     New invoice rate item         #
#####################################

function new_invoice_rates_item($db, $VAR){
    
    $sql = "INSERT INTO ".PRFX."TABLE_LABOR_RATE SET
        LABOR_RATE_NAME     =". $db->qstr( $VAR['display']      ).",
        LABOR_RATE_AMOUNT   =". $db->qstr( $VAR['amount']       ).",
        LABOR_RATE_COST     =". $db->qstr( $VAR['cost']         ).",
        LABOR_TYPE          =". $db->qstr( $VAR['type']         ).",
        LABOR_MANUF         =". $db->qstr( $VAR['manufacturer'] ).",
        LABOR_RATE_ACTIVE   =". $db->qstr( 1                    );

    if(!$rs = $db->execute($sql)) {
    force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
    exit;
    }
    
}

#####################################
#     Get invoice rates item        #
#####################################

function get_invoice_rates_item($db){
    
    // Loads rates from database
    $sql = "SELECT * FROM ".PRFX."TABLE_LABOR_RATE ORDER BY LABOR_RATE_ID ASC";
    
    if(!$rs = $db->execute($sql)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    }    
    
    return $rs->GetArray();
    
}