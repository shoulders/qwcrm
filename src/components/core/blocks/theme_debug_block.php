<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

$this->app->smarty->assign('IPaddress',                $this->app->system->security->getVisitorIpAddress()            );  // IP address of the Visitor
$this->app->smarty->assign('pageLoadTime',             microtime(1) - \CMSApplication::$VAR['system']['startTime']          );  // Time to load the page to the nearest microsecond
$this->app->smarty->assign('pageDisplayController',    $page_controller                    );  // the location of the real php file that loads the page
$this->app->smarty->assign('loadedComponent',          \CMSApplication::$VAR['component']                   );  // Loaded component
$this->app->smarty->assign('loadedPageTpl',            \CMSApplication::$VAR['page_tpl']                    );  // Loaded page
$this->app->smarty->assign('startMem',                 \CMSApplication::$VAR['system']['startMem'] / 1048576                 );  // PHP Memory used when starting QWcrm (in MB)
$this->app->smarty->assign('currentMem',               memory_get_usage() / 1048576        );  // PHP Memory used at the time this php is called (in MB)
$this->app->smarty->assign('peakMem',                  memory_get_peak_usage() / 1048576   );  // Peak PHP Memory used during the page load (in MB)

//\CMSApplication::$VAR['debug']['infoOutput'] - just incase I need a propername

// Advanced Debug - Only use in offline sites and for development only
$this->app->smarty->assign('qwcrmAdvancedDebug', $this->app->config->get('qwcrm_advanced_debug'));
if($this->app->config->get('qwcrm_advanced_debug')) {
    $this->app->smarty->assign('phpErrorGetLast', htmlspecialchars(print_r(error_get_last(), true)));
    $this->app->smarty->assign('definedPhpVariables', htmlspecialchars(print_r(get_defined_vars(), true)));
    $this->app->smarty->assign('definedPhpConstants', htmlspecialchars(print_r(get_defined_constants(), true)));
    $this->app->smarty->assign('definedPhpFunctions', print_r(get_defined_functions(), true));
    $this->app->smarty->assign('declaredPhpClasses', print_r(get_declared_classes(), true));
    $this->app->smarty->assign('serverEnviromentalVariables', htmlspecialchars(print_r($_SERVER, true)));
}
