<!-- edit.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<table width="100%" border="0" cellpadding="20" cellspacing="5">
    <tr>
        <td>
            <form action="index.php?component=otherincome&page_tpl=edit&otherincome_id={$otherincome_id}" method="post" name="edit_otherincome" id="edit_otherincome">
                <table width="1024" cellpadding="4" cellspacing="0" border="0" >

                    <!-- Title -->
                    <tr>
                        <td class="menuhead2" width="80%">&nbsp;{t}Details for{/t} {t}Otherincome ID{/t} {$otherincome_details.otherincome_id}</td>
                        <td class="menuhead2" width="20%" align="right" valign="middle">
                            <a>
                                <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}OTHERINCOME_EDIT_HELP_TITLE{/t}</strong></div><hr><div>{t escape=js}OTHERINCOME_EDIT_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
                            </a>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td class="menutd2" colspan="2">
                            <table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">

                                <!-- Otherincome Details Block -->
                                <tr>
                                    <td class="menutd">

                                        <!-- Otherincome Information -->
                                        <table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">

                                            <tr class="olotd4">
                                                <td class="row2"><b>{t}Otherincome ID{/t}</b></td>
                                                <td class="row2"><b>{t}Supplier ID{/t}</b></td>
                                                <td class="row2"><b>{t}Employee{/t}</b></td>
                                                <td class="row2"><b>{t}Date{/t}</b></td>
                                                <td class="row2"><b>{t}Due Date{/t}</b></td>
                                                <td class="row2"><b>{t}Status{/t}</b></td>
                                                <td class="row2"><b>{t}Gross{/t}</b></td>
                                                <td class="row2"><b>{t}Balance{/t}</b></td>
                                                <td class="row2"><b>{t}Date Closed{/t}</b></td>
                                            </tr>
                                            <tr class="olotd4">
                                                <td>{$otherincome_id}</td>
                                                <td>
                                                    {if $otherincome_details.supplier_id}
                                                        <a href="index.php?component=supplier&page_tpl=details&supplier_id={$otherincome_details.supplier_id}">{$otherincome_details.supplier_id}</a>
                                                    {/if}
                                                </td>
                                                <td><a href="index.php?component=user&page_tpl=details&user_id={$otherincome_details.employee_id}">{$employee_display_name}</a></td>
                                                <td>{$otherincome_details.date|date_format:$date_format}</td>
                                                <td>{$otherincome_details.due_date|date_format:$date_format}</td>
                                                <td>
                                                    {section name=s loop=$otherincome_statuses}
                                                        {if $otherincome_details.status == $otherincome_statuses[s].status_key}{t}{$otherincome_statuses[s].display_name}{/t}{/if}
                                                    {/section}
                                                </td>
                                                <td>{$currency_sym}{$otherincome_details.unit_gross|string_format:"%.2f"}</td>
                                                <td><font color="#cc0000">{$currency_sym}{$otherincome_details.balance|string_format:"%.2f"}</font></td>
                                                <td>{$otherincome_details.closed_on|date_format:$date_format}</td>
                                            </tr>

                                            <!-- Otherincome Details -->
                                            <tr>
                                                <td colspan="5">
                                                    <table width="100%" cellpadding="4" cellspacing="0" border="0">
                                                        <tr>
                                                            <td style="width:100px"><strong>{t}Payee{/t}:</strong></td>
                                                            <td>{$otherincome_details.display_name}</td>
                                                        </tr>
                                                            <td><strong>{t}Reference{/t}:</strong></td>
                                                            <td>{$otherincome_details.reference} </td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>{t}Type{/t}:</strong></td>
                                                            <td>
                                                                {section name=s loop=$otherincome_types}
                                                                    {if $otherincome_details.type == $otherincome_types[s].type_key}{t}{$otherincome_types[s].display_name}{/t}{/if}
                                                                {/section}
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>

                                                <!-- Additional Information -->
                                                <td colspan="2" valign="top">
                                                    <b>{t}Additional Info{/t}</b>
                                                    <table cellpadding="0" cellspacing="0" width="100%">
                                                        <tr>
                                                            <td valign="top">
                                                                {if $otherincome_details.additional_info|otherincome_addinfo_display}{$otherincome_details.additional_info|otherincome_addinfo_display}{else}{t}none{/t}{/if}
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>

                                        </table>
                                    </td>
                                </tr>

                                <!-- Function Buttons -->
                                <tr>
                                    <td>
                                        <table width="100%" cellpadding="4" cellspacing="0" border="0">
                                            <tr>
                                                <td class="menuhead2">&nbsp;{t}Function Buttons{/t}</td>
                                            </tr>
                                            <tr>
                                                <td class="menutd2">

                                                    <!-- Edit Button -->
                                                    {if $otherincome_details.status == 'pending' || $otherincome_details.status == 'unpaid'}
                                                        <button type="button" onclick="window.location.href='index.php?component=otherincome&page_tpl=edit&otherincome_id={$otherincome_details.otherincome_id}';">{t}Edit{/t}</button>
                                                    {/if}

                                                    <!-- Apply Payment Button -->
                                                    {if $otherincome_details.status == 'unpaid' || $otherincome_details.status == 'partially_paid'}
                                                        <button type="button" onclick="window.location.href='index.php?component=payment&page_tpl=new&type=otherincome&otherincome_id={$otherincome_details.otherincome_id}';">{t}Apply Payment{/t}</button>
                                                    {/if}

                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                <!-- Otherincome Items -->
                                <tr>
                                    <td>
                                        <table width="100%" cellpadding="4" cellspacing="0" border="0" >
                                            <tr>
                                                <td class="menuhead2">&nbsp;{t}Items{/t}</td>
                                            </tr>
                                            <tr>
                                                <td class="menutd2">
                                                    {if $otherincome_items}
                                                        <table width="100%" cellpadding="3" cellspacing="0" border="0" class="olotable">
                                                            <tr class="olotd4">
                                                                <td class="row2"><b>{t}No{/t}</b></td>
                                                                <td class="row2"><b>{t}Description{/t}</b></td>
                                                                <td class="row2" width="12"><b>{t}Unit Qty{/t}</b></td>
                                                                {if $otherincome_details.tax_system != 'no_tax'}
                                                                    <td class="row2"><b>{t}Unit Net{/t}</b></td>
                                                                {else}
                                                                    <td class="row2"><b>{t}Unit Gross{/t}</b></td>
                                                                {/if}
                                                                <td class="row2"><b>{t}Unit Discount{/t}</b></td>
                                                                {if $otherincome_details.tax_system != 'no_tax'}
                                                                    <td class="row2"><b>{t}Net{/t}</b></td>
                                                                    {if '/^vat_/'|preg_match:$otherincome_details.tax_system}<td class="row2"><b>{t}VAT Tax Code{/t}</b></td>{/if}
                                                                    <td class="row2"><b>{if '/^vat_/'|preg_match:$otherincome_details.tax_system}{t}VAT{/t}{else}{t}Sales Tax{/t}{/if} {t}Rate{/t}</b></td>
                                                                    <td class="row2"><b>{if '/^vat_/'|preg_match:$otherincome_details.tax_system}{t}VAT{/t}{else}{t}Sales Tax{/t}{/if}</b></td>
                                                                {/if}
                                                                <td class="row2"><b>{t}Gross{/t}</b></td>
                                                            </tr>
                                                            {section name=l loop=$otherincome_items}
                                                                <tr class="olotd4">
                                                                    <td>{$smarty.section.l.index + 1}</td>
                                                                    <td>{$otherincome_items[l].description}</td>
                                                                    <td>{$otherincome_items[l].unit_qty|string_format:"%.2f"}</td>
                                                                    <td>{$currency_sym}{$otherincome_items[l].unit_net|string_format:"%.2f"}</td>
                                                                    <td>{$currency_sym}{$otherincome_items[l].unit_discount|string_format:"%.2f"}</td>
                                                                    {if $otherincome_details.tax_system != 'no_tax'}
                                                                        <td>{$currency_sym}{$otherincome_items[l].subtotal_net|string_format:"%.2f"}</td>
                                                                        {if $otherincome_items[l].sales_tax_exempt}
                                                                            <td colspan="2" align="center">{t}Exempt{/t}</td>
                                                                        {elseif $otherincome_items[l].vat_tax_code == 'T2'}
                                                                            <td colspan="3" align="center">{t}Exempt{/t}</td>
                                                                        {else}
                                                                            {if '/^vat_/'|preg_match:$otherincome_details.tax_system}
                                                                                <td>
                                                                                    {section name=s loop=$vat_tax_codes}
                                                                                        {if $otherincome_items[l].vat_tax_code == $vat_tax_codes[s].tax_key}{$vat_tax_codes[s].tax_key} - {t}{$vat_tax_codes[s].display_name}{/t}{/if}
                                                                                    {/section}
                                                                                </td>
                                                                            {/if}
                                                                            <td>{$otherincome_items[l].unit_tax_rate|string_format:"%.2f"}%</td>
                                                                            <td>{$currency_sym}{$otherincome_items[l].subtotal_tax|string_format:"%.2f"}</td>
                                                                        {/if}
                                                                    {/if}
                                                                    <td>{$currency_sym}{$otherincome_items[l].subtotal_gross|string_format:"%.2f"}</td>
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
                                                <td class="menuhead2">&nbsp;{t}Otherincome Total{/t}</td>
                                            </tr>
                                            <tr>
                                                <td class="menutd2">
                                                    <table width="100%" border="1" cellpadding="3" cellspacing="0" class="olotable">
                                                        <tr>
                                                            <td class="olotd4" width="80%" align="right"><b>{t}Discount{/t}</b></td>
                                                            <td class="olotd4" width="20%" align="right">{$currency_sym}{$otherincome_details.unit_discount|string_format:"%.2f"}</td>
                                                        </tr>
                                                        {if $otherincome_details.tax_system != 'no_tax'}
                                                            <tr>
                                                                <td class="olotd4" width="80%" align="right"><b>{t}Net{/t}</b></td>
                                                                <td class="olotd4" width="20%" align="right">{$currency_sym}{$otherincome_details.unit_net|string_format:"%.2f"}</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="olotd4" width="80%" align="right"><b>{if '/^vat_/'|preg_match:$otherincome_details.tax_system}{t}VAT{/t}{else}{t}Sales Tax{/t} (@ {$otherincome_details.sales_tax_rate|string_format:"%.2f"}%){/if}</b></td>
                                                                <td class="olotd4" width="20%" align="right">{$currency_sym}{$otherincome_details.unit_tax|string_format:"%.2f"}</td>
                                                            </tr>
                                                        {/if}
                                                        <tr>
                                                            <td class="olotd4" width="80%" align="right"><b>{t}Gross{/t}</b></td>
                                                            <td class="olotd4" width="20%" align="right">{$currency_sym}{$otherincome_details.unit_gross|string_format:"%.2f"}</td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                <!-- Otherincome Note -->
                                <tr>
                                    <td>
                                        <table width="100%" cellpadding="4" cellspacing="0" border="0" >
                                            <tr>
                                                <td class="menuhead2">&nbsp;{t}Note{/t}</td>
                                            </tr>
                                            <tr>
                                                <td class="menutd2">{$otherincome_details.note}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                <!-- Hidden Section -->
                                <tr>
                                    <td>
                                        <table width="100%"  cellpadding="3" cellspacing="0" border="0">
                                            <tr>
                                                <td align="left" valign="top" width="100%">
                                                    <input type="hidden" name="qform[otherincome_id]" value="{$otherincome_details.otherincome_id}">
                                                </td>
                                                <td align="right" width="75%"></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                            </table>
                        </td>
                    </tr>
                </table>
            </form>
        </td>
    </tr>
</table>
