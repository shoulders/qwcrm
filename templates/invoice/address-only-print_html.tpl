<html>
    <head>
        <title>{$translate_invoice_invoice}#{$invoice.INVOICE_ID}</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <link href="css/default.css" rel="stylesheet" type="text/css">
    </head>
    <body>

<!-- BOF Top Row Section -->
        <table  width="700" height="125" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse;">
            <tr>
                <!-- COMPANY DETAILS -->
                <td valign="top" align="left" width="35%">
                    {foreach item=item from=$company}
                    <table border="0" cellpadding="0" cellspacing="0">
                        <!--<tr>
                            <td colspan="2"><b>{$item.COMPANY_NAME}</b></td>
                            </tr>-->
                        <tr>
                            <td valign="top"><b>{$translate_invoice_prn_address} :&nbsp;</b></td>
                            <td>{$item.COMPANY_ADDRESS|nl2br|regex_replace:"/[\r\t\n]/":" "}<br />{$item.COMPANY_CITY},<br />{$item.COMPANY_STATE},<br />{$item.COMPANY_ZIP}</td>
                        </tr>
                        <tr>
                            <td><b>{$translate_invoice_prn_phone} :&nbsp;</b></td>
                            <td>{$item.COMPANY_PHONE}</td>
                        </tr>
                        <tr>
                            <td><b>{$translate_invoice_prn_email} :&nbsp;</b></td>
                            <td>{$item.COMPANY_EMAIL}</td>
                        </tr>
                        <tr>
                            <td><b>{$translate_invoice_prn_abn} :&nbsp;</b></td>
                            <td>{$item.COMPANY_ABN}</td>
                        </tr>
                    </table>
                    {/foreach}
		</td>

                <!-- LOGO and Company Name-->
                <td valign="top" align="center" width="30%">
                    <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
                        <tr>
                            <td width="100%">
                                <a><img src="images/logo.jpg" alt="" border="0"></a>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align:center"><font size="+0"><b>{$item.COMPANY_NAME}</b></font></td>
                        </tr>
                    </table>
                </td>

                <!-- Invoice details -->
                <td valign="top" align="right" width="35%">
                    <table border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse;">
                        <tr>
                            <td valign="top" width="90%" align="right">
                            </td>
                            <td align="top" class="olotd5" width="200" >
                                <table width="180" border="1" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
                                    <tr>
                                        <td>
                                            <b>{$translate_invoice_prn_invoice_id} - </b>{$invoice.INVOICE_ID}<br>
                                            <b>{$translate_invoice_prn_invoice_status} - </b>{$stats2.CONFIG_WORK_ORDER_STATUS}<br>
                                            <b>{$translate_invoice_prn_invoice_date} - </b>  {$invoice.INVOICE_DATE|date_format:"$date_format"} <br>
                                            <b>{$translate_invoice_prn_invoice_due_date} - </b>  {$invoice.INVOICE_DUE|date_format:"$date_format"}<br>
                                            <b>{$translate_invoice_prn_work_order} - </b>{$invoice.WORKORDER_ID}<br>
                                            <b>{$translate_invoice_prn_technician} - </b>{$invoice.EMPLOYEE_DISPLAY_NAME}<br>
                                            {foreach item=item from=$customer_details}
                                            <b>{$translate_invoice_prn_credit_terms} - </b>{$item.CREDIT_TERMS}<br>
                                            {/foreach}
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
<!-- EOF Top Row Section - NB This table ends exactly where the envelope window starts-->
    <br />
<!-- BOF Address Row -->
        <table  width="700" border="0" cellpadding="3" cellspacing="0" >
            <tr>
                <td valign="top" width="10%" align="left"></td>
                <td>
                    <!--<font size="-1"><b>{$translate_invoice_prn_bill_to} :</b></font><br />-->
			{foreach item=item from=$customer_details}
                    <font size="+0">{$item.CUSTOMER_DISPLAY_NAME}</font><br />
                    <font size="+0">{$item.CUSTOMER_ADDRESS|nl2br|regex_replace:"/[\r\t\n]/":" "}</font><br />
                    <font size="+0">{$item.CUSTOMER_CITY},</font><br />
                    <font size="+0">{$item.CUSTOMER_STATE} {$item.CUSTOMER_ZIP}</font>
			{/foreach}
                </td>
            </tr>
        </table>
<!-- EOF Address Row -->
        <br />
<!-- BOF Invoice Details Row -->
        <table width="700" border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
            <tr>
                <td align="center" class="olotd5" ><font size="+2">{$translate_invoice_prn_invoice_details} {$item.CUSTOMER_DISPLAY_NAME}</font></td>
            </tr>
        </table>
<!-- EOF Invoice Details Row -->
        <br />
<!-- BOF Items Table Section -->
        <!-- Labour Table -->
        <table width="700" border="1" cellpadding="3" cellspacing="0" style="border-collapse: collapse;">
            <tr>
                <td width="40" class="olohead"><b>{$translate_invoice_prn_qty}</b></td>
                <td class="olohead"><b>{$translate_invoice_prn_labour_items}</b></td>
                <td class="olohead" width="60" align="right"><b>{$translate_invoice_prn_unit_price}</b></td>
                <td class="olohead" width="80" align="right"><b>{$translate_invoice_prn_subtotal}</b></td>
            </tr>
	{section name=q loop=$labor}
            <tr>
                <td class="olotd4" width="40">{$labor[q].INVOICE_LABOR_UNIT}</td>
                <td class="olotd4" >{$labor[q].INVOICE_LABOR_DESCRIPTION}</td>
                <td class="olotd4" width="60" align="right">{$currency_sym}{$labor[q].INVOICE_LABOR_RATE|string_format:"%.2f"}</td>
                <td class="olotd4" width="80" align="right">{$currency_sym}{$labor[q].INVOICE_LABOR_SUBTOTAL|string_format:"%.2f"}</td>
            </tr>
	{/section}
            <tr>
                <td colspan="3" style="text-align:right;">{$translate_invoice_prn_labour_total}</td>
                <td class="olotd4" width="80" align="right">{$currency_sym}{$labour_sub_total_sum|default:"0.00"}</td>
            </tr>
        </table>
        <br />

         <!-- Parts Table -->
        <table width="700" border="1" cellpadding="3" cellspacing="0" style="border-collapse: collapse;">
            <tr>
                <td width="40" class="olohead"><b>{$translate_invoice_prn_qty}</b></td>
                <td class="olohead"><b>{$translate_invoice_prn_parts_items}</b></td>
                <td class="olohead" width="60" align="right"><b>{$translate_invoice_prn_unit_price}</b></td>
                <td class="olohead" width="80" align="right"><b>{$translate_invoice_prn_subtotal}</b></td>
            </tr>
	{section name=w loop=$parts}		
            <tr class="olotd4">
                <td width="40" class="olotd4">{$parts[w].INVOICE_PARTS_COUNT}</td>
                <td class="olotd4">{$parts[w].INVOICE_PARTS_DESCRIPTION}</td>
                <td width="60" class="olotd4" align="right">{$currency_sym}{$parts[w].INVOICE_PARTS_AMOUNT|string_format:"%.2f"}</td>
                <td width="80" class="olotd4" align="right">{$currency_sym}{$parts[w].INVOICE_PARTS_SUBTOTAL|string_format:"%.2f"}</td>
            </tr>
	{/section}
            <tr>           
                <td colspan="3" style="text-align:right;">{$translate_invoice_prn_parts_total}</td>
                <td class="olotd4" width="80" align="right">{$currency_sym}{$parts_sub_total_sum|default:"0.00"}</td>
            </tr>
        </table>
<!-- BOF Items Table Section -->
         <br />
<!-- BOF Totals Box and Payments Section Row -->
        <table width="700" border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
            <tr>
                <td colspan="1" valign="TOP">
                    <table width="500" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse;">
                        <tr>
                            <td align="left" ><font size="-1"><b>{$translate_invoice_prn_payment_instructions}</b></font></td>
                        </tr>
                        <!-- payments not fully Translated Below -->
                        <tr>
                            <td>
                                {if $CHECK_PAYABLE <> ""}
                        <tr>
                            <td><img src="images/icons/cheque.jpeg" alt="" height="20">&nbsp;- Please make {$translate_invoice_cheque} payable to {$CHECK_PAYABLE}<BR></td>

                        </tr>
                        {/if}
                        {if $DD_NAME <> ""}
                        <tr>
                            <td><img src="images/icons/deposit.jpeg" alt="" height="20">Direct deposit details:-
                                <br>Bank: {$DD_BANK}
                                <br>Name: {$DD_NAME}
                                <br>Branch/BSB: {$DD_BSB}
                                <br>Account: {$DD_ACC}
                                <br>{$DD_INS}
                            </td>
                        </tr>
                        {/if}
                        {if $PP_ID <> ""}
                        <tr>
                            <td>
                                <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business={$PP_ID}&item_name=Payment%20for%20invoice%20{$invoice.INVOICE_ID}&item_number={$invoice.INVOICE_ID}&description=Invoice%20for%20{$invoice.INVOICE_ID}&amount={$pamount}&no_note=Thankyou%20for%20your%20buisness.&currency_sym={$currency_sym}&lc='.$country." target="_blank" ><img src="images/paypal/pay_now.gif" height="20"  alt="PayPal - The safer, easier way to pay online">&nbsp;<< Click on "Pay Now" to pay this invoice via PayPal using a valid Credit Card.<BR>
                                    <I><B><font size="-0.5">* A 1.5% surcharge applies.</font></B></I><BR></a>
                            </td>
                        </tr>
                        {/if}
                        {if $PAYMATE_LOGIN <> ""}
                        <tr valign="top">
                            <td>
                                <a href="https://www.paymate.com/PayMate/ExpressPayment?mid={$PAYMATE_LOGIN}&amt={$paymate_amt}&ref=Payment%20for%20invoice%20{$invoice.INVOICE_ID}&currency={$currency_sym}&amt_editable=N&pmt_sender_email={$customer1.CUSTOMER_EMAIL}&pmt_contact_firstname={$customer1.CUSTOMER_FIRST_NAME}&pmt_contact_surname={$customer1.CUSTOMER_LAST_NAME}&pmt_contact_phone={$customer1.CUSTOMER_PHONE}&regindi_state={$customer1.CUSTOMER_STATE}&regindi_address1={$customer1.CUSTOMER_ADDRESS}&regindi_sub={$customer1.CUSTOMER_CITY}&regindi_pcode={$customer1.CUSTOMER_ZIP}" target="_blank" ><img src="images/paymate/paymate_cc.gif" height="20"  alt="Paymate provides secure, reliable and innovative Internet-based payment services to buyers in 57 countries around the world and sellers in Australia, New Zealand and the USA.">&nbsp;<< Click to pay this invoice via Paymate using a valid Credit Card.<br>
                                    <I><B><font size="-0.5">* A {$PAYMATE_FEES}% surcharge applies.</font></B></I><BR></a>
                            </td>
                        </tr>
                        {/if}
                        {if $PP_ID == "" & $CHECK_PAYABLE == "" & $DD_NAME == "" & $PAYMATE_LOGIN ==""}
                        <tr>
                            <td>{$translate_invoice_prn_discuss_payments}</td>
                        </tr>
                        {/if}
                    </table>
                </td>
         
<td colspan="2" valign="TOP">
    <table width="200" border="1" cellpadding="3" cellspacing="0" style="border-collapse: collapse;">
        <tr>
            <td class="olotd4" align="left"><b>{$translate_invoice_prn_subtotal}</b></td>
            <td class="olotd4" width="80" align="right">{$currency_sym} {$invoice.SUB_TOTAL|string_format:"%.2f"}</td>
        </tr>
        <tr>
            <td class="olotd4"><b>{$translate_invoice_prn_discount}</b></td>
            <td class="olotd4" width="80" align="right">- {$currency_sym} {$invoice.DISCOUNT|string_format:"%.2f"}</td>
        </tr>
        <tr>
            <td class="olotd4"><b>{$translate_invoice_prn_shipping}</b></td>
            <td class="olotd4" width="80" align="right">{$currency_sym} {$invoice.SHIPPING|string_format:"%.2f"}</td>
        </tr>
        <tr>
            <td class="olotd4"><b>{$translate_invoice_prn_tax}</b></td>
            <td class="olotd4" width="80" align="right">{$currency_sym} {$invoice.TAX|string_format:"%.2f"}</td>
        </tr>
        <tr>
            <td class="olotd4"><b>{$translate_invoice_prn_invoice_total}</b></td>
            <td class="olotd4" width="80" align="right"><b>{$currency_sym} {$invoice.INVOICE_AMOUNT|string_format:"%.2f"}</b></td>
        </tr>
        <tr>
            <td class="olotd4"><b>{$translate_invoice_prn_paid}</b></td>
            <td class="olotd4" width="80" align="right">{$currency_sym} {$invoice.PAID_AMOUNT|string_format:"%.2f"}</td>
        </tr>
        <tr>
            <td class="olotd4"><b>{$translate_invoice_prn_balance}</b></td>
						{if $invoice.BALANCE == 0}
            <td class="olotd4" width="80" align="right"><b><font color="#CC0000">{$currency_sym} {$invoice.BALANCE|string_format:"%.2f"}</font></b></td>
				    {else}
            <td class="olotd4" width="80" align="right"><b><font color="#CC0000">{$currency_sym} {$invoice.BALANCE|string_format:"%.2f"}</font></b></td>
				    {/if}
        </tr>
    </table>
</td>
</tr>
</table>
<!-- EOF Totals Box and Payments Row -->
<br />
<br />
{if $thank_you > ''}
<table width="700" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse;">
    <tr>
        <td  align="center"><font size="-1">{$thank_you}</font></td>
    </tr>
</table>
{/if}
<br />
</body>
</html>
