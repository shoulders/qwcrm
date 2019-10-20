<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

// Prevent direct Access
defined('_QWEXEC') or die;

require(INCLUDES_DIR.'workorder.php');
  
// Is there a posted query string and is the string length greater than 0
if(isset($VAR['posted_scope_string']) && strlen($VAR['posted_scope_string']) > 0) {

    // BuildPage will only hold the html for this scope table
    \QFactory::$BuildPage = get_workorder_scope_suggestions($VAR['posted_scope_string']);

}

// Skip page logging
if(!defined('SKIP_LOGGING')) {
    define('SKIP_LOGGING', true);
}