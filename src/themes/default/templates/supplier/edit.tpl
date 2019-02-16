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

<table width="100%" border="0" cellpadding="20" cellspacing="0">
    <tr>
        <td>
            <table width="700" cellpadding="5" cellspacing="0" border="0" >
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{t}Supplier Edit{/t}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}SUPPLIER_EDIT_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}SUPPLIER_EDIT_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
                        </a>
                    </td>
                </tr>
                <tr>
                    <td class="menutd2" colspan="2">
                        <table width="100%" class="olotable" cellpadding="5" cellspacing="0" border="0" >
                            <tr>
                                <td width="100%" valign="top" >
                                    <table class="menutable" width="100%" border="0" cellpadding="0" cellspacing="0" >
                                        <tr>
                                            <td>                                                
                                                <form action="index.php?component=supplier&page_tpl=edit&supplier_id={$supplier_id}" method="post" name="edit_supplier" id="edit_supplier" autocomplete="off">                                                
                                                    <table width="100%" cellpadding="2" cellspacing="2" border="0">
                                                        <tr>
                                                            <td class="menuhead" colspan="2">{t}Contact{/t}</td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><b>{t}Supplier ID{/t}</b></td>
                                                            <td>{$supplier_id}</td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><b>{t}Company Name{/t}</b></td>
                                                            <td><input name="company_name" class="olotd5" size="50" value="{$supplier_details.company_name}" type="text" maxlength="50" onkeydown="return onlyName(event);"></td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><strong>{t}Contact First Name{/t}</strong><span style="color: #ff0000"> *</span></td>
                                                            <td><input name="first_name" class="olotd5" size="20" value="{$supplier_details.first_name}" type="text" maxlength="20" required onkeydown="return onlyName(event);"/></td>
                                                        <tr>
                                                            <td align="right"><strong>{t}Conact Last Name{/t}</strong><span style="color: #ff0000"> *</span></td>
                                                            <td><input name="last_name" class="olotd5" size="20" value="{$supplier_details.last_name}" type="text" maxlength="20" required onkeydown="return onlyName(event);"/></td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><b>{t}Website{/t}</b></td>
                                                            <td><input name="website" class="olotd5" size="50" value="{$supplier_details.website}" type="text"  maxlength="50" pattern="{literal}^(https?:\/\/)?([a-z0-9]+\.)*([a-z0-9]+\.[a-z]+)(\/[a-zA-Z0-9#]+\/?)*${/literal}" placeholder="https://quantumwarp.com/" onkeydown="return onlyURL(event);"></td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><b>{t}Email{/t}</b></td>
                                                            <td><input name="email" class="olotd5" size="50" value="{$supplier_details.email}" type="email" placeholder="no-reply@quantumwarp.com" maxlength="50" onkeydown="return onlyEmail(event);"></td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><b>{t}Type{/t}</b><span style="color: #ff0000"> *</span></td>
                                                            <td>
                                                                <select id="type" name="type" class="olotd5">               
                                                                    {section name=s loop=$supplier_types}
                                                                        <option value="{$supplier_types[s].type_key}"{if $supplier_details.type == $supplier_types[s].type_key} selected{/if}>{t}{$supplier_types[s].display_name}{/t}</option>
                                                                    {/section}
                                                                </select>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="menuhead" colspan="2">{t}Phone{/t}</td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><b>{t}Primary{/t}</b></td>
                                                            <td><input class="olotd5" name="primary_phone" size="20" value="{$supplier_details.primary_phone}" type="tel" maxlength="20" onkeydown="return onlyPhoneNumber(event);"></td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><b>{t}Mobile{/t}</b></td>
                                                            <td><input class="olotd5" name="mobile_phone" type="tel" size="20" value="{$supplier_details.mobile_phone}" maxlength="20" onkeydown="return onlyPhoneNumber(event);"></td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><b>{t}Fax{/t}</b></td>
                                                            <td><input name="fax" class="olotd5" size="20" value="{$supplier_details.fax}" type="tel" maxlength="20" onkeydown="return onlyPhoneNumber(event);"></td>
                                                        </tr>                                                        
                                                        <tr>
                                                            <td class="menuhead" colspan="2">{t}Address{/t}</td>
                                                        </tr>                                                        
                                                        <tr>
                                                            <td align="right"><strong>{t}Address{/t}</strong></td>
                                                            <td><textarea name="address" class="olotd5 mceNoEditor" cols="30" rows="3" maxlength="100" onkeydown="return onlyAddress(event);">{$supplier_details.address}</textarea></td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><strong>{t}City{/t}</strong></td>
                                                            <td><input name="city" class="olotd5" value="{$supplier_details.city}" type="text" maxlength="20" onkeydown="return onlyAlpha(event);"></td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><strong>{t}State{/t}</strong></td>
                                                            <td><input name="state" class="olotd5" value="{$supplier_details.state}" type="text" maxlength="20" onkeydown="return onlyAlpha(event);"></td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><strong>{t}Zip{/t}</strong></td>
                                                            <td colspan="2"><input name="zip" class="olotd5" value="{$supplier_details.zip}" type="text" maxlength="20" onkeydown="return onlyAlphaNumeric(event);"></td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><strong>{t}Country{/t}</strong></td>
                                                            <td><input name="country" class="olotd5" value="{$supplier_details.country}" type="text" maxlength="50" onkeydown="return onlyAlpha(event);"></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="menuhead" colspan="2"><b>{t}Description{/t}</b><span style="color: #ff0000"> *</span></td>
                                                        </tr> 
                                                        <tr>
                                                            <td align="right"></td>
                                                            <td><textarea class="olotd5 mceCheckForContent" name="description" cols="50" rows="20">{$supplier_details.description}</textarea></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="menuhead" colspan="2"><b>{t}Note{/t}</b></td>
                                                        </tr> 
                                                        <tr>
                                                            <td align="right"></td>
                                                            <td><textarea name="note" class="olotd5" cols="50" rows="20">{$supplier_details.note}</textarea></td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2">
                                                                <button type="submit" name="submit" value="update">{t}Update{/t}</button>
                                                                <button type="button" class="olotd4" onclick="window.location.href='index.php?component=supplier&page_tpl=details&supplier_id={$supplier_id}';">{t}Cancel{/t}</button>
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