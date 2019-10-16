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

// QWcrm
require QFRAMEWORK_DIR . 'qwcrm/defines.php';                      // Load System Constants
require QFRAMEWORK_DIR . 'qwcrm/error.php';                        // Configure PHP error reporting
require QFRAMEWORK_DIR . 'qwcrm/include.php';                      // Load System Include
require QFRAMEWORK_DIR . 'qwcrm/security.php';                     // Load QWcrm Security including mandatory security code
require QFRAMEWORK_DIR . 'qwcrm/mpdf.php';                         // Load mPDF functions
require QFRAMEWORK_DIR . 'qwcrm/email.php';                        // Load email transport
require QFRAMEWORK_DIR . 'qwcrm/variables.php';                    // Configure variables to be used by QWcrm
require QFRAMEWORK_DIR . 'qwcrm/router.php';                       // Route the page request
require QFRAMEWORK_DIR . 'qwcrm/buildpage.php';                    // Build the page content payload

// Redue the descriptions of these files

// Misc (Joomla)
require QFRAMEWORK_DIR . 'joomla/libraries/vendor/joomla/registry/src/Registry.php';            // Used to create a register for the class which can be manipulated (set/get/clear) and can be serialised into JSON compatible string for storage in the session
require QFRAMEWORK_DIR . 'joomla/libraries/vendor/joomla/application/src/Web/WebClient.php';    // Gets the browser details from the session (used in cookie creation)

// Input (Joomla)
require QFRAMEWORK_DIR . 'joomla/libraries/vendor/joomla/input/src/Input.php';                  // Joomla! Input Base Class                                         - Part of the Joomla Framework Input Package
require QFRAMEWORK_DIR . 'joomla/libraries/vendor/joomla/string/src/phputf8/native/core.php';   // Used just for function utf8_strpos() from JFilterInput           - Part of the Joomla Framework String Package
require QFRAMEWORK_DIR . 'joomla/libraries/vendor/joomla/string/src/StringHelper.php';          // Filtering of strings                                             - Part of the Joomla Framework String Package
require QFRAMEWORK_DIR . 'joomla/libraries/vendor/joomla/filter/src/InputFilter.php';           // InputFilter is a class for filtering input from any data source  - Part of the Joomla Framework String Package
require QFRAMEWORK_DIR . 'joomla/libraries/src/Filter/InputFilter.php';                         // A class for filtering input from any data source - used for QCookie and authentication
require QFRAMEWORK_DIR . 'joomla/libraries/src/Input/Input.php';                                // Joomla! Input Base Class - This is an abstracted input class used to manage retrieving data from the application environment.
require QFRAMEWORK_DIR . 'joomla/libraries/src/Input/Cookie.php';                               // Cookie Object with set and get
require QFRAMEWORK_DIR . 'joomla/libraries/fof/input/jinput/input.php';                         // This is an abstracted input class used to manage retrieving data from the application environment. (i.e. cookie.php)
require QFRAMEWORK_DIR . 'joomla/libraries/fof/input/jinput/cookie.php';                        // Extends input.php with cookie get and set functions to allow manipulation of cookie data via input.php class

// Crypto (Joomla)
require QFRAMEWORK_DIR . 'joomla/libraries/src/Crypt/Crypt.php';
class_alias('\Joomla\CMS\Crypt\Crypt', '\JCrypt');     // Joomla uses an alias of 'Crypt'

// Session (Joomla)
require QFRAMEWORK_DIR . 'joomla/libraries/src/Session/Session.php';                            // Primary Class for managing HTTP sessions
require QFRAMEWORK_DIR . 'joomla/libraries/joomla/session/handler/interface.php';               // Interface for managing HTTP sessions - 'index file' no function shere
require QFRAMEWORK_DIR . 'joomla/libraries/joomla/session/handler/native.php';                  // Interface for managing HTTP sessions - extends interface.php
require QFRAMEWORK_DIR . 'joomla/libraries/joomla/session/handler/joomla.php';                  // Interface for managing HTTP sessions - extends native.php
require QFRAMEWORK_DIR . 'joomla/libraries/joomla/session/storage.php';                         // Custom session storage handler for PHP
require QFRAMEWORK_DIR . 'joomla/libraries/joomla/session/storage/none.php';                    // File session handler for PHP - Allows to set 'none' for session handler which defaults to standard session files
require QFRAMEWORK_DIR . 'joomla/libraries/joomla/session/storage/database.php';                // Database session storage handler for PHP - can use databse for session control

// Authentication (Joomla)
require QFRAMEWORK_DIR . 'joomla/plugins/system/remember/remember.php'; 
require QFRAMEWORK_DIR . 'joomla/plugins/authentication/cookie/cookie.php';                     // Facilitates 'Remember me' cookie authorisation
require QFRAMEWORK_DIR . 'joomla/plugins/authentication/joomla/joomla.php';                     // Facilitates standard username and password authorisation
require QFRAMEWORK_DIR . 'joomla/libraries/src/Authentication/AuthenticationResponse.php';      // Authentication response class, provides an object for storing user and error details - this is used to store the responses from the qwcrm.php and remember.php authorisation plugins
require QFRAMEWORK_DIR . 'joomla/libraries/src/Authentication/Authentication.php';              // Authentication class, provides an interface for the Joomla authentication system

// User (Joomla)
require QFRAMEWORK_DIR . 'joomla/libraries/src/User/User.php';                                  // User class - Handles all application interaction with a user
require QFRAMEWORK_DIR . 'joomla/libraries/src/User/UserHelper.php';                            // This contains password hassing functions etc.. associated with users but used elswhere
require QFRAMEWORK_DIR . 'joomla/libraries/src/User/UserWrapper.php';                           // Wrapper class for UserHelper
require QFRAMEWORK_DIR . 'joomla/plugins/user/joomla/joomla.php';                               // Basic User Objects interactions (login/logout) - class PlgUserJoomla extends JPlugin

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
    public static $smarty       = null;     // Global Smarty object

    // Context Variables    
    public $conf                = null;

    public function __construct()
    {
               
        $this->conf     = self::getConfig();        
        
        // Load/Start/Create the session
        $this->loadSession();                
     
        // Try to automatically login - i.e. using the 'Remember me' feature, a silent login is instigated if a 'Remember me' cookie is found
        $PlgSystemRemember = new PlgSystemRemember;  // This allows silent login using 'Remember me' cookie after checking it exists - need to make sure it does not re-logon if already logged on
        $PlgSystemRemember->onAfterInitialise();
        unset($PlgSystemRemember);        
    
    }

/****************** Load QWcrm enviroment, files, variables and dependencies ******************/
    
    /**
     * Load all of the includes, settings and variables for QWcrm
     *
     */    
    public static function loadQwcrm(&$VAR)
    {                
        load_defines();                                                     // Load System Constants
        force_ssl(self::getConfig()->get('force_ssl'));                     // Redirect to SSL (if enabled)
        configure_php_error_reporting();                                    // Configure PHP error reporting
        require(VENDOR_DIR.'autoload.php');                                 // Load dependencies via composer
        load_whoops(self::getConfig()->get('error_handler_whoops'));        // Whoops Error Handler - Here so it can load ASAP (has to be after vendor)        
        load_language();                                                    // Load Language  (now in include)
        verify_qwcrm_install_state($VAR);                                   // Verify Installation state (install/migrate/upgrade/complete)
        load_system_variables($VAR);                                        // Load the system variables

        return;
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
        $registry = new Joomla\Registry\Registry;

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
     * @param   \JSession  $session  An optional session object. If omitted, the session is created.
     *
     * @return  CMSApplication  This method is chainable.
     *
     * @since   3.2
     *
     * From Joomla 3.9.8 libraries/src/Application/CMSApplication.php
     * This loads/starts a session with defined options
     * This 'Wrapper' can be changed to use any options you wish for the session
     */
    public function loadSession(\JSession $session = null)
    {
        if ($session !== null)
        {
            $this->session = $session;

            return $this;
        }

        /////$this->registerEvent('onAfterSessionStart', array($this, 'afterSessionStart')); - dont think this is needed here for QWcrm

        /*
         * Note: The below code CANNOT change from instantiating a session via \JFactory until there is a proper dependency injection container supported
         * by the application. The current default behaviours result in this method being called each time an application class is instantiated.
         * https://github.com/joomla/joomla-cms/issues/12108 explains why things will crash and burn if you ever attempt to make this change
         * without a proper dependency injection container.
         */
         
        // Generate a session name. - $name = self::getHash($this->conf->get('session_name', \Joomla\CMS\User\UserHelper::genRandomPassword(16)));
        // Calculate the session lifetime. - $lifetime = $this->conf->get('session_lifetime') ? $this->conf->get('session_lifetime') * 60 : 900;        
        //$options['clientid'] - another possible option to declare the client(site/administrator)
         
        // This actually starts the session with the options defined in the array
        $session = self::getSession(
            array(
                'name'      => self::getHash($this->conf->get('session_name', get_class($this))),
                'expire'    => $this->conf->get('session_lifetime') ? $this->conf->get('session_lifetime') * 60 : 900,
                'force_ssl' => self::isHttpsForced(),
            )
        );

        /////$session->initialise($this->input, $this->dispatcher);
        
        // Get the session handler from the configuration.
        $handler = $this->conf->get('session_handler', 'none');

        /*
         * Check for extra session metadata when:
         *
         * 1) The database handler is in use and the session is new
         * 2) The database handler is not in use and the time is an even numbered second or the session is new
         */
        if (($handler !== 'database' && (time() % 2 || $session->isNew())) || ($handler === 'database' && $session->isNew()))
        {
            $session->checkSession();
        }

        // Set the session object.
        self::$session = $session;
        
        // Check the session table for stale entries (replaces above)
        $this->removeExpiredSessions();

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
        $session        = Joomla\CMS\Session\Session::getInstance($handler, $options, $sessionHandler);

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
        self::$auth = new \Joomla\CMS\Authentication\Authentication;
            
        }
        return self::$auth;
    }
        
    
/****************** User Object ******************/
    
    // Check for data in the session.
    // $temp = Jself::getApplication()->getUserState('com_config.config.global.data');  - administrator/components/com_config/model/application.php

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
            if (!($instance instanceof \Joomla\CMS\User\User))
            {
                $instance = \Joomla\CMS\User\User::getInstance();
            }
        }
        // Check if we have a string as the id or if the numeric id is the current instance
        elseif (!($instance instanceof \Joomla\CMS\User\User) || is_string($id) || $instance->id !== $id)
        {
            $instance = \Joomla\CMS\User\User::getInstance($id);
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
        $smarty = self::getSmarty();
        
        $db = ADONewConnection('mysqli');
        
        /* ADODB Options */

        // ADODB_ASSOC_CASE - You can control the associative fetch case for certain drivers which behave differently. - native is default since v2.90
        // set what case to use for recordsets where the field name (not table names): 0 = lowercase, 1 = uppercase, 2 = native case
        //define('ADODB_ASSOC_CASE', 1); 
        
        //$ADODB_FETCH_MODE = ADODB_FETCH_NUM | ADODB_FETCH_ASSOC; // $ADODB_FETCH_MODE - This is a global variable that determines how arrays are retrieved by recordsets. 
        $db->setFetchMode(ADODB_FETCH_ASSOC); // Set fetch mode to only return associative arrays (i.e. no indexeing added in by ADOdb - // also see http://adodb.org/dokuwiki/doku.php?id=v5:reference:connection:setfetchmode
                
        // Also see http://adodb.org/dokuwiki/doku.php?id=v5:reference:connection:adonewconnection
        // http://adodb.org/dokuwiki/doku.php?id=v5:reference:reference_index - full list of command
        // Basic instructions in vendor/adodb-php/README.md        
        
        //set_include_path(get_include_path() . PATH_SEPARATOR . LIBRARIES_DIR.'adodb/'); // Set Path for ADODB in the php include path (Not needed because it is loaded by Composer)
        //require('adodb.inc.php'); // Load Dependency Manually (Not needed because it is loaded by composer)

        // Enable Error Trapping
        // This extends the system class Exception - http://adodb.org/dokuwiki/doku.php?id=v5:userguide:error_handling
        // I think this tries to convert standard PHP errors to Exceptions
        //require('adodb-exceptions.inc.php');
        //require(VENDOR_DIR.'adodb/adodb-php/adodb-exceptions.inc.php');        
                
        //$db->debug = true;  // This delivers a lot of information to the screen about failed SQL queries
        //$reporting_level = error_reporting(); // Get current PHP error reporting level (not needed with this version of ADOdb)
        //error_reporting(0); // Disable PHP error reporting (works globally) (not needed with this version of ADOdb)

        // ADOdb will show as connected if null values are sent to $db->connect  ?? - This is needed to allow install/migration
        if ($conf->get('db_host') && $conf->get('db_user') && $conf->get('db_name')) {
            
            // Create ADOdb database connection - and collection exceptions
            try
            {        
                $db->Connect($conf->get('db_host'), $conf->get('db_user'), $conf->get('db_pass'), $conf->get('db_name'));
            }

            catch (Exception $e)
            {
                // Re-Enable PHP error reporting
                //error_reporting($reporting_level); (not needed with this version of ADOdb)

                if($conf->get('test_db_connection') == 'test') {
                    //echo $e->msg;
                    //var_dump($e);
                    //adodb_backtrace($e->gettrace());
                    $conf->set('test_db_connection', 'failed');
                    $smarty->assign('warning_msg', prepare_error_data('error_database_connection', $e->msg));
                }

                return false;

            }       

            // Re-Enable PHP error reporting (not needed with this version of ADOdb)
            //error_reporting($reporting_level);
            
            // If just testing the database connection
            if($conf->get('test_db_connection') == 'test') {
                
                if(!$db->isConnected()) {
                    
                    // Database connection failed
                    $smarty->assign('warning_msg', prepare_error_data('error_database_connection', $db->ErrorMsg()));
                    $conf->set('test_db_connection', 'failed');
                    return;
                    
                } else {
                    
                    // Database connection succeeded
                    $conf->set('test_db_connection', 'passed');
                    return;
                    
                }                
                
            }
            
            // Database connection failed (rigged to allow installtion)
            if(!$db->isConnected()) {           
                
                // Valid installation, Database Connection has failed
                if (is_file('configuration.php') && !is_dir('components/_includes/setup')) {

                    die('<div style="color: red;">'._gettext("There is a database connection issue. Check your settings in the config file.").'<br><br>'.$db->ErrorMsg().'</div>');

                }           

            }            
            
            return $db;
        
        }
        
        // If the database connection values have not all been supplied
        return false;
    
    }
/****************** Smarty Object ******************/
    
    /**
     * Get a Smarty Object
     *
     * @return  smarty Object
     *
     * @see     
     * @since   
     */
    public static function getSmarty($newInstance = null)
    {
        if(!is_null($newInstance)) {
        self::$smarty = $newInstance;    
        }
        if(is_null(self::$smarty)) {
        self::$smarty = self::createSmarty();      
        }
        return self::$smarty;
    }         
    
    /**
     * Create a smarty object
     *
     * @return  Smarty Object
     *
     * @see     
     * @since   
     */
    protected static function createSmarty()
    {        
        $conf = self::getConfig();
        $smarty = new Smarty;
        
        /* Configure Smarty */

        // Smarty Class Variables - https://www.smarty.net/docs/en/api.variables.tpl

        $smarty->template_dir           = THEME_TEMPLATE_DIR;
        $smarty->cache_dir              = SMARTY_CACHE_DIR;
        $smarty->compile_dir            = SMARTY_COMPILE_DIR;
        $smarty->force_compile          = $conf->get('smarty_force_compile');

        // Enable caching
        if($conf->get('smarty_caching') == '1') { $smarty->caching = Smarty::CACHING_LIFETIME_CURRENT;}
        if($conf->get('smarty_caching') == '2') { $smarty->caching = Smarty::CACHING_LIFETIME_SAVED;}

        // Other Caching settings
        $smarty->force_cache            = $conf->get('smarty_force_cache');
        $smarty->cache_lifetime         = $conf->get('smarty_cache_lifetime');
        $smarty->cache_modified_check   = $conf->get('smarty_cache_modified_check');
        $smarty->cache_locking          = $conf->get('smarty_cache_locking');

        // Debugging    
        $smarty->debugging_ctrl         = $conf->get('smarty_debugging_ctrl');
        //$smarty->debugging            = $conf->get('smarty_debugging');                                 // Does not work with fetch()
        //$smarty->debugging_ctrl       = ($_SERVER['SERVER_NAME'] == 'localhost') ? 'URL' : 'NONE';      // Restrict debugging URL to work only on localhost
        //$smarty->debug_tpl            = LIBRARIES_DIR.'smarty/debug.tpl';                               // By default it is in the Smarty directory

        // Other Settings
        //$smarty->load_filter('output','trimwhitespace');  // removes all whitespace from output. useful to get smaller page payloads (minify?)
        //$smarty->error_unassigned = true;                 // to enable notices.
        //$smarty->error_reporting = E_ALL | E_STRICT;      // Uses standard PHP error levels.
        //$smarty->compileAllTemplates();                   // this is a really cool feature and useful for translations
        //$smarty->clearAllCache();                         // clears all of the cache
        //$smarty->clear_cache()();                         // clear individual cache files (or groups)
        //$smarty->clearCompiledTemplate();                 // Clears the compile directory
        
        return $smarty;
    
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

    // Joomla 3.9.8 - joomla/libraries/src/Application/CMSApplication.php
    /**
     * Checks if HTTPS is forced in the client configuration.
     *
     * @param   integer  $clientId  An optional client id (defaults to current application client).
     *
     * @return  boolean  True if is forced for the client, false otherwise.
     *
     * @since   3.7.3
     */
    public static function isHttpsForced($clientId = null)
    {
        $clientId = (int) ($clientId !== null ? $clientId : self::getClientId());
        $forceSsl = (int) self::$config->get('force_ssl');

        if ($clientId === 0 && $forceSsl === 2)
        {
            return true;
        }

        if ($clientId === 1 && $forceSsl >= 1)
        {
            return true;
        }

        return false;
    }
    
    /**
     * From Joomla 3.7.0 joomla/libraries/src/Application/WebApplication.php
     * Determine if we are using a secure (SSL) connection.
     *
     * @return  boolean  True if using SSL, false if not.
     *
     * @since   12.2
     */
    public static function isSSLConnection()
    {
        return (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on')) || getenv('SSL_PROTOCOL_VERSION');
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
      
    
    /** 
     * From Joomla 3.7.0 libraries/legacy/application/application.php - no longer used, merged into QFramework::loadSession()
     * I wrote this one myself but based on code from line 999 -> 1012
     */
    public function removeExpiredSessions()
    {
       $db = self::getDbo();

        // Get the current Time
       $time = time();

       // Remove expired sessions from the database.
       if ($time % 2)
       {
           // The modulus '% 2' introduces a little entropy, making the flushing less accurate
           // by firing the query less than half the time.
           $sql = "DELETE FROM ".PRFX."session WHERE time < " . ($time - self::$session->getExpire());            
           $db->Execute($sql);
       }  
    }  
    
}