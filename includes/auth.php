<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

class Auth {
    
    // varibles if not declared, are by default public - so i am forcing them private
    private $session;
    private $secretKey;    
    
    /** 
     * This function is always called when the class is invoked
     *  I don't know the difference between this and the constructor
     */
    function Auth($db, $smarty, $secretKey) {       
        
        // Make variables available throught the class        
        $this->db           = $db;
        $this->smarty       = $smarty;
        $this->secretKey    = $secretKey;        
        $this->session      = new Session();        
        $this->login(); 
        
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
        if(isset($_POST['action']) && $_POST['action'] === 'login' && !$_SESSION['login_id']) {
                        
            
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
        $this->session->set('login_id',                 $login_id                                       );  // Use this to validate the session - $_SESSION['login_id'] 
        $this->session->set('login_account_type_id',    $login_account_type_id                          );
        $this->session->set('login_display_name',       $login_display_name                             );
        
        // This is used to validate the logged in user's session
        $this->session->set('login_hash',               $hashed_login_pwd                               );

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
 
    // Logout from the session
    function logout(){
        
        // Log activity       
        write_record_to_activity_log($this->smarty->getTemplateVars('translate_system_auth_log_message_logout_successful_for').' '.$this->session->get('login_usr'));
        
        $this->session->del('login_usr');
        $this->session->del('login_pwd');
        $this->session->del('login_hash');        
        $this->session->del('login_id');
        $this->session->del('login_account_type_id');
        $this->session->del('login_display_name');   
        
        // Reload with 'Logout Successful' message
        force_page('core', 'login', 'information_msg='.$this->smarty->getTemplateVars('translate_system_auth_advisory_message_logout_successful'));
        exit;
        
    }
   
}