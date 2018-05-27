<!-- error.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<div class="">    
    <span style="color: black">{t}Error Page{/t}: </span>{$error_component}:{$error_page_tpl}<br />
    <span style="color: black">{t}Error Type{/t}: </span>{$error_type}<br /><br />
    
    <span style="color: black">{t}Error Location{/t}: </span>{$error_location}<br />    
    <span style="color: black">{t}PHP Function{/t}: </span>{$error_php_function}<br /><br />      
   
    <span style="color: black">{t}Database Error{/t}: </span>{$error_database}<br />
    <span style="color: black">{t}SQL Query{/t}: </span><br />{$error_sql_query}<br /><br />
    
    <span style="color: black">{t}Error Message{/t}: </span>{$error_msg}<br /><br /> 
</div>