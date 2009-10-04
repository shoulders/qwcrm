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
												<td><b>Company Display Name:</b></td>
												<td><input class="olotd5" type="text" name="company_name" value="{$company[q].COMPANY_NAME}"</td>
											</tr><tr>
											  <td><b>Company ABN:</b></td>
												<td><input class="olotd5" type="text" name="company_abn" value="{$company[q].COMPANY_ABN}"></td>
											</tr><tr>
												<td><b>Address:</b></td>
												<td><input class="olotd5" type="text" name="address" value="{$company[q].COMPANY_ADDRESS}"></td>
											</tr><tr>
												<td><b>City:</b></td>
												<td><input class="olotd5" type="text" name="city" value="{$company[q].COMPANY_CITY}"></td>
											</tr><tr>
												<td><b>State:</b></td>
												<td><input class="olotd5" type="text" name="state" value="{$company[q].COMPANY_STATE}">
        										</td>
											</tr><tr>
												<td><b>Zip:</b></td>
												<td><input class="olotd5" type="text" name="zip" value="{$company[q].COMPANY_ZIP}"></td>
											</tr><tr>
													<td><b>Country</b></td>
												<td>
													<select name="country" class="olotd5">
													{section name=c loop=$country}
  														<option value="{$country[c].code}" {if $company[q].COMPANY_COUNTRY == $country[c].code} selected {/if} >{$country[c].name}</option>
														
													{/section}
													</select>
												</td>
											</tr><tr>
												<td><b>Phone:</b></td>
												<td><input class="olotd5" type="text" name="phone" value="{$company[q].COMPANY_PHONE}"></td>
											</tr><tr>
												<td><b>Mobile Phone:</b></td>
												<td><input class="olotd5" type="text" name="mobile_phone" value="{$company[q].COMPANY_MOBILE}"></td>
											</tr><tr>
												<td><b>Toll Free:</b></td>
												<td><input class="olotd5" type="text" name="toll_free" value="{$company[q].COMPANY_TOLL_FREE}"></td>
											</tr>
											
											{section name=w loop=$setup}
											<!-- <tr>
												<td><b>PDF Printing:</b></td>
												<td><input class="olotd5" type="checkbox" name="pdf_print" value="1" { if $setup[w].PDF_PRINT == 1 } checked {/if}></td>
											</tr> -->
											
											<tr>
												<td><b>Tax Amount:</b></td>
												<td><input type="text" size="6" name="inv_tax" value="{$setup[w].INVOICE_TAX}" class="olotd5">%</td>
											</tr><tr>
												<td colspan="2"><b>Invoice Thank You Note:</b> 255 max characters. Displays at the bottom of each invoice.</td>
											</tr><tr>
												<td colspan="2"><textarea class="olotd5" cols="80" rows="5" name="inv_thank_you">{$setup[w].INV_THANK_YOU}</textarea></td>
											</tr><tr>
												<td><b>Company Welcome Note</b> (home page)</td>
											</tr><tr>
												<td colspan="2"><textarea class="olotd5" cols="80" rows="5" name="welcome">{$setup[w].WELCOME_NOTE}</textarea></td>	
												<tr>
                        <td colspan="2"> <input class="olotd5" type="submit" name="submit"  value="Update"></td>
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
	
