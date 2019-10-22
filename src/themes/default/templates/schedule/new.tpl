<!-- new.tpl -->
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
                    <td class="menuhead2" width="80%">&nbsp;{t}New Schedule{/t}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">                        
                        <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}SCHEDULE_NEW_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}SCHEDULE_NEW_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
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
                                                <form method="post" action="index.php?component=schedule&page_tpl=new&employee_id={$employee_id}&workorder_id={$workorder_id}">                                                    
                                                    <table class="olotable" width="100%" border="0">
                                                        <tr>
                                                            <td class="olohead">{t}Set Schedule{/t}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="olotd">
                                                                <table width="100%" cellpadding="5" cellspacing="5">                                          
                                                                    <tr>
                                                                        <td><b>{t}Start Time{/t}</b></td>
                                                                        <td><b>{t}End Time{/t}</b></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>
                                                                            <input id="start_date" name="qform[start_date]" size="10" value="{$schedule_details.start_date|date_format:$date_format}" type="text" maxlength="10" pattern="{literal}^[0-9]{2,4}(?:\/|-)[0-9]{2}(?:\/|-)[0-9]{2,4}${/literal}" required readonly onkeydown="return onlyDate(event);">
                                                                            <button type="button" id="start_date_button">+</button>
                                                                            <script>                                                                            
                                                                                Calendar.setup( {
                                                                                    trigger     : "start_date_button",
                                                                                    inputField  : "start_date",
                                                                                    dateFormat  : "{$date_format}"                                                                                        
                                                                                } );                                                                            
                                                                            </script>                                                                            
                                                                            {html_select_time use_24_hours=true minute_interval=15 display_seconds=false field_array=StartTime time=$schedule_details.start_time}
                                                                        </td>
                                                                        <td>
                                                                            <input id="end_date" name="qform[end_date]" size="10" value="{$schedule_details.end_date|date_format:$date_format}" type="text" maxlength="10" pattern="{literal}^[0-9]{2,4}(?:\/|-)[0-9]{2}(?:\/|-)[0-9]{2,4}${/literal}" required readonly onkeydown="return onlyDate(event);">
                                                                            <button type="button" id="end_date_button">+</button>                                                                            
                                                                            <script>                                                                            
                                                                                Calendar.setup( {
                                                                                    trigger     : "end_date_button",
                                                                                    inputField  : "end_date",
                                                                                    dateFormat  : "{$date_format}"
                                                                                } );                                                                            
                                                                            </script>                                                                            
                                                                            {html_select_time use_24_hours=true minute_interval=15 display_seconds=false field_array=EndTime time=$schedule_details.end_time}
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="2">
                                                                            <b>{t}Note{/t}</b>
                                                                            <br>
                                                                            <textarea name="qform[note]" class="olotd5 mceCheckForContent" rows="15" cols="70">{$schedule_details.note}</textarea>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="2">
                                                                            <input type="hidden" name="qform[client_id]" value="{$schedule_details.client_id}">
                                                                            <input type="hidden" name="qform[workorder_id]" value="{$schedule_details.workorder_id}">
                                                                            <input type="hidden" name="qform[employee_id]" value="{$schedule_details.employee_id}">
                                                                            <button type="submit" name="submit" value="submit">{t}Submit{/t}</button>
                                                                            <button type="button" class="olotd4" onclick="window.location.href='index.php?component=workorder&page_tpl=details&workorder_id={$workorder_id}';">{t}Cancel{/t}</button>
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