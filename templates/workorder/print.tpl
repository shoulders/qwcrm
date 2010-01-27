<!-- Print Work Order -->
{section name=i loop=$single_workorder_array}
<table  width="900" border="1" cellpadding="2" cellspacing="0" style="border-collapse: collapse;">
	<tr>		
		<!-- right column -->
		<td valign="top" align="center" ><img src="images/logo.jpg" height="50"></td>
		<!-- middle column -->
		<td valign="top" align="center" width="200">
			<font size="+3">TECHNICIAN COPY</font><br>
			Work Order ID# {$single_workorder_array[i].WORK_ORDER_ID}
		</td>
	</tr><tr>
		<!-- left Column -->
		<td valign="top" align="center" nowrap><b>Service Location</b></td>
		<!-- Center Column -->
		<td valign="top" align="center" nowrap><b>Service Details</b></td>
		<!-- right column -->
		<td valign="top" align="center" nowrap><b>Summary</b></td>
	</tr><tr>
		<!-- left Column -->
		<td valign="top" width="20%">
			<table border="0" cellpadding="4" cellspacing="0">
				<tr>
					<td><b>{$single_workorder_array[i].CUSTOMER_DISPLAY_NAME}</b></td>
				</tr>
			</table>
			<table width="100%" cellpadding="4" cellspacing="0" border="0">
				<tr>
					<td>{$single_workorder_array[i].CUSTOMER_FIRST_NAME} {$single_workorder_array[i].CUSTOMER_LAST_NAME}
					<br>{$single_workorder_array[i].CUSTOMER_ADDRESS}<br>
							{$single_workorder_array[i].CUSTOMER_CITY}, {$single_workorder_array[i].CUSTOMER_STATE} {$single_workorder_array[i].CUSTOMER_ZIP}
					</td>
				</tr><tr>
					<td><b>Home:</b> {$single_workorder_array[i].CUSTOMER_PHONE}<br>
						 <b>Work:</b> {$single_workorder_array[i].CUSTOMER_WORK_PHONE}<br>
						<b>Mobile:</b> {$single_workorder_array[i].CUSTOMER_MOBILE_PHONE}
					</td>
				</tr><tr>
					<td><b>Email:</b> {$single_workorder_array[i].CUSTOMER_EMAIL}<br>
				</tr>
			</table>
			<!--OLD LINE-->
			<hr>
			<table width="100%" cellpadding="4" cellspacing="0" border="0">
				<tr>
					<td><b>Company Contact</b></td>
				</tr>
				{section name=d loop=$company}
					<tr>
						<td>{$company[d].COMPANY_NAME}<br>
						    {$company[d].COMPANY_ADDRESS}<br>
							{$company[d].COMPANY_CITY}, {$company[d].COMPANY_STATE} {$company[d].COMPANY_ZIP}</td>
					</tr><tr>
						<td>
						</td>
					</tr><tr>
						<td><b>Phone Numbers<br>
                  Primary:</b>&nbsp {$company[d].COMPANY_PHONE}<br>                  
						    <b>Toll Free:</b>&nbsp {$company[d].COMPANY_TOLL_FREE}<br>
                <b>Mobile #:</b>&nbsp {$company[d].COMPANY_MOBILE}<br>
							</td>
					</tr>
				{/section}
			</table>	
			
			<hr>
			<p><center><b>Thank You &nbsp</b>{$single_workorder_array[i].CUSTOMER_FIRST_NAME} {$single_workorder_array[i].CUSTOMER_LAST_NAME}<br><br>Thank you for using our service. Your
 			 business is greatly appreciated!</center></p>

		</td>
		<!-- Center Column -->
		<td valign="top" width="60%">
			<table border="0" cellpadding="4" cellspacing="0">
				<tr>
					<td><b>Description</b></td>
				</tr><tr>
					<td>{$single_workorder_array[i].WORK_ORDER_DESCRIPTION }</td>
				</tr><tr>
					<td><b>Comments</b></td>
				</tr><tr>
					<td>{$single_workorder_array[i].WORK_ORDER_COMMENT}</td>
				</tr><tr>
					<td></td>
				</tr><tr>
					<td>{section name=b loop=$work_order_notes}
							<p><b>Service Notes</b>
					<br>{$work_order_notes[b].WORK_ORDER_NOTES_DESCRIPTION}<br><br>
					<b>Entered By: </b>{$work_order_notes[b].EMPLOYEE_DISPLAY_NAME}  
							<br><b>Date: </b> {$work_order_notes[b].WORK_ORDER_NOTES_DATE|date_format:"$date_format"}<br>
							</p>
						{/section}
					</td>
				</tr>
			</table>
			<hr>
			<table width="100%" cellpadding="4" cellspacing="0" border="0">
				<tr>
					<td align="center"><b>Schedule Details</b></td>
				</tr><tr>
					<td>
						{section name=e loop=$work_order_sched}
							<b>Scheduled Start </b> {$work_order_sched[e].SCHEDULE_START|date_format:"$date_format %I:%M  %p"}<br>
              <b>Scheduled End</b> {$work_order_sched[e].SCHEDULE_END|date_format:"$date_format %I:%M  %p "} <br>
							<b>Schedule Notes</b><br>
								{$work_order_sched[e].SCHEDULE_NOTES}
						{sectionelse}
							No schedule has been set. Click the day on the calendar you want to set the schedule.
						{/section}
					</td>
				</tr>
			</table>
			<hr>
			<table border="0" cellpadding="4" cellspacing="0">
				<tr>
					<td><b>Resolution:</b><br>
					{section name=r loop=$work_order_res}
						{if $work_order_res[r].EMPLOYEE_DISPLAY_NAME != ''}
							<b>Closed By:</b> {$work_order_res[r].EMPLOYEE_DISPLAY_NAME} <b>Date:</b>  {$work_order_res[r].WORK_ORDER_CLOSE_DATE|date_format:"$date_format"}
							{$work_order_res[r].WORK_ORDER_RESOLUTION}
						{/if}
					{/section}
					</td>
				</tr><tr>
					<td><br><br><br><br><br><br><br><br></td>
			</table>
		</td>
		<!-- right column -->
		<td valign="top" width="20%">
			<table border="0" cellpadding="4" cellspacing="0">
				<tr>
					<td valign="top" nowrap><b>Scope:</b></td>
					<td valign="top">{$single_workorder_array[i].WORK_ORDER_SCOPE}</td>
				</tr><tr>
					<td valign="top" nowrap><b>Date Opened:</b></td>
					<td valign="top">{$single_workorder_array[i].WORK_ORDER_OPEN_DATE|date_format:"$date_format"}</td>
				</tr><tr>
					<td valign="top"><b>Status:</b></td>
					<td valign="top">{if $single_workorder_array[i].WORK_ORDER_CURRENT_STATUS == "1"}
							Created
						{elseif $single_workorder_array[i].WORK_ORDER_CURRENT_STATUS == "2"}
							Assigned
						{elseif $single_workorder_array[i].WORK_ORDER_CURRENT_STATUS == "3"}
							Waiting For Parts
						{elseif $single_workorder_array[i].WORK_ORDER_CURRENT_STATUS == "6"}
							Closed
						{elseif $single_workorder_array[i].WORK_ORDER_CURRENT_STATUS == "7"}	
							Waiting For Payment
						{elseif $single_workorder_array[i].WORK_ORDER_CURRENT_STATUS == "8"}	
							Payment Made
						{elseif $single_workorder_array[i].WORK_ORDER_CURRENT_STATUS == "9"}	
							Pending	
						{/if}
					</td>
				</tr><tr>
					<td valign="top"><b>Tech:</b></td>
					<td valign="top">{if $single_workorder_array[i].EMPLOYEE_DISPLAY_NAME ==""}
							Not Assigned
						{else}
							{$single_workorder_array[i].EMPLOYEE_DISPLAY_NAME}
						{/if}
					</td>
				</tr><tr>
					<td><b>Last Changed:</b></td>
					<td>{$single_workorder_array[i].LAST_ACTIVE|date_format:"$date_format"}</td>
				</tr>
			</table>
			<hr>
			<table width="100%" border="0" cellpadding="4" cellspacing="0">
				<tr>
					<td align="center" colspan="2"><b>Service Time</b></td>
				</tr><tr>
					<td><b>Arrival</b></td>
					<td>___/____/____ __:__</td>
				</tr><tr>
					<td><b>Departed</b></td>
					<td>___/____/____ __:__</td>
				</tr>
			</table>
			<hr>
			<table width="100%" border="0" cellpadding="4" cellspacing="0">
				<tr>
					<td align="center"><b>Notes</b></td>
				</tr><tr>
					<td><br><br><br><br><br><br><br><br></td>
				</tr>
			</table>
			<hr>
			<table width="100%" border="0" cellpadding="4" cellspacing="0">
				<tr>
					<td align="center"><b>Feedback</b></td>
				</tr>
        <tr>
				  <td align="center">Please Rate this service 1(poor) & 5(excellent)</td>
        </tr>
        <tr>
				<td align="center"><b>Your rating is &nbsp;&nbsp;&nbsp;1&nbsp;&nbsp;&nbsp;2&nbsp;&nbsp;&nbsp;3&nbsp;&nbsp;&nbsp;4&nbsp;&nbsp;&nbsp;5</b></td>
				</tr>
					<td>Comments:<br>_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _
          <br>
          <br>_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _
          <br>
          <br>_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _
          <br>
          <br>_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _
          <br>
          </td>
				
			</table>
			<hr>
			<table width="100%" border="0" cellpadding="4" cellspacing="0">
				<tr>
					<td align="center" colspan="2"><b>Signature</b></td>
				</tr><tr>
					<td><b>Client Name</b></td>
					<td>__________________</td>
				</tr><tr>
					<td><b>Signature</b></td>
					<td>__________________</td>
				</tr><tr>
					<td><b>Tech Name</b></td>
					<td>__________________</td>
				</tr><tr>
					<td><b>Signature</b></td>
					<td>__________________</td>
				</tr>
			</table>
			<br>
			</td></tr>
</table>
<br>
<table width="900" border="0" cellpadding="4" cellspacing="0">
				<tr>
					<td align="center" ><b>This Work Order is confidential and contains privileged information.</b></td>
				</tr>
</table>
{/section}
