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

<table width="100%"   border="0" cellpadding="20" cellspacing="5">
    <tr>
        <td>            
            <table width="700" cellpadding="5" cellspacing="0" border="0" >
                <tr>
                    <td class="menuhead2" width="80%">{t}New Supplier{/t}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}SUPPLIER_NEW_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}SUPPLIER_NEW_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
                        </a>
                    </td>
                </tr>
                <tr>
                    <td class="menutd2" colspan="2">                         
                        <table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
                            <tr>
                                <td class="menutd">                                   
                                    <table class="menutable" width="100%" border="0" cellpadding="2" cellspacing="2" >
                                        <tr>
                                            <td>                                                
                                                <form action="index.php?component=supplier&page_tpl=new" method="post" name="new_supplier" id="new_supplier" autocomplete="off">                                                
                                                    <table width="100%" cellpadding="3" cellspacing="0" border="0">
                                                        <tr>
                                                            <td colspan="2" align="left">
                                                                <table>
                                                                    <tbody align="left">
                                                                        <tr>
                                                                            <td class="menuhead" colspan="2">{t}Contact{/t}</td>
                                                                        <tr>
                                                                            <td align="right"><b>{t}Supplier ID{/t}</b></td>
                                                                            <td>{$new_record_id}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><b>{t}Display Name{/t}</b><span style="color: #ff0000"> *</span></td>
                                                                            <td><input name="display_name" class="olotd5" size="50" type="text" maxlength="20" required onkeydown="return onlyName(event);"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><strong>{t}First Name{/t}</strong></td>
                                                                            <td><input name="first_name" class="olotd5" size="20" type="text" maxlength="20" onkeydown="return onlyName(event);"/></td>
                                                                        <tr>
                                                                            <td align="right"><strong>{t}Last Name{/t}</strong></td>
                                                                            <td><input name="last_name" class="olotd5" size="20" type="text" maxlength="20" onkeydown="return onlyName(event);"/></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><b>{t}Website{/t}</b></td>
                                                                            <td><input name="website" class="olotd5" size="50" type="url" maxlength="50" pattern="^https?://.+" placeholder="https://quantumwarp.com/" onkeydown="return onlyURL(event);"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><b>{t}Email{/t}</b></td>
                                                                            <td><input name="email" class="olotd5" size="50" type="email" maxlength="50" placeholder="no-reply@quantumwarp.com" onkeydown="return onlyEmail(event);"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><b>{t}Type{/t}</b><span style="color: #ff0000"> *</span></td>
                                                                            <td>
                                                                                <select id="type" name="type" class="olotd5">               
                                                                                    {section name=s loop=$supplier_types}
                                                                                        <option value="{$supplier_types[s].supplier_type_id}">{t}{$supplier_types[s].display_name}{/t}</option>
                                                                                    {/section}
                                                                                </select>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="menuhead" colspan="2">{t}Phone{/t}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><b>{t}Phone{/t}</b></td>
                                                                            <td><input name="primary_phone" class="olotd5" size="20" type="tel" maxlength="20" onkeydown="return onlyPhoneNumber(event);"></b></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><b>{t}Mobile{/t}</b></td>
                                                                            <td><input name="mobile_phone" class="olotd5" size="20" type="tel" maxlength="20" onkeydown="return onlyPhoneNumber(event);"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><b>{t}Fax{/t}</td>
                                                                            <td><input name="fax" class="olotd5" size="20" type="tel" maxlength="20" onkeydown="return onlyPhoneNumber(event);"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="menuhead" colspan="2">{t}Address{/t}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><strong>{t}Address{/t}</strong></td>
                                                                            <td><textarea name="address" class="olotd5 mceNoEditor" cols="30" rows="3" maxlength="100" onkeydown="return onlyAddress(event);"></textarea></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><strong>{t}City{/t}</strong></td>
                                                                            <td><input name="city" class="olotd5" size="20" type="text" maxlength="20" onkeydown="return onlyAlpha(event);"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><strong>{t}State{/t}</strong></td>
                                                                            <td><input name="state" class="olotd5" size="20" type="text" maxlength="20" onkeydown="return onlyAlpha(event);"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><strong>{t}Zip{/t}</strong></td>
                                                                            <td><input name="zip" class="olotd5" size="20" type="text" maxlength="20" onkeydown="return onlyAlphaNumeric(event);"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><strong>{t}Country{/t}</strong></td>
                                                                            <td><input name="country" class="olotd5" size="20" type="text" maxlength="50" onkeydown="return onlyAlpha(event);"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="menuhead" colspan="2"><b>{t}Description{/t}</b><span style="color: #ff0000"> *</span></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td></td>
                                                                            <td><textarea name="description" class="olotd5 mceCheckForContent" cols="50" rows="20"></textarea></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="menuhead" colspan="2"><b>{t}Note{/t}</b</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"></td>
                                                                            <td><textarea name="note" class="olotd5" cols="50" rows="20"></textarea></td>
                                                                        </tr>                                                                        
                                                                        <tr>
                                                                            <td></td>
                                                                            <td>
                                                                                <input class="olotd5" name="submit" value="{t}Submit{/t}" type="submit">
                                                                                <button type="submit" name="submitandnew" value="submitandnew">{t}Submit and New{/t}</button>
                                                                                <button type="button" class="olotd4" onclick="window.location.href='index.php?component=supplier&page_tpl=search';">{t}Cancel{/t}</button>
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