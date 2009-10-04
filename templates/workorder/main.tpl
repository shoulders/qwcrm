<!-- Work order main TPL-->
<table width="100%"   border="0" cellpadding="20" cellspacing="5">
	<tr>
		<td>
			<!-- Begin page -->
			<table width="700" cellpadding="3" cellspacing="0" border="0" >
				<tr>
					<td class="menuhead2" width="80%">{$translate_workorder_title}</td>
					<td class="menuhead2" width="20%" align="right" valign="middle">
					<a <img src="images/icons/16x16/help.gif" border="0" 
							onMouseOver="ddrivetip('<b>Navagation</b><hr><p>Double Click on an empty space in each row to go directly to the work order. <br><br>Hover over the magnifying glass under Customer to view the Quick Contact Information. Click on the Customers name to view the customers details.<br><br>Click on the status of each work order listed to update the curent work order status.<br><br>Hover over the Magnifying Glass under the Employee to view the Quick Contact Information for the assigned employee. Click on the employees name to view the details.<br><br>Under Action click the Printer Icon to print the work order. Click the mMagnifying Glass to view the work order. Click the Red Stop sign to close the work order and start the invoicing.</p>')" 
							onMouseOut="hideddrivetip()"></a>
					</td>
				</tr><tr>
					<td class="menutd2" colspan="2">	
						<table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
							<tr>
								<td class="menutd">
									<table width="100%" border="0" cellpadding="10" cellspacing="0">
										<tr>
											<td><a name="new"></a>{include file="workorder/blocks/new_work_order.tpl"}</td>
										</tr><tr>
											<td><a name="assigned"></a>{include file="workorder/blocks/assigned_work_order.tpl"}</td>
										</tr><tr>
											<td><a name="awaiting"></a>{include file="workorder/blocks/awaiting_work_order.tpl"}</td>
										</tr><tr>
											<td><a name="payment"></a>{include  file="workorder/blocks/payment_work_order.tpl"}</td>
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
	

