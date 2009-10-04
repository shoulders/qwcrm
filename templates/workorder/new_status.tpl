<!-- status.tpl -->
<table width="100%" border="0" cellpadding="20" cellspacing="0">
	<tr>
		<td><!-- Begin Page -->
			<table width="700" cellpadding="5" cellspacing="0" border="0" >
				<tr>
					<td class="menuhead2" width="100%">Update Work Order Status For Work Order ID#{$wo_id}</td>
				</tr><tr>
					<td class="menutd2">
						{if $error_msg != ""}
							{include file="core/error.tpl"}
						{/if}
						<table width="100%" class="olotable" cellpadding="5" cellspacing="0" border="0" >
							<tr>
								<td width="100%" valign="top" >
									<!-- Content Here -->
									<form  action="index.php?page=workorder:new_status" method="POST" name="new_workorder_status" id="new_workorder_status">
									<input type="hidden" name="page" value="workorder:new_status">
									<input type="hidden" name="create_by" value="{$display_login}">
									<input type="hidden" name="wo_id" value="{$wo_id}">
									<p>&nbsp;</p>
									<b>New Status:</b>
									<select class="olotd4" name="status">
										<option value="2">Assigned</option>
										<option value="3">Waiting For Parts</option>
									</select>
									<p>&nbsp;</p>
									<p>&nbsp;</p>
									<input class="olotd4" name="submit" value="submit" type="submit" />
									<p>&nbsp;</p>
									<p>&nbsp;</p>
									<p>&nbsp;</p>
									
									</form>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>