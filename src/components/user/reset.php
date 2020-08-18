<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Delete any expired resets (CRON is better)
$this->app->components->user->deleteExpiredResetCodes();

// Prevent undefined variable errors (temp)
\CMSApplication::$VAR['reset_code'] = \CMSApplication::$VAR['reset_code'] ?? null;
$this->app->smarty->assign('reset_code', \CMSApplication::$VAR['reset_code']);
\CMSApplication::$VAR['token'] = \CMSApplication::$VAR['token'] ?? null;
$this->app->smarty->assign('token', \CMSApplication::$VAR['token']);

###########################################################
# Stage 1 - Load Enter Email (Page Default Action)        #
########################################################### 

$stage = 'enter_email';

###########################################################
#  STAGE 2 - Email Submitted, Load Enter Token            #
###########################################################

if (isset(\CMSApplication::$VAR['submit']) && isset(\CMSApplication::$VAR['email']) && \CMSApplication::$VAR['email']) {
    
    // Prevent direct access to this page (when submitting form)
    if(!$this->app->system->security->checkPageAccessedViaQwcrm('user', 'reset')) {
        header('HTTP/1.1 403 Forbidden');
        die(_gettext("No Direct Access Allowed."));
    }

    // if recaptcha is disabled || recaptcha is enabled and passes authentication
    if(!$this->app->config->get('recaptcha') || ($this->app->config->get('recaptcha') && $this->app->components->user->authenticateRecaptcha($this->app->config->get('recaptcha_secret_key'), \CMSApplication::$VAR['g-recaptcha-response']))) {

        /* Allowed to submit */

        // Make sure user account exists and is not blocked
        if(!isset(\CMSApplication::$VAR['email']) || !$user_id = $this->app->components->user->validateResetEmail(\CMSApplication::$VAR['email'])) {

            // Display error message
            $this->app->system->variables->systemMessagesWrite('danger', _gettext("You cannot reset the password on this account. It either does not exist or is blocked."));

        // The account is valid and allowed to be reset
        } else {

            // Update reset count for the user
            $this->app->components->user->updateResetCount($user_id);

            // Build the reset email and send it
            $this->app->components->user->sendResetEmail($user_id);

            // Load the enter_token page
            $stage = 'enter_token';
            //$this->app->system->page->force_page('user', 'reset', 'layout=confirm', 'get'); // Using $this->app->system->page->force_page() keeps the URLs the same (from stege 1 and direct from email)

        }

    }
    
}

###########################################################
#  STAGE 2a - Token Submitted via Email, Load Enter Token #
###########################################################

if(!isset(\CMSApplication::$VAR['submit']) && isset(\CMSApplication::$VAR['token']) && \CMSApplication::$VAR['token']) {    
    
    // Load the 'Enter Token' form
    $this->app->smarty->assign('token', \CMSApplication::$VAR['token']);
    $stage = 'enter_token';
    
}

###########################################################     
#  STAGE 3 - Token Submitted, Load Enter Password         #
###########################################################

if (isset(\CMSApplication::$VAR['submit']) && isset(\CMSApplication::$VAR['token']) && \CMSApplication::$VAR['token']) {  
    
    // Load the 'Enter Token' form (Default Action)       
    $stage = 'enter_token';
    
    // Prevent direct access to this page (when submitting form)
    if(!$this->app->system->security->checkPageAccessedViaQwcrm('user', 'reset')) {
        header('HTTP/1.1 403 Forbidden');
        die(_gettext("No Direct Access Allowed."));
    }

    // if recaptcha is disabled || recaptcha is enabled and passes authentication
    if(!$this->app->config->get('recaptcha') || ($this->app->config->get('recaptcha') && $this->app->components->user->authenticateRecaptcha($this->app->config->get('recaptcha_secret_key'), \CMSApplication::$VAR['g-recaptcha-response']))) {

        /* Allowed to submit */

        // Process the token and reset the password for the account - this function sets response messages
        if($this->app->components->user->validateResetToken(\CMSApplication::$VAR['token'])) {

            // Authorise the actual password change, return the secret code and assign reset code into Smarty
            $this->app->smarty->assign('reset_code', $this->app->components->user->authorisePasswordReset(\CMSApplication::$VAR['token']));

            // Load the 'Enter Password' form
            $stage = 'enter_password';

        }

    }    
    
}
        
###########################################################  
#  STAGE 4 - Password Submitted, Complete Reset           #
###########################################################

if (isset(\CMSApplication::$VAR['submit']) && isset(\CMSApplication::$VAR['reset_code']) && \CMSApplication::$VAR['reset_code'] && isset(\CMSApplication::$VAR['password']) && \CMSApplication::$VAR['password']) {
    
    // Load the 'Enter Password' form (Default Action)       
    $stage = 'enter_password';
    
    // Prevent direct access to this page
    if(!$this->app->system->security->checkPageAccessedViaQwcrm('user', 'reset')) {
        header('HTTP/1.1 403 Forbidden');
        die(_gettext("No Direct Access Allowed."));
    }       
    
    // Validate the reset code
    if(!$this->app->components->user->validateResetCode(\CMSApplication::$VAR['reset_code'])) {

        // Display an error message
        $this->app->system->variables->systemMessagesWrite('danger', _gettext("The submitted reset code was invalid."));

    } else {

        // Get the user_id by the reset_code
        $user_id = $this->app->components->user->getIdByResetCode(\CMSApplication::$VAR['reset_code']);

        // Delete reset_code for this user
        $this->app->components->user->deleteResetCode($user_id);

        // Reset the password
        $this->app->components->user->resetPassword($user_id, \CMSApplication::$VAR['password']);

        // Logout the user out silently (if logged in)
        $this->app->components->user->logout(true);

        // Redirect to login page with success or failed message
        $this->app->system->variables->systemMessagesWrite('success', _gettext("Password reset successfully."));
        $this->app->system->page->forcePage('user', 'login');

    }    

}

########################################################### 
#  Build the Page                                         #
########################################################### 

// Set reCaptcha values
$this->app->smarty->assign('recaptcha', $this->app->config->get('recaptcha'));
$this->app->smarty->assign('recaptcha_site_key', $this->app->config->get('recaptcha_site_key'));

// Select the correct reset stage to load
$this->app->smarty->assign('stage', $stage);