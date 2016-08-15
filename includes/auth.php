<?php

class Auth {
    var $session;
    var $redirect;
    var $hashKey;
    var $md5;

    function Auth($db, $redirect, $hashKey){
        $this->db       = $db;
        $this->redirect = $redirect;
        $this->hashKey  = $hashKey;        
        $this->session  = new Session();
        $this->login();                     // automatically runs this function
    } 
 
    function login(){
        
        // See if we have values already stored in the session - if hash matchs return to index.php code to process
        if ($this->session->get('login_hash')) {
            $this->confirmAuth();
            return;
        }
        
        // If this is a Fresh Login
        if(isset($_POST['action']) && $_POST['action'] === 'login'){
            
            // and there is no username or password supplied then redirect
                if (!isset($_POST['login_usr']) || !isset($_POST['login_pwd'])){
                    $this->performRedirect();                  
                }

            // Hash the POST'ed password with MD5 and store it as $login_pwd - after this the password is always encrypted
            $login_pwd = md5($_POST['login_pwd']);
           

            // Escape the variables for the query - not currently used - is this needed?         
            //$link = mysqli_connec($DB_HOST, $DB_USER, $DB_PASS);
            //$login_usr = mysqli_real_escape_string($link, $_POST['login_usr']);
            //$login_pwd = mysqli_real_escape_string($link, $login_pwd);

            // This is required unless I use the escaping code above
            $login_usr = $_POST['login_usr'];

            // Query to count number of users with this combination
            $sql    = "SELECT COUNT(*) AS NUM_USERS FROM ".PRFX."TABLE_EMPLOYEE
                        WHERE EMPLOYEE_STATUS = '1'
                        AND EMPLOYEE_LOGIN=".$this->db->qstr($login_usr)."
                        AND EMPLOYEE_PASSWD=".$this->db->qstr($login_pwd);

            $result = $this->db->Execute($sql);
            $row    = $result->FetchRow();

            /* Validate the POSTed username */

            // If is no matching username
            if ($row['NUM_USERS'] == 0) {    

                // Log activity       
                write_record_to_activity_log('Failed Login  - No user name matches what you typed'.$login_usr);  

                // Reload with 'Login Failed' message
                force_page('index.php?error_msg=Login Failed - No user name matches what you typed');
            }

            // If there is more than one matching username
            elseif ($row['NUM_USERS'] > 1) {    

                // Log activity       
                write_record_to_activity_log('Failed Login  - more than 1 matching username so i cannot log you in - see an admin'.$login_usr);  

                // Reload with 'Login Failed' message
                force_page('index.php?error_msg=Login Failed - more than 1 matching username so i cannot log you in - see an admin');   

            // Else if there is a valid user, set the session variables
            } else {

                // Grab their login ID for tracking purposes (Employee Must be Active)
                $sql = "SELECT EMPLOYEE_ID, EMPLOYEE_TYPE, EMPLOYEE_DISPLAY_NAME
                        FROM ".PRFX."TABLE_EMPLOYEE
                        WHERE EMPLOYEE_STATUS = '1'
                        AND EMPLOYEE_LOGIN=".$this->db->qstr($login_usr);

                $result = $this->db->Execute($sql);
                $row = $result->FetchRow();

                // If We did not get a login ID
                if (!isset($row['EMPLOYEE_ID'])){          

                    // Log activity       
                    write_record_to_activity_log('Failed Login ID For - no active login ID found'.$login_usr);

                    // add session destruction here for safty

                    // Reload with 'Login Failed' message
                    force_page('index.php?error_msg=Login Failed - no active login ID found');
                    exit;


                // We got a login_id now add the employee details to the session    
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
        $login_usr  = $this->session->get('login_usr');
        $login_pwd  = $this->session->get('login_pwd');
        $hashKey    = $this->session->get('login_hash');
        
        if (md5($this->hashKey . $login_usr . $login_pwd) != $hashKey){
            $this->logout();            
        } else {
            return true;            
        }        
    }  
 
    function logout(){
        
        // Log activity       
        write_record_to_activity_log('Log Out '.$this->session->get('login_usr'));
        
        $this->session->del('login_usr');
        $this->session->del('login_pwd');
        $this->session->del('login_hash');        
        $this->session->del('login_id');
        $this->session->del('login_account_type');
        $this->session->del('login_display_name');
        
        $this->performRedirect();
    }
   
    function performRedirect($addFromQuery = Null){
      if ($addFromQuery){
            header('Location: ' . $this->redirect . '?from=' . $_SERVER['REQUEST_URI']);
        } else {
            header('Location: ' . $this->redirect);
        }
        exit();       
    }
}