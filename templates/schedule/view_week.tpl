<!-- Schedule -->
<table width="100%" border="0" cellpadding="20" cellspacing="5">
	<tr>
		<td>
			{section name=a loop=$arr}
			<table width="700" cellpadding="4" cellspacing="0" border="0" >
				<tr>
					<td class="menuhead2" width="80%">&nbsp;Scheduled range from the {$arr[a].SCHEDULE_START|date_format:"$date_format %r"} to {$arr[a].SCHEDULE_END|date_format:"$date_format %r"}
					</td>
				</tr><tr>
					<td class="menutd2">
						<table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
							<tr>
								<td class="menutd">
									<table width="100%" celpadding="5" cellspacing="5">
										<tr>
											<td >
											<b>{$translate_schedule_start} </b>{$arr[a].SCHEDULE_START|date_format:"%H:%M %p"} <br><b>{$translate_schedule_end} </b>{$arr[a].SCHEDULE_END|date_format:"%H:%M %p"}<br>
											{$arr[a].SCHEDULE_NOTES}<br>
											<b>{$translate_schedule_tech}</b> {$arr[a].EMPLOYEE_DISPLAY_NAME}
											<br>
											<a href="?page=schedule:edit&sch_id={$arr[a].SCHEDULE_ID}&y={$y}&m={$m}&d={$d}">{$translate_schedule_edit}</a> <a href="?page=schedule:delete&sch_id={$arr[a].SCHEDULE_ID}&y={$y}&m={$m}&d={$d}">{$translate_schedule_delete}</a> <a href="?page=workorder:view&wo_id={$arr[a].WORK_ORDER_ID}">View Work Order</a>
											{/section}
											
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
