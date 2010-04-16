<!-- Display Work Order Discription -->
						<table class="olotable" width="100%" border="0" cellpadding="0" cellspacing="0" summary="Work order display">
							<tr>
								<td class="olohead">
									<table width="100%" cellpadding="0" cellspacing="0" border="0">
										<tr>
											<td class="menuhead2" width="80%">&nbsp;{$translate_workorder_description_title}</td>
											<td class="menuhead2" width="20%" align="right">
												<table cellpadding="2" cellspacing="2" border="0">
												<tr>
													<td width="33%" align="right">
														{if $single_workorder_array[i].WORK_ORDER_STATUS != 6}
														<a href="?page=workorder:edit_description&wo_id={$single_workorder_array[i].WORK_ORDER_ID}&page_title={$translate_workorder_edit_title}">
														<img src="images/icons/16x16/small_edit.gif" alt="" border="0" onMouseOver="ddrivetip('Edit Description')" onMouseOut="hideddrivetip()"></a>
														{/if}
													</td>
												</tr>
												</table>
											</td>
										</tr>
									</table>
								</td>
							</tr><tr>
								{if $hide_work_order_description == 1 }
								{else}
								<td class="olotd4">
									<table width="100%" cellspacing="0" cellpadding="4">
										<tr>
											<td>
												{$single_workorder_array[i].WORK_ORDER_DESCRIPTION }<br>
											</td>
										</tr>
									</table>
								</td>
								{/if}
							</tr>
						</table>
						<br>