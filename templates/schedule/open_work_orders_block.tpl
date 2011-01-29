<!-- Open Work Orders Block -->
<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td class="menuhead2" width="80%">&nbsp;Open Work Orders</td>
		<td class="menuhead2" width="20%" align="right" valign="middle">
			<table cellpadding="2" cellspacing="2" border="0">
				<tr>
					<td width="33%" align="center" class="button">
						<a href=""  class="button" onClick="document.cookie='hide_open_work_order=1; path=/'">-</a></td>
					<td width="33%" align="center" class="button "> 
						<a href="" class="button" onClick="document.cookie='hide_open_work_order=0; path=/'">+</a></td>
					<td width="33%" align="center" class="button">
						<img src="images/icons/16x16/help.gif" border="0" 
							onMouseOver="ddrivetip('<b>Navagation</b><hr><p>Double Click on an empty space in each row to go directly to the work order. <br><br>Hover over the magnifying glass under Customer to view the Quick Contact Information. Click on the Customers name to view the customers details.<br><br>Click on the status of each work order listed to update the curent work order status.<br><br>Hover over the Magnifying Glass under the Employee to view the Quick Contact Information for the assigned employee. Click on the employees name to view the details.<br><br>Under Action click the Printer Icon to print the work order. Click the mMagnifying Glass to view the work order.</p>')" 
							onMouseOut="hideddrivetip()"></td>
				</tr>
			</table>
		</td>
	</tr><tr>
		<td colspan="2">
			<table class="olotable" width="100%" border="0" cellpadding="4" cellspacing="0">
				<tr>
					<td class="row3"><b>ID</b></td>
					<td class="row3"><b>Opened</b></td>
					<td class="row3"><b>Customer</b></td>
					<td class="row3"><b>Scope</b></td>
					<td class="row3"><b>Status</b></td>
					<td class="row3"><b>Tech</b></td>
					<td class="row3"><b>Action</b></td>
				</tr>
				{foreach from=$open_workorders item=open_workorders}
				{if $open_workorders.WORK_ORDER_ID  != ""}
				<tr onmouseover="this.className='row2'" onmouseout="this.className='row1'" onDblClick="window.location='?page=workorder:view&wo_id={$open_workorders.WORK_ORDER_ID}&customer_id={$open_workorders.CUSTOMER_ID}&page_title=Work Order ID {$open_workorders.WORK_ORDER_ID}';" class="row1">
					<td class="olotd4"><a href="?page=workorder:view&wo_id={$open_workorders.WORK_ORDER_ID}&customer_id={$open_workorders.CUSTOMER_ID}&page_title=Work Order ID {$open_workorders.WORK_ORDER_ID}">{$open_workorders.WORK_ORDER_ID}</a></td>
					<td class="olotd4"> {$open_workorders.WORK_ORDER_OPEN_DATE|date_format:"$date_format"}</td>
					<td class="olotd4" nowrap>
						<img src="images/icons/16x16/view.gif" border="0" onMouseOver="ddrivetip('<b><center>Contact Info</b></center><hr><b>Phone: </b>{$open_workorders.CUSTOMER_PHONE}<br> <b>Work: </b>{$open_workorders.CUSTOMER_WORK_PHONE}<br><b>Moile: </b>{$open_workorders.CUSTOMER_MOBILE_PHONE}<br><br>{$open_workorders.CUSTOMER_ADDRESS}<br>{$open_workorders.CUSTOMER_CITY}, {$open_workorders.CUSTOMER_STATE}<br>{$open_workorders.CUSTOMER_ZIP}')" onMouseOut="hideddrivetip()">
						<a class="link1" href="?page=customer:customer_details&customer_id={$open_workorders.CUSTOMER_ID}&page_title={$open_workorders.CUSTOMER_DISPLAY_NAME}">{$open_workorders.CUSTOMER_DISPLAY_NAME}</a>
					</td>
					<td class="olotd4" nowrap>
					{$open_workorders.WORK_ORDER_SCOPE}</td>
					<td class="olotd4">{$open_workorders.WORK_ORDER_CURRENT_STATUS}</td>
					<td class="olotd4" nowrap>
						<img src="images/icons/16x16/view.gif" border="0" onMouseOver="ddrivetip('<center><b>Contact</b></center><hr><b>Work: </b>{$open_workorders.EMPLOYEE_WORK_PHONE}<br><b>Mobile: </b>{$open_workorders.EMPLOYEE_MOBILE_PHONE}<br><b>Home: </b>{$open_workorders.EMPLOYEE_HOME_PHONE}')"onMouseOut="hideddrivetip()"><a  href="?page=employees:employee_details&employee_id={$open_workorders.EMPLOYEE_ID}&page_title={$open_workorders.EMPLOYEE_DISPLAY_NAME}">{$open_workorders.EMPLOYEE_DISPLAY_NAME}</a>
					</td>
					<td class="olotd4" align="center" nowrap>
						<a href="?page=workorder:print&wo_id={$open_workorders.WORK_ORDER_ID}&customer_id={$open_workorders.CUSTOMER_ID}&page_title=Print Work Order ID {$open_workorders.WORK_ORDER_ID}&escape=1"><img src="images/icons/16x16/fileprint.gif" border="0" onMouseOver="ddrivetip('Print The Work Order')" onMouseOut="hideddrivetip()"></a>
						<a href="?page=workorder:view&wo_id={$open_workorders.WORK_ORDER_ID}&customer_id={$open_workorders.CUSTOMER_ID}&page_title=Work Order ID {$open_workorders.WORK_ORDER_ID}"><img src="images/icons/16x16/viewmag.gif"  border="0" onMouseOver="ddrivetip('View The Work Order')" onMouseOut="hideddrivetip()"></a>										
					</td>
				</tr>
				{else}
					<tr>
						<td colspan="7" class="error">There are No Open Work Orders</td>
					</tr>
				{/if}
				{/foreach}
			</table>
		</td>
	</tr>
	{/if}
</table>	
