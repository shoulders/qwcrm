<?php
// D:\websites\htdocs\quantumwarp.com\includes\framework.php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// include class files
require INCLUDES_DIR . 'framework/general/config.php';                          // This gets the standard config settings ONLY
require INCLUDES_DIR . 'framework/general/registry.php';                // This class is used to create/edit/read a JSON compatible register than can be stored in the database i.e. user paremeters or used within the various classes for data manipulation (set/get/ckear)
require INCLUDES_DIR . 'framework/general/Webclient.php';        // this libary gets the browser details from the session (used in cookie creation)

require INCLUDES_DIR . 'framework/input/StringHelper.php';
require INCLUDES_DIR . 'framework/input/JFilterInput.php';            // this is used to clean/filter strings to suitable formats i.e. ALUM = alpha numeric - used for QCookie and authenticatin
require INCLUDES_DIR . 'framework/input/core.php';                      // this is weired little fiel just for function utf8_strpos() from JFilterInput

//
//require INCLUDES_DIR . 'framework/session/session.php';                 // This handeles the user session (mainly php but utilises cookes) - update thhis description
//require INCLUDES_DIR . 'framework/session/cookiesession.php';                  // This handles the basic cookie manipulation
//require INCLUDES_DIR . 'framework/cookie/Input.php'; only used get so i have put that in cookie

require INCLUDES_DIR . 'framework/general/Cookie.php'; // cookie object with set and get extends Input.php

require INCLUDES_DIR . 'framework/input/input.php';
require INCLUDES_DIR . 'framework/input/cookie.php';

// session files
require INCLUDES_DIR . 'framework/session/storage.php';
require INCLUDES_DIR . 'framework/session/session.php';
require INCLUDES_DIR . 'framework/session/storage/database.php';
require INCLUDES_DIR . 'framework/session/handler/interface.php';
require INCLUDES_DIR . 'framework/session/handler/native.php';
require INCLUDES_DIR . 'framework/session/handler/joomla.php';


require INCLUDES_DIR . 'framework/authentication/authentication.php';   // This is used to facilitate the authorization process
require INCLUDES_DIR . 'framework/authentication/response.php';         // this is used to store the responses from the qwcrm.php and cookie.php authorisation plugins


require INCLUDES_DIR . 'framework/authentication/methods/cookie.php';   // This allos the user to be be authenticated by a coookie (persitent session)
require INCLUDES_DIR . 'framework/authentication/methods/qwcrm.php';    // This is the standard username and password authorisation
//require INCLUDES_DIR . 'framework/authentication/methods/remember.php'; // instigates a silent login if a remember_me cookie is found


require INCLUDES_DIR . 'framework/user/user.php';                       // This is the object/thing that hols the user data objectr
require INCLUDES_DIR . 'framework/user/helper.php';                     // This contains password hassing functions etc..

//require INCLUDES_DIR . 'framework/session/phpsession.php';  


class JFactory {
    
    /**                               - temp
     * Global application object
     *
     * @var    JApplicationCms
     * @since  11.1
     * 
     * PHP 5 introduces abstract classes and methods. Classes defined as abstract may not be instantiated, and any class that contains at least one abstract method must also be abstract. Methods defined as abstract simply declare the method's signature - they cannot define the implementation. 
     * http://php.net/manual/en/language.oop5.abstract.php
     */
    public static $application = null;

    
    // Static
    public static $user         = null; 
    public static $config       = null;     // Global config object
    public static $clientId     = 0;        // The client identifier. (0 = site, 1 = administrator)
    public static $siteName     = 'site';   // Site name ('site' or 'administrator' )
    public static $database     = null;     // Global Databse object
    public static $auth         = null;     // Global authetication object
    public static $session      = null;
    
    // Context Variables
    public $db                  = null;     // Global database object   
    public $smarty              = null;     // Global smarty object
    public $conf                = null;

            
    public function __construct()
    {
        $this->conf     = self::getConfig();
        //$this->db       = self::getDbo();
        global $smarty;
        $this->smarty   = $smarty;
        
        // start/load the session
        //self::getSession();       
        
        // Try to automatically login - i,e, using the remember me cookie - instigates a silent login if a remembe_me cookie is found
        /*$rememberMePlg = new PlgSystemRemember;
        $rememberMePlg->onAfterInitialise();
        unset($rememberMePlg);*/
        $rememberMe = new PlgAuthenticationCookie;  // this allows silent login using remember me cookie after checking it exists
        $rememberMe->onAfterInitialise();
        unset($rememberMe);
        
        // Enable sessions by default.
        if (is_null($this->conf->get('session')))
        {
            $this->conf->set('session', true);
        }

        // Set the session default name from the config file            - i need to make a random/hashed name here
        if (is_null($this->conf->get('session_name')))
        {
            //$this->conf->set('session_name', $this->getName());
            $this->conf->set('session_name', session_name());
        }

        // Create the session if a session name is passed.
        if ($this->conf->get('session') !== false)
        {
            $this->loadSession();
        } 
        
        // if you dont logon the user and registry are not loaded
        $turnip = '555';
    }

 /**************************************login/authentication****************************************************/
    
    // These are wrappers so i can move the functions
    /**
     * Login authentication function.
     *
     */
    public function login($credentials, $options = array())
    {   
        // If username or password is missing, redirect
        if (!isset($credentials['username']) || $credentials['username'] == '' || !isset($credentials['password']) || $credentials['password'] == ''){
            force_page('core', 'login', 'warning_msg='.$this->smarty->getTemplateVars('translate_system_auth_advisory_message_username_or_password_missing'));
            exit;
        } 
            
        $auth = self::getAuth();
        
        if($auth->login($credentials, $options)) {
            
            /* Login true */
            
            //$user = self::getUser();

            // Log activity       
            write_record_to_activity_log($this->smarty->getTemplateVars('translate_system_auth_log_message_login_successful_for').' '.$user->login_usr); 

            // Reload with 'Login Successful' message
            //force_page('core', 'home', 'information_msg='.$this->smarty->getTemplateVars('translate_system_auth_advisory_message_login_successful'));            
            //exit;
            
            return true;
        
        
        } else {
            
            /* Login failed */
            
            // Reload with 'Login Failed' message
            force_page('core', 'login', 'warning_msg='.$this->smarty->getTemplateVars('translate_system_auth_advisory_message_login_failed'));
            exit;
            //return false;
        }
    }

    
    /**
     * Login authentication function. - could add silent to logout?
     *
     */
    public function logout($userid = null, $options = array())
    {    
                
        $user = self::getUser();  
        $auth = self::getAuth();

        
        // these all work
        //$auth->logout();
        //$auth->logout($userid = null, $options = array());
        //$auth->logout($user->id, $options);
        //$auth->logout(1, array());
        
        $auth->logout();
        
        // Log activity       
        write_record_to_activity_log($this->smarty->getTemplateVars('translate_system_auth_log_message_logout_successful_for').' '.$user->get('login_usr'));        
                
        // Reload with 'Logout Successful' message        
        force_page('core', 'login', 'information_msg='.$this->smarty->getTemplateVars('translate_system_auth_advisory_message_logout_successful'), 'get');
        exit;
    } 

 /************************************** session ****************************************************/

    /**
     * Allows the application to load a custom or default session.
     *
     * The logic and options for creating this object are adequately generic for default cases
     * but for many applications it will make sense to override this method and create a session,
     * if required, based on more specific needs.
     *
     * @param   JSession  $session  An optional session object. If omitted, the session is created.
     *
     * @return  JApplicationCms  This method is chainable.
     *
     * @since   3.2
     */
    public function loadSession(JSession $session = null)
    {
        if ($session !== null)
        {
            $this->session = $session;

            return $this;
        }

        // Generate a session name.
        //$name = JApplicationHelper::getHash($this->get('session_name', get_class($this)));
        $name = JFactory::getHash($this->conf->get('session_name', get_class($this)));

        // Calculate the session lifetime.
        $lifetime = ($this->conf->get('lifetime') ? $this->conf->get('lifetime') * 60 : 900);

        // Initialize the options for JSession.
        $options = array(
            'name'   => $name,
            'expire' => $lifetime,
        );

        switch (JFactory::getClientId())
        {
            case 0:
                if ($this->conf->get('force_ssl') == 2)
                {
                    $options['force_ssl'] = true;
                }

                break;

            case 1:
                if ($this->conf->get('force_ssl') >= 1)
                {
                    $options['force_ssl'] = true;
                }

                break;
        }

        //////$this->registerEvent('onAfterSessionStart', array($this, 'afterSessionStart'));

        /*
         * Note: The below code CANNOT change from instantiating a session via JFactory until there is a proper dependency injection container supported
         * by the application. The current default behaviours result in this method being called each time an application class is instantiated.
         * https://github.com/joomla/joomla-cms/issues/12108 explains why things will crash and burn if you ever attempt to make this change
         * without a proper dependency injection container.
         */

        $session = JFactory::getSession($options);
        //$session->initialise($this->input, $this->dispatcher);

        // TODO: At some point we need to get away from having session data always in the db.
        $db = JFactory::getDbo();

        // Remove expired sessions from the database.
        $time = time();

        // Check the session table for stale entries
        $session->removeExpiredSessions();

        // Get the session handler from the configuration.
        $handler = $this->conf->get('session_handler', 'none');

        if (($handler != 'database' && ($time % 2 || $session->isNew())) || ($handler == 'database' && $session->isNew()))
        {
            $session->checkSession();
        }

        // Set the session object.
        //$this->session = $session;
        self::$session = $session;

        return $this;
    }   

    
   /**
     * Get a session object.
     *
     * Returns the global {@link JSession} object, only creating it if it doesn't already exist.
     *
     * @param   array  $options  An array containing session options
     *
     * @return  JSession object
     *
     * @see     JSession
     * @since   11.1
     */
    public static function getSession(array $options = array())
    {
        if (!self::$session)
        {
            self::$session = self::createSession($options);
        }

        return self::$session;
    }
  
    /**
     * Create a session object
     *
     * @param   array  $options  An array containing session options
     *
     * @return  JSession object
     *
     * @since   11.1
     */
    protected static function createSession(array $options = array())
    {
        // Get the Joomla configuration settings
        $conf    = self::getConfig();
        $handler = $conf->get('session_handler', 'none');

        // Config time is in minutes
        $options['expire'] = ($conf->get('lifetime')) ? $conf->get('lifetime') * 60 : 900;  // this is already stated in load session

        $sessionHandler = new JSessionHandlerJoomla($options);
        $session        = JSession::getInstance($handler, $options, $sessionHandler);

        if ($session->getState() == 'expired')
        {
            $session->restart();
        }

        return $session;
    }    
    
    
   /**                             - mine
     * Get a session object.
     *
     * Returns the global {@link JDatabaseDriver} object, only creating it if it doesn't already exist.
     *
     * @return  JDatabaseDriver
     *
     * @see     JDatabaseDriver
     * @since   11.1
     *
    public static function getSession()
    {
        if (!self::$session)
        {
            self::$session = new JSession;
        }

        return self::$session;
    }     

    /**
     * 
     * there is doubling up on this code, is it needed ie.handler
     * 
     * Create a session object
     *
     * @param   array  $options  An array containing session options
     *
     * @return  JSession object
     *
     * @since   11.1
     *
    protected static function createSession(array $options = array())
    {
        // Get the Joomla configuration settings
        $conf    = new QConfig;        
        //$handler = $conf->get('session_handler', 'database');
        $handler = $conf->get('session_handler', 'none');   

        // Config time is in minutes - this is already handles in get session
        //$options['expire'] = ($conf->get('session_lifetime')) ? $conf->get('session_lifetime') * 60 : 900;

        // The session handler needs a JInput object, we can inject it without having a hard dependency to an application instance
        //$input = self::$application ? self::getApplication()->input : new JInput;

        //$sessionHandler = new JSessionHandlerJoomla($options);
        //$sessionHandler->input = $input;

        $session = JSession::getInstance($handler, $options);
        //getInstance($store, $options, JSessionHandlerInterface $handlerInterface = null)  // session handler removed
        
        if ($session->getState() == 'expired')
        {
            $session->restart();
        }

        return $session;
    }*/
    
    

    

    
/********************************** User Object ********************************************/


   /**
    * 
    *                                   - this loads the user object from the session into an instance in the user object
    * 
     * Get a user object.
     *
     * Returns the global {@link JUser} object, only creating it if it doesn't already exist.
     *
     * @param   integer  $id  The user to load - Can be an integer or string - If string, it is converted to ID automatically.
     *
     * @return  JUser object
     *
     * @see     JUser
     * @since   11.1
     */
    public static function getUser($id = null)
    {
        $instance = self::getSession()->get('user');        

        if (is_null($id))
        {
            if (!($instance instanceof JUser))
            {
                $instance = JUser::getInstance();
            }
        }
        // Check if we have a string as the id or if the numeric id is the current instance
        elseif (!($instance instanceof JUser) || is_string($id) || $instance->id !== $id)
        {
            $instance = JUser::getInstance($id);
        }

        return $instance;
    }
    
    /**
     * Gets a user state.
     *
     * @param   string  $key      The path of the state.
     * @param   mixed   $default  Optional default value, returned if the internal value is null.
     *
     * @return  mixed  The user state or null.
     *
     * @since   3.2
     */
    public function getUserState($key, $default = null)
    {
        $session = self::getSession();
        $registry = $session->get('registry');

        if (!is_null($registry))
        {
            return $registry->get($key, $default);
        }

        return $default;
    }
    
    /**                                             - dont know where this is used - see them notes
     * Sets the value of a user state variable.
     *
     * @param   string  $key    The path of the state.
     * @param   mixed   $value  The value of the variable.
     *
     * @return  mixed  The previous state, if one existed.
     *
     * @since   3.2
     */
    public function setUserState($key, $value)
    {
        $session = self::getSession();
        $registry = $session->get('registry');

        if (!is_null($registry))
        {
            return $registry->set($key, $value);
        }

        return;
    }
    
    /**                                             - dont know where this is used - see them notes
     * Gets the value of a user state variable.
     *
     * @param   string  $key      The key of the user state variable.
     * @param   string  $request  The name of the variable passed in a request.
     * @param   string  $default  The default value for the variable if not found. Optional.
     * @param   string  $type     Filter for the variable, for valid values see {@link JFilterInput::clean()}. Optional.
     *
     * @return  mixed  The request user state.
     *
     * @since   3.2
     */
    public function getUserStateFromRequest($key, $request, $default = null, $type = 'none')
    {
        $cur_state = $this->getUserState($key, $default);
        $new_state = $this->input->get($request, null, $type);

        if ($new_state === null)
        {
            return $cur_state;
        }

        // Save the new value only if it was set in this request.
        $this->setUserState($key, $new_state);

        return $new_state;
    }  


    
// change get user model back to not overriding the whole thing when an ID is set ? this is neater
    
    
    /**
     * Get a user object.
     *
     * Returns the global {@link JUser} object, only creating it if it doesn't already exist.
     *
     * @param   integer  $id  The user to load - Can be an integer or string - If string, it is converted to ID automatically.
     *
     * @return  JUser object
     *
     * @see     JUser
     * @since   11.1
     *      
    public static function getUser($id = null)
    {    
        
        if(!self::$user)
        {
            self::$user = new JUser($id);            
        }
        return self::$user;
        
        /*if(self::$user && $id !== null)
        {
            self::$user = new JUser($id);
            return self::$user;
        }
        
        if(self::$user && $id === null)
        {
            return self::$user;
        }
        
        if(!self::$user)
        {
            self::$user = new JUser($id);
            return self::$user;
        }**

    }*/

    
/********************************** Other Object Grabbers ********************************************/
    
    /**
     * Get a database object.
     *
     * Returns the global {@link JDatabaseDriver} object, only creating it if it doesn't already exist.
     *
     * @return  JDatabaseDriver
     *
     * @see     JDatabaseDriver
     * @since   11.1
     */
    public static function getDbo()
    {
        if (!self::$database)
        {
            self::$database = self::createDbo();
        }

        return self::$database;
    }       
    
    
    /**
     * Create an database object
     *
     * @return  JDatabaseDriver
     *
     * @see     JDatabaseDriver
     * @since   11.1
     */
    protected static function createDbo()
    {
          
        $conf = self::getConfig();       
        
        // create adodb database connection
        $db = ADONewConnection('mysqli');
        $db->Connect($conf->get('db_host'), $conf->get('db_user'), $conf->get('db_pass'), $conf->get('db_name'));
                
        return $db;        
    
    }
  
    
    /**
     * Get a configuration object - this allows the use in non object context
     *
     * Returns the global {@link GConfig} object, only creating it if it doesn't already exist.
     *
     * @param   string  $file       The path to the configuration file
     * @param   string  $type       The type of the configuration file
     * @param   string  $namespace  The namespace of the configuration file
     *
     * @return  Registry
     *
     * @see     Registry
     * @since   11.1
     */
    public static function getConfig($file = null, $type = 'PHP', $namespace = '')
    {
        if (!self::$config)
        {
            if ($file === null)
            {
                $file = 'configuration.php';
            }

            self::$config = self::createConfig($file, $type, $namespace);            
            
        }

        return self::$config;
    }

    /**
     * Create a configuration object
     *
     * @param   string  $file       The path to the configuration file.
     * @param   string  $type       The type of the configuration file.
     * @param   string  $namespace  The namespace of the configuration file.
     *
     * @return  Registry
     *
     * @see     Registry
     * @since   11.1
     */
    protected static function createConfig($file, $type = 'PHP', $namespace = '')
    {
        if (is_file($file))
        {
            include_once $file;
        }

        // Create the registry with a default namespace of config
        $registry = new Registry;

        // Sanitize the namespace.
        $namespace = ucfirst((string) preg_replace('/[^A-Z_]/i', '', $namespace));

        // Build the config name.
        $name = 'GConfig' . $namespace;

        // Handle the PHP configuration type.
        if ($type == 'PHP' && class_exists($name))
        {
            // Create the GConfig object
            $config = new $name;

            // Load the configuration values into the registry
            $registry->loadObject($config);
        }

        return $registry;
    }  
    


    
     /**
     * Get authentication object.
     *
     * Returns the global {@link JUser} object, only creating it if it doesn't already exist.
     *
     * @param   integer  $id  The user to load - Can be an integer or string - If string, it is converted to ID automatically.
     *
     * @return  QAuthentication object
     *
     * @see     QAuthentication
     * @since   11.1
     */       
    public static function getAuth()
    {        
        if(!self::$auth)
        {
            self::$auth = new QAuthentication;
            
        }
        return self::$auth;
    }
    

    /**                                 - temp
     * Get an application object.
     *
     * Returns the global {@link JApplicationCms} object, only creating it if it doesn't already exist.
     *
     * @param   mixed   $id      A client identifier or name.
     * @param   array   $config  An optional associative array of configuration settings.
     * @param   string  $prefix  Application prefix
     *
     * @return  JApplicationCms object
     *
     * @see     JApplication
     * @since   11.1
     * @throws  Exception
     *
    public static function getApplication($id = null, array $config = array(), $prefix = 'J')
    {
        if (!self::$application)
        {
            if (!$id)
            {
                throw new Exception('Application Instantiation Error', 500);
            }

            self::$application = JApplicationCms::getInstance($id);
        }

        return self::$application;
    }*/

/********************************** Client and Site checks ********************************************/

    /**
     * Gets the client id of the current running application.
     *
     * @return  integer  A client identifier.
     *
     * @since   3.2
     */
    public static function getClientId()
    {
        return self::$clientId;
    }

    /**
     * Gets the name of the current running application.
     *
     * @return  string  The name of the application.
     *
     * @since   3.2
     */
    public static function getSiteName()
    {
        return self::$siteName;
    }

    /**
     * Check the client interface by name.
     *
     * @param   string  $identifier  String identifier for the application interface
     *
     * @return  boolean  True if this application is of the given type client interface.
     *
     * @since   3.7.0
     */
    public static function isClient($identifier)
    {
        return self::getSiteName() === $identifier;
    }

/********************************** Misc ********************************************/
    
   // D:\websites\htdocs\quantumwarp.com\libraries\cms\application\helper.php
   /**
     * Provides a secure hash based on a seed
     *
     * @param   string  $seed  Seed string.
     *
     * @return  string  A secure hash
     *
     * @since   3.2
     */
    public static function getHash($seed)
    {        
        //return md5($this->config->get('secretKey') . $seed);
        return md5(self::getConfig()->get('secretKey') . $seed);
    }      
    
}

