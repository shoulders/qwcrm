<table class="olotable" border="0" width="100%" cellpadding="3" cellspacing="0" >
							<tr>
								<td class="olohead">
								<table width="100%" cellpadding="0" cellspacing="0" border="0">
										<tr>
											<td class="menuhead2" width="80%">{$translate_workorder_parts}</td>
											<td class="menuhead2" width="20%" align="right">
												<table cellpadding="2" cellspacing="2" border="0">
												<tr> 
													<td width="33%" align="right" ></td>
												</tr>
												</table>
												</a>
											</td>
										</tr>
									</table> 	  
								</td>
							</tr><tr>
							
								<td class="menutd">
									<table width="100%" cellpadding="1" cellspacing="0" border="0">
										<tr>
											<td>
												{section name=p loop=$order}
													<table width="100%" class="olotable" cellpadding="3" cellspacing="0" border="0">
														<tr>
															<td class="olohead">ID</td>
															<td class="olohead">Invoice</td>
															<td class="olohead">Created</td>
															<td class="olohead">Updated</td>
															<td class="olohead">Sub Total</td>
															<td class="olohead">Shipping</td>
															<td class="olohead">Total</td>
															<td class="olohead">Tracking</td>
															<td class="olohead">Status</td>
														</tr><tr>
															<td class="olotd4"><a href="?page=parts:view&ORDER_ID={$order[p].ORDER_ID}&page_title=Order%20Details%20for%20{$order[p].ORDER_ID}">{$order[p].ORDER_ID}</a></td>
															<td class="olotd4">{$order[p].INVOICE_ID}</td>
															<td class="olotd4">{$order[p].DATE_CREATE|date_format:"$date_format"}</td>
															<td class="olotd4">{$order[p].DATE_LAST|date_format:"$date_format"}</td>
															<td class="olotd4">${$order[p].SUB_TOTAL}</td>
															<td class="olotd4">${$order[p].SHIPPING}</td>
															<td class="olotd4">${$order[p].TOTAL}</td>
															<td class="olotd4">{if $order[p].TRACKING_NO == 0} <a href="">Get Tracking{else} {$order[p].TRACKING_NO} {/if}</td>
															<td class="olotd4">{ if $order[p].STATUS == '1'}
																						Open
																					 {/if}
																					{ if $order[p].STATUS == '0'}	
																						Closed
																					{/if}
															</td>
															
														</tr>
													</table>
												
												{sectionelse}
													No Parts On Order
												{/section}
												
											</td>
										</tr>
									</table>
								</td>

							</tr>
							
						</table>