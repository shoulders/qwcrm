<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// include class files
require INCLUDES_DIR . 'framework/config.php';                          // This gets the standard config settings ONLY
require INCLUDES_DIR . 'framework/general/registry.php';                // This class is used to create/edit/read a JSON compatible register than can be stored in the database i.e. user paremeters or used within the various classes for data manipulation (set/get/ckear)
require INCLUDES_DIR . 'framework/general/FilterInput.php';            // this is used to clean/filter strings to suitable formats i.e. ALUM = alpha numeric - used for QCookie and authenticatin

require INCLUDES_DIR . 'framework/session/session.php';                 // This handeles the user session (mainly php but utilises cookes) - update thhis description
//require INCLUDES_DIR . 'framework/session/cookiesession.php';                  // This handles the basic cookie manipulation
//require INCLUDES_DIR . 'framework/cookie/Input.php'; only used get so i have put that in cookie
require INCLUDES_DIR . 'framework/cookie/Cookie.php'; // cookie object with set and get extends Input.php

require INCLUDES_DIR . 'framework/authentication/authentication.php';   // This is used to facilitate the authorization process
require INCLUDES_DIR . 'framework/authentication/response.php';         // this is used to store the responses from the qwcrm.php and cookie.php authorisation plugins

require INCLUDES_DIR . 'framework/authentication/Webclient.php';        // this libary gets the browser details from the session (used in cookie creation)
require INCLUDES_DIR . 'framework/authentication/methods/cookie.php';   // This allos the user to be be authenticated by a coookie (persitent session)
require INCLUDES_DIR . 'framework/authentication/methods/qwcrm.php';    // This is the standard username and password authorisation
require INCLUDES_DIR . 'framework/authentication/methods/remember.php'; // This is the standard username and password authorisation
require INCLUDES_DIR . 'framework/authentication/StringHelper.php'; // This is the standard username and password authorisation

require INCLUDES_DIR . 'framework/user/user.php';                       // This is the object/thing that hols the user data objectr
require INCLUDES_DIR . 'framework/user/helper.php';                     // This contains password hassing functions etc..

//require INCLUDES_DIR . 'framework/session/phpsession.php';  


class QFactory {
    
    // Static
    public static $user         = null; 
    public static $config       = null;     // Global config object
    public static $clientId     = 0;        // The client identifier. (0 = site, 1 = administrator)
    public static $siteName     = 'site';   // Site name ('site' or 'administrator' )
    public static $database     = null;     // Global Databse object
    public static $auth         = null;     // Global authetication object
    
    // Context Variables
    public $db                  = null;     // Global database object   
    public $smarty              = null;     // Global smarty object
    public $conf                = null;
    public $session             = null;
    public $phpsession          = null;     // old php session for compatability - could be removed after testing
    
    // Might not keep this  
    //public $cookiePlg       = null;         // cookie authentication object
            
    public function __construct()
    {
        $this->conf     = QFactory::getConfig();
        $this->db       = QFactory::getDbo();
        global $smarty;
        $this->smarty   = $smarty;
        $this->session  = new QSession();        // make this an object
        //$this->phpsession = new phpsession;     // for compatability
        
        // Try to automatically login - i,e, using the remember me cookie
        $rememberMePlg = new PlgSystemRemember;
        $rememberMePlg->onAfterInitialise();
        unset($rememberMePlg);
        
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
            
        $auth = QFactory::getAuth();
        
        if($auth->login($credentials, $options)) {
            
            /* Login true */
            
            $user = QFactory::getUser();

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
     * Login authentication function.
     *
     */
    public function logout($userid = null, $options = array())
    {    
        
        $auth = QFactory::getAuth();
        $auth->logout($userid, $options);
        
        // Log activity       
        write_record_to_activity_log($this->smarty->getTemplateVars('translate_system_auth_log_message_logout_successful_for').' '.$this->session->get('login_usr'));        
                
        // Reload with 'Logout Successful' message        
        force_page('core', 'login', 'information_msg='.$this->smarty->getTemplateVars('translate_system_auth_advisory_message_logout_successful'), 'get');
        exit;
    } 
   
/********************************** Object Grabbers ********************************************/
    
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
          
        $conf = QFactory::getConfig();       
        
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
     * Get a user object.
     *
     * Returns the global {@link QUser} object, only creating it if it doesn't already exist.
     *
     * @param   integer  $id  The user to load - Can be an integer or string - If string, it is converted to ID automatically.
     *
     * @return  QUser object
     *
     * @see     QUser
     * @since   11.1
     */       
    public static function getUser($id = null)
    {        
        if(self::$user && $id !== null)
        {
            self::$user = new QUser($id);
            return self::$user;
        }
        
        if(self::$user && $id === null)
        {
            return self::$user;
        }
        
        if(!self::$user)
        {
            self::$user = new QUser($id);
            return self::$user;
        }

    }

     /**
     * Get a user object.
     *
     * Returns the global {@link QUser} object, only creating it if it doesn't already exist.
     *
     * @param   integer  $id  The user to load - Can be an integer or string - If string, it is converted to ID automatically.
     *
     * @return  QUser object
     *
     * @see     QUser
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
        return md5(QFactory::getConfig()->get('secretKey') . $seed);
    }      
    
}

