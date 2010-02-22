<!-- template name -->
<table width="100%" border="0" cellpadding="20" cellspacing="0">
	<tr>
		<td>
			<!-- Begin Page -->
			<table width="700" cellpadding="5" cellspacing="0" border="0" >
				<tr>
					<td class="menuhead2" width="80%">&nbsp;Edit The Company Information</td>
				</tr><tr>
					<td class="menutd2">
					<table width="100%" class="olotable" cellpadding="5" cellspacing="0" border="0" >
						<tr>
							<td width="100%" valign="top" >
								<!-- Content Here -->
								{section name=q loop=$company}
									<form method="POST" action="?page=control:company_edit">
										<table  cellpadding="5" cellspacing="0">
											<tr>
												<td align="right"><b>Company Display Name:</b></td>
												<td><input class="olotd5" type="text" name="company_name" value="{$company[q].COMPANY_NAME}"</td>
											</tr><tr>
											  <td align="right"><b>Company ABN:</b></td>
												<td><input class="olotd5" type="text" name="company_abn" value="{$company[q].COMPANY_ABN}"></td>
											</tr><tr>
												<td align="right"><b>Address:</b></td>
												<td><input class="olotd5" type="text" name="address" value="{$company[q].COMPANY_ADDRESS}"></td>
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
												<td align="right"><b>Toll Free:</b></td>
												<td><input class="olotd5" type="text" name="toll_free" value="{$company[q].COMPANY_TOLL_FREE}"></td>
											</tr>
                                                                                        <tr>
                                                                                            <td align="right"><b>Currency Symbol:</b></td>
												<td><input class="olotd5" type="text" name="currency_sym" value="{$company[q].COMPANY_CURRENCY_SYMBOL}"></td>
											</tr>
                                                                                         <tr>
												<td align="right"><b>Currency Code:</b></td>
												<td><input class="olotd5" type="text" name="currency_code" value="{$company[q].COMPANY_CURRENCY_CODE}"></td>
											</tr>
                                                                                        <tr>
												<td align="right"><b>Date Formatting:</b></td>
                                                                                                <td><select name="date_format">
                                                                                                        <option value="%d/%m/%Y" { if $company[q].COMPANY_DATE_FORMAT == '%d/%m/%Y' } SELECTED {/if}>dd/mm/yyyy</option>
                                                                                                        <option value="%m/%d/%Y" { if $company[q].COMPANY_DATE_FORMAT == '%m/%d/%Y' } SELECTED {/if}>mm/dd/yyyy</option>
                                                                                                        <option value="%d/%m/%y" { if $company[q].COMPANY_DATE_FORMAT == '%d/%m/%y' } SELECTED {/if}>dd/mm/yy</option>
                                                                                                        <option value="%m/%d/%y" { if $company[q].COMPANY_DATE_FORMAT == '%m/%d/%y' } SELECTED {/if}>mm/dd/yy</option></select>

                                                                                                </td>
											</tr>
                                                                                        <tr>
												<td align="right"><b>Default No-Reply Email:</b></td>
												<td><input class="olotd5" type="text" name="email_from" value="{$company[q].COMPANY_EMAIL_FROM}"></td>
											</tr>
                                                                                        <tr>
												<td align="right"><b>Email SMTP Server:</b></td>
												<td><input class="olotd5" type="text" name="email_server" value="{$company[q].COMPANY_EMAIL_SERVER}"></td>
											</tr>

                                                                                        <tr>
												<td align="right"><b>Email Port:</b></td>
												<td><input class="olotd5" type="text" name="email_port" value="{$company[q].COMPANY_EMAIL_PORT}"></td>
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
									</form>
								{/section}
								<!-- End Content -->
							</td>
						
					</table>
				</tr>
			</table>
		</td>
	</tr>
</table>
	
