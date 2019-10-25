<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'client.php');
require(INCLUDES_DIR.'invoice.php'); // require to stop email sub-system error
require(INCLUDES_DIR.'user.php');
require(INCLUDES_DIR.'workorder.php');

// Delete any expired resets (CRON is better)
delete_expired_reset_codes();

// Prevent undefined variable errors (temp)
\QFactory::$VAR['reset_code'] = isset(\QFactory::$VAR['reset_code']) ? \QFactory::$VAR['reset_code'] : null;
$smarty->assign('reset_code', \QFactory::$VAR['reset_code']);
\QFactory::$VAR['token'] = isset(\QFactory::$VAR['token']) ? \QFactory::$VAR['token'] : null;
$smarty->assign('token', \QFactory::$VAR['token']);

###########################################################
# Stage 1 - Load Enter Email (Page Default Action)        #
########################################################### 

$stage = 'enter_email';

###########################################################
#  STAGE 2 - Email Submitted, Load Enter Token            #
###########################################################

if (isset(\QFactory::$VAR['submit']) && isset(\QFactory::$VAR['email']) && \QFactory::$VAR['email']) {
    
    // Prevent direct access to this page (when submitting form)
    if(!check_page_accessed_via_qwcrm('user', 'reset')) {
        header('HTTP/1.1 403 Forbidden');
        die(_gettext("No Direct Access Allowed."));
    }

    // if recaptcha is disabled || recaptcha is enabled and passes authentication
    if(!$config->get('recaptcha') || ($config->get('recaptcha') && authenticate_recaptcha($config->get('recaptcha_secret_key'), \QFactory::$VAR['g-recaptcha-response']))) {

        /* Allowed to submit */

        // Make sure user account exists and is not blocked
        if(!isset(\QFactory::$VAR['email']) || !$user_id = validate_reset_email(\QFactory::$VAR['email'])) {

            // Display error message
            systemMessagesWrite('danger', _gettext("You cannot reset the password on this account. It either does not exist or is blocked."));

        // The account is valid and allowed to be reset
        } else {

            // Update reset count for the user
            update_user_reset_count($user_id);

            // Build the reset email and send it
            send_reset_email($user_id);

            // Load the enter_token page
            $stage = 'enter_token';
            //force_page('user', 'reset', 'layout=confirm', 'get'); // Using force_page() keeps the URLs the same (from stege 1 and direct from email)

        }

    }
    
}

###########################################################
#  STAGE 2a - Token Submitted via Email, Load Enter Token #
###########################################################

if(!isset(\QFactory::$VAR['submit']) && isset(\QFactory::$VAR['token']) && \QFactory::$VAR['token']) {    
    
    // Load the 'Enter Token' form
    $smarty->assign('token', \QFactory::$VAR['token']);
    $stage = 'enter_token';
    
}

###########################################################     
#  STAGE 3 - Token Submitted, Load Enter Password         #
###########################################################

if (isset(\QFactory::$VAR['submit']) && isset(\QFactory::$VAR['token']) && \QFactory::$VAR['token']) {  
    
    // Load the 'Enter Token' form (Default Action)       
    $stage = 'enter_token';
    
    // Prevent direct access to this page (when submitting form)
    if(!check_page_accessed_via_qwcrm('user', 'reset')) {
        header('HTTP/1.1 403 Forbidden');
        die(_gettext("No Direct Access Allowed."));
    }

    // if recaptcha is disabled || recaptcha is enabled and passes authentication
    if(!$config->get('recaptcha') || ($config->get('recaptcha') && authenticate_recaptcha($config->get('recaptcha_secret_key'), \QFactory::$VAR['g-recaptcha-response']))) {

        /* Allowed to submit */

        // Process the token and reset the password for the account - this function sets response messages
        if(validate_reset_token(\QFactory::$VAR['token'])) {

            // Authorise the actual password change, return the secret code and assign reset code into Smarty
            $smarty->assign('reset_code', authorise_password_reset(\QFactory::$VAR['token']));

            // Load the 'Enter Password' form
            $stage = 'enter_password';

        }

    }    
    
}
        
###########################################################  
#  STAGE 4 - Password Submitted, Complete Reset           #
###########################################################

if (isset(\QFactory::$VAR['submit']) && isset(\QFactory::$VAR['reset_code']) && \QFactory::$VAR['reset_code'] && isset(\QFactory::$VAR['password']) && \QFactory::$VAR['password']) {
    
    // Load the 'Enter Password' form (Default Action)       
    $stage = 'enter_password';
    
    // Prevent direct access to this page
    if(!check_page_accessed_via_qwcrm('user', 'reset')) {
        header('HTTP/1.1 403 Forbidden');
        die(_gettext("No Direct Access Allowed."));
    }       
    
    // Validate the reset code
    if(!validate_reset_code(\QFactory::$VAR['reset_code'])) {

        // Display an error message
        systemMessagesWrite('danger', _gettext("The submitted reset code was invalid."));

    } else {

        // Get the user_id by the reset_code
        $user_id = get_user_id_by_reset_code(\QFactory::$VAR['reset_code']);

        // Delete reset_code for this user
        delete_user_reset_code($user_id);

        // Reset the password
        reset_user_password($user_id, \QFactory::$VAR['password']);

        // Logout the user out silently (if logged in)
        logout(true);

        // Redirect to login page with success or failed message
        systemMessagesWrite('success', _gettext("Password reset successfully."));
        force_page('user', 'login');

    }    

}

########################################################### 
#  Build the Page                                         #
########################################################### 

// Set reCaptcha values
$smarty->assign('recaptcha', $config->get('recaptcha'));
$smarty->assign('recaptcha_site_key', $config->get('recaptcha_site_key'));

// Select the correct reset stage to load
$smarty->assign('stage', $stage);