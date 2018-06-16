<?php

/*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

/** Mandatory Code **/

################################################
#         Error Reporting Level                #
################################################

// Set the error_reporting
switch (QFactory::getConfig()->get('error_reporting'))
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

/** Other Functions **/

################################################
#    WSOD Mitigation                           #  // Use this when you get a white screen error 
################################################

/*function shutdown(){
  var_dump(error_get_last());
}

register_shutdown_function('shutdown');*/