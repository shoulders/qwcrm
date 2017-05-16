<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

class Auth {
    
    // varibles if not declared, are by default public - so i am forcing them private
    private $session;
    private $secretKey;    
    
    /** 
     * This function is always called when the class is invoked
     *  I don't know the difference between this and the constructor
     */
    function Auth($db, $smarty, $secretKey, $session_lifetime) {       
        
        // Make variables available throught the class        
        $this->db               = $db;
        $this->smarty           = $smarty;        
        $this->secretKey        = $secretKey;
        $this->session_lifetime = $session_lifetime;
        $this->session          = new Session($this->session_lifetime);        
        
    }    
    
    // Intial User Login
    function login() {           
        
        // If there is a session confirm details so no further processing is required
        if ($this->session->get('login_id')) {            
                        
            if($this->confirmAuth()) {
                return;
            }
            
        }
        
        // If this is a Fresh Login
        if(isset($_POST['action']) && $_POST['action'] === 'login' && !$_SESSION['login_token']) {        
            
            // Regenerates the session ID and moves over the session data to a new ID and deletes the old one (Prevents Session Attacks)
            session_regenerate_id(true);       
            
            
            /* Get Submitted Values Section */
            
                        
            // Get the username
            $login_usr = $_POST['login_usr'];
            
            // Get the password
            $login_pwd = $_POST['login_pwd'];
            
            // If username or password is missing, redirect
            if (!isset($login_usr) || $login_usr == '' || !isset($login_pwd) || $login_pwd == ''){
                force_page('core', 'login', 'warning_msg='.$this->smarty->getTemplateVars('translate_system_auth_advisory_message_username_or_password_missing'));
                exit;
            } 
            
            
            /* SQL Section */
            
                
            // load matching records from the database
            $sql    = "SELECT EMPLOYEE_ID, EMPLOYEE_TYPE, EMPLOYEE_DISPLAY_NAME, EMPLOYEE_HASH
                        FROM ".PRFX."EMPLOYEE
                        WHERE EMPLOYEE_STATUS = '1'
                        AND EMPLOYEE_LOGIN=".$this->db->qstr($login_usr);

            $rs             = $this->db->Execute($sql);            
            $row            = $rs->FetchRow();
            $record_count   = $rs->RecordCount();
            
                        
            /* Verification Section */
            
                        
            // If there are no matching records
            if ($record_count == 0) {    

                // Log activity    
                write_record_to_activity_log($this->smarty->getTemplateVars('translate_system_auth_log_message_login_failed_username_does_not_exist').' '.$login_usr);  

                // Reload with 'Login Failed' message
                force_page('core', 'login', 'warning_msg='.$this->smarty->getTemplateVars('translate_system_auth_advisory_message_login_failed'));
                exit;
                
            }

            // If there is more than one matching record
            if ($record_count > 1) {    

                // Log activity
                write_record_to_activity_log($this->smarty->getTemplateVars('translate_system_auth_log_message_login_failed_duplicate_username_and_password_for').' '.$login_usr);  

                // Reload with 'Login Failed' message
                force_page('core', 'login', 'warning_msg='.$this->smarty->getTemplateVars('translate_system_auth_advisory_message_login_failed'));
                exit;
                
            }
           
            // if there is a single valid user, set the session variables
            if ($record_count == 1) {
                
                /* If we did not get a login ID
                if (!isset($row['EMPLOYEE_ID'])){          

                    // Log activity       
                    write_record_to_activity_log($this->smarty->getTemplateVars('translate_system_auth_log_message_login_failed_no_active_login_id_for').' '.$login_usr);

                    // Reload with 'Login Failed' message
                    force_page('core', 'login', 'warning_msg='.$this->smarty->getTemplateVars('translate_system_auth_advisory_message_login_failed'));
                    exit;                
                }*/       
                
                // Get the Hashed Password
                $hashed_login_pwd = $this->hashPassword($login_pwd);
                
                // Verify the password against that in the database
                if ($hashed_login_pwd === $row['EMPLOYEE_HASH']) {
                    
                    
                    /* Verification Complete - Create Logged in session section */
                    

                    // Set the Employees ID number
                    $login_id = $row['EMPLOYEE_ID'];

                    // Set the Account Type
                    $login_account_type_id = $row['EMPLOYEE_TYPE'];

                    // Set the Display Name
                    $login_display_name = $row['EMPLOYEE_DISPLAY_NAME'];
                    
                    // Store user data in the session - these are used throughout QWcrm and is used for validating the session
                    $this->storeAuth($login_usr, $hashed_login_pwd, $login_id, $login_account_type_id, $login_display_name);

                    // Log activity       
                    write_record_to_activity_log($this->smarty->getTemplateVars('translate_system_auth_log_message_login_successful_for').' '.$login_usr); 

                    // Reload with 'Login Successful' message
                    force_page('core', 'home', 'information_msg='.$this->smarty->getTemplateVars('translate_system_auth_advisory_message_login_successful'));
                    exit;

                
                // The password is wronglog and display error    
                } else {
                    
                    // Log activity    
                    write_record_to_activity_log($this->smarty->getTemplateVars('translate_system_auth_log_message_login_password_does_not_match_for').' '.$login_usr);  

                    // Reload with 'Login Failed' message
                    force_page('core', 'login', 'warning_msg='.$this->smarty->getTemplateVars('translate_system_auth_advisory_message_login_failed'));
                    exit;
                }
                    
            }            

        }
        
    }  
    

    /* Functions Section */
    
        
    // Store variables in the session
    function storeAuth($login_usr, $hashed_login_pwd, $login_id, $login_account_type_id, $login_display_name){
        
        $this->session->set('login_usr',                $login_usr                                      );
        $this->session->set('login_hash',               $hashed_login_pwd                               );  // This is used to validate the logged in user's session
        $this->session->set('login_id',                 $login_id                                       );
        $this->session->set('login_account_type_id',    $login_account_type_id                          );
        $this->session->set('login_display_name',       $login_display_name                             );
        $this->session->set('last_active',              time()                                          );  // This is used to control inactive session last_active
        $this->session->set('login_token',              'login_verified'                                );  // Use this for a test for user logged in

    } 
    
    // Verify the User's details are valid by comparing the Session Hash against the stored hash for the User in the database
    function confirmAuth() {
        
        // Get user information form the database
        $sql = "SELECT EMPLOYEE_HASH
                FROM ".PRFX."EMPLOYEE
                WHERE EMPLOYEE_STATUS = '1'
                AND EMPLOYEE_LOGIN=".$this->db->qstr($this->session->get('login_usr'));
                
        $rs = $this->db->Execute($sql);
        $row = $rs->FetchRow();
        
        if ($this->session->get('login_hash') != $row['EMPLOYEE_HASH']) {            
            $this->logout();
            //return false;
        } else {            
            return true;            
        }
        
    }
     
    // Hash the password
    function hashPassword($login_pwd) {
        
        return hash('sha256', $this->secretKey.$login_pwd);
        
    }
 
     // Session Inactivity Control
    function sessionTimeOut($logout_type = 'logoutRestartSession') {

        // If session lifetime is set to unlimited
        if($this->session_lifetime == '0') { return; }

        // Verify if the user is still active
        if ($_SESSION['last_active'] + $this->session_lifetime > time()) {

            // Current Time - Declared here to keep cookie and session last_active in sync
            $current_time = time();
            
            // Set the session last_active
            $_SESSION['last_active'] = $current_time;           
                
            // Update the Session Cookie expiry time
            $cookie_params = session_get_cookie_params();                
            setcookie(session_name(), session_id(), $current_time + $this->session_lifetime, $cookie_params['path'], $cookie_params['secure'], $cookie_params['httponly']);            
            
            return;
            
        // if the session is timed out then logout and redirect to the login page
        } else {
            
            // Destroy Login Only
            if($logout_type == 'logoutOnly') {
                $this->logoutOnly();
                $message_transfer_method = 'session';
            }        

            // Logout by wiping and restarting the session
            if($logout_type == 'logoutRestartSession') {
                $this->logoutRestartSession();
                $message_transfer_method = 'session';
            }

            // Full logout - The most complete logout, no session restart, all data destroyed (add 'get' parameter to force_page)
            if($logout_type == 'logoutFull') {
                $this->logoutFull();
                $message_transfer_method = 'get';
            }

            // Log activity       
            write_record_to_activity_log('This user has been logged out because of inactivity '.$this->session->get('login_usr'));
            
            // Reload with 'Session Timeout' message            
            force_page('core', 'login', 'warning_msg=You have been logged out because of inactivity', $message_transfer_method);
            exit;            

        }    
    
    }    
    
    // Logout from QWcrm
    function logout($logout_type = 'logoutRestartSession') {
        
        // Destroy Login Only
        if($logout_type == 'logoutOnly') {
            $this->logoutOnly();
            $message_transfer_method = 'session';
        }        

        // Logout by wiping and restarting the session
        if($logout_type == 'logoutRestartSession') {
            $this->logoutRestartSession();
            $message_transfer_method = 'session';
        }

        // Full logout - The most complete logout, no session restart, all data destroyed (add 'get' parameter to force_page)
        if($logout_type == 'logoutFull') {
            $this->logoutFull();
            $message_transfer_method = 'get';
        }
        
        // Log activity       
        write_record_to_activity_log($this->smarty->getTemplateVars('translate_system_auth_log_message_logout_successful_for').' '.$this->session->get('login_usr'));        
                
        // Reload with 'Logout Successful' message        
        force_page('core', 'login', 'information_msg='.$this->smarty->getTemplateVars('translate_system_auth_advisory_message_logout_successful'), $message_transfer_method);
        exit;
        
    } 
 
    // Logout from QWcrm only, session is maintained and none login data in the session is kept i.e. 'post_emulation'
    private function logoutOnly() {        
                
        // Regenerates the session ID and moves over the session data to a new ID and deletes the old one (Prevents Session Attacks)        
        session_regenerate_id(true); 
        
        // Unset all the login details
        $this->session->del('login_usr');
        $this->session->del('login_pwd');
        $this->session->del('login_hash');        
        $this->session->del('login_id');
        $this->session->del('login_account_type_id');
        $this->session->del('login_display_name');
        
        // Expire the Cookie
        $cookie_params = session_get_cookie_params();            
        setcookie(session_name(), session_id(), 0, $cookie_params['path'], $cookie_params['domain'], $cookie_params['secure'], $cookie_params['httponly']);            
        
        return;
        
    }
    
    // Logout - Destroy all data in the session and then restart the session
    private function logoutRestartSession() {      
     
        // Expire the Cookie
        $cookie_params = session_get_cookie_params();            
        setcookie(session_name(), session_id(), 0, $cookie_params['path'], $cookie_params['domain'], $cookie_params['secure'], $cookie_params['httponly']);            
        
        // Destroy Session (Keeping Session Cookie to allow messages to be sent by $_SESSION)
        $this->session->destroy(true);        

        // Restart Session
        $this->session->start();
        
        // Regenerates the session ID and moves over the session data to a new ID and deletes the old one (Prevents Session Attacks)
        // This will NOT work if runbefore the session restarts, probably because the browser is not informed of the change
        session_regenerate_id(true);  
        
        return;
        
    }
    
    // Full logout - The most complete logout, no session restart, all data destroyed
    private function logoutFull() {                      
        
        // Destroy Session - A new session id will be created by the browser on the next page load so we do not need to run 'session_regenerate_id(true)'
        $this->session->destroy();
        
        return;
        
    }
    
}

// make the cookie/session persitient
function makeCookieSessionPersistent() {
    //add cookie setting stuff here
}