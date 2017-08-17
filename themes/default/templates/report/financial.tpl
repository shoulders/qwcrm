<!-- financial.tpl -->
<link rel="stylesheet" href="{$theme_js_dir}jscal2/css/jscal2.css" />
<link rel="stylesheet" href="{$theme_js_dir}jscal2/css/steel/steel.css" />
<script src="{$theme_js_dir}jscal2/jscal2.js"></script>
<script src="{$theme_js_dir}jscal2/unicode-letter.js"></script>
<script>{include file="`$theme_js_dir_finc`jscal2/language.js"}</script>

<table width="100%" border="0" cellpadding="20" cellspacing="5">
    <tr>
        <td>    
            <table width="700" cellpadding="4" cellspacing="0" border="0">
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{t}Financial Report{/t}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}REPORT_FINANCIAL_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}REPORT_FINANCIAL_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
                    </td>
                </tr>                
                <tr>
                    <td class="menutd2" colspan="2">
                        
                        <!-- Date Range -->
                        <form action="index.php?page=report:financial" method="post" name="stats_report" id="stats_report">
                            
                            <table width="650px" class="olotable"  border="0" cellpadding="4" cellspacing="0">
                                <tr>
                                    <td class="olotd">
                                        <table width="100%" cellpadding="4" cellspacing="0" border="0">
                                            <tr>
                                                <td class="menuhead2" width="100%">&nbsp;{t}Date Range{/t}</td>
                                            </tr>
                                            <tr>
                                                <td class="olotd">
                                                    <table width="100%" cellpadding="4" cellspacing="0" border="0" >
                                                        <tr align="left">
                                                            <td><b>{t}Report Date From{/t}: </b></td>
                                                            <td><b>{t}Report Date To{/t}: </b></td>
                                                        </tr>
                                                        <tr>
                                                            <td align="left">
                                                                <input id="start_date" name="start_date" class="olotd5" size="10" value="{$start_date|date_format:$date_format}" type="text" maxlength="10" pattern="{literal}^[0-9]{1,2}(\/|-)[0-9]{1,2}(\/|-)[0-9]{2,2}([0-9]{2,2})?${/literal}" required onkeydown="return onlyDate(event);">
                                                                <input id="start_date_button" value="+" type="button" >
                                                                <script>                            
                                                                    Calendar.setup( {
                                                                        trigger     : "start_date_button",
                                                                        inputField  : "start_date",
                                                                        dateFormat  : "{$date_format}"                                                                                            
                                                                    } );                            
                                                                </script>                
                                                            </td>
                                                            <td>
                                                                <input id="end_date" name="end_date" class="olotd5" size="10" value="{$end_date|date_format:$date_format}" type="text" maxlength="10" pattern="{literal}^[0-9]{1,2}(\/|-)[0-9]{1,2}(\/|-)[0-9]{2,2}([0-9]{2,2})?${/literal}" required onkeydown="return onlyDate(event);">
                                                                <input id="end_date_button" value="+" type="button">                    
                                                                <script>                            
                                                                    Calendar.setup( {
                                                                        trigger     : "end_date_button",
                                                                        inputField  : "end_date",
                                                                        dateFormat  : "{$date_format}"                                                                                            
                                                                    } );                            
                                                                </script>                    
                                                            </td>
                                                        </tr>                                                        
                                                    </table>
                                                </td>
                                            </tr>                                            
                                        </table>
                                    </td>
                                </tr>
                            </table>
                                                        
                            <!-- Submit Button -->
                            <br />
                            <div style="width: 65%; text-align: center;"><button type="submit" name="submit" value="submit">{t}Submit{/t}</button></div>
                            <br />
                            
                        </form>                
                                
                        <!-- Basic Statistics -->
                        <table width="650px" class="olotable"  border="0" cellpadding="4" cellspacing="0">
                            <tr>
                                <td class="olotd">
                                    <table width="100%" cellpadding="4" cellspacing="0" border="0" >
                                        <tr>
                                            <td class="menuhead2" width="100%">&nbsp;{t}Basic Statisitics{/t}</td>
                                        </tr>
                                        <tr>
                                            <td class="olotd5" colspan="2">
                                                <table width="100%"class="olotable"  border="0" cellpadding="5" cellspacing="0">
                                                    
                                                    <tr>
                                                        <td class="olohead">{t}Customers{/t}</td>
                                                        <td class="olohead">{t}Work Orders{/t}</td>
                                                        <td class="olohead">{t}Invoices{/t}</td>
                                                    </tr>                                                    
                                                    
                                                    <tr>
                                                        
                                                        <!-- Customers -->
                                                        <td class="olotd4" valign="top">
                                                            <table>
                                                                <tr>
                                                                    <td><b>{t}New{/t}:</b></td>
                                                                    <td><font color="red"<b> {$new_customers}</b></font></td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                        
                                                        <!-- Workorders -->
                                                        <td class="olotd4" valign="top">
                                                            <table >
                                                                <tr>
                                                                    <td><b>{t}Opened{/t}:</b></td>
                                                                    <td><font color="red"<b> {$wo_opened}</b></font></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><b>{t}Closed{/t}:</b></td>
                                                                    <td><font color="red"<b> {$wo_closed}</b></font></td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                        
                                                        <!-- Invoices -->
                                                        <td class="olotd4" valign="top">
                                                            <table>
                                                                <tr>
                                                                    <td><b>{t}New{/t}:</b></td>
                                                                    <td><font color="red"<b> {$new_invoices}</b></font></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><b>{t}Paid{/t}:</b></td>
                                                                    <td><font color="red"<b> {$paid_invoices}</b></font></td>
                                                                </tr>
                                                            </table>
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

                        <!-- Advanced Statistics -->
                        <table width="650px" class="olotable"  border="0" cellpadding="4" cellspacing="0">
                            <tr>
                                <td class="olotd">
                                    <table width="100%" cellpadding="4" cellspacing="0" border="0" >
                                        <tr>
                                            <td class="menuhead2" width="100%">&nbsp;{t}Advanced Statistics{/t}</td>
                                        </tr>
                                        <tr>
                                            <td class="olotd5" colspan="2">
                                                <table width="100%"class="olotable"  border="0" cellpadding="5" cellspacing="0">
                                                    <tr>
                                                        <td class="olohead">{t}Labour{/t}</td>
                                                        <td class="olohead">{t}Parts{/t}</td>                                                        
                                                        <td class="olohead">{t}Expenses{/t}</td>
                                                        <td class="olohead">{t}Refunds{/t}</td>                                                        
                                                    </tr>
                                                    <tr>
                                                        
                                                        <!-- Labour -->
                                                        <td class="olotd4" valign="top">
                                                            <table>
                                                                <tr>
                                                                    <td><b>{t}Items{/t}:</b></td>
                                                                    <td><font color="red"<b> {$labour_different_items_count}</b></font></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><b>{t}Qty{/t}:</b></td>
                                                                    <td><font color="red"<b>{$labour_items_count}</b></font></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><b>{t}Sub{/t}:</b></td>
                                                                    <td><font color="red"<b>{$currency_sym}{$labour_sub_total|string_format:"%.2f"}</b></font></td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                        
                                                        <!-- Parts -->
                                                        <td class="olotd4" valign="top">
                                                            <table>
                                                                <tr>
                                                                    <td><b>{t}Items{/t}:</b></td>
                                                                    <td><font color="red"<b> {$parts_different_items_count}</b></font></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><b>{t}Qty{/t}:</b></td>
                                                                    <td><font color="red"<b> {$parts_count}</b></font></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><b>{t}Sub{/t}:</b></td>
                                                                    <td><font color="red"<b>{$currency_sym}{$parts_sub_total|string_format:"%.2f"}</b></font></td>
                                                                </tr>
                                                            </table>
                                                        </td>                                                        
                                                        
                                                        <!-- Expenses -->
                                                        <td class="olotd4" valign="top">
                                                            <table>
                                                                <tr>
                                                                    <td><b>{t}Net{/t}:</b></td>
                                                                    <td><font color="red"<b>{$currency_sym}{$expense_net_amount|string_format:"%.2f"}</b></font></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><b>{t}Tax{/t}:</b></td>
                                                                    <td><font color="red"<b>{$currency_sym}{$expense_tax_amount|string_format:"%.2f"}</b></font></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><b>{t}Gross{/t}:</b></td>
                                                                    <td><font color="red"<b>{$currency_sym}{$expense_gross_amount|string_format:"%.2f"}</b></font></td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                        
                                                        <!-- Refunds -->
                                                        <td class="olotd4" valign="top">
                                                            <table>
                                                                <tr>
                                                                    <td><b>{t}Net{/t}:</b></td>
                                                                    <td><font color="red"<b>{$currency_sym}{$refund_net_amount|string_format:"%.2f"}</b></font></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><b>{t}Tax{/t}:</b></td>
                                                                    <td><font color="red"<b>{$currency_sym}{$refund_tax_amount|string_format:"%.2f"}</b></font></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><b>{t}Gross{/t}:</b></td>
                                                                    <td><font color="red"<b>{$currency_sym}{$refund_gross_amount|string_format:"%.2f"}</b></font></td>
                                                                </tr>
                                                            </table>
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
                        
                        <!-- Revenue -->
                        <table width="650px" class="olotable"  border="0" cellpadding="4" cellspacing="0">
                            <tr>
                                <td class="olotd">
                                    <table width="100%" cellpadding="4" cellspacing="0" border="0" >
                                        <tr>
                                            <td class="menuhead2" width="100%">&nbsp;{t}Revenue Calculations{/t}</td>
                                        </tr>                                        
                                        <tr>
                                            <td class="olotd5" colspan="2">
                                                <table width="100%"class="olotable"  border="0" cellpadding="5" cellspacing="0">
                                                    
                                                    <tr>                                                        
                                                        <td class="olohead">{t}Revenue{/t}</td>
                                                        <td class="olohead">{t}VAT (Tax){/t}</td>
                                                        <td class="olohead">{t}Calculations{/t}</td>                                                        
                                                    </tr>                                                    
                                                    
                                                    <tr>
                                                        
                                                        <!-- Revenue -->
                                                        <td class="olotd4" valign="top">
                                                            <table>
                                                                <tr>
                                                                    <td><b>{t}Sub Total{/t}:</b></td>
                                                                    <td><font color="red"<b>{$currency_sym}{$invoice_sub_total|string_format:"%.2f"}</b></font></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><b>{t}Discount{/t}:</b></td>
                                                                    <td><font color="red"<b>{$currency_sym}{$invoice_discount_amount|string_format:"%.2f"}</b></font></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><b>{t}Net{/t}:</b></td>
                                                                    <td><font color="red"<b>{$currency_sym}{$invoice_net_amount|string_format:"%.2f"}</b></font></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><b>{t}Tax{/t}:</b></td>
                                                                    <td><font color="red"<b>{$currency_sym}{$invoice_tax_amount|string_format:"%.2f"}</b></font></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><b>{t}Gross (Invoiced){/t}:</b></td>
                                                                    <td><font color="red"<b>{$currency_sym}{$invoice_gross_amount|string_format:"%.2f"}</b></font></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><hr></td>
                                                                </tr>
                                                                 
                                                                <tr>
                                                                    <td><b>{t}Received Monies{/t}:</b></td>
                                                                    <td><font color="red"<b>{$currency_sym}{$received_monies|string_format:"%.2f"}</b></font></td>
                                                                </tr> 
                                                                <tr>
                                                                    <td><b>{t}Outstanding Balance{/t}:</b></td>
                                                                    <td><font color="red"<b>{$currency_sym}{$outstanding_balance|string_format:"%.2f"}</b></font></td>
                                                                </tr> 
                                                            </table>
                                                        </td>
                                                        
                                                        <!-- VAT (Tax) -->
                                                        <td class="olotd4" valign="top">
                                                            <table>                                                                
                                                                <tr>
                                                                    <td><b>{t}VAT Paid{/t}:</b></td>
                                                                    <td><font color="red"<b>{$currency_sym}{$vat_paid|string_format:"%.2f"}</b></font></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><b>{t}VAT Received{/t}:</b></td>
                                                                    <td><font color="red"<b>{$currency_sym}{$vat_received|string_format:"%.2f"}</b></font></td>
                                                                </tr> 
                                                                <tr>
                                                                    <td><b>{t}VAT Balance{/t}:</b></td>
                                                                    <td><font color="red"<b>{$currency_sym}{$vat_balance|string_format:"%.2f"}</b></font></td>
                                                                </tr>
                                                            </table>
                                                        </td> 
                                                        
                                                        <!-- Calculations -->
                                                        <td class="olotd4" valign="top">
                                                            <table>
                                                                <tr>
                                                                    <td style="text-align: center;">{t}Taxable Profit = Invoiced - (Expenses - Refunds){/t}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="text-align: center;">
                                                                        <strong>Net (No Taxes Applied)</strong><br>
                                                                        {$currency_sym}{$taxable_profit_net} = {$currency_sym}{$invoice_net_amount|string_format:"%.2f"} - ({$currency_sym}{$expense_net_amount|string_format:"%.2f"} - {$currency_sym}{$refund_net_amount|string_format:"%.2f"})
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="text-align: center;">
                                                                        <strong>Gross (Taxes Applied)</strong><br>
                                                                        {$currency_sym}{$taxable_profit_gross} = {$currency_sym}{$invoice_gross_amount|string_format:"%.2f"} - ({$currency_sym}{$expense_gross_amount|string_format:"%.2f"} - {$currency_sym}{$refund_gross_amount|string_format:"%.2f"})
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>                                                                                                              
                                                        
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>                                                                      
                                                                    
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>