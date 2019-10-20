<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'setup.php');

// Prevent direct access to this page
if(!check_page_accessed_via_qwcrm('setup', 'choice', 'setup')) {
    header('HTTP/1.1 403 Forbidden');
    die(_gettext("No Direct Access Allowed."));
}

// Define the setup type for smarty - currently only used for 'upgrade'
isset($VAR['setup_type']) ? $smarty->assign('setup_type', $VAR['setup_type']) : $smarty->assign('setup_type', null);

// Create a Setup Object
$qsetup = new QSetup($VAR);

// Get Compatibility Results
$smarty->assign('compatibility_results', $qsetup->test_server_enviroment_compatibility());

// Build the page
\QFactory::$BuildPage .= $smarty->fetch('setup/choice.tpl');