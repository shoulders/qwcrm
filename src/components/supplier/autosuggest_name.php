<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

// Prevent direct Access
defined('_QWEXEC') or die;

// Is there a posted query string and is the string length greater than 0
if(isset(\CMSApplication::$VAR['supplier_autosuggest_name']) && strlen(\CMSApplication::$VAR['supplier_autosuggest_name']) > 0) {

    // BuildPage will only holds the html for the table, no template
    $pagePayload .= $this->app->components->supplier->getNameSuggestions(\CMSApplication::$VAR['supplier_autosuggest_name']);

}

// This page should be treated as RAW HTML
\CMSApplication::$VAR['themeVar'] = 'rawHtml';

// Skip page logging
if(!defined('SKIP_LOGGING')) {
    define('SKIP_LOGGING', true);
}
