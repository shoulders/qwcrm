<!-- note_edit.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<link rel="stylesheet" href="{$theme_js_dir}jscal2/css/jscal2.css" />
<link rel="stylesheet" href="{$theme_js_dir}jscal2/css/steel/steel.css" />
<script src="{$theme_js_dir}jscal2/jscal2.js"></script>
<script src="{$theme_js_dir}jscal2/unicode-letter.js"></script>
<script>{include file="`$theme_js_dir_finc`jscal2/language.js"}</script>
<script src="{$theme_js_dir}tinymce/tinymce.min.js"></script>
<script>{include file="`$theme_js_dir_finc`editor-config.js"}</script>

<table width="700" border="0" cellpadding="20" cellspacing="0">
    <tr>
        <td>
            <table width="700" cellpadding="5" cellspacing="0" border="0" >
                <tr>                    
                    <td class="menuhead2" width="80%">{t}Work Order Edit Note{/t}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}WORKORDER_NOTE_EDIT_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}WORKORDER_NOTE_EDIT_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
                        </a>
                    </td>
                </tr>
                <tr>
                    <td class="menutd2" colspan="2">                        
                        <table width="100%" class="olotable" cellpadding="5" cellspacing="0" border="0">
                            <tr>
                                <td width="100%" valign="top">                                    
                                    <form method="post" action="index.php?component=workorder&page_tpl=note_edit&workorder_note_id={$workorder_note_details.workorder_note_id}">
                                        <p><b>{t}Edit Workorder Note{/t}</b></p>                                        
                                        {*<div>
                                            <b>{t}Date{/t}:<b><br>
                                            <input id="date" name="date" class="olotd4" size="10" value="{$workorder_note_details.date|date_format:$date_format}" type="text" maxlength="10" pattern="{literal}^[0-9]{1,2}(\/|-)[0-9]{1,2}(\/|-)[0-9]{2,2}([0-9]{2,2})?${/literal}" required onkeydown="return onlyDate(event);">
                                            <input id="date_button" value="+" type="button">                                                    
                                            <script>                                            
                                                Calendar.setup( {
                                                    trigger     : "date_button",
                                                    inputField  : "date",
                                                    dateFormat  : "{$date_format}"                                                                                            
                                                } );                                            
                                            </script>                                                    
                                        </div>*}
                                        <p><b>{t}Note{/t}:</b></p>
                                        <textarea class="olotd4" rows="15" cols="70" name="note">{$workorder_note_details.description}</textarea>
                                        <br>                                        
                                        <input type="hidden" name="workorder_id" value="{$workorder_note_details.workorder_id}">
                                        <button type="submit" name="submit" value="submit">{t}Submit{/t}</button>
                                        <input class="olotd4" value="{t}Cancel{/t}" onclick="window.location.href='index.php?component=workorder&page_tpl=details&workorder_id={$workorder_note_details.workorder_id}';" type="button">
                                    </form>
                                    <br>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>