<?php

class Auth {
    var $session;
    var $redirect;
    var $hashKey;
    var $md5;

    function Auth($db, $redirect, $hashKey, $md5 = true){
        $this->db       = $db;
        $this->redirect = $redirect;
        $this->hashKey  = $hashKey;
        $this->md5      = $md5;
        $this->session  = new Session();
        $this->login();
    } 
 
    function login(){
        
        // See if we have values already stored in the session - if hash matchs return to index.php code to process
        if ($this->session->get('login_hash')) {
            $this->confirmAuth();
            return;
        }

        // If this is a fresh login, check $_POST variables - will require suername and password to continue
        if (!isset($_POST['login_usr']) || !isset($_POST['login_pwd'])){
            $this->redirect();
        }

        // Calculate the md5 hash of the POST'ed password and stores it as $login_pwd
        if ($this->md5){
            $login_pwd = md5($_POST['login_pwd']);
        } else {
            $login_pwd = $_POST['login_pwd'];
        }

        // Escape the variables for the query - not currently used
        // is this needed?
        //$login_usr = mysqli_real_escape_string($link, $_POST['login_usr']);
        //$login_pwd = mysqli_real_escape_string($link, $login_pwd);
        $login_usr = mysql_real_escape_string($_POST['login_usr']);
        $login_pwd = mysql_real_escape_string($login_pwd);


        // Query to count number of users with this combination
        $sql    = "SELECT COUNT(*) AS num_users FROM ".PRFX."TABLE_EMPLOYEE
                    WHERE EMPLOYEE_STATUS = '1'
                    AND EMPLOYEE_LOGIN=".$this->db->qstr($login_usr)."
                    AND EMPLOYEE_PASSWD=".$this->db->qstr($login_pwd);
        
        $result = $this->db->Execute($sql);
        $row    = $result->FetchRow();

        // If there isn't is exactly one entry, redirect
        if ($row['num_users'] != 1) {    
                        
            // Log activity       
            write_record_to_activity_log('Failed Login  - not exactly 1 entry'.$login_usr);  
        
            // Reload with 'Login Failed' message
            force_page('login.php?error_msg=Login Failed - not exactly 1 entry');

        // Else if there is a valid user, set the session variables
        } else {
            
            // Grab their login ID for tracking purposes (Employee Must be Active)
            $sql = "SELECT EMPLOYEE_ID, EMPLOYEE_TYPE, EMPLOYEE_DISPLAY_NAME FROM ".PRFX."TABLE_EMPLOYEE
                    WHERE EMPLOYEE_STATUS = '1'
                    AND EMPLOYEE_LOGIN=".$this->db->qstr($login_usr);
            $result = $this->db->Execute($sql);
            $row = $result->FetchRow();

            // If We did not get a login ID                      // the above only grabs the ID not the other employee things
            if (!isset($row['EMPLOYEE_ID'])){          
                
                // Log activity       
                write_record_to_activity_log('Failed Login ID For - - no login ID'.$login_usr);
                
                // add session destruction here for safty
                
                // Reload with 'Login Failed' message
                force_page('login.php?error_msg=Login Failed - no login username');
                
            } else {
                // Sets the Employees ID number
                $login_id = $row['EMPLOYEE_ID'];
                
                // Set the account type
                $login_account_type = $row['EMPLOYEE_TYPE'];
                
                // Set the display name
                $login_display_name = $row['EMPLOYEE_DISPLAY_NAME'];
            }

          $this->storeAuth($login_usr, $login_pwd, $login_id, $login_account_type, $login_display_name);

        }
    }  

    function storeAuth($login_usr, $login_pwd, $login_id, $login_account_type, $login_display_name){
        
        // Store Variables in $_SESSION
        $this->session->set('login_usr',            $login_usr          );
        $this->session->set('login_pwd',            $login_pwd          );
        $this->session->set('login_id',             $login_id           );
        
        $this->session->set('login_account_type',   $login_account_type );
        $this->session->set('login_display_name',   $login_display_name );

        // Create a session variable to use to confirm sessions
        $hashKey = md5($this->hashKey . $login_usr . $login_pwd);
        $this->session->set('login_hash', $hashKey);
 
        // Log activity       
        write_record_to_activity_log('Login '.$login_usr); 

    } 
    
    function confirmAuth(){
        $login_usr = $this->session->get('login_usr');
        $login_pwd = $this->session->get('login_pwd');
        $hashKey = $this->session->get('login_hash');
        if (md5($this->hashKey . $login_usr . $login_pwd) != $hashKey){
            $this->logout(true);
        }
        
    }  
 
    function logout(){
        
        $login_usr = $this->session->get('login_usr');
        
        // Log activity       
        write_record_to_activity_log('Log Out '.$login_usr);
        
        $this->session->del('login_usr');
        $this->session->del('login_pwd');
        $this->session->del('login_hash');
        
        $this->session->del('login_id');
        $this->session->del('login_account_type');
        $this->session->del('login_display_name');
        
        $this->redirect();
    }
   
    function redirect($addFromQuery = Null){
        if ($addFromQuery){
            header('Location: ' . $this->redirect . '?from=' . $_SERVER['REQUEST_URI']);
        } else {
            header('Location: ' . $this->redirect);
        }
        exit();
    }
}