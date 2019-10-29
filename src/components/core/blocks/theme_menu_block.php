<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;
 
/* Get Workorder Status if we have a workorder_id - not currently used
if(\CMSApplication::$VAR['workorder_id'] != '') {
    $this->app->smarty->assign('menu_workorder_is_closed', menu_get_single_workorder_is_closed(\CMSApplication::$VAR['workorder_id']));
}*/