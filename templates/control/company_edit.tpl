<!-- template name -->
 <form method="POST" action="?page=control:company_edit">
<table width="100%" border="0" cellpadding="20" cellspacing="0">

	<tr>
		<td><div id="tabs_container">
                <ul class="tabs">
                    <li class="active"><a href="#" rel="#tab_1_contents" class="tab"><img src="images/icons/key.png" alt="" border="0" height="14" width="14" />&nbsp;Company Details</a></li>
                    <li><a href="#" rel="#tab_2_contents" class="tab"><img src="images/icons/money.png" alt="" border="0" height="14" width="14" />&nbsp;Localisation Setup</a></li>
                   <!-- <li><a href="#" rel="#tab_3_contents" class="tab"><img src="images/icons/16x16/email.jpg" alt="" border="0" height="14" width="14" />&nbsp;Email Setup</a></li>
                    <li><a href="#" rel="#tab_4_contents" class="tab"><img src="images/icons/16x16/email.jpg" alt="" border="0" height="14" width="14" />&nbsp;Email Messages</a></li> -->

                </ul>

                <!-- This is used so the contents don't appear to the
                     right of the tabs -->
                <div class="clear"></div>

                <!-- This is a div that hold all the tabbed contents -->
                <div class="tab_contents_container">
                    <!-- Tab 1 Contents -->
                    <div id="tab_1_contents" class="tab_contents tab_contents_active">
			<!-- Begin Page -->
			<table width="100%" cellpadding="5" cellspacing="0" border="0" >
				<tr>
					<td class="menuhead2" width="80%">&nbsp;Edit The Company Information</td>
				</tr><tr>
					<td class="menutd2">
					<table width="100%" class="olotable" cellpadding="5" cellspacing="0" border="0" >
						<tr>
							<td width="100%" valign="top" >
								<!-- Content Here -->
								{section name=q loop=$company}
									
										<table  cellpadding="5" cellspacing="0">
											<tr>
												<td align="right"><b>Company Display Name:</b></td>
												<td><input class="olotd5" type="text" name="company_name" value="{$company[q].COMPANY_NAME}"</td>
											</tr><tr>
											  <td align="right"><b>Company ABN:</b></td>
												<td><input class="olotd5" type="text" name="company_abn" value="{$company[q].COMPANY_ABN}"></td>
											</tr><tr>
												<td align="right"><b>Address:</b></td>
                                                                                                <td><textarea class="olotd5" cols="30" rows="3"  name="address" >{$company[q].COMPANY_ADDRESS}</textarea></td>
											</tr><tr>
												<td align="right"><b>City:</b></td>
												<td><input class="olotd5" type="text" name="city" value="{$company[q].COMPANY_CITY}"></td>
											</tr><tr>
												<td align="right"><b>State:</b></td>
												<td><input class="olotd5" type="text" name="state" value="{$company[q].COMPANY_STATE}">
        										</td>
											</tr><tr>
												<td align="right"><b>Zip:</b></td>
												<td><input class="olotd5" type="text" name="zip" value="{$company[q].COMPANY_ZIP}"></td>
											</tr><tr>
													<td align="right"><b>Country</b></td>
												<td>
													<select name="country" class="olotd5">
													{section name=c loop=$country}
  														<option value="{$country[c].code}" {if $company[q].COMPANY_COUNTRY == $country[c].code} selected {/if} >{$country[c].name}</option>
														
													{/section}
													</select>
												</td>
											</tr><tr>
												<td align="right"><b>Phone:</b></td>
												<td><input class="olotd5" type="text" name="phone" value="{$company[q].COMPANY_PHONE}"></td>
											</tr><tr>
												<td align="right"><b>Mobile Phone:</b></td>
												<td><input class="olotd5" type="text" name="mobile_phone" value="{$company[q].COMPANY_MOBILE}"></td>
											</tr><tr>
												<td align="right"><b>Fax:</b></td>
												<td><input class="olotd5" type="text" name="fax" value="{$company[q].COMPANY_FAX}"></td>
											</tr>
                                                                                        {section name=w loop=$setup}
											<!-- <tr>
												<td><b>PDF Printing:</b></td>
												<td><input class="olotd5" type="checkbox" name="pdf_print" value="1" { if $setup[w].PDF_PRINT == 1 } checked {/if}></td>
											</tr> -->
											
											<tr>
												<td align="right"><b>Tax Amount:</b></td>
												<td><input type="text" size="6" name="inv_tax" value="{$setup[w].INVOICE_TAX}" class="olotd5">%</td>
											</tr><tr>
												<td align="right"><b>Invoice Starting Number:</b></td>
                                                                                                <td><input class="olotd5" type="text" name="inv_number" value="{$setup[w].INVOICE_NUMBER_START}"></td>
                                                                                        </tr><tr>
                                                                                            <td colspan="3" align="center"><b>eg: 2000 - this will start invoice increments from 2000 onwards</b><br></td>
											</tr><tr>
												<td colspan="2"><b>Invoice Thank You Note:</b> 255 max characters. Displays at the bottom of each invoice.</td>
											</tr><tr>
												<td colspan="2"><textarea class="olotd5" cols="80" rows="5" name="inv_thank_you">{$setup[w].INV_THANK_YOU}</textarea></td>
											</tr><tr>
												<td><b>Company Welcome Note</b> (home page)</td>
											</tr><tr>
												<td colspan="2"><textarea class="olotd5" cols="80" rows="5" name="welcome">{$setup[w].WELCOME_NOTE}</textarea></td>	
                                                                                        </tr>
                                                                                        <tr>
                                                                                        <td colspan="2"> <input class="olotd5" type="submit" name="submit"  value="Update"></td>
                                                                                        </tr>
											{/section}
											
										</table>
									
								{/section}
								<!-- End Content -->
							</td>
						
					</table>
				</tr>
			</table>
                    </div>   
                    <!-- Tab 2 Contents -->
                    <div id="tab_2_contents" class="tab_contents">                        
                        <table width="100%" cellpadding="5" cellspacing="0" border="0" >
				<tr>
					<td class="menuhead2" width="80%">&nbsp;Edit your Companies Currency Settings</td>
				</tr><tr>
					<td class="menutd2">
					<table width="100%" class="olotable" cellpadding="5" cellspacing="0" border="0" >
						<tr>
							<td width="100%" valign="top" >
								<!-- Content Here -->
								{section name=q loop=$company}
										<table  cellpadding="5" cellspacing="0">											
                                                                                        <tr>
                                                                                            <td align="right"><b>Currency Symbol:</b></td>
												<td><input class="olotd5" type="text" size="5" name="currency_sym" value="{$company[q].COMPANY_CURRENCY_SYMBOL}"></td>
											</tr>
                                                                                         <tr>
												<td align="right"><b>Currency Code:</b></td>
												<td><input class="olotd5" type="text" size="5" name="currency_code" value="{$company[q].COMPANY_CURRENCY_CODE}">eg: "USD" = US Dollars, "AUD" = Australian Dollars, "GBP" = British Pound.</td>
                                                                                                
                                                                                         </tr>
                                                                                        <tr>
												<td align="right"><b>Date Formatting:</b></td>
                                                                                                <td><select name="date_format" class="olotd5">
                                                                                                        <option value="%d/%m/%Y" { if $company[q].COMPANY_DATE_FORMAT == '%d/%m/%Y' } SELECTED {/if}>dd/mm/yyyy</option>
                                                                                                        <option value="%m/%d/%Y" { if $company[q].COMPANY_DATE_FORMAT == '%m/%d/%Y' } SELECTED {/if}>mm/dd/yyyy</option>
                                                                                                        <option value="%d/%m/%y" { if $company[q].COMPANY_DATE_FORMAT == '%d/%m/%y' } SELECTED {/if}>dd/mm/yy</option>
                                                                                                        <option value="%m/%d/%y" { if $company[q].COMPANY_DATE_FORMAT == '%m/%d/%y' } SELECTED {/if}>mm/dd/yy</option></select>

                                                                                                </td>
											</tr>
                                                                                        
											{section name=w loop=$setup}
											 <tr>
                                                                                        <td colspan="2"> <input class="olotd5" type="submit" name="submit"  value="Update"></td>
                                                                                        </tr>
											{/section}

										</table>
									
								{/section}
								<!-- End Content -->
							</td>

					</table>
				</tr>
			</table>
                    </div>
                                        <!-- Tab 3 Contents 
                    <div id="tab_3_contents" class="tab_contents">                        
                        <table width="100%" cellpadding="5" cellspacing="0" border="0" >
				<tr>
					<td class="menuhead2" width="80%">&nbsp;Edit your Companies Email Settings</td>
				</tr><tr>
					<td class="menutd2">
					<table width="100%" class="olotable" cellpadding="5" cellspacing="0" border="0" >
						<tr>
							<td width="100%" valign="top" >
								
								{section name=q loop=$company}
									
										<table  cellpadding="5" cellspacing="0">

                                                                                        <tr>
												<td align="right"><b>Default No-Reply Email:</b></td>
												<td><input class="olotd5" type="text" size="50" name="email_from" value="{$company[q].COMPANY_EMAIL_FROM}"></td>
											</tr>
                                                                                        <tr>
												<td align="right"><b>Email SMTP Server:</b></td>
												<td><input class="olotd5" type="text" size="50" name="email_server" value="{$company[q].COMPANY_EMAIL_SERVER}"></td>
											</tr>

                                                                                        <tr>
												<td align="right"><b>Email Port:</b></td>
												<td><input class="olotd5" type="text" size="5" name="email_port" value="{$company[q].COMPANY_EMAIL_PORT}"></td>
											</tr>
                                                                                        <tr>
												<td align="right"><b>Connection Type:</b></td>
                                                                                                <td><select class="olotd5" name="conn_type">
                                                                                                        <option value="SSL" { if $company[q].COMPANY_EMAIL_CONNECTION_TYPE == 'SSL' } SELECTED {/if}>SSL</option>
                                                                                                        <option value="" { if $company[q].COMPANY_EMAIL_CONNECTION_TYPE != 'SSL' } SELECTED {/if}>None</option>
                                                                                                    </select>
											</tr>
                                                                                        <tr>
												<td align="right"><b>SMTP Login Name:</b></td>
												<td><input class="olotd5" type="text" size="50" name="email_login" value="{$company[q].COMPANY_SMTP_USERNAME}"><font color="RED">*</font>Only required if authentication is needed</td>
											</tr>
                                                                                        <tr>
												<td align="right"><b>SMTP Server Password:</b></td>
												<td><input class="olotd5" size="50" type="password" name="email_password" value="{$company[q].COMPANY_SMTP_PASSWORD}"><font color="RED">*</font>Only required if authentication is needed</td>
											</tr>
											{section name=w loop=$setup}
											 <tr>
                                                                                        <td colspan="2"> <input class="olotd5" type="submit" name="submit"  value="Update"></td>
                                                                                        </tr>
											{/section}

										</table>
								
								{/section}
							</td>

					</table>
				</tr>
			</table>
                    </div>
                    <div id="tab_4_contents" class="tab_contents">
                        <table width="100%" cellpadding="5" cellspacing="0" border="0" >
				<tr>
					<td class="menuhead2" width="80%">&nbsp;Edit Email Messaging functions</td>
				</tr><tr>
					<td class="menutd2">
					<table width="100%" class="olotable" cellpadding="5" cellspacing="0" border="0" >
						<tr>
							<td width="100%" valign="top" >
								{section name=e loop=$setup}

										<table  cellpadding="5" cellspacing="0">

                                                                                        <tr>
												<td align="left"><b>New Invoice Message:</b></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                                <td align="left"><b>Enabled:</b>
                                                                                                    <select id="new_invoice_enabled">
                                                                                                        <option value="1" { if $setup[e].EMAIL_MSG_NEW_INVOICE_ACTIVE == '1' } SELECTED {/if}>Yes</option>
                                                                                                        <option value="0" { if $setup[e].EMAIL_MSG_NEW_INVOICE_ACTIVE == '0' } SELECTED {/if}>No</option>
                                                                                                    </select>
                                                                                                </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td><textarea cols="80" rows="15" class="olotd5" name="new_invoice" >{$setup[e].EMAIL_MSG_NEW_INVOICE}</textarea></td>

                                                                                        </tr>

											{section name=w loop=$setup}
											 <tr>
                                                                                        <td colspan="2"> <input class="olotd5" type="submit" name="submit"  value="Update"></td>
                                                                                        </tr>
											{/section}

										</table>
                                                                                <table  cellpadding="5" cellspacing="0">

                                                                                        <tr>
												<td align="left"><b>New Work Order Created Message:</b></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                                <td align="left"><b>Enabled:</b>
                                                                                                    <select id="new_wo_enabled">
                                                                                                        <option value="1" { if $setup[e].EMAIL_MSG_WO_CREATED_ACTIVE == '1' } SELECTED {/if}>Yes</option>
                                                                                                        <option value="0" { if $setup[e].EMAIL_MSG_WO_CREATED_ACTIVE == '0' } SELECTED {/if}>No</option>
                                                                                                    </select>
                                                                                                </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td><textarea cols="80" rows="15" class="olotd5" name="new_wo" >{$setup[e].EMAIL_MSG_WO_CREATED}</textarea></td>

                                                                                        </tr>

											{section name=w loop=$setup}
											 <tr>
                                                                                        <td colspan="2"> <input class="olotd5" type="submit" name="submit"  value="Update"></td>
                                                                                        </tr>
											{/section}

										</table>

								{/section}
							</td>

					</table>
				</tr>
			</table>
                    </div> -->
                    </div>
                    </div>
		</td>
	</tr>
</table></form>

	
