<?php

defined('_QWEXEC') or die;

$smarty->assign('remember_me', $GConfig->remember_me);
$smarty->assign('captcha', $captcha);
$smarty->assign('recaptcha_site_key', $recaptcha_site_key);

$BuildPage .= $smarty->fetch('core/login.tpl');