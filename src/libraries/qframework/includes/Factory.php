<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

abstract class Factory {
    
    // Static
    public static $application  = null;     // Application Object
    public static $config       = null;     // Global Config object
    public static $session      = null;     // Global Session object
    public static $auth         = null;     // Global Authetication object
    public static $user         = null;     // Global User object
    public static $database     = null;     // Global Database object    
    public static $smarty       = null;     // Global Smarty object  
    
    /****************** Application Object ******************/
    
    /**
     * Get an application object.
     *
     * Returns the global {@link CMSApplication} object, only creating it if it doesn't already exist.
     *
     *
     * @return  CMSApplication object
     *
     * @see     JApplication
     * @since   1.7.0
     * @throws  \Exception
     */
    //public static function getApplication($id = null, array $config = array(), $prefix = 'J')
    public static function getApplication()
    {        
        if(!self::$application)
        {
            self::$application = new CMSApplication;            
        }
        return self::$application;
    }
    
    /**
     * Returns a reference to the global CMSApplication object, only creating it if it doesn't already exist.
     *
     * This method must be invoked as: $web = CMSApplication::getInstance();
     *
     * @param   string  $name  The name (optional) of the CMSApplication class to instantiate.
     *
     * @return  CMSApplication
     *
     * @since   3.2
     * @throws  \RuntimeException
     */
    public static function createApplication()
    {
        if (empty(static::$instances[$name]))
        {
            // Create a CMSApplication object.
            //$classname = '\JApplication' . ucfirst($name);
            $classname = 'CMSApplication';
            
            if (!class_exists($classname))
            {
                //throw new \RuntimeException(\JText::sprintf('JLIB_APPLICATION_ERROR_APPLICATION_LOAD', $name), 500);
            }

            static::$instances[$name] = new $classname;
        }

        return static::$instances[$name];
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
        $config    = self::getConfig();
        $handler = $config->get('session_handler', 'none');

        // Config time is in minutes already declared in loadsession()
        //$options['expire'] = ($config->get('session_lifetime')) ? $config->get('session_lifetime') * 60 : 900;  // this is already stated in load session

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
        
        $config = self::getConfig();        
        
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
        if ($config->get('db_host') && $config->get('db_user') && $config->get('db_name')) {
            
            // Create ADOdb database connection - and collection exceptions
            try
            {        
                $db->Connect($config->get('db_host'), $config->get('db_user'), $config->get('db_pass'), $config->get('db_name'));
            }

            catch (Exception $e)
            {
                // Re-Enable PHP error reporting
                //error_reporting($reporting_level); (not needed with this version of ADOdb)

                if($config->get('test_db_connection') == 'test') {
                    //echo $e->msg;
                    //var_dump($e);
                    //adodb_backtrace($e->gettrace());
                    $config->set('test_db_connection', 'failed');
                    self::getApplication()->system->variables->systemMessagesWrite('danger', self::getApplication()->system->general->prepareErrorData('error_database_connection', $e->msg));
                }

                return false;

            }       

            // Re-Enable PHP error reporting (not needed with this version of ADOdb)
            //error_reporting($reporting_level);
            
            // If just testing the database connection
            if($config->get('test_db_connection') == 'test') {
                
                if(!$db->isConnected()) {
                    
                    // Database connection failed
                    self::getApplication()->system->variables->systemMessagesWrite('danger', self::getApplication()->system->general->prepareErrorData('error_database_connection', $db->ErrorMsg()));
                    $config->set('test_db_connection', 'failed');
                    return;
                    
                } else {
                    
                    // Database connection succeeded
                    $config->set('test_db_connection', 'passed');
                    return;
                    
                }                
                
            }
            
            // Database connection failed (rigged to allow installation)
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
        $config = self::getConfig();
        $smarty = new Smarty;
        
        /* Configure Smarty */

        // Smarty Class Variables - https://www.smarty.net/docs/en/api.variables.tpl
        $smarty->template_dir           = THEME_TEMPLATE_DIR;
        $smarty->cache_dir              = SMARTY_CACHE_DIR;
        $smarty->compile_dir            = SMARTY_COMPILE_DIR;
        $smarty->force_compile          = $config->get('smarty_force_compile');
        
        // Custom Plugin Directory
        $smarty->addPluginsDir(LIBRARIES_DIR.'/custom/smarty/plugins/');

        // Enable caching
        if($config->get('smarty_caching') == '1') { $smarty->caching = Smarty::CACHING_LIFETIME_CURRENT;}
        if($config->get('smarty_caching') == '2') { $smarty->caching = Smarty::CACHING_LIFETIME_SAVED;}

        // Other Caching settings
        $smarty->force_cache            = $config->get('smarty_force_cache');
        $smarty->cache_lifetime         = $config->get('smarty_cache_lifetime');
        $smarty->cache_modified_check   = $config->get('smarty_cache_modified_check');
        $smarty->cache_locking          = $config->get('smarty_cache_locking');

        // Debugging    
        $smarty->debugging_ctrl         = $config->get('smarty_debugging_ctrl');
        //$smarty->debugging            = $config->get('smarty_debugging');                               // Does not work with fetch()
        //$smarty->debugging_ctrl       = ($_SERVER['SERVER_NAME'] == 'localhost') ? 'URL' : 'NONE';      // Restrict debugging URL to work only on localhost
        //$smarty->debug_tpl            = LIBRARIES_DIR.'smarty/debug.tpl';                               // By default it is in the Smarty directory

        // Other Settings/functions
        //$smarty->load_filter('output','trimwhitespace');  // removes all whitespace from output. useful to get smaller page payloads (minify?)
        //$smarty->error_unassigned = true;                 // to enable notices.
        //$smarty->error_reporting = E_ALL | E_STRICT;      // Uses standard PHP error levels.
        //$smarty->compileAllTemplates();                   // this is a really cool feature and useful for translations
        //$smarty->clearAllCache();                         // clears all of the cache
        //$smarty->clear_cache()();                         // clear individual cache files (or groups)
        //$smarty->clearCompiledTemplate();                 // Clears the compile directory
        
        return $smarty;
    
    }
    
}