<?php

/* File Upload */
if(isset($VAR['csv_upload'])) {

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








// Now if we edit/add a new item
if(isset($VAR['submit'])) {
    
    // edit rate
    if($VAR['submit'] == 'update') {            
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
    
    // delete rate
    if($VAR['submit'] == 'delete') {
        $sql = "DELETE FROM ".PRFX."TABLE_LABOR_RATE WHERE LABOR_RATE_ID =".$db->qstr($VAR['id']);
        
        if(!$rs = $db->execute($sql)) {
            force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
            exit;
        }

    }

    // New Rate
    if($VAR['submit'] == 'new') {
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

    // Reload rates page
    force_page('company', 'invoice_rates');
    exit;
    
} else {
    
    // Loads rates from database
    $sql = "SELECT * FROM ".PRFX."TABLE_LABOR_RATE ORDER BY LABOR_RATE_ID ASC";
    
    if(!$rs = $db->execute($sql)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    }    
    $smarty->assign('rate', $rs->GetArray());
    
    // Fetch Page    
    $BuildPage .= $smarty->fetch('company/invoice_rates.tpl');
    
}