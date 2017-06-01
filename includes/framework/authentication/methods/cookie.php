<?php
// D:\websites\htdocs\quantumwarp.com\plugins\authentication\cookie\cookie.php
//also
// D:\websites\htdocs\quantumwarp.com\plugins\system\remember\remember.php
/**
 * @package     Joomla.Plugin
 * @subpackage  Authentication.cookie
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_QWEXEC') or die;

/**
 * Joomla Authentication plugin
 *
 * @since  3.2
 * @note   Code based on http://jaspan.com/improved_persistent_login_cookie_best_practice
 *         and http://fishbowl.pastiche.org/2004/01/19/persistent_login_cookie_best_practice/
 */
class PlgAuthenticationCookie //extends QFramework //class PlgSystemRemember //extends JPlugin
{
    /**
     * Application object
     *
     * @var    JApplicationCms
     * @since  3.2
     */
    //protected $app;

    /**
     * Database object
     *
     * @var    JDatabaseDriver
     * @since  3.2
     */
    protected $db;
    
    private $cookie;   // the cookie obj 
    private $config;
    public $response;
    
    public function __construct()
    {
        $this->db           = JFactory::getDbo();
        $this->config       = JFactory::getConfig();
        $this->cookie       = new Cookie;
        $this->response     = new QAuthenticationResponse;  // does this need to be an object?
        $this->filter       = new JFilterInput;
        
    }   
    
    /**
     * Remember me method to run onAfterInitialise
     * Only purpose is to initialise the login authentication process if a cookie is present - this allows a silent login with the remember_me cookie
     *
     * @return  void
     *
     * @since   1.5
     * @throws  InvalidArgumentException
     */
    public function onAfterInitialise()
    {
        // Get the application if not done by JPlugin. This may happen during upgrades from Joomla 2.5.
        /*if (!$this->app)
        {
            $this->app = JFactory::getApplication();
        }*/

        // No remember me for admin.
        if (JFactory::isClient('administrator'))
        {
            return;
        }
        
        // Check for a cookie if user is not logged in - (guests are not log in)        
        if (JFactory::getUser()->get('guest'))
        {
            $cookieName = 'qwcrm_remember_me_' . QUserHelper::getShortHashedUserAgent();

            /* Try with old cookieName (pre 3.6.0) if not found
            if (!$this->app->input->cookie->get($cookieName))
            {
                $cookieName = QUserHelper::getShortHashedUserAgent();
            }*/

            $cookie = new Cookie;            
            
            // Check for the cookie
            //if ($this->app->input->cookie->get($cookieName))
            if ($cookie->get($cookieName))
            {
                $auth = JFactory::getAuth();
                $auth->login(array('username' => ''), array('silent' => true));
            }
        }
    }
    
    /**
     * This method should handle any authentication and report back to the subject
     *
     * @param   array   $credentials  Array holding the user credentials
     * @param   array   $options      Array of extra options
     * @param   object  &$response    Authentication response object
     *
     * @return  boolean
     *
     * @since   3.2
     */
    public function onUserAuthenticate($credentials, $options, &$response)
    {
        // No remember me for admin
        if (JFactory::isClient('administrator'))
        {
            return false;
        }

        // Get cookie
        $cookieName  = 'qwcrm_remember_me_' . QUserHelper::getShortHashedUserAgent();
        $cookieValue = $this->cookie->get($cookieName);        

        if (!$cookieValue)
        {
            return false;
        }

        $cookieArray = explode('.', $cookieValue);

        // Check for valid cookie value
        if (count($cookieArray) != 2)
        {
            // Destroy the cookie in the browser.
            $this->cookie->set($cookieName, false, time() - 42000, $this->config->get('cookie_path', '/'), $this->config->get('cookie_domain'));
            //setcookie($cookieName, false, time() - 42000, $this->app->get('cookie_path', '/'), $this->app->get('cookie_domain'));
            //JLog::add('Invalid cookie detected.', JLog::WARNING, 'error');

            return false;
        }

        $response->type = 'Cookie';

        // Filter series since we're going to use it in the query
        $filter = new JFilterInput;
        $series = $filter->clean($cookieArray[1], 'ALNUM');

        /* Remove expired tokens
        $query = $this->db->getQuery(true)
            ->delete('#__user_keys')
            ->where($this->db->quoteName('time') . ' < ' . $this->db->quote(time()));*/
        
        // Remove expired tokens
        $sql = "DELETE FROM ".PRFX."user_keys WHERE time < ".$this->db->qstr(time());
        
        try
        {
            //$this->db->setQuery($query)->execute();
            $this->db->Execute($sql);
        }
        catch (RuntimeException $e)
        {
            // We aren't concerned with errors from this query, carry on
        }

        /* Find the matching record if it exists.
        $query = $this->db->getQuery(true)
            ->select($this->db->quoteName(array('user_id', 'token', 'series', 'time')))
            ->from($this->db->quoteName('#__user_keys'))
            ->where($this->db->quoteName('series') . ' = ' . $this->db->quote($series))
            ->where($this->db->quoteName('uastring') . ' = ' . $this->db->quote($cookieName))
            ->order($this->db->quoteName('time') . ' DESC');*/
        
        // Find the matching record if it exists.
        $sql = "SELECT user_id, token, series, time FROM ".PRFX."user_keys WHERE series = ".$this->db->qstr($series)." AND uastring = ".$this->db->qstr($series)." ORDER BY time DESC";
        
        try
        {
            //$results = $this->db->setQuery($query)->loadObjectList();
            $rs = $this->db->Execute($sql);
            $results = $rs->GetArray; 
        }
        catch (RuntimeException $e)
        {
            $response->status = QAuthentication::STATUS_FAILURE;

            return false;
        }

        
        // If there is not exactly 1 record found
        if (count($results) !== 1)
        {
            // Destroy the cookie in the browser.
            //setcookie($cookieName, false, time() - 42000, $this->conf->get('cookie_path', '/'), $this->conf->get('cookie_domain'));
            $this->cookie->set($cookieName, false, time() - 42000, $this->config->get('cookie_path', '/'), $this->config->get('cookie_domain'));
            $response->status = QAuthentication::STATUS_FAILURE;

            return false;
        }

        // We have a user with one cookie with a valid series and a corresponding record in the database.
        if (!QUserHelper::verifyPassword($cookieArray[0], $results[0]['token']))
        {
            /*
             * This is a real attack! Either the series was guessed correctly or a cookie was stolen and used twice (once by attacker and once by victim).
             * Delete all tokens for this user!
             */
            /*$query = $this->db->getQuery(true)
                ->delete('#__user_keys')
                ->where($this->db->quoteName('user_id') . ' = ' . $this->db->quote($results[0]->user_id));*/
            
             $sql = "DELETE FROM ".PRFX."user_keys WHERE user_id = ".$this->db->qstr($results[0]['user_id']);

            try
            {
                //$this->db->setQuery($query)->execute();
                $rs = $this->db->Execute($sql);
            }
            catch (RuntimeException $e)
            {
                // Log an alert for the site admin
                JLog::add(
                    sprintf('Failed to delete cookie token for user %s with the following error: %s', $results[0]['user_id'], $e->getMessage()),
                    JLog::WARNING,
                    'security'
                );
            }
            
            // Destroy the cookie in the browser.
            $this->cookie->set($cookieName, false, time() - 42000, $this->config->get('cookie_path', '/'), $this->config->get('cookie_domain'));

            // Issue warning by email to user and/or admin?
            JLog::add(JText::sprintf('PLG_AUTH_COOKIE_ERROR_LOG_LOGIN_FAILED', $results[0]->user_id), JLog::WARNING, 'security');
            $response->status = QAuthentication::STATUS_FAILURE;

            return false;
        }

        // Make sure there really is a user with this name and get the data for the session.
        /*$query = $this->db->getQuery(true)
            ->select($this->db->quoteName(array('id', 'username', 'password')))
            ->from($this->db->quoteName('#__users'))
            ->where($this->db->quoteName('username') . ' = ' . $this->db->quote($results[0]->user_id))
            ->where($this->db->quoteName('requireReset') . ' = 0');*/
        
        $sql = "SELECT EMPLOYEE_ID, EMPLOYEE_LOGIN, EMPLOYEE_PASSWORD WHERE REQUIRE_RESET = 0";

        try
        {
            //$result = $this->db->setQuery($query)->loadObject();
            $rs = $this->db->Execute($sql);
            $result = $rs->GetRowAssoc;            
        }
        catch (RuntimeException $e)
        {
            $response->status = QAuthentication::STATUS_FAILURE;

            return false;
        }

        if ($result)
        {
            // Bring this in line with the rest of the system
            $user = QUser::getInstance($result['EMPLOYEE_ID']);
            //$user = JFactory::getUser($result['EMPLOYEE_ID']);  // or use load() - i shuld use get instances for the data but use the response for authentication?
            
            // Set response data.
            $response->username = $result['EMPLOYEE_LOGIN'];
            $response->email    = $user->email;
            $response->fullname = $user->name;
            $response->password = $result['EMPLOYEE_PASSWORD'];
            //$response->language = $user->getParam('language');

            // Set response status.
            $response->status        = QAuthentication::STATUS_SUCCESS;
            $response->error_message = '';
        }
        else
        {
            $response->status        = QAuthentication::STATUS_FAILURE;
            $response->error_message = JText::_('JGLOBAL_AUTH_NO_USER');
        }
    }

    /**
     * We set the authentication cookie only after login is successfullly finished.
     * We set a new cookie either for a user with no cookies or one
     * where the user used a cookie to authenticate.
     *
     * @param   array  $options  Array holding options
     *
     * @return  boolean  True on success
     *
     * @since   3.2
     */
    public function onUserAfterLogin($options)
    {
        // No remember me for admin
        if (JFactory::isClient('administrator'))
        {
            return false;
        }

        if (isset($options['responseType']) && $options['responseType'] === 'Cookie')
        {
            // Logged in using a cookie
            $cookieName = 'qwcrm_remember_me_' . QUserHelper::getShortHashedUserAgent();

            // We need the old data to get the existing series
            $cookieValue = $this->cookie->get($cookieName);

            /* Try with old cookieName (pre 3.6.0) if not found
            if (!$cookieValue)
            {
                $oldCookieName = QUserHelper::getShortHashedUserAgent();
                $cookieValue   = $this->cookie->get($oldCookieName);

                // Destroy the old cookie in the browser
                $this->cookie->set($oldCookieName, false, time() - 42000, $this->app->get('cookie_path', '/'), $this->conf->get('cookie_domain'));
            }*/

            $cookieArray = explode('.', $cookieValue);

            // Filter series since we're going to use it in the query
            $filter = new JFilterInput;
            $series = $filter->clean($cookieArray[1], 'ALNUM');
        }
        elseif (!empty($options['remember']))
        {
            // Remember checkbox is set
            $cookieName = 'qwcrm_remember_me_' . QUserHelper::getShortHashedUserAgent();

            // Create a unique series which will be used over the lifespan of the cookie
            $unique     = false;
            $errorCount = 0;

            do
            {
                $series = QUserHelper::genRandomPassword(20);
                
                /*
                $query  = $this->db->getQuery(true)
                    ->select($this->db->quoteName('series'))
                    ->from($this->db->quoteName('#__user_keys'))
                    ->where($this->db->quoteName('series') . ' = ' . $this->db->quote($series));*/
                
                $sql = "SELECT series FROM ".PRFX."user_keys WHERE series = ".$this->db->qstr($series);

                try
                {
                    $rs = $this->db->Execute($sql);
                    $results = $rs->GetRowAssoc;
                            
                    //$results = $this->db->setQuery($query)->loadResult();

                    if (is_null($results))
                    {
                        $unique = true;
                    }
                }
                catch (RuntimeException $e)
                {
                    $errorCount++;

                    // We'll let this query fail up to 5 times before giving up, there's probably a bigger issue at this point
                    if ($errorCount == 5)
                    {
                        return false;
                    }
                }
            }

            while ($unique === false);
        }
        else
        {
            return false;
        }

        // Get the parameter values - this are settings from within the cookie plugin (now added to my config for now)
        //$lifetime = $this->params->get('cookie_lifetime', '60') * 24 * 60 * 60;
        //$length   = $this->params->get('key_length', '16');
        $lifetime = $this->config->get('cookie_lifetime', '60') * 24 * 60 * 60;
        $length   = $this->config->get('cookie_key_length', '16');

        // Generate new cookie
        $token       = QUserHelper::genRandomPassword($length);
        $cookieValue = $token . '.' . $series;

        // Overwrite existing cookie with new value
        //setcookie($cookieName, $cookieValue, time() + $lifetime, $this->config->get('cookie_path', '/'), $this->config->get('cookie_domain'), $this->isSSLConnection());
        $this->cookie->set($cookieName, $cookieValue, time() + $lifetime, $this->config->get('cookie_path', '/'), $this->config->get('cookie_domain'), $this->isSSLConnection());
        
        
        // not sure what this does - possibly clears query
        //$query = $this->db->getQuery(true);

        if (!empty($options['remember']))
        {
            // Create new record
            /*$query
                ->insert($this->db->quoteName('#__user_keys'))
                ->set($this->db->quoteName('user_id') . ' = ' . $this->db->quote($options['user']->username))
                ->set($this->db->quoteName('series') . ' = ' . $this->db->quote($series))
                ->set($this->db->quoteName('uastring') . ' = ' . $this->db->quote($cookieName))
                ->set($this->db->quoteName('time') . ' = ' . (time() + $lifetime));*/
            
            /*$sql = "INSERT INTO ".PRFX."user_keys SET
                    user_id     =". $this->db->qstr( $options['user']->username ).",
                    series      =". $this->db->qstr( $series                    ).",
                    uastring    =". $this->db->qstr( $cookieName                ).",
                    time        =". ( time() + $lifetime                        );*/
            
            $record['user_id']  = $this->db->qstr( $options['user']->username );
            $record['series']   = $this->db->qstr( $series);
            $record['uastring'] = $this->db->qstr( $cookieName );
            $record['time']     = time() + $lifetime;
                

        }
        else
        {
            /* Update existing record with new token
            $query
                ->update($this->db->quoteName('#__user_keys'))
                ->where($this->db->quoteName('user_id') . ' = ' . $this->db->quote($options['user']->username))
                ->where($this->db->quoteName('series') . ' = ' . $this->db->quote($series))
                ->where($this->db->quoteName('uastring') . ' = ' . $this->db->quote($cookieName));
            
            $sql = "UPDATE ".PRFX."user_keys SET
                    user_id     =". $this->db->qstr( $options['user']->username ).",
                    series      =". $this->db->qstr( $series                    ).",
                    uastring    =". $this->db->qstr( $cookieName                );*/
                   
            $where['user_id']  = $this->db->qstr( $options['user']->username );
            $where['series']   = $this->db->qstr( $series );
            $where['uastring'] = $this->db->qstr( $cookieName );
            
        }

        // Get hashed token
        $hashed_token = QUserHelper::hashPassword($token);        

        //query->set($this->db->quoteName('token') . ' = ' . $this->db->quote($hashed_token));
        $record['token'] = $this->db->qstr( $hashed_token );
        
        try
        {
            //$this->db->setQuery($query)->execute();
             if (!empty($options['remember']))
             {
                 $this->db->AutoExecute(PRFX.'user_keys', $record, 'INSERT');
             } 
             else
             {
                $this->db->AutoExecute(PRFX.'user_keys', $record, 'UPDATE', $where);
             }
        }
        catch (RuntimeException $e)
        {
            return false;
        }

        return true;
    }

    /**
     * Imports the authentication plugin on user logout to make sure that the cookie is destroyed.
     *
     * @param   array  $user     Holds the user data.
     * @param   array  $options  Array holding options (remember, autoregister, group).
     *
     * @return  boolean
     */
    public function onUserLogout($user, $options)
    {
        // No remember me for admin
        if (JFactory::isClient('administrator'))
        {
            return true;
        }

        $cookieName = 'qwcrm_remember_me_' . QUserHelper::getShortHashedUserAgent();

        // Check for the cookie
        $config = JFactory::getConfig();
        if ($config->get($cookieName))
        {
            // Make sure authentication group is loaded to process onUserAfterLogout event
            //JPluginHelper::importPlugin('authentication');
        }

        return true;
    }
    
    /**
     * This is where we delete any authentication cookie when a user logs out
     *
     * @param   array  $options  Array holding options (length, timeToExpiration)
     *
     * @return  boolean  True on success
     *
     * @since   3.2
     */
    //public function onUserAfterLogout($options)
    public function onUserAfterLogout()
    {
        // No remember me for admin
        if (JFactory::isClient('administrator'))
        {
            return false;
        }

        $cookieName  = 'qwcrm_remember_me_' . QUserHelper::getShortHashedUserAgent();
        $cookieValue = $this->cookie->get($cookieName);

        // There are no cookies to delete.
        if (!$cookieValue)
        {
            return true;
        }

        $cookieArray = explode('.', $cookieValue);

        // Filter series since we're going to use it in the query        
        $series = $this->filter->clean($cookieArray[1], 'ALNUM');

        // Remove the record from the database
        /*$query = $this->db->getQuery(true)
            ->delete('#__user_keys')
            ->where($this->db->quoteName('series') . ' = ' . $this->db->quote($series));*/
        
        $sql = "DELETE FROM ".PRFX."user_keys WHERE series = ".$this->db->qstr($series);

        try
        {
            //$this->db->setQuery($query)->execute();
            $this->db->Execute($sql);
        }
        catch (RuntimeException $e)
        {
            // We aren't concerned with errors from this query, carry on
        }

        // Destroy the cookie
        $this->cookie->set($cookieName, false, time() - 42000, $this->config->get('cookie_path', '/'), $this->config->get('cookie_domain'));

        return true;
    }
    
    // D:\websites\htdocs\quantumwarp.com\libraries\joomla\application\web.php
    /**
     * Determine if we are using a secure (SSL) connection.
     *
     * @return  boolean  True if using SSL, false if not.
     *
     * @since   12.2
     */
    public function isSSLConnection()
    {
        return (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on')) || getenv('SSL_PROTOCOL_VERSION');
    }      
        
}