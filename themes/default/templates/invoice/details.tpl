<!-- edit.tpl -->
<table width="100%" border="0" cellpadding="20" cellspacing="5">
    <tr>
        <td>
            <table width="700" cellpadding="4" cellspacing="0" border="0" >
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{t}Invoice For Work Order ID{/t} {$workorder_id}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}INVOICE_DETAILS_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}INVOICE_DETAILS_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
                        </a>
                    </td>
                </tr>
                <tr>
                    <td class="menutd2" colspan="2">
                        <table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
                            <tr>
                                <td class="menutd">                                   
                                    
                                    <table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
                                        
                                        <!-- Invoice Information -->
                                        <tr class="olotd4">
                                            <td class="row2"><b>{t}Invoice ID{/t}</b></td>
                                            <td class="row2"><b>{t}Date{/t}</b></td>
                                            <td class="row2"><b>{t}Due Date{/t}</b></td>
                                            <td class="row2"><b>{t}Amount{/t}</b></td>
                                            <td class="row2"><b>{t}Tech{/t}</b></td>
                                            <td class="row2"><b>{t}Work Order{/t}</b></td>
                                            <td class="row2"><b>{t}Date Paid{/t}</b></td>
                                            <td class="row2"><b>{t}Amount Paid{/t}</b></td>
                                            <td class="row2"><b>{t}Balance{/t}</b></td>
                                        </tr>
                                        <tr class="olotd4">
                                            <td>{$invoice_details.INVOICE_ID}</td>
                                            <td>{$invoice_details.DATE|date_format:$date_format}</td>                                            
                                            <td>{$invoice_details.DUE_DATE|date_format:$date_format}</td>
                                            <td>{$currency_sym}{$invoice_details.TOTAL|string_format:"%.2f"}</td>
                                            <td><a href="index.php?page=employee:details&employee_id={$invoice_details.EMPLOYEE_ID}">{$employee_display_name}</a></td>
                                            <td><a href="index.php?page=workorder:details&workorder_id={$invoice_details.WORKORDER_ID}">{$invoice_details.WORKORDER_ID}</a></td>
                                            <td>{$invoice_details.PAID_DATE|date_format:$date_format}</td>
                                            <td>{$currency_sym}{$invoice_details.PAID_AMOUNT|string_format:"%.2f"}</td>
                                            <td><font color="#CC0000">{$currency_sym}{$invoice_details.BALANCE|string_format:"%.2f"}</font></td>
                                        </tr>

                                        <tr>

                                            <!-- Customer Details -->
                                            <td colspan="6" valign="top" align="left">
                                                <b>{t}Bill{/t}</b>                                                        
                                                <table cellpadding="0" cellspacing="0">
                                                    <tr>
                                                        <td valign="top">
                                                            <a href="index.php?page=customer:details&customer_id={$customer_details.CUSTOMER_ID}">{$customer_details.CUSTOMER_DISPLAY_NAME}</a><br>
                                                            {$customer_details.CUSTOMER_ADDRESS|nl2br}<br>
                                                            {$customer_details.CUSTOMER_CITY}, {$customer_details.CUSTOMER_STATE} {$customer_details.CUSTOMER_ZIP}<br>
                                                            {$customer_details.CUSTOMER_PHONE}<br>
                                                            {$customer_details.CUSTOMER_EMAIL}                                                                        
                                                        </td>
                                                    </tr>
                                                </table>                                                        
                                            </td>

                                            <!-- Company Details -->
                                            <td colspan="3" valign="top" >
                                                <b>{t}Pay To{/t}</b>
                                                <table cellpadding="0" cellspacing="0" width="100%">
                                                    <tr>
                                                        <td valign="top">                                                                    
                                                            {$company_details.NAME} <br>
                                                            {$company_details.ADDRESS}<br>
                                                            {$company_details.CITY}, {$company_details.STATE} {$company_details.ZIP}<br>
                                                            {$company_details.PHONE}<br>                                                                    
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>

                                        </tr>

                                        <!-- Terms and Discount -->
                                        <tr>
                                            <td colspan="9" valign="top" align="left">                                                        
                                                {t}TERMS{/t}: <font color="red">{$customer_details.CREDIT_TERMS}</font><br>
                                                {t}Customer discount rate for this invoice is{/t}: 
                                                {$invoice_details.DISCOUNT_RATE|string_format:"%.2f"} %<br>
                                                                                                      
                                            </td>
                                        </tr>

                                    </table>                                                         
                                    <br>                                            

                                    <!-- if invoice has an amount -->
                                    {if $invoice_details.TOTAL > 0 }

                                        <!-- Function Buttons -->
                                        <table width="100%" cellpadding="4" cellspacing="0" border="0" id="transaction_log">
                                            <tr>
                                                <td class="menuhead2">&nbsp;{t}Function Buttons{/t}</td>
                                            </tr>
                                            <tr>
                                                <td class="menutd2">

                                                    <!-- Print Buttons -->   
                                                    <button type="button" name="{t}Print{/t}" onClick="window.open('index.php?page=invoice:print&invoice_id={$invoice_details.INVOICE_ID}&print_type=print_html&print_content=invoice&theme=print');">{t}Print{/t}</button>
                                                    <button type="button" name="{t}Print PDF{/t}" onClick="window.open('index.php?page=invoice:print&invoice_id={$invoice_details.INVOICE_ID}&print_type=print_pdf&print_content=invoice&theme=print');"><img src="{$theme_images_dir}icons/pdf_small.png"  height="14" alt="pdf">{t}rint PDF{/t}</button>
                                                    <button type="button" name="{t}Print Address Only{/t}" onClick="window.open('index.php?page=invoice:print&invoice_id={$invoice_details.INVOICE_ID}&print_type=print_html&print_content=invoice&theme=print');">{t}Print Address Only{/t}</button>                                            

                                                </td>
                                            </tr>
                                        </table>
                                        <br>
                                        
                                    {/if} 

                                    <!-- Transaction Log -->
                                    {if $transactions != null}                                            
                                        <table width="100%" cellpadding="4" cellspacing="0" border="0" id="transaction_log">
                                            <tr>
                                                <td class="menuhead2">&nbsp;{t}Transaction Log{/t}</td>
                                            </tr>
                                            <tr>
                                                <td class="menutd2">
                                                    <table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
                                                        <tr class="olotd4">
                                                            <td class="row2"><b>{t}Transaction ID{/t}</b></td>
                                                            <td class="row2"><b>{t}Date{/t}</b></td>
                                                            <td class="row2"><b>{t}Amount{/t}</b></td>
                                                            <td class="row2"><b>{t}Type{/t}</b></td>
                                                        </tr>                                                            
                                                        {section name=t loop=$transactions}
                                                            <tr class="olotd4">
                                                                <td>{$transactions[t].TRANSACTION_ID}</td>
                                                                <td>{$transactions[t].DATE|date_format:$date_format}</td>
                                                                <td><b>{$currency_symbol}</b>{$transactions[t].AMOUNT|string_format:"%.2f"}</td>
                                                                <td>
                                                                    {if $transactions[t].TYPE == 1}{t}invoice_cc{/t}
                                                                    {elseif $transactions[t].TYPE == 2}{t}Cheque{/t}
                                                                    {elseif $transactions[t].TYPE == 3}{t}Cash{/t}
                                                                    {elseif $transactions[t].TYPE == 4}{t}Gift Certificate{/t}
                                                                    {elseif $transactions[t].TYPE == 5}{t}Paypal{/t}
                                                                    {/if}
                                                                </td>
                                                            </tr>
                                                            <tr class="olotd4">
                                                                <td><b>{t}Note{/t}</b></td>
                                                                <td colspan="3">{$transactions[t].NOTE}</td>
                                                            </tr>
                                                        {/section}
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                        <br>
                                    {/if}                                            

                                    <!-- Labour Items -->
                                    <table width="100%" cellpadding="4" cellspacing="0" border="0" >
                                        <tr>
                                            <td class="menuhead2">&nbsp;{t}Labour{/t}</td>
                                        </tr>
                                        <tr>
                                            <td class="menutd2">
                                                {if $labour_items != '0'}
                                                    <table width="100%" cellpadding="3" cellspacing="0" border="0" class="olotable">
                                                        <tr  class="olotd4">
                                                            <td class="row2"><b>{t}No.{/t}</b></td>
                                                            <td class="row2" width="12"><b>{t}Qty{/t}</b></td>
                                                            <td class="row2"><b>{t}Description{/t}</b></td>
                                                            <td class="row2"><b>{t}Rate{/t}</b></td>
                                                            <td class="row2"><b>{t}Total{/t}</b></td>
                                                            <td class="row2"><b>{t}Actions{/t}</b></td>
                                                        </tr>
                                                        {section name=l loop=$labour_items}
                                                            <tr class="olotd4">
                                                                <td>{$smarty.section.q.index+1}</td>
                                                                <td>{$labour_items[l].INVOICE_LABOUR_UNIT}</td>
                                                                <td>{$labour_items[l].INVOICE_LABOUR_DESCRIPTION}</td>
                                                                <td>{$currency_sym}{$labour_items[l].INVOICE_LABOUR_RATE|string_format:"%.2f"}</td>
                                                                <td>{$currency_sym}{$labour_items[l].INVOICE_LABOUR_SUBTOTAL|string_format:"%.2f"}</td>
                                                                <td>
                                                                    <a href="index.php?page=invoice:delete_labour&labour_id={$labour_items[l].INVOICE_LABOUR_ID}" onclick="return confirmDelete('{t}Are you Sure you want to delete this Labour Record? This will permanently remove the record from the database.{/t}');">
                                                                        <img src="{$theme_images_dir}icons/delete.gif" alt="" border="0" height="14" width="14" onMouseOver="ddrivetip('<b>{t}Delete Labour Record{/t}</b>');" onMouseOut="hideddrivetip();">
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        {/section}
                                                        <tr>
                                                            <td colspan="5" style="text-align:right;"><b>{t}Labour Total{/t}</b></td>
                                                            <td style="text-align:left;">{$currency_sym}{$labour_sub_total|string_format:"%.2f"}</td>
                                                        </tr>
                                                    </table>
                                                {/if}
                                                <br>
                                                <!-- Additional Javascript Labour Table -->
                                                <table width="100%" cellpadding="3" cellspacing="0" border="0" class="olotable" id="labour_items">
                                                    <tr class="olotd4">
                                                        <td class="row2"><b>{t}No.{/t}</b></td>
                                                        <td class="row2"><b>{t}qty{/t}</b></td>
                                                        <td class="row2"><b>{t}Description{/t}</b></td>
                                                        <td class="row2"><b>&nbsp;&nbsp;{t}Rate{/t}</b></td>
                                                    </tr>

                                                    <!-- Additional Rows are added here -->

                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                    <br>

                                    <!-- Parts Items -->
                                    <table width="100%" cellpadding="4" cellspacing="0" border="0" >
                                        <tr>
                                            <td class="menuhead2">&nbsp;{t}Parts{/t}</td>
                                        </tr>
                                        <tr>
                                            <td class="menutd2">
                                                {if $parts_items != '0'}
                                                    <table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
                                                        <tr class="olotd4">
                                                            <td class="row2"><b>{t}No.{/t}</b></td>
                                                            <td class="row2"><b>{t}Count{/t}</b></td>
                                                            <td class="row2"><b>{t}Description{/t}</b></td>
                                                            <td class="row2"><b>{t}Price{/t}</b></td>
                                                            <td class="row2"><b>{t}Total{/t}</b></td>
                                                            <td class="row2"><b>{t}Actions{/t}</b></td>
                                                        </tr>
                                                        {section name=p loop=$parts_items}
                                                            <tr class="olotd4">
                                                                <td>{$smarty.section.w.index+1}</td>
                                                                <td>{$parts_items[p].INVOICE_PARTS_COUNT}</td>
                                                                <td>{$parts_items[p].INVOICE_PARTS_DESCRIPTION}</td>
                                                                <td>{$currency_sym}{$parts_items[p].INVOICE_PARTS_AMOUNT|string_format:"%.2f"}</td>
                                                                <td>{$currency_sym}{$parts_items[p].INVOICE_PARTS_SUBTOTAL|string_format:"%.2f"}</td>
                                                                <td>
                                                                    <a href="index.php?page=invoice:delete_parts&parts_id={$parts_items[p].INVOICE_PARTS_ID}" onclick="return confirmDelete('{t}Are you Sure you want to delete this Parts Record? This will permanently remove the record from the database.{/t}');">
                                                                        <img src="{$theme_images_dir}icons/delete.gif" alt="" border="0" height="14" width="14" onMouseOver="ddrivetip('<b>{t}Delete Parts Record{/t}</b>');" onMouseOut="hideddrivetip();">
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                         {/section}
                                                        <tr>
                                                            <td colspan="5" style="text-align:right;"><b>{t}Parts Total{/t}</b></td>
                                                            <td style="text-align:left;">{$currency_sym}{$parts_sub_total|string_format:"%.2f"}</td>
                                                        </tr>
                                                    </table>
                                                {/if}
                                                <br>
                                                <!-- Additional Javascript Parts Table -->
                                                <table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable" id="parts_items">
                                                    <tr class="olotd4">
                                                        <td class="row2"><b>{t}No.{/t}</b></td>
                                                        <td class="row2"><b>{t}Count{/t}-qty</b></td>
                                                        <td class="row2"><b>{t}Description{/t}</b></td>
                                                        <td class="row2"><b>{t}Price{/t}</b></td>
                                                    </tr>

                                                    <!-- Additional Rows are added here -->

                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                    <br>

                                    <!-- Totals Section -->
                                    <table width="100%" cellpadding="4" cellspacing="0" border="0" >
                                        <tr>
                                            <td class="menuhead2">&nbsp;{t}Invoice Total{/t}</td>
                                        </tr>
                                        <tr>
                                            <td class="menutd2">
                                                <table width="100%" border="1" cellpadding="3" cellspacing="0" class="olotable">
                                                    <tr>
                                                        <td class="olotd4" width="80%" align="right"><b>{t}Sub Total{/t}</b></td>
                                                        <td class="olotd4" width="20%" align="right">{$currency_sym}{$invoice_details.SUB_TOTAL|string_format:"%.2f"}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="olotd4" width="80%" align="right"><b>{t}Discount{/t} (@ {$invoice_details.DISCOUNT_RATE|string_format:"%.2f"}%)</b></td>
                                                        <td class="olotd4" width="20%" align="right">- {$currency_sym}{$invoice_details.DISCOUNT|string_format:"%.2f"}</td>
                                                    </tr>                                                        
                                                    <tr>                                                            
                                                        <td class="olotd4" width="80%" align="right"><b>{t}Tax{/t} (@ {$invoice_details.TAX_RATE}%)</b></td>
                                                        <td class="olotd4" width="20%" align="right">{$currency_sym}{$invoice_details.TAX|string_format:"%.2f"}</td>                                                            
                                                    </tr>
                                                    <tr>
                                                        <td class="olotd4" width="80%" align="right"><b>{t}Total{/t}</b></td>
                                                        <td class="olotd4" width="20%" align="right">{$currency_sym}{$invoice_details.TOTAL|string_format:"%.2f"}</td>
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