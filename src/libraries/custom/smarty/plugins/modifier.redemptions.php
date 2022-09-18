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
 * Smarty    Voucher Redemptions Information modifier plugin
 * Type:     modifier
 * Name:     redemptions
 * Purpose:  convert voucher redemptions JSON string to a viewable HTML block
 *
 * @link      http://quantumwarp.com
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 *
 * @return string
 */
function smarty_modifier_redemptions($string)
{
    // Get the QWcrm Application
    //$app = \Factory::getApplication();
    
    // Convert into a standard PHP array or return null
    if(!$redemptions = json_decode($string)) { return false; }
    
    // Build HTML
    $contentFlag = false;
    $html = '';
    foreach ($redemptions as $redemption) {
        
        foreach ($redemption as $key => $value) {
        
            // Make sure there is a value
            if(!$value) {continue;}

            // Apply modifications as required
            if($key == 'payment_id')
            {                       
                $html .= '<strong>'._gettext("Payment ID").':</strong> <a href="index.php?component=payment&page_tpl=details&payment_id='.$value.'">'.$value.'</a><br>';
                $contentFlag = true;            
            }
            if($key == 'redeemed_on')
            {                   
                $html .= '<strong>'._gettext("Redeemed On").':</strong> '.date(str_replace('%', '', DATE_FORMAT), strtotime($value)).'<br>';
                $contentFlag = true;  
            }  
            if($key == 'redeemed_client_id')
            {                       
                $html .= '<strong>'._gettext("Redeemed Client ID").':</strong> <a href="index.php?component=client&page_tpl=details&client_id='.$value.'">'.$value.'</a><br>';
                $contentFlag = true;            
            }
            if($key == 'redeemed_invoice_id')
            {                       
                $html .= '<strong>'._gettext("Redeemed Invoice ID").':</strong> <a href="index.php?component=invoice&page_tpl=details&invoice_id='.$value.'">'.$value.'</a><br>';
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