<!-- Print Work Order -->
{literal}
<script type="text/javascript">
function data_copy()
{

if(document.form1.copy[0].checked){
document.form1.tt1.value=document.form1.t1.value;
document.form1.ddt1.value=document.form1.dt1.value;
document.form1.tt2.value=document.form1.t2.value;
document.form1.ddt2.value=document.form1.dt2.value;
document.form1.tt3.value=document.form1.t3.value;
document.form1.ddt3.value=document.form1.dt3.value;
document.form1.tt4.value=document.form1.t4.value;
document.form1.ddt4.value=document.form1.dt4.value;
document.form1.tt5.value=document.form1.t5.value;
document.form1.ddt5.value=document.form1.dt5.value;
document.form1.tt6.value=document.form1.t6.value;
document.form1.ddt6.value=document.form1.dt6.value;
document.form1.tt7.value=document.form1.t7.value;
document.form1.ddt7.value=document.form1.dt7.value;
document.form1.bk2.value=document.form1.bk1.value;

}else{
document.form1.tt1.value="";
document.form1.ddt1.value="";
document.form1.tt2.value="";
document.form1.ddt2.value="";
document.form1.tt3.value="";
document.form1.ddt3.value="";
document.form1.tt4.value="";
document.form1.ddt4.value="";
document.form1.tt5.value="";
document.form1.ddt5.value="";
document.form1.tt6.value="";
document.form1.ddt6.value="";
document.form1.tt7.value="";
document.form1.ddt7.value="";
document.form1.bk2.value="";

}

}

</script>
{/literal}
{section name=i loop=$single_workorder_array}
<table  width="100%" border="1" cellpadding="2" cellspacing="0" style="border-collapse: collapse;">
    <tr align="center">
		<!-- right column -->
		<td valign="top" align="center" ><img src="images/logo.jpg" alt="" height="50"></td>
		<!-- middle column -->
		<td valign="top" align="center" width="80%">
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
						    <b>Fax:</b>&nbsp {$company[d].COMPANY_FAX}<br>
                <b>Mobile #:</b>&nbsp {$company[d].COMPANY_MOBILE}<br>
							</td>
					</tr>
				{/section}
			</table>	
			
			<hr>
			<b>Thank You &nbsp</b>{$single_workorder_array[i].CUSTOMER_FIRST_NAME} {$single_workorder_array[i].CUSTOMER_LAST_NAME}<br><br>
                            Thank you for using our service. Your
 			 business is greatly appreciated!

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
                                <tr>
					<td><b>Travel</b></td>
					<td>_________KM/Miles</td>
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
        </tr><tr>
					<td>
                                            Comments:<br>_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _
                                          <br>
                                          <br>_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _
                                          <br>
                                          <br>_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _
                                          <br>
                                          <br>_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _
                                          <br>
                                          </td>
				</tr>
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
{/section}
<h2 align="center" style="page-break-after: always;" >Please select which hardware/peripherials have been received.</h2>
<form name=form1 method=post action=''>
<table id="test" width="100%" align="center" border="0" cellpadding="2" cellspacing="0" style="border-collapse: collapse;">
    <tr>
        <td colspan="1" align="right" valign="top" >
            <input type="text" value="PC Tower/Laptop:" align="right" readonly><br><br>
            <input type="text" value="Power Cords/Supply:" readonly><br><br>
            <input type="text" value="Software/Discs:" readonly><br><br>
            <input type="text" value="Mice:" readonly><br><br>
            <input type="text" value="Modem/Router:" readonly><br><br>
            <input type="text" value="Printer(s):" readonly><br><br>
            <input type="text" value="Other(s):" readonly><br><br>
        </td>
        <td colspan="2" align="left" valign="top" >
            Qty:<input type="text" size="3" name="t1">&nbsp;&nbsp;Make/Model:<input type="text" size="40" name="dt1"><br><br>
            Qty:<input type="text" size="3" name="t2">&nbsp;&nbsp;Description(s):<input type="text" size="40" name="dt2"><br><br>
            Qty:<input type="text" size="3" name="t3">&nbsp;&nbsp;Description(s):<input type="text" size="40" name="dt3"><br><br>
            Qty:<input type="text" size="3" name="t4">&nbsp;&nbsp;Make/Model:<input type="text" size="40" name="dt4"> <br><br>
            Qty:<input type="text" size="3" name="t5">&nbsp;&nbsp;Make/Model:<input type="text" size="40" name="dt5"><br><br>
            Qty:<input type="text" size="3" name="t6">&nbsp;&nbsp;Make/Model:<input type="text" size="40" name="dt6"><br><br>
            Qty:<input type="text" size="3" name="t7">&nbsp;&nbsp;Description:<input type="text" size="40" name="dt7"><br>
            <br><input type=radio name=copy value='yes' onclick="data_copy()";>Copy Data to Customers Copy
            <br><input type=hidden name=copy value='no' onclick="data_copy()";>
            
        </td>
    </tr>

</table>
<table align="center" width="100%">

    <tr>
        <td>
            <font><b>If backup required, Backup was done to__________________________________ </b></font>
        </td>
    </tr>
    <tr>
        <td>
            Data Backup required?<select name="bk1">
                <option value="Yes">Yes</option>
                <option value="No" selected>No</option>
            </select><br><br>
            
        </td>
    </tr>
</table>
<hr align="center" noshade style="page-break-after: always;">
<!-- Work Order Customers Copy -->
{section name=i loop=$single_workorder_array}
<table  width="100%" border="1" cellpadding="2" cellspacing="0" style="border-collapse: collapse;">
    <tr>
        <!-- right column -->
        <td valign="top" align="center" ><img src="images/logo.jpg" alt="" height="50"></td>
        <!-- middle column -->
        <td valign="top" align="center">
            <font size="+3">Customer Workorder Slip</font><br>
			Work Order ID# {$single_workorder_array[i].WORK_ORDER_ID}
        </td>
    </tr><tr>
        <!-- left Column -->
        <td valign="top" align="center" nowrap><b>Customer Details</b></td>
        <!-- Center Column -->
        <td valign="top" align="center" nowrap><b>Service Details</b></td>
        <!-- right column -->

    </tr><tr>
        <!-- left Column -->
        <td valign="top" width="20%">
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
        </td>
        <!-- Center Column -->
        <td valign="top" width="100%">
            <table border="0" cellpadding="4" cellspacing="0">
                <tr>
                    <td valign="top" nowrap><b>Scope:&nbsp;</b>{$single_workorder_array[i].WORK_ORDER_SCOPE}</td>
                </tr>
                <tr>
                    <td><b>Description:</b></td>
                </tr>
                <tr>
                    <td>{$single_workorder_array[i].WORK_ORDER_DESCRIPTION }</td>
                </tr>
                {if $single_workorder_array[i].WORK_ORDER_COMMENT != ""}
                <tr>
                    <td><b>Comments:</b></td>
                </tr>
                <tr>
                    <td>{$single_workorder_array[i].WORK_ORDER_COMMENT}</td>
                </tr>{/if}
                <tr>
                    <td valign="top" nowrap>This Work Order was created on the &nbsp;{$single_workorder_array[i].WORK_ORDER_OPEN_DATE|date_format:"$date_format"}
                        &nbsp;and has the status of{if $single_workorder_array[i].WORK_ORDER_CURRENT_STATUS == "1"}
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
                        .&nbsp;<br>This work order has been assigned to {if $single_workorder_array[i].EMPLOYEE_DISPLAY_NAME ==""}
							our next available technician
						{else}
							{$single_workorder_array[i].EMPLOYEE_DISPLAY_NAME}
						{/if}



                    </td>
                </tr>
    {if $single_workorder_array[i].WORK_ORDER_NOTES_DESCRIPTION != ""}
    <tr>
        <td>{section name=b loop=$work_order_notes}
            <p><b>Service Notes</b>
                <br>{$work_order_notes[b].WORK_ORDER_NOTES_DESCRIPTION}<br><br>
    </tr>{/section}{/if}


            </table>
</td>
</tr>



</table>
{/section}
<h2 align="center" >Please select which hardware/peripherials have been received.</h2>
<table width="100%" align="center" border="0" cellpadding="2" cellspacing="0" style="border-collapse: collapse;">
    <tr>
        <td colspan="1" align="right" valign="top" >
            <input type="text" value="PC Tower/Laptop:" align="right" readonly><br><br>
            <input type="text" value="Power Cords/Supply:" readonly><br><br>
            <input type="text" value="Software/Discs:" readonly><br><br>
            <input type="text" value="Mice:" readonly><br><br>
            <input type="text" value="Modem/Router:" readonly><br><br>
            <input type="text" value="Printer(s):" readonly><br><br>
            <input type="text" value="Other(s):" readonly><br><br>
        </td>
        <td colspan="2" valign="top" >
            Qty:<input type="text" size="3" name="tt1" readonly>&nbsp;&nbsp;Make/Model:<input type="text" size="40" name="ddt1" readonly><br><br>
            Qty:<input type="text" size="3" name="tt2" readonly>&nbsp;&nbsp;Description(s):<input type="text" size="40" name="ddt2" readonly><br><br>
            Qty:<input type="text" size="3" name="tt3" readonly>&nbsp;&nbsp;Description(s):<input type="text" size="40" name="ddt3" readonly><br><br>
            Qty:<input type="text" size="3" name="tt4" readonly>&nbsp;&nbsp;Make/Model:<input type="text" size="40" name="ddt4" readonly> <br><br>
            Qty:<input type="text" size="3" name="tt5" readonly>&nbsp;&nbsp;Make/Model:<input type="text" size="40" name="ddt5" readonly><br><br>
            Qty:<input type="text" size="3" name="tt6" readonly>&nbsp;&nbsp;Make/Model:<input type="text" size="40" name="ddt6" readonly><br><br>
            Qty:<input type="text" size="3" name="tt7" readonly>&nbsp;&nbsp;Description:<input type="text" size="40" name="ddt7" readonly><br>

        </td>
    </tr>

</table>

<br>
<table align="center" width="100%">

    <tr>
        <td>
            <font><b>NOTE: We have a duty of care to preserve you computers data whilst we are servicing it however, it is the customers responsibilty to ensure that this data is reliably backed up should data loss occur. </b></font>
        </td>
    </tr>
    <tr>
        <td>
            Data Backup required?<input type="text" size="3" name="bk2" readonly><br><br>
        </td>
    </tr>

    <tr>
        <td><h3>Important Notes</h3>
    <ul>
        <li>Please hold onto this receipt as proof of service request</li>
        <li>This document (copies will not be accepted) MUST be produce this at time of pickup. If this can't be provided then photo identification is required.</li>


    </ul>

        </td>
    </tr>
</table>
</form>
<table width="600" align="center">
     <tr>{section name=d loop=$company}
         <td><font size="-2"<b>{$company[d].COMPANY_NAME} -</b>{$company[d].COMPANY_ADDRESS} ,{$company[d].COMPANY_CITY}, {$company[d].COMPANY_STATE} {$company[d].COMPANY_ZIP}
                <b>PH:</b>&nbsp;{$company[d].COMPANY_PHONE}
                {if $company[d].COMPANY_TOLL_FREE !=""}
                <b>Toll Free:</b>&nbsp;{$company[d].COMPANY_TOLL_FREE}
                {/if}
                {if $company[d].COMPANY_MOBILE !=""}
                <b>Mobile:</b>&nbsp;{$company[d].COMPANY_MOBILE}
                    {/if}
             </font></td>
                    </tr>

				{/section}

</table>
