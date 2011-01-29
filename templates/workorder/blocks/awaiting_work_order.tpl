<!-- Open Work Orders Block -->
<b>{$translate_workorder_waiting_parts_title}</b>
<table class="olotable" width="100%" border="0" cellpadding="4" cellspacing="0">
	<tr>
		<td class="olohead" width="6"><b>{$translate_workorder_id}</b></td>
		<td class="olohead"><b>{$translate_workorder_opened}</b></td>
		<td class="olohead"><b>{$translate_workorder_customer}</b></td>
		<td class="olohead"><b>{$translate_workorder_scope}</b></td>
		<td class="olohead"><b>{$translate_workorder_status}</b></td>
		<td class="olohead"><b>{$translate_workorder_tech}</b></td>
		<td class="olohead"><b>{$translate_workorder_action}</b></td>
	</tr>
				{foreach from=$awaiting item=awaiting}
				{if $awaiting.WORK_ORDER_ID > 0 }
				<tr onmouseover="this.className='row2'" onmouseout="this.className='row1'" onDblClick="window.location='?page=workorder:view&wo_id={$awaiting.WORK_ORDER_ID}&customer_id={$awaiting.CUSTOMER_ID}&page_title={$translate_workorder_page_title} {$awaiting.WORK_ORDER_ID}';" class="row1">
					<td class="olotd4"><a href="?page=workorder:view&wo_id={$awaiting.WORK_ORDER_ID}&customer_id={$awaiting.CUSTOMER_ID}&page_title={$translate_workorder_page_title} {$awaiting.WORK_ORDER_ID}">{$awaiting.WORK_ORDER_ID}</a></td>
					<td class="olotd4"> {$awaiting.WORK_ORDER_OPEN_DATE|date_format:"$date_format"}</td>
					<td class="olotd4" nowrap>
						<img src="images/icons/16x16/view.gif" border="0" onMouseOver="ddrivetip('<b><center>Contact Info</b></center><hr><b>Phone: </b>{$awaiting.CUSTOMER_PHONE}<br> <b>Work: </b>{$awaiting.CUSTOMER_WORK_PHONE}<br><b>Moile: </b>{$awaiting.CUSTOMER_MOBILE_PHONE}<br><br>{$awaiting.CUSTOMER_ADDRESS}<br>{$awaiting.CUSTOMER_CITY}, {$awaiting.CUSTOMER_STATE}<br>{$awaiting.CUSTOMER_ZIP}')" onMouseOut="hideddrivetip()">
						<a class="link1" href="?page=customer:customer_details&customer_id={$awaiting.CUSTOMER_ID}&page_title={$awaiting.CUSTOMER_DISPLAY_NAME}">{$awaiting.CUSTOMER_DISPLAY_NAME}</a>
					</td>
					<td class="olotd4" nowrap>
					{$awaiting.WORK_ORDER_SCOPE}</td>
					<td class="olotd4">{$awaiting.CONFIG_WORK_ORDER_STATUS}</td>
					<td class="olotd4" nowrap>
					{if $awaiting.EMPLOYEE_DISPLAY_NAME == ""}
						{$translate_workorder_not_assigned}
					{else}
						<img src="images/icons/16x16/view.gif" border="0" onMouseOver="ddrivetip('<center><b>Contact</b></center><hr><b>Work: </b>{$awaiting.EMPLOYEE_WORK_PHONE}<br><b>Mobile: </b>{$awaiting.EMPLOYEE_MOBILE_PHONE}<br><b>Home: </b>{$awaiting.EMPLOYEE_HOME_PHONE}')"onMouseOut="hideddrivetip()"> 
						<a class="link1" href="?page=employees:employee_details&employee_id={$awaiting.EMPLOYEE_ID}&page_title={$awaiting.EMPLOYEE_DISPLAY_NAME}">{$awaiting.EMPLOYEE_DISPLAY_NAME}</a>
					{/if}
					</td>
					<td class="olotd4" align="center" nowrap>
						<a href="?page=workorder:print&wo_id={$awaiting.WORK_ORDER_ID}&customer_id={$awaiting.CUSTOMER_ID}&page_title=$translate_workorder_print_title} {$awaiting.WORK_ORDER_ID}&escape=1"><img src="images/icons/16x16/fileprint.gif" border="0" onMouseOver="ddrivetip('Print The Work Order')" onMouseOut="hideddrivetip()"></a>
						<a href="?page=workorder:view&wo_id={$awaiting.WORK_ORDER_ID}&customer_id={$awaiting.CUSTOMER_ID}&page_title={$translate_workorder_page_title} {$awaiting.WORK_ORDER_ID}"><img src="images/icons/16x16/viewmag.gif"  border="0" onMouseOver="ddrivetip('View The Work Order')" onMouseOut="hideddrivetip()"></a>										
					</td>
				</tr>
				{else}
				<tr>
					<td colspan="7" class="error">{$translate_workorder_msg_4}</td>
				</tr>
			{/if}
				{/foreach}
			
			</table>

