<!-- details_edit_description.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<script src="{$theme_js_dir}tinymce/tinymce.min.js"></script>
<script>{include file="`$theme_js_dir_finc`editor-config.js"}</script>
<script>{include file="`$theme_js_dir_finc`modules/workorder.js"}</script>

<table width="100%" border="0" cellpadding="20" cellspacing="0">
    <tr>
        <td>
            <table width="700" cellpadding="5" cellspacing="0" border="0" >
                <tr>                    
                    <td class="menuhead2" width="80%">{t}Edit Work Order Description{/t}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}WORKORDER_DETAILS_EDIT_DESCRIPTION_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}WORKORDER_DETAILS_EDIT_DESCRIPTION_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
                        </a>
                    </td>
                </tr>                
                <tr>
                    <td class="menutd2" colspan="2">                        
                        <table width="100%" class="olotable" cellpadding="5" cellspacing="0" border="0">
                            <tr>
                                <td width="100%" valign="top">                                                                      
                                    <form method="post" action="index.php?component=workorder&page_tpl=details_edit_description">                                    
                                        <b>{t}Scope{/t}</b></br>
                                        <input id="scope" name="scope" value="{$scope}"size="40" type="text" maxlength="80" required onkeydown="return onlyAlphaNumericPunctuation(event);" onkeyup="lookupSuggestions(this.value);" onblur="closeSuggestions();">
                                        <div class="suggestionsBoxWrapper">
                                            <div class="suggestionsBox" id="suggestions">
                                                <img src="{$theme_images_dir}upArrow.png" style="position: relative; top: -12px; left: 1px;" alt="upArrow" />
                                                <div class="suggestionList" id="autoSuggestionsList">&nbsp;</div>
                                            </div>
                                        </div>
                                        <br>
                                        <br>
                                        <br>
                                        <b>{t}Description{/t}</b><br>
                                        <textarea name="description" class="olotd4 mceCheckForContent" rows="15" cols="70">{$description}</textarea>
                                        <br>
                                        <input type="hidden" name="workorder_id" value="{$workorder_id}">
                                        <button type="submit" name="submit" value="submit">{t}Submit{/t}</button>
                                        <button type="button" class="olotd4" onclick="window.location.href='index.php?component=workorder&page_tpl=details&workorder_id={$workorder_id}';">{t}Cancel{/t}</button>
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