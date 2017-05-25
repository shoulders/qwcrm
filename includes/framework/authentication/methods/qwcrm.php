<?php
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
class PlgAuthenticationQwcrm //extends JFramework
{
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

        // Joomla does not like blank passwords
        if (empty($credentials['password']))
        {
            $response->status        = QAuthentication::STATUS_FAILURE;
            $response->error_message = JText::_('JGLOBAL_AUTH_EMPTY_PASS_NOT_ALLOWED');

            return;
        }

        // Get a database object
        $db    = QFactory::getDbo();

        // Load the relevant user record form the database
        $sql = "SELECT EMPLOYEE_ID, EMPLOYEE_PASSWORD FROM ".PRFX."EMPLOYEE WHERE EMPLOYEE_LOGIN = ".$db->qstr($credentials['username']);        
        $rs = $db->Execute($sql);
        $result = $rs->GetRowAssoc();   // If I call this twice for this search, no results are shown on the TPL
              
        // if there is a match, verify it
        if ($result)
        {
            $match = QUserHelper::verifyPassword($credentials['password'], $result['EMPLOYEE_PASSWORD'], $result['EMPLOYEE_ID']);

            if ($match === true)
            {
                // Bring this in line with the rest of the system
                $user               = QUser::getInstance($result['EMPLOYEE_ID']);
                $response->email    = $user->email;
                $response->fullname = $user->name;

                $response->status        = QAuthentication::STATUS_SUCCESS;
                $response->error_message = '';
            }
            else
            {
                // Invalid password
                $response->status        = QAuthentication::STATUS_FAILURE;
                $response->error_message = JText::_('JGLOBAL_AUTH_INVALID_PASS');
            }
        }
        else
        {
            // Let's hash the entered password even if we don't have a matching user for some extra response time
            // By doing so, we mitigate side channel user enumeration attacks
            QUserHelper::hashPassword($credentials['password']);

            // Invalid user
            $response->status        = QAuthentication::STATUS_FAILURE;
            //$response->error_message = JText::_('JGLOBAL_AUTH_NO_USER');
        }

        return;
        
    }
}
