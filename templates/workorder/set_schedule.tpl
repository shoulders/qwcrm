{literal}
<script language="javascript" type="text/javascript" src="include/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script language="javascript" type="text/javascript">
	tinyMCE.init({
		mode : "specific_textareas",
		theme : "advanced",
		plugins : "advlink,iespell,insertdatetime,preview",
		theme_advanced_buttons2_add : "separator,insertdate,inserttime,preview,separator,forecolor,backcolor",
		theme_advanced_buttons2_add_before: "cut,copy,paste",
		theme_advanced_toolbar_location : "bottom",
		theme_advanced_toolbar_align : "center", 
		
	    plugin_insertdate_dateFormat : "%Y-%m-%d",
	    plugin_insertdate_timeFormat : "%H:%M:%S",
		extended_valid_elements : "a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]",
		external_link_list_url : "example_link_list.js",
		external_image_list_url : "example_image_list.js",
		flash_external_list_url : "example_flash_list.js",
		file_browser_callback : "fileBrowserCallBack",
		width : "100%"
	}); 

</script>
<link rel="stylesheet" type="text/css" media="all" href="include/jscalendar/calendar-blue.css" title="win2k-1" />
<script type="text/javascript" src="include/jscalendar/calendar_stripped.js"></script>
<script type="text/javascript" src="include/jscalendar/lang/calendar-english.js"></script>
<script type="text/javascript" src="include/jscalendar/calendar-setup_stripped.js"></script>
{/literal}
<table  class="toolbar" border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td >
			<table  cellpadding="2" cellspacing="2">
				<tr>
                                    <td>
		    		{include file="core/tool_bar.tpl"}
                                    </td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<table width="100%" border="0" cellpadding="20" cellspacing="0">
	<tr>
		<td class="olotd">
			<table width="75%" cellpadding="5" cellspacing="0" border="0" >
				<tr>
					<td class="menuhead2" width="80%">&nbsp;
					{section name=i loop=$cur_schedule}	
						{if $cur_schedule[i].SCHEDULE_ID > 0}
							Update schedule For Work Order ID#{$wo_id}
						{else}
							Set schedule For Work Order ID#{$wo_id}
						{/if}
					</td>
					<td class="menuhead2" size="10%" valign="right"><img src="images/icons/16x16/help.gif" border="0"
						onMouseOver="ddrivetip('<b>Set schedule</b>')" onMouseOut="hideddrivetip()">
					</td>
				</tr><tr>
					<td class="olotd5" colspan="2">
						<table class="menutable" width="100%" border="0" cellpadding="5" cellspacing="0">
							<tr>
								<td>
									<!-- Work Order Notes -->
									{$form.javascript}
									<form {$form.attributes}>
									<input type="hidden" name="page" value="workorder:set_schedule">
									<input type="hidden" name="create_by" value="{$display_login}">
									<input type="hidden" name="wo_id" value="{$wo_id}">
									<table class="olotable" width="100%" border="0" summary="Work order display">
										<tr>
											<td class="olohead">Set schedule</td>
										</tr><tr>
											<td class="olotd">
												<table width="100%" cellpadding="5" cellspacing="5">
													<tr>
														<td>
															
															<b>Start Time: </b> 
															<input size="10" name="start[SCHEDULE_date]" type="text" id="SCHEDULE_date" value="{$cur_schedule[i].SCHEDULE_START|date_format:"$date_format"}"/>
															<input type="button" id="trigger_SCHEDULE_date" value="+">
															{literal}
															<script type="text/javascript">
																Calendar.setup(
																	{
																		inputField  : "SCHEDULE_date",
																		ifFormat    : "%d/%m/%y",
																		button      : "trigger_SCHEDULE_date"
																	}
																);
															</script>
															{/literal}
															{html_select_time use_24_hours=false display_seconds=false minute_interval=15 field_array=start time=$cur_schedule[i].SCHEDULE_START}
															&nbsp;&nbsp; 
															<b>End Time: </b>
															<input size="10" name="end[SCHEDULE_date]" type="text" id="end_SCHEDULE_date" value="{$cur_schedule[i].SCHEDULE_END|date_format:"$date_format"}" />
															<input type="button" id="trigger_end_SCHEDULE_date" value="+">
															{literal}
															<script type="text/javascript">
																Calendar.setup(
																	{
																		inputField  : "end_SCHEDULE_date",
																		ifFormat    : "%d/%m/%y",
																		button      : "trigger_end_SCHEDULE_date"
																	}
																);
															</script>
															{/literal}
															{html_select_time use_24_hours=false display_seconds=false minute_interval=15 field_array=end time=$cur_schedule[i].SCHEDULE_END}
															
														</td>
													</tr><tr>
														<td>
															<b>Schedule Notes:</b><br>
															<textarea name="SCHEDULE_notes" rows="15" cols="70" mce_editable="true">
																{$cur_schedule[i].SCHEDULE_NOTES}
															</textarea>
															{/section}
														</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
									<br>
									{$form.submit.html}	
									</form>
									<br>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>