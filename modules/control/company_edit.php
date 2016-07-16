<?php
$inv_increment = $VAR['inv_number'];
if(isset($VAR['submit'])) {
        //Start Invoice Numbers from a specific point - eg 2000 will start invoice numbering from 2000 and up
        if($VAR['inv_number'] != '' || $VAR['inv_number'] > '0' ) {
                $q = "ALTER TABLE ".PRFX."TABLE_INVOICE auto_increment =".$inv_increment ;

                if(!$rs = $db->execute($q)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;

                }
                
        }
    $wo_increment = $VAR['wo_number'];
        //Start Work Orders Numbers from a specific point - eg 2000 will start works order numbering from 2000 and up
        if($VAR['wo_number'] != '' || $VAR['wo_number'] > '0' ) {
            $q = "ALTER TABLE ".PRFX."TABLE_WORK_ORDER auto_increment =".$wo_increment ;

            if(!$rs = $db->execute($q)) {
                force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
                exit;

            }

        }
/* Removes / from messages parsed to database */
$string3= $VAR['welcome'];
$string4=stripslashes($string3);
$string5= $VAR['inv_thank_you'];
$string6=stripslashes($string5);
$string= $VAR['company_name'];
$string2=stripslashes($string);
    // File Uploader Start
    if(isset($VAR['COMPANY_LOGO'])){
    $allowedExts = array("jpg", "jpeg", "gif", "png");
    $extension = end(explode(".", $_FILES["file"]["name"]));
    if ((($_FILES["file"]["type"] == "image/gif")
        || ($_FILES["file"]["type"] == "image/jpeg")
        || ($_FILES["file"]["type"] == "image/png")
        || ($_FILES["file"]["type"] == "image/pjpeg"))
        && ($_FILES["file"]["size"] < 2048000)
        && in_array($extension, $allowedExts))
    {
        if ($_FILES["file"]["error"] > 0 )
        {
            echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
        }
        else
        {

//            echo "Upload: " . $_FILES["file"]["name"] . "<br />";
//            echo "Type: " . $_FILES["file"]["type"] . "<br />";
//            echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
//            echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";


            move_uploaded_file($_FILES["file"]["tmp_name"],
                "upload/" . $_FILES["file"]["name"]);
//            echo "Stored in: " . "upload/" . $_FILES["file"]["name"];
            }
        }
    else
    {
        force_page('core', 'error&error_msg=Invalid File');
    }
    }
    // File Uploader End

$q = 'UPDATE '.PRFX.'SETUP SET
        INVOICE_TAX = '. $db->qstr( $VAR['inv_tax']) .',
        INVOICE_NUMBER_START = '. $db->qstr( $VAR['inv_number']).',
        WO_NUMBER_START = '. $db->qstr( $VAR['wo_number']).',
        INV_THANK_YOU = '. $db->qstr( $string6     ) .',
        WELCOME_NOTE = '. $db->qstr( $string4      ) ;
    if(!$rs = $db->execute($q)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    }

                $q = 'UPDATE '.PRFX.'TABLE_COMPANY SET
              COMPANY_NAME        = '. $db->qstr( $string2 ).',
              COMPANY_ABN        = '. $db->qstr( $VAR['company_abn']) .',
              COMPANY_ADDRESS     = '. $db->qstr( $VAR['address']) .',
            COMPANY_CITY         = '. $db->qstr( $VAR['city']) .',
            COMPANY_STATE        = '. $db->qstr( $VAR['state']) .',
            COMPANY_ZIP        = '. $db->qstr( $VAR['zip']) .',
            COMPANY_COUNTRY        = '. $db->qstr( $VAR['country']).',
            COMPANY_PHONE        = '. $db->qstr( $VAR['phone']) .',
            COMPANY_MOBILE        = '. $db->qstr( $VAR['mobile_phone']) .',
            COMPANY_FAX             = '. $db->qstr( $VAR['fax']) .',
                        COMPANY_CURRENCY_SYMBOL    = '. $db->qstr( $VAR['currency_sym']) .',
                        COMPANY_CURRENCY_CODE    = '. $db->qstr( $VAR['currency_code']) .',
                        COMPANY_LOGO = '. $db->qstr('upload/'. $_FILES["file"]["name"]).',
                        COMPANY_DATE_FORMAT    = '. $db->qstr( $VAR['date_format']) ;
    if(!$rs = $db->execute($q)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    }else {
        force_page('control', 'company_edit&msg=The Company information was updated');
        exit;
    }



} else {

    /* get current Company information */
    $q = 'SELECT * FROM '.PRFX.'TABLE_COMPANY';
        if(!$rs = $db->execute($q)) {
            force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
            exit;
        } else {
            $arr = $rs->GetArray();
        }
    
    /* load setup Information */
    $q = 'SELECT * FROM '.PRFX.'SETUP';
    if(!$rs = $db->execute($q)) {
            force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
            exit;
        } else {
            $setup = $rs->GetArray();
        }
    
    /* get country codes */
    $q = 'SELECT * FROM '.PRFX.'COUNTRY';
    if(!$rs = $db->execute($q)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    }
    $country = $rs->GetArray();
    
    //$arr = stripslashes($arr);
    
    $smarty->assign('country', $country);
    $smarty->assign('setup', $setup);
    $smarty->assign('company', $arr);
    $smarty->display('control/company_edit.tpl');
}


?>
