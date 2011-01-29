<!-- Main Employees TPL -->
{literal}
<script language="JavaScript">
        function go()
        {
                box = document.forms[1].page_no;
                destination = box.options[box.selectedIndex].value;
                if (destination) location.href = destination;
        }
        </script>
<script type="text/javascript">
		//<![CDATA[
		function validate_employee_search(frm) {
		var value =  '';
		var errFlag = new Array();
		var _qfGroups = {};
		_qfMsg = '';

		value = frm.elements['name'].value;
		if (value != '' && value.length > 10 && !errFlag['name']) {
			errFlag['name'] = true;
			_qfMsg = _qfMsg + '\n - Employees Name cannot be more than 10 characters';
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
					<td class="menuhead2" width="80%">&nbsp;{$translate_employee_search}</td>
					<td class="menuhead2" width="20%" align="right" valign="middle">
							<img src="images/icons/16x16/help.gif" border="0" 
							onMouseOver="ddrivetip('<b>Employee Search</b><hr><p>You can search by the employees full display name or just their first name. If you wish to see all the employees for just one letter like A enter the letter a only.</p> <p>To find employees whos name starts with Ja enter just ja. The system will intelegently look for the corect employee that matches.</p>')" 
							onMouseOut="hideddrivetip()">
					</td>
				</tr><tr>
					<td class="menutd2" colspan="2">
						<table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
							<tr>
								<td class="menutd">
						
						<!-- Content -->
					
						<table class="menutable" width="100%" border="0" cellpadding="5" cellspacing="0">
							<tr>
								<td>
									{literal}
									<form method="get" action="?page=employees:main" onsubmit="try { var myValidator = validate_employee_search; } catch(e) { return true; } return myValidator(this);">
									{/literal}
                                                                        <table border="0">
                                                                            <p>
                                                                                <font color="RED">{$translate_employee_display_name_criteria}</font>
                                                                            </p>
									
										<tr>
											<td align="right" valign="top"><b>{$translate_employee_display_name}</b></td>
											<td valign="top" align="left"><input class="olotd4" name="name" type="text" onkeypress="return OnlyAlphaNumeric();" /></td>
											</tr><tr>
											<td align="right" valign="top"><b></b></td>
											<td valign="top" align="left"><input class="olotd4" name="submit" value="Search" type="submit" /></td>
										</tr>
									</table>
									
									</form>

								</td>
								<td valign="top">
								<form id="1">
									<a href="?page=employees%3Amain&name={$name}&submit=submit&page_no=1"><img src="images/rewnd_24.gif" border="0"></a>&nbsp;
									{if $previous != ''}
										<a href="?page=employees%3Amain&name={$name}&submit=submit&page_no={$previous}"><img src="images/back_24.gif" border="0"></a>&nbsp;
									{/if}
									<select name="page_no" onChange="go()">
									{section name=page loop=$total_pages start=1}
										<option value="?page=employees%3Amain&name={$name}&submit=submit&page_no={$smarty.section.page.index}" {if $page_no == $smarty.section.page.index } Selected {/if}>
											{$translate_employee_page} {$smarty.section.page.index} {$translate_employee_of} {$total_pages}
										</option>
									{/section}
										<option value="?page=employees%3Amain&name={$name}&submit=submit&page_no={$total_pages}" {if $page_no == $total_pages} selected {/if}>
											{$translate_employee_page} {$total_pages} {$translate_employee_of} {$total_pages}
										</option>
									</select>
									{if $next != ''}
									<a href="?page=employees%3Amain&name={$name}&submit=submit&page_no={$next}"><img src="images/forwd_24.gif" border="0"></a>
									{/if}
									
									<a href="?page=employees%3Amain&name={$name}&submit=submit&page_no={$total_pages}"><img src="images/fastf_24.gif" border="0"></a>
									<br>
									{$total_results} {$translate_employee_records_found}
								</td>
							</tr><tr>
								<td valign="top" colspan="2">
									{foreach  from=$alpha item=alpha}
										&nbsp;<a href="?page=employees%3Amain&name={$alpha}&submit=submit">{$alpha}</a>&nbsp;
									{/foreach}
									
								</td>
							</tr><tr>
								<td valign="top" colspan="2">
									<table class="olotable" width="100%" cellpadding="5" celspacing="0" border="0" summary="Work order display">
							<tr>
								<td class="olohead">{$translate_employee_id}</td>
								<td class="olohead">{$translate_employee_display}</td>
								<td class="olohead">{$translate_employee_first}</td>
								<td class="olohead">{$translate_employee_last}</td>
								<td class="olohead">{$translate_employee_work_phone}</td>
								<td class="olohead">{$translate_employee_type}</td>
								<td class="olohead">{$translate_employee_email}</td>
								<td class="olohead">{$translate_employee_action}</td>
							</tr>
							{section name=i loop=$employee_search_result}
							<tr onmouseover="this.className='row2'" onmouseout="this.className='row1'" onDblClick="window.location='index.php?page=employees:employee_details&employee_id={$employee_search_result[i].EMPLOYEE_ID}&page_title={$translate_employee_details_for} {$employee_search_result[i].EMPLOYEE_DISPLAY_NAME}';" class="row1">
								<td class="olotd4"><a href="?page=employees:employee_details&employee_id={$employee_search_result[i].EMPLOYEE_ID}&page_title={$translate_employee_details_for} {$employee_search_result[i].EMPLOYEE_DISPLAY_NAME}">{$employee_search_result[i].EMPLOYEE_ID}</a></td>
								<td class="olotd4">
									<img src="images/icons/16x16/view.gif" border="0" onMouseOver="ddrivetip('{$employee_search_result[i].EMPLOYEE_ADDRESS}<br>{$employee_search_result[i].EMPLOYEE_CITY}, {$employee_search_result[i].EMPLOYEE_SATE}  {$employee_search_result[i].EMPLOYEE_ZIP}')" onMouseOut="hideddrivetip()">
									{$employee_search_result[i].EMPLOYEE_DISPLAY_NAME}
								</td>
								<td class="olotd4">{$employee_search_result[i].EMPLOYEE_FIRST_NAME}</td>
								<td class="olotd4">{$employee_search_result[i].EMPLOYEE_LAST_NAME}</td>
								<td class="olotd4">
									<img src="images/icons/16x16/view.gif" border="0"
										onMouseOver="ddrivetip('<b>{$translate_employee_home} </b>{$employee_search_result[i].EMPLOYEE_HOME_PHONE}<br><b>{$translate_employee_mobile} </b>{$employee_search_result[i].EMPLOYEE_MOBILE_PHONE}')" 
										onMouseOut="hideddrivetip()">
									{$employee_search_result[i].EMPLOYEE_WORK_PHONE}
								</td>
								<td class="olotd4">{$employee_search_result[i].TYPE_NAME}</td>
								<td class="olotd4"><a href="mailto: {$employee_search_result[i].EMPLOYEE_EMAIL}"><font class="blueLink">{$employee_search_result[i].EMPLOYEE_EMAIL}</font></a></td>
								<td class="olotd4">
									<a href="?page=employees:employee_details&employee_id={$employee_search_result[i].EMPLOYEE_ID}&page_title={$translate_employee_details_for} {$employee_search_result[i].EMPLOYEE_DISPLAY_NAME}"><img src="images/icons/16x16/viewmag.gif"  border="0" onMouseOver="ddrivetip('View Employees Details')" onMouseOut="hideddrivetip()"></a>&nbsp;<a href="?page=employees:edit&employee_id={$employee_search_result[i].EMPLOYEE_ID}&page_title={$translate_employee_edit} {$employee_search_result[i].EMPLOYEE_DISPLAY_NAME}"><img src="images/icons/16x16/small_edit_employees.gif" border="0" onMouseOver="ddrivetip('Edit')" onMouseOut="hideddrivetip()"></a>
								</td>
							</tr>
							{/section}
						</table>
								</td>
							</tr>
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
<!--					<td><a href="?page=employees:new">New Employee</a></td> -->
			
			