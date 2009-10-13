<!-- New Employee tpl -->
<table width="100%" border="0" cellpadding="20" cellspacing="5">
    <tr>
        <td><!-- Begin Page -->
            <table width="700" cellpadding="5" cellspacing="0" border="0" >
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{$translate_employee_add_new_employee}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <img src="images/icons/16x16/help.gif" alt="" border="0"
                             onMouseOver="ddrivetip('<b>Customer Details</b><hr><p>No help</p>')"
                             onMouseOut="hideddrivetip()">
                    </td>
                </tr><tr>
                    <td class="olotd5" colspan="2">
					{literal}
                        <form  action="index.php?page=employee:new" method="POST" name="new_employee" id="new_employee" onsubmit="try { var myValidator = validate_new_employee; } catch(e) { return true; } return myValidator(this);" >
					{/literal}
                            <table class="menutable" width="100%" border="0" cellpadding="0" cellspacing="0" >
                                <tr>
                                    <td class="menutd">
                                        <input type="hidden" name="page" value="employees:new">

                                        <table width="100%" cellpadding="2" cellspacing="2" border="0" class="menutd2">
                                            <tr>
                                                <td>
                                                    <table width="100%" cellpadding="5" cellspacing="0" border="0" class="olotable">
                                                        <tr>
                                                            <td colspan="2" align="left">
                                                                <table>
                                                                    <tbody align="left">
                                                                        <tr>{include file="employees/emp_new.js"}
                                                                            <td><span style="color: #ff0000">*</span>
                                                                                <strong>{$translate_employee_display_name}</strong></td>
                                                                            <td colspan="3"><input size="60" name="displayName" type="text" class="olotd5" /></td>
                                                                        </tr><tr>
                                                                            <td><span style="color: #ff0000">*</span>
                                                                                <strong>{$translate_employee_first_name}</strong></td>
                                                                            <td><input name="firstName" type="text" class="olotd5"/></td>
                                                                            <td><span style="color: #ff0000">*</span>
                                                                                <strong>{$translate_employee_last_name}</strong></td>
                                                                            <td><input name="lastName" type="text" class="olotd5"/></td>
                                                                        </tr><tr>
                                                                            <td><span style="color: #ff0000">*</span>
                                                                                <strong>{$translate_employee_login_id}</strong></td>
                                                                            <td><input name="login_id" type="text" class="olotd5"/></td>
                                                                            <td><span style="color: #ff0000">*</span>
                                                                                <strong>{$translate_employee_password}</strong></td>
                                                                            <td><input name="password" type="password" class="olotd5"/></td>
                                                                            <td><span style="color: #ff0000">*</span>
                                                                                <strong>{$translate_employee_password_confirm}</strong></td>
                                                                            <td><input  name="confirmPass" type="password" class="olotd5"/></td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr><tr class="row2">
                                                            <td class="menuhead" colspan="2">&nbsp;{$translate_employee_phone_numbers}</td>
                                                        </tr><tr>
                                                            <td colspan="2">
                                                                <table>
                                                                    <tr>
                                                                        <td><span style="color: #ff0000">*</span>
                                                                            <strong>{$translate_employee_home_phone_number}</strong></td>
                                                                        <td><input name="homePhone" type="text" class="olotd5" /></td>
                                                                    </tr><tr>
                                                                        <td>
                                                                            <strong>{$translate_employee_work_phone_number}</strong></td>
                                                                        <td><input name="workPhone" type="text" class="olotd5"/></td>
                                                                    </tr><tr>
                                                                        <td>
                                                                            <strong>{$translate_employee_mobile_phone_number}</strong></td>
                                                                        <td><input name="mobilePhone" type="text" class="olotd5"/></td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr><tr class="row2">
                                                            <td class="menuhead" colspan="2">&nbsp;{$translate_employee_address}</td>
                                                        </tr><tr>
                                                            <td colspan="2">
                                                                <table>
                                                                    <tbody align="left">
                                                                        <tr>
                                                                            <td><span style="color: #ff0000">*</span>
                                                                                <strong>{$translate_employee_address}</strong></td>
                                                                            <td colspan="3"><input size="54" name="address" type="text" class="olotd5"/></td>
                                                                        </tr><tr>
                                                                            <td><span style="color: #ff0000">*</span>
                                                                                <strong>{$translate_employee_city}</strong></td>
                                                                            <td><input name="city" type="text" class="olotd5" value="{$company_city}"/></td>
                                                                            <td><span style="color: #ff0000">*</span>
                                                                                <strong>{$translate_employee_state}</strong></td>
                                                                            <td><input name="state" type="text" class="olotd5" value="{$company_state}"/></td>
                                                                        </tr><tr>
                                                                            <td><span style="color: #ff0000">*</span>
                                                                                <strong>{$translate_employee_zip}</strong></td>						<td colspan="2"><input name="zip" type="text" class="olotd5" value="{$company_zip}"/></td>
                                                                        </tr><tr>
                                                                            <td><span style="color: #ff0000">*</span>
                                                                                <strong>{$translate_employee_based}&nbsp&nbsp</strong>
                                                                                <select class="olotd5" name="based" >
                                                                                    <option value="1">{$translate_employee_b_home}</option>
                                                                                    <option value="2" selected>{$translate_employee_b_office}</option>
                                                                                </select></td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr><tr>
                                                            <td><span style="color: #ff0000">*</span>
                                                                <strong>{$translate_employee_position}&nbsp&nbsp</strong>
                                                                <strong>{$translate_employee_employee_type}</strong>
                                                                <select class="olotd5" name="type" >
                                                                    <option value="1">{$translate_employee_manager}</option>
                                                                    <option value="2">{$translate_employee_supervisor}</option>
                                                                    <option value="3">{$translate_employee_technician}</option>
                                                                </select>
                                                            </td>
                                                        </tr><tr>
                                                            <td>
                                                                <span style="color: #ff0000">*</span>
                                                                <strong>{$translate_employee_email_address}</strong><input name="email" type="text" class="olotd5"/></td>
                                                            <td></td>
                                                        </tr><tr>
                                                            <td colspan="2"><input name="submit" value="{$translate_employee_submit}" type="submit" class="olotd5"/></td>
                                                        </tr>
                                                    </table>


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


