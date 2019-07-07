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

                /*
                if (JFactory::getApplication()->isClient('administrator'))
                {
                    $response->language = $user->getParam('admin_language');
                }
                else
                {
                    $response->language = $user->getParam('language');
                }
                */

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
 
}

