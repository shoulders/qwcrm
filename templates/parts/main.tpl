<!-- parts -->
{literal}

<SCRIPT LANGUAGE="JavaScript">

function setOptions(chosen) {
var selbox = document.form1.CAT2;
	
	selbox.options.length = 0;
	if (chosen == " ") {
	selbox.options[selbox.options.length] = new Option('Please select one of the options above first',' ');
	
	}
   {/literal}
   {section name=q loop=$CAT}
   {literal}if (chosen == "{/literal}{$CAT[q].ID}{literal}"){{/literal}
    {section name=w loop=$SUB_CAT}
     {if $SUB_CAT[w].CAT == $CAT[q].ID}
      {literal}
        selbox.options[selbox.options.length] = new Option({/literal}'{$SUB_CAT[w].DESCRIPTION}','{$SUB_CAT[w].SUB_CATEGORY}'{literal});
      {/literal}
      {/if}
     {/section}
    {literal}
     }
   {/literal}
   {/section}
   {literal}
}
</script>
{/literal}
{if $error_msg != ""}
	{include file="core/error.tpl"}
{/if}
<table width="100%" border="0" cellpadding="20" cellspacing="0">
	<tr>
		<td><!-- Begin Page -->

			<table width="100%" 	cellpadding="5" cellspacing="0" border="0" >
				<tr>
					<td class="menuhead2">&nbsp;{$translate_parts_order}</td>	
				</tr><tr>
					<td class="menutd2">
					<table width="100%" class="olotable" cellpadding="5" cellspacing="0" border="0" >
						<tr>
							<td class="menutd" valign="top" >
								<!-- Content Here -->

								<table width="100%">
									<tr>
										<td valign="top" width="75%">
										<!-- Left Side -->
										<p>{$translate_parts_msg_1}</p>
										<p>
											{$translate_parts_msg_2}
										<br>
											{$translate_parts_msg_3}
										<br>
											{$translate_parts_msg_4}
										</p>
										{if $crm_msg != ''}
											<table width="100%" border="0" cellpadding="4" cellspacing="4">
											<tr>
												<td>
													<table class="error" width="100%" border="0" cellpadding="5" cellspacing="5">
														<tr>
															<td valign="middle">
																<span>{$crm_msg}</span>
																<br>
															</td>
														</tr>
													</table>
												</td>
											</tr>
											</table>	
										{/if}
										{if $parts != ''}
											<table class="olotable" width="100%" cellpadding="3" cellspacing="0" border="0">
												<tr>
													<td class="olohead">{$translate_parts_amount}</td>
													<td class="olohead">{$translate_parts_sku}</td>
													<td class="olohead">{$translate_parts_item_id}</td>
													<td class="olohead">{$translate_parts_description}</td>
													<td class="olohead">{$translate_parts_vendor}</td>
													<td class="olohead">{$translate_parts_weight}</td> 
													<td class="olohead">{$translate_parts_price}</td>
													<td class="olohead">{$translate_parts_add}</td>
											{section name=p loop=$parts}
												<form method="post" action="?page=parts:main">
												<tr onmouseover="this.className='row2'" onmouseout="this.className='row1';" class="row1">
													<td class="olotd4" align="center" valign="middle"><input type="text" class="olotd5" name="AMOUNT" size="2" maxlength="4"></td>
													<td class="olotd4" align="center" valign="middle">{$parts[p].SKU}</td>
													<td class="olotd4" align="center" valign="middle">{$parts[p].ITEMID}</td>
													<td class="olotd4">{$parts[p].DESCRIPTION}</td>
													<td class="olotd4">{$parts[p].VENDOR}</td>
													<td class="olotd4" align="center" valign="middle">{$parts[p].Weight} {$parts[p].UNIT}</td> 
													<td class="olotd4" align="center" valign="middle">${$parts[p].PRICE}</td>
													<input type="hidden" name="SKU" value="{$parts[p].SKU}">
													<input type="hidden" name="DESCRIPTION" value="{$parts[p].DESCRIPTION}">
													 <input type="hidden" name="VENDOR" value="{$parts[p].VENDOR}">
													<input type="hidden" name="ITEMID" value="{$parts[p].ITEMID}">
													<input type="hidden" name="Weight" value="{$parts[p].Weight}">
													<input type="hidden" name="PRICE" value="{$parts[p].PRICE|string_format:"%.2f"}">
													<input type="hidden" name="CAT2" value="{$CAT2}">
													<input type="hidden" name="add_part" value="1">
													<td class="olotd4" align="center" valign="middle">
													<input type="hidden" name="wo_id" value="{$wo_id}">
													<input type="hidden" name="from_zip" value="{$from_zip}">
													<input type="submit" name="submit" value="Add"></td>
												</form>
												</tr>
											{/section}
											</table>
										{/if}
										<br>
										{if $cart_contents != ''}
											<b>Check Out</b>
											<table class="olotable" width="100%" cellpadding="3" cellspacing="0" border="0">
												<tr>
													<td class="olohead">{$translate_parts_amount}</td>
													<td class="olohead">{$translate_parts_sku}</td>
													<td class="olohead">{$translate_parts_item_id}</td>
													<td class="olohead">{$translate_parts_description}</td>
													<td class="olohead">{$translate_parts_vendor}</td>
													<td class="olohead">{$translate_parts_weight}</td> 
													<td class="olohead">{$translate_parts_each}</td>
													<td class="olohead">{$translate_parts_total}</td>
											{section name=a loop=$cart_contents}
												
												<tr onmouseover="this.className='row2'" onmouseout="this.className='row1';" class="row1">
													<td class="olotd4" align="center" valign="middle">{$cart_contents[a].AMOUNT}</td>
													<td class="olotd4" align="center" valign="middle">{$cart_contents[a].SKU}</td>
													<td class="olotd4" align="center" valign="middle">{$cart_contents[a].ITEMID}</td>
													<td class="olotd4">{$cart_contents[a].DESCRIPTION}</td>
													<td class="olotd4">{$cart_contents[a].VENDOR}</td>
													<td class="olotd4" align="center" valign="middle">{$cart_contents[a].Weight} {$cart_contents[a].UNIT}</td> 
													<td class="olotd4" align="right" valign="middle">${$cart_contents[a].PRICE|string_format:"%.2f"}</td>
													<td class="olotd4" align="right" valign="middle">${$cart_contents[a].SUB_TOTAL|string_format:"%.2f"}</td>
													<input type="hidden" name="SKU" value="{$cart_contents[a].SKU}">
													<input type="hidden" name="PRICE" value="{$cart_contents[a].PRICE|string_format:"%.2f"}">
													
												</tr>
											{/section}
										
												<tr>
													<td colspan="6" align="left">{$translate_parts_msg_5} ${$total_charges|string_format:"%.2f"}. {$translate_parts_msg_6} {$service_code} {$translate_parts_msg_7} {$location}. {$translate_parts_msg_8} </td>
													<td class="olotd4" align="right" nowrap><b>{$translate_parts_sub_total}</b></td>
													<td class="olotd4"  align="right" ><b>${$sub_total}</b></td>
												</tr><tr>
													<td colspan="6" align="left">{$translate_parts_msg_9}</td>
													<td class="olotd4" align="right" nowrap><b>{$translate_parts_shipping}</b></td>
													<td class="olotd4"  align="right"><b>${$shipping_charges|string_format:"%.2f"}</b></td>
												</tr><tr>
													<td colspan="6" align="left">
														{if $ResponseStatusCode == 0}
															<span class="error">{$ErrorDescription}</span>
															<br>
														{else}
															<form method="post" action="?page=parts:checkout">
															<input type="hidden" name="wo_id" value="{$wo_id}">
															<input type="submit" name="submit" value="check out">
															</form>
														{/if}
													</td>
													<td class="olotd4"  align="right" nowrap><b>{$translate_parts_total_charges}</b></td>
													<td class="olotd4"  align="right" valign="middle"><b>${$total_charges|string_format:"%.2f"}</b></td>
												</tr>
											</table>
															
														
													</td>
												</tr>
											</table>	
										{/if}
										</td>
										<td valign="top" width="25%">
											<b>{$translate_parts_select}</b>
											<form  action="?page=parts:main" method=post id=form1 name=form1>
												<select name="CAT" size="1" class="olotd5" onchange="setOptions(document.form1.CAT.options[document.form1.CAT.selectedIndex].value);">
													<option value="" selected="selected">{$translate_parts_select_cat}</option>
													{section name=q loop=$CAT}
														<option value="{$CAT[q].ID}">{$CAT[q].DESCRIPTION}</option>
													{/section}
												</select>
												<br>
												<br>
												 <select name="CAT2" size="1" class="olotd5">
													<option value=" " selected="selected">&nbsp;</option>
        										</select>
												<br>
												<br>
												<input type="hidden" name="wo_id" value="{$wo_id}">
												<input type="submit" name="submit" value="{$translate_parts_search}">
											</form>
											<hr>
											<form action="?page=parts:main" method="post">
											<b>{$translate_parts_cart}</b><br>
											{$translate_parts_total_items} {$cart_count}<br>
											<table cellpadding="3" cellspacing="0" border="0">
												<tr>
													<td>{$translate_parts_remove}</td>
													<td>{$translate_parts_sku}</td>
													<td>{$translate_parts_amount}</td>
													<td>{$translate_parts_sub_total}</td>
												</tr>
												{section name=c loop=$cart}
												<tr>
													<td align="left"><input type="checkbox" name="remove[{$smarty.section.c.index}]" value="{$cart[c].SKU}"></td>
													<td>{$cart[c].SKU}</td>
													<td align="center">{$cart[c].AMOUNT}</td>
													<td align="right">${$cart[c].PRICE|string_format:"%.2f"}</td>
												</tr>
												{/section}	
												<tr>
													<td colspan="4"><input type="hidden" name="wo_id" value="{$wo_id}">
														<input type="hidden" name="CAT2" value="{$CAT2}">
														<input type="hidden" name="update_cart" value="1">
														<input type="submit" name="submit" value="{$translate_parts_update}"> 
														</form><br><hr></td>
												</tr><tr>
													<td>{$translate_parts_cart_total}</td>
													<td colspan="3" align="right">${$cart_total|string_format:"%.2f"}</td>
												</tr>
												
											</table>
											 
											<table>
												<tr>
													<td>
														
													</td>
													<td>
														<form method="POST" action="?page=parts:main">
															<input type="hidden" name="wo_id" value="{$wo_id}">
															<input type="hidden" name="check_out" value="1">
															
															<input type="submit" name="submit" value="{$translate_parts_checkout}">
															<input type="submit" name="submit" value="{$translate_parts_view}">
														</form>
													</td>
												</tr>
											</table><br>
											<b>{$translate_parts_wo_id}</b> {$wo_id}<br>
											<b>{$translate_parts_shipping_method}</b> {$service_code}<br>
											<b>{$translate_parts_ware}</b> {$location}<br>
											
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