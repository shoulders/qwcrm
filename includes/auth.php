<?php

class Auth {
    
    // varibles if not declared are by default public - so i am forcing them private
    private $session;
    private $secretKey;
  
    /** 
     * This function is always called when the class is invoked
     *  I dont know the difference between this and the constructor
     */
    function Auth($db, $smarty, $redirect, $secretKey){
        
        // Make variables available throught the class
        $this->db           = $db;
        $this->smarty       = $smarty;          // this allows the use of smarty translations
        $this->redirect     = $redirect;
        $this->secretKey    = $secretKey;        
        $this->session      = new Session();        
        $this->login();
    }    
    
    // Login User
    function login(){
        
        // See if we have values already stored in the session - if hash matches return to index.php code to process
        if ($this->session->get('login_hash')) {
            $this->confirmAuth();
            return;
        }
        
        // If this is a Fresh Login
        if(isset($_POST['action']) && $_POST['action'] === 'login'){
            
            // If username or password is missing, redirect
            if (!isset($_POST['login_usr']) || $_POST['login_usr'] === '' || !isset($_POST['login_pwd']) || $_POST['login_pwd'] === ''){
                force_page('core', 'login', 'warning_msg='.$this->smarty->get_template_vars('translate_system_auth_advisory_message_username_or_password_missing'));
                exit;
            }

            // Hash the POST'ed password with MD5 and store it as $login_pwd - after this point the password is always encrypted
            $login_pwd = md5($_POST['login_pwd']);           

            // This is required unless I use the escaping code above
            $login_usr = $_POST['login_usr'];
            
            /* username and password verification section */

            // Query to count number of users with this combination
            $sql    = "SELECT COUNT(*) AS NUM_USERS FROM ".PRFX."TABLE_EMPLOYEE
                        WHERE EMPLOYEE_STATUS = '1'
                        AND EMPLOYEE_LOGIN=".$this->db->qstr($login_usr)."
                        AND EMPLOYEE_PASSWD=".$this->db->qstr($login_pwd);

            $result = $this->db->Execute($sql);
            $row    = $result->FetchRow();

            /* Validate the POST'ed username - check to see if there is a matching username and password pair */

            // If there are no matching username and password pairs
            if ($row['NUM_USERS'] == 0) {    

                // Log activity       
                write_record_to_activity_log($this->smarty->get_template_vars('translate_system_auth_log_message_login_failed_username_password_dont_match_for').' '.$login_usr);  

                // Reload with 'Login Failed' message
                force_page('core', 'login', 'warning_msg='.$this->smarty->get_template_vars('translate_system_auth_advisory_message_login_failed'));
                exit;
            }

            // If there is more than one matching username and password pair (catches errors)
            elseif ($row['NUM_USERS'] > 1) {    

                // Log activity       
                write_record_to_activity_log($this->smarty->get_template_vars('translate_system_auth_log_message_login_failed_duplicate_username_and_password_for').' '.$login_usr);  

                // Reload with 'Login Failed' message
                force_page('core', 'login', 'warning_msg='.$this->smarty->get_template_vars('translate_system_auth_advisory_message_login_failed'));
                exit;

            // Else if there is a single valid user, set the session variables
            } else {

                // Grab their login ID for tracking purposes (Employee Must be Active)
                $sql = "SELECT EMPLOYEE_ID, EMPLOYEE_TYPE, EMPLOYEE_DISPLAY_NAME
                        FROM ".PRFX."TABLE_EMPLOYEE
                        WHERE EMPLOYEE_STATUS = '1'
                        AND EMPLOYEE_LOGIN=".$this->db->qstr($login_usr);

                $result = $this->db->Execute($sql);
                $row = $result->FetchRow();

                // If we did not get a login ID
                if (!isset($row['EMPLOYEE_ID'])){          

                    // Log activity       
                    write_record_to_activity_log($this->smarty->get_template_vars('translate_system_auth_log_message_login_failed_no_active_login_id_for').' '.$login_usr);

                    // Reload with 'Login Failed' message
                    force_page('core', 'login', 'warning_msg='.$this->smarty->get_template_vars('translate_system_auth_advisory_message_login_failed'));
                    exit;


                // We have a login_id, now add the employee details to the session    
                } else {

                    // Sets the Employees ID number
                    $login_id = $row['EMPLOYEE_ID'];

                    // Set the account type
                    $login_account_type_id = $row['EMPLOYEE_TYPE'];

                    // Set the display name
                    $login_display_name = $row['EMPLOYEE_DISPLAY_NAME'];
                }

            // Store user data in the session - these are used throughout QWcrm and is used for validating the session
            $this->storeAuth($login_usr, $login_pwd, $login_id, $login_account_type_id, $login_display_name);
            
            // Log activity       
            write_record_to_activity_log($this->smarty->get_template_vars('translate_system_auth_log_message_login_successful_for').' '.$login_usr); 
            
            // Reload with 'Login Successful' message
            force_page('core', 'home', 'information_msg='.$this->smarty->get_template_vars('translate_system_auth_advisory_message_login_successful'));
            exit;

            }
        }  
    }
        
    // Store variables in the session
    function storeAuth($login_usr, $login_pwd, $login_id, $login_account_type_id, $login_display_name){
        
        $this->session->set('login_usr',                $login_usr                                      );
        $this->session->set('login_pwd',                $login_pwd                                      );
        $this->session->set('login_id',                 $login_id                                       );        
        $this->session->set('login_account_type_id',    $login_account_type_id                          );
        $this->session->set('login_display_name',       $login_display_name                             );
        
        // This is used to validate session authentication - maybe not use this as makes confirm auth pointless
        $this->session->set('login_hash',               md5($this->secretKey . $login_usr . $login_pwd) );

    } 
    
    // verify the user is logged in by checking session varibles and validating the credentials
    function confirmAuth(){
        
        if (md5($this->secretKey . $this->session->get('login_usr') . $this->session->get('login_pwd')) != $this->session->get('login_hash')){
            $this->logout();            
        } else {
            return true;            
        }
        
    }  
 
    // Logout from the session
    function logout(){
        
        // Log activity       
        write_record_to_activity_log($this->smarty->get_template_vars('translate_system_auth_log_message_logout_successful_for').' '.$this->session->get('login_usr'));
        
        $this->session->del('login_usr');
        $this->session->del('login_pwd');
        $this->session->del('login_hash');        
        $this->session->del('login_id');
        $this->session->del('login_account_type_id');
        $this->session->del('login_display_name');   
        
        // Reload with 'Logout Successful' message
        force_page('core', 'login', 'information_msg='.$this->smarty->get_template_vars('translate_system_auth_advisory_message_logout_successful'));
        exit;
        
    }
   
}