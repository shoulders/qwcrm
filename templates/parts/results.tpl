<!-- invoice -->
<table width="700" border="0" cellpadding="20" cellspacing="0">
	<tr>
		<td><!-- Begin Page -->

			<table width="100%" 	cellpadding="5" cellspacing="0" border="0" >
				<tr>
					<td class="menuhead2">&nbsp;{$translate_parts_order_complete}</td>	
				</tr><tr>
					<td class="menutd2">
					{if $error_msg != ""}
						{include file="core/error.tpl"}
					{/if}
					<table width="100%" class="olotable" cellpadding="5" cellspacing="0" border="0" >
						<tr>
							<td class="menutd" valign="top" >
								{$translate_parts_msg_10} <a
								 href="?page=parts:print_results&wo_id={$invoice_details.WORKORDER}&escape=1" target="new">{$translate_parts_print	}</a> 
								<table  width="700" border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
	<tr>
		<td valign="top">
			<!-- Left Column -->

				<font size="+2">MyIT CRM CRM</font><br>
				????????<br>
				????????<br>
				????????<br>
		</td>
		<td valign="top" align="right" width="205">
			<!-- Right Column -->
			<table width="205" border="1" cellpadding="3" cellspacing="0" style="border-collapse: collapse;">
				<tr>
					<td class="olotd5"><b>{$translate_parts_crm_order_id}</b> {$invoice_details.ORDER_ID}<br>
							<b>{$translate_parts_date}</b> {$invoice_details.DATE|date_format:"$date_format"}<br>
							<b>{$translate_parts_total}</b> ${$invoice_details.TOTAL|string_format:"%.2f"}<br>
							<b>{$translate_parts_total_items}</b> {$invoice_details.TOTAL_ITEMS}<br>
							<b>{$translate_parts_weight}</b> {$invoice_details.WEIGHT} lbs
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<br>
<table  width="700" border="0" cellpadding="3" cellspacing="0" >
	<tr>
		<td valign="top" width="10%" align="right"><b>{$translate_parts_ship_to}</b></td>
		<td valign="top" >
			
			{foreach item=item from=$customer}
				<font size="+2">{$item.COMPANY_NAME}</font><br>
				{$item.COMPANY_ADDRESS}<br>
				{$item.COMPANY_CITY}, {$item.COMPANY_STATE} {$item.COMPANY_ZIP}<br>
				{$item.COMPANY_PHONE}<br>
			{/foreach}
		</td>
		<td valign="top" align="right" width="200">
			<table width="200" border="1" cellpadding="5" cellspacing="0" style="border-collapse: collapse;">
				<tr>
					<td class="olotd5">
						<b>{$translate_parts_wo_id}</b> {$invoice_details.WORKORDER}<br>
						<b>{$translate_parts_tech}</b> {$display_login}
					</td>
				</tr>
			</table>
	</tr>
</table>
<br>
<table width="700" border="1" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
	<tr>
		<td align="center" class="olotd5"><font size="+2">{$translate_parts_cap_invoice}</font></td>
	</tr>
</table>
<br>
<b>Parts</b>
<table width="700" border="1" cellpadding="3" cellspacing="0" style="border-collapse: collapse;">
	<tr>
		<td class="olohead" width="40"><b>{$translate_parts_sku}</b></td>
		<td class="olohead" width="40"><b>{$translate_parts_count}</b></td>
		<td class="olohead"><b>{$translate_parts_description}</b></td>
		<td class="olohead" width="40"><b>{$translate_parts_vendor}</b></td>
		<td class="olohead" width="40"><b>{$translate_parts_amount}</b></td>	
		<td class="olohead" width="80"><b>{$translate_parts_sub_total}</b></td>
	</tr>
		{section name=w loop=$details}		
		<tr >
			<td class="olotd4" width="40"><b>{$details[w].SKU}</b></td>
			<td class="olotd4">{$details[w].COUNT}</td>
			<td class="olotd4">{$details[w].DESCRIPTION}</td>
			<td class="olotd4">{$details[w].VENDOR}</td>
			<td class="olotd4" align="right">${$details[w].PRICE|string_format:"%.2f"}</td>
			<td class="olotd4" align="right">${$details[w].SUB_TOTAL|string_format:"%.2f"}</td>
		</tr>	
	{/section}
</table>
<br>
<table width="700" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse;">
	<tr>
		<td align="right">
			<table width="200" border="1" cellpadding="3" cellspacing="0" style="border-collapse: collapse;">
				<tr>
			
						<td class="olotd4"><b>{$translate_parts_sub_total}</b></td>
						<td class="olotd4" width="80" align="right">${$invoice_details.CART_TOTAL|string_format:"%.2f"}</td>
				</tr><tr>
						<td class="olotd4"><b>{$translate_parts_shipping}</b></td>
						<td class="olotd4" width="80" align="right">${$invoice_details.SHIPPING|string_format:"%.2f"}</td>
				</tr><t>
						<td class="olotd4"><b>{$translate_parts_tax}</b></td>
						<td class="olotd4" width="80" align="right">${$invoice_details.TAX|string_format:"%.2f"}</td>
				</tr><t>
						<td class="olotd4"><b>{$translate_parts_total}</b></td>
						<td class="olotd4" width="80" align="right">${$invoice_details.TOTAL|string_format:"%.2f"}</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<br>
<table width="700" border="1" cellpadding="3" cellspacing="0" style="border-collapse: collapse;">
	<tr>
		<td class="olotd5"><font size="-1">{$translate_parts_msg_11}</font></td>
	</tr>
</table>
<br>
<br>

<!--! end -->

							</td>
						</tr>
					</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>