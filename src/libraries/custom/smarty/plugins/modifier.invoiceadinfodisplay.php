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
 * Name:     invoiceadinfodisplay
 * Purpose:  convert an Additional Info JSON string to a viewable HTML block
 *
 * @link      http://quantumwarp.com
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 *
 * @return string
 */
function smarty_modifier_invoiceadinfodisplay($string)
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
        $html .= '<strong>'._gettext("Reason for Cancelling").':</strong> '.$value.'<br>';
        $contentFlag = true;
        
    }  
    
    $html = rtrim($html, '<br>');   // Remove the last <br> if present
    
    // If there is no additional info, return false
    if(!$contentFlag) {
        $html = false;
    }    
    
    return $html;
    
}