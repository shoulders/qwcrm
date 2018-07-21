<!-- edit.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<script src="{$theme_js_dir}tinymce/tinymce.min.js"></script>
<script>{include file="`$theme_js_dir_finc`editor-config.js"}</script>

<table width="100%" border="0" cellpadding="20" cellspacing="0">
    <tr>
        <td>            
            <table width="700" cellpadding="5" cellspacing="0" border="0">
                <tr>
                    <td class="menuhead2" width="680">&nbsp;{t}Edit{/t} - {$client_details.display_name}</td>
                    <td class="menuhead2" width="20" align="right" valign="middle">
                        <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}CUSTOMER_EDIT_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}CUSTOMER_EDIT_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
                    </td>
                </tr>                
                <tr>
                    <td class="menutd2" colspan="2">                    
                        <table width="100%" class="olotable" cellpadding="5" cellspacing="0" border="0">
                            <tr>
                                <td width="100%" valign="top">                                   
                                    <table class="menutable" width="100%" border="0" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td class="menutd">                                                                                                 
                                                <form action="index.php?component=client&page_tpl=edit&client_id={$client_details.client_id}" method="post" name="edit_client" id="edit_client">                                                    
                                                    <table width="100%" cellpadding="2" cellspacing="2" border="0">
                                                        <tr>
                                                            <td colspan="2" align="left">
                                                                <table>
                                                                    <tbody align="left">
                                                                        <tr>
                                                                            <td align="right"><strong>{t}Company Name{/t}</strong></td>
                                                                            <td colspan="3"><input name="company_name" class="olotd5" size="50" value="{$client_details.company_name}" type="text" maxlength="50" onkeydown="return onlyName(event);"/></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><strong>{t}First Name{/t}</strong><span style="color: #ff0000">*</span></td>
                                                                            <td><input name="first_name" class="olotd5" value="{$client_details.first_name}" size="20" type="text" maxlength="20" required onkeydown="return onlyName(event);"/></td>
                                                                        <tr>
                                                                            <td align="right"><strong>{t}Last Name{/t}</strong><span style="color: #ff0000">*</span></td>
                                                                            <td><input name="last_name" class="olotd5" value="{$client_details.last_name}" size="20" type="text" maxlength="20" required onkeydown="return onlyName(event);"/></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><b>{t}Website{/t}</b></td>
                                                                            <td><input name="website" class="olotd5" value="{$client_details.website}" size="50" type="url" maxlength="50" placeholder="https://quantumwarp.com/" pattern="^https?://.+" onkeydown="return onlyURL(event);"/></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><strong>{t}Email{/t}</strong></td>
                                                                            <td><input name="email" class="olotd5" value="{$client_details.email}" size="50" type="email" maxlength="50" placeholder="no-reply@quantumwarp.com" onkeydown="return onlyEmail(event);"/></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><strong>{t}Credit Terms{/t}</strong></td>
                                                                            <td><input name="credit_terms" class="olotd5" value="{$client_details.credit_terms}" size="50" type="text" maxlength="50" onkeydown="return onlyAlphaNumeric(event);"/></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><b>{t}Discount{/t}</b><span style="color: #ff0000">*</span></td>
                                                                            <td><input name="discount_rate" class="olotd5" size="4" value="{$client_details.discount_rate|string_format:"%.2f"}" type="text" maxlength="5" pattern="{literal}^[0-9]{0,2}(\.[0-9]{0,2})?${/literal}" required onkeydown="return onlyNumberPeriod(event);"/><b>%</b></td>
                                                                        </tr>                                                                                  
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="menuhead" colspan="2">{t}Account{/t}</td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">
                                                                    <table>
                                                                        <tr>
                                                                            <td align="right"><strong>{t}Type{/t}</strong><span style="color: #ff0000">*</span></td>
                                                                            <td>
                                                                                <select id="type" name="type" class="olotd5"> 
                                                                                    {section name=s loop=$client_types}    
                                                                                        <option value="{$client_types[s].client_type_id}"{if $client_details.type == $client_types[s].client_type_id} selected{/if}>{t}{$client_types[s].display_name}{/t}</option>
                                                                                    {/section}    
                                                                                </select>                                                                                   
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><b>{t}Active{/t}</b></td>
                                                                            <td>
                                                                                <select class="olotd5" id="active" name="active">                                                       
                                                                                    <option value="0"{if $client_details.active == '0'} selected{/if}>{t}No{/t}</option>
                                                                                    <option value="1"{if $client_details.active == '1'} selected{/if}>{t}Yes{/t}</option>
                                                                                </select>                                                                                
                                                                            </td>                        
                                                                        </tr>                                                                        
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="menuhead" colspan="2">{t}Phone{/t}</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2">
                                                                <table>
                                                                    <tr>
                                                                        <td align="right"><strong>{t}Phone{/t}</strong></td>
                                                                        <td><input name="primary_phone" class="olotd5" value="{$client_details.primary_phone}" type="tel" maxlength="20" onkeydown="return onlyPhoneNumber(event);"/></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td align="right"><strong>{t}Mobile{/t}</strong></td>
                                                                        <td><input name="mobile_phone" class="olotd5" value="{$client_details.mobile_phone}" type="tel" maxlength="20" onkeydown="return onlyPhoneNumber(event);"/></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td align="right"><strong>{t}Fax{/t}</strong></td>
                                                                        <td><input name="fax" class="olotd5" value="{$client_details.fax}" type="tel" maxlength="20" onkeydown="return onlyPhoneNumber(event);"/></td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="menuhead" colspan="2">{t}Address{/t}</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2">
                                                                <table>
                                                                    <tr>
                                                                        <td align="right"><strong>{t}Address{/t}</strong></td>
                                                                        <td colspan="3"><textarea name="address" class="olotd5 mceNoEditor" cols="30" rows="3" maxlength="100" onkeydown="return onlyAddress(event);"/>{$client_details.address}</textarea></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td align="right"><strong>{t}City{/t}</strong></td>
                                                                        <td><input name="city" class="olotd5" value="{$client_details.city}" type="text" maxlength="20" onkeydown="return onlyAlpha(event);"/></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td align="right"><strong>{t}State{/t}</strong></td>
                                                                        <td><input name="state" class="olotd5" value="{$client_details.state}" type="text" maxlength="20" onkeydown="return onlyAlpha(event);"/></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td align="right"><strong>{t}Zip{/t}</strong></td>
                                                                        <td colspan="2"><input name="zip" class="olotd5" value="{$client_details.zip}" type="text" maxlength="20" onkeydown="return onlyAlphaNumeric(event);"/></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td align="right"><strong>{t}Country{/t}</strong></td>
                                                                        <td><input name="country" class="olotd5" value="{$client_details.country}" type="text" maxlength="50" onkeydown="return onlyAlpha(event);"/></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="menuhead"><b>{t}Note{/t}</b></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td></td>
                                                                        <td colspan="2"><textarea name="note" class="olotd5" cols="50" rows="20">{$client_details.note}</textarea></td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2">
                                                                <button type="submit" name="submit" value="update">{t}Update{/t}</button>
                                                                <button type="button" class="olotd4" onclick="window.location.href='index.php?component=client&page_tpl=details&client_id={$client_id}';">{t}Cancel{/t}</button>
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