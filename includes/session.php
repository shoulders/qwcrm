<?php

defined('_QWEXEC') or die;

class Session {

    // Start the class
    function Session($session_lifetime) {
        
        // Make variables available throught the class        
        $this->session_lifetime = $session_lifetime;
        
        // Run the start session function
        $this->start();
    }
    
    // Start the session with specified options
    function start() {
        
        // Current Time - Declared here to keep cookie and session last_active in sync
        $current_time = time();
        
        // Set the session last_active
        $_SESSION['last_active'] = $current_time;    
        
        // Set the paremeters for the session cookie
        session_set_cookie_params($current_time + $this->session_lifetime); // Set intial session lifetime
        session_name('QWCRM');                                              // Set session cookie name
        
        // Start session with set paremeters
        session_start();                                                
    }

    // Set a single value
    function set($name, $value) {
        $_SESSION[$name] = $value;
    }

    // Get a single value
    function get($name) {
        if (isset($_SESSION[$name])) {
            return $_SESSION[$name];
        } else {
            return false;
        }
    }

    // Delete a single value
    function del($name) {
        unset($_SESSION[$name]);
    }

    // Destroy the Session
    function destroy($keep_cookie = false) {
        
        /*
         * In order to kill the session altogether, such as to log the user out, the session id
         * must also be unset. If a cookie is used to propagate the session id (default behavior),
         * then the session cookie must be deleted.
         * 
         * When you delete the cookie you cannot restart the session in the same PHP instance as there is no longer a browser server link stored in a cookie
         */
        
        if($keep_cookie === false) {
            
            // Expire the Cookie (I assume the browser then deletes it)
            $cookie_params = session_get_cookie_params();            
            setcookie(session_name(), '', time() - 3600, $cookie_params['path'], $cookie_params['domain'], $cookie_params['secure'], $cookie_params['httponly']);
            
        }
        
        // The deletes the $_SESSION variable/data
        session_unset();
        
        // Destroy Session - removes session data stored on the server for that session id and requests the client to delete that cookie
        session_destroy();
        
    }
    
    
    
    
    
    
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