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
        // See if we have values already stored in the session
        if ($this->session->get('login_hash')) {
            $this->confirmAuth();
            return;
        }

        // If this is a fresh login, check $_POST variables
        if (!isset($_POST[LOGIN_USR]) || !isset($_POST[LOGIN_PWD])){
            $this->redirect();
        }

        // md5 encrypt the password if available
        if ($this->md5){
            $login_pwd = md5($_POST[LOGIN_PWD]);
        } else {
            $login_pwd = $_POST[LOGIN_PWD];
        }

        // Escape the variables for the query
        $login_usr = mysql_real_escape_string($_POST[LOGIN_USR]);
        $login_pwd = mysql_real_escape_string($login_pwd);

        // Query to count number of users with this combination
        $sql    = "SELECT COUNT(*) AS num_users FROM ".PRFX."TABLE_EMPLOYEE WHERE EMPLOYEE_STATUS = '1' AND EMPLOYEE_LOGIN=".$this->db->qstr($login_usr) ." AND EMPLOYEE_PASSWD=".$this->db->qstr($login_pwd);
        $result = $this->db->Execute($sql);
        $row    = $result->FetchRow();

        // If there isn't is exactly one entry, redirect
        if ($row['num_users'] != 1) {    
                        
            // Log activity       
            write_record_to_activity_log('Failed Login '.$login_usr);  
        
            // Reload with 'Login Failed' message
            force_page('login.php?error_msg=Login Failed');

        // Else is a valid user; set the session variables
        } else {
            
            // grab their login ID for tracking purposes
            $sql = "SELECT EMPLOYEE_ID  FROM ".PRFX."TABLE_EMPLOYEE WHERE EMPLOYEE_STATUS = '1' AND EMPLOYEE_LOGIN='$login_usr'";
            $result = $this->db->Execute($sql);
            $row = $result->FetchRow();

            // If We did not get a login ID 
            if (!isset($row['EMPLOYEE_ID'])){          
                
                // Log activity       
                write_record_to_activity_log('Failed Login ID For '.$login_usr);
                
                // Reload with 'Login Failed' message
                force_page('login.php?error_msg=Login Failed');
                
            } else {
                $login_id = $row['EMPLOYEE_ID'];
            }

          $this->storeAuth($login_usr, $login_pwd, $COMPANY, $login_id);

        }
    }  

    function storeAuth($login_usr, $login_pwd, $COMPANY, $login_id){
        $this->session->set(LOGIN_USR,  $login_usr  );
        $this->session->set(LOGIN_PWD,  $login_pwd  );
        $this->session->set('login_id', $login_id   );

        // Create a session variable to use to confirm sessions
        $hashKey = md5($this->hashKey . $login_usr . $login_pwd);
        $this->session->set('login_hash', $hashKey);
 
        // Log activity       
        write_record_to_activity_log('Login '.$login_usr);   
        

    }
  
function confirmAuth(){
        $login_usr = $this->session->get(LOGIN_USR);
        $login_pwd = $this->session->get(LOGIN_PWD);
        $hashKey = $this->session->get('login_hash');
        if (md5($this->hashKey . $login_usr . $login_pwd) != $hashKey){
            $this->logout(true);
        }
    }
  
 
    function logout($from){
        
        $login_usr = $this->session->get(LOGIN_USR);
        
        // Log activity       
        write_record_to_activity_log('Log Out '.$login_usr);
        
        $this->session->del(LOGIN_USR);
        $this->session->del(LOGIN_PWD);
        $this->session->del('login_hash');
        $this->redirect($from);
    }
   
    function redirect($from = true){
        if ($from){
          header('Location: ' . $this->redirect . '?from=' . $_SERVER['REQUEST_URI']);
        } else {
          header('Location: ' . $this->redirect);
        }
        exit();
    }
}