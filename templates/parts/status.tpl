<!-- Status tpl -->
{literal}
<script type="text/javascript" >
        function go()
        {
                box = document.forms[1].page_no;
                destination = box.options[box.selectedIndex].value;
                if (destination) location.href = destination;
        }
        </script>
{/literal}
<table width="100%" border="0" cellpadding="20" cellspacing="5">
	<tr>
		<td>

			<table width="700" cellpadding="4" cellspacing="0" border="0" >
				<tr>
					<td class="menuhead2" width="80%">&nbsp;{$or_status} Orders</td>
					<td class="menuhead2" width="20%" align="right" valign="middle">
                                        <img src="images/icons/16x16/help.gif" border="0" alt="" onMouseOver="ddrivetip('<b>Employee Search</b><hr><p>You can search by the employees full display name or just their first name. If you wish to see all the employees for just one letter like A enter the letter a only.</p> <p>To find employees whos name starts with Ja enter just ja. The system will intelegently look for the corect employee that matches.</p>')"
							onMouseOut="hideddrivetip()">
					</td>
				</tr><tr>
					<td class="menutd2" colspan="2">
						<table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
							<tr>
								<td class="menutd">
						{if $error_msg != ""}
							<br>
							{include file="core/error.tpl"}
							<br>
						{/if}
						<!-- Content -->
					
						<table class="menutable" width="100%" border="0" cellpadding="5" cellspacing="0">
							<tr>
                                                            <td>
								<form method="POST" action="?page=parts:view">
                                                                    <table border="0">
									<tr>
                                                                            <td align="right" valign="top"><b>{$translate_parts_inc_inv}</b></td>
                                                                            <td valign="top" align="left"><input class="olotd4" name="order_id" type="text" /></td>
									</tr>
                                                                        <tr>
                                                                            <td align="right" valign="top"><b></b></td>
                                                                            <td valign="top" align="left"><input class="olotd4" name="submit" value="Search" type="submit" /></td>
									</tr>
                                                                    </table>
								</form>
                                                            </td>
                                                            <td valign="top">
								<form id="1"><a href="?page=parts:status&submit=submit&page_no=1">
									<input type="hidden" name="status" value="{$status}">
									<img src="images/rewnd_24.gif" border="0" alt=""></a>&nbsp;
									{if $previous != ''}
										<a href="?page=parts:status&submit=submit&page_no={$previous}"><img src="images/back_24.gif" border="0" alt=""></a>&nbsp;
									{/if}
									<select name="page_no" onChange="go()">
									{section name=page loop=$total_pages start=1}
										<option value="?page=parts:status&submit=submit&page_no={$smarty.section.page.index}" {if $page_no == $smarty.section.page.index } Selected {/if}>
											{$translate_parts_page} {$smarty.section.page.index} {$translate_parts_of} {$total_pages}
										</option>
									{/section}
										<option value="?page=parts:status&submit=submit&page_no={$total_pages}" {if $page_no == $total_pages} selected {/if}>
											{$translate_parts_page} {$total_pages} {$translate_parts_of} {$total_pages}
										</option>
									</select>
									{if $next != ''}
									<a href="?page=parts:status&submit=submit&page_no={$next}"><img src="images/forwd_24.gif" border="0" alt=""></a>
									{/if}
									
									<a href="?page=pafile:///srv/www/htdocs/citecrm/templates/parts/status.tplrts:status&submit=submit&page_no={$total_pages}"><img src="images/fastf_24.gif" border="0" alt=""></a>
									<br>
									{$total_results} {$translate_parts_records_found}
								</td>
							</tr><tr>
								<td valign="top" colspan="2">
								</td>
							</tr><tr>
								<td valign="top" colspan="2">
									<table class="olotable" width="100%" cellpadding="5" cellspacing="0" border="0" summary="Work order display">
							<tr>
								<td class="olohead">{$translate_parts_id}</td>
								<td class="olohead">{$translate_parts_created}</td>
								<td class="olohead">{$translate_parts_invoice}</td>
								<td class="olohead">{$translate_parts_wo}</td>
								<td class="olohead">{$translate_parts_sub_total}</td>
								<td class="olohead">{$translate_parts_shipping}</td>
								<td class="olohead">{$translate_parts_total}</td>
								<td class="olohead">{$translate_parts_update}</td>
								<td class="olohead">{$translate_parts_tracking}</td>
								<td class="olohead">{$translate_parts_status}</td>
							</tr>
							{section name=i loop=$order}
							<tr onmouseover="this.className='row2'" onmouseout="this.className='row1'" onDblClick="window.location='index.php?page=parts:view&ORDER_ID={$order[i].ORDER_ID}&page_title={$translate_parts_order_details} {$order[i].ORDER_ID}';" class="row1">
								<td class="olotd4"><a href="index.php?page=parts:view&ORDER_ID={$order[i].ORDER_ID}&page_title={$translate_parts_order_details} {$order[i].ORDER_ID}">{$order[i].ORDER_ID}</a>
								</td>
								<td class="olotd4">{$order[i].DATE_CREATE|date_format:"$date_format"}</td>
								<td class="olotd4">{$order[i].INVOICE_ID}</td>
                                                                <td class="olotd4"><a href ="?page=workorder:view&wo_id={$order[i].WO_ID}&page_title={$translate_parts_wo_id} {$order[i].WO_ID}">{$order[i].WO_ID}</a></td>
								<td class="olotd4">${$order[i].SUB_TOTAL|string_format:"%.2f"}</td>
								<td class="olotd4">${$order[i].SHIPPING|string_format:"%.2f"}</td>
								<td class="olotd4">${$order[i].TOTAL|string_format:"%.2f"}</td>
								<td class="olotd4">{$order[i].DATE_LAST|date_format:"$date_format"}</td>
								<td class="olotd4">{if $order[i].TRACKING_NO == '0'}
															<a href="?page=parts:tracking&invoice_id={$order[i].INVOICE_ID}&order_id={$order[i].ORDER_ID}">Get Tracking</a>{else}
															{$order[i].TRACKING_NO}
														{/if}
															
								</td>
								<td class="olotd4">{if $order[i].STATUS == 1} <a href="?page=parts:update&wo_id={$order[i].WO_ID}">{$translate_parts_set_recv}</a>{else}{$translate_parts_rcv}{/if}</td>
							</tr>
							{/section}
						</table>
								</td>
							</tr>
                                                        </table>
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


