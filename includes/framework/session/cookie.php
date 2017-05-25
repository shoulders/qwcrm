<?php

// this overrrides key function in QSession and then runs the corresponding parent function so the cookie is set and so is the session in tandom


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
class QCookie extends QSession
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
        //ini_set('session.use_trans_sid', '0');

        // Only allow the session ID to come from cookies and nothing else.
        //ini_set('session.use_only_cookies', '1');

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
        $session_name = $this->getName();

        // Get the JInputCookie object
        //$this->data = & $_COOKIE
        //$cookie = $this->input->cookie;
        //$cookie = $_COOKIE;

        //if (is_null($cookie->get($session_name)))
        if (is_null($_COOKIE[$session_name]))
        {
            $session_clean = $this->get($session_name, false, 'string');

            if ($session_clean)
            {
                $this->setId($session_clean);
                $this->setCookie($session_name, '', time() - 3600);
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
                $config        = QFactory::getConfig();
                $cookie_domain = $config->get('cookie_domain', '');
                $cookie_path   = $config->get('cookie_path', '/');
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

            $config = QFactory::getConfig();

            if ($config->get('cookie_domain', '') != '')
            {
                $cookie['domain'] = $config->get('cookie_domain');
            }

            if ($config->get('cookie_path', '') != '')
            {
                $cookie['path'] = $config->get('cookie_path');
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
    


        // not from this file
    /**
     * Sets a value
     *
     * @param   string   $name      Name of the value to set.
     * @param   mixed    $value     Value to assign to the input.
     * @param   integer  $expire    The time the cookie expires. This is a Unix timestamp so is in number
     *                              of seconds since the epoch. In other words, you'll most likely set this
     *                              with the time() function plus the number of seconds before you want it
     *                              to expire. Or you might use mktime(). time()+60*60*24*30 will set the
     *                              cookie to expire in 30 days. If set to 0, or omitted, the cookie will
     *                              expire at the end of the session (when the browser closes).
     * @param   string   $path      The path on the server in which the cookie will be available on. If set
     *                              to '/', the cookie will be available within the entire domain. If set to
     *                              '/foo/', the cookie will only be available within the /foo/ directory and
     *                              all sub-directories such as /foo/bar/ of domain. The default value is the
     *                              current directory that the cookie is being set in.
     * @param   string   $domain    The domain that the cookie is available to. To make the cookie available
     *                              on all subdomains of example.com (including example.com itself) then you'd
     *                              set it to '.example.com'. Although some browsers will accept cookies without
     *                              the initial ., RFC 2109 requires it to be included. Setting the domain to
     *                              'www.example.com' or '.www.example.com' will make the cookie only available
     *                              in the www subdomain.
     * @param   boolean  $secure    Indicates that the cookie should only be transmitted over a secure HTTPS
     *                              connection from the client. When set to TRUE, the cookie will only be set
     *                              if a secure connection exists. On the server-side, it's on the programmer
     *                              to send this kind of cookie only on secure connection (e.g. with respect
     *                              to $_SERVER["HTTPS"]).
     * @param   boolean  $httpOnly  When TRUE the cookie will be made accessible only through the HTTP protocol.
     *                              This means that the cookie won't be accessible by scripting languages, such
     *                              as JavaScript. This setting can effectively help to reduce identity theft
     *                              through XSS attacks (although it is not supported by all browsers).
     *
     * @return  void
     *
     * @link    http://www.ietf.org/rfc/rfc2109.txt
     * @see     setcookie()
     * @since   11.1
     * was just set
     */
    public function setCookie($name, $value, $expire = 0, $path = '', $domain = '', $secure = false, $httpOnly = false)
    {
        if (is_array($value))
        {
            foreach ($value as $key => $val)
            {
                setcookie($name . "[$key]", $val, $expire, $path, $domain, $secure, $httpOnly);
            }
        }
        else
        {
            setcookie($name, $value, $expire, $path, $domain, $secure, $httpOnly);
        }

        $this->data[$name] = $value;
    }

    // D:\websites\htdocs\quantumwarp.com\libraries\vendor\joomla\input\src\Input.php  
    /**
     * Gets a value from the input data.
     *
     * @param   string  $name     Name of the value to get.
     * @param   mixed   $default  Default value to return if variable does not exist.
     * @param   string  $filter   Filter to apply to the value.
     *
     * @return  mixed  The filtered input value.
     *
     * @see     \Joomla\Filter\InputFilter::clean()
     * @since   1.0
     *
    public function get($name, $default = null, $filter = 'cmd')
    {
        if (isset($this->data[$name]))
        {
            return $this->filter->clean($this->data[$name], $filter);
        }

        return $default;
    }*/

}


/************************************************************************/
    
    // D:\websites\htdocs\quantumwarp.com\libraries\joomla\input\cookie.php - possibly
    
    /**
     * @package     Joomla.Platform
     * @subpackage  Input
     *
     * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
     * @license     GNU General Public License version 2 or later; see LICENSE
     */
    /**
        * Joomla! Input Cookie Class
        *
        * @since  11.1
        */

    /**
     * Constructor.
     *
     * @param   array  $source   Ignored.
     * @param   array  $options  Array of configuration parameters (Optional)
     *
     * @since   11.1
     *
    public function __construct(array $source = null, array $options = array())
    {      
       
       /* if (isset($options['filter']))
        {
            $this->filter = $options['filter'];
        }
        else
        {
            $this->filter = QFilterInput::getInstance();
        }
            /* sort these - You cannot change the session module's ini settings at this time
                // D:\websites\htdocs\quantumwarp.com\libraries\joomla\session\handler\joomla.php
                // Disable transparent sid support
                ini_set('session.use_trans_sid', '0');

                // Only allow the session ID to come from cookies and nothing else.
                ini_set('session.use_only_cookies', '1');
            */
            /*    // Set options
                $this->setOptions($options);
                $this->setCookieParams();
        
        // Set the data source.
        $this->data = & $_COOKIE;

        // Set the options for the class.
        $this->options = $options;
    }*/


    // D:\websites\htdocs\quantumwarp.com\libraries\fof\input\jinput\cookie.php
    /**
     * Sets a value
     *
     * @param   string   $name      Name of the value to set.
     * @param   mixed    $value     Value to assign to the input.
     * @param   integer  $expire    The time the cookie expires. This is a Unix timestamp so is in number
     *                              of seconds since the epoch. In other words, you'll most likely set this
     *                              with the time() function plus the number of seconds before you want it
     *                              to expire. Or you might use mktime(). time()+60*60*24*30 will set the
     *                              cookie to expire in 30 days. If set to 0, or omitted, the cookie will
     *                              expire at the end of the session (when the browser closes).
     * @param   string   $path      The path on the server in which the cookie will be available on. If set
     *                              to '/', the cookie will be available within the entire domain. If set to
     *                              '/foo/', the cookie will only be available within the /foo/ directory and
     *                              all sub-directories such as /foo/bar/ of domain. The default value is the
     *                              current directory that the cookie is being set in.
     * @param   string   $domain    The domain that the cookie is available to. To make the cookie available
     *                              on all subdomains of example.com (including example.com itself) then you'd
     *                              set it to '.example.com'. Although some browsers will accept cookies without
     *                              the initial ., RFC 2109 requires it to be included. Setting the domain to
     *                              'www.example.com' or '.www.example.com' will make the cookie only available
     *                              in the www subdomain.
     * @param   boolean  $secure    Indicates that the cookie should only be transmitted over a secure HTTPS
     *                              connection from the client. When set to TRUE, the cookie will only be set
     *                              if a secure connection exists. On the server-side, it's on the programmer
     *                              to send this kind of cookie only on secure connection (e.g. with respect
     *                              to $_SERVER["HTTPS"]).
     * @param   boolean  $httpOnly  When TRUE the cookie will be made accessible only through the HTTP protocol.
     *                              This means that the cookie won't be accessible by scripting languages, such
     *                              as JavaScript. This setting can effectively help to reduce identity theft
     *                              through XSS attacks (although it is not supported by all browsers).
     *
     * @return  void
     *
     * @link    http://www.ietf.org/rfc/rfc2109.txt
     * @see     setcookie()
     * @since   11.1
     *
    public function set($name, $value, $expire = 0, $path = '', $domain = '', $secure = false, $httpOnly = false)
    {
        setcookie($name, $value, $expire, $path, $domain, $secure, $httpOnly);

        $this->data[$name] = $value;
    }*/