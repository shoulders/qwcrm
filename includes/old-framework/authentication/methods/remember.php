<?php
// D:\websites\htdocs\quantumwarp.com\plugins\system\remember\remember.php
/**
 * @package     Joomla.Plugin
 * @subpackage  System.remember
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_QWEXEC') or die;

/**
 * Joomla! System Remember Me Plugin
 *
 * @since  1.5
 */

class PlgSystemRemember //extends JPlugin
{
    /**
     * Application object.
     *
     * @var    JApplicationCms
     * @since  3.2
     */
    protected $app;

    /**
     * Remember me method to run onAfterInitialise
     * Only purpose is to initialise the login authentication process if a cookie is present
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
            $this->app = QFactory::getApplication();
        }*/

        // No remember me for admin.
        if (QFactory::isClient('administrator'))
        {
            return;
        }
        
        // Check for a cookie if user is not logged in - (guests are not log in)        
        if (QFactory::getUser()->get('guest'))
        {
            $cookieName = 'qwcrm_remember_me_' . QUserHelper::getShortHashedUserAgent();

            /* Try with old cookieName (pre 3.6.0) if not found
            if (!$this->app->input->cookie->get($cookieName))
            {
                $cookieName = QUserHelper::getShortHashedUserAgent();
            }*/

            $cookieData = new QCookie;
            echo $cookieData->get($cookieName);die('remember plugin');
            
            // Check for the cookie
            //if ($this->app->input->cookie->get($cookieName))
            if ($cookieData->get($cookieName))
            {
                $this->app->login(array('username' => ''), array('silent' => true));
            }
        }
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
        if (QFactory::isClient('administrator'))
        {
            return true;
        }

        $cookieName = 'qwcrm_remember_me_' . QUserHelper::getShortHashedUserAgent();

        // Check for the cookie
        $conf = QFactory::getConfig();
        if ($conf->get($cookieName))
        {
            // Make sure authentication group is loaded to process onUserAfterLogout event
            //JPluginHelper::importPlugin('authentication');
        }

        return true;
    }
  
}
