<!-- edit.tpl -->
<table width="100%" border="0" cellpadding="20" cellspacing="0">
    <tr>
        <td>
            <table width="700" cellpadding="5" cellspacing="0" border="0">
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;Edit Employee</td>
                </tr>
                <tr>
                    <td class="menutd2">
                        <table width="100%" class="olotable" cellpadding="5" cellspacing="0" border="0">
                            <tr>
                                <td width="100%" valign="top">                                 
                                    {literal}
                                    <form action="?page=employee:edit" method="POST" name="edit_employee" id="edit_employee" onsubmit="return checkPasswordsMatch('{/literal}{$translate_core_theme_passwords_match}', '{$translate_core_theme_passwords_do_not_match}{literal}');">
                                    {/literal}
                                        <table class="menutable" width="100%" border="0" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td class="menutd">
                                                    <table width="100%" cellpadding="2" cellspacing="2" border="0" class="menutd2">
                                                        <tr>
                                                            <td>
                                                                {section name="a" loop=$employee_details}                                                                
                                                                    <input name="employee_id" value="{$employee_details[a].EMPLOYEE_ID}" type="hidden">
                                                                    <table class="olotable" width="100%" cellpadding="5" cellspacing="0" border="0">
                                                                        <tr class="row2">
                                                                            <td class="menuhead" colspan="3" width="100%">&nbsp;{$translate_employee_contact_information}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="left">
                                                                                <table>
                                                                                    <tbody align="left">
                                                                                        <tr>
                                                                                            <td align="right"><strong>{$translate_employee_display_name}</strong><span style="color: #ff0000">*</span></td>
                                                                                            <td><input name="employee_displayName" class="olotd5" value="{$employee_details[a].EMPLOYEE_DISPLAY_NAME}" type="text" maxlength="20" required onkeydown="return onlyAlpha(event);"></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td align="right"><strong>{$translate_employee_first_name}</strong><span style="color: #ff0000">*</span></td>
                                                                                            <td><input name="employee_firstName" class="olotd5" value="{$employee_details[a].EMPLOYEE_FIRST_NAME}" type="text" maxlength="20" required onkeydown="return onlyAlpha(event);"></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td align="right"><strong>{$translate_employee_last_name}</strong><span style="color: #ff0000">*</span></td>
                                                                                            <td><input name="employee_lastName" class="olotd5" value="{$employee_details[a].EMPLOYEE_LAST_NAME}" type="text" maxlength="20" required onkeydown="return onlyAlpha(event);"></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td align="right"><strong>{$translate_employee_email_address}</strong><span style="color: #ff0000">*</span></td>
                                                                                            <td><input name="employee_email" class="olotd5" size="50" value="{$employee_details[a].EMPLOYEE_EMAIL}" type="email" maxlength="50" required onkeydown="return onlyEmail(event);"></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td align="right"><strong>{$translate_employee_type}</strong></td>
                                                                                            <td>
                                                                                                <select name="employee_type" class="olotd5">
                                                                                                    {section name=g loop=$employee_type}
                                                                                                        <option value="{$employee_type[g].TYPE_ID}" {if $employee_details[a].EMPLOYEE_TYPE == $employee_type[g].TYPE_ID} selected{/if}>{$employee_type[g].TYPE_NAME}</option>
                                                                                                    {/section}
                                                                                                </select>
                                                                                            </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td colspan="1" align="right"><b>Active</b></td>
                                                                                            <td>
                                                                                                <select name="employee_active" class="olotd5">
                                                                                                    <option value="0" {if $employee_details[a].EMPLOYEE_STATUS == '0'} selected {/if}>No</option>
                                                                                                    <option value="1" {if $employee_details[a].EMPLOYEE_STATUS == '1'} selected {/if}>Yes</option>
                                                                                                </select>
                                                                                            </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td align="right"><strong>{$translate_employee_login_id}</strong><span style="color: #ff0000">*</span></td>
                                                                                            <td><input name="employee_usr" class="olotd5" value="{$employee_details[a].EMPLOYEE_LOGIN}" type="text" maxlength="20" required onkeydown="return onlyUsername(event);"></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td align="right"><strong>{$translate_employee_password}</strong></td>
                                                                                            <td><input id="password" name="employee_pwd" class="olotd5" type="password" maxlength="20" onkeydown="return onlyPassword(event);"></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td align="right"><strong>{$translate_employee_password_confirm}</strong></td>
                                                                                            <td>
                                                                                                <input id="confirmPassword" name="confirmPassword" class="olotd5" type="password" maxlength="20" onkeyup="checkPasswordsMatch('{$translate_core_theme_passwords_match}', '{$translate_core_theme_passwords_do_not_match}');" onkeydown="onlyPassword(event);">
                                                                                                <div id="passwordMessage" style="min-height: 5px;"></div>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                        <tr class="row2">
                                                                            <td class="menuhead" colspan="2">&nbsp;{$translate_employee_phone_numbers}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="2" align="left">
                                                                                <table>
                                                                                    <tr>
                                                                                        <td align="right"><strong>{$translate_employee_home_phone_number}</strong></td>
                                                                                        <td><input name="employee_homePhone" class="olotd5" value="{$employee_details[a].EMPLOYEE_HOME_PHONE}" type="tel" maxlength="20" onkeydown="return onlyPhoneNumber(event);"></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td align="right"><strong>{$translate_employee_work_phone_number}</strong></td>
                                                                                        <td><input name="employee_workPhone" class="olotd5" value="{$employee_details[a].EMPLOYEE_WORK_PHONE}" type="tel" maxlength="20" onkeydown="return onlyPhoneNumber(event);"></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td align="right"><strong>{$translate_employee_mobile_phone_number}</strong></td>
                                                                                        <td><input name="employee_mobilePhone" class="olotd5" value="{$employee_details[a].EMPLOYEE_MOBILE_PHONE}" type="tel" maxlength="20" onkeydown="return onlyPhoneNumber(event);"></td>
                                                                                    </tr>
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                        <tr class="row2">
                                                                            <td class="menuhead" colspan="2">&nbsp;{$translate_employee_address}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="3">
                                                                                <table>
                                                                                    <tbody align="left">
                                                                                        <tr>
                                                                                            <td valign="top" align="right"><strong>{$translate_employee_address}</strong></td>
                                                                                            <td><textarea name="employee_address" class="olotd5" cols="30" rows="3" maxlength="100" onkeydown="return onlyAddress(event);">{$employee_details[a].EMPLOYEE_ADDRESS}</textarea></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td align="right"><strong>{$translate_employee_city}</strong></td>
                                                                                            <td><input name="employee_city" class="olotd5" value="{$employee_details[a].EMPLOYEE_CITY}" type="text" maxlength="20" onkeydown="return onlyAlpha(event);"></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td align="right"><strong>{$translate_employee_state}</strong></td>
                                                                                            <td><input name="employee_state" class="olotd5" value="{$employee_details[a].EMPLOYEE_STATE}" type="text" maxlength="20" onkeydown="return onlyAlpha(event);"></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td align="right"><strong>{$translate_employee_zip}</strong></td>
                                                                                            <td ><input name="employee_zip" class="olotd5" value="{$employee_details[a].EMPLOYEE_ZIP}" type="text" maxlength="20" onkeydown="return onlyAlphaNumeric(event);"></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td colspan="3">
                                                                                                <strong>{$translate_employee_based}&nbsp&nbsp</strong>
                                                                                                <select name="employee_based" class="olotd5">                                                                                                    
                                                                                                    <option value="0" {if $employee_details[a].EMPLOYEE_BASED == 0 } selected{/if}>Office</option>
                                                                                                    <option value="1" {if $employee_details[a].EMPLOYEE_BASED == 1 } selected{/if}>Home</option>
                                                                                                    <option value="2" {if $employee_details[a].EMPLOYEE_BASED == 2 } selected{/if}>OnSite</option>
                                                                                                </select>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="2"><input name="submit" class="olotd5" value="{$translate_employee_submit}" type="submit"></td>
                                                                        </tr>                                                                    
                                                                    </table>
                                                                {/section}
                                                            </td>
                                                    </table>
                                                </td>
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