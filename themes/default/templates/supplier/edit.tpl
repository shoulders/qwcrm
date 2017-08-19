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
                                                <form action="index.php?page=supplier:edit&supplier_id={$supplier_id}" method="post" name="edit_supplier" id="edit_supplier" autocomplete="off">                                                
                                                    <table width="100%" cellpadding="2" cellspacing="2" border="0">
                                                        <tr>
                                                            <td class="menuhead" colspan="2">{t}Contact{/t}</td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><b>{t}Supplier ID{/t}</b></td>
                                                            <td>{$supplier_id}</td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><b>{t}Display Name{/t}</b><span style="color: #ff0000"> *</span></td>
                                                            <td><input name="display_name" class="olotd5" size="50" value="{$supplier_details.display_name}" type="text" maxlength="50" required onkeydown="return onlyAlphaNumeric(event);"></td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><strong>{t}First Name{/t}</strong></td>
                                                            <td><input name="first_name" class="olotd5" size="20" value="{$supplier_details.first_name}" type="text" maxlength="20" onkeydown="return onlyAlpha(event);"/></td>
                                                        <tr>
                                                            <td align="right"><strong>{t}Last Name{/t}</strong></td>
                                                            <td><input name="last_name" class="olotd5" size="20" value="{$supplier_details.last_name}" type="text" maxlength="20" onkeydown="return onlyAlpha(event);"/></td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><b>{t}Website{/t}</b></td>
                                                            <td><input name="website" class="olotd5" size="50" value="{$supplier_details.website}" type="url"  maxlength="50" pattern="^https?://.+" placeholder="https://quantumwarp.com/" onkeydown="return onlyURL(event);"></td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><b>{t}Email{/t}</b></td>
                                                            <td><input name="email" class="olotd5" size="50" value="{$supplier_details.email}" type="email" placeholder="no-reply@quantumwarp.com" maxlength="50" onkeydown="return onlyEmail(event);"></td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><b>{t}Type{/t}</b><span style="color: #ff0000"> *</span></td>
                                                            <td>
                                                                <select id="type" name="type" class="olotd5" style="width: 150px" col="30" value="{$supplier_details.type}">
                                                                    <option value="1"{if $supplier_details.type == '1'} selected{/if}>{t}SUPPLIER_TYPE_1{/t}</option>
                                                                    <option value="2"{if $supplier_details.type == '2'} selected{/if}>{t}SUPPLIER_TYPE_2{/t}</option>
                                                                    <option value="3"{if $supplier_details.type == '3'} selected{/if}>{t}SUPPLIER_TYPE_3{/t}</option>
                                                                    <option value="4"{if $supplier_details.type == '4'} selected{/if}>{t}SUPPLIER_TYPE_4{/t}</option>
                                                                    <option value="5"{if $supplier_details.type == '5'} selected{/if}>{t}SUPPLIER_TYPE_5{/t}</option>
                                                                    <option value="6"{if $supplier_details.type == '6'} selected{/if}>{t}SUPPLIER_TYPE_6{/t}</option>
                                                                    <option value="7"{if $supplier_details.type == '7'} selected{/if}>{t}SUPPLIER_TYPE_7{/t}</option>
                                                                    <option value="8"{if $supplier_details.type == '8'} selected{/if}>{t}SUPPLIER_TYPE_8{/t}</option>
                                                                    <option value="9"{if $supplier_details.type == '9'} selected{/if}>{t}SUPPLIER_TYPE_9{/t}</option>
                                                                    <option value="10"{if $supplier_details.type == '10'} selected{/if}>{t}SUPPLIER_TYPE_10{/t}</option>
                                                                    <option value="11"{if $supplier_details.type == '11'} selected{/if}>{t}SUPPLIER_TYPE_11{/t}</option>                                                                                       
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
                                                            <td class="menuhead" colspan="2"><b>{t}Notes{/t}</b></td>
                                                        </tr> 
                                                        <tr>
                                                            <td align="right"></td>
                                                            <td><textarea name="notes" class="olotd5" cols="50" rows="20">{$supplier_details.notes}</textarea></td>
                                                        </tr>
                                                        <tr>
                                                            <td></td>
                                                            <td><input class="olotd5" name="submit" type="submit" value="{t}Update{/t}" /></td>
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