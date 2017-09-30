<?php

/*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

/** Mandatory Code **/

################################################
#         Load Language                        #
################################################

// Load compatibility layer (motranslator)
PhpMyAdmin\MoTranslator\Loader::loadFunctions();

// Autodetect Language - I18N support information here
if($QConfig->autodetect_language === '1' || (defined('QWCRM_SETUP') && QWCRM_SETUP == 'install')) {
    
    if(!$language = locale_accept_from_http($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
        $language = $QConfig->default_language; 
    }
    
} elseif($QConfig->autodetect_language === '0') {
    
    $language = $QConfig->default_language;    

// if installing - use the locale language if detected or default to english
} else {
    
    if(!$language = locale_accept_from_http($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
        $language = 'en_GB'; 
    }
    
}

// Autodetect Language - I18N support information here
if($QConfig->autodetect_language === '1' || (defined('QWCRM_SETUP') && QWCRM_SETUP == 'install')) {
    
    // Use the locale language if detected or default language or british english
    if(!$language = locale_accept_from_http($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
        
        // set default language as the chosen language or fallback to british english
        if(!$language = $QConfig->default_language) {
            $language = 'en_GB';
        }
        
    }
          
    // if there is no language file for the locale, set language to british english - This allows me to use CONSTANTS in translations but bypasses normal fallback mechanism for _gettext()
    if(!is_file(LANGUAGE_DIR.$language.'/LC_MESSAGES/site.po')) {
        $language = 'en_GB';    
    }
    
} else {
    
    // Use the default language
    $language = $QConfig->default_language;

}

// Here we define the global system locale given the found language
putenv("LANG=$language");

// this might be useful for date functions (LC_TIME) or money formatting (LC_MONETARY), for instance
_setlocale(LC_ALL, $language);

// Set the text domain
$textdomain = 'site';

// this will make _gettext look for ../language/<lang>/LC_MESSAGES/site.mo
_bindtextdomain($textdomain, LANGUAGE_DIR);

// indicates in what encoding the file should be read
_bind_textdomain_codeset($textdomain, 'UTF-8');

// here we indicate the default domain the _gettext() calls will respond to
_textdomain($textdomain);

/** Other Functions **/