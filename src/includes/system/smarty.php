<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

/*
// Set Path for SMARTY in the PHP include path (Not needed because it is loaded by composer)
set_include_path(get_include_path() . PATH_SEPARATOR . LIBRARIES_DIR.'smarty/');

// Load Dependency Manually (Not needed because it is loaded by Composer)
require_once('Smarty.class.php');
 */

class QSmarty extends Smarty {
    
    static $instance = null;

    public static function getInstance($newInstance = null)
    {
        if(!is_null($newInstance)) {
            self::$instance = $newInstance;    
        }
        if(is_null(self::$instance)) {
            self::$instance = new QSmarty();        
        }
        return self::$instance;
    }

    public function __construct()
    { 

        $config = QFactory::getConfig();

        // Initialize Smarty 
        parent::__construct();    

        /* Configure Smarty */

        // Smarty Class Variables - https://www.smarty.net/docs/en/api.variables.tpl

        $this->template_dir           = THEME_TEMPLATE_DIR;
        $this->cache_dir              = SMARTY_CACHE_DIR;
        $this->compile_dir            = SMARTY_COMPILE_DIR;
        $this->force_compile          = $config->get('smarty_force_compile');

        // Enable caching
        if($config->get('smarty_caching') == '1') { $this->caching = Smarty::CACHING_LIFETIME_CURRENT;}
        if($config->get('smarty_caching') == '2') { $this->caching = Smarty::CACHING_LIFETIME_SAVED;}

        // Other Caching settings
        $this->force_cache            = $config->get('smarty_force_cache');
        $this->cache_lifetime         = $config->get('smarty_cache_lifetime');
        $this->cache_modified_check   = $config->get('smarty_cache_modified_check');
        $this->cache_locking          = $config->get('smarty_cache_locking');

        // Debugging    
        $this->debugging_ctrl         = $config->get('smarty_debugging_ctrl');
        //$this->debugging            = $config->get('smarty_debugging');                                     // Does not work with fetch()
        //$this->debugging_ctrl       = ($_SERVER['SERVER_NAME'] == 'localhost') ? 'URL' : 'NONE';      // Restrict debugging URL to work only on localhost
        //$this->debug_tpl            = LIBRARIES_DIR.'smarty/debug.tpl';                               // By default it is in the Smarty directory

        // Other Settings
        //$this->load_filter('output','trimwhitespace');  // removes all whitespace from output. useful to get smaller page payloads
        //$this->error_unassigned = true;                 // to enable notices.
        //$this->error_reporting = E_ALL | E_STRICT;      // Uses standard PHP error levels.
        //$this->compileAllTemplates();                   // this is a really cool feature and useful for translations
        //$this->clearAllCache();                         // clears all of the cache
        //$this->clear_cache()();                         // clear individual cache files (or groups)
        //$this->clearCompiledTemplate();                 // Clears the compile dirctory
    }
  
} 

// Create Smarty
$smarty = QSmarty::getInstance();