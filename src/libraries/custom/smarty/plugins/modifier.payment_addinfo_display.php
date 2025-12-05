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
 * Smarty    Payment Additional Information modifier plugin
 * Type:     modifier
 * Name:     payment_addinfo_display
 * Purpose:  convert an Additional Info JSON string to a viewable HTML block
 *
 * @link      http://quantumwarp.com
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 *
 * @return string
 */
function smarty_modifier_payment_addinfo_display($string)
{
    // Get the QWcrm Application
    $app = \Factory::getApplication();

    // Convert into a standard PHP array or return null
    if(!$additional_info = json_decode($string, true)) { return false; }

    // Get field names in an array for translation
    $adNames = $app->components->payment->getAdditionalInfoTypes();
    $cardNames = $app->components->payment->getCardTypes();

    // Build HTML
    $contentFlag = false;
    $html = '';
    foreach ($additional_info as $key => $value) {

        // Make sure there is a value
        if(!$value) {continue;}

        // Apply modifications as required
        if(in_array($key, $adNames)){

            // Payment Additional Information (the original, lol)
            switch($key) {
                case 'card_type_key' :
                    $html .= '<strong>'._gettext("$adNames[$key]").':</strong> '._gettext("$cardNames[$value]").'<br>';
                    break;
                case 'paypal_transaction_id' :
                    $html .= '<strong>'._gettext("$adNames[$key]").':</strong> <a href="https://www.paypal.com/myaccount/transaction/details/'.$value.'">'.$value.'</a><br>';
                    break;
                default :
                    $html .= '<strong>'._gettext("$adNames[$key]").':</strong> '.$value.'<br>';
            }

        // Additional messages and flags
        } else {
            switch($key) {
                case 'reason_for_cancelling' :
                    $html .= '<strong>'._gettext("Reason for Cancelling").':</strong> '.$value.'<br>';
                    break;
                default :
                    $html .= '<strong>'._gettext($key).':</strong> '.$value.'<br>';
            }
        }

        $contentFlag = true;

    }

    $html = rtrim($html, '<br>');   // Remove the last <br> if present

    // If there is no additional info, return false
    if(!$contentFlag) {
        $html = false;
    }

    return $html;

}
