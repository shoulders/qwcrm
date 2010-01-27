<html>
<head>
	<title>{$translate_parts_order_complete}</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="css/default.css" rel="stylesheet" type="text/css">
</head>
<body>
<table  width="700" border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
	<tr>
		<td valign="top">
			<img src="images/logo.jpg" border="0">
		</td>
	</tr>
</table>
<br>
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
					<td class="olotd5"><b>{$translate_parts_crm_order_id}</b> {$order.INVOICE_ID}<br>
							<b>{$translate_parts_date}</b> {$order.DATE_CREATE|date_format:"$date_format"}<br>
							<b>{$translate_parts_total}</b> ${$order.TOTAL}<br>
							<b>{$translate_parts_total_items}</b> {$order.TOTAL_ITEMS}<br>
							<b>{$translate_parts_weight}</b> {$order.WEIGHT} lbs
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
			
			
				<font size="+2">{$company_name}</font><br>
				{$company_address}<br>
				{$company_city}, {$company_state {$company_zip}<br>
				{$company_phone}<br>
			
		</td>
		<td valign="top" align="right" width="200">
			<table width="200" border="1" cellpadding="5" cellspacing="0" style="border-collapse: collapse;">
				<tr>
					<td class="olotd5">
						<b>{$translate_parts_wo_id}</b> {$order.WO_ID}<br>
						<b>{$translate_parts_tech}</b>{$display_login}
					</td>
				</tr>
			</table>
	</tr>
</table>
<br>
<table width="700" border="1" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
	<tr>
		<td align="center" class="olotd5" ><font size="+2">{$translate_parts_cap_invoice}</font></td>
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
		{section name=q loop=$details}
		<tr>
			<td class="olotd4" width="40"><b>{$details[q].SKU}</b></td>
			<td class="olotd4">{$details[q].COUNT}</td>
			<td class="olotd4">{$details[q].DESCRIPTION}</td>
			<td class="olotd4">{$details[q].VENDOR}</td>
			<td class="olotd4" align="right">${$details[q].PRICE|string_format:"%.2f"}</td>
			<td class="olotd4" align="right">${$details[q].SUB_TOTAL|string_format:"%.2f"}</td>
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
						<td width="80" align="right" class="olotd4">${$order.SUB_TOTAL|string_format:"%.2f"}</td>
				</tr><tr>
						<td class="olotd4"><b>{$translate_parts_shipping}</b></td>
						<td width="80" align="right" class="olotd4">${$order.SHIPPING|string_format:"%.2f"}</td>
				</tr><t>
						<td class="olotd4"><b>{$translate_parts_tax}</b></td>
						<td width="80" align="right" class="olotd4">${$order.TAX|string_format:"%.2f"}</td>
				</tr><t>
						<td><b>{$translate_parts_total}</b></td>
						<td width="80" align="right" class="olotd4">${$order.TOTAL|string_format:"%.2f"}</td>
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
</body>
</html>