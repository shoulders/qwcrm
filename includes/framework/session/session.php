<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

//class QSession extends QFactory {    
class QSession {     

    protected $db = null;
    protected $config = null;
    protected $user = null; 
    
   // protected $data;
    private $_force_ssl;
    //protected $_security = null;
    
    //protected $_expire = 900;    
    //protected $_state = 'inactive';    
    //private $started = false;
    //private $closed = false;
    
    /**
     * Internal state.
     * One of 'inactive'|'active'|'expired'|'destroyed'|'error'
     *
     * @var    string
     * @see    QSession::getState()
     * @since  11.1
     */
    protected $_state = 'inactive';

    /**
     * Maximum age of unused session in seconds
     *
     * @var    string
     * @since  11.1
     */
    protected $_expire = 900;

    /**
     * The session store object.   none(php)/database
     *
     * @var    QSessionStorage
     * @since  11.1
     */
    protected $_store = null;

    /**
     * Security policy.
     * List of checks that will be done.
     *
     * Default values:
     * - fix_browser
     * - fix_adress
     *
     * @var    array
     * @since  11.1
     */
    protected $_security = array('fix_browser');

    /**
     * The type of storage for the session.
     *
     * @var    string
     * @since  12.2
     */
    protected $storeName;

    /**
     * Internal data store for the session data  (i.e. those values stored in the cookie or session temp file)
     *
     * @var  \Joomla\Registry\Registry
     */
    protected $data;
    
    /**
     * Has the session been started
     *
     * @var    boolean
     * @since  3.5
     */
    private $started = false;

    /**
     * Has the session been closed
     *
     * @var    boolean
     * @since  3.5
     */
    private $closed = false;    
    
 
    
    public function __construct(array $options = null)
    {
        $this->db       = QFactory::getDbo();
        $this->config   = QFactory::getConfig();
        $this->user     = QFactory::getUser();
        
        // sessionname = PHPSESSID - this is used also for the cookie name
        // sessionID   = q1r4unbg4rnubkmocidcu37qg5 - not given until session started (SID) - this is stored within the sesion cookie
        // the temp file on the server =   sessionname . sessionID :   PHPSESSIDq1r4unbg4rnubkmocidcu37qg5
        // data can be stored within it.
        
        // If no options supplied used the default settings
        if($options === null) { $options = $this->buildSessionOptions(); }
       
        // set session options passed by array (session name, session id, expiry time, security? + set session.gc_maxlifetime)
        $this->_setOptions($options);

        // Initialize the data variable, let's avoid fatal error if the session is not corretly started (ie in CLI). - this object is eventually iterated into a json and thenm loaded into the databse 'data'
        $this->data = new Registry;

        // Add the session name to the registry. (site /admin) - The Session name is the name of the cookie/url param stores the session_id. By default when you create a session, a special cookie will be created normally with the name PHPSESSID, the session_id, is the value of that cookie that later identifies you. 
        if (is_null($this->data->get('session_name')))
        {
            $this->data->set('session_name', $this->getName());
        }      
      
        // Clear any existing sessions - Need to destroy any existing sessions started with session.auto_start
        /*if ($this->getId())
        {
            $this->allClear();
        }*/
        
        // Remove expired sessions for the session table
        $this->removeExpiredSessions();
        
        // Starts the session
        $this->start();
        
        // If the #__session record doesn't exist, add it to the database - after session started otherwise you dont get a session_id();
        $this->checkSessionDbExists();
    }
    
    
/******************* Start Operations *******************/    
 
    /**                             - builds the options array - this can be overriden by supplying the parameters or just moving the function out of session
     * Build the session options
     *
     * @return  session options
     *
     */
   public function buildSessionOptions() {    
        
        // Use client name (site/admin) for the session name (this does get hashed to something else in $session _setOptions())
        $name = QFactory::$siteName;

        // Calculate the session lifetime. - this just changes minutes into seconds and could be removed
        $expire = ($this->config->get('session_lifetime') ? $this->config->get('session_lifetime') * 60 : 900);

        // Initialization the options for QSession.
        $options = array(
            'name'   => $name,
            'expire' => $expire,
        );
        
        // Set force_ssl for the session and cookie to be SSL
        if($this->config->get('force_ssl'))    
        {
            $options['force_ssl'] = true;
        }
       
        return $options;
   } 
   
    /**                             - does some checks on the session before passing it to the next stage : start()|_start()|doSessionStart()
     * Start a session.
     *
     * @return  void
     *
     * @since   12.2
     */
    public function start()
    {
        if ($this->getState() === 'active')
        {
            return;
        }

        $this->_start();

        $this->_state = 'active';

        // Initialise the session
        $this->_setCounter();
        $this->_setTimers();

        // Perform security checks
        if (!$this->_validate())
        {
            // If the session isn't valid because it expired try to restart it
            // else destroy it.
            if ($this->_state === 'expired')
            {
                $this->restart();
            }
            else
            {
                $this->destroy();
            }
        }

        /*if ($this->_dispatcher instanceof JEventDispatcher)
        {
            $this->_dispatcher->trigger('onAfterSessionStart');
        }*/        
        
        //// onAfterSessionStart - inserts a Registry and User object into the data - might
        $this->afterSessionStart();
        
    }

    /**                            - this reads the stored 'registry' data from the php session file - // DO I NEED THIS - this get the data out of the session file and places it in the registry
     * Start a session.
     *
     * Creates a session (or resumes the current one based on the state of the session)
     *
     * @return  boolean  true on success
     *
     * @since   11.1
     */
    protected function _start()
    {
        if (!$this->isStarted())
        {
            $this->doSessionStart();
        }

        // Ok let's unserialize the whole thing
        // Try loading data from the session into $this->data
        if (isset($_SESSION['qwcrm']) && !empty($_SESSION['qwcrm']))
        {
            $data = $_SESSION['qwcrm'];

            $data = base64_decode($data);

            $this->data = unserialize($data);
        }

        // Temporary, PARTIAL, data migration of existing session data to avoid logout on update from J < 3.4.7        
        if (isset($_SESSION['__default']) && !empty($_SESSION['__default']))
        {
            $migratableKeys = array(
                'user',
                'session.token',
                'session.counter',
                'session.timer.start',
                'session.timer.last',
                'session.timer.now'
            );

            foreach ($migratableKeys as $migratableKey)
            {
                if (!empty($_SESSION['__default'][$migratableKey]))
                {
                    // Don't overwrite existing session data
                    if (!is_null($this->data->get('__default.' . $migratableKey, null)))
                    {
                        continue;
                    }

                    $this->data->set('__default.' . $migratableKey, $_SESSION['__default'][$migratableKey]);
                    unset($_SESSION['__default'][$migratableKey]);
                }
            }

            /**
             * Finally, empty the __default key since we no longer need it. Don't unset it completely, we need this
             * for the administrator/components/com_admin/script.php to detect upgraded sessions and perform a full
             * session cleanup.
             */
            $_SESSION['__default'] = array();
        }

        return true;
    }    
    
    /**                                       - this checks to make sure the session is not already running and then starts it
     * Performs the session start mechanism
     *
     * @return  void
     *
     * @since   3.5.1
     * @throws  RuntimeException If something goes wrong starting the session.
     */
    private function doSessionStart()
    {
        // Register our function as shutdown method, so we can manipulate it - this will run $this->save() as the last operation
        register_shutdown_function(array($this, 'save'));
        
        // Disable the cache limiter
        session_cache_limiter('none');

        /*
         * Extended checks to determine if the session has already been started
         */

        // use the native API to check for active session
        if (version_compare(PHP_VERSION, '5.4', 'ge') && PHP_SESSION_ACTIVE === session_status())
        {
            throw new RuntimeException('Failed to start the session: already started by PHP.');
        }

        // If we are using cookies (default true) and headers have already been started (early output) - is this needed ?
        if (ini_get('session.use_cookies') && headers_sent($file, $line))
        {
            throw new RuntimeException(sprintf('Failed to start the session because headers have already been sent by "%s" at line %d.', $file, $line));
        }

        // Ok to try and start the session
        if (!session_start())
        {
            throw new RuntimeException('Failed to start the session');
        }

        // Mark ourselves as started
        $this->started = true;
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
    public function checkSessionDbExists()
    {          
        $sql = "SELECT session_id FROM ".PRFX."session WHERE session_id = " . $this->db->qstr( $this->getId() );        
        $rs = $this->db->Execute($sql);
        $exists = $rs->RecordCount();
                
        // If the session record doesn't exist initialise it.
        if (!$exists)
        {
            $time = $this->isNew() ? time() : $this->data->get('session.timer.start');

            // Set up the record to insert            
            $record['session_id']  = $this->getId()  ;
            $record['guest']       = (int) $this->user->guest;
            $record['time']        = (int) $time;
            $record['userid']      = (int) $this->user->id;
            $record['username']    = $this->user->username;                 
           
            // if login not shared between site and admin (joomla thing)
            if (!$this->config->get('shared_session', '0'))
            {
                $record['client_id'] = (int) QFactory::getClientId();
            }            

            // If the insert failed, exit the application.
            try
            {
                $this->db->AutoExecute(PRFX.'session', $record, 'INSERT');                
            }
            catch (RuntimeException $e)
            {
                throw new RuntimeException(JText::_('JERROR_SESSION_STARTUP'), $e->getCode(), $e);
            }
        }
    } 
       
      
   
/******************* State Operations *******************/      

    /**
     * Restart an expired or locked session.
     *
     * @return  boolean  True on success
     *
     * @see     QSession::destroy()
     * @since   11.1
     */
    public function restart()
    {
        $this->destroy();

        if ($this->getState() !== 'destroyed')
        {
            // @TODO :: generated error here
            return false;
        }

        // Re-register the session handler after a session has been destroyed, to avoid PHP bug
        //$this->_store->register();

        $this->_state = 'restart';

        // Regenerate session id
        $this->_start();
        $this->regenerate(true, null);
        $this->_state = 'active';

        if (!$this->_validate())
        {
            /**
             * Destroy the session if it's not valid - we can't restart the session here unlike in the start method
             * else we risk recursion.
             */
            $this->destroy();
        }

        $this->_setCounter();

        return true;
    }

    /**                                                                     - I dont thing this is used anywhere
     * Create a new session and copy variables from the old one
     *
     * @return  boolean $result true on success
     *
     * @since   11.1
     */
    public function fork()
    {
        if ($this->getState() !== 'active')
        {
            // @TODO :: generated error here
            return false;
        }

        // Keep session config
        $cookie = session_get_cookie_params();

        // Re-register the session store after a session has been destroyed, to avoid PHP bug
        //$this->_store->register();

        // Restore config
        session_set_cookie_params($cookie['lifetime'], $cookie['path'], $cookie['domain'], $cookie['secure'], true);

        // Restart session with new id
        $this->regenerate(true, null);
        $this->start();

        return true;
    }
    
    /**
     * Regenerates ID that represents this storage.
     *
     * Note regenerate+destroy should not clear the session data in memory only delete the session data from persistent storage.
     *
     * @param   boolean  $destroy   Destroy session when regenerating?
     * @param   integer  $lifetime  Sets the cookie lifetime for the session cookie. A null value will leave the system settings unchanged,
     *                              0 sets the cookie to expire with browser session. Time is in seconds, and is not a Unix timestamp.
     *
     * @return  boolean  True if session regenerated, false if error
     *
     * @since   3.5
     */
    public function regenerate($destroy = false, $lifetime = null)
    {
        if (null !== $lifetime)
        {
            ini_set('session.cookie_lifetime', $lifetime);
        }

        $return = session_regenerate_id($destroy);

        // Workaround for https://bugs.php.net/bug.php?id=61470 as suggested by David Grudl
        session_write_close();
        $this->closed = true;

        if (isset($_SESSION))
        {
            $backup = $_SESSION;
            $this->doSessionStart();
            $_SESSION = $backup;
        }
        else
        {
            $this->doSessionStart();
        }

        return $return;
    }

    
/******************* Close Operations *******************/    
  
    /**
     * Writes session data and ends session
     *
     * Session data is usually stored after your script terminated without the need
     * to call QSession::close(), but as session data is locked to prevent concurrent
     * writes only one script may operate on a session at any time. When using
     * framesets together with sessions you will experience the frames loading one
     * by one due to this locking. You can reduce the time needed to load all the
     * frames by ending the session as soon as all changes to session variables are
     * done.
     *
     * @return  void
     *
     * @since   11.1
     */
    public function close()
    {
        $this->save();
        $this->_state = 'inactive';
    }
    
    /**                                                                -  this writes 'registry' to the php session file
     * Force the session to be saved and closed.
     *
     * This method must invoke session_write_close() unless this interface is used for a storage object design for unit or functional testing where
     * a real PHP session would interfere with testing, in which case it should actually persist the session data if required.
     *
     * @return  void
     *
     * @see     session_write_close()
     * @since   3.5
     */
    public function save()
    {
        // Verify if the session is active
        if ((version_compare(PHP_VERSION, '5.4', 'ge') && PHP_SESSION_ACTIVE === session_status()))
        {
            //$session = QFactory::getSession();
            $data    = $this->getData();

            // Before storing it, let's serialize and encode the Registry object
            $_SESSION['qwcrm'] = base64_encode(serialize($data));

            session_write_close();

            $this->closed  = true;
            $this->started = false;
        }
    }

/******************* Delete Operations *******************/    
    
    /** session.php                                                       - not sure this is used anywhere
     * 
     * Unset data from the session store
     *
     * @param   string  $name       Name of variable
     * @param   string  $namespace  Namespace to use, default to 'default'
     *
     * @return  mixed   The value from session or NULL if not set
     *
     * @since   11.1
     */ 
    public function clear($name, $namespace = 'default')
    {
        
        if (!$this->isActive())
        {
            $this->start();
        }

        // Add prefix to namespace to avoid collisions
        $namespace = '__' . $namespace;

        if ($this->getState() !== 'active')
        {
            // @TODO :: generated error here
            return;
        }

        return $this->data->set($namespace . '.' . $name, null);
    }    
    
       /**
     * Frees all session variables and destroys all data registered to a session
     *
     * This method resets the data pointer and destroys all of the data associated
     * with the current session in its storage. It forces a new session to be
     * started after this method is called. It does not unset the session cookie.
     *
     * @return  boolean  True on success
     *
     * @see     session_destroy()
     * @see     session_unset()
     * @since   11.1
     */
    public function destroy()
    {
        // Session was already destroyed
        if ($this->getState() === 'destroyed')
        {
            return true;
        }

        // Kill session
        $this->allClear();

        // Create new data storage
        $this->data = new Registry;

        $this->_state = 'destroyed';

        return true;
    }

    /** native.php                  - is this needed check logic
     * 
     * Clear all session data in memory.
     *
     * @return  void
     *
     * @since   3.5
     * was called clear in the handler
     * */
     
    public function allClear()
    {
        // Need to destroy any existing sessions started with session.auto_start
        if ($this->getId())
        {
            session_unset();
            session_destroy();
        }

        $this->closed  = true;
        $this->started = false;
    }   

/******************* Token Operations *******************/

     
   /**
     * Get a session token, if a token isn't set yet one will be generated.
     *
     * Tokens are used to secure forms from spamming attacks. Once a token
     * has been generated the system will check the post request to see if
     * it is present, if not it will invalidate the session.
     *
     * @param   boolean  $forceNew  If true, force a new token to be created
     *
     * @return  string  The session token
     *
     * @since   11.1
     */
    public function getToken($forceNew = false)
    {
        $token = $this->get('session.token');

        // Create a token
        if ($token === null || $forceNew)
        {
            $token = $this->_createToken();
            $this->set('session.token', $token);
        }

        return $token;
    }

    /**
     * Method to determine a hash for anti-spoofing variable names
     *
     * @param   boolean  $forceNew  If true, force a new token to be created
     *
     * @return  string  Hashed var name
     *
     * @since   11.1
     */
    public static function getFormToken($forceNew = false)
    {
        $user    = QFactory::getUser();
        $session = QFactory::getSession();

        return QFactory::getHash($user->get('id', 0) . $session->getToken($forceNew));
    } 


    /**
     * Checks for a form token in the request.
     *
     * Use in conjunction with JHtml::_('form.token') or QSession::getFormToken.
     *
     * @param   string  $method  The request method in which to look for the token key.
     *
     * @return  boolean  True if found and valid, false otherwise.
     *
     * @since   12.1
     */
    public static function checkToken($method = 'post')
    {
        $token = self::getFormToken();
        $app = QFactory::getApplication();

        if (!$app->input->$method->get($token, '', 'alnum'))
        {
            if (QFactory::getSession()->isNew())
            {
                // Redirect to login screen.
                //$app->enqueueMessage(JText::_('JLIB_ENVIRONMENT_SESSION_EXPIRED'), 'warning');
                force_page('index.php');

                return true;
            }

            return false;
        }

        return true;
    }
    /**
     * Method to determine if a token exists in the session. If not the
     * session will be set to expired
     *
     * @param   string   $tCheck       Hashed token to be verified
     * @param   boolean  $forceExpire  If true, expires the session
     *
     * @return  boolean
     *
     * @since   11.1
     */
    public function hasToken($tCheck, $forceExpire = true)
    {
        // Check if a token exists in the session
        $tStored = $this->get('session.token');

        // Check token
        if (($tStored !== $tCheck))
        {
            if ($forceExpire)
            {
                $this->_state = 'expired';
            }

            return false;
        }

        return true;
    }
    
    /**
     * Create a token-string
     *
     * @param   integer  $length  Length of string
     *
     * @return  string  Generated token
     *
     * @since   11.1
     */
    protected function _createToken($length = 32)
    {
        return JUserHelper::genRandomPassword($length);
    }
    
/******************* Get Operations *******************/

    /**
     * Get data from the session store
     *
     * @param   string  $name       Name of a variable
     * @param   mixed   $default    Default value of a variable if not set
     * @param   string  $namespace  Namespace to use, default to 'default'
     *
     * @return  mixed  Value of a variable
     *
     * @since   11.1
     */
    public function get($name, $default = null, $namespace = 'default')
    {
        if (!$this->isActive())
        {
            $this->start();
        }

        // Add prefix to namespace to avoid collisions
        $namespace = '__' . $namespace;

        if ($this->getState() === 'destroyed')
        {
            // @TODO :: generated error here
            $error = null;

            return $error;
        }

        return $this->data->get($namespace . '.' . $name, $default);
    }    

    /**
     * Magic method to get read-only access to properties.
     *
     * @param   string  $name  Name of property to retrieve
     *
     * @return  mixed   The value of the property
     *
     * @since   12.2
     */
    public function __get($name)
    {
        if ($name === 'storeName')
        {
            return $this->$name;
        }

        if ($name === 'state' || $name === 'expire')
        {
            $property = '_' . $name;

            return $this->$property;
        }
    }

    /**
     * Returns a clone of the internal data pointer
     *
     * @return  Registry
     */
    public function getData()
    {
        return $this->data;
    }    
    
    /**
     * Get session name
     *
     * @return  string  The session name
     *
     * @since   11.1
     */
    public function getName()
    {
        if ($this->getState() === 'destroyed')
        {
            // @TODO : raise error
            return;
        }

        return session_name();
    }

    /**
     * Get session id
     *
     * @return  string  The session name
     *
     * @since   11.1
     */
    public function getId()
    {
        if ($this->getState() === 'destroyed')
        {
            // @TODO : raise error
            return;
        }

        return session_id();
    }  
  
    /**
     * Get current state of session
     *
     * @return  string  The session state
     *
     * @since   11.1
     */
    public function getState()
    {
        return $this->_state;
    }

    /**
     * Get expiration time in seconds
     *
     * @return  integer  The session expiration time in seconds
     *
     * @since   11.1
     */
    public function getExpire()
    {
        return $this->_expire;
    }       
   
    
/******************* Set Operations *******************/    
    
    /**
     * Set data into the session store.
     *
     * @param   string  $name       Name of a variable.
     * @param   mixed   $value      Value of a variable.
     * @param   string  $namespace  Namespace to use, default to 'default'.
     *
     * @return  mixed  Old value of a variable.
     *
     * @since   11.1
     */
    public function set($name, $value = null, $namespace = 'default')
    {
        if (!$this->isActive())
        {
            $this->start();
        }

        // Add prefix to namespace to avoid collisions
        $namespace = '__' . $namespace;

        if ($this->getState() !== 'active')
        {
            // @TODO :: generated error here
            return;
        }

        $prev = $this->data->get($namespace . '.' . $name, null);
        $this->data->set($namespace . '.' . $name, $value);

        return $prev;        
    } 
    
    
    /**
     * Sets the session ID
     *
     * @param   string  $id  The session ID
     *
     * @return  void
     *
     * @since   3.5
     * @throws  LogicException
     */
    public function setId($id)
    {
        if ($this->isStarted())
        {
            throw new LogicException('Cannot change the ID of an active session');
        }

        session_id($id);
    }    

    /**
     * Sets the session name
     *
     * @param   string  $name  The name of the session
     *
     * @return  void
     *
     * @since   3.5
     * @throws  LogicException
     */
    public function setName($name)
    {
        if ($this->isStarted())
        {
            throw new LogicException('Cannot change the name of an active session');
        }

        session_name($name);
    }      
    
    /**
     * Set additional session options
     *
     * @param   array  $options  List of parameter
     *
     * @return  boolean  True on success
     *
     * @since   11.1
     */
    protected function _setOptions(array $options)
    {
        //print_r($options);
        
        // Set name
        if (isset($options['name']))
        {        
            // Generate a 'random'/hashed session name
            //$this->setName(md5(md5($options['name'])));
            
            // Generate a 'random' session name (md5 hash) using a seed/secret key            
            $this->setName(md5(QFactory::getHash($options['name'])));            
            
        }

        // Set id
        if (isset($options['id']))
        {
            $this->setId($options['id']);
        }

        // Set expire time
        if (isset($options['expire']))
        {
            $this->_expire = $options['expire'];
        }
        
        // Set Force SSL
        if (isset($options['force_ssl']))
        {
            $this->_force_ssl = $options['force_ssl'];
        }        

        /* Get security options
        if (isset($options['security']))
        {
            $this->_security = explode(',', $options['security']);
        }*/

        // Sync the session maxlifetime
        ini_set('session.gc_maxlifetime', $this->_expire);

        return true;
    } 

    
    /**
     * Set counter of session usage
     *
     * @return  boolean  True on success
     *
     * @since   11.1
     */
    protected function _setCounter()
    {
        $counter = $this->get('session.counter', 0);
        ++$counter;

        $this->set('session.counter', $counter);

        return true;
    }

    /**
     * Set the session timers
     *
     * @return  boolean  True on success
     *
     * @since   11.1
     */
    protected function _setTimers()
    {
        if (!$this->has('session.timer.start'))
        {
            $start = time();

            $this->set('session.timer.start', $start);
            $this->set('session.timer.last', $start);
            $this->set('session.timer.now', $start);
        }

        $this->set('session.timer.last', $this->get('session.timer.now'));
        $this->set('session.timer.now', time());

        return true;
    }

/******************* Other Checks *******************/      
    
    /**
     * Check whether data exists in the session store
     *
     * @param   string  $name       Name of variable
     * @param   string  $namespace  Namespace to use, default to 'default'
     *
     * @return  boolean  True if the variable exists
     *
     * @since   11.1
     */
    public function has($name, $namespace = 'default')
    {
        if (!$this->isActive())
        {
            $this->start();
        }

        // Add prefix to namespace to avoid collisions.
        $namespace = '__' . $namespace;

        if ($this->getState() !== 'active')
        {
            // @TODO :: generated error here
            return;
        }

        return !is_null($this->data->get($namespace . '.' . $name, null));
    }
    
    /**
     * Do some checks for security reason
     *
     * - timeout check (expire)
     * - ip-fixiation
     * - browser-fixiation
     *
     * If one check failed, session data has to be cleaned.
     *
     * @param   boolean  $restart  Reactivate session
     *
     * @return  boolean  True on success
     *
     * @see     http://shiflett.org/articles/the-truth-about-sessions
     * @since   11.1
     */
    protected function _validate($restart = false)
    {
        // Allow to restart a session
        if ($restart)
        {
            $this->_state = 'active';

            $this->set('session.client.address', null);
            $this->set('session.client.forwarded', null);
            $this->set('session.client.browser', null);
            $this->set('session.token', null);
        }

        // Check if session has expired
        if ($this->getExpire())
        {
            $curTime = $this->get('session.timer.now', 0);
            $maxTime = $this->get('session.timer.last', 0) + $this->getExpire();

            // Empty session variables
            if ($maxTime < $curTime)
            {
                $this->_state = 'expired';

                return false;
            }
        }

        // Check for client address
        if (in_array('fix_adress', $this->_security) && isset($_SERVER['REMOTE_ADDR'])
            && filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP) !== false)
        {
            $ip = $this->get('session.client.address');

            if ($ip === null)
            {
                $this->set('session.client.address', $_SERVER['REMOTE_ADDR']);
            }
            elseif ($_SERVER['REMOTE_ADDR'] !== $ip)
            {
                $this->_state = 'error';

                return false;
            }
        }

        // Record proxy forwarded for in the session in case we need it later
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && filter_var($_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP) !== false)
        {
            $this->set('session.client.forwarded', $_SERVER['HTTP_X_FORWARDED_FOR']);
        }

        return true;
    } 
    
/******************* State is Checks *******************/      

   
    /**
     * Shorthand to check if the session is active
     *
     * @return  boolean
     *
     * @since   12.2
     */
    public function isActive()
    {
        return (bool) ($this->getState() == 'active');
    }

    /**
     * Check whether this session is currently created
     *
     * @return  boolean  True on success.
     *
     * @since   11.1
     */
    public function isNew()
    {
        return (bool) ($this->data->get('session.counter') === 1);
    }    
    
    
    /**
     * Checks if the session is started.
     *
     * @return  boolean  True if started, false otherwise.
     *
     * @since   3.5
     */
    public function isStarted()
    {
        return $this->started;
    }       
    


/******************* Misc *******************/
    
    /*
    * Check the session table for stale entries
    * 
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
           $sql = "DELETE FROM ".PRFX."session WHERE time < " . ($time - $this->getExpire());            
           $this->db->Execute($sql);
       }  
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
        //$session = QFactory::getSession();

        if ($this->isNew())
        {
            $this->data->set('registry', new Registry);
            $this->data->set('user', new QUser);
        }
    }
 
}