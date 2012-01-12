<!-- Customer Search TPL -->
{literal}
<script type="text/javascript">
        function go()
        {
                box = document.forms[1].page_no;
                destination = box.options[box.selectedIndex].value;
                if (destination) location.href = destination;
        }
</script>

		<script type="text/javascript">
		//<![CDATA[
		function validate_customer_search(frm) {
		var value =  '';
		var errFlag = new Array();
		var _qfGroups = {};
		_qfMsg = '';
		
		value = frm.elements['name'].value;
		if (value != '' && value.length > 10 && !errFlag['name']) {
			errFlag['name'] = true;
			_qfMsg = _qfMsg + '\n - Customers Name cannot be more than 10 characters';
		}
		
		if (_qfMsg != '') {
			_qfMsg = 'Invalid information entered.' + _qfMsg;
			_qfMsg = _qfMsg + '\nPlease correct these fields.';
			alert(_qfMsg);
			return false;
		}
		return true;
		}
                // Allow only numbers and letters including space, delete, enter , comma, backslash, apostrophe and minus
function OnlyAlphaNumeric(e)
{
var key;
var keychar;

if (window.event)
   key = window.event.keyCode;
else if (e)
   key = e.which;
else
   return true;
keychar = String.fromCharCode(key);
keychar = keychar.toLowerCase();

// control keys
if ((key==null) || (key==0) || (key==8) ||
    (key==9) || (key==13) || (key==27) || (key==32) )
   return true;

// alphas and numbers
else if ((("abcdefghijklmnopqrstuvwxyz0123456789,/-'").indexOf(keychar) > -1))
   return true;
else
   return false;
}
		//]]>
</script>

{/literal}
<table width="100%" border="0" cellpadding="20" cellspacing="5">
	<tr>
		<td>
			<table width="700" cellpadding="4" cellspacing="0" border="0" >
				<tr>
					<td class="menuhead2" width="80%">&nbsp;&nbsp;{$translate_main_title}</td>
					<td class="menuhead2" width="20%" align="right" valign="middle">
                                            <a ><img src="images/icons/16x16/help.gif" border="0" alt=""
						onMouseOver="ddrivetip('<b>Customer Search</b><hr><p>You can search by the customers full display name or just their first name. If you wish to see all the customers for just one letter like A Click the letter A.</p> <p>To find customers whos name starts with Ja enter just ja. The system will intelegently look for the corect customers that match. To view all customers leave the name field blank and click view.</p>')" 
						onMouseOut="hideddrivetip()"></a>
					</td>
					</tr><tr>
					<td class="menutd2" colspan="2">
						<table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
							<tr>
								<td class="menutd">
									<!-- Content -->
									<table class="menutable" width="100%" border="0" cellpadding="5" cellspacing="0">
							<tr>
								<td valign="top">
									{literal}
									<form action="index.php?page=customer:view" method="get" name="customer_search" id="customer_search" onsubmit="try { var myValidator = validate_customer_search; } catch(e) { return true; } return myValidator(this);">
									{/literal}
									<div>
									<input name="page" type="hidden" value="customer:view" />
									<table border="0">
									
										<tr>
                                                                                    <td style ="color:RED;">{$translate_employee_display_name_criteria}</td>
                                                                                </tr>
                                                                                <tr>
											<td align="left" valign="top"><b>{$translate_display}</b><input class="olotd4" name="name" type="text" onkeypress="return OnlyAlphaNumeric();" /><input class="olotd4" name="submit" value="Search" type="submit" /></td>
										</tr>									
									</table>
									</div>
									</form>
								</td>
								<td valign="top" nowrap>
								<form id="1">
									<a href="?page=customer%3Aview&name={$name|escape}&submit=submit&page_no=1"><img src="images/rewnd_24.gif" border="0" alt=""></a>&nbsp;
									{if $previous != ''}
										<a href="?page=customer%3Aview&name={$name|escape}&submit=submit&page_no={$previous}"><img src="images/back_24.gif" border="0" alt=""></a>&nbsp;
									{/if}
									
                                		<select name="page_no" onChange="go()">
											{section name=page loop=$total_pages start=1}
												<option value="?page=customer%3Aview&name={$name|escape}&submit=submit&page_no={$smarty.section.page.index}" {if $page_no == $smarty.section.page.index } Selected {/if}>
													{$translate_page} {$smarty.section.page.index} {$translate_of} {$total_pages} 
												</option>
											{/section}
												<option value="?page=customer%3Aview&name={$name|escape}&submit=submit&page_no={$total_pages}" {if $page_no == $total_pages} selected {/if}>
													{$translate_page} {$total_pages} {$translate_of} {$total_pages}
												</option>
										</select>
									
									{if $next != ''}
									<a href="?page=customer%3Aview&name={$name|escape}&submit=submit&page_no={$next}"><img src="images/forwd_24.gif" border="0" alt=""></a>
									{/if}
									
									<a href="?page=customer%3Aview&name={$name|escape}&submit=submit&page_no={$total_pages}"><img src="images/fastf_24.gif" border="0" alt=""></a>
									<br>
									{$total_results} {$translate_records_found}.
									</form>
								</td>
							</tr><tr>
								<td valign="top" colspan="2">
									{foreach  from=$alpha item=alpha}
										&nbsp;<a href="?page=customer%3Aview&name={$alpha}&submit=submit">{$alpha}</a>&nbsp;
									{/foreach}
									
								</td>
							</tr><tr>
								<td valign="top" colspan="2">
									<table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
							<tr>
								<td class="olohead">{$translate_action}</td>
								<td class="olohead">{$translate_display}</td>
								<td class="olohead">{$translate_first}</td>
								<td class="olohead">{$translate_last}</td>
								<td class="olohead">{$translate_phone}</td>
								<td class="olohead">{$translate_type}</td>
								<td class="olohead">{$translate_email}</td>
								<td class="olohead">ID</td>
							</tr>
							{section name=i loop=$customer_search_result}
								
							<tr onmouseover="this.className='row2'" onmouseout="this.className='row1'" onDblClick="window.location='index.php?page=customer:customer_details&customer_id={$customer_search_result[i].CUSTOMER_ID}&page_title={$customer_search_result[i].CUSTOMER_FIRST_NAME}';" class="row1">
								<td class="olotd4" nowrap><a href="?page=customer:customer_details&customer_id={$customer_search_result[i].CUSTOMER_ID}&page_title={$customer_search_result[i].CUSTOMER_FIRST_NAME}%20{$customer_search_result[i].CUSTOMER_LAST_NAME}"><img src="images/icons/16x16/viewmag.gif" alt="" border="0" onMouseOver="ddrivetip('View Customer Details')" onMouseOut="hideddrivetip()"></a>
                                                                    &nbsp;<a href="?page=workorder:new&customer_id={$customer_search_result[i].CUSTOMER_ID}&page_title=New Work Order"><img src="images/icons/16x16/small_new_work_order.gif" alt="" border="0" onMouseOver="ddrivetip('New Work Order')" onMouseOut="hideddrivetip()" alt=""></a>
                                                                    &nbsp;<a href="?page=invoice:new&invoice_type=invoice-only&wo_id=0&customer_id={$customer_search_result[i].CUSTOMER_ID}&page_title=Invoice Only"><img src="images/icons/16x16/small_new_invoice_only.gif" alt="" border="0" onMouseOver="ddrivetip('New Invoice Only')" onMouseOut="hideddrivetip()" alt=""></a>
                                                                </td>
                                                                <td class="olotd4" nowrap><img src="images/icons/16x16/view.gif" alt="" border="0" onMouseOver="ddrivetip('{$customer_search_result[i].CUSTOMER_ADDRESS}<br>{$customer_search_result[i].CUSTOMER_CITY}, {$customer_search_result[i].CUSTOMER_STATE}  {$customer_search_result[i].CUSTOMER_ZIP}')" onMouseOut="hideddrivetip()">&nbsp;{$customer_search_result[i].CUSTOMER_DISPLAY_NAME}</td>
								<td class="olotd4" nowrap>{$customer_search_result[i].CUSTOMER_FIRST_NAME}</td>
								<td class="olotd4" nowrap>{$customer_search_result[i].CUSTOMER_LAST_NAME}</td>
								<td class="olotd4" nowrap><img src="images/icons/16x16/view.gif" border="0" alt=""
									onMouseOver="ddrivetip('<b>Work: </b>{$customer_search_result[i].CUSTOMER_WORK_PHONE}<br><b>Mobile:</b>{$customer_search_result[i].CUSTOMER_MOBILE_PHONE}')" 
									onMouseOut="hideddrivetip()">
									{$customer_search_result[i].CUSTOMER_PHONE}
								</td>
								<td class="olotd4" nowrap>
												{if $customer_search_result[i].CUSTOMER_TYPE ==1}
													{$translate_customer_type_1}
												{/if}
												{if $customer_search_result[i].CUSTOMER_TYPE ==2}
													{$translate_customer_type_2}
												{/if}
												{if $customer_search_result[i].CUSTOMER_TYPE ==3}
													{$translate_customer_type_3}
												{/if}
												{if $customer_search_result[i].CUSTOMER_TYPE ==4}
													{$translate_customer_type_4}
												{/if}
								</td>
								<td class="olotd4" nowrap><a href="mailto:{$customer_search_result[i].CUSTOMER_EMAIL}"><font class="blueLink">{$customer_search_result[i].CUSTOMER_EMAIL}</font></a></td>
								<td class="olotd4" nowrap><a href="index.php?page=customer:customer_details&customer_id={$customer_search_result[i].CUSTOMER_ID}&page_title={$customer_search_result[i].CUSTOMER_DISPLAY_NAME}">{$customer_search_result[i].CUSTOMER_ID}</a></td>
								
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
		</td>
	</tr>
</table>
