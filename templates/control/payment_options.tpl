<!-- payment options.tpl -->
<table width="100%" border="0" cellpadding="20" cellspacing="0">
	<tr>
		<td><!-- Begin Page -->
			<table width="700" cellpadding="5" cellspacing="0" border="0" >
				<tr>
					<td class="menuhead2" width="80%">&nbsp;Payment Options	</td>
				</tr><tr>
					<td class="menutd2">
					
					<table width="100%" class="olotable" cellpadding="5" cellspacing="0" border="0" >
						<tr>
							<td width="100%" valign="top" class="menutd">
								<!-- Content Here -->
								<form method="POST" action="?page=control:payment_options">
								
									<table><caption><b><font color="RED">Available Payment types</font></b></caption>
										{section name=q loop=$arr}
										<tr>
											<td><b>{$arr[q].BILLING_NAME}</b></td>
											<td>Active: <input type="checkbox" name="{$arr[q].BILLING_OPTION}" {if $arr[q].ACTIVE == 1} checked {/if} value=1 class="olotd5"></td>
										</tr>
										{/section}
									</table>
									<br>
									<br>
                                                                        <!--<b><font color="red" size="+1" >CREDIT CARD PROCESSING VIA AUTHORIZE.NET HAS TEMPORARILY BEEN DISBALED</font></b><br> -->
                                                                        <b><font color="RED">Authorize.Net information</font></b><br>
									If you are enabling credit card billing you must have an Authorize.Net account set up and enbaled. To set up an Authorize.Net account click here. You account information will encrypted before being stored in the database. No credit Card information is stored in the MYIT CRM system. For more information on billing profiles and setup please contact Authorize.Net. If you re-install MYIT CRM you will need to enter your Authorize.Net account settings as a random encyption key is generated at install time. {section name=w loop=$opts}
									<table >
										<tr>
											<td><b>Login:</b></td>
											<td><input type="text" name="AN_LOGIN_ID" value="{$opts[w].AN_LOGIN_ID}" class="olotd5"></td>
										</tr><tr>
											<td><b>Password:</b></td>
											<td><input type="text" name="AN_PASSWORD" value="{$opts[w].AN_PASSWORD}" class="olotd5"> </td>
										</tr><tr>
											<td><b>Transaction Key:</b></td>
											<td><input type="text" name="AN_TRANS_KEY" value="{$opts[w].AN_TRANS_KEY}" size="50" class="olotd5"></td>
										</tr>
									</table>
									<br>
									<br>
									<b><font color="RED">Paypal Information</font></b><br>
									You must have a Paypal Merchant account set and working. Please see https://www.paypal.com/ for more information.
									<table>
										<tr>
											<td><b>Paypal Email</b></td>
											<td><input type="text" name="PP_ID" value="{$opts[w].PP_ID}" size="50" class="olotd5"></td>
										</tr>
                                                                                <tr><td><p></td></tr>
                                                                                <tr>
												<td colspan="2"><font color="RED"><b>Payment Instructions printed on Invoices</b></font></td>
                                                                                        </tr>
                                                                                        <tr>
												<td colspan="2"><b>Check/Cheque Details:</b></td>
                                                                                        </tr>
                                                                                        <tr>
												<td><b>Checks payable to:</b></td>
												<td><input class="olotd5" type="text" name="CHECK_PAYABLE" value="{$opts[w].CHECK_PAYABLE}"></td>
											</tr>
                                                                                        <tr><td><p></td></tr>
                                                                                        <tr>
                                                                                            <td colspan="2"><font color="RED"><b>Direct Deposit Details:</b></font></td>
                                                                                        </tr>
                                                                                        <tr>
												<td><b>Name:</b></td>
												<td><input class="olotd5" type="text" name="DD_NAME" value="{$opts[w].DD_NAME}"></td>
											</tr>
                                                                                        <tr>
												<td><b>Bank:</b></td>
												<td><input class="olotd5" type="text" name="DD_BANK" value="{$opts[w].DD_BANK}"></td>
											</tr>
                                                                                        <tr>
												<td><b>Bank ID/BSB Details:</b></td>
												<td><input class="olotd5" type="text" name="DD_BSB" value="{$opts[w].DD_BSB}"></td>
											</tr>
                                                                                        <tr>
												<td><b>Account Number:</b></td>
												<td><input class="olotd5" type="text" name="DD_ACC" value="{$opts[w].DD_ACC}"></td>
											</tr>
                                                                                        <tr>
												<td><b>Transaction Instructions</b></td>
												<td><textarea class="olotd5" name="DD_INS" cols="50" rows="2" >{$opts[w].DD_INS}</textarea></td>
											</tr>
									</table>
                                                                        <br>
                                                                        <!-- Paymate.com Processing Option -->
                                                                        <b><a href="http://paymate.com" target="new"><font color="RED">Paymate.com information</font></a></b><br>
									Please visit <a href="http://paymate.com">Paymate.com</a> and signup for an account if you wish to enable this payment option
									<table >
										<tr>
											<td><b>Paymate Username:</b></td>
											<td><input type="text" name="PAYMATE_LOGIN" value="{$opts[w].PAYMATE_LOGIN}" class="olotd5"></td>
										</tr><tr>
											<td><b>Password:</b></td>
											<td><input type="PASSWORD" name="PAYMATE_PASSWORD" value="{$opts[w].PAYMATE_PASSWORD}" class="olotd5"> </td>
										</tr><tr>
											<td><b>Paymate Transaction fee- default 1.5%:</b></td>
											<td><input type="text" name="PAYMATE_FEES" value="{$opts[w].PAYMATE_FEES}" size="5" class="olotd5"></td>
										</tr>
									</table>
									<input type="submit" name="submit" value="Submit">
								{/section}
								</form>
								<!-- End Content -->
							</td>
						</tr>
					</table>
				</tr>
			</table>
		</td>
	</tr>
</table>
	