<!-- edit.tpl -->
<script src="{$theme_js_dir}tinymce/tinymce.min.js"></script>
<script src="{$theme_js_dir}editor-config.js"></script>

<table width="100%" border="0" cellpadding="20" cellspacing="0">
    <tr>
        <td>            
            <table width="700" cellpadding="5" cellspacing="0" border="0">
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{t}Edit{/t} - {$customer_details.CUSTOMER_DISPLAY_NAME}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}CUSTOMER_EDIT_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}CUSTOMER_EDIT_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
                    </td>
                </tr>                
                <tr>
                    <td class="menutd2">                    
                        <table width="100%" class="olotable" cellpadding="5" cellspacing="0" border="0">
                            <tr>
                                <td width="100%" valign="top">                                   
                                    <table class="menutable" width="100%" border="0" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td class="menutd">                                                                                                 
                                                <form action="index.php?page=customer:edit" method="POST" name="edit_customer" id="edit_customer">                                                    
                                                    <input type="hidden" name="customer_id" value="{$customer_details.CUSTOMER_ID}">
                                                    <table width="100%" cellpadding="2" cellspacing="2" border="0">
                                                        <tr>
                                                            <td colspan="2" align="left">
                                                                <table>
                                                                    <tbody align="left">
                                                                        <tr>
                                                                            <td align="right"><strong>{t}Display Name{/t}</strong><span style="color: #ff0000">*</span></td>
                                                                            <td colspan="3"><input name="displayName" class="olotd5" size="50" value="{$customer_details.CUSTOMER_DISPLAY_NAME}" type="text" maxlength="50" required onkeydown="return onlyAlpha(event);"/></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><strong>{t}First Name{/t}</strong></td>
                                                                            <td><input name="firstName" class="olotd5" value="{$customer_details.CUSTOMER_FIRST_NAME}" type="text" maxlength="20" onkeydown="return onlyAlpha(event);"/></td>
                                                                        <tr>
                                                                            <td align="right"><strong>{t}Last Name{/t}</strong></td>
                                                                            <td><input name="lastName" class="olotd5" value="{$customer_details.CUSTOMER_LAST_NAME}" type="text" maxlength="20" onkeydown="return onlyAlpha(event);"/></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><span style="color: #ff0000"></span><b>{t}Website{/t}</b></td>
                                                                            <td><input name="customerWww" class="olotd5" value="{$customer_details.CUSTOMER_WWW}" size="50" type="url" maxlength="50" placeholder="https://quantumwarp.com/" pattern="^https?://.+" onkeydown="return onlyURL(event);"/></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><strong>{t}Email{/t}</strong></td>
                                                                            <td><input name="email" class="olotd5" value="{$customer_details.CUSTOMER_EMAIL}" size="50" type="email" maxlength="50" placeholder="no-reply@quantumwarp.com" onkeydown="return onlyEmail(event);"/></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><strong>{t}Credit Terms{/t}</strong></td>
                                                                            <td><input name="creditterms" class="olotd5" value="{$customer_details.CREDIT_TERMS}" size="50" type="text" maxlength="50" onkeydown="return onlyAlphaNumeric(event);"/></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><strong>{t}Type{/t}</strong><span style="color: #ff0000">*</span></td>
                                                                            <td>
                                                                                <select class="olotd5" name="customerType">
                                                                                    <option value="1"{if $customer_details.CUSTOMER_TYPE == 1} selected{/if}>{t}CUSTOMER_TYPE_1{/t}</option>
                                                                                    <option value="2"{if $customer_details.CUSTOMER_TYPE == 2} selected{/if}>{t}CUSTOMER_TYPE_2{/t}</option>
                                                                                    <option value="3"{if $customer_details.CUSTOMER_TYPE == 3} selected{/if}>{t}CUSTOMER_TYPE_3{/t}</option>
                                                                                    <option value="4"{if $customer_details.CUSTOMER_TYPE == 4} selected{/if}>{t}CUSTOMER_TYPE_4{/t}</option>
                                                                                    <option value="5"{if $customer_details.CUSTOMER_TYPE == 5} selected{/if}>{t}CUSTOMER_TYPE_5{/t}</option>
                                                                                    <option value="6"{if $customer_details.CUSTOMER_TYPE == 6} selected{/if}>{t}CUSTOMER_TYPE_6{/t}</option>
                                                                                    <option value="7"{if $customer_details.CUSTOMER_TYPE == 7} selected{/if}>{t}CUSTOMER_TYPE_7{/t}</option>
                                                                                    <option value="8"{if $customer_details.CUSTOMER_TYPE == 8} selected{/if}>{t}CUSTOMER_TYPE_8{/t}</option>
                                                                                    <option value="9"{if $customer_details.CUSTOMER_TYPE == 9} selected{/if}>{t}CUSTOMER_TYPE_9{/t}</option>
                                                                                    <option value="10"{if $customer_details.CUSTOMER_TYPE == 10} selected{/if}>{t}CUSTOMER_TYPE_10{/t}</option>
                                                                                </select>                                                                                    
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><b>{t}Discount{/t}</b><span style="color: #ff0000">*</span></td>
                                                                            <td><input name="discount_rate" class="olotd5" size="4" value="{$customer_details.DISCOUNT_RATE|string_format:"%.2f"}" type="text" maxlength="5" pattern="{literal}^[0-9]{0,2}(\.[0-9]{0,2})?${/literal}" required onkeydown="return onlyNumbersPeriod(event);"/><b>%</b></td>
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
                                                                        <td align="right"><strong>{t}Home{/t}</strong></td>
                                                                        <td><input name="homePhone" class="olotd5" value="{$customer_details.CUSTOMER_PHONE}" type="tel" maxlength="20" onkeydown="return onlyPhoneNumber(event);"/></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td align="right"><strong>{t}Work{/t}</strong></td>
                                                                        <td><input name="workPhone" class="olotd5" value="{$customer_details.CUSTOMER_WORK_PHONE}" type="tel" maxlength="20" onkeydown="return onlyPhoneNumber(event);"/></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td align="right"><strong>{t}Mobile{/t}</strong></td>
                                                                        <td><input name="mobilePhone" class="olotd5" value="{$customer_details.CUSTOMER_MOBILE_PHONE}" type="tel" maxlength="20" onkeydown="return onlyPhoneNumber(event);"/></td>
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
                                                                        <td colspan="3"><textarea name="address" class="olotd5 mceNoEditor" cols="30" rows="3" maxlength="100" onkeydown="return onlyAddress(event);"/>{$customer_details.CUSTOMER_ADDRESS}</textarea></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td align="right"><strong>{t}City{/t}</strong></td>
                                                                        <td><input name="city" class="olotd5" value="{$customer_details.CUSTOMER_CITY}" type="text" onkeydown="return onlyAlpha(event);"/></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td align="right"><strong>{t}State{/t}</strong></td>
                                                                        <td><input name="state" class="olotd5" value="{$customer_details.CUSTOMER_STATE}" type="text" onkeydown="return onlyAlpha(event);"/></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td align="right"><strong>{t}Zip{/t}</strong></td>
                                                                        <td colspan="2"><input name="zip" class="olotd5" value="{$customer_details.CUSTOMER_ZIP}" type="text" onkeydown="return onlyAlphaNumeric(event);"/></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="menuhead"><b>{t}Notes{/t}</b></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td></td>
                                                                        <td colspan="2"><textarea name="customerNotes" class="olotd5" cols="50" rows="20">{$customer_details.CUSTOMER_NOTES}</textarea></td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td></td>
                                                            <td><input class="olotd5" name="submit" value="Update" type="submit"></td>
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