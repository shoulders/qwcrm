<?php

/*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

/** Mandatory Code **/

// Force SSL/HTTPS if enabled - add base path stuff here
if($QConfig->force_ssl >= 1 && !isset($_SERVER['HTTPS'])) {   
    force_page($_SERVER['REQUEST_URI'], null, null, 'auto', 'https' );
}

// add security routines here
// post get varible sanitisation
// url checking,
// sql injection

/** Other Functions **/