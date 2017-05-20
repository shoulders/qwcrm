<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

class Authentication {
    
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
        
        // Create Session
        $this->session          = new Session($this->session_lifetime);
        
        // Has the session been inactive to long (This works in $_SESSION for both non-persistent and persitant sessions)
        if(isset($_SESSION['login_token'])) { $this->sessionTimeOut(); }
        
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
    


    // make the cookie/session persitient
    function makeCookieSessionPersistent() {
        //add cookie setting stuff here
    }


/**
     * Check if the user is required to reset their password.
     *
     * If the user is required to reset their password will be redirected to the page that manage the password reset.
     *
     * @param   string  $option  The option that manage the password reset
     * @param   string  $view    The view that manage the password reset
     * @param   string  $layout  The layout of the view that manage the password reset
     * @param   string  $tasks   Permitted tasks
     *
     * @return  void
     */
    protected function checkUserRequireReset($option, $view, $layout, $tasks)
    {
        if (JFactory::getUser()->get('requireReset', 0))
        {
            $redirect = false;

            /*
             * By default user profile edit page is used.
             * That page allows you to change more than just the password and might not be the desired behavior.
             * This allows a developer to override the page that manage the password reset.
             * (can be configured using the file: configuration.php, or if extended, through the global configuration form)
             */
            $name = $this->getName();

            if ($this->get($name . '_reset_password_override', 0))
            {
                $option = $this->get($name . '_reset_password_option', '');
                $view = $this->get($name . '_reset_password_view', '');
                $layout = $this->get($name . '_reset_password_layout', '');
                $tasks = $this->get($name . '_reset_password_tasks', '');
            }

            $task = $this->input->getCmd('task', '');

            // Check task or option/view/layout
            if (!empty($task))
            {
                $tasks = explode(',', $tasks);

                // Check full task version "option/task"
                if (array_search($this->input->getCmd('option', '') . '/' . $task, $tasks) === false)
                {
                    // Check short task version, must be on the same option of the view
                    if ($this->input->getCmd('option', '') != $option || array_search($task, $tasks) === false)
                    {
                        // Not permitted task
                        $redirect = true;
                    }
                }
            }
            else
            {
                if ($this->input->getCmd('option', '') != $option || $this->input->getCmd('view', '') != $view || $this->input->getCmd('layout', '') != $layout)
                {
                    // Requested a different option/view/layout
                    $redirect = true;
                }
            }

            if ($redirect)
            {
                // Redirect to the profile edit page
                $this->enqueueMessage(JText::_('JGLOBAL_PASSWORD_RESET_REQUIRED'), 'notice');
                $this->redirect(JRoute::_('index.php?option=' . $option . '&view=' . $view . '&layout=' . $layout, false));
            }
        }
    }

/**
     * Login authentication function.
     *
     * Username and encoded password are passed the onUserLogin event which
     * is responsible for the user validation. A successful validation updates
     * the current session record with the user's details.
     *
     * Username and encoded password are sent as credentials (along with other
     * possibilities) to each observer (authentication plugin) for user
     * validation.  Successful validation will update the current session with
     * the user details.
     *
     * @param   array  $credentials  Array('username' => string, 'password' => string)
     * @param   array  $options      Array('remember' => boolean)
     *
     * @return  boolean|JException  True on success, false if failed or silent handling is configured, or a JException object on authentication error.
     *
     * @since   3.2
     */
    public function login($credentials, $options = array())
    {
        // Get the global JAuthentication object.
        $authenticate = JAuthentication::getInstance();
        $response = $authenticate->authenticate($credentials, $options);

        // Import the user plugin group.
        JPluginHelper::importPlugin('user');

        if ($response->status === JAuthentication::STATUS_SUCCESS)
        {
            /*
             * Validate that the user should be able to login (different to being authenticated).
             * This permits authentication plugins blocking the user.
             */
            $authorisations = $authenticate->authorise($response, $options);
            $denied_states = JAuthentication::STATUS_EXPIRED | JAuthentication::STATUS_DENIED;

            foreach ($authorisations as $authorisation)
            {
                if ((int) $authorisation->status & $denied_states)
                {
                    // Trigger onUserAuthorisationFailure Event.
                    $this->triggerEvent('onUserAuthorisationFailure', array((array) $authorisation));

                    // If silent is set, just return false.
                    if (isset($options['silent']) && $options['silent'])
                    {
                        return false;
                    }

                    // Return the error.
                    switch ($authorisation->status)
                    {
                        case JAuthentication::STATUS_EXPIRED:
                            return JError::raiseWarning('102002', JText::_('JLIB_LOGIN_EXPIRED'));

                        case JAuthentication::STATUS_DENIED:
                            return JError::raiseWarning('102003', JText::_('JLIB_LOGIN_DENIED'));

                        default:
                            return JError::raiseWarning('102004', JText::_('JLIB_LOGIN_AUTHORISATION'));
                    }
                }
            }

            // OK, the credentials are authenticated and user is authorised.  Let's fire the onLogin event.
            $results = $this->triggerEvent('onUserLogin', array((array) $response, $options));

            /*
             * If any of the user plugins did not successfully complete the login routine
             * then the whole method fails.
             *
             * Any errors raised should be done in the plugin as this provides the ability
             * to provide much more information about why the routine may have failed.
             */
            $user = JFactory::getUser();

            if ($response->type == 'Cookie')
            {
                $user->set('cookieLogin', true);
            }

            if (in_array(false, $results, true) == false)
            {
                $options['user'] = $user;
                $options['responseType'] = $response->type;

                // The user is successfully logged in. Run the after login events
                $this->triggerEvent('onUserAfterLogin', array($options));
            }

            return true;
        }

        // Trigger onUserLoginFailure Event.
        $this->triggerEvent('onUserLoginFailure', array((array) $response));

        // If silent is set, just return false.
        if (isset($options['silent']) && $options['silent'])
        {
            return false;
        }

        // If status is success, any error will have been raised by the user plugin
        if ($response->status !== JAuthentication::STATUS_SUCCESS)
        {
            JLog::add($response->error_message, JLog::WARNING, 'jerror');
        }

        return false;
    }

    /**
     * Logout authentication function.
     *
     * Passed the current user information to the onUserLogout event and reverts the current
     * session record back to 'anonymous' parameters.
     * If any of the authentication plugins did not successfully complete
     * the logout routine then the whole method fails. Any errors raised
     * should be done in the plugin as this provides the ability to give
     * much more information about why the routine may have failed.
     *
     * @param   integer  $userid   The user to load - Can be an integer or string - If string, it is converted to ID automatically
     * @param   array    $options  Array('clientid' => array of client id's)
     *
     * @return  boolean  True on success
     *
     * @since   3.2
     */
    public function logout($userid = null, $options = array())
    {
        // Get a user object from the JApplication.
        $user = JFactory::getUser($userid);

        // Build the credentials array.
        $parameters['username'] = $user->get('username');
        $parameters['id'] = $user->get('id');

        // Set clientid in the options array if it hasn't been set already and shared sessions are not enabled.
        if (!$this->get('shared_session', '0') && !isset($options['clientid']))
        {
            $options['clientid'] = $this->getClientId();
        }

        // Import the user plugin group.
        JPluginHelper::importPlugin('user');

        // OK, the credentials are built. Lets fire the onLogout event.
        $results = $this->triggerEvent('onUserLogout', array($parameters, $options));

        // Check if any of the plugins failed. If none did, success.
        if (!in_array(false, $results, true))
        {
            $options['username'] = $user->get('username');
            $this->triggerEvent('onUserAfterLogout', array($options));

            return true;
        }

        // Trigger onUserLoginFailure Event.
        $this->triggerEvent('onUserLogoutFailure', array($parameters));

        return false;
    }




}