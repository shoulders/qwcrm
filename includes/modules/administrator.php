<?php

require(INCLUDES_DIR.'modules/administrator.php');

/**
 * Method to get the PHP info
 *
 * @return  string  PHP info
 *
 * @since   1.6
 * 
 * from joomla sysinfo.php - it strips dodgy formatting
 */

/**
 * @package     Joomla.Administrator
 * @subpackage  com_admin
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
function getPHPInfo()
{
    ob_start();
    date_default_timezone_set('UTC');
    phpinfo(INFO_GENERAL | INFO_CONFIGURATION | INFO_MODULES);
    $phpInfo = ob_get_contents();
    ob_end_clean();
    preg_match_all('#<body[^>]*>(.*)</body>#siU', $phpInfo, $output);
    $output = preg_replace('#<table[^>]*>#', '<table class="table table-striped adminlist">', $output[1][0]);
    $output = preg_replace('#(\w),(\w)#', '\1, \2', $output);
    $output = preg_replace('#<hr />#', '', $output);
    $output = str_replace('<div class="center">', '', $output);
    $output = preg_replace('#<tr class="h">(.*)<\/tr>#', '<thead><tr class="h">$1</tr></thead><tbody>', $output);
    $output = str_replace('</table>', '</tbody></table>', $output);
    $output = str_replace('</div>', '', $output);    

    return $output;
}