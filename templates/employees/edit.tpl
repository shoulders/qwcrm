<!-- template name -->
<table width="100%" border="0" cellpadding="20" cellspacing="0">
	<tr>
		<td><!-- Begin Page -->
			<table width="700" cellpadding="5" cellspacing="0" border="0" >
				<tr>
					<td class="menuhead2" width="80%">&nbsp;Edit Employee</td>
					</td>
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
														<tr>
															<td colspan="2" align="left">
																<table>
																<tbody align="left">
																	<tr>
																		<td><span style="color: #ff0000">*</span>
																			<strong>{$translate_employee_display_name}</strong></td>
																		<td colspan="3"><input size="60" name="displayName" value="{$employee_details[a].EMPLOYEE_DISPLAY_NAME}" type="text" class="olotd5" /></td>
																	</tr><tr>
																		<td><span style="color: #ff0000">*</span>
																			<strong>{$translate_employee_first_name}</strong></td>
																		<td><input name="firstName" value="{$employee_details[a].EMPLOYEE_FIRST_NAME}" type="text" class="olotd5"/></td>
																		<td><span style="color: #ff0000">*</span>
																			<strong>{$translate_employee_last_name}</strong></td>
																		<td><input name="lastName" value="{$employee_details[a].EMPLOYEE_LAST_NAME}" type="text" class="olotd5"/></td>
																	</tr><tr>
                                                                                                                                                <td><span style="color: #ff0000">*</span>
																			<strong>{$translate_employee_login_id}</strong></td>
																		<td><input name="login_id" type="text" class="olotd5"/></td>
																		<td><span style="color: #ff0000"></span>
																			<strong>{$translate_employee_password}</strong></td>
																		<td><input name="password"  type="password" class="olotd5"/></td>
																		<td><span style="color: #ff0000"></span>
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
																		<td><input name="homePhone" value="{$employee_details[a].EMPLOYEE_HOME_PHONE	}" type="text" class="olotd5" /></td>
																	</tr><tr>
																		<td>
																			<strong>{$translate_employee_work_phone_number}</strong></td>
																		<td><input name="workPhone" value="{$employee_details[a].EMPLOYEE_WORK_PHONE}" type="text" class="olotd5"/></td>
																	</tr><tr>
																		<td>
																			<strong>{$translate_employee_mobile_phone_number}</strong></td>
																		<td><input name="mobilePhone" value="{$employee_details[a].EMPLOYEE_MOBILE_PHONE}" type="text" class="olotd5"/></td>
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
																			<td colspan="3"><input size="54" name="address" value="{$employee_details[a].EMPLOYEE_ADDRESS}" type="text" class="olotd5"/></td>
																		</tr><tr>
																			<td><span style="color: #ff0000">*</span>
																				<strong>{$translate_employee_city}</strong></td>
																			<td><input name="city" value="{$employee_details[a].EMPLOYEE_CITY}" type="text" class="olotd5"/></td>
																			<td><span style="color: #ff0000">*</span>
																				<strong>{$translate_employee_state}</strong></td>
																			<td><input name="state" value="{$employee_details[a].EMPLOYEE_STATE}" type="text" class="olotd5"/></td>
																		</tr><tr>
																			<td><span style="color: #ff0000">*</span>
																				<strong>{$translate_employee_zip}</strong></td>
																			<td colspan="2"><input name="zip"  value="{$employee_details[a].EMPLOYEE_ZIP}" type="text" class="olotd5"/></td>
																		</tr><tr><span style="color: #ff0000">*</span>
																				<strong>{$translate_employee_based}&nbsp&nbsp</strong>
																				<select  class="olotd5" name="based" >
																				<option value="1" { if $employee_details[a].EMPLOYEE_BASED == 1 } selected{/if}>Home</option>
																				<option value="0" { if $employee_details[a].EMPLOYEE_BASED == 0 } selected{/if}>Office</option>	
																				</select>
																						
																			</tr>
																	</tbody>
																	</table>	
																</td>
															</tr><tr>
																<td><span style="color: #ff0000">*</span>
																	<strong>{$translate_employee_type}</strong>
																	<select  class="olotd5" name="type" >
																	{section name=g loop=$employee_type}
																		<option value="{$employee_type[g].TYPE_ID}" { if $employee_details[a].EMPLOYEE_TYPE == $employee_type[g].TYPE_ID } selected{/if}>{$employee_type[g].TYPE_NAME}</option>	
																	{/section}
																	</select>
																</td>
															</tr><tr>
																<td>
																	<span style="color: #ff0000">*</span>
																	<strong>{$translate_employee_email_address}</strong><input name="email" value="{$employee_details[a].EMPLOYEE_EMAIL}" type="text" class="olotd5"/></td>
																<td></td>
															</tr><tr>
																<td colspan="1" ><b>Active </b> 
																	<select class="olotd5" name="active">
																		<option value="0" { if $employee_details[a].EMPLOYEE_STATUS == '0' } selected {/if}>No</option>
																		<option value="1" { if $employee_details[a].EMPLOYEE_STATUS == '1' } selected {/if}>Yes</option>
																	</select>
																</td>
																</tr><tr>
																<td colspan="2"><input name="submit" value="{$translate_employee_submit}" type="submit" class="olotd5"/></td>
															</tr>
															</tbody>
														</table>
														{/section}
														
												</td>
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
</table>