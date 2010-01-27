<!-- -->
<table width="700" border="0" cellpadding="20" cellspacing="5">
	<tr>
		<td>

			<table width="100%" cellpadding="4" cellspacing="0" border="0">
				<tr>
					<td class="menuhead2" width="80%">&nbsp;{$translate_billing_gift}</td>
					<td class="menuhead2" width="20%" align="right" valign="middle"></td>
				</tr><tr>
					<td class="olotd5" colspan="2">
					<!-- Content Begin -->

						<table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
							<tr>
								<td class="olotd4">

									<table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
										<tr>
											<td class="olotd4" valign="top">

												<table cellpadding="3" cellspacing="0" border="0" width="100%">
													<tr>
														<td><h2>{$translate_billing_gift}</h2></td>
														<td>{$company_name}</td>
														<td>{$company_phone}</td>
													</tr>
												</table>
												<hr>

												<table cellpadding="3" cellspacing="0" border="0" width="100%">
													<tr>
														<td valign="top" width="50%">
															<b>To:</b>
															{section name=q loop=$customer}
																{$customer[q].CUSTOMER_DISPLAY_NAME}<br>
																{$customer[q].CUSTOMER_ADDRESS}<br>
																{$customer[q].CUSTOMER_CITY} {$customer[q].CUSTOMER_STATE} .{$customer[q].CUSTOMER_ZIP}<br>
																<b>Customer ID: </b>{$customer[q].CUSTOMER_ID}
																{assign var="customer_id" value=$customer[q].CUSTOMER_ID}

															{/section}
															
														</td>
														<td valign="top" width="50%">

															<table cellpadding="0" cellspacing="0" border="0" width="100%">
																<tr>
																	<td><b>{$translate_billing_amount}</b></td>
																	<td>${$amount|string_format:"%.2f"}</td>
																</tr><tr>
																	<td><b>{$translate_billing_gift_code_3}</b></td>
																	<td>{$gift_code}</td>
																</tr><tr>
																	<td><b>{$translate_billing_created}</b></td>
																	<td>{$create|date_format:"$date_format"}</td>
																</tr><tr>
																	<td><b>{$translate_billing_expires}</b></td>
																	<td>{$expire|date_format:"$date_format"}</td>
																</tr>
															</table>	

															<table cellpadding="3" cellspacing="0" border="0" width="100%">
																<tr>
																	<td>{$memo}</td>
																</tr>
															</table>

														<td>
													</tr>
												</table>
												{$translate_billing_gift_note_1} ${$amount} {$translate_billing_gift_note_2}
											</td>
										</tr>
									</table>
							
								</td>
							</tr>
						</table>
						<a href="?page=billing:new_gift&gift_id={$gift_id}&customer_id={$customer_id}&action=print&submit=1&escape=1" target="new" ><img src="images/icons/16x16/fileprint.gif" border="0" onMouseOver="ddrivetip('Print')" onMouseOut="hideddrivetip()"></a>&nbsp;<a href="?page=customer:customer_details&customer_id={$customer_id}">{$translate_billing_back}</a>
					</td>
				</tr>
			</table>

		</td>
	</tr>
</table>