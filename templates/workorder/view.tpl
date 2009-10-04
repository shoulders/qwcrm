<!-- View Work Order tpl -->
{section name=i loop=$single_workorder_array}
{if $error_msg != ""}
	{include file="core/error.tpl"}
{/if}
<table width="100%" border="0" cellpadding="20" cellspacing="0">
	<tr>
		<td><!-- Begin Page -->
			<table width="700" cellpadding="5" cellspacing="0" border="0" >
				<tr>
					<td class="menuhead2" width="80%">&nbsp;{$translate_workorder_page_title} {$single_workorder_array[i].WORK_ORDER_ID}

					<td class="menuhead2" width="20%" align="right" valign="middle">
						<a href="" target="new"><img src="images/icons/16x16/help.gif" border="0"
						onMouseOver="ddrivetip('<b>Work Orders</b><hr><p>You can edit the Work order commments, notes, and set schedule by clicking the icon on the right of each window<br><br>You can navigate to the customer by clicking their name. Hover over the Magnifing glass by the customer name to view Quick Contact Information. <br><br>If you need to edit the Customers Contact Information click the Edit Icon by their contact details.<br><br>Click on the Employee name to go to the employees details. Hover over the Magnifing glass to view the employees Quick Contact Information.<br><br>Click the Print link on the Quick Bar to generate a printable PDF of this work order. Click the Close link on the Quick Bar to complete the order and start the invoicing processes</p>')" onMouseOut="hideddrivetip()"></a></td>
					</td>
				</tr><tr>
					<td class="menutd2" colspan="2">
						<table width="100%" class="olotable" cellpadding="5" cellspacing="0" border="0" >
							<tr>
								<td width="100%" valign="top" >
									<!-- Inside Content -->
									<!-- Display Work order -->
									{include file="workorder/work_order_head.tpl"}
									<br>
									{include file="workorder/work_order_description.tpl"}
									<!-- Display Comment -->
									{include file="workorder/work_order_comments.tpl"}
									<br> 
									{include file="workorder/work_order_customer_contact.tpl"}
									<br>
									<!-- Display schedule -->
									{include file="workorder/work_order_schedule.tpl"}	
									<br>		
									<!-- Work Order Notes -->
									{include file="workorder/work_order_notes.tpl"}
									<br>
									{include file="workorder/work_order_parts.tpl"}
									<br>
									<!-- Work Order Status -->
									{include file="workorder/work_order_status.tpl"}
									<br>
									
									{if $single_workorder_array[i].WORK_ORDER_CLOSE_BY != "" }
										{include file="workorder/resolution.tpl}
									{/if}
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
{/section}