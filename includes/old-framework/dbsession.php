<?php

/*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

class DBSession
{
    /* Tokenised session thing I am building */
    
    // create initial QW Database session
    function qw_session_create($db, $session_id, $client_id, $guest, $data, $user_id, $username) {

        global $smarty;

        $sql = "INSERT INTO ".PRFX."session SET
                session_id  =". $db->qstr( $session_id  ).",
                client_id   =". $db->qstr( $client_id   ).",
                guest       =". $db->qstr( $guest       ).", 
                time        =". $db->qstr( time()       ).", 
                data        =". $db->qstr( $data        ).",
                user_id     =". $db->qstr( $user_id     ).",
                username    =". $db->qstr( $username    );

        if(!$rs = $db->Execute($sql)) {
            force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->getTemplateVars('translate_customer_error_message_function_'.__FUNCTION__.'_failed'));
            exit;
        } else {

            return $db->Insert_ID();  

        }
        
    }

    // write QW Database session to db
    function qw_session_update($db, $session_id, $client_id, $guest, $data, $user_id, $username) {
        
        global $smarty;

        $sql = "UPDATE ".PRFX."session SET
                session_id  =". $db->qstr( $session_id  ).",
                client_id   =". $db->qstr( $client_id   ).",
                guest       =". $db->qstr( $guest       ).", 
                time        =". $db->qstr( time()       ).", 
                data        =". $db->qstr( $data        ).",
                user_id     =". $db->qstr( $user_id     ).",
                username    =". $db->qstr( $username    );

        if(!$rs = $db->Execute($sql)) {
            force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->getTemplateVars('translate_customer_error_message_function_'.__FUNCTION__.'_failed'));
            exit;
        } else {

            return $db->Insert_ID();  

        }        
        
    }
    
    // Get QW session information from QW database
    function qw_session_get_details($db, $session_id, $item = null) {
        
        global $smarty;

        $sql = "SELECT * FROM ".PRFX."session WHERE session_id=".$db->qstr($session_id);

        if(!$rs = $db->Execute($sql)) {
            force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->getTemplateVars('translate_customer_error_message_function_'.__FUNCTION__.'_failed'));
            exit;
        } else { 

            if($item === null){

                return $rs->GetRowAssoc(); 

            } else {

                return $rs->fields[$item];   

            } 

        }
        
    }
    
    // Delete the QW session
    function qw_session_destroy($db, $session_id) {
        
        global $smarty;

        $sql = "DELETE FROM ".PRFX."session WHERE session_id=".$db->qstr($session_id);

        if(!$rs = $db->Execute($sql)) {
            force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->getTemplateVars('translate_customer_error_message_function_'.__FUNCTION__.'_failed'));
            exit;        
            
        } else {
            return;
        }      
        
    }
    
    // verify the QW session
    function qw_session_verify($session_id) {
        
    }
    
    // Delete stale QW sessions in the database (this needs to be set to run automatically) / Garbage collect stale sessions from the SessionHandler backend.
    function qw_session_garbage_collection($db, $session_lifetime = 1440) {
        
        global $smarty;

        $sql = "DELETE FROM ".PRFX."session WHERE time < ".$db->qstr($session_lifetime);

        if(!$rs = $db->Execute($sql)) {
            force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->getTemplateVars('translate_customer_error_message_function_'.__FUNCTION__.'_failed'));
            exit;        
            
        } else {
            return;
        }           
    }
    
}