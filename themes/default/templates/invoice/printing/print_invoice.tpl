<!-- print_invoice_template.tpl -->
{section name=q loop=$company_details}
{section name=c loop=$customer_details}
{section name=i loop=$invoice_details}
<!DOCTYPE html>
<html lang="en-GB">
<head>
    <meta charset="utf-8">
    
    <!-- PDF Title -->
    <title>{$translate_invoice_invoice}#{$invoice_details[i].INVOICE_ID}</title>   
        
    <!-- PDF Subject -->
    <meta name="description" content="{$meta_description}needs translating">
    
    <!-- PDF Keywords -->
    <meta name="keywords" content="{$meta_keywords}needs translating">
    
    <!-- PDF Author -->
    <meta name="author" content="QWcrm - QuantumWarp.com">       
    
    <link rel="icon" href="favicon.ico">
    <link rel="stylesheet" href="{$theme_css_dir}template.css">    
</head>

<body>
    
    <!-- Header Section -->
    
    <table  width="800px" height="125" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse;">
        <tr>
            
            <!-- COMPANY DETAILS -->
            <td valign="top" align="left" width="200px">                
                <table border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td valign="top"><b>{$translate_invoice_prn_address} :&nbsp;</b></td>
                        <td>
                            {$company_details[q].ADDRESS|nl2br|regex_replace:"/[\r\t\n]/":" "}<br>
                            {$company_details[q].CITY},<br>
                            {$company_details[q].STATE},<br>
                            {$company_details[q].ZIP}
                        </td>
                    </tr>
                    <tr>
                        <td><b>{$translate_invoice_prn_phone} :&nbsp;</b></td>
                        <td>{$company_details[q].PHONE}</td>
                    </tr>
                    <tr>
                        <td><b>{$translate_invoice_prn_website} :&nbsp;</b></td>
                        <td>{$company_details[q].WWW}</td>
                    </tr>
                    <tr>
                        <td><b>{$translate_invoice_prn_email} :&nbsp;</b></td>
                        <td>{$company_details[q].EMAIL}</td>
                    </tr>
                    <tr>
                        <td><b>{$translate_invoice_prn_company_number} :&nbsp;</b></td>
                        <td>{$company_details[q].NUMBER}</td>
                    </tr>
                </table>     
            </td>

            <!-- LOGO and Company Name-->
            <td valign="top" align="center" width="300px">
                <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
                    <tr>
                        <td width="100%" align="center"><img src="{$theme_images_dir}logo.png" height="100px" alt="" border="0"></td>
                    </tr>
                    <tr><td style="text-align:center"><b>{$company_details[q].NAME}</b></td></tr>
                </table>
            </td>

            <!-- Invoice details -->
            <td valign="top" align="right" width="200px">
                <table border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse;">
                    <tr>
                        <td valign="top" width="90%" align="right"></td>
                        <td align="top" class="olotd5" width="200" >
                            <table width="180" border="1" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
                                <tr>
                                    <td>
                                        <b>{$translate_invoice_prn_invoice_id} - </b>{$invoice_details[i].INVOICE_ID}<br>
                                        <b>{$translate_invoice_prn_invoice_status} - </b>{section name=w loop=$workorder_details}{$workorder_details[w].WORK_ORDER_STATUS}{/section}<br>
                                        <b>{$translate_invoice_prn_invoice_date} - </b>{$invoice_details[i].DATE|date_format:$date_format} <br>
                                        <b>{$translate_invoice_prn_invoice_due_date} - </b>{$invoice_details[i].DUE_DATE|date_format:$date_format}<br>
                                        <b>{$translate_invoice_prn_work_order} - </b>{$invoice_details[i].WORKORDER_ID}<br>
                                        <b>{$translate_invoice_prn_technician} - </b>{$employee_display_name}<br>                                        
                                        <b>{$translate_invoice_prn_credit_terms} - </b>{$customer_details[c].CREDIT_TERMS}<br>                                       
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <br>
    
    <!-- Workorder and Customer Section -->
    
    <!-- Customer Address -->
    <table width="800" border="0" cellpadding="3" cellspacing="0" >
        <tr>
            <td valign="top" width="10%" align="left"></td>
            <td>
                <p>Customer Details:</p>
                {$customer_details[c].CUSTOMER_DISPLAY_NAME}<br>
                {$customer_details[c].CUSTOMER_ADDRESS|nl2br|regex_replace:"/[\r\t\n]/":" "}<br>
                {$customer_details[c].CUSTOMER_CITY},<br>
                {$customer_details[c].CUSTOMER_STATE}<br>
                {$customer_details[c].CUSTOMER_ZIP}                
            </td>
        </tr>
    </table>
    
    {section name=w loop=$workorder_details}
    <!-- Workorder Row -->
    {if $workorder_details[w].WORK_ORDER_DESCRIPTION > NULL}
        <table  width="800" border="0" cellpadding="3" cellspacing="0" >
            <tr>
                <td><b>{$translate_invoice_prn_work_order}</b></td>
                <td><b>{$translate_invoice_prn_work_order_resolution}</b></td>
            </tr>
            <tr>
                <td width="50%" valign="top">{$workorder_details[w].WORK_ORDER_DESCRIPTION}</td>
                <td width="50%" valign="top" style="border-left: 1px solid;">{$workorder_details[w].WORK_ORDER_RESOLUTION}</td>
            </tr>
        </table>
    {/if}
    <br>
    {/section}

    <!-- Invoice To Box -->
    <table width="800" border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
        <tr>
            <td align="center" class="olotd5" ><font size="+2">{$translate_invoice_prn_invoice_details} {$customer_details[c].CUSTOMER_DISPLAY_NAME}</font></td>
        </tr>
    </table>
    <br>

    <!-- Items Table Section -->

    <!-- Labour Table -->
    <table width="800" border="1" cellpadding="3" cellspacing="0" style="border-collapse: collapse;">
        <tr>
            <td width="40" class="olohead"><b>{$translate_invoice_prn_qty}</b></td>
            <td class="olohead"><b>{$translate_invoice_prn_labour_items}</b></td>
            <td class="olohead" width="60" align="right"><b>{$translate_invoice_prn_unit_price}</b></td>
            <td class="olohead" width="80" align="right"><b>{$translate_invoice_prn_subtotal}</b></td>
        </tr>
        {section name=l loop=$labour_details}
            <tr>
                <td class="olotd4" width="40">{$labour_details[l].INVOICE_LABOUR_UNIT}</td>
                <td class="olotd4" >{$labour_details[l].INVOICE_LABOUR_DESCRIPTION}</td>
                <td class="olotd4" width="60" align="right">{$currency_sym}{$labour_details[l].INVOICE_LABOUR_RATE|string_format:"%.2f"}</td>
                <td class="olotd4" width="80" align="right">{$currency_sym}{$labour_details[l].INVOICE_LABOUR_SUBTOTAL|string_format:"%.2f"}</td>
            </tr>
        {/section}
        <tr>
            <td colspan="3" style="text-align:right;"><b>{$translate_invoice_prn_labour_total}</b></td>
            <td class="olotd4" width="80" align="right">{$currency_sym}{$labour_sub_total|string_format:"%.2f"}</td>
        </tr>
    </table>
    <br>

    <!-- Parts Table -->
    <table width="800" border="1" cellpadding="3" cellspacing="0" style="border-collapse: collapse;">
        <tr>
            <td width="40" class="olohead"><b>{$translate_invoice_prn_qty}</b></td>
            <td class="olohead"><b>{$translate_invoice_prn_parts_items}</b></td>
            <td class="olohead" width="60" align="right"><b>{$translate_invoice_prn_unit_price}</b></td>
            <td class="olohead" width="80" align="right"><b>{$translate_invoice_prn_subtotal}</b></td>
        </tr>
        {section name=p loop=$parts_details}        
            <tr class="olotd4">
                <td width="40" class="olotd4">{$parts_details[p].INVOICE_PARTS_COUNT}</td>
                <td class="olotd4">{$parts_details[p].INVOICE_PARTS_DESCRIPTION}</td>
                <td width="60" class="olotd4" align="right">{$currency_sym}{$parts_details[p].INVOICE_PARTS_AMOUNT|string_format:"%.2f"}</td>
                <td width="80" class="olotd4" align="right">{$currency_sym}{$parts_details[p].INVOICE_PARTS_SUBTOTAL|string_format:"%.2f"}</td>
            </tr>
        {/section}
        <tr>           
            <td colspan="3" style="text-align:right;"><b>{$translate_invoice_prn_parts_total}</b></td>
            <td class="olotd4" width="80" align="right">{$currency_sym}{$parts_sub_total|string_format:"%.2f"}</td>
        </tr>
    </table>
    <br>

    <!-- Financial Section -->         
    
    <table width="800" border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
        <tr>
            
            <!-- Payments Methods -->            
            
            <td colspan="1" valign="top">
                <table width="600" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse;">
                    <tr>
                        <td align="left" ><font size="-1"><b>{$translate_invoice_prn_payment_instructions}</b></font></td>
                    </tr>

                    <!-- Cheque -->                        
                    {if $active_payment_methods.cheque_active == '1'}
                        <tr>
                            <td>                                    
                                <img src="{$theme_images_dir}icons/cheque.jpeg" alt="" height="20"><br>
                                <b>Cheques</b><br>
                                Please make {$translate_invoice_cheque} payable to:
                            </td>                                
                        </tr>
                        <tr>
                            <td>{$payment_details.CHEQUE_PAYABLE_TO_MSG}</td>
                        </tr>
                    {/if}

                    <!-- Direct Deposit -->
                    {if $active_payment_methods.direct_deposit_active == '1'}
                        <tr>
                            <td>                                    
                                <img src="{$theme_images_dir}icons/deposit.jpeg" alt="" height="20"><br>
                                <b>Direct deposit details</b><br>
                                Bank Account Name: {$payment_details.BANK_ACCOUNT_NAME}<br>
                                Bank Name: {$payment_details.BANK_NAME}<br>
                                Account Number: {$payment_details.BANK_ACCOUNT_NUMBER}<br>
                                Sort Code: {$payment_details.BANK_SORT_CODE}<br>
                                IBAN: {$payment_details.BANK_IBAN}<br>
                                Note: {$payment_details.BANK_TRANSACTION_MSG}
                            </td>
                        </tr>
                    {/if}

                    <!-- PayPal -->
                    {if $active_payment_methods.paypal_active == '1'}
                    <tr>
                        <td>
                            <img src="{$theme_images_dir}paypal/pay_now.gif" height="20"  alt="PayPal - The safer, easier way to pay online"><br>
                            <b>PayPal</b><br>
                            <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business={$PP_ID}&item_name=Payment%20for%20invoice%20{$invoice_details[i].INVOICE_ID}&item_number={$invoice_details[i].INVOICE_ID}&description=Invoice%20for%20{$invoice_details[i].INVOICE_ID}&amount={$pamount}&no_note=Thankyou%20for%20your%20buisness.&currency_code={$currency_code}&lc='.$country." target="_blank">
                               Click here to pay this invoice via PayPal using a valid Credit Card.
                             </a><br>
                            <i><b>* A 3.5% surcharge applies.</b></i>                               
                        </td>
                    </tr>
                    {/if}

                    <!-- If none of the above are enabled then display this message -->                        
                    {if $active_payment_methods.cheque_active != '1' && $active_payment_methods.direct_deposit_active != '1' && $active_payment_methods.direct_deposit_active != '1'}
                        <tr>
                            <td>{$translate_invoice_prn_discuss_payments}</td>
                        </tr>
                    {/if}

                </table>
            </td>
                  
            
            <!-- Totals Box -->
            <td colspan="2" valign="TOP">
                <table width="200" border="1" cellpadding="3" cellspacing="0" style="border-collapse: collapse;">
                    <tr>
                        <td class="olotd4" align="left"><b>{$translate_invoice_prn_subtotal}</b></td>
                        <td class="olotd4" width="80" align="right">{$currency_sym} {$invoice_details[i].SUB_TOTAL|string_format:"%.2f"}</td>
                    </tr>
                    <tr>
                        <td class="olotd4"><b>{$translate_invoice_prn_discount}</b></td>
                        <td class="olotd4" width="80" align="right">{$currency_sym}{$invoice_details[i].DISCOUNT|string_format:"%.2f"}</td>
                    </tr>                    
                    <tr>
                        <td class="olotd4"><b>{$translate_invoice_prn_tax}</b></td>
                        <td class="olotd4" width="80" align="right">{$currency_sym}{$invoice_details[i].TAX|string_format:"%.2f"}</td>
                    </tr>
                    <tr>
                        <td class="olotd4"><b>{$translate_invoice_prn_invoice_total}</b></td>
                        <td class="olotd4" width="80" align="right"><b>{$currency_sym}{$invoice_details[i].TOTAL|string_format:"%.2f"}</b></td>
                    </tr>
                    <tr>
                        <td class="olotd4"><b>{$translate_invoice_prn_paid}</b></td>
                        <td class="olotd4" width="80" align="right">{$currency_sym}{$invoice_details[i].PAID_AMOUNT|string_format:"%.2f"}</td>
                    </tr>
                    <tr>
                        <td class="olotd4"><b>{$translate_invoice_prn_balance}</b></td>
                        {if $invoice_details[i].BALANCE == 0}
                            <td class="olotd4" width="80" align="right"><b><font color="#CC0000">{$currency_sym} {$invoice_details[i].BALANCE|string_format:"%.2f"}</font></b></td>
                        {else}
                            <td class="olotd4" width="80" align="right"><b><font color="#CC0000">{$currency_sym} {$invoice_details[i].BALANCE|string_format:"%.2f"}</font></b></td>
                        {/if}
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <br>
    <br>
                        
    <!-- Footer Section -->
    {section name=t loop=$payment_details}
        <table width="800" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse;">
            <tr>
                <td align="center">{$payment_details.INVOICE_FOOTER_MSG}</td>
            </tr>
        </table>
    {/section}
  
    
</body>
</html>
{/section}
{/section}
{/section}