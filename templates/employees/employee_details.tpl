<!-- Employee Details TPL -->
{section name=i loop=$employee_details}
<table width="100%" border="0" cellpadding="20" cellspacing="0">
	<tr>
		<td><!-- Begin Page -->
			<table width="700" cellpadding="5" cellspacing="0" border="0" >
				<tr>
					<td class="menuhead2" width="80%">&nbsp;{$translate_employee_details_for} {$employee_details[i].EMPLOYEE_DISPLAY_NAME}</td>
					<td class="menuhead2" width="20%" align="right" valign="middle">
							<img src="images/icons/16x16/help.gif" border="0" >
					</td>
				</tr><tr>
					<td class="menutd2" colspan="2">

					<table width="100%" class="olotable" cellpadding="5" cellspacing="0" border="0" >
						<tr>
							<td width="100%" valign="top" >
								<!-- Content Here -->
						<table class="olotable" border="0" cellpadding="2" cellspacing="0" width="100%" summary="Customer Contact">
							<tr>
								<td class="olohead" colspan="4">{$translate_employee_contact_information}</td>
							</tr><tr>						
								<td class="menutd"><b>{$translate_employee_display_name}</b></td>
								<td class="menutd"> {$employee_details[i].EMPLOYEE_FIRST_NAME} {$employee_details[i].EMPLOYEE_LAST_NAME}</td>
								<td class="menutd"><b>{$translate_employee_email}</b></td>
								<td class="menutd"> {$employee_details[i].EMPLOYEE_EMAIL}</td>
							</tr><tr>
								<td class="menutd"><b>{$translate_employee_first_name}</b></td>
								<td class="menutd">{$employee_details[i].EMPLOYEE_FIRST_NAME}</td>
								<td class="menutd"><b>{$translate_employee_last_name}</b>
								<td class="menutd">{$employee_details[i].EMPLOYEE_LAST_NAME}</td>
							</tr><tr>
								<td class="row2" colspan="4">&nbsp;</td>
							</tr><tr>
								<td class="menutd"><b>{$translate_employee_address}</b></td>
							<td class="menutd"></td>
								<td class="menutd"><b>{$translate_employee_home}</b></td>
								<td class="menutd">{$employee_details[i].EMPLOYEE_HOME_PHONE}</td>
							</tr><tr>								
								<td class="menutd" colspan="2">{$employee_details[i].EMPLOYEE_ADDRESS}</td>			
								<td class="menutd"><b>{$translate_employee_work_phone}</b></td>
								<td class="menutd"> {$employee_details[i].EMPLOYEE_WORK_PHONE}</td>
							</tr><tr>
								<td class="menutd"> {$employee_details[i].EMPLOYEE_CITY},</td>
								<td class="menutd">{$employee_details[i].EMPLOYEE_STATE} {$employee_details[i].EMPLOYEE_ZIP}</td>
								<td class="menutd"><b>{$translate_employee_mobile}</b></td>
								<td class="menutd"> {$employee_details[i].EMPLOYEE_MOBILE_PHONE}</td>
							</tr><tr>
								<td class="row2" colspan="4">&nbsp;</td>
							</tr><tr>
								<td class="menutd"><b>{$translate_employee_type}</b></td>
								<td class="menutd"> {$employee_details[i].TYPE_NAME	}</td>
								<td class="menutd"><b>{$translate_employee_login}</b></td>
								<td class="menutd">{$employee_details[i].EMPLOYEE_LOGIN}</td>
							</tr><tr>
								<td class="row2" colspan="4">&nbsp;</td>
							</tr>
						</table>
						<br>
						<!-- Open Work Orders -->
						<table class="menutable" width="100%" border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td class="menuhead">{$employee_details[i].EMPLOYEE_DISPLAY_NAME}'s Assigned Work Orders</td>
							</tr>
						</table>
						{/section}
						<table class="olotable" width="100%" border="0" cellpadding="5">
							<tr>
								<td class="olohead">{$translate_employee_wo_id}</td>
								<td class="olohead">{$translate_employee_date_open}</td>
								<td class="olohead">{$translate_employee_customer}</td>
								<td class="olohead">{$translate_employee_scope}</td>
								<td class="olohead">{$translate_employee_status}</td>
								<td class="olohead">{$translate_employee_manager}</td>
								<td class="olohead">Action</td>
							</tr>
							{section name=a loop=$open_work_orders}
							<tr onmouseover="this.className='row2'" onmouseout="this.className='row1'" onDblClick="window.location='?page=workorder:view&wo_id={$open_work_orders[a].WORK_ORDER_ID}&customer_id={$open_work_orders[a].CUSTOMER_ID}&page_title={$translate_employee_work_order_id}{$open_work_orders[a].WORK_ORDER_ID},';" class="row1">
								<td class="olotd4">{$open_work_orders[a].WORK_ORDER_ID}</td>
								<td class="olotd4">{$open_work_orders[a].WORK_ORDER_OPEN_DATE|date_format:"$date_format"}</td>
								<td class="olotd4">
									<img src="images/icons/16x16/view.gif" border="0" onMouseOver="ddrivetip('<center><b>Customer Contact</b><hr></center><b>Home: </b>{$open_work_orders[a].CUSTOMER_PHONE}<br><b>Work: </b>{$open_work_orders[a].CUSTOMER_WORK_PHONE}<br><b>Mobile: </b>{$open_work_orders[a].CUSTOMER_MOBILE_PHONE}')"
										onMouseOut="hideddrivetip()">{$open_work_orders[a].CUSTOMER_DISPLAY_NAME}
								</td>
								<td class="olotd4">
									<img src="images/icons/16x16/view.gif" border="0" onMouseOver="ddrivetip('<center><b> Description</b><hr></center>{$open_work_orders[a].WORK_ORDER_DESCRIPTION}')" onMouseOut="hideddrivetip()">
									{$open_work_orders[a].WORK_ORDER_SCOPE}
								</td>
								<td class="olotd4">{$open_work_orders[a].CONFIG_WORK_ORDER_STATUS}</td>
								<td class="olotd4">{$open_work_orders[a].EMPLOYEE_DISPLAY_NAME}</td>
								<td class="olotd4" align="center">
									<a href="?page=workorder:print&wo_id={$open_work_orders[a].WORK_ORDER_ID}&customer_id={$open_work_orders[a].CUSTOMER_ID}&escape=1" target="new" >
									<img src="images/icons/16x16/fileprint.gif" border="0"
										onMouseOver="ddrivetip('Print The Work Order')" onMouseOut="hideddrivetip()"></a>
									<a href="?page=workorder:view&wo_id={$open_work_orders[a].WORK_ORDER_ID}&customer_id={$open_work_orders[a].CUSTOMER_ID}">
									<img src="images/icons/16x16/viewmag.gif"  border="0"
										onMouseOver="ddrivetip('View The Work Order')" onMouseOut="hideddrivetip()"></a>
								</td>
							</tr>
							{/section}
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