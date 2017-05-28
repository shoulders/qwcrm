<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */




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
require INCLUDES_DIR . 'framework/session.php';
require INCLUDES_DIR . 'framework/cookie.php';
require INCLUDES_DIR . 'framework/authentication.php';
require INCLUDES_DIR . 'framework/user.php';
require INCLUDES_DIR . 'framework/config.php';


/**
 * Joomla Platform Factory class.
 *
 * @since  11.1
 */
class QFramework
{


    public static $config = null;   // Global configuraiton object
    public static $session = null;  // Global session object
    public static $db = null;       // Global database object
    //private $db;
    private $smarty;                // Global smarty object
    public $cookie = null;          // Global cookie object
    public $user = null;            // Global user object (should be functions and logged in user)
    public $auth = null;            // Global authentication object (performs login etc..)
    

    /**
     * Class constructor.
     *
     * @param   JInput                 $input   An optional argument to provide dependency injection for the application's
     *                                          input object.  If the argument is a JInput object that object will become
     *                                          the application's input object, otherwise a default input object is created.
     * @param   Registry               $config  An optional argument to provide dependency injection for the application's
     *                                          config object.  If the argument is a Registry object that object will become
     *                                          the application's config object, otherwise a default config object is created.
     * @param   JApplicationWebClient  $client  An optional argument to provide dependency injection for the application's
     *                                          client object.  If the argument is a JApplicationWebClient object that object will become
     *                                          the application's client object, otherwise a default client object is created.
     *
     * @since   3.2
     */
    public function __construct($db, $smarty) {
        
        // Populate Global Objects
        $this->db = $db;
        $this->smarty = $smarty;
        
        $this->config   = new QConfig;
        $this->session  = new Session;
        $this->cookie   = new Cookie;
        //$this->registry = mew Registry; // I might add this
        //$this->user     = new User;
        $this->auth     = new Auth;

        // Enable sessions by default.
        if (is_null($this->config->get('session')))
        {
            $this->config->set('session', true);
        }

        // Set the session default name.       'site' or 'administrator' I have removed this but need to sort
        if (is_null($this->config->get('session_name')))
        {
            $this->config->set('session_name', $this->getName());
        }

        // Create the session if a session name is passed.
        if ($this->config->get('session') !== false)
        {
            $this->loadSession();
        }
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
        $db = JFactory::getDbo();
        $session = JFactory::getSession();
        $user = JFactory::getUser();

        $query = $db->getQuery(true)
            ->select($db->quoteName('session_id'))
            ->from($db->quoteName('#__session'))
            ->where($db->quoteName('session_id') . ' = ' . $db->quote($session->getId()));

        $db->setQuery($query, 0, 1);
        $exists = $db->loadResult();

        // If the session record doesn't exist initialise it.
        if (!$exists)
        {
            $query->clear();

            $time = $session->isNew() ? time() : $session->get('session.timer.start');

            $columns = array(
                $db->quoteName('session_id'),
                $db->quoteName('guest'),
                $db->quoteName('time'),
                $db->quoteName('userid'),
                $db->quoteName('username'),
            );

            $values = array(
                $db->quote($session->getId()),
                (int) $user->guest,
                $db->quote((int) $time),
                (int) $user->id,
                $db->quote($user->username),
            );

            if (!$this->get('shared_session', '0'))
            {
                $columns[] = $db->quoteName('client_id');
                $values[] = (int) $this->getClientId();
            }

            $query->insert($db->quoteName('#__session'))
                ->columns($columns)
                ->values(implode(', ', $values));

            $db->setQuery($query);

            // If the insert failed, exit the application.
            try
            {
                $db->execute();
            }
            catch (RuntimeException $e)
            {
                throw new RuntimeException(JText::_('JERROR_SESSION_STARTUP'), $e->getCode(), $e);
            }
        }
    }



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
        $name = JApplicationHelper::getHash($this->get('session_name', get_class($this)));

        // Calculate the session lifetime.
        $lifetime = ($this->get('lifetime') ? $this->get('lifetime') * 60 : 900);

        // Initialize the options for JSession.
        $options = array(
            'name'   => $name,
            'expire' => $lifetime,
        );

        // This sets SSL option on cookie depending whether admin or front - : client_Id = 1 means admin access / client_Id = 0 means frontend access
        switch ($this->getClientId())
        {
            case 0:
                if ($this->get('force_ssl') == 2)
                {
                    $options['force_ssl'] = true;
                }

                break;

            case 1:
                if ($this->get('force_ssl') >= 1)
                {
                    $options['force_ssl'] = true;
                }

                break;
        }

        $this->registerEvent('onAfterSessionStart', array($this, 'afterSessionStart'));

        /*
         * Note: The below code CANNOT change from instantiating a session via JFactory until there is a proper dependency injection container supported
         * by the application. The current default behaviours result in this method being called each time an application class is instantiated.
         * https://github.com/joomla/joomla-cms/issues/12108 explains why things will crash and burn if you ever attempt to make this change
         * without a proper dependency injection container.
         */

        $session = JFactory::getSession($options);
        $session->initialise($this->input, $this->dispatcher);

        // TODO: At some point we need to get away from having session data always in the db.
        $db = JFactory::getDbo();

        // Remove expired sessions from the database.
        $time = time();

        if ($time % 2)
        {
            // The modulus introduces a little entropy, making the flushing less accurate
            // but fires the query less than half the time.
            $query = $db->getQuery(true)
                ->delete($db->quoteName('#__session'))
                ->where($db->quoteName('time') . ' < ' . $db->quote((int) ($time - $session->getExpire())));

            $db->setQuery($query);
            $db->execute();
        }

        // Get the session handler from the configuration.
        $handler = $this->get('session_handler', 'none');

        if (($handler != 'database' && ($time % 2 || $session->isNew()))
            || ($handler == 'database' && $session->isNew()))
        {
            $this->checkSession();
        }

        // Set the session object.
        $this->session = $session;

        return $this;
    }

    /**
     * After the session has been started we need to populate it with some default values.
     *
     * @return  void
     *
     * @since   3.2
     */
    public function afterSessionStart()
    {
        $session = JFactory::getSession();

        if ($session->isNew())
        {
            $session->set('registry', new Registry);
            $session->set('user', new JUser);
        }
    }
    
    
    
    
    


    




    
    
    
    
    
    
    
    
    

   /* GET GLOBAL OBJECTS */
    
    /**
     * Get a configuration object
     *
     * Returns the global {@link JConfig} object, only creating it if it doesn't already exist.
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
    

   /* CREATE GLOBAL OBJECTS */


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
        $name = 'JConfig' . $namespace;

        // Handle the PHP configuration type.
        if ($type == 'PHP' && class_exists($name))
        {
            // Create the JConfig object
            $config = new $name;

            // Load the configuration values into the registry
            $registry->loadObject($config);
        }

        return $registry;
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
        $options['expire'] = ($conf->get('lifetime')) ? $conf->get('lifetime') * 60 : 900;

        // The session handler needs a JInput object, we can inject it without having a hard dependency to an application instance
        $input = self::$application ? self::getApplication()->input : new JInput;

        $sessionHandler = new JSessionHandlerJoomla($options);
        $sessionHandler->input = $input;

        $session = JSession::getInstance($handler, $options, $sessionHandler);

        if ($session->getState() == 'expired')
        {
            $session->restart();
        }

        return $session;
    }

    
}