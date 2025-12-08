<?php

/*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

/**
 * Smarty plugin
 *
 * @package    Smarty
 * @subpackage PluginsModifier
 */

/**
 * Smarty    Voucher Information modifier plugin
 * Type:     modifier
 * Name:     vouchers_display
 * Purpose:  convert an Voucher records JSON string to a viewable HTML block
 *
 * @link      http://quantumwarp.com
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 *
 * @return string
 */
function smarty_modifier_vouchers_display($string)
{
    // Get the QWcrm Application
    $app = \Factory::getApplication();

    // Convert into a standard PHP array or return null
    if(!$vouchers = json_decode($string, true)) { return false; }

    // Build HTML
    $contentFlag = false;
    $html = '';
    foreach ($vouchers as $voucher) {

        foreach ($voucher as $key => $value) {

            // Apply modifications as required
            if($key == 'voucher_id')
            {
                $html .= '<strong>'._gettext("Voucher ID").':</strong> <a href="index.php?component=voucher&page_tpl=details&voucher_id='.$value.'">'.$value.'</a><br>';
                $contentFlag = true;
            }
            if($key == 'voucher_code')
            {
                $html .= '<strong>'._gettext("Voucher Code").':</strong> '.$value.'<br>';
                $contentFlag = true;
            }
            if($key == 'expiry_date')
            {
                $html .= '<strong>'._gettext("Expiry Date").':</strong> '.date(str_replace('%', '', DATE_FORMAT), strtotime($value)).'<br>';
                $contentFlag = true;
            }
            if($key == 'unit_net')
            {
                $html .= '<strong>'._gettext("Net").':</strong> '.CURRENCY_SYMBOL.sprintf('%.2f', $value).'<br>';
                $contentFlag = true;
            }
            if($key == 'balance')
            {
                $html .= '<strong>'._gettext("Balance").':</strong> '.CURRENCY_SYMBOL.sprintf('%.2f', $value).'<br>';
                $contentFlag = true;
            }
        }

        // Break the different redepmtions
        $html .= '<hr><br>';

    }

    $html = rtrim($html, '<br><hr><br>');   // Remove the trailing separator if present

    // If there is no additional info, return false
    if(!$contentFlag) {
        $html = false;
    }

    return $html;

}
