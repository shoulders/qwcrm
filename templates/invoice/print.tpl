<html>
    <head>
        <title>{$translate_invoice_invoice}#{$invoice.INVOICE_ID}</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <link href="css/default.css" rel="stylesheet" type="text/css">
    </head>
    <body>
        <!-- Left Column -->
        <table  width="697" border="0" cellpadding="6" cellspacing="0" style="border-collapse: collapse;">
            <tr>
                <!-- LOGO -->
                <td valign="center" align="center" width="10%">
                    <table  width="10%" border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
                        <tr>
                            <td>
                                <a><img src="images/logo.jpg" alt="" height="40" border="0"></a>
                            </td>
                        </tr>
                    </table>
                </td>
                <td valign="top" align="right" width="90%">
                    <!-- COMPANY DETAILS -->

                    {foreach item=item from=$company}
                    <font size="+0">{$item.COMPANY_NAME}</font><br>
                    <b> Address:</b> {$item.COMPANY_ADDRESS}<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$item.COMPANY_CITY}, {$item.COMPANY_STATE} {$item.COMPANY_ZIP}<br>
                    <b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PH:</b> {$item.COMPANY_PHONE}<br>
                    <b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ABN:</b> {$item.COMPANY_ABN}<br>
                    <b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Email:</b> {$item.COMPANY_EMAIL}<br>
			{/foreach}
                </td>
            </tr>
        </table>
        <!-- Invoice details -->
        <table width="697" border="0" width="30%" cellpadding="3" cellspacing="0" style="border-collapse: collapse;">
            <tr>
                <td valign="top" width="90%" align="right">
                </td>
                <td align="top" class="olotd5" width="200" >
                    <table width="180" border="1" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
                        <tr>
                            <td>
                                <b>Invoice #</b>  {$invoice.INVOICE_ID}<br>
                                <b>Invoice Status -  {$stats2.CONFIG_WORK_ORDER_STATUS}</b><br>
                                <b>Invoice Date -</b>  {$invoice.INVOICE_DATE|date_format:"$date_format"} <br>
                                <b>Due Date -</b>  {$invoice.INVOICE_DUE|date_format:"$date_format"}<br>
                                <br>
                                <b>Work Order #</b>  {$invoice.WORKORDER_ID}<br>
                                <b>Tech</b>  {$invoice.EMPLOYEE_DISPLAY_NAME}<br>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <table  width="700" border="0" cellpadding="3" cellspacing="0" >
            <tr>
                <td valign="top" width="30%" align="left">
                    <font size="-1"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Bill To:</b></font><br>

			{foreach item=item from=$customer_details}
                    <font size="+0">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$item.CUSTOMER_DISPLAY_NAME}</font><br>
                    <font size="+0">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$item.CUSTOMER_ADDRESS}</font><br>
                    <font size="+0">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$item.CUSTOMER_CITY}, {$item.CUSTOMER_STATE} {$item.CUSTOMER_ZIP}</font><br>
			{/foreach}
                </td>
            </tr>
        </table>
        <br>
        <table width="700" border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
            <tr>
                <td align="center" class="olotd5" ><font size="+2">INVOICE</font></td>
            </tr>
        </table>
        <br>
        <b>Invoiced Items</b>
        <table width="700" border="1" cellpadding="3" cellspacing="0" style="border-collapse: collapse;">
            <tr>
                <td width="40" class="olohead"><b>QTY</b></td>
                <td class="olohead"><b>Description</b></td>
                <td class="olohead" width="40"><b>Amount</b></td>
                <td class="olohead" width="80"><b>Sub Total</b></td>
            </tr>
	{section name=q loop=$labor}
            <tr>
                <td class="olotd4" width="40">{$labor[q].INVOICE_LABOR_UNIT}</td>
                <td class="olotd4" >{$labor[q].INVOICE_LABOR_DESCRIPTION}</td>
                <td class="olotd4" width="40" align="right">{$currency_sym}{$labor[q].INVOICE_LABOR_RATE|string_format:"%.2f"}</td>
                <td class="olotd4" width="80" align="right">{$currency_sym}{$labor[q].INVOICE_LABOR_SUBTOTAL|string_format:"%.2f"}</td>
            </tr>
	{/section}
        </table>
        <table width="700" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse;">
	{section name=w loop=$parts}		
            <tr class="olotd4">
                <td width="40" class="olotd4">{$parts[w].INVOICE_PARTS_COUNT}</td>
                <td class="olotd4">{$parts[w].INVOICE_PARTS_DESCRIPTION}</td>
                <td width="50" class="olotd4" align="right">{$currency_sym}{$parts[w].INVOICE_PARTS_AMOUNT|string_format:"%.2f"}</td>
                <td width="80" class="olotd4" align="right">{$currency_sym}{$parts[w].INVOICE_PARTS_SUBTOTAL|string_format:"%.2f"}</td>
            </tr>
	{/section}
        </table>
        <BR>
        <table width="700" border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
            <tr>
                <td colspan="1" valign="TOP">
                    <table width="500" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse;">
                        <tr>
                            <td align="left" ><font size="-1"><b>Payment Instructions</b></font></td>
                        </tr>
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
                                <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business={$PP_ID}&item_name=Payment%20for%20invoice%20{$invoice.INVOICE_ID}&item_number={$invoice.INVOICE_ID}&description=Invoice%20for%20{$invoice.INVOICE_ID}&amount={$pamount}&no_note=Thankyou%20for%20your%20buisness.&currency_code={$currency_code}&lc='.$country." target="_blank" ><img src="images/paypal/pay_now.gif" height="40"  alt="PayPal - The safer, easier way to pay online"><br>Click on "Pay Now" to pay this invoice via PayPal using a valid Credit Card.<BR>
                                    <I><B>NOTE:- A 1.5% surcharge applies to this type of payment.</B></I><BR></a>
                            </td>
                        </tr>
                        {/if}
                        {if $PP_ID == "" & $CHECK_PAYABLE == "" & $DD_NAME == ""}
                        <tr>
                            <td>Please call us to discuss payment options</td>
                        </tr>
                        {/if}
                    </table>
                </td>
         
<td colspan="2" valign="TOP">
    <table width="200" border="1" cellpadding="3" cellspacing="0" style="border-collapse: collapse;">
        <tr>
            <td class="olotd4" align="left"><b>Sub Total</b></td>
            <td class="olotd4" width="80" align="right">{$currency_sym} {$invoice.SUB_TOTAL|string_format:"%.2f"}</td>
        </tr><tr>
            <td class="olotd4"><b>Tax</b></td>
            <td class="olotd4" width="80" align="right">{$currency_sym} {$invoice.TAX|string_format:"%.2f"}</td>
        </tr><tr>
            <td class="olotd4"><b>Shipping</b></td>
            <td class="olotd4" width="80" align="right">{$currency_sym}{$invoice.SHIPPING|string_format:"%.2f"}</td>
        </tr><tr>
            <td class="olotd4"><b>Discount</b></td>
            <td class="olotd4" width="80" align="right">- {$currency_sym} {$invoice.DISCOUNT|string_format:"%.2f"}</td>
        </tr><tr>
            <td class="olotd4"><b>Invoice Total</b></td>
            <td class="olotd4" width="80" align="right"><b>{$currency_sym} {$invoice.INVOICE_AMOUNT|string_format:"%.2f"}</b></td>
        </tr><tr>
            <td class="olotd4"><b>Payments Made</b></td>
            <td class="olotd4" width="80" align="right">{$currency_sym} {$payments.AMOUNT|string_format:"%.2f"}</td>
        </tr><tr>
            <td class="olotd4"><b>Invoice Balance</b></td>
						{if $invoice.PAID_AMOUNT == 0}
            <td class="olotd4" width="80" align="right"><b><font color="#CC0000">{$currency_sym} {$invoice.INVOICE_AMOUNT-$payments.AMOUNT|string_format:"%.2f"}</font></b></td>
				    {else}
            <td class="olotd4" width="80" align="right"><b><font color="#CC0000">{$currency_sym} {$invoice.INVOICE_AMOUNT-$payments.AMOUNT|string_format:"%.2f"}</font></b></td>
				    {/if}
        </tr>
    </table>
</td>
</tr>
</table>
<br>
<br>
{if $thank_you > ''}
<table width="700" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse;">
    <tr>
        <td  align="center"><font size="-1">{$thank_you}</font></td>
    </tr>
</table>
{/if}
<br>
<br>
</body>
</html>
