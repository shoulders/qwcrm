<?php

defined('_QWEXEC') or die;

$smarty->assign('qwcrm_version', QWCRM_VERSION);

$BuildPage .= $smarty->fetch('core/blocks/theme_footer_block.tpl');