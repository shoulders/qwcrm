<!-- template name -->
<table width="100%" border="0" cellpadding="20" cellspacing="0">
    <tr>
        <td><!-- Begin Page -->
            <table width="700" cellpadding="5" cellspacing="0" border="0" >
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;Edit Employee</td>

                </tr><tr>
                    <td class="menutd2">

                        <table width="100%" class="olotable" cellpadding="5" cellspacing="0" border="0" >
                            <tr>
                                <td width="100%" valign="top" >
                                    <!-- Content Here -->
								{literal}
                                    <form  action="?page=employees:edit" method="POST" name="new_employee" id="new_employee" onsubmit="try { var myValidator = validate_new_employee; } catch(e) { return true; } return myValidator(this);">
					{/literal}
                                        <table class="menutable" width="100%" border="0" cellpadding="0" cellspacing="0" >
                                            <tr>
                                                <td class="menutd">


                                                    <table width="100%" cellpadding="2" cellspacing="2" border="0" class="menutd2">
                                                        <tr>
                                                            <td>
												{section name="a" loop=$employee_details}
                                                                {include file="employees/emp_edit.js"}
                                                                <input type="hidden" name="employee_id" value="{$employee_details[a].EMPLOYEE_ID}">
                                                                <table width="100%" cellpadding="5" cellspacing="0" border="0" class="olotable">
                                                                    <tr class="row2">
                                                                                        <td class="menuhead" colspan="3" width="100%">&nbsp;{$translate_employee_phone_numbers}</td>
                                                                                    </tr>
                                                                                    <tr>
                                                                        <td align="left">
                                                                            <table>
                                                                                <tbody align="left">
                                                                                    
                                                                                    <tr>
                                                                                        <td align="right">
                                                                                            <strong>{$translate_employee_display_name}</strong></td>
                                                                                        <td><input name="displayName" value="{$employee_details[a].EMPLOYEE_DISPLAY_NAME}" type="text" class="requiredfields" /></td>
                                                                                    </tr><tr>
                                                                                        <td align="right">
                                                                                            <strong>{$translate_employee_first_name}</strong></td>
                                                                                        <td><input name="firstName" value="{$employee_details[a].EMPLOYEE_FIRST_NAME}" type="text" class="requiredfields"/></td>
                                                                                    </tr>
                                                                                    <tr><td align="right">
                                                                                            <strong>{$translate_employee_last_name}</strong></td>
                                                                                        <td><input name="lastName" value="{$employee_details[a].EMPLOYEE_LAST_NAME}" type="text" class="requiredfields"/></td>
                                                                                    </tr><tr>
                                                                                        <td align="right">
                                                                                            <strong>{$translate_employee_email_address}</strong></td>
                                                                                        <td><input size="50" name="email" value="{$employee_details[a].EMPLOYEE_EMAIL}" type="text" class="requiredfields"/></td>
                                                                                    </tr><tr>
                                                                                        <td align="right">
                                                                                            <strong>{$translate_employee_type}</strong></td>
                                                                                        <td><select  class="requiredfields" name="type" >
                                                                                                {section name=g loop=$employee_type}
                                                                                                <option value="{$employee_type[g].TYPE_ID}" { if $employee_details[a].EMPLOYEE_TYPE == $employee_type[g].TYPE_ID } selected{/if}>{$employee_type[g].TYPE_NAME}</option>
                                                                                                {/section}
                                                                                            </select>
                                                                                        </td>
                                                                                    </tr><tr>
                                                                                        <td colspan="1" align="right" ><b>Active </b></td>
                                                                                        <td>
                                                                                            <select class="olotd5" name="active">
                                                                                                <option value="0" { if $employee_details[a].EMPLOYEE_STATUS == '0' } selected {/if}>No</option>
                                                                                                <option value="1" { if $employee_details[a].EMPLOYEE_STATUS == '1' } selected {/if}>Yes</option>
                                                                                            </select>
                                                                                        </td>
                                                                                    </tr><tr>
                                                                                        <td align="right"><strong>{$translate_employee_login_id}</strong></td>
                                                                                        <td><input name="login_id" type="text" class="olotd5"/></td>
                                                                                    </tr><tr>
                                                                                        <td align="right">
                                                                                            <strong>{$translate_employee_password}</strong></td>
                                                                                        <td><input name="password"  type="password" class="olotd5"/></td>
                                                                                    </tr><tr>
                                                                                        <td align="right">
                                                                                            <strong>{$translate_employee_password_confirm}</strong></td>
                                                                                        <td><input  name="confirmPass" type="password" class="olotd5"/></td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </td>
                                                                    </tr><tr class="row2">
                                                                        <td class="menuhead" colspan="2">&nbsp;{$translate_employee_phone_numbers}</td>
                                                                    </tr><tr>
                                                                        <td colspan="2" align="left">
                                                                            <table>
                                                                                <tr>
                                                                                    <td align="right">
                                                                                        <strong>{$translate_employee_home_phone_number}</strong></td>
                                                                                    <td><input name="homePhone" value="{$employee_details[a].EMPLOYEE_HOME_PHONE}" type="text" class="requiredfields" /></td>
                                                                                </tr><tr>
                                                                                    <td align="right">
                                                                                        <strong>{$translate_employee_work_phone_number}</strong></td>
                                                                                    <td><input name="workPhone" value="{$employee_details[a].EMPLOYEE_WORK_PHONE}" type="text" class="olotd5"/></td>
                                                                                </tr><tr>
                                                                                    <td align="right">
                                                                                        <strong>{$translate_employee_mobile_phone_number}</strong></td>
                                                                                    <td><input name="mobilePhone" value="{$employee_details[a].EMPLOYEE_MOBILE_PHONE}" type="text" class="olotd5"/></td>
                                                                                </tr>
                                                                            </table>
                                                                        </td>
                                                                    </tr><tr class="row2">
                                                                        <td class="menuhead" colspan="2">&nbsp;{$translate_employee_address}</td>
                                                                    </tr><tr>
                                                                        <td colspan="3">
                                                                            <table>
                                                                                <tbody align="left">
                                                                                    <tr>
                                                                                        <td valign="top" align="right">
                                                                                            <strong>{$translate_employee_address}</strong></td>
                                                                                        <td><textarea cols="30" rows="3"  name="address" class="requiredfields">{$employee_details[a].EMPLOYEE_ADDRESS}</textarea></td>

                                                                                    </tr><tr>
                                                                                        <td align="right">
                                                                                            <strong>{$translate_employee_city}</strong></td>
                                                                                        <td><input name="city" value="{$employee_details[a].EMPLOYEE_CITY}" type="text" class="requiredfields"/></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td align="right">
                                                                                            <strong>{$translate_employee_state}</strong></td>
                                                                                        <td><input name="state" value="{$employee_details[a].EMPLOYEE_STATE}" type="text" class="requiredfields"/></td>
                                                                                    </tr><tr>
                                                                                        <td align="right">
                                                                                            <strong>{$translate_employee_zip}</strong></td>
                                                                                        <td ><input name="zip"  value="{$employee_details[a].EMPLOYEE_ZIP}" type="text" class="requiredfields"/></td>
                                                                                    </tr><tr>
                                                                                        <td colspan="3">

                                                                                            <strong>{$translate_employee_based}&nbsp&nbsp</strong>
                                                                                            <select  class="requiredfields" name="based" >
                                                                                                <option value="1" { if $employee_details[a].EMPLOYEE_BASED == 1 } selected{/if}>Home</option>
                                                                                                <option value="0" { if $employee_details[a].EMPLOYEE_BASED == 0 } selected{/if}>Office</option>
                                                                                            </select>
                                                                                        </td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </td>
                                                                    </tr><tr>
                                                                        <td colspan="2"><input name="submit" value="{$translate_employee_submit}" type="submit" class="olotd5"/></td>
                                                                    </tr>
                                                                    </tbody>
                                                                </table>
														{/section}

                                                            </td>
                                                    </table>
                                                </td>
                                        </table>
                                    </form>
                                    <!-- End Content -->
                                </td>
                            </tr>
                        </table>
                </tr>
            </table>
        </td>
    </tr>
</table>
