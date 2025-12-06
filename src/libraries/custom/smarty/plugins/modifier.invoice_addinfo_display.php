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
 * Smarty    Invoice Additional Information modifier plugin
 * Type:     modifier
 * Name:     invoice_addinfo_display
 * Purpose:  convert an Additional Info JSON string to a viewable HTML block
 *
 * @link      http://quantumwarp.com
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 *
 * @return string
 */
function smarty_modifier_invoice_addinfo_display($string)
{
    // Get the QWcrm Application
    $app = \Factory::getApplication();

    // Convert into a standard PHP array or return null
    if(!$additional_info = json_decode($string, true)) { return false; }

    // Build HTML
    $contentFlag = false;
    $html = '';
    foreach ($additional_info as $key => $value) {

        // Make sure there is a value
        if(!$value) {continue;}

        // Apply modifications as required
        switch($key) {
            case 'reason_for_cancelling' :
                $html .= '<strong>'._gettext("Reason for Cancelling").':</strong> '.$value.'<br>';
                break;
            case 'closed_by_creditnote_payment_id' :
                $html .= '<strong>'._gettext("Closed by Credit Note - Payment ID").':</strong> <a href="index.php?component=creditnote&page_tpl=details&creditnote_id='.$value.'">'.$value.'</a><br>';
                break;
            default :
                $html .= '<strong>'._gettext($key).':</strong> '.$value.'<br>';
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
