<!-- edit.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<script src="{$theme_js_dir}tinymce/tinymce.min.js"></script>
<script>{include file="`$theme_js_dir_finc`editor-config.js"}</script>
<link rel="stylesheet" href="{$theme_js_dir}jscal2/css/jscal2.css" />
<link rel="stylesheet" href="{$theme_js_dir}jscal2/css/steel/steel.css" />
<script src="{$theme_js_dir}jscal2/jscal2.js"></script>
<script src="{$theme_js_dir}jscal2/unicode-letter.js"></script>
<script>{include file="`$theme_js_dir_finc`jscal2/language.js"}</script>

<table width="100%" border="0" cellpadding="20" cellspacing="5">
    <tr>
        <td>
            <table width="700" cellpadding="4" cellspacing="0" border="0">
                 <tr>
                    <td class="menuhead2" width="80%">&nbsp;{t}Edit Schedule{/t}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">                        
                        <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}SCHEDULE_EDIT_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}SCHEDULE_EDIT_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
                    </td>
                </tr>                
                <tr>
                    <td class="menutd2" colspan="2">
                        <table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
                            <tr>
                                <td class="menutd">                                 
                                    <table class="menutable" width="100%" border="0" cellpadding="5" cellspacing="0">
                                        <tr>
                                            <td>                                                
                                                <form method="post" action="index.php?component=schedule&page_tpl=edit&schedule_id={$schedule_id}">                                                                                                       
                                                    <table class="olotable" width="100%" border="0">
                                                        <tr>
                                                            <td class="olotd">
                                                                <table width="100%" cellpadding="5" cellspacing="5">
                                                                    <tr>                                                                        
                                                                        <td>
                                                                            <p><b>{t}Assigned Employee{/t}</b></p>
                                                                            {if $login_usergroup_id <= 3 }                                                                                
                                                                                <select name="employee_id">
                                                                                    {section name=i loop=$active_employees}
                                                                                        <option value="{$active_employees[i].user_id}" {if $employee_id == $active_employees[i].user_id} selected {/if}>{$active_employees[i].display_name}</option>
                                                                                    {/section}
                                                                                </select>                                                                                
                                                                            {else}
                                                                                <input type="hidden" name="employee_id" value="{$employee_id}">
                                                                            {/if}
                                                                        </td>
                                                                        <td></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>{t}Start Time{/t}</b></td>
                                                                        <td><b>{t}End Time{/t}</b></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>
                                                                            <input id="start_date" name="start_date" size="10" value="{$start_date}" type="text" maxlength="10" pattern="{literal}^[0-9]{1,2}(\/|-)[0-9]{1,2}(\/|-)[0-9]{2,2}([0-9]{2,2})?${/literal}" required onkeydown="return onlyDate(event);">
                                                                            <input id="start_date_button" value="+" type="button">                                                                                
                                                                            <script>                                                                            
                                                                                Calendar.setup( {
                                                                                    trigger     : "start_date_button",
                                                                                    inputField  : "start_date",
                                                                                    dateFormat  : "{$date_format}"
                                                                                } );                                                                            
                                                                            </script>                                                                            
                                                                            {html_select_time use_24_hours=true minute_interval=15 display_seconds=false field_array=StartTime time=$start_time}
                                                                        </td>
                                                                        <td>
                                                                            <input id="end_date" name="end_date" size="10" value="{$end_date}" type="text" maxlength="10" pattern="{literal}^[0-9]{1,2}(\/|-)[0-9]{1,2}(\/|-)[0-9]{2,2}([0-9]{2,2})?${/literal}" required onkeydown="return onlyDate(event);">
                                                                            <input id="end_date_button" value="+" type="button">                                                                                
                                                                            <script>                                                                            
                                                                                Calendar.setup( {
                                                                                    trigger     : "end_date_button",
                                                                                    inputField  : "end_date",
                                                                                    dateFormat  : "{$date_format}"
                                                                                } );                                                                           
                                                                            </script>                                                                            
                                                                            {html_select_time use_24_hours=true minute_interval=15 display_seconds=false field_array=EndTime time=$end_time}
                                                                        </td>
                                                                    </tr>
                                                                    
                                                                    <tr>
                                                                        <td colspan="2">
                                                                            <b>{t}Notes{/t}</b>
                                                                            <br>
                                                                            <textarea name="notes" class="olotd5 mceCheckForContent" rows="15" cols="70">{$notes}</textarea>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="2">
                                                                            <input type="hidden" name="customer_id" value="{$customer_id}"> 
                                                                            <input type="hidden" name="workorder_id" value="{$workorder_id}"> 
                                                                            <input type="submit" name="submit" value="{t}Submit{/t}">
                                                                            <input class="olotd4" value="{t}Cancel{/t}" onclick="window.location.href='index.php?component=schedule&page_tpl=details&schedule_id={$schedule_id}';" type="button">
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </form>                                                
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>