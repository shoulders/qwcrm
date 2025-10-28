<!-- details.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<table width="100%" border="0" cellpadding="20" cellspacing="5">
    <tr>
        <td>
            <table width="700" cellpadding="4" cellspacing="0" border="0">

                <!-- tile -->
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{t}Details for{/t} {t}Credit Note ID{/t} {$creditnote_details.creditnote_id}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}CREDITNOTE_DETAILS_HELP_TITLE{/t}</strong></div><hr><div>{t escape=js}CREDITNOTE_DETAILS_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
                        </a>
                    </td>
                </tr>

                <!-- Content -->
                <tr>
                    <td class="menutd2" colspan="2">
                        <table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">

                            <!-- Credit Note Details Block -->
                            <tr>
                                <td class="menutd">

                                    <!-- Credit Note Information -->
                                    <table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">

                                        <tr class="olotd4">

                                            <td class="row2"><b>{t}Credit Note ID{/t}</b></td>
                                            <td class="row2"><b>{t}Type{/t}</b></td>
                                            {if $creditnote_details.type == 'sales'}
                                                <td class="row2"><b>{t}Client ID{/t}<br>{t}(created from){/t}</b></td>
                                                <td class="row2"><b>{t}Invoice ID{/t}<br>{t}(created from){/t}</b></td>
                                            {else}
                                                <td class="row2"><b>{t}Supplier ID{/t}<br>{t}(created from){/t}</b></td>
                                                <td class="row2"><b>{t}Expense ID{/t}<br>{t}(created from){/t}</b></td>
                                            {/if}
                                            <td class="row2"><b>{t}Employee{/t}</b></td>
                                            <td class="row2"><b>{t}Date{/t}</b></td>
                                            <td class="row2"><b>{t}Expiry Date{/t}</b></td>
                                            <td class="row2"><b>{t}Status{/t}</b></td>
                                            <td class="row2"><b>{t}Gross{/t}</b></td>
                                            <td class="row2"><b>{t}Balance{/t}</b></td>
                                            <td class="row2"><b>{t}Date Closed{/t}</b></td>
                                        </tr>
                                        <tr class="olotd4">
                                            <td>{$creditnote_id}</td>
                                            <td>
                                                {section name=t loop=$creditnote_types}
                                                    {if $creditnote_details.type == $creditnote_types[t].type_key}{t}{$creditnote_types[t].display_name}{/t}{/if}
                                                {/section}
                                            </td>
                                            {if $creditnote_details.type == 'sales'}
                                                <td><a href="index.php?component=client&page_tpl=details&client_id={$creditnote_details.client_id}">{$creditnote_details.client_id}</a></td>
                                                <td><a href="index.php?component=invoice&page_tpl=details&invoice_id={$creditnote_details.invoice_id}">{$creditnote_details.invoice_id}</a></td>
                                            {else}
                                                <td><a href="index.php?component=client&page_tpl=details&supplier_id={$creditnote_details.supplier_id}">{$creditnote_details.supplier_id}</a></td>
                                                <td><a href="index.php?component=expense&page_tpl=details&expense_id={$creditnote_details.expense_id}">{$creditnote_details.expense_id}</a></td>
                                            {/if}
                                            <td><a href="index.php?component=user&page_tpl=details&user_id={$creditnote_details.employee_id}">{$employee_display_name}</a></td>
                                            <td>{$creditnote_details.date|date_format:$date_format}</td>
                                            <td>{$creditnote_details.expiry_date|date_format:$date_format}</td>
                                            <td>
                                                {section name=s loop=$creditnote_statuses}
                                                    {if $creditnote_details.status == $creditnote_statuses[s].status_key}{t}{$creditnote_statuses[s].display_name}{/t}{/if}
                                                {/section}
                                            </td>
                                            <td>{$currency_sym}{$creditnote_details.unit_gross|string_format:"%.2f"}</td>
                                            <td><font color="#cc0000">{$currency_sym}{$creditnote_details.balance|string_format:"%.2f"}</font></td>
                                            <td>
                                                {$creditnote_details.closed_on|date_format:$date_format}<br>
                                            </td>

                                        </tr>

                                        <tr>

                                            {if $creditnote_details.client_id}
                                                    <!-- Client Details -->
                                                    <td colspan="5" valign="top" align="left">
                                                        <b>{t}Client Details{/t}</b>
                                                        <table cellpadding="0" cellspacing="0">
                                                            <tr>
                                                                <td valign="top">
                                                                    <a href="index.php?component=client&page_tpl=details&client_id={$client_details.client_id}">{$client_details.display_name}</a><br>
                                                                    {$client_details.address|nl2br}<br>
                                                                    {$client_details.city}<br>
                                                                    {$client_details.state}<br>
                                                                    {$client_details.zip}<br>
                                                                    {$client_details.country}<br>
                                                                    {$client_details.primary_phone}<br>
                                                                    {$client_details.email}
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                {else if $creditnote_details.supplier_id}
                                                    <!-- Supplier Details -->
                                                    <td colspan="5" valign="top" align="left">
                                                        <b>{t}Supplier Details{/t}</b>
                                                        <table cellpadding="0" cellspacing="0">
                                                            <tr>
                                                                <td valign="top">
                                                                    <a href="index.php?component=supplier&page_tpl=details&supplier_id={$supplier_details.supplier_id}">{$supplier_details.display_name}</a><br>
                                                                    {$supplier_details.address|nl2br}<br>
                                                                    {$supplier_details.city}<br>
                                                                    {$supplier_details.state}<br>
                                                                    {$supplier_details.zip}<br>
                                                                    {$supplier_details.country}<br>
                                                                    {$supplier_details.primary_phone}<br>
                                                                    {$supplier_details.email}
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                {/if}

                                            <!-- Unused -->
                                            <td colspan="5" valign="top">
                                                <table cellpadding="0" cellspacing="0" width="100%">
                                                    <tr>
                                                        <td valign="top">
                                                            {$company_details.company_name}<br>
                                                            {$company_details.address|nl2br|regex_replace:"/[\r\t\n]/":" "}<br>
                                                            {$company_details.city}<br>
                                                            {$company_details.state}<br>
                                                            {$company_details.zip}<br>
                                                            {$company_details.country}<br>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>

                                        </tr>

                                        <!-- Reference -->
                                        <tr>
                                            <td colspan="10" valign="top" align="left">
                                                <b>{t}Reference{/t}: </b>{$creditnote_details.reference}
                                            </td>
                                        </tr>

                                        <!-- Reason for Credit Note -->
                                        <tr>
                                            <td colspan="10" valign="top" align="left">
                                                <b>{t}Note{/t}: </b>
                                                <div style="width: 300px; word-wrap: break-word;">{$creditnote_details.note}</div>
                                            </td>
                                        </tr>

                                    </table>
                                </td>
                            </tr>

                            <!-- Function Buttons -->
                            <tr>
                                <td>
                                    <table width="100%" cellpadding="4" cellspacing="0" border="0" id="payments_log">
                                        <tr>
                                            <td class="menuhead2">&nbsp;{t}Function Buttons{/t}</td>
                                        </tr>
                                        <tr>
                                            <td class="menutd2">

                                                <!-- Print Buttons -->
                                                {if $creditnote_details.unit_gross > 0 && $creditnote_details.client_id}
                                                    <button type="button" onclick="window.open('index.php?component=creditnote&page_tpl=print&creditnote_id={$creditnote_details.creditnote_id}&commContent=creditnote&commType=htmlBrowser');">{t}Print HTML{/t}</button>
                                                    <button type="button" onclick="window.open('index.php?component=creditnote&page_tpl=print&creditnote_id={$creditnote_details.creditnote_id}&commContent=creditnote&commType=pdfBrowser');"><img src="{$theme_images_dir}icons/pdf_small.png"  height="14" alt="pdf">{t}Print PDF{/t}</button>
                                                    <button type="button" onclick="window.open('index.php?component=creditnote&page_tpl=print&creditnote_id={$creditnote_details.creditnote_id}&commContent=creditnote&commType=pdfDownload');"><img src="{$theme_images_dir}icons/pdf_small.png"  height="14" alt="pdf">{t}Download PDF{/t}</button>
                                                    <button type="button" onclick="confirm('Are you sure you want to email this credit note to the client?') && $.ajax( { url:'index.php?component=creditnote&page_tpl=email&creditnote_id={$creditnote_details.creditnote_id}&commContent=creditnote&commType=pdfEmail', success: function(data) { $('body').append(data); } } );"><img src="{$theme_images_dir}icons/pdf_small.png"  height="14" alt="pdf">{t}Email PDF{/t}</button>
                                                    <button type="button" onclick="window.open('index.php?component=creditnote&page_tpl=print&creditnote_id={$creditnote_details.creditnote_id}&commContent=client_envelope&commType=htmlBrowser');">{t}Print Client Envelope{/t}</button>
                                                    <br>
                                                    <br>
                                                {/if}

                                                <!-- Edit Button -->
                                                {if $creditnote_details.status == 'pending' || $creditnote_details.status == 'unused'}
                                                    <button type="button" onclick="location.href='index.php?component=creditnote&page_tpl=edit&creditnote_id={$creditnote_details.creditnote_id}';">{t}Edit Credit Note{/t}</button>
                                                {/if}

                                                <!-- Record Refund Button -->
                                                {if ($creditnote_details.status == 'unused' || $creditnote_details.status == 'partially_used') && $creditnote_details.client_id}
                                                    <button type="button" onclick="location.href='index.php?component=payment&page_tpl=new&type=creditnote&creditnote_id={$creditnote_details.creditnote_id}';">{t}Record Refund{/t} / {t}Refund to Client{/t}</button>
                                                {/if}

                                                <!-- Apply Payment Buttons -->
                                                {if $creditnote_details.balance > 0 && $creditnote_details.supplier_id}
                                                    <button type="button" onclick="location.href='index.php?component=payment&page_tpl=new&type=creditnote&creditnote_id={$creditnote_details.creditnote_id}';">{t}Apply Payment from Supplier{/t}</button>
                                                {/if}

                                                {if $creditnote_details.status == 'unused' && $creditnote_details.invoice_id}
                                                    <button type="button" onclick="location.href='index.php?component=payment&page_tpl=new&type=invoice&method=credit_note&invoice_id={$creditnote_details.invoice_id}&creditnote_id={$creditnote_details.creditnote_id}';">{t}Apply Credit Note to Invoice{/t}</button>
                                                {/if}
                                                {if $creditnote_details.status == 'unused' && $creditnote_details.expense_id}
                                                    <button type="button" onclick="location.href='index.php?component=payment&page_tpl=new&type=expense&method=credit_note&expense_id={$creditnote_details.expense_id}&creditnote_id={$creditnote_details.creditnote_id}';">{t}Apply Credit Note to Expense{/t}</button>
                                                {/if}

                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                </tr>

                            <!-- Payments -->
                            <tr>
                                <td>
                                    {include file='payment/blocks/display_payments_block.tpl' display_payments=$display_payments block_title=_gettext("Payments")}
                                </td>
                            </tr>

                            <!-- Credit Note Items -->
                            <tr>
                                <td>
                                    <table width="100%" cellpadding="4" cellspacing="0" border="0" >
                                        <tr>
                                            <td class="menuhead2">&nbsp;{t}Items{/t}</td>
                                        </tr>
                                        <tr>
                                            <td class="menutd2">
                                                {if $creditnote_items}
                                                    <table width="100%" cellpadding="3" cellspacing="0" border="0" class="olotable">
                                                        <tr class="olotd4">
                                                            <td class="row2"><b>{t}No{/t}</b></td>
                                                            <td class="row2"><b>{t}Description{/t}</b></td>
                                                            <td class="row2" width="12"><b>{t}Unit Qty{/t}</b></td>
                                                            {if $creditnote_details.tax_system != 'no_tax'}
                                                                <td class="row2"><b>{t}Unit Net{/t}</b></td>
                                                            {else}
                                                                <td class="row2"><b>{t}Unit Gross{/t}</b></td>
                                                            {/if}
                                                            <td class="row2"><b>{t}Unit Discount{/t}</b></td>
                                                            {if $creditnote_details.tax_system != 'no_tax'}
                                                                <td class="row2"><b>{t}Net{/t}</b></td>
                                                                {if '/^vat_/'|preg_match:$creditnote_details.tax_system}<td class="row2"><b>{t}VAT Tax Code{/t}</b></td>{/if}
                                                                <td class="row2"><b>{if '/^vat_/'|preg_match:$creditnote_details.tax_system}{t}VAT{/t}{else}{t}Sales Tax{/t}{/if} {t}Rate{/t}</b></td>
                                                                <td class="row2"><b>{if '/^vat_/'|preg_match:$creditnote_details.tax_system}{t}VAT{/t}{else}{t}Sales Tax{/t}{/if}</b></td>
                                                            {/if}
                                                            <td class="row2"><b>{t}Gross{/t}</b></td>
                                                        </tr>
                                                        {section name=l loop=$creditnote_items}
                                                            <tr class="olotd4">
                                                                <td>{$smarty.section.l.index + 1}</td>
                                                                <td>{$creditnote_items[l].description}</td>
                                                                <td>{$creditnote_items[l].unit_qty|string_format:"%.2f"}</td>
                                                                <td>{$currency_sym}{$creditnote_items[l].unit_net|string_format:"%.2f"}</td>
                                                                <td>{$currency_sym}{$creditnote_items[l].unit_discount|string_format:"%.2f"}</td>
                                                                {if $creditnote_details.tax_system != 'no_tax'}
                                                                    <td>{$currency_sym}{$creditnote_items[l].subtotal_net|string_format:"%.2f"}</td>
                                                                    {if $creditnote_items[l].sales_tax_exempt}
                                                                        <td colspan="2" align="center">{t}Exempt{/t}</td>
                                                                    {elseif $creditnote_items[l].vat_tax_code == 'T2'}
                                                                        <td colspan="3" align="center">{t}Exempt{/t}</td>
                                                                    {else}
                                                                        {if '/^vat_/'|preg_match:$creditnote_details.tax_system}
                                                                            <td>
                                                                                {section name=s loop=$vat_tax_codes}
                                                                                    {if $creditnote_items[l].vat_tax_code == $vat_tax_codes[s].tax_key}{$vat_tax_codes[s].tax_key} - {t}{$vat_tax_codes[s].display_name}{/t}{/if}
                                                                                {/section}
                                                                            </td>
                                                                        {/if}
                                                                        <td>{$creditnote_items[l].unit_tax_rate|string_format:"%.2f"}%</td>
                                                                        <td>{$currency_sym}{$creditnote_items[l].subtotal_tax|string_format:"%.2f"}</td>
                                                                    {/if}
                                                                {/if}
                                                                <td>{$currency_sym}{$creditnote_items[l].subtotal_gross|string_format:"%.2f"}</td>
                                                            </tr>
                                                        {/section}

                                                    </table>
                                                {/if}
                                                <br>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <!-- Totals Section -->
                            <tr>
                                <td>
                                    <table width="100%" cellpadding="4" cellspacing="0" border="0" >
                                        <tr>
                                            <td class="menuhead2">&nbsp;{t}Credit Note Total{/t}</td>
                                        </tr>
                                        <tr>
                                            <td class="menutd2">
                                                <table width="100%" border="1" cellpadding="3" cellspacing="0" class="olotable">
                                                    <tr>
                                                        <td class="olotd4" width="80%" align="right"><b>{t}Discount{/t}</b></td>
                                                        <td class="olotd4" width="20%" align="right">{$currency_sym}{$creditnote_details.unit_discount|string_format:"%.2f"}</td>
                                                    </tr>
                                                    {if $creditnote_details.tax_system != 'no_tax'}
                                                        <tr>
                                                            <td class="olotd4" width="80%" align="right"><b>{t}Net{/t}</b></td>
                                                            <td class="olotd4" width="20%" align="right">{$currency_sym}{$creditnote_details.unit_net|string_format:"%.2f"}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="olotd4" width="80%" align="right"><b>{if '/^vat_/'|preg_match:$creditnote_details.tax_system}{t}VAT{/t}{else}{t}Sales Tax{/t} (@ {$creditnote_details.sales_tax_rate|string_format:"%.2f"}%){/if}</b></td>
                                                            <td class="olotd4" width="20%" align="right">{$currency_sym}{$creditnote_details.unit_tax|string_format:"%.2f"}</td>
                                                        </tr>
                                                    {/if}
                                                    <tr>
                                                        <td class="olotd4" width="80%" align="right"><b>{t}Gross{/t}</b></td>
                                                        <td class="olotd4" width="20%" align="right">{$currency_sym}{$creditnote_details.unit_gross|string_format:"%.2f"}</td>
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
