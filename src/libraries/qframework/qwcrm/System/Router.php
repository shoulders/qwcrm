<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

class Router extends System {

    /* Mandatory */

    ############################################
    #  Build path to relevant Page Controller  # // $mode = set_route/get_route
    ############################################

    function page_controller($mode = null, $component = null, $page_tpl = null, $themeVar = null) {        
        
        // Set routing variables locally for analysis, either manually supplied or from the system
        $component = isset($component) ? $component : ( isset(\CMSApplication::$VAR['component']) ? \CMSApplication::$VAR['component'] : null);
        $page_tpl = isset($page_tpl) ? $page_tpl : ( isset(\CMSApplication::$VAR['page_tpl']) ? \CMSApplication::$VAR['page_tpl'] : null);

        // Setup is in progress (install/migrate/upgrade), skip validations (because no database access etc..)
        if(
            defined('QWCRM_SETUP') &&
            isset($component, $page_tpl) &&
            $component == 'setup' &&
            ($page_tpl == 'choice' || $page_tpl == 'install' || $page_tpl == 'migrate' || $page_tpl == 'upgrade')
        )
        {
            goto page_controller_return;
        }

        // Maintenance Mode
        if($this->app->config->get('maintenance')) {

            // Set to the maintenance page    
            $component   = 'core';
            $page_tpl    = 'maintenance';        
            $themeVar     = 'off';   

            goto page_controller_acl_check;

        }    

        // Check if URL is valid
        if(!$this->check_link_is_valid($_SERVER['REQUEST_URI'])) {

            // Set the error page    
            $component   = 'core';
            $page_tpl    = '404';        
            $themeVar     = 'off'; 

            goto page_controller_acl_check;

        }

        // If SEF routing is enabled parse the link and set the controller (not if returning the content only)
        // This allows the use of Non-SEF URLS in the SEF enviroment
        if ($this->app->config->get('sef') && $this->check_link_is_sef($_SERVER['REQUEST_URI']) && $mode != 'get_payload') {

            // Set 'component' and 'page_tpl' variables in \CMSApplication::$VAR for correct routing when using SEF
            $this->parse_sef_url($_SERVER['REQUEST_URI'], 'basic', 'set_var');
            
            // Re-Grab the routing components
            $component = \CMSApplication::$VAR['component'];
            $page_tpl = \CMSApplication::$VAR['page_tpl'];

        }
        
        // Check to see if the page exists otherwise send to the 404 page
        if (isset($component, $page_tpl) && !$this->check_page_exists($component, $page_tpl)) {

            // Set to the 404 error page       
            $component   = 'core';
            $page_tpl    = '404';            
            $themeVar     = 'off';

            goto page_controller_acl_check;

        }  

        // If no page specified, set page based on login status
        if(!isset($component) && !isset($page_tpl) ) {    

            if(isset($this->app->user->login_token)) {

                // If logged in
                $component           = 'core';
                $page_tpl            = 'dashboard';

            } else {

                // If NOT logged in
                $component           = 'core';
                $page_tpl            = 'home';

            }

        }    

        page_controller_acl_check:    

        // Check the requested page with the current usergroup against the ACL for authorisation, if it fails set page 403
        if(!$this->check_page_acl($component, $page_tpl)) {

            // Log activity
            $record = _gettext("A user tried to access the following resource without the correct permissions.").' ('.$component.':'.$page_tpl.')';
            $this->app->system->general->write_record_to_activity_log($record); 

            // Set to the 403 error page 
            $component   = 'core';
            $page_tpl    = '403';        
            $themeVar     = 'off';

        }

        page_controller_return:

        // Set the routing variables to the system unless only payload to be returned
        if($mode != 'get_payload') {
            if(isset($component)) {\CMSApplication::$VAR['component'] = $component;}
            if(isset($page_tpl)) {\CMSApplication::$VAR['page_tpl'] = $page_tpl;}
            if(isset($themeVar)) {\CMSApplication::$VAR['themeVar'] = $themeVar;}
        }

        // Return the page display controller for the requested page
        return COMPONENTS_DIR.$component.'/'.$page_tpl.'.php';

    }

    #####################################
    #  Build SEF URL from Non-SEF URL   #  // index.php?compnent=workorder&page_tpl=search, outputs /develop/qwcrm/workorder/search
    #####################################

    function build_sef_url($non_sef_url, $url_length = 'relative') {

        $sef_url_path = '';
        $sef_url_query = '';
        $sef_url_fragement = '';    

        // Convert URL into an array 
        $parsed_url = parse_url($non_sef_url);

        // Get URL Query Variables (if present    
        if(isset($parsed_url['query'])) {

            // Convert Query variables into an array
            parse_str($parsed_url['query'], $parsed_url_query);

            // Build URL 'Path' from query variables and then remove them as they are no longer needed
            if(isset($parsed_url_query['component'], $parsed_url_query['page_tpl']) && $parsed_url_query['component'] && $parsed_url_query['page_tpl']) { 
                $sef_url_path = $parsed_url_query['component'].'/'.$parsed_url_query['page_tpl'];
                unset($parsed_url_query['component']);
                unset($parsed_url_query['page_tpl']);    
            }

            // Build URL 'Query' (if variables present)
            if(!empty($parsed_url_query)) {

                foreach($parsed_url_query as $key => $value) {
                    $sef_url_query .= '&'.$key.'='.$value;
                }

                // Remove the first & and prepend a ?
                $sef_url_query = '?'.ltrim($sef_url_query, '&');

            }

        }

        // Build URL 'Fragement'
        if(isset($parsed_url['fragment'])) {
            $sef_url_fragement = '#'.$parsed_url['fragment'];        
        }

        // The Basic Slug
        $slug = $sef_url_path . $sef_url_query . $sef_url_fragement;

        // Full URL (https://quantumwarp.com/develop/qwcrm/user/login)
        if($url_length == 'absolute') {   
            $url = QWCRM_PROTOCOL . QWCRM_DOMAIN . QWCRM_BASE_PATH . $slug;

        // Relative URL (/develop/qwcrm/user/login)
        } elseif($url_length == 'relative') {
            $url = QWCRM_BASE_PATH . $slug;

        // Basic URL (user/login)
        } elseif($url_length == 'basic') {
            $url = $slug;
        }

        return $url;

    }

    #########################################################################################################################
    #  Convert a SEF url into a standard URL and (optionally) inject routing varibles into $VAR or return routing variables #  makes nonsef from sef
    #########################################################################################################################

    function parse_sef_url($sef_url, $url_length = 'basic', $mode = null) {    

        $nonsef_url_path_variables = '';
        $nonsef_url_query = '';
        $nonsef_url_fragment = '';    

        // Move URL into an array
        $parsed_url = parse_url($sef_url);

        // Remove base path from path
        if(QWCRM_BASE_PATH === '/')
        {
            // QWcrm is in the root
            $parsed_url['path'] = ltrim($parsed_url['path'], '/');
        } else {
            // QWcrm is in a sub-folder
            $parsed_url['path'] = str_replace(QWCRM_BASE_PATH, '', $parsed_url['path']);
        }
		
        // Get URL segments from path
        $url_segments = array_filter(explode('/', $parsed_url['path']));

        // Create a holding variable because page is index.php
        if($mode == 'get_var') { $onlyVar = array(); }

        // If there are routing variables
        if ($url_segments) {

            // Set $_GET routing variables        
            $nonsef_url_path_variables .= '?';
            $nonsef_url_path_variables .= 'component='.$url_segments['0'];
            $nonsef_url_path_variables .= '&page_tpl='.$url_segments['1'];       

            // Sets the following routing values for return statement
            if ($mode == 'get_var') {
                if($url_segments['0']) { $onlyVar['component'] = $url_segments['0']; }
                if($url_segments['1']) { $onlyVar['page_tpl'] = $url_segments['1']; }
            }

            // Sets the following routing values into \CMSApplication::$VAR for routing
            if ($mode == 'set_var') {
                if($url_segments['0']) { \CMSApplication::$VAR['component'] = $url_segments['0']; }
                if($url_segments['1']) { \CMSApplication::$VAR['page_tpl'] = $url_segments['1']; }
            }

        }

        // No further processing needed with 'only_get_var'
        if ($mode == 'get_var') { return $onlyVar; }

        // No further processing needed with 'only_set_var'
        if ($mode == 'set_var') { return; }    

        // Build URL 'Query' (if variables present)
        if(isset($parsed_url['query'])) {

            // Load Query variables into an array
            parse_str($parsed_url['query'], $parsed_url_query_variables);

            // Build URL 'Query' if variables present
            if (!empty($parsed_url_query_variables)) {
                foreach($parsed_url_query_varibles as $key => $value) {
                    $nonsef_url_query .= '&'.$key.'='.$value;
                }

                // Remove the first & and prepend a ?
                $nonsef_url_query = '?'.ltrim($nonsef_url_query, '&');            

            }

        }        

        // Build URL 'Fragement'
        if(isset($parsed_url['fragment'])) {
            $nonsef_url_fragment = '#'.$parsed_url['fragment'];        
        }    

        // The Basic Slug
        $slug = 'index.php' . $nonsef_url_path_variables . $nonsef_url_query . $nonsef_url_fragment;    

        // Full URL (https://quantumwarp.com/develop/qwcrm/index.php?component=user&page_tpl=login)
        if($url_length == 'absolute') {   
            $url = QWCRM_PROTOCOL . QWCRM_DOMAIN . QWCRM_BASE_PATH . $slug;

        // Relative URL (/develop/qwcrm/index.php?component=user&page_tpl=login) (Might not be needed)
        } elseif($url_length == 'relative') {
            $url = QWCRM_BASE_PATH . $slug;

        // Basic URL index.php?component=user&page_tpl=login)
        } elseif($url_length == 'basic') {
            $url = $slug;
        }

        return $url;

    }

    ###################################################
    #  Build URL from component and page_tpl          #
    ###################################################

    function build_url_from_variables($component, $page_tpl, $url_length = 'basic', $url_sef = 'auto') {

        // Set URL Type to return
        if(defined('QWCRM_SETUP')) { $sef = false; }
        elseif($url_sef == 'sef') { $sef = true; }
        elseif($url_sef == 'nonsef') { $sef = false; }
        else { $sef = $this->app->config->get('sef'); }    
        //else { $sef = $config->sef; } 

        // The Basic Slug
        $slug = 'index.php?component='.$component.'&page_tpl='.$page_tpl;

        // Build either SEF or nonSEF URL
        if ($sef) {
            $url = $this->build_sef_url($slug, $url_length);
        } else {

            // Full URL (https://quantumwarp.com/develop/qwcrm/index.php?component=user&page_tpl=login)
            if($url_length == 'absolute') {   
                $url = QWCRM_PROTOCOL . QWCRM_DOMAIN . QWCRM_BASE_PATH . $slug;

            // Relative URL (/develop/qwcrm/index.php?component=user&page_tpl=login) (Might not be needed)
            } elseif($url_length == 'relative') {
                $url = QWCRM_BASE_PATH . $slug;

            // Basic URL index.php?component=user&page_tpl=login)
            } elseif($url_length == 'basic') {
                $url = $slug;
            }

        }

        return $url;

    }

    #############################################
    #  Validate links and prep SEF environment  #
    #############################################

    function get_routing_variables_from_url($url) {

        // Check if URL is valid
        if(!$this->check_link_is_valid($_SERVER['REQUEST_URI'])) {

            return false;

        } else {    

            // Running parse_sef_url only when the link is a SEF allows the use of Non-SEF URLS aswell
            if ($this->check_link_is_sef($url)) {

                // Get 'component' and 'page_tpl' variables from SEF URL           
                $routingVariables = $this->parse_sef_url($url, 'basic', 'get_var');

            // non-sef url
            } else {

                // Get URL Query Variables
                parse_str(parse_url($url, PHP_URL_QUERY), $parsed_url_query);            

                // Set only routing variables if they exist
                if(isset($parsed_url_query['component'])) { $routingVariables['component'] = $parsed_url_query['component']; }
                if(isset($parsed_url_query['page_tpl'])) { $routingVariables['page_tpl'] = $parsed_url_query['page_tpl']; }

            }

            // If $VAR is empty it is because page is index.php, set required
            if(!isset($routingVariables['component']) && !isset($routingVariables['page_tpl'])) {

                if(isset($this->app->user->login_token)) {

                    // If logged in
                    $routingVariables['component']           = 'core';
                    $routingVariables['page_tpl']            = 'dashboard';

                } else {

                    // If NOT logged in
                    $routingVariables['component']           = 'core';
                    $routingVariables['page_tpl']            = 'home';

                }

            }   

        }

        return $routingVariables;

    }

    #################################################################
    #  Verify User's authorisation for a specific page / operation  #
    #################################################################

    function check_page_acl($component, $page_tpl, $user = null) {

        // Get the current user unless a user (object) has been passed
        if($user == null) { $user = $this->app->user; }

        // If installing
        if(defined('QWCRM_SETUP')) { return true; }

        // Usergroup Error catching - you cannot use normal error logging as it will cause a loop (should not be needed now)
        if($user->login_usergroup_id == '') {
            die(_gettext("The ACL has been supplied with no usergroup. QWcrm will now die."));                
        }

        // Get user's Group Name by login_usergroup_id
        $sql = "SELECT ".PRFX."user_usergroups.display_name
                FROM ".PRFX."user_usergroups
                WHERE usergroup_id =".$this->app->db->qstr($user->login_usergroup_id);

        if(!$rs = $this->app->db->execute($sql)) {        
            $this->app->system->page->force_error_page('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Could not get the user's Group Name by Login Account Type ID."));
        } else {
            $usergroup_display_name = $rs->fields['display_name'];
        } 

        // Build the page name for the ACL lookup
        $page_name = $component.':'.$page_tpl;

        /* Check Page to see if we have access */

        $sql = "SELECT ".$usergroup_display_name." AS acl FROM ".PRFX."user_acl_page WHERE page=".$this->app->db->qstr($page_name);

        if(!$rs = $this->app->db->execute($sql)) {        
            $this->app->system->page->force_error_page('authentication', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Could not get the Page's ACL."));
        } else {

            $acl = $rs->fields['acl'];

            // Add if guest (8) rules here if there are errors

            if($acl != 1) {

                return false;

            } else {

                return true;

            }

        }

    }

    #######################
    #  Check page exists  #
    #######################

    function check_page_exists($component = null, $page_tpl = null) {

        // If a valid page has not been submitted
        if($component == null || $page_tpl == null) { return false; }

        // Check the controller file exists
        if (!file_exists(COMPONENTS_DIR.$component.'/'.$page_tpl.'.php')) { return false;  }

        // Check to see if the page exists in the ACL
        $sql = "SELECT page FROM ".PRFX."user_acl_page WHERE page = ".$this->app->db->qstr($component.':'.$page_tpl);

        if(!$rs = $this->app->db->Execute($sql)) {
            $this->app->system->page->force_error_page('database', __FILE__, __FUNCTION__, $this->app->db->ErrorMsg(), $sql, _gettext("Failed to check if the page exists in the ACL."));
        } else {

            if($rs->RecordCount() == 1) {

                return true;

            } else {

                return false;

            }

        }

    }

    #######################################
    #  Check to see if the link is valid  #
    #######################################

    function check_link_is_valid($url) {    

        // Get URL path
        $url = parse_url($url, PHP_URL_PATH);

        // Remove base path from URL path
        $url = str_replace(QWCRM_BASE_PATH, '', $url);

        // index.php can only be in the root, anywhere else is bad
        if (preg_match('|index\.php|U', $url) && !preg_match('|^index\.php|U', $url)) {

            // is not valid
            return false;

        }

        // is valid
        return true;     

    }

    #####################################
    #  Check to see if the link is SEF  #
    #####################################

    function check_link_is_sef($url) {

        // Get URL path
        $url = parse_url($url, PHP_URL_PATH);

        // Remove base path from URL path
        $url = str_replace(QWCRM_BASE_PATH, '', $url);

        // If start with index.php == Non SEF
        if (preg_match('|^index\.php|U', $url)) {
            return false;        
        }

        // if root '/' - This can be either SEF or Non SEF so return Non SEF
        if($url == '') {        
            return false;
        }

        // Is SEF
        return true;       

    }

    /** Other Functions **/

}