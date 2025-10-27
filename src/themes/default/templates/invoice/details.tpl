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
                    <td class="menuhead2" width="80%">&nbsp;{t}Details for{/t} {t}Invoice ID{/t} {$invoice_details.invoice_id}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}INVOICE_DETAILS_HELP_TITLE{/t}</strong></div><hr><div>{t escape=js}INVOICE_DETAILS_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
                        </a>
                    </td>
                </tr>

                <!-- Content -->
                <tr>
                    <td class="menutd2" colspan="2">
                        <table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">

                            <!-- Invoice Details Block -->
                            <tr>
                                <td class="menutd">

                                    <!-- Invoice Information -->
                                    <table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">

                                        <tr class="olotd4">

                                            <td class="row2"><b>{t}Invoice ID{/t}</b></td>
                                            <td class="row2"><b>{t}Work Order{/t}</b></td>
                                            <td class="row2"><b>{t}Employee{/t}</b></td>
                                            <td class="row2"><b>{t}Date{/t}</b></td>
                                            <td class="row2"><b>{t}Due Date{/t}</b></td>
                                            <td class="row2"><b>{t}Status{/t}</b></td>
                                            <td class="row2"><b>{t}Gross{/t}</b></td>
                                            <td class="row2"><b>{t}Balance{/t}</b></td>
                                            <td class="row2"><b>{t}Date Closed{/t}</b></td>
                                        </tr>
                                        <tr class="olotd4">

                                            <td>{$invoice_id}</td>
                                            <td>
                                                {if $invoice_details.workorder_id}
                                                    <a href="index.php?component=workorder&page_tpl=details&workorder_id={$invoice_details.workorder_id}">{$invoice_details.workorder_id}</a>
                                                {else}
                                                    {t}n/a{/t}
                                                {/if}
                                            </td>
                                            <td><a href="index.php?component=user&page_tpl=details&user_id={$invoice_details.employee_id}">{$employee_display_name}</a></td>
                                            <td>{$invoice_details.date|date_format:$date_format}</td>
                                            <td>{$invoice_details.due_date|date_format:$date_format}</td>
                                            <td>
                                                {section name=s loop=$invoice_statuses}
                                                    {if $invoice_details.status == $invoice_statuses[s].status_key}{t}{$invoice_statuses[s].display_name}{/t}{/if}
                                                {/section}
                                            <td>{$currency_sym}{$invoice_details.unit_gross|string_format:"%.2f"}</td>
                                            <td><font color="#cc0000">{$currency_sym}{$invoice_details.balance|string_format:"%.2f"}</font></td>
                                            <td>{$invoice_details.closed_on|date_format:$date_format}</td>

                                        </tr>
                                        <tr class="olotd4">

                                            <!-- Scope -->
                                            <td colspan="2"><b>{t}Work Order Scope{/t}:</b></td>
                                            <td colspan="8">{if $invoice_details.workorder_id}{$workorder_details.scope}{else}{t}n/a{/t}{/if}</td>

                                        </tr>
                                        <tr>

                                            <!-- Client Details -->
                                            <td colspan="5" valign="top" align="left">
                                                <b>{t}Bill{/t}</b>
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

                                            <!-- Company Details -->
                                            <td colspan="5" valign="top" >
                                                <b>{t}Pay To{/t}</b>
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

                                        <!-- Terms and Discount -->
                                        <tr>
                                            <td colspan="10" valign="top" align="left">
                                                <p><b>{t}Credit Terms{/t}: </b>{if $client_details.credit_terms}{$client_details.credit_terms}{else}{t}n/a{/t}{/if}</p>
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
                                                {if $invoice_details.unit_gross > 0 }
                                                    <button type="button" onclick="window.open('index.php?component=invoice&page_tpl=print&invoice_id={$invoice_details.invoice_id}&commContent=invoice&commType=htmlBrowser');">{t}Print HTML{/t}</button>
                                                    <button type="button" onclick="window.open('index.php?component=invoice&page_tpl=print&invoice_id={$invoice_details.invoice_id}&commContent=invoice&commType=pdfBrowser');"><img src="{$theme_images_dir}icons/pdf_small.png"  height="14" alt="pdf">{t}Print PDF{/t}</button>
                                                    <button type="button" onclick="window.open('index.php?component=invoice&page_tpl=print&invoice_id={$invoice_details.invoice_id}&commContent=invoice&commType=pdfDownload');"><img src="{$theme_images_dir}icons/pdf_small.png"  height="14" alt="pdf">{t}Download PDF{/t}</button>
                                                    <button type="button" onclick="confirm('Are you sure you want to email this invoice to the client?') && $.ajax( { url:'index.php?component=invoice&page_tpl=email&invoice_id={$invoice_details.invoice_id}&commContent=invoice&commType=pdfEmail', success: function(data) { $('body').append(data); } } );"><img src="{$theme_images_dir}icons/pdf_small.png"  height="14" alt="pdf">{t}Email PDF{/t}</button>
                                                    <button type="button" onclick="window.open('index.php?component=invoice&page_tpl=print&invoice_id={$invoice_details.invoice_id}&commContent=client_envelope&commType=htmlBrowser');">{t}Print Client Envelope{/t}</button>
                                                    <br>
                                                    <br>
                                                {/if}

                                                <!-- Edit Button -->
                                                {if $invoice_details.status == 'pending' || $invoice_details.status == 'unpaid'}
                                                    <button type="button" onclick="location.href='index.php?component=invoice&page_tpl=edit&invoice_id={$invoice_details.invoice_id}';">{t}Edit Invoice{/t}</button>
                                                {/if}

                                                <!-- Receive Payment Button -->
                                                {if $invoice_details.status == 'unpaid' || $invoice_details.status == 'partially_paid'}
                                                    <button type="button" onclick="location.href='index.php?component=payment&page_tpl=new&type=invoice&invoice_id={$invoice_details.invoice_id}';">{t}Receive Payment{/t}</button>
                                                {/if}

                                                <!-- Credit Note Button -->
                                                {if $allowed_to_create_creditnote}
                                                    <button type="button" onclick="window.open('index.php?component=creditnote&page_tpl=new&invoice_id={$invoice_details.invoice_id}');">{t}Add Sales Credit Note{/t}</button>
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

                            <!-- Invoice Items -->
                            <tr>
                                <td>
                                    <table width="100%" cellpadding="4" cellspacing="0" border="0" >
                                        <tr>
                                            <td class="menuhead2">&nbsp;{t}Items{/t}</td>
                                        </tr>
                                        <tr>
                                            <td class="menutd2">
                                                {if $invoice_items}
                                                    <table width="100%" cellpadding="3" cellspacing="0" border="0" class="olotable">
                                                        <tr class="olotd4">
                                                            <td class="row2"><b>{t}No{/t}</b></td>
                                                            <td class="row2"><b>{t}Description{/t}</b></td>
                                                            <td class="row2" width="12"><b>{t}Unit Qty{/t}</b></td>
                                                            {if $invoice_details.tax_system != 'no_tax'}
                                                                <td class="row2"><b>{t}Unit Net{/t}</b></td>
                                                            {else}
                                                                <td class="row2"><b>{t}Unit Gross{/t}</b></td>
                                                            {/if}
                                                            <td class="row2"><b>{t}Unit Discount{/t}</b></td>
                                                            {if $invoice_details.tax_system != 'no_tax'}
                                                                <td class="row2"><b>{t}Net{/t}</b></td>
                                                                {if '/^vat_/'|preg_match:$invoice_details.tax_system}<td class="row2"><b>{t}VAT Tax Code{/t}</b></td>{/if}
                                                                <td class="row2"><b>{if '/^vat_/'|preg_match:$invoice_details.tax_system}{t}VAT{/t}{else}{t}Sales Tax{/t}{/if} {t}Rate{/t}</b></td>
                                                                <td class="row2"><b>{if '/^vat_/'|preg_match:$invoice_details.tax_system}{t}VAT{/t}{else}{t}Sales Tax{/t}{/if}</b></td>
                                                            {/if}
                                                            <td class="row2"><b>{t}Gross{/t}</b></td>
                                                        </tr>
                                                        {section name=l loop=$invoice_items}
                                                            <tr class="olotd4">
                                                                <td>{$smarty.section.l.index + 1}</td>
                                                                <td>{$invoice_items[l].description}</td>
                                                                <td>{$invoice_items[l].unit_qty|string_format:"%.2f"}</td>
                                                                <td>{$currency_sym}{$invoice_items[l].unit_net|string_format:"%.2f"}</td>
                                                                <td>{$currency_sym}{$invoice_items[l].unit_discount|string_format:"%.2f"}</td>
                                                                {if $invoice_details.tax_system != 'no_tax'}
                                                                    <td>{$currency_sym}{$invoice_items[l].subtotal_net|string_format:"%.2f"}</td>
                                                                    {if $invoice_items[l].sales_tax_exempt}
                                                                        <td colspan="2" align="center">{t}Exempt{/t}</td>
                                                                    {elseif $invoice_items[l].vat_tax_code == 'T2'}
                                                                        <td colspan="3" align="center">{t}Exempt{/t}</td>
                                                                    {else}
                                                                        {if '/^vat_/'|preg_match:$invoice_details.tax_system}
                                                                            <td>
                                                                                {section name=s loop=$vat_tax_codes}
                                                                                    {if $invoice_items[l].vat_tax_code == $vat_tax_codes[s].tax_key}{$vat_tax_codes[s].tax_key} - {t}{$vat_tax_codes[s].display_name}{/t}{/if}
                                                                                {/section}
                                                                            </td>
                                                                        {/if}
                                                                        <td>{$invoice_items[l].unit_tax_rate|string_format:"%.2f"}%</td>
                                                                        <td>{$currency_sym}{$invoice_items[l].subtotal_tax|string_format:"%.2f"}</td>
                                                                    {/if}
                                                                {/if}
                                                                <td>{$currency_sym}{$invoice_items[l].subtotal_gross|string_format:"%.2f"}</td>
                                                            </tr>
                                                        {/section}

                                                        <!-- Invoice Items Sub Totals -->
                                                        <tr>
                                                            <td colspan="10" style="text-align:right;">
                                                                <table style="margin-top: 10px;" width="750" cellpadding="3" cellspacing="0" style="border-collapse: collapse;" align="right">
                                                                    <tr>
                                                                        <td style="text-align:right;"><b>{t}Invoice Items{/t} {t}Totals{/t}</b></td>
                                                                        <td width="80" align="right">{t}Discount{/t}: {$currency_sym}{$invoice_items_subtotals.subtotal_discount|string_format:"%.2f"}</td>
                                                                        {if $invoice_details.tax_system != 'no_tax'}
                                                                            <td width="80" align="right">{t}Net{/t}: {$currency_sym}{$invoice_items_subtotals.subtotal_net|string_format:"%.2f"}</td>
                                                                            <td width="80" align="right">{if '/^vat_/'|preg_match:$invoice_details.tax_system}{t}VAT{/t}{else}{t}Sales Tax{/t}{/if}: {$currency_sym}{$invoice_items_subtotals.subtotal_tax|string_format:"%.2f"}</td>
                                                                        {/if}
                                                                        <td width="80" align="right">{t}Gross{/t}: {$currency_sym}{$invoice_items_subtotals.subtotal_gross|string_format:"%.2f"}</td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>


                                                    </table>
                                                {/if}
                                                <br>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <!-- Vouchers -->
                            <tr>
                                <td>
                                    {include file='voucher/blocks/display_vouchers_block.tpl' display_vouchers=$display_vouchers block_title=_gettext("Vouchers")}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="10" style="text-align:right;">
                                    <table style="margin-top: 10px;" width="750" cellpadding="3" cellspacing="0" style="border-collapse: collapse;" align="right">
                                        <tr>
                                            <td style="text-align:right;"><b>{t}Voucher{/t} {t}Totals{/t}</b></td>
                                            {if $invoice_details.tax_system != 'no_tax'}
                                                <td width="80" align="right">{t}Net{/t}: {$currency_sym}{$voucher_subtotals.subtotal_net|string_format:"%.2f"}</td>
                                                <td width="80" align="right">{if '/^vat_/'|preg_match:$invoice_details.tax_system}{t}VAT{/t}{else}{t}Sales Tax{/t}{/if}: {$currency_sym}{$voucher_subtotals.subtotal_tax|string_format:"%.2f"}</td>
                                            {/if}
                                            <td width="80" align="right">{t}Gross{/t}: {$currency_sym}{$voucher_subtotals.subtotal_gross|string_format:"%.2f"}</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <!-- Totals Section -->
                            <tr>
                                <td>
                                    <table width="100%" cellpadding="4" cellspacing="0" border="0" >
                                        <tr>
                                            <td class="menuhead2">&nbsp;{t}Invoice Total{/t}</td>
                                        </tr>
                                        <tr>
                                            <td class="menutd2">
                                                <table width="100%" border="1" cellpadding="3" cellspacing="0" class="olotable">
                                                    <tr>
                                                        <td class="olotd4" width="80%" align="right"><b>{t}Discount{/t}</b></td>
                                                        <td class="olotd4" width="20%" align="right">{$currency_sym}{$invoice_details.unit_discount|string_format:"%.2f"}</td>
                                                    </tr>
                                                    {if $invoice_details.tax_system != 'no_tax'}
                                                        <tr>
                                                            <td class="olotd4" width="80%" align="right"><b>{t}Net{/t}</b></td>
                                                            <td class="olotd4" width="20%" align="right">{$currency_sym}{$invoice_details.unit_net|string_format:"%.2f"}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="olotd4" width="80%" align="right"><b>{if '/^vat_/'|preg_match:$invoice_details.tax_system}{t}VAT{/t}{else}{t}Sales Tax{/t} (@ {$invoice_details.sales_tax_rate|string_format:"%.2f"}%){/if}</b></td>
                                                            <td class="olotd4" width="20%" align="right">{$currency_sym}{$invoice_details.unit_tax|string_format:"%.2f"}</td>
                                                        </tr>
                                                    {/if}
                                                    <tr>
                                                        <td class="olotd4" width="80%" align="right"><b>{t}Gross{/t}</b></td>
                                                        <td class="olotd4" width="20%" align="right">{$currency_sym}{$invoice_details.unit_gross|string_format:"%.2f"}</td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <!-- Note -->
                            <tr>
                                <td>
                                    <table width="100%" cellpadding="4" cellspacing="0" border="0" >
                                        <tr>
                                            <td class="menuhead2">&nbsp;{t}Note{/t}</td>
                                        </tr>
                                        <tr>
                                            <td class="menutd2">{$invoice_details.note}</td>
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
