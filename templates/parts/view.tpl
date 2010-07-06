<!-- parts order-->
<table width="100%" border_details="0" cellpadding="20" cellspacing="5">
	<tr>
		<td>
			{section name=q loop=$order}
			<table width="700" cellpadding="4" cellspacing="0" border_details="0" >
				<tr>
					<td class="menuhead2" width="80%">&nbsp;{$translate_part_order_num} {$order[q].ORDER_ID}</td>
					<td class="menuhead2" width="20%" align="right" valign="middle"></td>
				</tr><tr>
					<td class="menutd2" colspan="2">
						
						<table width="100%" class="olotable" cellpadding="5" cellspacing="0" border_details="0" >
							<tr>
								<td class="menutd" valign="top" >
										<table  width="700" border_details="0" cellpadding="0" cellspacing="0" >
											<tr>
												<td valign="top">
													<!-- Left Column -->
													<font size="+2">MyIT CRM CRM</font><br>
				????????<br>
				????????<br>
				????????<br>
													<a href="?page=parts:print_results&wo_id={$order[q].WO_ID}&escape=1" target="new">{$translate_parts_print}</a>
												</td>
												<td valign="top" align="right" width="205">
												<!-- Right Column -->
												
												<table width="205" border_details="1" cellpadding="3" cellspacing="0" class="olotd5">
													<tr>
														<td><b>{$translate_parts_crm_order_id}</b> {$order[q].INVOICE_ID}<br>
															<b>{$translate_parts_date}</b> {$order[q].DATE_CREATE|date_format:"$date_format"}<br>
															<b>{$translate_parts_total}</b> ${$order[q].TOTAL}<br>
															<b>{$translate_parts_total_items}</b> {$order[q].ITEMS}<br>
															<b>{$translate_parts_weight}</b> {$order[q].WEIGHT} lbs<br>
															<b>{$translate_parts_tracking}</b>{if $order[q].TRACKING_NO == '0'}
															<a href="?page=parts:tracking&invoice_id={$order[q].INVOICE_ID}&order_id={$order[q].ORDER_ID}">Get Tracking</a>{else}
															{$order[q].TRACKING_NO}
														{/if}
														</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
									<br>
									<table  width="700" border_details="0" cellpadding="3" cellspacing="0" >
										<tr>
											<td valign="top" width="10%" align="right"><b>{$translate_parts_ship_to}</b></td>
											<td valign="top">
													<font size="+2">{$company_name}</font><br>
													{$company_address}<br>
													{$company_city}, {$company_state} {$company_zip}<br>
													{$company_phone}<br>
											</td>
											<td valign="top" align="right" width="200">
												<table width="200" border_details="1" cellpadding="5" cellspacing="0" class="olotd5">
													<tr>
														<td>
															<b>{$translate_parts_wo_id}</b> {$order[q].WO_ID}<br>
															<b>{$translate_parts_tech}</b> {$display_login}
														</td>
													</tr>
												</table>
										</tr>
									</table>
									<br>
									<table width="700" border_details="1" cellpadding="0" cellspacing="0" class="olotd5">
										<tr>
											<td align="center"><font size="+2">{$translate_parts_cap_invoice}</font></td>
										</tr>
									</table>
									<br>
									<b>Parts</b>
									<table width="700" border_details="1" cellpadding="3" cellspacing="0" class="olotable">
										<tr>
											<td class="olohead" width="40"><b>{$translate_parts_sku}</b></td>
											<td class="olohead" width="40"><b>{$translate_parts_count}</b></td>
											<td class="olohead"><b>{$translate_parts_description}</b></td>
											<td class="olohead" width="40"><b>{$translate_parts_vendor}</b></td>
											<td class="olohead" width="40"><b>{$translate_parts_amount}</b></td>	
											<td class="olohead" width="80"><b>{$translate_parts_sub_total}</b></td>
										</tr>
											{section name=w loop=$order_details}		
											<tr >
												<td class="olotd4" width="40"><b>{$order_details[w].SKU}</b></td>
												<td class="olotd4">{$order_details[w].COUNT}</td>
												<td class="olotd4">{$order_details[w].INVOICE_PARTS_DESCRIPTION}</td>
												<td class="olotd4">{$order_details[w].INVOICE_PARTS_MANUF}</td>
												<td class="olotd4" align="right">${$order_details[w].PRICE|string_format:"%.2f"}</td>
												<td class="olotd4" align="right">${$order_details[w].SUB_TOTAL|string_format:"%.2f"}</td>
											</tr>	
										{/section}
									</table>
									<br>
									<table width="700" border_details="0" cellpadding="3" cellspacing="0" >
										<tr>
											<td align="right">
												<table width="200" border_details="1" cellpadding="3" cellspacing="0" class="olotd4">
													<tr>
												
															<td><b>{$translate_parts_sub_total}</b></td>
															<td width="80" align="right">$<b>{$order[q].SUB_TOTAL|string_format:"%.2f"}</b></td>
													</tr><tr>
															<td><b>{$translate_parts_shipping}</b></td>
															<td width="80" align="right">$<b>{$order[q].SHIPPING|string_format:"%.2f"}</b></td>
													</tr><t>
															<td><b>{$translate_parts_tax}</b></td>
															<td width="80" align="right"><b>${$invoice_details.TAX|string_format:"%.2f"}</b></td>
													</tr><t>
															<td><b>{$translate_parts_total}</b></td>
															<td width="80" align="right">$<b>{$order[q].TOTAL|string_format:"%.2f"}</b></td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
									<br>
									<table width="700" border_details="1" cellpadding="3" cellspacing="0" class="olotd5">
										<tr>
											<td ><font size="-1">{$translate_parts_msg_11}</font></td>
										</tr>
									</table>
									<br>
									<br>
								</td>
							</tr>
						</table>
					{/section}
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>



