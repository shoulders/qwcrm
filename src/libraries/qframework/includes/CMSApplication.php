<?php
// joomla\includes\framework.php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @copyright Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

/* Acquire variables from classes examples
 * $user->login_user_id;                                    // This is a public variable defined in the class
 * \Factory::getUser()->login_user_id;                      // Static method to get variable
 * \Factory::getConfig()->get('sef')                        // This is a variable stored in the registry
 * $config = \Factory::getConfig();  | $config->get('sef')  // Get the config into a variable and then you can call the config settings
 * $QConfig->sef                                            // Only works for the QConfig class I made in the root
 */

defined('_QWEXEC') or die;

// Main Framework class
class CMSApplication {
    
    public static $BuildPage    = '';       // Holds the HTML page to be outputted
    public static $VAR          = array();  // Global Variable store
    public static $messages     = array();  // Global System Message Store    
    public static $clientId     = 0;        // The Client identifier. (0 = site, 1 = administrator) should this bee from the user???
    public static $siteName     = 'site';   // Site Name ('site' or 'administrator' )    
    public static $classes      = null;     // Store for classes that can be instanciated (needs to be Static so loader.php can load)
    
    // Context Variables    
    public $config              = null;     // Config object    
    public $smarty              = null;     // Smarty Template System
    public $db                  = null;     // Database instance    
    public $system              = null;     // Hold all of the core framework classes
    public $components          = null;     // Holds all of the loaded component classes        
    public $modules             = null;     // Holds all of the loaded module classes(not currently used)
    public $plugins             = null;     // Holds all of the loaded plugin classes (not currently used)
 
/****************** Load QWcrm enviroment, files, variables and dependencies ******************/
    
    public function execute() {
           
        // Build and configure the Framework/Application
        self::$VAR = array_merge($_POST, $_GET, self::$VAR);                                // Merge Primary Arrays  - Merge the $_GET, $_POST and emulated $_POST ---  1,2,3   1 is overwritten by 2, 2 is overwritten by 3.)
        self::classFilesExecuteStored('system');                                            // Instanciate the QWcrm Framework System Classes into $this->system
        $this->config = \Factory::getConfig();                                              // Load Global Config Object
        $this->system->general->loadLanguage();                                            // Load Language
        $this->db = \Factory::getDbo();                                                     // This is needed to make sure the setup loadsds the database
        $this->system->qerror->configurePhpErrorReporting();                             // Configure PHP error reporting (Enable Error Reporting Immediately) (need to make static and first)----------- (has no dependencies so coulf go earlier)
        $this->system->qerror->loadWhoops($this->config->get('error_handler_whoops'));     // Whoops Error Handler - Here so it can load ASAP       
        $this->smarty = \Factory::getSmarty();                                              // Load Global Smarty Object
        $this->system->security->forceSsl($this->config->get('force_ssl'));                // Redirect to SSL (if enabled) 
        $this->system->general->verifyQwcrmInstallState();                               // Verify Installation state (install/migrate/upgrade/complete) - This enables the DB if it checks the QWcrm database version (upgrade and migrate)
        self::classFilesExecuteStored('components');                                        // Instanciate the QWcrm Component Classes into $this->components
        $this->system->variables->loadSystemVariables();                                  // Load the system variables
        self::classFilesExecuteStored('modules');                                           // Instanciate the QWcrm Module Classes into $this->modules - Not currently used
        self::classFilesExecuteStored('plugins');                                           // Instanciate the QWcrm Plugin Classes into $this->plugins - Not currently used        
        
        // If there is a live/configured database connection, load the session
        if(!defined('QWCRM_SETUP')) // || (defined('PRFX') && $this->db)
        {            
            // Load/Start/Create the session
            $this->loadSession(); 
        
            // Try to automatically login - i.e. using the 'Remember me' feature, a silent login is instigated if a 'Remember me' cookie is found
            $PlgSystemRemember = new PlgSystemRemember;  // This allows silent login using 'Remember me' cookie after checking it exists - need to make sure it does not re-logon if already logged on
            $PlgSystemRemember->onAfterInitialise();
            unset($PlgSystemRemember);        

            // Merge the `Post Emulation Store`, `(stored in the session) to $VAR  ---  1,2,3   1 is overwritten by 2, 2 is overwritten by 3.)        
            self::$VAR = array_merge(self::$VAR, $this->system->variables->postEmulationReturnStore());
            
            // Get the Global user object here
            $this->user = \Factory::getUser();
        }       
        
    }

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
         
        /* 
         * This actually starts the session with the options defined in the array
         * get_class($this) always returns this class's name which then gets hashed to the same hash
         * so if i replace this with another fixed hash or string this causes everyone to be logged out as their session seems dependent on this hash
         * session_name is only used when shared sessions is enabled.
         * session_name is a randomly created hash that is created when shared session is enabled and deleted when disabled.
         * Both these hashes need to be fixed
         */        
        $session = \Factory::getSession(
            array(
                'name'      => self::getHash($this->config->get('session_name', get_class($this))),                       // If i use a random string instead of get_class($this), i can never login
                'expire'    => $this->config->get('session_lifetime') ? $this->config->get('session_lifetime') * 60 : 900,
                'force_ssl' => self::isHttpsForced(),
                //'clientid' => 0, // Possible option to declare the client(site/administrator)
            )
        );

        /////$session->initialise($this->input, $this->dispatcher);
        
        // Get the session handler from the configuration.
        $handler = $this->config->get('session_handler', 'none');

        /*
         * Check for extra session metadata when:
         *
         * 1) The database handler is in use and the session is new
         * 2) The database handler is not in use and the time is an even numbered second or the session is new
         * 
         * This actually creates the session in the database
         */
        if (($handler !== 'database' && (time() % 2 || $session->isNew())) || ($handler === 'database' && $session->isNew()))
        {
            $session->checkSession();
        }

        // Set the session object. the getSession() adds this to the static variable so i could remove this
        //\Factory::$session = $session;
        
        // Check the session table for stale entries (replaces above)
        $this->removeExpiredSessions();

        return $this;
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
        $session = \Factory::getSession();
        $registry = $session->get('registry');

        if (!is_null($registry))
        {
            return $registry->set($key, $value);
        }

        return;
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
        $forceSsl = (int) \Factory::$config->get('force_ssl');

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
        return md5(\Factory::getConfig()->get('secret_key') . $seed);
    }      
      
    
    /** 
     * From Joomla 3.7.0 libraries/legacy/application/application.php - no longer used, merged into QFramework::loadSession()
     * I wrote this one myself but based on code from line 999 -> 1012
     */
    public function removeExpiredSessions()
    {
       // Get the current Time
       $time = time();

       // Remove expired sessions from the database.
       if ($time % 2)
       {
           // The modulus '% 2' introduces a little entropy, making the flushing less accurate
           // by firing the query less than half the time.
           $sql = "DELETE FROM ".PRFX."session WHERE time < " . ($time - \Factory::$session->getExpire());            
           $this->db->Execute($sql);
       }  
    }  
    
    /**
     * based on discover() joomla/libraries/loader.php   - not currently used
     * 
     * Method to discover and instanciate classes of a given type in a given path to a specific variable
     *
     * @param   string   $parentPath   Full path to the parent folder for the classes to discover.
     * @param   variable &$classHolder Target variable for all of the classes found in the given path
     *
     *
     * @return  void
     *
     * @since   3.1.2
     *
    static public function classFilesLoadExecute($parentPath)
    {
        $classHolder = new stdClass();
        
        try
        {
            
            $iterator = new DirectoryIterator($parentPath);

            /** @type  $file  DirectoryIterator * /
            foreach ($iterator as $file)
            {
                $fileName = $file->getFilename();

                // Only load for php files.
                if ($file->isFile() && $file->getExtension() === 'php')
                {
                    // Get the file
                    //require($parentPath.$fileName);
                    require($file->getPathname());
                                        
                    // Get the class name for each file.
                    $className = preg_replace('#\.php$#', '', $fileName);
                    
                    // If not in setup, skip 'Setup' class
                    if(!defined('QWCRM_SETUP') && $className === 'Setup' ) { continue; }
                    
                    // Checks if the class is instantiable (needed because of 'Factory' is in main class load folder)
                    $checkClass = new ReflectionClass($className);
                    if(!$checkClass->isInstantiable()) { continue; }
                    
                    // Get the lowercase version of the class name
                    $lowerClassName = strtolower($className);
                    
                    // Load the class in to the relevant variable
                    $classHolder->$lowerClassName = new $className();                     
  
                }
            }
        }        
        catch (UnexpectedValueException $e)
        {
            // Exception will be thrown if the path is not a directory. Ignore it.
        }
        
        return $classHolder;
        
    }*/



    /**
     * based on discover() joomla/libraries/loader.php
     * 
     * Method to discover and load class files classes of a given type in a given path to a specific variable
     *
     * This allows me to autoload the files without instantiating the classes
     * Only instantiatable classes will be loaded in the $classes variable
     *
     * @param   string   $parentPath   Full path to the parent folder for the classes to discover.
     * @param   string   $classGroup   Class Group being looked up
     *
     * @return  void
     *
     * @since   3.1.2
     */
    static public function classFilesLoad($parentPath, $classGroup = null)
    {
                
        try
        {
            
            $iterator = new DirectoryIterator($parentPath);

            /** @type  $file  DirectoryIterator */
            foreach ($iterator as $file)
            {
                $fileName = $file->getFilename();

                // Only load for php files.
                if ($file->isFile() && $file->getExtension() === 'php')
                {
                    // Get the file
                    //require($parentPath.$fileName);
                    require($file->getPathname());
                                        
                    // Get the class name for each file.
                    $className = preg_replace('#\.php$#', '', $fileName);
                    
                    // If not in setup, skip 'Setup' class
                    //if(!defined('QWCRM_SETUP') && $className === 'Setup' ) { continue; }
                    
                    // Checks if the class is instantiable (needed because 'Factory' is in main class load folder)
                    $checkClass = new ReflectionClass($className);
                    if(!$checkClass->isInstantiable()) { continue; }
                                     
                    // If no store is defined, skip adding the class reference to the Class Group Store/registry (useful for loading payment method and type classes)
                    if($classGroup) {
                        
                        // Store the Class names for instaciating later
                        self::$classes[$classGroup][] = $className;
                    
                    }
  
                }
            }
        }        
        catch (UnexpectedValueException $e)
        {
            // Exception will be thrown if the path is not a directory. Ignore it.
        }
  
    }
    
    /**
     * Method to discover and load class files classes of a given type in a given path to a specific variable
     *
     * This instanciating the classes into their corresponding group variable (components/modules/plugins/system/etc....), this class holds the variable
     * $onlyThisGroup is currently optional but so far I always declare a group
     * 
     * @param   string   $classGroup   Class Group being looked up
     * @param   string   $parentPath   Full path to the parent folder for the classes to discover.
     *
     *
     * @return  void
     *
     * @since   3.1.2
     */
    private function classFilesExecuteStored($onlyThisGroup = null)
    {
        
        // Check if the store is empty
        if(empty(self::$classes)){ return; }
        
        // Check if the specified store is empty, exit if empty
        if($onlyThisGroup && empty(self::$classes[$onlyThisGroup])){ return; }
                
        // cycle through the different class groups
        foreach (self::$classes as $classGroup => $classNames)
        {
            // If a specific group is set, skip unless it matches the correct group
            if($onlyThisGroup && $onlyThisGroup !== $classGroup) { continue; }
            
            // Create standard object for the group so it can accepts these sub-objects
            if(is_null($this->$classGroup)) {
                $this->$classGroup = new stdClass();
            }
            
            // cycle through each of the classes in this group
            foreach($classNames as $className)
            {
                // If not in setup, skip 'Setup' class
                if(!defined('QWCRM_SETUP') && $className === 'Setup' ) { continue; }
                
                // Get the lowercase version of the class name
                $lowerClassName = strtolower($className);
                
                // Load the class in to the relevant variable (in this class)
                $this->$classGroup->$lowerClassName = new $className();  
            }
            
        }
        
        return;
    }        
     
}