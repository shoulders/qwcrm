<!-- new.tpl -->
<script src="{$theme_js_dir}tinymce/tinymce.min.js"></script>
<script src="{$theme_js_dir}editor-config.js"></script>

<table width="100%"   border="0" cellpadding="20" cellspacing="5">
    <tr>
        <td>            
            <table width="700" cellpadding="5" cellspacing="0" border="0" >
                <tr>
                    <td class="menuhead2" width="80%">{$translate_customer_add}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" alt="" border="0" onMouseOver="ddrivetip('<b>{$translate_customer_add}</b><hr><p><i>Display Name:</i>This is the customers display name. It will show up on all pertaining pages. This can be a company name or the customers Fist name and last name.<br><br> <i>First Name:</i>This is the customers first name or if this is a bussiness this is the main contacts first name for the bussiness.<br></p>');" onMouseOut="hideddrivetip();" onClick="window.location;">                                
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
                                                {literal}
                                                <form  action="index.php?page=customer:new" method="POST" name="new_customer" id="new_customer">
                                                {/literal}
                                                    <table width="100%" cellpadding="3" cellspacing="0" border="0">
                                                        <tr>
                                                            <td colspan="2" align="left">
                                                                <table>
                                                                    <tbody align="left">
                                                                        <tr>
                                                                            <td align="right"><b>{$translate_display}</b><span style="color: #ff0000">*</span></td>
                                                                            <td colspan="3"><input name="displayName" class="olotd5" size="60" type="text" maxlength="50" required onkeydown="return onlyAlpha(event);"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><b>{$translate_first}</b></td>
                                                                            <td><input name="firstName" class="olotd5" type="text" maxlength="20" onkeydown="return onlyAlpha(event);"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><b>{$translate_last}</b></td>
                                                                            <td><input name="lastName" class="olotd5" type="text" maxlength="20" onkeydown="return onlyAlpha(event);"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><span style="color: #ff0000"></span><b>{$translate_customer_www}</b></td>
                                                                            <td><input name="customerWww" class="olotd5" size="50" type="url" maxlength="50" placeholder="https://quantumwarp.com/" pattern="^^https?://.+" onkeydown="return onlyURL(event);"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><span style="color: #ff0000"></span><b>{$translate_email}</b></td>
                                                                            <td><input class="olotd5" name="email" size="50" type="email" maxlength="50" placeholder="no-reply@quantumwarp.com" onkeydown="return onlyEmail(event);"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><span style="color: #ff0000"></span><b>{$translate_credit_terms}</b></td>
                                                                            <td><input name="creditterms" class="olotd5" size="50" type="text" maxlength="50" onkeydown="return onlyAlphaNumeric(event);"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><b>{$translate_customer_type}</b><span style="color: #ff0000">*</span></td>
                                                                            <td>
                                                                                <select name="customerType" class="olotd5">
                                                                                    <option value="1">{$translate_customer_type_1}</option>
                                                                                    <option value="2">{$translate_customer_type_2}</option>
                                                                                    <option value="3">{$translate_customer_type_3}</option>
                                                                                    <option value="4">{$translate_customer_type_4}</option>
                                                                                    <option value="5">{$translate_customer_type_5}</option>
                                                                                    <option value="6">{$translate_customer_type_6}</option>
                                                                                    <option value="7">{$translate_customer_type_7}</option>
                                                                                    <option value="8">{$translate_customer_type_8}</option>
                                                                                    <option value="9">{$translate_customer_type_9}</option>
                                                                                    <option value="10">{$translate_customer_type_10}</option>
                                                                                </select>
                                                                                <input name="page" value="customer:new" type="hidden">
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><b>{$translate_customer_discount}</b><span style="color: #ff0000">*</span></td>
                                                                            <td><a><input name="discount" class="olotd5" value="0.00" type="text" maxlength="5" pattern="{literal}^[0-9]{0,2}(\.[0-9]{0,2})?${/literal}" required onkeydown="return onlyNumbersPeriod(event);"><b>%</b></a></td>
                                                                        </tr>                                                              
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="menuhead" colspan="2">{$translate_phone}</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2">
                                                                <table>
                                                                    <tr>
                                                                        <td align="right"><b>{$translate_customer_home}</b></td>
                                                                        <td><input name="homePhone" class="olotd5" type="tel" maxlength="20" onkeydown="return onlyPhoneNumber(event);"></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td align="right"><b>{$translate_customer_work}</b></td>
                                                                        <td><input name="workPhone" class="olotd5" type="tel" maxlength="20" onkeydown="return onlyPhoneNumber(event);"></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td align="right"><b>{$translate_customer_mobile}</b></td>
                                                                        <td><input name="mobilePhone" class="olotd5" type="tel" maxlength="20" onkeydown="return onlyPhoneNumber(event);"></td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="menuhead" colspan="2">{$translate_customer_address}</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2">
                                                                <table>
                                                                    <tbody align="left">
                                                                        <tr>
                                                                            <td align="right"><b>{$translate_customer_address}</b></td>
                                                                            <td colspan="3"><textarea name="address" class="olotd5 mceNoEditor" cols="30" rows="3" maxlength="100" onkeydown="return onlyAddress(event);"></textarea></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><b>{$translate_customer_city}</b></td>
                                                                            <td><input name="city" class="olotd5" type="text" maxlength="20" onkeydown="return onlyAlpha(event);"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><b>{$translate_customer_state}</b></td>
                                                                            <td><input name="state" class="olotd5" type="text" maxlength="20" onkeydown="return onlyAlpha(event);"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><b>{$translate_customer_zip}</b></td>
                                                                            <td colspan="2"><input name="zip" class="olotd5" type="text" maxlength="20" onkeydown="return onlyAlphaNumeric(event);"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="menuhead"><b>{$translate_customer_notes}</b></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td></td>                                                                            
                                                                            <td colspan="2"><textarea name="customerNotes" class="olotd5" cols="50" rows="20"></textarea></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td></td>
                                                                            <td><input class="olotd5" name="submit" value="Submit" type="submit" /></td>
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