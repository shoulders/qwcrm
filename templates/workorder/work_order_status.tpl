<!-- status.tpl-->
<table class="olotable" width="100%" cellpadding="3" cellspacing="0" >
							<tr>
								<td class="olohead">
									<table width="100%" cellpadding="0" cellspacing="0" border="0">
										<tr>
											<td class="menuhead2" width="80%">{$translate_workorder_history_title}</td>
											
										</tr>
									</table>
								</td>
							</tr>
							{section name=c loop=$work_order_status}
							<tr>
							{if $hide_work_order_status == 1}
							{else}
								<td class="menutd">
									<table width="100%" cellpadding="4" cellspacing="0" border="0">
										<tr>
											<td>
												<b>{$translate_workorder_enter_by} </b><a href="?page=employees:employee_details&employee_id={$work_order_status[c].WORK_ORDER_STATUS_ENTER_BY}&page_title={$translate_workorder_employee} {$work_order_status[c].EMPLOYEE_DISPLAY_NAME}">{$work_order_status[c].EMPLOYEE_DISPLAY_NAME}</a> 
												<b>{$translate_workorder_date} </b>{$work_order_status[c].WORK_ORDER_STATUS_DATE|date_format:"$date_format %r"}<br>
												{$work_order_status[c].WORK_ORDER_STATUS_NOTES}</p>
											</td>
										</tr>
									</table>
								</td>
							{/if}
							</tr>
							{/section}
						</table>