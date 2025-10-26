<!-- new.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<script src="{$theme_js_dir}tinymce/tinymce.min.js"></script>
<script>{include file="`$theme_js_dir_finc`editor-config.js"}</script>
<script>{include file="`$theme_js_dir_finc`components/workorder_autosuggest.js"}</script>

<table width="100%">
    <tr>
        <td>
            <table width="700" cellpadding="5" cellspacing="0" border="0">
                <tr>
                    <td class="menuhead2" width="80%">{t}New Work Order for{/t} {$client_display_name}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}WORKORDER_NEW_HELP_TITLE{/t}</strong></div><hr><div>{t escape=js}WORKORDER_NEW_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
                        </a>
                    </td>
                </tr>
                <tr>
                    <td class="menutd2" colspan="2">
                        <table width="100%" class="olotable" cellpadding="5" cellspacing="0" border="0" >
                            <tr>
                                <td width="100%" valign="top">
                                    <table width="100%" class="olotable" cellpadding="5" cellspacing="0" border="0">
                                        <tr>
                                            <td valign="top">
                                                <form method="post" action="index.php?component=workorder&page_tpl=new" name="new_workorder" id="new_workorder">

                                                    <!-- Header -->
                                                    <table class="olotable" width="100%" border="0"  cellpadding="4" cellspacing="0" summary="Work order display">
                                                        <tr>
                                                            <td class="olohead">{t}Opened{/t}</td>
                                                            <td class="olohead">{t}Client{/t}</td>
                                                            <td class="olohead">{t}Scope{/t}</td>
                                                            <td class="olohead">{t}Employee{/t}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="olotd4">{$smarty.now|date_format:$date_format}</td>
                                                            <td class="olotd4">{$client_display_name}</td>
                                                            <td class="olotd4">
                                                                <input id="scope" name="scope" size="40" type="text" maxlength="80" required onkeydown="return onlyAlphaNumericPunctuation(event);" onkeyup="debounceWorkorderAutosuggestScopeLookup(this.value);" onblur="workorderAutosuggestScopeClose();">
                                                                <div class="suggestionsBoxWrapper">
                                                                    <div id="workorderAutosuggestScope" class="suggestionsBox">
                                                                        <img src="{$theme_images_dir}upArrow.png" style="position: relative; top: -12px; left: 1px;" alt="upArrow" />
                                                                        <div id="workorderAutosuggestScopeList" class="suggestionList">&nbsp;</div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td class="olotd4">{$login_display_name}</td>
                                                        </tr>
                                                    </table>
                                                    <br>

                                                    <!-- Description -->
                                                    <table class="olotable" width="100%" border="0">
                                                        <tr>
                                                            <td class="olohead">&nbsp;{t}Description{/t}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="olotd">
                                                                <textarea class="olotd4 mceCheckForContent" rows="15" cols="70" name="description"></textarea>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <br>

                                                    <!-- Comment -->
                                                    <table class="olotable" width="100%" border="0">
                                                        <tr>
                                                            <td class="olohead">&nbsp;{t}Comment{/t}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="olotd"><textarea class="olotd4" rows="15" cols="70" name="comment"></textarea></td>
                                                        </tr>
                                                    </table>
                                                    <br>

                                                    <!-- Submit Button -->
                                                    <table width="100%" border="0">
                                                        <tr>
                                                            <td>
                                                                <input name="assign_to_employee" type="checkbox" value="1" checked>{t}Assign to the current employee{/t} ({$login_display_name}).
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <input name="client_id" value="{$client_id}" type="hidden">
                                                                <button type="submit" name="submit" value="submit">{t}Submit{/t}</button>
                                                                <button type="button" class="olotd4" onclick="window.location.href='index.php?component=workorder&page_tpl=search';">{t}Cancel{/t}</button>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <br>

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
