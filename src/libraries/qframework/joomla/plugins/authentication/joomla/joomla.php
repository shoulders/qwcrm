<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Authentication.joomla
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Joomla Authentication plugin
 *
 * @since  1.5
 */
class PlgAuthenticationJoomla //extends JPlugin
{
    private $db;
    public function __construct()
    {
        $this->db = \QFactory::getDbo();
    }
    /**
     * This method should handle any authentication and report back to the subject
     *
     * @param   array   $credentials  Array holding the user credentials
     * @param   array   $options      Array of extra options
     * @param   object  &$response    Authentication response object
     *
     * @return  void
     *
     * @since   1.5
     */
    public function onUserAuthenticate($credentials, $options, &$response)
    {
        $response->type = 'Joomla';
        $result = null; // I added this, is it needed?

        // Joomla does not like blank passwords
        if (empty($credentials['password']))
        {
            $response->status        = \Joomla\CMS\Authentication\Authentication::STATUS_FAILURE;
            $response->error_message = _gettext("Empty password not allowed.");

            return;
        }

        // Get a database object - Load the relevant user record form the database
        $sql = "SELECT user_id, password FROM ".PRFX."user_records WHERE username = ".$this->db->qstr($credentials['username']);        

        $rs = $this->db->Execute($sql);
        //$result = $rs->GetRowAssoc();
        
        // if there is a record, set it
        if($rs->RecordCount() == 1) {
            $result = $rs->GetRowAssoc();            
        }
        
        // if there is a match, verify it
        if ($result)
        {
            $match = \Joomla\CMS\User\UserHelper::verifyPassword($credentials['password'], $result['password'], $result['user_id']);

            if ($match === true)
            {
                // Bring this in line with the rest of the system
                $user               = \Joomla\CMS\User\User::getInstance($result['user_id']);
                $response->email    = $user->email;
                $response->fullname = $user->name;

                /*if (JFactory::getApplication()->isClient('administrator'))
                {
                    $response->language = $user->getParam('admin_language');
                }
                else
                {
                    $response->language = $user->getParam('language');
                }*/

                $response->status        = \Joomla\CMS\Authentication\Authentication::STATUS_SUCCESS;
                $response->error_message = '';
            }
            else
            {
                // Invalid password
                $response->status        = \Joomla\CMS\Authentication\Authentication::STATUS_FAILURE;
                $response->error_message = _gettext("Username and password do not match or you do not have an account yet.");
            }
        }
        else
        {
            // Let's hash the entered password even if we don't have a matching user for some extra response time
            // By doing so, we mitigate side channel user enumeration attacks
            \Joomla\CMS\User\UserHelper::hashPassword($credentials['password']);

            // Invalid user
            $response->status        = \Joomla\CMS\Authentication\Authentication::STATUS_FAILURE;
            $response->error_message = _gettext("Username and password do not match or you do not have an account yet.");
        }

        // Check the two factor authentication
        if ($response->status === \Joomla\CMS\Authentication\Authentication::STATUS_SUCCESS)
        {
            // I am not using 2 Factor authentication in QWcrm so just return
            return;  
            
            $methods = \Joomla\CMS\Authentication\AuthenticationHelper::getTwoFactorMethods();            

            if (count($methods) <= 1)
            {
                // No two factor authentication method is enabled
                return;
            }

            JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_users/models', 'UsersModel');

            /** @var UsersModelUser $model */
            $model = JModelLegacy::getInstance('User', 'UsersModel', array('ignore_request' => true));

            // Load the user's OTP (one time password, a.k.a. two factor auth) configuration
            if (!array_key_exists('otp_config', $options))
            {
                $otpConfig             = $model->getOtpConfig($result->id);
                $options['otp_config'] = $otpConfig;
            }
            else
            {
                $otpConfig = $options['otp_config'];
            }

            // Check if the user has enabled two factor authentication
            if (empty($otpConfig->method) || ($otpConfig->method === 'none'))
            {
                // Warn the user if they are using a secret code but they have not
                // enabled two factor auth in their account.
                if (!empty($credentials['secretkey']))
                {
                    try
                    {
                        $app = JFactory::getApplication();

                        $this->loadLanguage();

                        $app->enqueueMessage(JText::_('PLG_AUTH_JOOMLA_ERR_SECRET_CODE_WITHOUT_TFA'), 'warning');
                    }
                    catch (Exception $exc)
                    {
                        // This happens when we are in CLI mode. In this case
                        // no warning is issued
                        return;
                    }
                }

                return;
            }

            // Try to validate the OTP
            FOFPlatform::getInstance()->importPlugin('twofactorauth');

            $otpAuthReplies = FOFPlatform::getInstance()->runPlugins('onUserTwofactorAuthenticate', array($credentials, $options));

            $check = false;

            /*
             * This looks like noob code but DO NOT TOUCH IT and do not convert
             * to in_array(). During testing in_array() inexplicably returned
             * null when the OTEP begins with a zero! o_O
             */
            if (!empty($otpAuthReplies))
            {
                foreach ($otpAuthReplies as $authReply)
                {
                    $check = $check || $authReply;
                }
            }

            // Fall back to one time emergency passwords
            if (!$check)
            {
                // Did the user use an OTEP instead?
                if (empty($otpConfig->otep))
                {
                    if (empty($otpConfig->method) || ($otpConfig->method === 'none'))
                    {
                        // Two factor authentication is not enabled on this account.
                        // Any string is assumed to be a valid OTEP.

                        return;
                    }
                    else
                    {
                        /*
                         * Two factor authentication enabled and no OTEPs defined. The
                         * user has used them all up. Therefore anything they enter is
                         * an invalid OTEP.
                         */
                        $response->status        = \Joomla\CMS\Authentication\Authentication::STATUS_FAILURE;
                        $response->error_message = JText::_('JGLOBAL_AUTH_INVALID_SECRETKEY');

                        return;
                    }
                }

                // Clean up the OTEP (remove dashes, spaces and other funny stuff
                // our beloved users may have unwittingly stuffed in it)
                $otep  = $credentials['secretkey'];
                $otep  = filter_var($otep, FILTER_SANITIZE_NUMBER_INT);
                $otep  = str_replace('-', '', $otep);
                $check = false;

                // Did we find a valid OTEP?
                if (in_array($otep, $otpConfig->otep))
                {
                    // Remove the OTEP from the array
                    $otpConfig->otep = array_diff($otpConfig->otep, array($otep));

                    $model->setOtpConfig($result->id, $otpConfig);

                    // Return true; the OTEP was a valid one
                    $check = true;
                }
            }

            if (!$check)
            {
                $response->status        = \Joomla\CMS\Authentication\Authentication::STATUS_FAILURE;
                $response->error_message = JText::_('JGLOBAL_AUTH_INVALID_SECRETKEY');
            }
        }
    }
        
    /**************************** Extra Functions Added ****************************/
    
    /**
     * From Joomla 3.7.0 joomla\plugins\user\joomla\joomla.php
     * @package     Joomla.Plugin
     * @subpackage  User.joomla
     *
     * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
     * @license     GNU General Public License version 2 or later; see LICENSE.txt
     */

    /**
     * From Joomla 3.7.0 joomla\plugins\user\joomla\joomla.php
     * Remove all sessions for the user name
     *
     * Method is called after user data is deleted from the database
     *
     * @param   array    $user     Holds the user data
     * @param   boolean  $success  True if user was successfully stored in the database
     * @param   string   $msg      Message
     *
     * @return  boolean
     *
     * @since   1.6
     */
    public function onUserAfterDelete($user, $success, $msg)
    {
        if (!$success)
        {
            return false;
        }

        $sql = "DELETE FROM ".PRFX."session WHERE userid = ". $this->db->qstr((int) $user['id']);

        try
        {            
            $this->db->Execute($sql);
        }
        catch (JDatabaseExceptionExecuting $e)
        {
            return false;
        }

        return true;
    }

    /** Not currently used in QWcrm
     * From Joomla 3.7.0 joomla\plugins\user\joomla\joomla.php
     * Utility method to act on a user after it has been saved.
     *
     * This method sends a registration email to new users created in the backend.
     *
     * @param   array    $user     Holds the new user data.
     * @param   boolean  $isnew    True if a new user is stored.
     * @param   boolean  $success  True if user was successfully stored in the database.
     * @param   string   $msg      Message.
     *
     * @return  void
     *
     * @since   1.6
     */
    public function onUserAfterSave($user, $isnew, $success, $msg)
    {
        $mail_to_user = $this->params->get('mail_to_user', 1);

        if ($isnew)
        {
            // TODO: Suck in the frontend registration emails here as well. Job for a rainy day.
            // The method check here ensures that if running as a CLI Application we don't get any errors
            if (method_exists($this->app, 'isClient') && $this->app->isClient('administrator'))
            {
                if ($mail_to_user)
                {
                    $lang = \QFactory::getLanguage();
                    $defaultLocale = $lang->getTag();

                    /**
                     * Look for user language. Priority:
                     *     1. User frontend language
                     *     2. User backend language
                     */
                    $userParams = new Registry($user['params']);
                    $userLocale = $userParams->get('language', $userParams->get('admin_language', $defaultLocale));

                    if ($userLocale != $defaultLocale)
                    {
                        $lang->setLanguage($userLocale);
                    }

                    $lang->load('plg_user_joomla', JPATH_ADMINISTRATOR);

                    // Compute the mail subject.
                    $emailSubject = JText::sprintf(
                        'PLG_USER_JOOMLA_NEW_USER_EMAIL_SUBJECT',
                        $user['name'],
                        $config = $this->app->get('sitename')
                    );

                    // Compute the mail body.
                    $emailBody = JText::sprintf(
                        'PLG_USER_JOOMLA_NEW_USER_EMAIL_BODY',
                        $user['name'],
                        $this->app->get('sitename'),
                        JUri::root(),
                        $user['username'],
                        $user['password_clear']
                    );

                    // Assemble the email data...the sexy way!
                    $mail = \QFactory::getMailer()
                        ->setSender(
                            array(
                                $this->app->get('mailfrom'),
                                $this->app->get('fromname')
                            )
                        )
                        ->addRecipient($user['email'])
                        ->setSubject($emailSubject)
                        ->setBody($emailBody);

                    // Set application language back to default if we changed it
                    if ($userLocale != $defaultLocale)
                    {
                        $lang->setLanguage($defaultLocale);
                    }

                    if (!$mail->Send())
                    {
                        $this->app->enqueueMessage(JText::_('JERROR_SENDING_EMAIL'), 'warning');
                    }
                }
            }
        }
        else
        {
            // Existing user - nothing to do...yet.
        }
    }

    /**
     * From Joomla 3.7.0 joomla\plugins\user\joomla\joomla.php
     * This method should handle any login logic and report back to the subject
     *
     * @param   array  $user     Holds the user data
     * @param   array  $options  Array holding options (remember, autoregister, group)
     *
     * @return  boolean  True on success
     *
     * @since   1.5
     */
    public function onUserLogin($user, $options = array())
    {        
        $instance = $this->_getUser($user, $options);

        // If _getUser returned an error, then pass it back.
        if ($instance instanceof Exception)
        {
            return false;
        }

        // If the user is blocked, redirect with an error
        if ($instance->block == 1)
        {
            //$this->app->enqueueMessage(_gettext("Login denied! Your account has either been blocked or you have not activated it yet."), 'warning');
            return false;            
        }

        /*
        // Authorise the user based on the group information - see authentication.php:351
        if (!isset($options['group']))
        {
            $options['group'] = 'USERS';
        }
        
        // Check the user can login.
        $result = $instance->authorise($options['action']);

        if (!$result)
        {
            $this->app->enqueueMessage(_gettext("Login denied! Your account has either been blocked or you have not activated it yet."), 'warning');            
            return false;
        }
         */
           
        // Mark the user as logged in
        $instance->guest = 0;

        $session = \QFactory::getSession();

        // Grab the current session ID
        $oldSessionId = $session->getId();

        // Fork the session - regenerates it
        $session->fork();

        // install the logged in user's object into the session
        $session->set('user', $instance);

        // Ensure the new session's metadata is written to the database        
        $session->checkSession();        

        // Purge the old session
        $sql = "DELETE FROM ".PRFX."session WHERE session_id = " . $this->db->qstr($oldSessionId);

        try
        {            
            $this->db->Execute($sql);
        }
        catch (RuntimeException $e)
        {
            // The old session is already invalidated, don't let this block logging in
        }

        // Hit the user last visit field
        $instance->setLastVisit();

        // Add "user state" cookie used for reverse caching proxies like Varnish, Nginx etc.
        $config = \QFactory::getConfig();
        $cookie_domain = $config->get('cookie_domain', '');
        $cookie_path   = $config->get('cookie_path', '/');

        if (QFactory::isClient('site'))
        {
            $cookie = new \Joomla\CMS\Input\Cookie;
            $cookie->set('qwcrm_user_state', 'logged_in', 0, $cookie_path, $cookie_domain, 0);
        }

        return true;
    }

    /**
     * From Joomla 3.7.0 joomla\plugins\user\joomla\joomla.php
     * This method should handle any logout logic and report back to the subject
     *
     * @param   array  $user     Holds the user data.
     * @param   array  $options  Array holding options (client, ...).
     *
     * @return  bool  True on success
     *
     * @since   1.5
     */
    public function onUserLogout($user, $options = array())
    {
        $my      = \QFactory::getUser();
        $session = \QFactory::getSession();
        $config  = \QFactory::getConfig();
        $cookie  = new \Joomla\CMS\Input\Cookie;

        // Make sure we're a valid user first
        if ($user['id'] == 0 && !$my->get('tmp_user'))
        {
            return true;
        }

        $sharedSessions = $config->get('shared_session', '0');

        // Check to see if we're deleting the current session
        if ($my->id == $user['id'] && ($sharedSessions || (!$sharedSessions && $options['clientid'] == \QFactory::getClientId())))
        {
            // Hit the user last visit field
            $my->setLastVisit();

            // Destroy the php session for this user
            $session->destroy();
        }

        // Enable / Disable Forcing logout all users with same userid
        //$forceLogout = $this->params->get('forceLogout', 1);
        $forceLogout = 1;

        if ($forceLogout)
        {
            $sql = "DELETE FROM ".PRFX."session WHERE userid = " . $this->db->qstr((int) $user['id']);

            if (!$sharedSessions)
            {
                $sql .= "AND clientid = " . $this->db->qstr((int) $options['clientid']);
            }

            try
            {
                $this->db->Execute($sql);
            }
            catch (RuntimeException $e)
            {
                return false;
            }
        }

        // Delete "user state" cookie used for reverse caching proxies like Varnish, Nginx etc.
        $cookie_domain = $config->get('cookie_domain', '');
        $cookie_path   = $config->get('cookie_path', '/');

        if (\QFactory::isClient('site'))
        {
            $cookie->set('qwcrm_user_state', '', time() - 86400, $cookie_path, $cookie_domain, 0);            
        }

        return true;
    }

    /**
     * From Joomla 3.7.0 joomla\plugins\user\joomla\joomla.php
     * This method will return a user object
     *
     * If options['autoregister'] is true, if the user doesn't exist yet they will be created
     *
     * @param   array  $user     Holds the user data.
     * @param   array  $options  Array holding options (remember, autoregister, group).
     *
     * @return  JUser
     *
     * @since   1.5
     */
    protected function _getUser($user, $options = array())
    {
        $instance = \Joomla\CMS\User\User::getInstance();
        $id = (int) \Joomla\CMS\User\UserHelper::getUserId($user['username']);

        if ($id)
        {
            $instance->load($id);

            return $instance;
        }

        // TODO : move this out of the plugin
        //$config = JComponentHelper::getParams('com_users');

        // Hard coded default to match the default value from com_users.
        //$defaultUserGroup = $config->get('new_usertype', 2);

        $instance->id = 0;
        $instance->name = $user['fullname'];
        $instance->username = $user['username'];
        $instance->password_clear = $user['password_clear'];

        // Result should contain an email (check).
        $instance->email = $user['email'];
        //$instance->groups = array($defaultUserGroup);

        /*
        // If autoregister is set let's register the user
        $autoregister = isset($options['autoregister']) ? $options['autoregister'] : $this->params->get('autoregister', 1);

        if ($autoregister)
        {
            if (!$instance->save())
            {
                JLog::add('Error in autoregistration for user ' . $user['username'] . '.', JLog::WARNING, 'error');
            }
        }
        else
        {
            // No existing user and autoregister off, this is a temporary user.
            $instance->set('tmp_user', true);
        }
         */

        return $instance;
    }    
    
}

