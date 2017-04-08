<?php

require(INCLUDES_DIR.'modules/company.php');

// Update Company details
if(isset($VAR['submit'])) {
    
    // Logo - Only process if there is an image uploaded
    if(!empty($_FILES['company_logo']['name'])) {
        
        // Delete current logo
        unlink(get_company_info($db, 'LOGO'));        
        
        // Allowed extensions
        $allowedExts = array('jpg', 'jpeg', 'gif', 'png');       
        
        // Get file extension
        $extension = end(explode(".", $_FILES['company_logo']['name']));
        
        // Rename Logo Filename to logo.xxx (keeps original image extension)
        $logo_info = pathinfo($_FILES['company_logo']['name']);
        $new_logo_filename = 'logo.' . $logo_info['extension'];
        
        // Check file has an allowed mime type, file extensions and is less than 2mb
        if ((($_FILES['company_logo']['type'] == 'image/gif') || ($_FILES['company_logo']['type'] == 'image/jpeg') || ($_FILES['company_logo']['type'] == 'image/png'))            
            && ($_FILES['company_logo']['size'] < 2048000)
            && in_array($extension, $allowedExts)) {
    
            // Check for file submission errors and echo them
            if ($_FILES['company_logo']['error'] > 0 ) {
                echo 'Return Code: ' . $_FILES['company_logo']['error'] . '<br />';                
            
            // If no errors then move the file from the PHP temporary storage to the logo location
            } else {        

                 echo "Upload: "    . $_FILES['file']['name'] . '<br />';
                 echo "Type: "      . $_FILES['file']['type'] . '<br />';
                 echo "Size: "      . ($_FILES['file']['size'] / 1024) . ' Kb<br />';
                 echo "Temp file: " . $_FILES['file']['tmp_name'] . '<br />';
                 echo "Stored in: " . 'media/' . $_FILES['file']['name'];

                move_uploaded_file($_FILES['company_logo']['tmp_name'], 'media/' . $new_logo_filename);
//              
            }
            
        // If file is invalid then load the error page  
        } else {
    
            force_page('core', 'error&error_msg=Invalid File');
            
        }
        
    }
       
    // submit data
    $sql = 'UPDATE '.PRFX.'TABLE_COMPANY SET
            NAME                = '. $db->qstr( $VAR['company_name']                ).',
            NUMBER              = '. $db->qstr( $VAR['company_number']              ).',
            ADDRESS             = '. $db->qstr( $VAR['company_address']             ).',
            CITY                = '. $db->qstr( $VAR['company_city']                ).',
            STATE               = '. $db->qstr( $VAR['company_state']               ).',
            ZIP                 = '. $db->qstr( $VAR['company_zip']                 ).',
            COUNTRY             = '. $db->qstr( $VAR['company_country']             ).',
            PHONE               = '. $db->qstr( $VAR['company_phone']               ).',
            MOBILE              = '. $db->qstr( $VAR['company_mobile']              ).',
            FAX                 = '. $db->qstr( $VAR['company_fax']                 ).',
            EMAIL               = '. $db->qstr( $VAR['company_email']               ).',    
            CURRENCY_SYMBOL     = '. $db->qstr( $VAR['company_currency_sym']        ).',
            CURRENCY_CODE       = '. $db->qstr( $VAR['company_currency_code']       ).',
            DATE_FORMAT         = '. $db->qstr( $VAR['company_date_format']         ).', 
            LOGO                = '. $db->qstr( 'media/'.$new_logo_filename         ).',
            WWW                 = '. $db->qstr( $VAR['company_www']                 ).',
            OPENING_HOUR        = '. $db->qstr( $VAR['company_opening_hour']        ).',  
            OPENING_MINUTE      = '. $db->qstr( $VAR['company_opening_minute']      ).',
            CLOSING_HOUR        = '. $db->qstr( $VAR['company_closing_hour']        ).',
            CLOSING_MINUTE      = '. $db->qstr( $VAR['company_closing_minute']      ).',  
            INVOICE_TAX_RATE    = '. $db->qstr( $VAR['company_invoice_tax_rate']    ).',
            WELCOME_MSG         = '. $db->qstr( $VAR['company_welcome_msg']         ).',      
            INVOICE_MSG         = '. $db->qstr( $VAR['company_invoice_msg']         );             
    
    if(!$rs = $db->execute($sql)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    } else {
        force_page('company', 'company_edit&msg=The Company information was updated');
        exit;
    }

} else {
     
    // Fetch page
    $smarty->assign('country', get_country_codes($db));
    $smarty->assign('company', get_company_info($db));
    $BuildPage .= $smarty->fetch('company/company_edit.tpl');
}