<?php

/*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

class QUser {
    
    // This holds the account data for the requested user
    private $data;
    
    public function __construct($user_id = null) {
        
        //if(!$user_id) { loadUserData($user_id); }

        
    }
    
    // load the user data from the database into the user object - similiar to get_employee_details()        
    private function loadUserData($user_id) {

        $sql = "SELECT * FROM ".PRFX."EMPLOYEE WHERE EMPLOYEE_ID =".$user_id;

        if(!$rs = $this->db->execute($sql)){        
            force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $this->db->ErrorMsg(), $sql, $this->smarty->getTemplateVars('translate_system_include_error_message_function_'.__FUNCTION__.'_failed'));
            exit;
        } else {

            $this->data = $rs->GetRowAssoc();
            return;

        }

    }
        
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
     * Get the data being rendered
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
     * Gets a user state.
     *
     * @param   string  $key      The path of the state.
     * @param   mixed   $default  Optional default value, returned if the internal value is null.
     *
     * @return  mixed  The user state or null.
     *
     * @since   3.2
     */
    public function getUserState($key, $default = null)
    {
        $session = JFactory::getSession();
        $registry = $session->get('registry');

        if (!is_null($registry))
        {
            return $registry->get($key, $default);
        }

        return $default;
    }

    /**
     * Gets the value of a user state variable.
     *
     * @param   string  $key      The key of the user state variable.
     * @param   string  $request  The name of the variable passed in a request.
     * @param   string  $default  The default value for the variable if not found. Optional.
     * @param   string  $type     Filter for the variable, for valid values see {@link JFilterInput::clean()}. Optional.
     *
     * @return  mixed  The request user state.
     *
     * @since   3.2
     */
    public function getUserStateFromRequest($key, $request, $default = null, $type = 'none')
    {
        $cur_state = $this->getUserState($key, $default);
        $new_state = $this->input->get($request, null, $type);

        if ($new_state === null)
        {
            return $cur_state;
        }

        // Save the new value only if it was set in this request.
        $this->setUserState($key, $new_state);

        return $new_state;
    }
 

    /**
     * Sets the value of a user state variable.
     *
     * @param   string  $key    The path of the state.
     * @param   mixed   $value  The value of the variable.
     *
     * @return  mixed  The previous state, if one existed.
     *
     * @since   3.2
     */
    public function setUserState($key, $value)
    {
        $session = JFactory::getSession();
        $registry = $session->get('registry');

        if (!is_null($registry))
        {
            return $registry->set($key, $value);
        }

        return;
    }
    
  
}
