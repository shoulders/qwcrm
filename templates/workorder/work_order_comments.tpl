<table class="olotable" width="100%" border="0"  cellpadding="0" cellspacing="0" summary="Work order display">
							<tr>
								<td class="olohead">
									<table width="100%" cellpadding="0" cellspacing="0" border="0">
										<tr>
											<td class="menuhead2" width="80%">&nbsp;{$translate_workorder_comments_title}</td>
											<td class="menuhead2" width="20%" align="right">
												<table cellpadding="2" cellspacing="2" border="0">
												<tr>
													<td width="33%" align="right"> 
														
														<a href="?page=workorder:edit_comment&wo_id={$single_workorder_array[i].WORK_ORDER_ID}&page_title={$translate_workorder_edit_comments}"><img src="images/icons/16x16/small_edit.gif" border="0" onMouseOver="ddrivetip('Edit Comment')" onMouseOut="hideddrivetip()"></a>
														
													</td>
												</tr>
												</table>
											</td>
										<tr>
									</table>
									
								</td>
							</tr><tr>
							{if $hide_work_order_comment == 1}
							{else}
								<td class="menutd">
									<table width="100%" cellpadding="4" cellspacing="0">
										<tr>
											<td>{$single_workorder_array[i].WORK_ORDER_COMMENT}<br></td>
										</tr>
									</table>	
								</td>
							{/if}
							</tr>
						</table>