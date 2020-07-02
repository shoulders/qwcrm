<?php

/*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

class QError extends System {

    /** Mandatory Code **/

    ################################################
    #         Error Reporting Level                #
    ################################################

    public function configure_php_error_reporting() {

        // Set the error_reporting
        switch ($this->app->config->get('error_reporting'))
        {
            case 'default':
            case '-1':
                break;

            case 'none':
            case '0':
                error_reporting(0);
                break;

            case 'verysimple':
                error_reporting(E_ERROR | E_WARNING | E_PARSE | ~E_NOTICE);
                ini_set('display_errors', 1);
                break;            

            case 'simple':
                error_reporting(E_ERROR | E_WARNING | E_PARSE);
                ini_set('display_errors', 1);
                break;

            case 'maximum':
                error_reporting(E_ALL);
                ini_set('display_errors', 1);
                break;

            case 'development':
                error_reporting(-1);
                ini_set('display_errors', 1);
                break;

            default:
                //error_reporting(-1);
                //ini_set('display_errors', 1);
                break;
        }

    }

    /** Other Functions **/

    ################################################
    #         Load Whoops Error Handler            #  // This replaces the PHP default error handler
    ################################################

    public function load_whoops($run_whoops = false) {

        if($run_whoops) {    
            $whoops = new \Whoops\Run;
            $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
            $whoops->register();
            //trigger_error("Number cannot be larger than 10"); // This can be used to simulate an error*/
        }

        return;

    }

    ################################################
    #    WSOD Mitigation                           #  // Use these when you get a white screen error 
    ################################################

    /*
    function shutdown(){
      var_dump(error_get_last());
    }

    register_shutdown_function('shutdown');
     */
    
}