<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

abstract class Modules {
    
    /*
     * Varible for holding the application for use within each system module
     */
    protected $app = null;
    
    public function __construct() {
        $this->app = \Factory::getApplication();
    }
    
}