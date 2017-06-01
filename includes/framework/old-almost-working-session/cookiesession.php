<?php

// this overrrides key function in JSession and then runs the corresponding parent function so the cookie is set and so is the session in tandom


// D:\websites\htdocs\quantumwarp.com\libraries\joomla\session\handler\joomla.php
/*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

/**
 * @package     Joomla.Platform
 * @subpackage  Session
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

/**
 * Interface for managing HTTP sessions
 *
 * @since       3.5
 * @deprecated  4.0  The CMS' Session classes will be replaced with the `joomla/session` package
 */
class QCookieSession extends JSession
{
    /**
     * The input object
     *
     * @var    JInput
     * @since  3.5
     */
    public $input = null;

    /**
     * Force cookies to be SSL only
     *
     * @var    boolean
     * @since  3.5
     */
    protected $force_ssl = false;

    /**
     * Public constructor
     *
     * @param   array  $options  An array of configuration options
     *
     * @since   3.5
     */
    public function __construct($options = array())
    {
        // ini_set(): A session is active. You cannot change the session module's ini settings at this time i
        
        // Disable transparent sid support
        ini_set('session.use_trans_sid', '0');

        // Only allow the session ID to come from cookies and nothing else.
        ini_set('session.use_only_cookies', '1');

        // Set options
        $this->setOptions($options);
        $this->setCookieParams();
    }
    
    /**
     * Starts the session
     *
     * @return  boolean  True if started
     *
     * @since   3.5
     * @throws  RuntimeException If something goes wrong starting the session.
     */
    public function start()
    {
        // Get the session name from the parent class
        $session_name = $this->getName();

        // Get the JInputCookie object
        //$this->data = & $_COOKIE
        //$cookie = $this->input->cookie;
        //$cookie = $_COOKIE;
        
        $cookie = new Cookie;

        //if (is_null($cookie->get($session_name)))
        if (is_null($_COOKIE[$session_name]))
        {
            $session_clean = $this->get($session_name, false, 'string');

            if ($session_clean)
            {
                $this->setId($session_clean);
                //$this->setCookie($session_name, '', time() - 3600);
                $cookie->set($session_name, '', time() - 3600);
            }
        }

        return parent::start();
    }

        /**
         * Clear all session data in memory.
         *
         * @return  void
         *
         * @since   3.5
         */
        public function clear()
        {
            $session_name = $this->getName();

            /*
             * In order to kill the session altogether, such as to log the user out, the session id
             * must also be unset. If a cookie is used to propagate the session id (default behavior),
             * then the session cookie must be deleted.
             */
            if (isset($_COOKIE[$session_name]))
            {
                
                $cookie_domain = $this->config->get('cookie_domain', '');
                $cookie_path   = $this->config->get('cookie_path', '/');
                setcookie($session_name, '', time() - 42000, $cookie_path, $cookie_domain);
            }

            parent::clear();
        }

        // D:\websites\htdocs\quantumwarp.com\libraries\joomla\session\handler\joomla.php
        /**
         * Set session cookie parameters
         *
         * @return  void
         *
         * @since   3.5
         */
        protected function setCookieParams()
        {
            $cookie = session_get_cookie_params();

            if ($this->force_ssl)
            {
                $cookie['secure'] = true;
            }

            //$config = JFactory::getConfig();

            if ($this->config->get('cookie_domain', '') != '')
            {
                $cookie['domain'] = $this->config->get('cookie_domain');
            }

            if ($this->config->get('cookie_path', '') != '')
            {
                $cookie['path'] = $this->config->get('cookie_path');
            }

            session_set_cookie_params($cookie['lifetime'], $cookie['path'], $cookie['domain'], $cookie['secure'], true);
        }

        /**
         * Set additional session options
         *
         * @param   array  $options  List of parameter
         *
         * @return  boolean  True on success
         *
         * @since   3.5
         */
        protected function setOptions(array $options)
        {
            if (isset($options['force_ssl']))
            {
                $this->force_ssl = (bool) $options['force_ssl'];
            }

            return true;
        }
}