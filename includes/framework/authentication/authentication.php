<?php
// D:\websites\htdocs\quantumwarp.com\libraries\joomla\authentication\authentication.php
/**
 * @package     Joomla.Platform
 * @subpackage  Authentication
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_QWEXEC') or die;


/**
 * Authentication class, provides an interface for the Joomla authentication system
 *
 * @since  11.1
 */
class QAuthentication
{
    // Shared success status
    /**
     * This is the status code returned when the authentication is success (permit login)
     * @const  STATUS_SUCCESS successful response
     * @since  11.2
     */
    const STATUS_SUCCESS = 1;

    // These are for authentication purposes (username and password is valid)
    /**
     * Status to indicate cancellation of authentication (unused)
     * @const  STATUS_CANCEL cancelled request (unused)
     * @since  11.2
     */
    const STATUS_CANCEL = 2;

    /**
     * This is the status code returned when the authentication failed (prevent login if no success)
     * @const  STATUS_FAILURE failed request
     * @since  11.2
     */
    const STATUS_FAILURE = 4;

    // These are for authorisation purposes (can the user login)
    /**
     * This is the status code returned when the account has expired (prevent login)
     * @const  STATUS_EXPIRED an expired account (will prevent login)
     * @since  11.2
     */
    const STATUS_EXPIRED = 8;

    /**
     * This is the status code returned when the account has been denied (prevent login)
     * @const  STATUS_DENIED denied request (will prevent login)
     * @since  11.2
     */
    const STATUS_DENIED = 16;

    /**
     * This is the status code returned when the account doesn't exist (not an error)
     * @const  STATUS_UNKNOWN unknown account (won't permit or prevent login)
     * @since  11.2
     */
    const STATUS_UNKNOWN = 32;

    /**
     * An array of Observer objects to notify
     *
     * @var    array
     * @since  12.1
     */
    protected $observers = array();

    /**
     * The state of the observable object
     *
     * @var    mixed
     * @since  12.1
     */
    protected $state = null;

    /**
     * A multi dimensional array of [function][] = key for observers
     *
     * @var    array
     * @since  12.1
     */
    protected $methods = array();

    /**
     * @var    QAuthentication  QAuthentication instances container.
     * @since  11.3
     */
    protected static $instance;

    /**
     * Constructor
     *
     * @since   11.1
     */
    public function __construct()
    {
        /*
        $isLoaded = QPluginHelper::importPlugin('authentication');

        if (!$isLoaded)
        {
            JLog::add(JText::_('JLIB_USER_ERROR_AUTHENTICATION_LIBRARIES'), JLog::WARNING, 'jerror');
        }*/
    }

    /**
     * Returns the global authentication object, only creating it
     * if it doesn't already exist.
     *
     * @return  QAuthentication  The global QAuthentication object
     *
     * @since   11.1
     */
    public static function getInstance()
    {
        if (empty(self::$instance))
        {
            self::$instance = new QAuthentication;
        }

        return self::$instance;
    }

    /**
     * Get the state of the QAuthentication object
     *
     * @return  mixed    The state of the object.
     *
     * @since   11.1
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Attach an observer object
     *
     * @param   object  $observer  An observer object to attach
     *
     * @return  void
     *
     * @since   11.1
     */
    public function attach($observer)
    {
        if (is_array($observer))
        {
            if (!isset($observer['handler']) || !isset($observer['event']) || !is_callable($observer['handler']))
            {
                return;
            }

            // Make sure we haven't already attached this array as an observer
            foreach ($this->observers as $check)
            {
                if (is_array($check) && $check['event'] == $observer['event'] && $check['handler'] == $observer['handler'])
                {
                    return;
                }
            }

            $this->observers[] = $observer;
            end($this->observers);
            $methods = array($observer['event']);
        }
        else
        {
            if (!($observer instanceof QAuthentication))
            {
                return;
            }

            // Make sure we haven't already attached this object as an observer
            $class = get_class($observer);

            foreach ($this->observers as $check)
            {
                if ($check instanceof $class)
                {
                    return;
                }
            }

            $this->observers[] = $observer;
            $methods = array_diff(get_class_methods($observer), get_class_methods('JPlugin'));
        }

        $key = key($this->observers);

        foreach ($methods as $method)
        {
            $method = strtolower($method);

            if (!isset($this->methods[$method]))
            {
                $this->methods[$method] = array();
            }

            $this->methods[$method][] = $key;
        }
    }

    /**
     * Detach an observer object
     *
     * @param   object  $observer  An observer object to detach.
     *
     * @return  boolean  True if the observer object was detached.
     *
     * @since   11.1
     */
    public function detach($observer)
    {
        $retval = false;

        $key = array_search($observer, $this->observers);

        if ($key !== false)
        {
            unset($this->observers[$key]);
            $retval = true;

            foreach ($this->methods as &$method)
            {
                $k = array_search($key, $method);

                if ($k !== false)
                {
                    unset($method[$k]);
                }
            }
        }

        return $retval;
    }

    /**
     * Finds out if a set of login credentials are valid by asking all observing
     * objects to run their respective authentication routines.
     *
     * @param   array  $credentials  Array holding the user credentials.
     * @param   array  $options      Array holding user options.
     *
     * @return  QAuthenticationResponse  Response object with status variable filled in for last plugin or first successful plugin.
     *
     * @see     QAuthenticationResponse
     * @since   11.1
     * 
     * will read cookie and then qwcrm standard username and password
     */
    public function authenticate($credentials, $options = array())
    {
        // Get plugins
        //$plugins = QPluginHelper::getPlugin('authentication');
        // this checks to see if a cookie can authenticate
        $plugins = array(array ('className' => 'PlgAuthenticationCookie',
                                'type' => 'Authentication',
                                'name' => 'Cookie'),
                         array ('className' => 'PlgAuthenticationQwcrm',
                                'type' => 'Authentication',
                                'name' => 'Qwcrm'));
        
        
        
        // Create authentication response - holds the response(s)
        $response = new QAuthenticationResponse;

        /*
         * Loop through the plugins and check if the credentials can be used to authenticate
         * the user
         *
         * Any errors raised in the plugin should be returned via the QAuthenticationResponse
         * and handled appropriately.
         */
        foreach ($plugins as $plugin)
        {
            $className = 'plg' . $plugin['type'] . $plugin['name'];
            //$className = $plugin->className; // i added this

            if (class_exists($className))
            {
                $plugin = new $className($this, (array) $plugin);
            }
            else
            {
                // Bail here if the plugin can't be created
                //JLog::add(JText::sprintf('JLIB_USER_ERROR_AUTHENTICATION_FAILED_LOAD_PLUGIN', $className), JLog::WARNING, 'jerror');
                continue;
            }

            // Try to authenticate
            $plugin->onUserAuthenticate($credentials, $options, $response);
            //print_r($plugin);die;
            
            // If authentication is successful break out of the loop
            if ($response->status === self::STATUS_SUCCESS)
            {
                if (empty($response->type))
                {
                    $response->type = isset($plugin->_name) ? $plugin->_name : $plugin->name;
                }

                break;
            }
        }
        

        if (empty($response->username))
        {
            $response->username = $credentials['username'];
        }

        if (empty($response->fullname))
        {
            $response->fullname = $credentials['username'];
        }

        if (empty($response->password) && isset($credentials['password']))
        {
            $response->password = $credentials['password'];
        }
        
        //echo 'coocck';print_r($response);die;
        return $response;
    }

    /**
     * Authorises that a particular user should be able to login
     *
     * @param   QAuthenticationResponse  $response  response including username of the user to authorise
     * @param   array                    $options   list of options
     *
     * @return  array[QAuthenticationResponse]  results of authorisation
     *
     * @since  11.2
     */
    public static function authorise($response, $options = array())
    {
        
        // this is suppose to cycle through auth plugins that ahve this event and process them
        // cookie.php and qwcrm.php do not have this
        
    
        
        // if user has been blocked or deactivates return the result - i can add stuff here
        
        
        // Get plugins in case they haven't been imported already
        //QPluginHelper::importPlugin('user');
        //QPluginHelper::importPlugin('authentication');        
        //$dispatcher = JEventDispatcher::getInstance();
        //$results = $dispatcher->trigger('onUserAuthorisation', array($response, $options));
        
        $results = array();

        return $results;
    }
}