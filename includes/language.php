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
if (($QConfig->autodetect_language == '1' || $QConfig->autodetect_language == null)) {
    
    // Use the locale language if detected or default language or british english
    if (!$language = locale_accept_from_http($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
        
        // Set default language as the chosen language or fallback to british english
        if (!$language = $QConfig->default_language) {
            $language = 'en_GB';
        }
    }
          
    // If there is no language file for the locale, set language to british english - This allows me to use CONSTANTS in translations but bypasses normal fallback mechanism for _gettext()
    if (!is_file(LANGUAGE_DIR.$language.'/LC_MESSAGES/site.po')) {
        $language = 'en_GB';
    }
} else {

    // Set default language or fallback to british english
    if (!$language = $QConfig->default_language) {
        $language = 'en_GB';
    }
}

// Here we define the global system locale given the found language
putenv("LANG=$language");

// This might be useful for date functions (LC_TIME) or money formatting (LC_MONETARY), for instance
_setlocale(LC_ALL, $language);

// Set the text domain
$textdomain = 'site';

// This will make _gettext look for ../language/<lang>/LC_MESSAGES/site.mo
_bindtextdomain($textdomain, LANGUAGE_DIR);

// Indicates in what encoding the file should be read
_bind_textdomain_codeset($textdomain, 'UTF-8');

// Here we indicate the default domain the _gettext() calls will respond to
_textdomain($textdomain);

/** Other Functions **/
