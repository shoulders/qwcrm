<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/workorder.php');

// Build the page
$smarty->assign('workorder_statuses', get_workorder_statuses($db));
$smarty->assign('workorders_open', display_workorders($db, 'workorder_id', 'DESC', true, $page_no, '25', null, null, 'open'));
$BuildPage .= $smarty->fetch('workorder/open.tpl');