<!-- template name -->
<table width="100%" border="0" cellpadding="20" cellspacing="0">
	<tr>
		<td><!-- Begin Page -->
			<table width="700" cellpadding="5" cellspacing="0" border="0" >
				<tr>
					<td class="menuhead2" width="80%">&nbsp;Office Hours</td>
				</tr><tr>
					<td class="menutd2" >
						<table width="100%" class="olotable" cellpadding="5" cellspacing="0" border="0" >
						<tr>
							<td width="100%" valign="top" class="menutd">
							<!-- Content Here -->
								<form method="POST" action="?page=control:hours_edit">
								{section name=a loop=$arr}
								<table >
									<tr>
										<td><b>Start Hour (Currently {$arr[a].OFFICE_HOUR_START})</b></td>
										<td align="left">
                   <!--{html_select_time use_24_hours=true display_minutes=true display_seconds=false prefix=start default={$arr[a].OFFICE_HOUR_START}}-->
										<select class="olotd5" name="startHour"> 
											<option value="00" {if $arr[a].OFFICE_HOUR_START} = 0 "selected"{/if} >00</option>
											<option value="01" {if $arr[a].OFFICE_HOUR_START} = 1 "selected"{/if} >01</option>
											<option value="02" {if $arr[a].OFFICE_HOUR_START} = 2 "selected"{/if}>02</option>
											<option value="03" {if $arr[a].OFFICE_HOUR_START} = 3 "selected"{/if}>03</option>
											<option value="04" {if $arr[a].OFFICE_HOUR_START} = 4 "selected"{/if}>04</option>
											<option value="05" {if $arr[a].OFFICE_HOUR_START} = 5 "selected"{/if}>05</option>
											<option value="06" {if $arr[a].OFFICE_HOUR_START} = 6 "selected"{/if}>06</option>
											<option value="07" {if $arr[a].OFFICE_HOUR_START} = 7 "selected"{/if}>07</option>
											<option value="08" {if $arr[a].OFFICE_HOUR_START} = 8 "selected"{/if}>08</option>
											<option value="09" {if $arr[a].OFFICE_HOUR_START} = 9 "selected"{/if}>09</option>
											<option value="10" {if $arr[a].OFFICE_HOUR_START} = 10 "selected"{/if}>10</option>
											<option value="11" {if $arr[a].OFFICE_HOUR_START} = 11 "selected"{/if}>11</option>
											<option value="12" {if $arr[a].OFFICE_HOUR_START} = 12 "selected"{/if}>12</option>
											<option value="13" {if $arr[a].OFFICE_HOUR_START} = 13 "selected"{/if}>13</option>
											<option value="14" {if $arr[a].OFFICE_HOUR_START} = 14 "selected"{/if}>14</option>
											<option value="15" {if $arr[a].OFFICE_HOUR_START} = 15 "selected"{/if}>15</option>
											<option value="16" {if $arr[a].OFFICE_HOUR_START} = 16 "selected"{/if}>16</option>
											<option value="17" {if $arr[a].OFFICE_HOUR_START} = 17 "selected"{/if}>17</option>
											<option value="18" {if $arr[a].OFFICE_HOUR_START} = 18 "selected"{/if}>18</option>
											<option value="19" {if $arr[a].OFFICE_HOUR_START} = 19 "selected"{/if}>19</option>
											<option value="20" {if $arr[a].OFFICE_HOUR_START} = 20 "selected"{/if}>20</option>
											<option value="21" {if $arr[a].OFFICE_HOUR_START} = 21 "selected"{/if}>21</option>
											<option value="22" {if $arr[a].OFFICE_HOUR_START} = 22 "selected"{/if}>22</option>
											<option value="23" {if $arr[a].OFFICE_HOUR_START} = 23 "selected"{/if}>23</option>
										</td>
										</tr><tr>
										<td><b>End Hour (Currently {$arr[a].OFFICE_HOUR_END})</b></td>
										<td align="left">
                    					<select class="olotd5" name="endHour"> 
											<option value="00" {if $arr[a].OFFICE_HOUR_END} = 0 "selected"{/if} >00</option>
											<option value="01" {if $arr[a].OFFICE_HOUR_END} = 1 "selected"{/if} >01</option>
											<option value="02" {if $arr[a].OFFICE_HOUR_END} = 2 "selected"{/if}>02</option>
											<option value="03" {if $arr[a].OFFICE_HOUR_END} = 3 "selected"{/if}>03</option>
											<option value="04" {if $arr[a].OFFICE_HOUR_END} = 4 "selected"{/if}>04</option>
											<option value="05" {if $arr[a].OFFICE_HOUR_END} = 5 "selected"{/if}>05</option>
											<option value="06" {if $arr[a].OFFICE_HOUR_END} = 6 "selected"{/if}>06</option>
											<option value="07" {if $arr[a].OFFICE_HOUR_END} = 7 "selected"{/if}>07</option>
											<option value="08" {if $arr[a].OFFICE_HOUR_END} = 8 "selected"{/if}>08</option>
											<option value="09" {if $arr[a].OFFICE_HOUR_END} = 9 "selected"{/if}>09</option>
											<option value="10" {if $arr[a].OFFICE_HOUR_END} = 10 "selected"{/if}>10</option>
											<option value="11" {if $arr[a].OFFICE_HOUR_END} = 11 "selected"{/if}>11</option>
											<option value="12" {if $arr[a].OFFICE_HOUR_END} = 12 "selected"{/if}>12</option>
											<option value="13" {if $arr[a].OFFICE_HOUR_END} = 13 "selected"{/if}>13</option>
											<option value="14" {if $arr[a].OFFICE_HOUR_END} = 14 "selected"{/if}>14</option>
											<option value="15" {if $arr[a].OFFICE_HOUR_END} = 15 "selected"{/if}>15</option>
											<option value="16" {if $arr[a].OFFICE_HOUR_END} = 16 "selected"{/if}>16</option>
											<option value="17" {if $arr[a].OFFICE_HOUR_END} = 17 "selected"{/if}>17</option>
											<option value="18" {if $arr[a].OFFICE_HOUR_END} = 18 "selected"{/if}>18</option>
											<option value="19" {if $arr[a].OFFICE_HOUR_END} = 19 "selected"{/if}>19</option>
											<option value="20" {if $arr[a].OFFICE_HOUR_END} = 20 "selected"{/if}>20</option>
											<option value="21" {if $arr[a].OFFICE_HOUR_END} = 21 "selected"{/if}>21</option>
											<option value="22" {if $arr[a].OFFICE_HOUR_END} = 22 "selected"{/if}>22</option>
											<option value="23" {if $arr[a].OFFICE_HOUR_END} = 23 "selected"{/if}>23</option>
									</td>
									</tr><tr>
										<td>
											<input type="submit" name="submit" value="Submit">
										</td>
									</tr>	
								</table>
								These settings are used to display the start and stop times of the schedule.
								<b>Current Start Hour = 	{$arr[a].OFFICE_HOUR_START} and Current end hour = {$arr[a].OFFICE_HOUR_END}.</b>
								{/section}	
								<!-- End Content -->
							</td>
						</tr>
					</table>
				</tr>
			</table>
		</td>
	</tr>
</table>
	