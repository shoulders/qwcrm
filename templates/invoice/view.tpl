<!-- invoice view -->
<table width="100%" border="0" cellpadding="20" cellspacing="5">
    <tr>
        <td>
            <table width="700" cellpadding="4" cellspacing="0" border="0" >
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{$translate_invoice_for} {$wo_id}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a><img src="images/icons/16x16/help.gif" border="0" alt=""
                                    onMouseOver="ddrivetip('<b>{$translate_invoice_view_help_title|nl2br|regex_replace:"/[\r\t\n]/":" "}</b><hr><p>{$translate_invoice_view_help_content|nl2br|regex_replace:"/[\r\t\n]/":" "}</p>')"
                                    onMouseOut="hideddrivetip()"></a>
                    </td>
                </tr><tr>
                    <td class="menutd2" colspan="2">
                        <table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
                            <tr>
                                <td class="menutd">

                                    {if $error_msg != ""}
                                    <br>
                                    {include file="core/error.tpl"}
                                    <br>
                                    {/if}
                                    <!-- Content -->


                                    <table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
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
                                        </tr><tr class="olotd4">
                                            <td>{$invoice.INVOICE_ID}</td>
                                            <td>{$invoice.INVOICE_DATE|date_format:"$date_format"}</td>
                                            <td>{$invoice.INVOICE_DUE|date_format:"$date_format"}</td>
                                            <td>{$currency_sym}{$invoice.INVOICE_AMOUNT|string_format:"%.2f"}</td>
                                            <td>{$invoice.EMPLOYEE_DISPLAY_NAME}</td>
                                            <td><a href="?page=workorder:view&amp;wo_id={$invoice.WORKORDER_ID}&amp;page_title={$translate_invoice_wo_id}&amp;{$invoice.WORKORDER_ID}">{$invoice.WORKORDER_ID}</a></td>
                                            <td>{$invoice.PAID_DATE|date_format:"$date_format"}</td>
                                            <td>{$currency_sym}{$invoice.PAID_AMOUNT|string_format:"%.2f"}</td>
                                            <td>{if $invoice.BALANCE > 0}
                                                <font color="#CC0000">{$currency_sym}{$invoice.INVOICE_AMOUNT-$invoice.PAID_AMOUNT|string_format:"%.2f"}</font>
                                                {else}
                                                    {$currency_sym}{$invoice.BALANCE|string_format:"%.2f"}
                                                {/if}
                                            </td>
                                        </tr><tr>
                                            <td colspan="3" valign="top">
                                                <b>{$translate_invoice_bill}</b>
										{foreach item=item from=$customer_details}
                                                <table width="100%" cellpadding="0" cellspacing="0">
                                                    <tr>
                                                        <td valign="top">
                                                            <a href="?page=customer:customer_details&amp;customer_id={$item.CUSTOMER_ID}&amp;page_title={$item.CUSTOMER_DISPLAY_NAME}">{$item.CUSTOMER_DISPLAY_NAME}</a><br>
                                                            {$item.CUSTOMER_PHONE}<br>
                                                            {$item.CUSTOMER_ADDRESS}<br>
                                                            {$item.CUSTOMER_CITY}, {$item.CUSTOMER_STATE} {$item.CUSTOMER_ZIP}<br>
                                                            {$item.CUSTOMER_EMAIL}
                                                        </td>
                                                    </tr>
                                                </table>
                                            {/foreach}
                                            </td>

                                            <td colspan="6" valign="top"  align="right">
                                                <table cellpadding="0" cellspacing="0">
                                                    <tr>
                                                        <td valign="top">
                                                            <b>{$translate_invoice_pay}</b>
                                                            <table cellpadding="0" cellspacing="0" width="100%">
                                                                <tr>
                                                                    <td valign="top">
                                                                    {section name=x loop=$company}
                                                                            {$company[x].COMPANY_NAME} <br>
                                                                            {$company[x].COMPANY_ADDRESS}<br>
                                                                            {$company[x].COMPANY_CITY}, {$company[x].COMPANY_STATE} {$company[x].COMPANY_ZIP}<br>
                                                                            {$company[x].COMPANY_PHONE}<br>
                                                                    {/section}
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
                                    
                                    <form action="">
                                        <button type="button" name="{$translate_invoice_print}" onClick=window.open('?page=invoice:print&amp;print_type=html&amp;wo_id={$invoice.WORKORDER_ID}&amp;customer_id={$invoice.CUSTOMER_ID}&amp;invoice_id={$invoice.INVOICE_ID}&amp;escape=1')>{$translate_invoice_print}</button>
                                        <button type="button" name="{$translate_invoice_pdf}" OnClick=window.open('?page=invoice:print&amp;print_type=pdf&amp;wo_id={$invoice.WORKORDER_ID}&customer_id={$invoice.CUSTOMER_ID}&invoice_id={$invoice.INVOICE_ID}&escape=1')><img src="images/icons/pdf_small.png"  height="14" alt="pdf">&nbsp;{$translate_invoice_pdf}</button>
                                    </form>
                                    {if $invoice.INVOICE_AMOUNT-$invoice.PAID_AMOUNT > 0 }
                                        <button type="button" name="{$translate_invoice_bill_customer}" OnClick=location.href='?page=billing:new&wo_id={$invoice.WORKORDER_ID}&customer_id={$invoice.CUSTOMER_ID}&invoice_id={$invoice.INVOICE_ID}&page_title=Receiving%20Payment%20for%20{$invoice.INVOICE_ID}'>{$translate_invoice_bill_customer}</button>
                                    {/if}
                                    
                                    
                                    <br>
                                    <table width="100%" cellpadding="4" cellspacing="0" border="0" >
                                        <tr>
                                            <td class="menuhead2">&nbsp;{$translate_invoice_labor}</td>
                                        </tr><tr>
                                            <td class="menutd2">
										{if $labor != '0'}	

                                                <table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
                                                    <tr  class="olotd4">
                                                        <td class="row2"><b>{$translate_invoice_no}</b></td>
                                                        <td class="row2" width="12"><b>{$translate_invoice_hours}</b></td>
                                                        <td class="row2"><b>{$translate_invoice_description}</b></td>
                                                        <td class="row2"><b>{$translate_invoice_rate}</b></td>
                                                        <td class="row2"><b>{$translate_invoice_total}</b></td>
                                                    </tr>
												{section name=q loop=$labor}
                                                    <tr class="olotd4">
                                                        <td>{$smarty.section.q.index+1}</td>
                                                        <td>{$labor[q].INVOICE_LABOR_UNIT}</td>
                                                        <td>{$labor[q].INVOICE_LABOR_DESCRIPTION}</td>
                                                        <td>{$currency_sym}{$labor[q].INVOICE_LABOR_RATE|string_format:"%.2f"}</td>
                                                        <td>{$currency_sym}{$labor[q].INVOICE_LABOR_SUBTOTAL|string_format:"%.2f"}</td>
                                                    </tr>
                                                        {/section}
                                                    <tr>
                                                        <td colspan="4" style="text-align:right;"><b>{$translate_invoice_labour_total}</b></td>
                                                        <td style="text-align:left;">{$currency_sym}{$labour_sub_total_sum}</td>
                                                    </tr>
                                                </table>
										{/if}
                                            </td>
                                        </tr>
                                    </table>

                                    <br>
                                    <table width="100%" cellpadding="4" cellspacing="0" border="0" >
                                        <tr>
                                            <td class="menuhead2">&nbsp;{$translate_invoice_parts}</td>
                                        </tr><tr>
                                            <td class="menutd2">
                                                        {if $parts != '0'}
                                                <table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
                                                    <tr class="olotd4">
                                                        <td class="row2"><b>{$translate_invoice_no}</b></td>
                                                        <td class="row2"><b>{$translate_invoice_count}</b></td>
                                                        <td class="row2"><b>{$translate_invoice_description}</b></td>
                                                        <td class="row2"><b>{$translate_invoice_man}</b></td>
                                                        <td class="row2"><b>{$translate_invoice_price}</b></td>
                                                        <td class="row2"><b>{$translate_invoice_total}</b></td>
                                                    </tr>
                                                        {section name=w loop=$parts}
                                                    <tr class="olotd4">
                                                        <td>{$smarty.section.w.index+1}</td>
                                                        <td>{$parts[w].INVOICE_PARTS_COUNT}</td>
                                                        <td>{$parts[w].INVOICE_PARTS_DESCRIPTION}</td>
                                                        <td>{$parts[w].INVOICE_PARTS_MANUF}</td>
                                                        <td>{$currency_sym}{$parts[w].INVOICE_PARTS_AMOUNT|string_format:"%.2f"}</td>
                                                        <td>{$currency_sym}{$parts[w].INVOICE_PARTS_SUBTOTAL|string_format:"%.2f"}</td>
                                                    </tr>
                                                        {/section}
                                                        <tr>
                                                            <td colspan="4" style="text-align:right;"><b>{$translate_invoice_parts_total}</b></td>
                                                            <td style="text-align:left;"">{$currency_sym}{$parts_sub_total_sum}</td>
                                                        </tr>
                                                </table>
                                                {/if}
                                            </td>
                                        </tr>
                                    </table>
                                    <br>
                                    <table width="100%" cellpadding="4" cellspacing="0" border="0" >
                                        <tr>
                                            <td class="menuhead2">&nbsp;{$translate_invoice_total}</td>
                                        </tr><tr>
                                            <td class="menutd2">
                                                <table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
                                                    <tr>
                                                        <td class="olotd4" width="80%" align="right"><b>{$translate_invoice_sub_total}</b></td>
                                                        <td class="olotd4" width="20%" align="right">{$currency_sym}{$invoice.SUB_TOTAL}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="olotd4" width="80%" align="right"><b>{$translate_invoice_discount}</b></td>
                                                        <td class="olotd4" width="20%" align="right">- {$currency_sym}{$invoice.DISCOUNT}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="olotd4" width="80%" align="right"><b>{$translate_invoice_shipping}</b></td>
                                                        <td class="olotd4" width="20%" align="right">{$currency_sym}{$invoice.SHIPPING}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="olotd4" width="80%" align="right"><b>{$translate_invoice_tax}</b></td>
                                                        <td class="olotd4" width="20%" align="right">{$currency_sym}{$invoice.TAX}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="olotd4" width="80%" align="right"><b>{$translate_invoice_total}</b></td>
                                                        <td class="olotd4" width="20%" align="right">{$currency_sym}{$invoice.INVOICE_AMOUNT}</td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                    <br>
                                    <!-- Transaction log -->
                                    <table width="100%" cellpadding="4" cellspacing="0" border="0" >
                                        <tr>
                                            <td class="menuhead2">&nbsp;{$translate_invoice_trans_log}</td>
                                        </tr><tr>
                                            <td class="menutd2">
                                                <table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
                                                    <tr class="olotd4">
                                                        <td class="row2"><b>{$translate_invoice_trans_id}</b></td>
                                                        <td class="row2"><b>{$translate_invoice_date}</b></td>
                                                        <td class="row2"><b>{$translate_invoice_amount}</b></td>
                                                        <td class="row2"><b>{$translate_invoice_type}</b></td>
                                                    </tr>
												{section name=r loop=$trans}
                                                    <tr class="olotd4">
                                                        <td>{$trans[r].TRANSACTION_ID}</td>
                                                        <td>{$trans[r].DATE|date_format:"$date_format"}</td>
                                                        <td><b>{$currency_sym}</b>{$trans[r].AMOUNT}</td>
                                                        <td>
                                                            {if $trans[r].TYPE == 1}
                                                                    {$translate_invoice_cc}
                                                            {elseif $trans[r].TYPE == 2}
                                                                    {$translate_invoice_check}
                                                            {elseif $trans[r].TYPE == 3}
                                                                    {$translate_invoice_cash}
                                                            {elseif $trans[r].TYPE == 4}
                                                                    {$translate_invoice_gift}
                                                            {elseif $trans[r].TYPE == 5}
                                                                    {$translate_invoice_paypal}
                                                            {/if}
                                                        </td>
                                                    </tr><tr class="olotd4">
                                                        <td><b>{$translate_invoice_memo}</b></td>
                                                        <td colspan="3">{$trans[r].MEMO}</td>
                                                    </tr>
                                                    {/section}
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

