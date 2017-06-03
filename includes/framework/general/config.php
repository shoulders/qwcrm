<?php

/*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

// joomla\administrator\components\com_config\model\application.php

// The config object loads the standard configuration and then also holds other settings for QWcrm
        

/**
 * Model for the global configuration
 */
class QConfig
{
    
    private $data;
    
    public function __construct() {
        
        //$this->conf = new GConfig;
        //return new GConfig;
    }
    
  
    /**
     * Method to get the configuration data.
     * @return    array  An array containg all config
     
    public function get($key = null)
    {       
        // Get the current configuration.
        
        $current_config = get_object_vars($this->conf);
        
        // return all data as an array
        if($key === null) {
            return $current_config;
        
        // return an individual value
        } else {
            return array_search($key, $current_config);            
        }
        
    }*/
    
        /**
     * Method to get the value from the data array
     *
     * @param   string  $key           Key to search for in the data array
     * @param   mixed   $defaultValue  Default value to return if the key is not set
     *
     * @return  mixed   Value from the data array | defaultValue if doesn't exist
     *
     * @since   3.5
     */
    public function get($key, $defaultValue = null)
    {
        return isset($this->data[$key]) ? $this->data[$key] : $defaultValue;
    }
    
    /**
     * Get all the data being rendered
     *
     * @return  array
     *
     * @since   3.5
     */
    public function getData()
    {
        return $this->data;
    }

  /**
     * Method to set a value in the data array. Example: $layout->set('items', $items);
     *
     * @param   string  $key    Key for the data array
     * @param   mixed   $value  Value to assign to the key
     *
     * @return  self
     *
     * @since   3.5
     */
    public function set($key, $value)
    {
        $this->data[(string) $key] = $value;

        return $this;
    }    
      
    
    

    /**
     * Method to save the configuration data.
     */
    public function save($new_config)
    {
        // Get a fresh copy of the standard settings as an array        
        $current_config = get_object_vars(new QConfig);        

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


