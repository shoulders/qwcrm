<?php

/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

##############################
#  Delete Gift Certificate   #
##############################

function delete_giftcert($db, $giftcert_id) {     
    
    global $smarty;

    // update and set non-active as you cannot really delete an issues gift certificate

    $sql = "UPDATE ".PRFX."GIFTCERT SET ACTIVE=". $db->qstr(0)."WHERE GIFTCERT_ID=".$db->qstr($giftcert_id);

    if(!$db->execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_giftcert_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {

        return;

    }            
        
}

##########################
#  Get giftcert details  #
##########################

/*
 * This combined function allows you to pull any of the giftcert information individually
 * or return them all as an array
 * supply the required field name or all to return all of them as an array
 */

function get_giftcert_details($db, $giftcert_id, $item = null){
    
    global $smarty;

    $sql = "SELECT * FROM ".PRFX."GIFTCERT WHERE GIFTCERT_ID=".$db->qstr($giftcert_id);
    
    if(!$rs = $db->execute($sql)){        
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_giftcert_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        
        if($item === null){
            
            return $rs->GetArray();            
            
        } else {
            
            return $rs->fields[$item];   
            
        } 
        
    }
    
}


################################################
#  Validate the Gift Certificate can be used   #
################################################

function validate_giftcert_code($db, $giftcert_id) {

    // check is active
    if(get_giftcert_details($db, $giftcert_id['ACTIVE']) != 1) {
        //force_page('core','error', 'error_msg=This gift certificate is not active');
        //exit;
        return false;
    }

    // check if expired
    if(get_giftcert_details($db, $giftcert_id['DATE_EXPIRES']) < time()) {
        //force_page('core', 'error', 'error_msg=This gift certificate is expired.');
        //exit;
        return false;
    }
    
    return true;
    
}

############################################
#  Generate Random Gift Certificate code   #
############################################

function generate_giftcert_code() {
    
    // generate a random string for the gift certificate
    
    $acceptedChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $max_offset = strlen($acceptedChars)-1;
    $giftcert_code = '';
    
    for($i=0; $i < 16; $i++) {
        $giftcert_code .= $acceptedChars{mt_rand(0, $max_offset)};
    }
    
    return $giftcert_code;
    
}

#################################
#   insert Gift Certificate     #
#################################

function insert_giftcert($db, $customer_id, $date_expires, $giftcert_code, $amount, $memo) {
    
    global $smarty;

    $sql = "INSERT INTO ".PRFX."GIFTCERT SET 
            CUSTOMER_ID     =". $db->qstr( $customer_id             ).",               
            INVOICE_ID      =". $db->qstr( 0                        ).",
            EMPLOYEE_ID     =". $db->qstr( $_SESSION['login_id']    ).",
            DATE_CREATED    =". $db->qstr( time()                   ).",
            DATE_EXPIRES    =". $db->qstr( $date_expires            ).",
            DATE_REDEEMED   =". $db->qstr( 0                        ).",
            IS_REDEEMED     =". $db->qstr( 0                        ).",   
            CODE            =". $db->qstr( $giftcert_code           ).",                
            AMOUNT          =". $db->qstr( $amount                  ).",
            ACTIVE          =". $db->qstr( 1                        ).",                
            MEMO            =". $db->qstr( $memo                    );

    if(!$db->execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_giftcert_error_message_function_'.__FUNCTION__.'_failed'));
        exit;

    } else {

        return $db->insert_id();
    }
    
}

#########################################
#   get giftcert_id by giftcert_code    #
#########################################

function get_giftcert_id_by_code($db, $giftcert_code) {
    
    global $smarty;
    
    $sql = "SELECT * FROM ".PRFX."GIFTCERT WHERE CODE=".$db->qstr( $giftcert_code );

    if(!$rs = $db->execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_giftcert_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    }
    
    if($rs->fields['GIFTCERT_ID'] != '') {
        return $rs->fields['GIFTCERT_ID'];
    } else {
        return false;
    }
    
}


######################################################
#   redeem the gift certificate against an invoice   #
######################################################

function update_giftcert_as_redeemed($db, $giftcert_id, $invoice_id) {
    
    global $smarty;
    
    $sql = "UPDATE ".PRFX."GIFTCERT SET
            DATE_REDEEMED       =". $db->qstr( time()       ).",
            IS_REDEEMED         =". $db->qstr( 1            ).",   
            INVOICE_ID          =". $db->qstr( $invoice_id  ).",
            ACTIVE              =". $db->qstr( 0            )."
            WHERE GIFTCERT_ID   =". $db->qstr( $giftcert_id );
    
    if(!$rs = $db->execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_giftcert_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    }
    
}