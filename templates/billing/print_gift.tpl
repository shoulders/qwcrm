<html>
<head>
	<title>{$translate_billing_print_title}</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="css/default.css" rel="stylesheet" type="text/css">
</head>
<body>
						<table width="700" cellpadding="4" cellspacing="0" border="0" class="olotable">
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
															{section name=g loop=$gift}
															<table cellpadding="0" cellspacing="0" border="0" width="100%">
																<tr>
																	<td><b>{$translate_billing_amount}</b></td>
																	<td>${$gift[g].AMOUNT|string_format:"%.2f"}</td>
																</tr><tr>
																	<td><b>{$translate_billing_gift_code_3}</b></td>
																	<td>{$gift[g].GIFT_CODE}</td>
																</tr><tr>
																	<td><b>{$translate_billing_created}</b></td>
																	<td>{$gift[g].DATE_CREATE|date_format:"$date_format"}</td>
																</tr><tr>
																	<td><b>{$translate_billing_expires}</b></td>
																	<td>{$gift[g].EXPIRE|date_format:"$date_format"}</td>
																</tr>
															</table>	
														<td>
													</tr>
												</table>
												<table cellpadding="3" cellspacing="0" border="0" width="100%">
													<tr>
														<td>{$gift[g].MEMO}</td>
													</tr>
												</table>
												<br>
												{$translate_billing_gift_note_1} ${$gift[g].AMOUNT|string_format:"%.2f"} {$translate_billing_gift_note_2}
											</td>
										</tr>
									</table>
									{/section}
								</td>
							</tr>
						</table>
</body>
</html>