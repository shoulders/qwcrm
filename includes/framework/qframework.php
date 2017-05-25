<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */


// D:\websites\htdocs\quantumwarp.com\libraries\joomla\application\web.php

// D:\websites\htdocs\quantumwarp.com\libraries\joomla\factory.php
/**
 * @package    Joomla.Platform
 *
 * @copyright  Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

// D:\websites\htdocs\quantumwarp.com\libraries\cms\application\cms.php
/**
 * @package     Joomla.Libraries
 * @subpackage  Application
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_QWEXEC') or die;

// include class files
require INCLUDES_DIR . 'framework/config.php';                          // This gets the standard config settings ONLY


require INCLUDES_DIR . 'framework/session/session.php';                 // This handeles the user session (mainly php but utilises cookes) - update thhis description
require INCLUDES_DIR . 'framework/session/cookie.php';                  // This handles the basic cookie manipulation

require INCLUDES_DIR . 'framework/general/registry.php';                // This class is used to create/edit/read a JSON compatible register than can be stored in the database i.e. user paremeters or used within the various classes for data manipulation (set/get/ckear)

require INCLUDES_DIR . 'framework/authentication/authentication.php';   // This is used to facilitate the authorization process
require INCLUDES_DIR . 'framework/authentication/response.php';         // this is used to store the responses from the qwcrm.php and cookie.php authorisation plugins
require INCLUDES_DIR . 'framework/authentication/input.php';            // this is used to clean/filter strings to suitable formats i.e. ALUM = alpha numeric
require INCLUDES_DIR . 'framework/authentication/Webclient.php';        // this libary gets the browser details from the session (used in cookie creation)
require INCLUDES_DIR . 'framework/authentication/methods/cookie.php';   // This allos the user to be be authenticated by a coookie (persitent session)
require INCLUDES_DIR . 'framework/authentication/methods/qwcrm.php';    // This is the standard username and password authorisation
require INCLUDES_DIR . 'framework/authentication/methods/remember.php'; // This is the standard username and password authorisation

require INCLUDES_DIR . 'framework/user/user.php';                       // This is the object/thing that hols the user data objectr
require INCLUDES_DIR . 'framework/user/helper.php';                     // This contains password hassing functions etc..

/**
 * Joomla Platform Factory class.
 *
 * @since  11.1
 */
class QFactory
{

    //public $config          = null;     // Global configuraiton object
    //public $session         = null;     // Global session object
    public $db              = null;     // Global database object   
    private $smarty;                    // Global smarty object
    public $cookiePlg = null;            // Global cookie object
    public $user            = null;     // Global user object (should be functions and logged in user details) - i will use this to store the logged in user (when authenticated)
    public $auth            = null;     // Global authentication object (performs login etc..)
    public $ACLAuthorise    = false;    // The user has access to this resource
    //protected $clientId;     // The client identifier (client/employee || site/admin  || client_Id = 0 means frontend access / client_Id = 1 means admin access)
    public $registry = null;   // my temporary registry object replace some config
    
    
    /**
     * Global application object
     *
     * @var    JApplicationCms
     * @since  11.1
     */
    //public static $application = null;
    //public static $instance = null;
    
    /**
     * Global configuraiton object
     *
     * @var    JConfig
     * @since  11.1
     */
    public static $config = null;


    /**
     * Global session object
     *
     * @var    JSession
     * @since  11.1
     */
    public static $session = null;
    
    
 /**
     * Global language object
     *
     * @var    JLanguage
     * @since  11.1
     */
    public static $language = null;

    /**
     * Global document object
     *
     * @var    JDocument
     * @since  11.1
     */
    public static $document = null;
    

    /**
     * Global database object
     *
     * @var    JDatabaseDriver
     * @since  11.1
     */
    public static $database = null;
    
    /**
     * Global mailer object
     *
     * @var    JMail
     * @since  11.1
     */
    public static $mailer = null;    
    /**
     * The client identifier. (0 = site, 1 = admin)
     *
     * @var    integer
     * @since  3.2
     * @deprecated  4.0  Will be renamed $clientId
     */
    //protected $clientId = null;
    protected $clientId = '0';


    /**
     * The name of the application. ('site'/'admin')
     *
     * @var    array
     * @since  3.2
     * @deprecated  4.0  Will be renamed $name
     */
    //protected $name = null;
    protected static $name = 'site';

    /**
     * Data for the layout
     *
     * @var    array
     * @since  3.5
     */
    protected $data = array();
       
    public function __construct(Registry $config = null)
    {       
                
        // If a config object is given use it.  - not sure I need this as it is the root
        if ($config instanceof Registry)
        {
            self::$config = $config;           
        }
        // Instantiate a new configuration object.
        else
        {
            $conf = self::getConfig();
        }
        
        // load config/registry object for this function
        //$conf = self::getConfig();
        //$session = self::$session;
        
        // Populate Global Objects
        $this->db = QFactory::getDbo();
        global $smarty;                     // I am passing global smarty to keep 1 instance
       
        $this->smarty = $smarty;        
        $this->cookiePlg   = new PlgAuthenticationCookie;
        $this->user     = QUser::getInstance();
        
                
        /* Load session */ // sort session loading
        
        // Create the session based on the application logic.
        /*if ($session !== false)
        {echo 'cheese';
            $this->loadSession($session);
        }*/

        // Enable sessions by default.
        if (is_null($conf->get('session')))
        {            
            $conf->set('session', true);
        }

        // Set the session default name. (site /admin)
        if (is_null($conf->get('session_name')))
        {
            $conf->set('session_name', self::getName());
        }

        // Create the session if a session name is passed.
        if ($conf->get('session') !== false)
        {            
            //$this->loadSession($session); 
            $this->loadsession();
        }
        
        // Perfomr auto login via cookie/remember me
        //$autoCookieLogin = new PlgSystemRemember;
        //$autoCookieLogin->onAfterInitialise();
   
        
       
    }
/****************************Temp**************************************************************/
// D:\websites\htdocs\quantumwarp.com\libraries\cms\plugin\plugin.php
    /**
     * Constructor
     *
     * @param   object  &$subject  The object to observe
     * @param   array   $config    An optional associative array of configuration settings.
     *                             Recognized key values include 'name', 'group', 'params', 'language'
     *                             (this list is not meant to be comprehensive).
     *
     * @since   1.5
     *
    public function __construct(&$subject, $config = array())
    {
        // Get the parameters.
        if (isset($config['params']))
        {
            if ($config['params'] instanceof Registry)
            {
                $this->params = $config['params'];
            }
            else
            {
                $this->params = new Registry($config['params']);
            }
        }

        // Get the plugin name.
        if (isset($config['name']))
        {
            $this->_name = $config['name'];
        }

        // Get the plugin type.
        if (isset($config['type']))
        {
            $this->_type = $config['type'];
        }

        // Load the language files if needed.
        if ($this->autoloadLanguage)
        {
            $this->loadLanguage();
        }

        if (property_exists($this, 'app'))
        {
            $reflection = new ReflectionClass($this);
            $appProperty = $reflection->getProperty('app');

            if ($appProperty->isPrivate() === false && is_null($this->app))
            {
                $this->app = JFactory::getApplication();
            }
        }

        if (property_exists($this, 'db'))
        {
            $reflection = new ReflectionClass($this);
            $dbProperty = $reflection->getProperty('db');

            if ($dbProperty->isPrivate() === false && is_null($this->db))
            {
                $this->db = JFactory::getDbo();
            }
        }

        parent::__construct($subject);
    }*/

/******************************************************************************************/
    
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
    //public function loadSession(QSession $session)   - is the 'QSession $session = null' needed or just ($session)
    public function loadSession(QSession $session = null)
    {
       $conf = QFactory::getConfig();
       
       if ($session !== null)
        {
            self::$session = $session;            
            return $this;
        }

        // Generate a session name - uses class for a seed if there is no session name set - is also set in the constructor  
        $name = $this->getHash($conf->get('session_name', get_class($this)));

        // Calculate the session lifetime.
        $lifetime = ($conf->get('session_lifetime') ? $conf->get('session_lifetime') * 60 : 900);               
        
        // Initialize the options for QSession.
        $options = array(
            'name'   => $name,
            'expire' => $lifetime,
        );

        // Set force_ssl for the session cookie        
        switch ($this->getClientId())
        {
            // force_ssl: 0 = none, 1 = Admin only, 2 = entire site
            
            // If 'site' and enable SSL for 'Entire Site'
            case 0:
                if ($conf->get('force_ssl') == 2)
                {
                    $options['force_ssl'] = true;
                }

                break;

            // If 'admin' and either 'Admin Only' or 'Entire Site'
            case 1:
                if ($conf->get('force_ssl') >= 1)
                {
                    $options['force_ssl'] = true;
                }

                break;
        }
        
               
        //onAfterSessionStart        
        //$this->registerEvent('onAfterSessionStart', array($this, 'afterSessionStart'));

        /*
         * Note: The below code CANNOT change from instantiating a session via QFactory until there is a proper dependency injection container supported
         * by the application. The current default behaviours result in this method being called each time an application class is instantiated.
         * https://github.com/joomla/joomla-cms/issues/12108 explains why things will crash and burn if you ever attempt to make this change
         * without a proper dependency injection container.
         */

        $session = self::getSession($options);
        //$session->initialise($this->input, $this->dispatcher);             
        
        // Get the current Time
        $time = time();
        
        // Remove expired sessions from the database. - is this in the right plpace or should i create a nother function called prune
        if ($time % 2)
        {
            // The modulus '% 2' introduces a little entropy, making the flushing less accurate
            // by firing the query less than half the time.
            $sql = "DELETE FROM ".PRFX."session WHERE time < ".$time - $session->getExpire();            
            $this->db->Execute($sql);
        }

        // Get the session handler from the configuration.
        $handler = $conf->get('session_handler', 'database');
        
        if (  ($handler != 'database' && ($time % 2 || $session->isNew()))    ||    ($handler == 'database' && $session->isNew())  )
        {
            $this->checkSession();
        }

        // Set the session object.
        self::$session = $session;

        return $this;
        
        
        /*
         * // D:\websites\htdocs\quantumwarp.com\libraries\joomla\application\web.php
         * $this->registerEvent('onAfterSessionStart', array($this, 'afterSessionStart'));

        // Instantiate the session object.
        $session = JSession::getInstance($handler, $options);
        $session->initialise($this->input, $this->dispatcher);

        if ($session->getState() == 'expired')
        {
            $session->restart();
        }
        else
        {
            $session->start();
        }

        // Set the session object.
        $this->session = $session;

        return $this;
         *
         */
        
        
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
     * 
     * thre is doubling up on this code, is it needed ie.handler
     * 
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
        $conf    = new QConfig;        
        //$handler = $conf->get('session_handler', 'database');
        $handler = $conf->get('session_handler', 'none');   

        // Config time is in minutes - this is already handles in get session
        $options['expire'] = ($conf->get('session_lifetime')) ? $conf->get('session_lifetime') * 60 : 900;

        // The session handler needs a JInput object, we can inject it without having a hard dependency to an application instance
        //$input = self::$application ? self::getApplication()->input : new JInput;

        //$sessionHandler = new JSessionHandlerJoomla($options);
        //$sessionHandler->input = $input;

        $session = QSession::getInstance($handler, $options);
        //getInstance($store, $options, QSessionHandlerInterface $handlerInterface = null)  // session handler removed
        
        if ($session->getState() == 'expired')
        {
            $session->restart();
        }

        return $session;
    }
    
    /**
     * Checks the user session.
     *
     * If the session record doesn't exist, initialise it.
     * If session is new, create session variables
     *
     * @return  void
     *
     * @since   3.2
     * @throws  RuntimeException
     */
    public function checkSession()
    {
        //$db = QFactory::getDbo();
        $conf = QFactory::getConfig();
        $session = QFactory::getSession();
        $user = QFactory::getUser();        
        
        $sql = "SELECT session_id FROM ".PRFX."session WHERE session_id = ".$this->db->qstr($session->getId());        
        $rs = $this->db->Execute($sql);
        $exists = $rs->RecordCount();
                
        // If the session record doesn't exist initialise it.
        if (!$exists)
        {
            $time = $session->isNew() ? time() : $session->get('session.timer.start');

            // Set up the record to insert            
            $record['session_id']  = $session->getId()  ;
            $record['guest']       = $this->db->qstr( (int) $user->guest );
            $record['time']        = (int) $time;
            $record['userid']      = $this->db->qstr( (int) $user->id    );
            $record['username']    = $this->db->qstr( $user->username    );                 
           
            // if login not shared between site and admin (joomla thing)
            if (!$conf->get('shared_session', '0'))
            {
                $record['client_id'] = $this->db->qstr((int) $this->getClientId());
            }            

            // If the insert failed, exit the application.
            try
            {
                //$db->execute();
                $this->db->AutoExecute(PRFX.'session', $record, 'INSERT');
            }
            catch (RuntimeException $e)
            {
                throw new RuntimeException(JText::_('JERROR_SESSION_STARTUP'), $e->getCode(), $e);
            }
        }
    }
    
 
    
    /**************************************login/authentication****************************************************/
    
    
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
        // Get the global QAuthentication object.
        $authenticate = QAuthentication::getInstance();
        $response = $authenticate->authenticate($credentials, $options); // this cycles through the plugins (qwcrm.php cookie.php methods) and collates the responses in a 'reponse class' and then returns it

        // Import the user plugin group. // not sure what this is for
        //JPluginHelper::importPlugin('user');

        if ($response->status === QAuthentication::STATUS_SUCCESS)
        {
            /*
             * Validate that the user should be able to login (different to being authenticated).
             * This permits authentication plugins blocking the user.
             * This cycle through plugins responses (cookie.php and qwcrm.php) and then executes their login failures routine (if any) or continue
             */
            $authorisations = $authenticate->authorise($response, $options);
            $denied_states = QAuthentication::STATUS_EXPIRED | QAuthentication::STATUS_DENIED;

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
                        case QAuthentication::STATUS_EXPIRED:
                            return JError::raiseWarning('102002', JText::_('JLIB_LOGIN_EXPIRED'));

                        case QAuthentication::STATUS_DENIED:
                            return JError::raiseWarning('102003', JText::_('JLIB_LOGIN_DENIED'));

                        default:
                            return JError::raiseWarning('102004', JText::_('JLIB_LOGIN_AUTHORISATION'));
                    }
                }
            }

            // OK, the credentials are authenticated and user is authorised.  Let's fire the onLogin event. (stored qwcrm.php and cookie.php methods)
            //$results = $this->triggerEvent('onUserLogin', array((array) $response, $options));
            $results = array();
            
            /*
             * If any of the user plugins did not successfully complete the login routine
             * then the whole method fails.
             *
             * Any errors raised should be done in the plugin as this provides the ability
             * to provide much more information about why the routine may have failed.
             */
            $user = QFactory::getUser();

            if ($response->type == 'Cookie')
            {
                $user->set('cookieLogin', true);
            }

            if (in_array(false, $results, true) == false)
            {
                $options['user'] = $user;
                $options['responseType'] = $response->type;

                // The user is successfully logged in. Run the after login events  (stored qwcrm.php and cookie.php methods)
                //$this->triggerEvent('onUserAfterLogin', array($options));
                $this->cookiePlg->onUserAfterLogin($options);
                
            }

            return true;
        }

        // Trigger onUserLoginFailure Event.
        //$this->triggerEvent('onUserLoginFailure', array((array) $response));   (stored qwcrm.php and cookie.php methods)

        // If silent is set, just return false.
        if (isset($options['silent']) && $options['silent'])
        {
            return false;
        }

        // If status is success, any error will have been raised by the user plugin
        if ($response->status !== QAuthentication::STATUS_SUCCESS)
        {
            //JLog::add($response->error_message, JLog::WARNING, 'jerror');
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
        $user = QFactory::getUser($userid);

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
        $results = $this->triggerEvent('onUserLogout', array($parameters, $options));   //(stored qwcrm.php and cookie.php methods)

        // Check if any of the plugins failed. If none did, success.
        if (!in_array(false, $results, true))
        {
            $options['username'] = $user->get('username');
            $this->triggerEvent('onUserAfterLogout', array($options));          // (stored qwcrm.php and cookie.php methods)

            return true;
        }

        // Trigger onUserLoginFailure Event.
        $this->triggerEvent('onUserLogoutFailure', array($parameters));         // (stored qwcrm.php and cookie.php methods)

        return false;
    }
    
    /**************************************QFamework / App ****************************************************/     
    /**
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
     */
    public static function OFFgetApplication($id = null, array $config = array(), $prefix = 'J')
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
    }
    
    /**
     * Returns a reference to the global JApplicationCms object, only creating it if it doesn't already exist.
     *
     * This method must be invoked as: $web = JApplicationCms::getInstance();
     *
     * @param   string  $name  The name (optional) of the JApplicationCms class to instantiate.
     *
     * @return  JApplicationCms
     *
     * @since   3.2
     * @throws  RuntimeException
     */
    public static function OFFgetInstance($name = null)
    {
        if (empty(static::$instances[$name]))
        {
            // Create a JApplicationCms object.
            $classname = 'JApplication' . ucfirst($name);

            if (!class_exists($classname))
            {
                throw new RuntimeException(JText::sprintf('JLIB_APPLICATION_ERROR_APPLICATION_LOAD', $name), 500);
            }

            static::$instances[$name] = new $classname;
        }

        return static::$instances[$name];
    }    
    
        /**
     * Returns the global App object, only creating it if it doesn't already exist.
     *
     * @param   string                    $store             The type of storage for the session.
     * @param   array                     $options           An array of configuration options.
     * @param   QSessionHandlerInterface  $handlerInterface  The session handler
     *
     * @return  QSession  The Session object.
     *
     * @since   11.1
     */
    public static function getApplication()
    {
        if (!is_object(self::$instance))
        {
            self::$instance = new QFactory();
        }

        return self::$instance;
    }   
    
    
    /*********************************user**********************************************************/
    
    
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
        $instance = self::getSession()->get('user');        

        if (is_null($id))
        {
            if (!($instance instanceof QUser))
            {
                $instance = QUser::getInstance();
            }
        }
        // Check if we have a string as the id or if the numeric id is the current instance
        elseif (!($instance instanceof QUser) || is_string($id) || $instance->id !== $id)
        {
            $instance = QUser::getInstance($id);
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
    public static function getUserState($key, $default = null)
    {
        $session = QFactory::getSession();
        $registry = $session->get('registry');

        if (!is_null($registry))
        {
            return $registry->get($key, $default);
        }

        return $default;
    }
    
    /**
     * Sets the value of a user state variable.
     *
     * @param   string  $key    The path of the state.
     * @param   mixed   $value  The value of the variable.
     *
     * @return  mixed  The previous state, if one existed.
     *
     * @since   3.2
     */
    public static function setUserState($key, $value)
    {
        $session = QFactory::getSession();
        $registry = $session->get('registry');

        if (!is_null($registry))
        {
            return $registry->set($key, $value);
        }

        return;
    }
    /**
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
     *
    public static function getUserStateFromRequest($key, $request, $default = null, $type = 'none')
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
    } */  


    
    
/**********************************other checks ********************************************/

    /**
     * Gets the client id of the current running application.
     *
     * @return  integer  A client identifier.
     *
     * @since   3.2
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * Gets the name of the current running application.
     *
     * @return  string  The name of the application.
     *
     * @since   3.2
     */
    public static function getName()
    {
        return self::$name;
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
        return self::getName() === $identifier;
    }

    
   /*******************database ***********************************/ 



    


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

/*************************language**********************************************/

    /**
     * Get a language object.
     *
     * Returns the global {@link JLanguage} object, only creating it if it doesn't already exist.
     *
     * @return  JLanguage object
     *
     * @see     JLanguage
     * @since   11.1
     */
    public static function getLanguage()
    {
        if (!self::$language)
        {
            self::$language = self::createLanguage();
        }

        return self::$language;
    }
    
    /**
     * Create a language object
     *
     * @return  JLanguage object
     *
     * @see     JLanguage
     * @since   11.1
     */
    protected static function createLanguage()
    {
        $conf = self::getConfig();
        $locale = $conf->get('language');
        $debug = $conf->get('debug_lang');
        $lang = JLanguage::getInstance($locale, $debug);

        return $lang;
    }    


/*************config*********************/
    
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

/*******************document / buildpage ***************/

    /**
     * Get a document object.
     *
     * Returns the global {@link JDocument} object, only creating it if it doesn't already exist.
     *
     * @return  JDocument object
     *
     * @see     JDocument
     * @since   11.1
     */
    public static function getDocument()
    {
        if (!self::$document)
        {
            self::$document = self::createDocument();
        }

        return self::$document;
    }
    
    /**
     * Create a document object
     *
     * @return  JDocument object
     *
     * @see     JDocument
     * @since   11.1
     */
    protected static function createDocument()
    {
        $lang = self::getLanguage();

        $input = self::getApplication()->input;
        $type = $input->get('format', 'html', 'cmd');

        $version = new JVersion;

        $attributes = array(
            'charset'      => 'utf-8',
            'lineend'      => 'unix',
            'tab'          => "\t",
            'language'     => $lang->getTag(),
            'direction'    => $lang->isRtl() ? 'rtl' : 'ltr',
            'mediaversion' => $version->getMediaVersion(),
        );

        return JDocument::getInstance($type, $attributes);
    }    
    
/***********************mailer*****************************/ 

    /**
     * Get a mailer object.
     *
     * Returns the global {@link JMail} object, only creating it if it doesn't already exist.
     *
     * @return  JMail object
     *
     * @see     JMail
     * @since   11.1
     */
    public static function getMailer()
    {
        if (!self::$mailer)
        {
            self::$mailer = self::createMailer();
        }

        $copy = clone self::$mailer;

        return $copy;
    }

    /**
     * Create a mailer object
     *
     * @return  JMail object
     *
     * @see     JMail
     * @since   11.1
     */
    protected static function createMailer()
    {
        $conf = self::getConfig();

        $smtpauth = ($conf->get('smtpauth') == 0) ? null : 1;
        $smtpuser = $conf->get('smtpuser');
        $smtppass = $conf->get('smtppass');
        $smtphost = $conf->get('smtphost');
        $smtpsecure = $conf->get('smtpsecure');
        $smtpport = $conf->get('smtpport');
        $mailfrom = $conf->get('mailfrom');
        $fromname = $conf->get('fromname');
        $mailer = $conf->get('mailer');

        // Create a JMail object
        $mail = JMail::getInstance();

        // Clean the email address
        $mailfrom = JMailHelper::cleanLine($mailfrom);

        // Set default sender without Reply-to if the mailfrom is a valid address
        if (JMailHelper::isEmailAddress($mailfrom))
        {
            // Wrap in try/catch to catch phpmailerExceptions if it is throwing them
            try
            {
                // Check for a false return value if exception throwing is disabled
                if ($mail->setFrom($mailfrom, JMailHelper::cleanLine($fromname), false) === false)
                {
                    JLog::add(__METHOD__ . '() could not set the sender data.', JLog::WARNING, 'mail');
                }
            }
            catch (phpmailerException $e)
            {
                JLog::add(__METHOD__ . '() could not set the sender data.', JLog::WARNING, 'mail');
            }
        }

        // Default mailer is to use PHP's mail function
        switch ($mailer)
        {
            case 'smtp':
                $mail->useSmtp($smtpauth, $smtphost, $smtpuser, $smtppass, $smtpsecure, $smtpport);
                break;

            case 'sendmail':
                $mail->isSendmail();
                break;

            default:
                $mail->isMail();
                break;
        }

        return $mail;
    }
    
/******************************data*********************/
    
// D:\websites\htdocs\quantumwarp.com\libraries\cms\layout\base.php
    
// this seems to be the base where $this->get and $this-set is used    
    /**
     * Method to get the value from the data array
     *
     * @param   string  $key           Key to search for in the data array
     * @param   mixed   $defaultValue  Default value to return if the key is not set
     *
     * @return  mixed   Value from the data array | defaultValue if doesn't exist
     *
     * @since   3.5
     */
    public function get($key, $defaultValue = null)
    {
        return isset($this->data[$key]) ? $this->data[$key] : $defaultValue;
    }

    /**
     * Get the data being rendered
     *
     * @return  array
     *
     * @since   3.5
     */
    public function getData()
    {
        return $this->data;
    }
    
    /**
     * Method to set a value in the data array. Example: $layout->set('items', $items);
     *
     * @param   string  $key    Key for the data array
     * @param   mixed   $value  Value to assign to the key
     *
     * @return  self
     *
     * @since   3.5
     */
    public function set($key, $value)
    {
        $this->data[(string) $key] = $value;

        return $this;
    }

    /**
     * Set the the data passed the layout
     *
     * @param   array  $data  Array with the data for the layout
     *
     * @return  self
     *
     * @since   3.5
     */
    public function setData(array $data)
    {
        $this->data = $data;

        return $this;
    }
    
/*******************************this object******************************/
    
    
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
    public function getApp()
    {
        $instance = self::getInstance();        

        if (is_null($id))
        {
            if (!($instance instanceof QFactory))
            {
                $instance = self::getInstance();
            }
        }
        // Check if we have a string as the id or if the numeric id is the current instance
        elseif (!($instance instanceof QFactory))
        {
            $instance = QUser::getInstance();
        }

        return $instance;
    }

    /*************************************other********************************************/
    
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
        $conf = QFactory::$config;
        return md5($conf->get('secretKey') . $seed);
    } 
    
}