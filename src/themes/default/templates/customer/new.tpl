<!-- new.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<script src="{$theme_js_dir}tinymce/tinymce.min.js"></script>
<script>{include file="`$theme_js_dir_finc`editor-config.js"}</script>

<table width="100%"   border="0" cellpadding="20" cellspacing="5">
    <tr>
        <td>            
            <table width="700" cellpadding="5" cellspacing="0" border="0" >
                <tr>
                    <td class="menuhead2" width="80%">{t}Add Customer{/t}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}CUSTOMER_NEW_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}CUSTOMER_NEW_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
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
                                                <form action="index.php?component=customer&page_tpl=new" method="post" name="new_customer" id="new_customer">                                                
                                                    <table width="100%" cellpadding="3" cellspacing="0" border="0">
                                                        <tr>
                                                            <td colspan="2" align="left">
                                                                <table>
                                                                    <tbody align="left">
                                                                        <tr>
                                                                            <td align="right"><b>{t}Display Name{/t}</b><span style="color: #ff0000">*</span></td>
                                                                            <td colspan="3"><input name="display_name" class="olotd5" size="60" type="text" maxlength="50" required onkeydown="return onlyName(event);"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><b>{t}First Name{/t}</b></td>
                                                                            <td><input name="first_name" class="olotd5" type="text" maxlength="20" onkeydown="return onlyName(event);"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><b>{t}Last Name{/t}</b></td>
                                                                            <td><input name="last_name" class="olotd5" type="text" maxlength="20" onkeydown="return onlyName(event);"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><b>{t}Website{/t}</b></td>
                                                                            <td><input name="website" class="olotd5" size="50" type="url" maxlength="50" placeholder="https://quantumwarp.com/" pattern="^^https?://.+" onkeydown="return onlyURL(event);"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><b>{t}Email{/t}</b></td>
                                                                            <td><input class="olotd5" name="email" size="50" type="email" maxlength="50" placeholder="no-reply@quantumwarp.com" onkeydown="return onlyEmail(event);"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><b>{t}Credit Terms{/t}</b></td>
                                                                            <td><input name="credit_terms" class="olotd5" size="50" type="text" maxlength="50" onkeydown="return onlyAlphaNumeric(event);"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><b>{t}Discount{/t}</b><span style="color: #ff0000">*</span></td>
                                                                            <td><a><input name="discount_rate" class="olotd5" value="0.00" type="text" maxlength="5" pattern="{literal}^[0-9]{0,2}(\.[0-9]{0,2})?${/literal}" required onkeydown="return onlyNumberPeriod(event);"><b>%</b></a></td>
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
                                                                                    {section name=s loop=$customer_types}    
                                                                                        <option value="{$customer_types[s].customer_type_id}"{if $customer_details.type == $customer_types[s].customer_type_id} selected{/if}>{t}{$customer_types[s].display_name}{/t}</option>
                                                                                    {/section}    
                                                                                </select>                                                                                   
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><b>{t}Active{/t}</b></td>
                                                                            <td>
                                                                                <select class="olotd5" id="active" name="active">                                                                                    
                                                                                    <option value="1"{if $customer_details.active == '1'} selected{/if}>{t}Yes{/t}</option>
                                                                                    <option value="0"{if $customer_details.active == '0'} selected{/if}>{t}No{/t}</option>
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
                                                                        <td align="right"><b>{t}Primary{/t}</b></td>
                                                                        <td><input name="primary_phone" class="olotd5" type="tel" maxlength="20" onkeydown="return onlyPhoneNumber(event);"></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td align="right"><b>{t}Mobile{/t}</b></td>
                                                                        <td><input name="mobile_phone" class="olotd5" type="tel" maxlength="20" onkeydown="return onlyPhoneNumber(event);"></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td align="right"><b>{t}Fax{/t}</b></td>
                                                                        <td><input name="fax" class="olotd5" type="tel" maxlength="20" onkeydown="return onlyPhoneNumber(event);"></td>
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
                                                                    <tbody align="left">
                                                                        <tr>
                                                                            <td align="right"><b>{t}Address{/t}</b></td>
                                                                            <td colspan="3"><textarea name="address" class="olotd5 mceNoEditor" cols="30" rows="3" maxlength="100" onkeydown="return onlyAddress(event);"></textarea></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><b>{t}City{/t}</b></td>
                                                                            <td><input name="city" class="olotd5" type="text" maxlength="20" onkeydown="return onlyAlpha(event);"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><b>{t}State{/t}</b></td>
                                                                            <td><input name="state" class="olotd5" type="text" maxlength="20" onkeydown="return onlyAlpha(event);"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><b>{t}Zip{/t}</b></td>
                                                                            <td colspan="2"><input name="zip" class="olotd5" type="text" maxlength="20" onkeydown="return onlyAlphaNumeric(event);"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><b>{t}Country{/t}</b></td>
                                                                            <td><input name="country" class="olotd5" type="text" maxlength="20" onkeydown="return onlyAlpha(event);"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="menuhead"><b>{t}Note{/t}</b></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td></td>                                                                            
                                                                            <td colspan="2"><textarea name="note" class="olotd5" cols="50" rows="20"></textarea></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="2">
                                                                                <button type="submit" name="submit" value="submit">{t}Submit{/t}</button>
                                                                                <button type="button" class="olotd4" onclick="window.location.href='index.php?component=customer&page_tpl=search';">{t}Cancel{/t}</button>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
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