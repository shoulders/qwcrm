<!-- print_invoice.tpl -->
<!DOCTYPE html>
<html lang="en-GB">
<head>
    <meta charset="utf-8">
    
    <!-- PDF Title -->
    <title>{t}Invoice{/t} {$invoice_details.invoice_id}</title>   
        
    <!-- PDF Subject -->
    <meta name="description" content="{$meta_description}">
    
    <!-- PDF Keywords -->
    <meta name="keywords" content="{$meta_keywords}">
    
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
                        <td valign="top"><b>{t}Address{/t} :&nbsp;</b></td>
                        <td>
                            {$company_details.address|nl2br|regex_replace:"/[\r\t\n]/":" "}<br>
                            {$company_details.city}<br>
                            {$company_details.state}<br>
                            {$company_details.zip}<br>
                            {$company_details.country}
                        </td>
                    </tr>
                    <tr>
                        <td><b>{t}Phone{/t} :&nbsp;</b></td>
                        <td>{$company_details.phone}</td>
                    </tr>
                    <tr>
                        <td><b>{t}Website{/t} :&nbsp;</b></td>
                        <td>{$company_details.website}</td>
                    </tr>
                    <tr>
                        <td><b>{t}Email{/t} :&nbsp;</b></td>
                        <td>{$company_details.email}</td>
                    </tr>
                    <tr>
                        <td><b>{t}Company Number{/t} :&nbsp;</b></td>
                        <td>{$company_details.company_number}</td>
                    </tr>
                </table>     
            </td>

            <!-- LOGO and Company Name-->
            <td valign="top" align="center" width="300px">
                <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
                    <tr>
                        <td width="100%" align="center"><img src="{$theme_images_dir}logo.png" height="100px" alt="" border="0"></td>
                    </tr>
                    <tr><td style="text-align:center"><b>{$company_details.name}</b></td></tr>
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
                                        <b>{t}Invoice ID{/t} - </b>{$invoice_details.invoice_id}<br>
                                        <b>{t}Status{/t} - </b>{$workorder_details.status}<br>
                                        <b>{t}Date{/t} - </b>{$invoice_details.date|date_format:$date_format} <br>
                                        <b>{t}Due Date{/t} - </b>{$invoice_details.due_date|date_format:$date_format}<br>
                                        <b>{t}Work Order{/t} - </b>{$invoice_details.workorder_id}<br>
                                        <b>{t}Technician{/t} - </b>{$employee_display_name}<br>                                        
                                        <b>{t}Credit Terms{/t} - </b>{$customer_details.credit_terms}<br>                                       
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
                <p>{t}Customer Details{/t}:</p>
                {$customer_details.display_name}<br>
                {$customer_details.address|nl2br|regex_replace:"/[\r\t\n]/":" "}<br>
                {$customer_details.city}<br>
                {$customer_details.state}<br>
                {$customer_details.zip}<br>
                {$customer_details.country}
            </td>
        </tr>
    </table>    
    
    <!-- Workorder Row -->
    {if $workorder_details.description > null}
        <table  width="800" border="0" cellpadding="3" cellspacing="0" >
            <tr>
                <td><b>{t}Work Order{/t}</b></td>
                <td><b>{t}Work Order Resolution{/t}</b></td>
            </tr>
            <tr>
                <td width="50%" valign="top">{$workorder_details.description}</td>
                <td width="50%" valign="top" style="border-left: 1px solid;">{$workorder_details.resolution}</td>
            </tr>
        </table>
    {/if}
    <br>
    
    <!-- Invoice To Box -->
    <table width="800" border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
        <tr>
            <td align="center" class="olotd5" ><font size="+2">{t}Invoice Details{/t} {$customer_details.display_name}</font></td>
        </tr>
    </table>
    <br>

    <!-- Items Table Section -->

    <!-- Labour Table -->
    <table width="800" border="1" cellpadding="3" cellspacing="0" style="border-collapse: collapse;">
        <tr>
            <td width="40" class="olohead"><b>{t}Qty{/t}</b></td>
            <td class="olohead"><b>{t}Labour Items{/t}</b></td>
            <td class="olohead" width="60" align="right"><b>{t}Unit Price{/t}</b></td>
            <td class="olohead" width="80" align="right"><b>{t}Sub Total{/t}</b></td>
        </tr>
        {section name=l loop=$labour_items}
            <tr>
                <td class="olotd4" width="40">{$labour_details[l].invoice_labour_unit}</td>
                <td class="olotd4" >{$labour_details[l].description}</td>
                <td class="olotd4" width="60" align="right">{$currency_sym}{$labour_details[l].invoice_labour_rate|string_format:"%.2f"}</td>
                <td class="olotd4" width="80" align="right">{$currency_sym}{$labour_details[l].invoice_labour_subtotal|string_format:"%.2f"}</td>
            </tr>
        {/section}
        <tr>
            <td colspan="3" style="text-align:right;"><b>{t}Labour Total{/t}</b></td>
            <td class="olotd4" width="80" align="right">{$currency_sym}{$labour_sub_total|string_format:"%.2f"}</td>
        </tr>
    </table>
    <br>

    <!-- Parts Table -->
    <table width="800" border="1" cellpadding="3" cellspacing="0" style="border-collapse: collapse;">
        <tr>
            <td width="40" class="olohead"><b>{t}Qty{/t}</b></td>
            <td class="olohead"><b>{t}Parts Items{/t}</b></td>
            <td class="olohead" width="60" align="right"><b>{t}Unit Price{/t}</b></td>
            <td class="olohead" width="80" align="right"><b>{t}Sub Total{/t}</b></td>
        </tr>
        {section name=p loop=$parts_items}        
            <tr class="olotd4">
                <td width="40" class="olotd4">{$parts_details[p].qty}</td>
                <td class="olotd4">{$parts_details[p].description}</td>
                <td width="60" class="olotd4" align="right">{$currency_sym}{$parts_details[p].amount|string_format:"%.2f"}</td>
                <td width="80" class="olotd4" align="right">{$currency_sym}{$parts_details[p].sub_total|string_format:"%.2f"}</td>
            </tr>
        {/section}
        <tr>           
            <td colspan="3" style="text-align:right;"><b>{t}Parts Total{/t}</b></td>
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
                        <td align="left" ><font size="-1"><b>{t}Payment Instructions{/t}</b></font></td>
                    </tr>

                    <!-- Cheque -->                        
                    {if $active_payment_methods.cheque_active == '1'}
                        <tr>
                            <td>                                    
                                <img src="{$theme_images_dir}icons/cheque.jpeg" alt="" height="20"><br>
                                <b>Cheques</b><br>
                                {t}Please make Cheque payable to{/t}:
                            </td>                                
                        </tr>
                        <tr>
                            <td>{$payment_details.cheque_payable_to_msg}</td>
                        </tr>
                    {/if}

                    <!-- Direct Deposit -->
                    {if $active_payment_methods.direct_deposit_active == '1'}
                        <tr>
                            <td>                                    
                                <img src="{$theme_images_dir}icons/deposit.jpeg" alt="" height="20"><br>
                                <b>{t}Direct Deposit{/t}</b><br>
                                {t}Bank Account Name{/t}: {$payment_details.bank_account_name}<br>
                                {t}Bank Name{/t}: {$payment_details.bank_name}<br>
                                {t}Account Number{/t}: {$payment_details.bank_account_number}<br>
                                {t}Sort Code{/t}: {$payment_details.bank_sort_code}<br>
                                {t}IBAN{/t}: {$payment_details.bank_iban}<br>
                                {t}Note{/t}: {$payment_details.bank_transaction_msg}
                            </td>
                        </tr>
                    {/if}

                    <!-- PayPal -->
                    {if $active_payment_methods.paypal_active == '1'}
                    <tr>
                        <td>
                            <img src="{$theme_images_dir}paypal/pay_now.gif" height="20"  alt="PayPal - The safer, easier way to pay online"><br>
                            <b>{t}PayPal{/t}</b><br>
                            <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business={$PP_ID}&item_name=Payment%20for%20invoice%20{$invoice_details.invoice_id}&item_number={$invoice_details.invoice_id}&description=Invoice%20for%20{$invoice_details.invoice_id}&amount={$pamount}&no_note=Thankyou%20for%20your%20buisness.&currency_code={$currency_code}&lc='.$country." target="_blank">
                               {t}Click here to pay this invoice via PayPal using a valid Credit Card.{/t}
                             </a><br>
                            <i><b>* {t} A 3.5% surcharge applies.{/t}</b></i>                               
                        </td>
                    </tr>
                    {/if}

                    <!-- If none of the above are enabled then display this message -->                        
                    {if $active_payment_methods.cheque_active != '1' && $active_payment_methods.direct_deposit_active != '1' && $active_payment_methods.direct_deposit_active != '1'}
                        <tr>
                            <td>{t}Please call us to discuss payment options.{/t}</td>
                        </tr>
                    {/if}

                </table>
            </td>
                  
            
           <!-- Totals Box -->
            <td colspan="2" valign="TOP">
                <table width="200" border="1" cellpadding="3" cellspacing="0" style="border-collapse: collapse;">
                    <tr>
                        <td class="olotd4" align="left"><b>{t}Sub Total{/t}</b></td>
                        <td class="olotd4" width="80" align="right">{$currency_sym} {$invoice_details.sub_total|string_format:"%.2f"}</td>
                    </tr>
                    <tr>
                        <td class="olotd4"><b>{t}Discount{/t}</b></td>
                        <td class="olotd4" width="80" align="right">{$currency_sym}{$invoice_details.discount_amount|string_format:"%.2f"}</td>
                    </tr>                    
                    <tr>
                        <td class="olotd4"><b>{t}Tax{/t}</b></td>
                        <td class="olotd4" width="80" align="right">{$currency_sym}{$invoice_details.tax_amount|string_format:"%.2f"}</td>
                    </tr>
                    <tr>
                        <td class="olotd4"><b>{t}Total{/t}</b></td>
                        <td class="olotd4" width="80" align="right"><b>{$currency_sym}{$invoice_details.total|string_format:"%.2f"}</b></td>
                    </tr>
                    <tr>
                        <td class="olotd4"><b>{t}Paid{/t}</b></td>
                        <td class="olotd4" width="80" align="right">{$currency_sym}{$invoice_details.paid_amount|string_format:"%.2f"}</td>
                    </tr>
                    <tr>
                        <td class="olotd4"><b>{t}Balance{/t}</b></td>
                        {if $invoice_details.balance == 0}
                            <td class="olotd4" width="80" align="right"><b><font color="#CC0000">{$currency_sym} {$invoice_details.balance|string_format:"%.2f"}</font></b></td>
                        {else}
                            <td class="olotd4" width="80" align="right"><b><font color="#CC0000">{$currency_sym} {$invoice_details.balance|string_format:"%.2f"}</font></b></td>
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
                <td align="center">{$payment_details.invoice_footer_msg}</td>
            </tr>
        </table>
    {/section}  
    
</body>
</html>