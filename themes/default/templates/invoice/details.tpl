<!-- edit.tpl -->
<table width="100%" border="0" cellpadding="20" cellspacing="5">
    <tr>
        <td>
            <table width="700" cellpadding="4" cellspacing="0" border="0" >
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{$translate_invoice_for}{$workorder_id}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" alt="" onMouseOver="ddrivetip('<b>{$translate_invoice_details_help_title|nl2br|regex_replace:"/[\r\t\n]/":" "}</b><hr><p>{$translate_invoice_new_details_content|nl2br|regex_replace:"/[\r\t\n]/":" "}</p>');" onMouseOut="hideddrivetip();">
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
                                            <td class="row2"><b>{$translate_invoice_id}</b></td>
                                            <td class="row2"><b>{$translate_invoice_date}</b></td>
                                            <td class="row2"><b>{$translate_invoice_due}</b></td>
                                            <td class="row2"><b>{$translate_invoice_amount}</b></td>
                                            <td class="row2"><b>{$translate_invoice_tech}</b></td>
                                            <td class="row2"><b>{$translate_invoice_work_order}</b></td>
                                            <td class="row2"><b>{$translate_invoice_date_paid}</b></td>
                                            <td class="row2"><b>{$translate_invoice_amount_paid}</b></td>
                                            <td class="row2"><b>{$translate_invoice_balance}</b></td>
                                        </tr>
                                        <tr class="olotd4">
                                            <td>{$invoice_details.INVOICE_ID}</td>
                                            <td>{$invoice_details.DATE|date_format:$date_format}</td>                                            
                                            <td>{$invoice_details.DUE_DATE|date_format:$date_format}</td>
                                            <td>{$currency_sym}{$invoice_details.TOTAL|string_format:"%.2f"}</td>
                                            <td><a href="?page=employee:details&employee_id={$invoice_details.EMPLOYEE_ID}">{$employee_display_name}</a></td>
                                            <td><a href="?page=workorder:details&workorder_id={$invoice_details.WORKORDER_ID}&page_title={$translate_invoice_workorder_id} {$invoice_details.WORKORDER_ID}">{$invoice_details.WORKORDER_ID}</a></td>
                                            <td>{$invoice_details.PAID_DATE|date_format:$date_format}</td>
                                            <td>{$currency_sym}{$invoice_details.PAID_AMOUNT|string_format:"%.2f"}</td>
                                            <td><font color="#CC0000">{$currency_sym}{$invoice_details.BALANCE|string_format:"%.2f"}</font></td>
                                        </tr>

                                        <tr>

                                            <!-- Customer Details -->
                                            <td colspan="6" valign="top" align="left">
                                                <b>{$translate_invoice_bill}</b>                                                        
                                                <table cellpadding="0" cellspacing="0">
                                                    <tr>
                                                        <td valign="top">
                                                            <a href="?page=customer:details&customer_id={$customer_details.CUSTOMER_ID}&page_title={$customer_details.CUSTOMER_DISPLAY_NAME}">{$customer_details.CUSTOMER_DISPLAY_NAME}</a><br>
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
                                                <b>{$translate_invoice_pay}</b>
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
                                                TERMS: <font color="red">{$customer_details.CREDIT_TERMS}</font><br>
                                                Customer discount rate for this invoice is : 
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
                                                <td class="menuhead2">&nbsp;Function Buttons</td>
                                            </tr>
                                            <tr>
                                                <td class="menutd2">

                                                    <!-- Print Buttons -->   
                                                    <button type="button" name="{$translate_invoice_print}" onClick="window.open('index.php?page=invoice:print&invoice_id={$invoice_details.INVOICE_ID}&print_type=print_html&print_content=invoice&theme=print');">{$translate_invoice_print}</button>
                                                    <button type="button" name="{$translate_invoice_pdf}" onClick="window.open('index.php?page=invoice:print&invoice_id={$invoice_details.INVOICE_ID}&print_type=print_pdf&print_content=invoice&theme=print');"><img src="{$theme_images_dir}icons/pdf_small.png"  height="14" alt="pdf">{$translate_invoice_pdf}</button>
                                                    <button type="button" name="Print Address Only" onClick="window.open('index.php?page=invoice:print&invoice_id={$invoice_details.INVOICE_ID}&print_type=print_html&print_content=invoice&theme=print');">Print Address Only</button>                                            

                                                </td>
                                            </tr>
                                        </table>
                                        <br>
                                        
                                    {/if} 

                                    <!-- Transaction Log -->
                                    {if $transactions != null}                                            
                                        <table width="100%" cellpadding="4" cellspacing="0" border="0" id="transaction_log">
                                            <tr>
                                                <td class="menuhead2">&nbsp;{$translate_invoice_transaction_log}</td>
                                            </tr>
                                            <tr>
                                                <td class="menutd2">
                                                    <table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
                                                        <tr class="olotd4">
                                                            <td class="row2"><b>{$translate_invoice_trans_id}</b></td>
                                                            <td class="row2"><b>{$translate_invoice_date}</b></td>
                                                            <td class="row2"><b>{$translate_invoice_amount}</b></td>
                                                            <td class="row2"><b>{$translate_invoice_type}</b></td>
                                                        </tr>                                                            
                                                        {section name=t loop=$transactions}
                                                            <tr class="olotd4">
                                                                <td>{$transactions[t].TRANSACTION_ID}</td>
                                                                <td>{$transactions[t].DATE|date_format:$date_format}</td>
                                                                <td><b>{$currency_symbol}</b>{$transactions[t].AMOUNT|string_format:"%.2f"}</td>
                                                                <td>
                                                                    {if $transactions[t].TYPE == 1}{$translate_invoice_cc}
                                                                    {elseif $transactions[t].TYPE == 2}{$translate_invoice_check}
                                                                    {elseif $transactions[t].TYPE == 3}{$translate_invoice_cash}
                                                                    {elseif $transactions[t].TYPE == 4}{$translate_invoice_gift}
                                                                    {elseif $transactions[t].TYPE == 5}{$translate_invoice_paypal}
                                                                    {/if}
                                                                </td>
                                                            </tr>
                                                            <tr class="olotd4">
                                                                <td><b>{$translate_invoice_note}</b></td>
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
                                            <td class="menuhead2">&nbsp;{$translate_invoice_labour}</td>
                                        </tr>
                                        <tr>
                                            <td class="menutd2">
                                                {if $labour_items != '0'}
                                                    <table width="100%" cellpadding="3" cellspacing="0" border="0" class="olotable">
                                                        <tr  class="olotd4">
                                                            <td class="row2"><b>{$translate_invoice_no}</b></td>
                                                            <td class="row2" width="12"><b>{$translate_invoice_hours}</b></td>
                                                            <td class="row2"><b>{$translate_invoice_description}</b></td>
                                                            <td class="row2"><b>{$translate_invoice_rate}</b></td>
                                                            <td class="row2"><b>{$translate_invoice_total}</b></td>
                                                            <td class="row2"><b>{$translate_invoice_actions}</b></td>
                                                        </tr>
                                                        {section name=l loop=$labour_items}
                                                            <tr class="olotd4">
                                                                <td>{$smarty.section.q.index+1}</td>
                                                                <td>{$labour_items[l].INVOICE_LABOUR_UNIT}</td>
                                                                <td>{$labour_items[l].INVOICE_LABOUR_DESCRIPTION}</td>
                                                                <td>{$currency_sym}{$labour_items[l].INVOICE_LABOUR_RATE|string_format:"%.2f"}</td>
                                                                <td>{$currency_sym}{$labour_items[l].INVOICE_LABOUR_SUBTOTAL|string_format:"%.2f"}</td>
                                                                <td>
                                                                    <a href="index.php?page=invoice:delete_labour&labour_id={$labour_items[l].INVOICE_LABOUR_ID}" onclick="return confirmDelete('{$translate_invoice_labour_delete_mes_confirmation}');">
                                                                        <img src="{$theme_images_dir}icons/delete.gif" alt="" border="0" height="14" width="14" onMouseOver="ddrivetip('<b>{$translate_invoice_delete_invoice_labour_item|nl2br|regex_replace:"/[\r\t\n]/":" "}</b>');" onMouseOut="hideddrivetip();">
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        {/section}
                                                        <tr>
                                                            <td colspan="5" style="text-align:right;"><b>{$translate_invoice_labour_total}</b></td>
                                                            <td style="text-align:left;">{$currency_sym}{$labour_sub_total|string_format:"%.2f"}</td>
                                                        </tr>
                                                    </table>
                                                {/if}
                                                <br>
                                                <!-- Additional Javascript Labour Table -->
                                                <table width="100%" cellpadding="3" cellspacing="0" border="0" class="olotable" id="labour_items">
                                                    <tr class="olotd4">
                                                        <td class="row2"><b>{$translate_invoice_no}</b></td>
                                                        <td class="row2"><b>{$translate_invoice_hours}</b></td>
                                                        <td class="row2"><b>{$translate_invoice_description}</b></td>
                                                        <td class="row2"><b>&nbsp;&nbsp;{$translate_invoice_rate}</b></td>
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
                                            <td class="menuhead2">&nbsp;{$translate_invoice_parts}</td>
                                        </tr>
                                        <tr>
                                            <td class="menutd2">
                                                {if $parts_items != '0'}
                                                    <table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
                                                        <tr class="olotd4">
                                                            <td class="row2"><b>{$translate_invoice_no}</b></td>
                                                            <td class="row2"><b>{$translate_invoice_count}</b></td>
                                                            <td class="row2"><b>{$translate_invoice_description}</b></td>
                                                            <td class="row2"><b>{$translate_invoice_price}</b></td>
                                                            <td class="row2"><b>{$translate_invoice_total}</b></td>
                                                            <td class="row2"><b>{$translate_invoice_actions}</b></td>
                                                        </tr>
                                                        {section name=p loop=$parts_items}
                                                            <tr class="olotd4">
                                                                <td>{$smarty.section.w.index+1}</td>
                                                                <td>{$parts_items[p].INVOICE_PARTS_COUNT}</td>
                                                                <td>{$parts_items[p].INVOICE_PARTS_DESCRIPTION}</td>
                                                                <td>{$currency_sym}{$parts_items[p].INVOICE_PARTS_AMOUNT|string_format:"%.2f"}</td>
                                                                <td>{$currency_sym}{$parts_items[p].INVOICE_PARTS_SUBTOTAL|string_format:"%.2f"}</td>
                                                                <td>
                                                                    <a href="index.php?page=invoice:delete_parts&parts_id={$parts_items[p].INVOICE_PARTS_ID}" onclick="return confirmDelete('{$translate_invoice_parts_delete_mes_confirmation}');">
                                                                        <img src="{$theme_images_dir}icons/delete.gif" alt="" border="0" height="14" width="14" onMouseOver="ddrivetip('<b>{$translate_invoice_delete_parts_record|nl2br|regex_replace:"/[\r\t\n]/":" "}</b>');" onMouseOut="hideddrivetip();">
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                         {/section}
                                                        <tr>
                                                            <td colspan="5" style="text-align:right;"><b>{$translate_invoice_parts_total}</b></td>
                                                            <td style="text-align:left;">{$currency_sym}{$parts_sub_total|string_format:"%.2f"}</td>
                                                        </tr>
                                                    </table>
                                                {/if}
                                                <br>
                                                <!-- Additional Javascript Parts Table -->
                                                <table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable" id="parts_items">
                                                    <tr class="olotd4">
                                                        <td class="row2"><b>{$translate_invoice_no}</b></td>
                                                        <td class="row2"><b>{$translate_invoice_count}-QTY</b></td>
                                                        <td class="row2"><b>{$translate_invoice_description}</b></td>
                                                        <td class="row2"><b>{$translate_invoice_price}</b></td>
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
                                            <td class="menuhead2">&nbsp;{$translate_invoice_total}</td>
                                        </tr>
                                        <tr>
                                            <td class="menutd2">
                                                <table width="100%" border="1" cellpadding="3" cellspacing="0" class="olotable">
                                                    <tr>
                                                        <td class="olotd4" width="80%" align="right"><b>{$translate_invoice_sub_total}</b></td>
                                                        <td class="olotd4" width="20%" align="right">{$currency_sym}{$invoice_details.SUB_TOTAL|string_format:"%.2f"}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="olotd4" width="80%" align="right"><b>{$translate_invoice_discount} (@ {$invoice_details.DISCOUNT_RATE|string_format:"%.2f"}%)</b></td>
                                                        <td class="olotd4" width="20%" align="right">- {$currency_sym}{$invoice_details.DISCOUNT|string_format:"%.2f"}</td>
                                                    </tr>                                                        
                                                    <tr>                                                            
                                                        <td class="olotd4" width="80%" align="right"><b>{$translate_invoice_tax} (@ {$invoice_details.TAX_RATE}%)</b></td>
                                                        <td class="olotd4" width="20%" align="right">{$currency_sym}{$invoice_details.TAX|string_format:"%.2f"}</td>                                                            
                                                    </tr>
                                                    <tr>
                                                        <td class="olotd4" width="80%" align="right"><b>{$translate_invoice_total}</b></td>
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