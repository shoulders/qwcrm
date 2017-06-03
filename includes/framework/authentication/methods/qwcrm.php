<?php
// joomla\plugins\authentication\joomla\joomla.php
/**
 * @package     Joomla.Plugin
 * @subpackage  Authentication.joomla
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_QWEXEC') or die;

/**
 * Joomla Authentication plugin
 *
 * @since  1.5
 */
class PlgAuthenticationQwcrm
{
    private $db;
    
    public function __construct()
    {
        $this->db = QFactory::getDbo();
        
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
        $response->type = 'Qwcrm';

        // Joomla does not like blank passwords
        if (empty($credentials['password']))
        {
            $response->status        = JAuthentication::STATUS_FAILURE;
            //$response->error_message = JText::_('JGLOBAL_AUTH_EMPTY_PASS_NOT_ALLOWED');

            return;
        }

        // Load the relevant user record form the database
        $sql = "SELECT EMPLOYEE_ID, EMPLOYEE_PASSWORD FROM ".PRFX."EMPLOYEE WHERE EMPLOYEE_LOGIN = ".$this->db->qstr($credentials['username']);        
        $rs = $this->db->Execute($sql);
        $result = $rs->GetRowAssoc();   // If I call this twice for this search, no results are shown on the TPL
              
        // if there is a match, verify it
        if ($result)
        {
            $match = JUserHelper::verifyPassword($credentials['password'], $result['EMPLOYEE_PASSWORD'], $result['EMPLOYEE_ID']);

            if ($match === true)
            {
                // Bring this in line with the rest of the system
                $user                    = JUser::getInstance($result['EMPLOYEE_ID']);               
                
                $response->email         = $user->email;
                $response->fullname      = $user->name;
                $response->status        = JAuthentication::STATUS_SUCCESS;
                $response->error_message = '';
            }
            else
            {
                // Invalid password
                $response->status        = JAuthentication::STATUS_FAILURE;
                //$response->error_message = JText::_('JGLOBAL_AUTH_INVALID_PASS');
            }
        }
        else
        {
            // Let's hash the entered password even if we don't have a matching user for some extra response time
            // By doing so, we mitigate side channel user enumeration attacks
            JUserHelper::hashPassword($credentials['password']);

            // Invalid user
            $response->status        = JAuthentication::STATUS_FAILURE;
            //$response->error_message = JText::_('JGLOBAL_AUTH_NO_USER');
        }

        return;
        
    }
    
    // joomla\plugins\user\joomla\joomla.php
    /**
     * @package     Joomla.Plugin
     * @subpackage  User.joomla
     *
     * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
     * @license     GNU General Public License version 2 or later; see LICENSE.txt
     */

    /**
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

        $sql = "DELETE FROM ".PRFX."session WHERE userid = ". (int) $user['id'];

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

    /**
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
     *
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
                    $lang = QFactory::getLanguage();
                    $defaultLocale = $lang->getTag();

                    /**
                     * Look for user language. Priority:
                     *     1. User frontend language
                     *     2. User backend language
                     *
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
                    $mail = QFactory::getMailer()
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
    }*/

    /**
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
            $this->app->enqueueMessage(JText::_('JERROR_NOLOGIN_BLOCKED'), 'warning');

            return false;
        }

        // Authorise the user based on the group information
        if (!isset($options['group']))
        {
            $options['group'] = 'USERS';
        }

        /* Check the user can login.
        $result = $instance->authorise($options['action']);

        if (!$result)
        {
            $this->app->enqueueMessage(JText::_('JERROR_LOGIN_DENIED'), 'warning');

            return false;
        }*/

        // Mark the user as logged in
        $instance->guest = 0;

        $session = QFactory::getSession();

        // Grab the current session ID
        $oldSessionId = $session->getId();

        // Fork the session - regenerates it
        $session->fork();

        // install the logged in user's object into the session
        $session->set('user', $instance);

        // Ensure the new session's metadata is written to the database        
        $session->checkSession();
        

        // Purge the old session
        $sql = "DELETE FROM ".PFRX."session WHERE session_id = " . $this->db->qstr($oldSessionId);

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
        $config = new QConfig;
        $cookie_domain = $config->get('cookie_domain', '');
        $cookie_path   = $config->get('cookie_path', '/');

        if (QFactory::isClient('site'))
        {
            $cookie = new Cookie;
            $cookie->set('qwcrm_user_state', 'logged_in', 0, $cookie_path, $cookie_domain, 0);
        }

        return true;
    }

    /**
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
        $my      = QFactory::getUser();
        $session = QFactory::getSession();
        $config  = QFactory::getConfig();
        $cookie  = new Cookie;

        // Make sure we're a valid user first
        if ($user['id'] == 0 && !$my->get('tmp_user'))
        {
            return true;
        }

        $sharedSessions = $config->get('shared_session', '0');

        // Check to see if we're deleting the current session
        if ($my->id == $user['id'] && ($sharedSessions || (!$sharedSessions && $options['clientid'] == QFactory::getClientId())))
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
                $sql .= "AND client_id = " . $this->db->qstr((int) $options['clientid']);
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

        if (QFactory::isClient('site'))
        {
            $cookie->set('qwcrm_user_state', '', time() - 86400, $cookie_path, $cookie_domain, 0);            
        }

        return true;
    }

    /**
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
        $instance = JUser::getInstance();
        $id = (int) JUserHelper::getUserId($user['username']);

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

        /* If autoregister is set let's register the user
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
        }*/

        return $instance;
    }    
    
}