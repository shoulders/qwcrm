<?php
// joomla\includes\framework.php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @copyright Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// General Helpers
require QFRAMEWORK_DIR . 'general/Registry.php';                    // Used to create a register for the class which can be manipulated (set/get/clear) and can be serialised into JSON compatible string for storage in the session
require QFRAMEWORK_DIR . 'general/WebClient.php';                   // Gets the browser details from the session (used in cookie creation)
require QFRAMEWORK_DIR . 'general/Cookie.php';                      // Cookie Object with set and get

// Input
require QFRAMEWORK_DIR . 'input/StringHelper.php';                  // Filtering of strings
require QFRAMEWORK_DIR . 'input/JFilterInput.php';                  // Filtering of strings - a class for filtering input from any data source - used for QCookie and authenticatin
require QFRAMEWORK_DIR . 'input/core.php';                          // Used just for function utf8_strpos() from JFilterInput
require QFRAMEWORK_DIR . 'input/input.php';                         // This is an abstracted input class used to manage retrieving data from the application environment. (i.e. cookie.php)
require QFRAMEWORK_DIR . 'input/cookie.php';                        // Extends input.php with cookie get and set functions to allow manipulation of cookie data via input.php class

// Session
require QFRAMEWORK_DIR . 'session/session.php';                     // Primary Class for managing HTTP sessions
require QFRAMEWORK_DIR . 'session/storage.php';                     // Custom session storage handler for PHP
require QFRAMEWORK_DIR . 'session/storage/none.php';                // File session handler for PHP - Allows to set 'none' for session handler which defaults to standard session files
require QFRAMEWORK_DIR . 'session/storage/database.php';            // Database session storage handler for PHP - can use databse for session controll
require QFRAMEWORK_DIR . 'session/handler/interface.php';           // Interface for managing HTTP sessions - 'index file' no function shere
require QFRAMEWORK_DIR . 'session/handler/native.php';              // Interface for managing HTTP sessions - extends interface.php
require QFRAMEWORK_DIR . 'session/handler/joomla.php';              // Interface for managing HTTP sessions - extends native.php

// Authentication
require QFRAMEWORK_DIR . 'authentication/authentication.php';       // Authentication class, provides an interface for the Joomla authentication system
require QFRAMEWORK_DIR . 'authentication/response.php';             // Authentication response class, provides an object for storing user and error details - this is used to store the responses from the qwcrm.php and remember.php authorisation plugins
require QFRAMEWORK_DIR . 'authentication/methods/qwcrm.php';        // Facilitates standard username and password authorisation
require QFRAMEWORK_DIR . 'authentication/methods/remember.php';     // Facilitates 'Remember me' cookie authorisation

// User
require QFRAMEWORK_DIR . 'user/user.php';                           // User class - Handles all application interaction with a user
require QFRAMEWORK_DIR . 'user/helper.php';                         // This contains password hassing functions etc.. associated with users but used elswhere

// Main Framework class
class QFactory {
    
    // Static
    public static $config       = null;     // Global Config object
    public static $session      = null;     // Global Session object
    public static $auth         = null;     // Global Authetication object
    public static $user         = null;     // Global User object
    public static $database     = null;     // Global Database object
    public static $clientId     = 0;        // The Client identifier. (0 = site, 1 = administrator)
    public static $siteName     = 'site';   // Site Name ('site' or 'administrator' )
    
    // Context Variables
    public $smarty              = null;     // Global Smarty object
    public $conf                = null;

    public function __construct()
    {
        $this->conf     = self::getConfig();
        $this->smarty   = QSmarty::getInstance();
        
        // Enable sessions by default.
        if (is_null($this->conf->get('session')))
        {
            $this->conf->set('session', true);
        }

        // Set the session default name from the config file            - i need to make a random/hashed name here
        if (is_null($this->conf->get('session_name')))
        {
            //$this->conf->set('session_name', $this->getName());
            $this->conf->set('session_name', JUserHelper::genRandomPassword(16));
        }

        // Create the session if a session name is passed.
        if ($this->conf->get('session') !== false)
        {
            $this->loadSession();
        }        
     
        // Try to automatically login - i,e, using the remember me cookie - instigates a silent login if a 'Remember me' cookie is found
        $rememberMe = new PlgAuthenticationCookie;  // this allows silent login using remember me cookie after checking it exists - need to mnake sure it does not logon if already logged on
        $rememberMe->onAfterInitialise();
        unset($rememberMe);
    
    }


/****************** Configuration Object ******************/
    
    
    /**
     * Get a configuration object - this allows the use in non object context
     *
     * Returns the global {@link QConfig} object, only creating it if it doesn't already exist.
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
        $name = 'QConfig' . $namespace;

        // Handle the PHP configuration type.
        if ($type == 'PHP' && class_exists($name))
        {
            // Create the QConfig object
            $config = new $name;

            // Load the configuration values into the registry
            $registry->loadObject($config);
        }

        return $registry;
    }  
    
    
/****************** Session Object ******************/

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
            self::$session = $session;

            return $this;
        }

        // Generate a session name.        
        $name = QFactory::getHash($this->conf->get('session_name', JUserHelper::genRandomPassword(16)));

        // Calculate the session lifetime.
        $lifetime = ($this->conf->get('session_lifetime') ? $this->conf->get('session_lifetime') * 60 : 900);
        
        // another possible option to declare the client(site/administrator)
        //$options['clientid'] 

        // Initialize the options for JSession.
        $options = array(
            'name'   => $name,
            'expire' => $lifetime,
        );
        
        switch (QFactory::getClientId())
        {
            // site
            case 0:
                if ($this->conf->get('force_ssl') == 2)
                {
                    $options['force_ssl'] = true;
                }

                break;

            // administrator
            case 1:
                if ($this->conf->get('force_ssl') >= 1)
                {
                    $options['force_ssl'] = true;
                }

                break;
        }

        //////$this->registerEvent('onAfterSessionStart', array($this, 'afterSessionStart')); - dont think this is needed here

        /*
         * Note: The below code CANNOT change from instantiating a session via QFactory until there is a proper dependency injection container supported
         * by the application. The current default behaviours result in this method being called each time an application class is instantiated.
         * https://github.com/joomla/joomla-cms/issues/12108 explains why things will crash and burn if you ever attempt to make this change
         * without a proper dependency injection container.
         */

        $session = QFactory::getSession($options);        

        // TODO: At some point we need to get away from having session data always in the db.
        //$db = QFactory::getDbo();

        // Remove expired sessions from the database.
        $time = time();

        // Check the session table for stale entries
        $session->removeExpiredSessions();

        // Get the session handler from the configuration.
        $handler = $this->conf->get('session_handler', 'none');

        // Check the session is in the database, if not create it else load it
        if (($handler != 'database' && ($time % 2 || $session->isNew())) || ($handler == 'database' && $session->isNew()))
        {
            $session->checkSession();
        }

        // Set the session object.        
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

        // Config time is in minutes already declared in loadsession()
        //$options['expire'] = ($conf->get('session_lifetime')) ? $conf->get('session_lifetime') * 60 : 900;  // this is already stated in load session

        $sessionHandler = new JSessionHandlerJoomla($options);
        $session        = JSession::getInstance($handler, $options, $sessionHandler);

        if ($session->getState() == 'expired')
        {
            $session->restart();
        }

        return $session;
    }    

    
/****************** Authentication Object ******************/
    
    
     /**
     * Get authentication object.
     *
     * Returns the global {@link JUser} object, only creating it if it doesn't already exist.
     *
     * @param   integer  $id  The user to load - Can be an integer or string - If string, it is converted to ID automatically.
     *
     * @return  JAuthentication object
     *
     * @see     JAuthentication
     * @since   11.1
     */       
    public static function getAuth()
    {        
        if(!self::$auth)
        {
            self::$auth = new JAuthentication;
            
        }
        return self::$auth;
    }
        
    
/****************** User Object ******************/
    
    // Check for data in the session.
    // $temp = JFactory::getApplication()->getUserState('com_config.config.global.data');  - administrator/components/com_config/model/application.php

    // this handles user data stored in the user section of the session data blob

   /**
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
    
    /**                                             - not currently used
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
    
    /**                                             - not currently used
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

/****************** Database Object ******************/
    
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


/****************** Client and Site checks ******************/
    

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

    
/****************** Misc ******************/
    
    
   // joomla\libraries\cms\application\helper.php
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
        return md5(self::getConfig()->get('secret_key') . $seed);
    }      
      
}