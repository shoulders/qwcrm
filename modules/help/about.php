<?php

defined('_QWEXEC') or die;

$smarty->assign('help_details', $VAR);

if($VAR['submit'] != '') {
    send_email($VAR['customer_name'], $VAR['customer_email'], $VAR['subject'], $VAR['body']);
}

$BuildPage .= $smarty->fetch('help/about.tpl');
