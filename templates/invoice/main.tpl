<!-- Invoice main.tpl -->
{if $error_msg != ""}
	{include file="core/error.tpl"}
{/if}
<table width="100%" border="0" cellpadding="20" cellspacing="0">
	<tr>
		<td class="olotd">
		
			<table width="100%" cellpadding="5" cellspacing="0" border="0" >
				<tr>
					<td class="menuhead2" width="80%">&nbsp;Open Work Order: #{$single_workorder_array[i].WORK_ORDER_ID}
					<td class="menuhead2" width="20%" align="right" valign="middle">
						<img src="images/icons/16x16/help.gif" border="0"
						onMouseOver="ddrivetip('<b>Invoice</b><hr><p></p>')" onMouseOut="hideddrivetip()"></td>
					</td>
				</tr><tr>
					<td class="olotd5" colspan="2">
					</td>
				</tr>
			</table>
			
		</td>
	</tr>
</table>