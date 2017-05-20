<?php

/*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

// D:\websites\htdocs\quantumwarp.com\administrator\components\com_config\model\application.php
        

/**
 * Model for the global configuration
 */
class Config
{
    
    private $config;
    
    public function __construct() {
        $this->config = new QConfig;
    }
    
    /**
     * Method to set a registry setting - this is a hangover from joomla Create a temp registry
     */    
    public function set($key) {
        return;
    }
   
    /**
     * Method to get the configuration data.
     * @return    array  An array containg all config
     */
    public function get($key = null)
    {       
        // Get the current configuration.
        
        $current_config = get_object_vars($this->config);
        
        // return all values as an array
        if($key === null) {
            return $current_config;
        
        // return an individual value
        } else {
            return array_search($key, $current_config);            
        }
        
    }

    /**
     * Method to save the configuration data.
     */
    public function save($new_config)
    {
        // Get the current configuration as an array        
        $current_config = get_object_vars($current_config);        

        // Merge the new submitted config and the old one. We do this to preserve values that were not in the submitted form but are in the config.
        $config_data = array_merge($current_config, $new_config);
        
        // Prepare the data
        $config_data = prepareConfigFileData($config_data);
        
        // Write the configuration file.
        $this->writeConfigFile($config_data);
        
        return;
        
    }
    
    /**
     * Prepare the Config file data layout
     */
    private function prepareConfigFileData($config_data)
    {
        $output = "<?php\n";
        $output .= "class QConfig {\n";

        foreach ($config_data as $key => $value)
        {
            $output .= "    public $key = '$value';\n";
        }

       $output .= "}";
       
       return $output;
    }
    
    /**
     * Write data to config file
     */
    private function writeConfigFile($config_data)
    {
        // Set the configuration file path.
        $file = 'configuration.php';

        // Check file is writable
        chmod($file, '0644');

        // Write file
        $fp = fopen($file, 'w');
        fwrite($fp, $config_data);
        fclose($fp);

        // Make file 444
        chmod($file, '0444');      

        return true;
    }    

}


