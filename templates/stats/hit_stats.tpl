<!-- hits -->
<table width="100%" border="0" cellpadding="20" cellspacing="5">
	<tr>
		<td>

			<table width="700" cellpadding="4" cellspacing="0" border="0" >
				<tr>
					<td class="menuhead2" width="80%">&nbsp;Stats for {$smarty.now|date_format:"$date_format"}</td>
					<td class="menuhead2" width="20%" align="right" valign="middle">
						<img src="images/icons/16x16/help.gif" border="0"
						onMouseOver="ddrivetip('<b>Help Menu</b><hr><p></p>')" 
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
						
						<table class="olotable"  border="0" cellpadding="5" cellspacing="0">
							<tr>
								<td class="olohead">Daily Hits</td>
								<td class="olohead">Daily Visits</td>
								<td class="olohead">Monthly Hits</td>
							</tr><tr>
								<td class="olotd4">{$daily_total}</td>
								<td class="olotd4"></td>
								<td class="olotd4">{$month_hit}</td>
							</tr>
						</table>
						<br>
						<br>	
							
						<table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
							<tr>
								<td class="olohead">Time</td>
								<td class="olohead">IP Address</td>
								<td class="olohead">User Agent</td>
								<td class="olohead">Hits</td>
							</tr>
							{section name=i loop=$hit}
							<tr onmouseover="this.className='row2'" onmouseout="this.className='row1'" onDblClick="window.location='index.php?page=stats:hit_stats_view&ip={$hit[i].ip}&page_title=Hits For {$hit[i].ip}';" class="row1">
								
								<td class="olotd4">{$hit[i].date|date_format:" %H:%M:%S"}</td>
								<td class="olotd4">{$hit[i].ip}</td>
								<td class="olotd4">{$hit[i].uagent}</td>
								<td class="olotd4">{$hit[i].count}</td>
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
