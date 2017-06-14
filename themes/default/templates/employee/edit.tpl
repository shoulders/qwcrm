<!-- edit.tpl -->
<table width="100%" border="0" cellpadding="20" cellspacing="0">
    <tr>
        <td>
            <table width="700" cellpadding="5" cellspacing="0" border="0">
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{t}Edit Employee{/t}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">                        
                        <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}EMPLOYEE_EDIT_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}EMPLOYEE_EDIT_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
                    </td>
                </tr>
                <tr>
                    <td class="menutd2">
                        <table width="100%" class="olotable" cellpadding="5" cellspacing="0" border="0">
                            <tr>
                                <td width="100%" valign="top">                                                                      
                                    <form action="index.php?page=employee:edit&employee_id={$employee_id}" method="POST" name="edit_employee" id="edit_employee" onsubmit="return checkPasswordsMatch('{t}Passwords Match!{/t}', '{t}Passwords Do Not Match!{/t}');">
                                        <table class="menutable" width="100%" border="0" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td class="menutd">
                                                    <table width="100%" cellpadding="2" cellspacing="2" border="0" class="menutd2">
                                                        <tr>
                                                            <td>                                                                
                                                                <table class="olotable" width="100%" cellpadding="5" cellspacing="0" border="0">
                                                                    <tr class="row2">
                                                                        <td class="menuhead" colspan="3" width="100%">&nbsp;{t}Employee Contact Information{/t}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td align="left">
                                                                            <table>
                                                                                <tbody align="left">
                                                                                    <tr>
                                                                                        <td align="right"><strong>{t}Display Name{/t}</strong><span style="color: #ff0000">*</span></td>
                                                                                        <td><input name="employee_displayName" class="olotd5" value="{$employee_details.EMPLOYEE_DISPLAY_NAME}" type="text" maxlength="20" required onkeydown="return onlyAlpha(event);"></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td align="right"><strong>{t}First Name{/t}</strong><span style="color: #ff0000">*</span></td>
                                                                                        <td><input name="employee_firstName" class="olotd5" value="{$employee_details.EMPLOYEE_FIRST_NAME}" type="text" maxlength="20" required onkeydown="return onlyAlpha(event);"></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td align="right"><strong>{t}Last Name{/t}</strong><span style="color: #ff0000">*</span></td>
                                                                                        <td><input name="employee_lastName" class="olotd5" value="{$employee_details.EMPLOYEE_LAST_NAME}" type="text" maxlength="20" required onkeydown="return onlyAlpha(event);"></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td align="right"><strong>{t}Email{/t}</strong><span style="color: #ff0000">*</span></td>
                                                                                        <td><input name="employee_email" class="olotd5" size="50" value="{$employee_details.EMPLOYEE_EMAIL}" type="email" maxlength="50" required onkeydown="return onlyEmail(event);"></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td align="right"><strong>{t}Type{/t}</strong></td>
                                                                                        <td>                                                                                                
                                                                                            <select name="employee_type" class="olotd5">
                                                                                                {section name=b loop=$employee_type}
                                                                                                    <option value="{$employee_type[b].TYPE_ID}" {if $employee_details.EMPLOYEE_TYPE == $employee_type[b].TYPE_ID} selected{/if}>{$employee_type[b].TYPE_NAME}</option>
                                                                                                {/section}
                                                                                            </select>                                                                                            
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td colspan="1" align="right"><b>Active</b></td>
                                                                                        <td>
                                                                                            <select name="employee_status" class="olotd5">
                                                                                                <option value="0" {if $employee_details.EMPLOYEE_STATUS == '0'} selected {/if}>{t}No{/t}</option>
                                                                                                <option value="1" {if $employee_details.EMPLOYEE_STATUS == '1'} selected {/if}>{t}Yes{/t}</option>
                                                                                            </select>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td align="right"><strong>{t}Username{/t}</strong><span style="color: #ff0000">*</span></td>
                                                                                        <td><input name="employee_usr" class="olotd5" value="{$employee_details.EMPLOYEE_LOGIN}" type="text" maxlength="20" required onkeydown="return onlyUsername(event);"></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td align="right"><strong>{t}Password{/t}</strong></td>
                                                                                        <td><input id="password" name="employee_pwd" class="olotd5" type="password" maxlength="20" onkeydown="return onlyPassword(event);"></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td align="right"><strong>{t}Confirm Password{/t}</strong></td>
                                                                                        <td>
                                                                                            <input id="confirmPassword" name="employee_confirmPassword" class="olotd5" type="password" maxlength="20" onkeyup="checkPasswordsMatch('{t}Passwords Match!{/t}', '{t}Passwords Do Not Match!{/t}');" onkeydown="onlyPassword(event);">
                                                                                            <div id="passwordMessage" style="min-height: 5px;"></div>
                                                                                        </td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                    <tr class="row2">
                                                                        <td class="menuhead" colspan="2">&nbsp;{t}Phone Numbers{/t}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="2" align="left">
                                                                            <table>
                                                                                <tr>
                                                                                    <td align="right"><strong>{t}Home{/t}</strong></td>
                                                                                    <td><input name="employee_homePhone" class="olotd5" value="{$employee_details.EMPLOYEE_HOME_PHONE}" type="tel" maxlength="20" onkeydown="return onlyPhoneNumber(event);"></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td align="right"><strong>{t}Work{/t}</strong></td>
                                                                                    <td><input name="employee_workPhone" class="olotd5" value="{$employee_details.EMPLOYEE_WORK_PHONE}" type="tel" maxlength="20" onkeydown="return onlyPhoneNumber(event);"></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td align="right"><strong>{t}Mobile{/t}</strong></td>
                                                                                    <td><input name="employee_mobilePhone" class="olotd5" value="{$employee_details.EMPLOYEE_MOBILE_PHONE}" type="tel" maxlength="20" onkeydown="return onlyPhoneNumber(event);"></td>
                                                                                </tr>
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                    <tr class="row2">
                                                                        <td class="menuhead" colspan="2">&nbsp;{t}Address{/t}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="3">
                                                                            <table>
                                                                                <tbody align="left">
                                                                                    <tr>
                                                                                        <td valign="top" align="right"><strong>{t}Address{/t}</strong></td>
                                                                                        <td><textarea name="employee_address" class="olotd5" cols="30" rows="3" maxlength="100" onkeydown="return onlyAddress(event);">{$employee_details.EMPLOYEE_ADDRESS}</textarea></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td align="right"><strong>{t}City{/t}</strong></td>
                                                                                        <td><input name="employee_city" class="olotd5" value="{$employee_details.EMPLOYEE_CITY}" type="text" maxlength="20" onkeydown="return onlyAlpha(event);"></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td align="right"><strong>{t}State{/t}</strong></td>
                                                                                        <td><input name="employee_state" class="olotd5" value="{$employee_details.EMPLOYEE_STATE}" type="text" maxlength="20" onkeydown="return onlyAlpha(event);"></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td align="right"><strong>{t}Zip{/t}</strong></td>
                                                                                        <td ><input name="employee_zip" class="olotd5" value="{$employee_details.EMPLOYEE_ZIP}" type="text" maxlength="20" onkeydown="return onlyAlphaNumeric(event);"></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td colspan="3">
                                                                                            <strong>{t}Based{/t}</strong>
                                                                                            <select name="employee_based" class="olotd5">                                                                                                    
                                                                                                <option value="1" {if $employee_details.EMPLOYEE_BASED == 1 } selected{/if}>{t}Office{/t}</option>
                                                                                                <option value="2" {if $employee_details.EMPLOYEE_BASED == 2 } selected{/if}>{t}Home{/t}</option>
                                                                                                <option value="3" {if $employee_details.EMPLOYEE_BASED == 3 } selected{/if}>{t}OnSite{/t}</option>
                                                                                            </select>
                                                                                        </td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="2"><input name="submit" class="olotd5" value="{t}Submit{/t}" type="submit"></td>
                                                                    </tr>                                                                    
                                                                </table>                                                                
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