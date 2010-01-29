<table class="olotable" border="0" width="100%" cellpadding="0" cellspacing="0" >
							<tr>
								<td class="olohead">
								<table width="100%" cellpadding="0" cellspacing="0" border="0">
										<tr>
											<td class="menuhead2" width="80%">&nbsp;{$translate_workorder_notes}</td>
											<td class="menuhead2" width="20%" align="right">
												<table cellpadding="2" cellspacing="2" border="0">
												<tr> 
													<td width="33%" align="right" >
														
														<a href="?page=workorder:new_note&wo_id={$single_workorder_array[i].WORK_ORDER_ID}&page_title={$translate_workorder_new_note_title}"><img src="images/icons/16x16/small_new_work_order.gif" border="0" onMouseOver="ddrivetip('New Note')" onMouseOut="hideddrivetip()"></a>
													
													</td>
												</tr>
												</table>
												</a>
											</td>
										</tr>
									</table> 	  
								</td>
							</tr><tr>
							{if $hide_work_order_notes == 1}
							{else}
								<td class="menutd">
									<table width="100%" cellpadding="4" cellspacing="0" border="0">
										<tr>
											<td>
												{section name=b loop=$work_order_notes}
													<b>{$translate_workorder_enter_by} </b>{$work_order_notes[b].EMPLOYEE_DISPLAY_NAME}  <b>{$translate_workorder_date} </b> {$work_order_notes[b].WORK_ORDER_NOTES_DATE|date_format:"$date_format"}<br>
													{$work_order_notes[b].WORK_ORDER_NOTES_DESCRIPTION}
													</p>
												{/section}
											</td>
										</tr>
									</table>
								</td>
							{/if}	
							</tr>
							
						</table>