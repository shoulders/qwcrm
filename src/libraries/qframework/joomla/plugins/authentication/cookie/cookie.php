<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Authentication.cookie
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Joomla Authentication plugin
 *
 * @since  3.2
 * @note   Code based on http://jaspan.com/improved_persistent_login_cookie_best_practice
 *         and http://fishbowl.pastiche.org/2004/01/19/persistent_login_cookie_best_practice/
 */
class PlgAuthenticationCookie //extends JPlugin
{
    /**
     * Application object
     *
     * @var    JApplicationCms
     * @since  3.2
     */
    protected $app;

    /**
     * Database object
     *
     * @var    JDatabaseDriver
     * @since  3.2
     */
    protected $db;
    private $cookie;
    private $config;
    public $response;
    
    public function __construct()
    {
        $this->db           = \QFactory::getDbo();
        $this->config       = \QFactory::getConfig();
        $this->cookie       = new \Joomla\CMS\Input\Cookie;
        $this->response     = new Joomla\CMS\Authentication\AuthenticationResponse;
        $this->filter       = new Joomla\CMS\Filter\InputFilter;
    }
        
    /**
     * Reports the privacy related capabilities for this plugin to site administrators.
     *
     * @return  array
     *
     * @since   3.9.0
     */
    public function onPrivacyCollectAdminCapabilities()
    {
        $this->loadLanguage();

        return array(
            JText::_('PLG_AUTHENTICATION_COOKIE') => array(
                JText::_('PLG_AUTH_COOKIE_PRIVACY_CAPABILITY_COOKIE'),
            )
        );
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
        if (QFactory::isClient('administrator'))
        {
            return false;
        }

        // Get cookie
        $cookieName  = 'qwcrm_remember_me_' . \Joomla\CMS\User\UserHelper::getShortHashedUserAgent();
        $cookieValue = $this->cookie->get($cookieName);        

        /*
        // Try with old cookieName (pre 3.6.0) if not found        
        if (!$cookieValue)
        {
            $cookieName  = \Joomla\CMS\User\UserHelper::getShortHashedUserAgent();
            $cookieValue = $this->app->input->cookie->get($cookieName);
        }
        */

        if (!$cookieValue)
        {
            return false;
        }

        // The cookie content is seperaters into 2 values by a period '.'
        $cookieArray = explode('.', $cookieValue);

        // Check for valid cookie value (must be 2 values seperated by a period '.' in cookie content/value)
        if (count($cookieArray) != 2)
        {
            // Destroy the cookie in the browser.
            $this->cookie->set($cookieName, false, time() - 42000, $this->config->get('cookie_path', '/'), $this->config->get('cookie_domain'));            
            //JLog::add('Invalid cookie detected.', JLog::WARNING, 'error');

            return false;
        }

        $response->type = 'Cookie';

        // Filter series since we're going to use it in the query
        $filter = new \Joomla\CMS\Filter\InputFilter;
        $series = $filter->clean($cookieArray[1], 'ALNUM');

        // Remove expired tokens
        $sql = "DELETE FROM ".PRFX."user_keys WHERE time < ".time();

        try
        {
            //$this->db->setQuery($query)->execute();
            $this->db->Execute($sql);
        }
        catch (RuntimeException $e)
        {
            // We aren't concerned with errors from this query, carry on
        }

        // Find the matching record if it exists.
        $sql = "SELECT user_id, token, series, time FROM ".PRFX."user_keys WHERE series = ".$this->db->qstr($series)." AND uastring = ".$this->db->qstr($cookieName)." ORDER BY time DESC";

        try
        {
            //$results = $this->db->setQuery($query)->loadObjectList();
            $rs = $this->db->Execute($sql);
            $results = $rs->GetArray();            
        }
        catch (RuntimeException $e)
        {
            $response->status = \Joomla\CMS\Authentication\Authentication::STATUS_FAILURE;

            return false;
        }

        // If there is not exactly 1 record found
        if (count($results) !== 1)
        {
            // Destroy the cookie in the browser.
            $this->cookie->set($cookieName, '', 1, $this->config->get('cookie_path', '/'), $this->config->get('cookie_domain'));
            $response->status = \Joomla\CMS\Authentication\Authentication::STATUS_FAILURE;

            return false;
        }

        // We have a user with one cookie with a valid series and a corresponding record in the database.
        if (!\Joomla\CMS\User\UserHelper::verifyPassword($cookieArray[0], $results[0]['token']))
        {
            /*
             * This is a real attack!
             * Either the series was guessed correctly or a cookie was stolen and used twice (once by attacker and once by victim).
             * Delete all tokens for this user!
             */
            $sql = "DELETE FROM ".PRFX."user_keys WHERE user_id = ".$this->db->qstr($results[0]['user_id']);

            try
            {
                $this->db->Execute($sql);
            }
            catch (RuntimeException $e)
            {
                // Log an alert for the site admin
                JLog::add(
                    sprintf('Failed to delete cookie token for user %s with the following error: %s', $results[0]->user_id, $e->getMessage()),
                    JLog::WARNING,
                    'security'
                );
            }

            // Destroy the cookie in the browser.
            $this->cookie->set($cookieName, '', 1, $this->config->get('cookie_path', '/'), $this->config->get('cookie_domain'));

            // Issue warning by email to user and/or admin?
            //JLog::add(JText::sprintf(_gettext("Cookie login failed for user "), $results[0]['user_id']), JLog::WARNING, 'security');
            $response->status = \Joomla\CMS\Authentication\Authentication::STATUS_FAILURE;

            return false;
        }

        // Make sure there really is a user with this name and get the data for the session.
        $sql = "SELECT user_id, username, password FROM ".PRFX."user_records WHERE username = ".$this->db->qstr($results[0]['user_id'])." AND require_reset = 0";

        try
        {
            $rs = $this->db->Execute($sql);
            $result = $rs->GetRowAssoc();  
        }
        catch (RuntimeException $e)
        {
            $response->status = \Joomla\CMS\Authentication\Authentication::STATUS_FAILURE;

            return false;
        }

        if ($result)
        {
            // Bring this in line with the rest of the system
            $user = \Joomla\CMS\User\User::getInstance($result['user_id']);            

            // Set response data.
            $response->username = $result['username'];
            $response->email    = $user->email;
            $response->fullname = $user->name;
            $response->password = $result['password'];
            //$response->language = $user->getParam('language');

            // Set response status.
            $response->status        = \Joomla\CMS\Authentication\Authentication::STATUS_SUCCESS;
            $response->error_message = '';
        }
        else
        {
            $response->status        = \Joomla\CMS\Authentication\Authentication::STATUS_FAILURE;
            $response->error_message = _gettext("Username and password do not match or you do not have an account yet.");
        }
    }

    /**
     * We set the authentication (remember me) cookie only after login is successfully finished.
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
        if (QFactory::isClient('administrator'))
        {
            return false;
        }

        if (isset($options['responseType']) && $options['responseType'] === 'Cookie')
        {
            // Logged in using a cookie
            $cookieName = 'qwcrm_remember_me_' . \Joomla\CMS\User\UserHelper::getShortHashedUserAgent();

            // We need the old data to get the existing series
            $cookieValue = $this->cookie->get($cookieName);

            /*
            // Try with old cookieName (pre 3.6.0) if not found
            if (!$cookieValue)
            {
                $oldCookieName = \Joomla\CMS\User\UserHelper::getShortHashedUserAgent();
                $cookieValue   = $this->app->input->cookie->get($oldCookieName);

                // Destroy the old cookie in the browser
                $this->app->input->cookie->set($oldCookieName, '', 1, $this->app->get('cookie_path', '/'), $this->app->get('cookie_domain', ''));
            }
            */

            $cookieArray = explode('.', $cookieValue);

            // Filter series since we're going to use it in the query
            $filter = new \Joomla\CMS\Filter\InputFilter;
            $series = $filter->clean($cookieArray[1], 'ALNUM');
        }
        // If login is submitted with the 'Remember me' checkbox set
        elseif (!empty($options['remember']))
        {
            // Remember checkbox is set
            $cookieName = 'qwcrm_remember_me_' . \Joomla\CMS\User\UserHelper::getShortHashedUserAgent();

            // Create a unique series which will be used over the lifespan of the cookie
            $unique     = false;
            $errorCount = 0;

            do
            {
                $series = \Joomla\CMS\User\UserHelper::genRandomPassword(20);
                $sql = "SELECT series FROM ".PRFX."user_keys WHERE series = ".$this->db->qstr($series);

                try
                {
                    $rs = $this->db->Execute($sql);
                    $results = $rs->RecordCount();             

                    if ($results === 0)
                    {
                        $unique = true;
                    }
                }
                catch (RuntimeException $e)
                {
                    $errorCount++;

                    // We'll let this query fail up to 5 times before giving up, there's probably a bigger issue at this point
                    if ($errorCount === 5)
                    {
                        return false;
                    }
                }
            }

            while ($unique === false);
        }
        else
        {
            // If not authenticated by 'Remember me' cookie and 'Remember me' has not been checked
            return false;
        }
        
        // Create/Overwrite the cookie        

        // Get the parameter values - this are settings from within the cookie plugin (now added to main config)        
        $lifetime = $this->config->get('cookie_lifetime', '60') * 24 * 60 * 60;
        $length   = $this->config->get('cookie_token_length', '16');

        // Generate new cookie (content)
        $token       = \Joomla\CMS\User\UserHelper::genRandomPassword($length);
        $cookieValue = $token . '.' . $series;

        // Create/Overwrite 'remember me' cookie with new content
        $this->cookie->set(
            $cookieName,
            $cookieValue,
            time() + $lifetime,
            $this->config->get('cookie_path', '/'),
            $this->config->get('cookie_domain'),
            \QFactory::isSSLConnection()
        );

        // If the 'Remember me' box is ticked

        if (!empty($options['remember']))
        {
            // Create new record
            $record['user_id']  = $options['user']->username;
            $record['series']   = $series;
            $record['uastring'] = $cookieName;
            $record['time']     = time() + $lifetime;
        }
        // Silent login
        else
        {
            // Update existing record with new token
            $where = "user_id = ".$this->db->qstr($options['user']->username)." 
            AND series = ".$this->db->qstr($series)." 
            AND uastring = ".$this->db->qstr($cookieName);
            
        }

        // Get hashed token
        $hashed_token = \Joomla\CMS\User\UserHelper::hashPassword($token);        

        //$query->set($this->db->quoteName('token') . ' = ' . $this->db->quote($hashedToken));
        
        // Set token for both insert or update routines below
        $record['token'] = $hashed_token;

        // Add new record or update exist record in #__user_keys     
        try
        {
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
        if (QFactory::isClient('administrator'))
        {
            return false;
        }

        $cookieName  = 'qwcrm_remember_me_' . \Joomla\CMS\User\UserHelper::getShortHashedUserAgent();
        $cookieValue = $this->cookie->get($cookieName);

        // There are no cookies to delete.
        if (!$cookieValue)
        {
            return true;
        }

        $cookieArray = explode('.', $cookieValue);

        // Filter series since we're going to use it in the query
        //$filter = new JFilterInput;
        //$series = $filter->clean($cookieArray[1], 'ALNUM');
        $series = $this->filter->clean($cookieArray[1], 'ALNUM');

        // Remove the record from the database
        $sql = "DELETE FROM ".PRFX."user_keys WHERE series = ".$this->db->qstr($series);

        try
        {
            $this->db->Execute($sql);
        }
        catch (RuntimeException $e)
        {
            // We aren't concerned with errors from this query, carry on
        }

        // Destroy the cookie
        $this->cookie->set($cookieName, '', 1, $this->config->get('cookie_path', '/'), $this->config->get('cookie_domain'));

        return true;
    }
    
}
