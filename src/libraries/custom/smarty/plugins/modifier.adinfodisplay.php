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
 * Smarty    Additional Information modifier plugin
 * Type:     modifier
 * Name:     adinfodisplay
 * Purpose:  convert an Additional Info JSON string to a viewable HTML block
 *
 * @link      http://quantumwarp.com
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 *
 * @return string
 */
function smarty_modifier_adinfodisplay($string)
{
    // Get the QWcrm Application
    $app = \Factory::getApplication();
    
    // Convert into a standard PHP array or return null
    if(!$additional_info = json_decode($string)) { return; }
    
    // Get field names in an array for translation
    $adNames = $app->components->payment->getAdditionalInfoTypes();
    $cardNames = $app->components->payment->getCardTypes();
    
    // Build HTML
    $contentFlag = false;
    $html = '';
    foreach ($additional_info as $key => $value) {
        
        if($key == 'card_type_key') {
            
            if($value) {
                $html .= '<strong>'._gettext("$adNames[$key]").':</strong> '._gettext("$cardNames[$value]").'<br>';
                $contentFlag = true;
            }            
            
        } else {
            
            if($value) {
                $html .= '<strong>'._gettext("$adNames[$key]").':</strong> '.$value.'<br>';
                $contentFlag = true;
            }
            
        }
       
    }  
    rtrim($html, '<br>');   // Remove the last <br> if present
    $html .= '';   
    
    // If no additional info return false
    if(!$contentFlag) {
        $html = false;
    }    
    
    return $html;
    
}
