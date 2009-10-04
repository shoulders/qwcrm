<!-- Add New Customer results TPL -->
<table width="100%" cellpadding="0" cellspacing="0" class="olotable">
	<tr>
		<td class="olohead">
			<!-- Tool Bar -->
			<table  class="toolbar" cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td >
						<table  cellpadding="4" cellspacing="0">
							<tr>		   						
								<td class="button">	
									<a href="?page=customer:new">
										<img src="images/icons/new_employee.gif" border="0"
											onMouseOver="ddrivetip('Add New Employee')" onMouseOut="hideddrivetip()">
									</a>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<table width="100%" border="0" cellpadding="8" cellspacing="0">
				<tr>
					<td class="olotd">
					
						<table class="olotable" border="0" cellpadding="5" cellspacing="0" width="100%" summary="Customer Contact">
							<tr>
								<td class="olohead" colspan="4">New Employee Information</td>
							</tr><tr>						
								<td class="menutd"><b>Contact</b></td>
								<td class="menutd"> {$VAR.displayName}</td>
								<td class="menutd"><b>Email</b></td>
								<td class="menutd"> {$VAR.email}</td>
							</tr><tr>
								<td class="menutd"><b>First Name</b></td>
								<td class="menutd">{$VAR.firstName}</td>
								<td class="menutd"><b>Last Name</b>
								<td class="menutd">{$VAR.lastName}</td>
							</tr><tr>
								<td class="row2" colspan="4">&nbsp;</td>
							</tr><tr>
								<td class="menutd"><b>Address</b></td>
								<td class="menutd"></td>
								<td class="menutd"><b>Home</b></td>
								<td class="menutd">{$VAR.homePhone}</td>
							</tr><tr>
								<td class="menutd" colspan="2">{$VAR.address}</td>			
								<td class="menutd"><b>Work</b></td>
								<td class="menutd"> {$VAR.workPhone}</td>
							</tr><tr>
								<td class="menutd"> {$VAR.city},</td>
								<td class="menutd">{$VAR.state} {$VAR.zip}</td>
								<td class="menutd"><b>Mobile</b></td>
								<td class="menutd"> {$VAR.mobilePhone}</td>
							</tr><tr>
								<td class="row2" colspan="4">&nbsp;</td>
							</tr><tr>
								<td class="menutd"><b>Type</b></td>
								<td class="menutd"> {$VAR.type}</td>
								<td class="menutd"><b>Login:</b></td>
								<td class="menutd">{$VAR.login}</td>
							</tr><tr>
								<td class="row2" colspan="4">&nbsp;</td>
							</tr>
						</table>
						<p>&nbsp;</p>
						<p>&nbsp;</p>
						<p>&nbsp;</p>
						<p>&nbsp;</p>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
