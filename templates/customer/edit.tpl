<!-- Add New Customer tpl -->
<table width="100%" border="0" cellpadding="20" cellspacing="0">
	<tr>
		<td >
			<!-- Begin Page -->
			<table width="700" cellpadding="5" cellspacing="0" border="0" >
				<tr>
					<td class="menuhead2" width="80%">&nbsp;{$translate_customer_edit}</td>
				</tr><tr>
					<td class="menutd2">
					{include file="customer/edit.js"}
					<table width="100%" class="olotable" cellpadding="5" cellspacing="0" border="0" >
						<tr>
							<td width="100%" valign="top" >
							<!-- Content Here -->
							<table class="menutable" width="100%" border="0" cellpadding="0" cellspacing="0" >
							<tr>
								<td class="menutd">	
									<!-- Edit Customer Form -->
									{literal}
									<form  action="index.php?page=customer:edit" method="POST" name="edit_customer" id="edit_customer" onsubmit="try { var myValidator = validate_edit_customer; } catch(e) { return true; } return myValidator(this);">
									{/literal}
									{section name=q loop=$customer}
									<input type="hidden" name="customer_id" value="{$customer[q].CUSTOMER_ID}">
									<table width="100%" cellpadding="2" cellspacing="2" border="0">
										<tr>
											<td colspan="2" align="left">
												<table>
												<tbody align="left">
													<tr>
														<td><span style="color: #ff0000">*</span>
														 	<strong>{$translate_display}</strong></td>
														<td colspan="3"><input class="olotd5" size="60" value="{$customer[q].CUSTOMER_DISPLAY_NAME}" name="displayName" type="text" /></td>
													</tr><tr>
														<td><span style="color: #ff0000">*</span>
															<strong>{$translate_first}</strong></td>
														<td><input class="olotd5" value="{$customer[q].CUSTOMER_FIRST_NAME}" name="firstName" type="text" /></td>
														<td><span style="color: #ff0000">*</span>
															<strong>{$translate_last}</strong></td>
														<td><input class="olotd5" value="{$customer[q].CUSTOMER_LAST_NAME}" name="lastName" type="text" /></td>
													</tr>
												</tbody>
												</table>	
											</td>
										</tr><tr>
											<td class="menuhead" colspan="2">{$translate_phone}</td>
										</tr><tr>
											<td colspan="2">
												<table>
													<tr>
														<td><span style="color: #ff0000">*</span>
															<strong>{$translate_primary}</strong></td>
														<td><input class="olotd5" value="{$customer[q].CUSTOMER_PHONE}" name="homePhone" type="text" /></td>
													</tr><tr>
														<td><span style="color: #ff0000">*</span>
															<strong>{$translate_secondary}</strong></td>
														<td><input class="olotd5" value="{$customer[q].CUSTOMER_WORK_PHONE}" name="workPhone" type="text" /></td>
													</tr><tr>
														<td><span style="color: #ff0000">*</span>
															<strong>{$translate_mobile}</strong></td>
														<td><input class="olotd5" value="{$customer[q].CUSTOMER_MOBILE_PHONE}" name="mobilePhone" type="text" /></td>
													</tr>
												</table>
											</td>
										</tr><tr>
											<td class="menuhead" colspan="2">{$translate_customer_address}</td>
										</tr><tr>
											<td colspan="2">
												<table>
													<tr>
														<td><span style="color: #ff0000">*</span>
															<strong>{$translate_customer_address}</strong></td>
														<td colspan="3"><input class="olotd5" size="80" value="{$customer[q].CUSTOMER_ADDRESS}" name="address" type="text" /></td>
													</tr><tr>
														<td><span style="color: #ff0000">*</span>
															<strong>{$translate_customer_city}</strong></td>
														<td><input class="olotd5" value="{$customer[q].CUSTOMER_CITY}" name="city" type="text" /></td>
														<td><span style="color: #ff0000">*</span>
															<strong>{$translate_customer_state}</strong></td>
														<td><input class="olotd5" value="{$customer[q].CUSTOMER_STATE}" name="state" type="text" /></td>
													</tr><tr>
														<td><span style="color: #ff0000">*</span>
															<strong>{$translate_customer_zip}</strong></td>
														<td colspan="2"><input class="olotd5" value="{$customer[q].CUSTOMER_ZIP}" name="zip" type="text" /></td>
													</tr>
												</table>	
											</td>
										</tr><tr>
											<td class="menuhead" colspan="2"><br></td>
										</tr><tr>
											<td>
												<span style="color: #ff0000">*</span>
												<strong>{$translate_email}</strong> <input class="olotd5" value="{$customer[q].CUSTOMER_EMAIL}" name="email" size="50" type="text" />
											</td>
											<td><span style="color: #ff0000">*</span>
												<strong>{$translate_type}</strong>
												<select class="SignupData" name="customerType">
													<option value="1" {if $customer[q].CUSTOMER_TYPE == 1} selected{/if}>{$translate_customer_type_1}</option>
													<option value="2"   {if $customer[q].CUSTOMER_TYPE == 2}   selected{/if}>{$translate_customer_type_2}</option>
													<option value="3"   {if $customer[q].CUSTOMER_TYPE == 3}   selected{/if}>{$translate_customer_type_3}</option>
													<option value="4"   {if $customer[q].CUSTOMER_TYPE == 4}   selected{/if}>{$translate_customer_type_4}</option>
												</select>
												<input type="hidden" name="page" value="customer:edit">
											</td>
										</tr><tr>
                                                                                        <td><b>{$translate_customer_discount}</b><a><input class="olotd5" type="text" size="4" name="discount" value="{$customer[q].DISCOUNT}"></a><b>%</b></td>
										</tr>
											<td colspan="2"><input type="submit" name="submit" value="submit"></td>
										</tr>
									</table>
									{/section}
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