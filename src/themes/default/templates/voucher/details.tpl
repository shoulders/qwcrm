<!-- details.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<table width="700" cellpadding="4" cellspacing="0" border="0" class="olotable">
    <tr>
        <td class="olotd4">
            <table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{t}Voucher{/t}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a href="index.php?component=voucher&page_tpl=edit&voucher_id={$voucher_details.voucher_id}" ><img src="{$theme_images_dir}icons/edit.gif"  alt="" height="16" border="0">{t}Edit{/t}</a>
                        <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}VOUCHER_DETAILS_HELP_TITLE{/t}</strong></div><hr><div>{t escape=js}VOUCHER_DETAILS_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
                    </td>
                </tr>
                <tr>
                    <td class="olotd4" valign="top" colspan="2">
                        <table cellpadding="3" cellspacing="0" border="0" width="100%">
                            <tr>
                                <td><h2>{t}Voucher{/t}</h2></td>
                            </tr>
                        </table>
                        <hr>
                        <table cellpadding="3" cellspacing="0" border="0" width="100%">
                            <tr>

                                <!-- Client Details -->
                                <td valign="top" width="50%">
                                    <p><b>{t}Client{/t} </b><a href="index.php?component=client&page_tpl=details&client_id={$voucher_details.client_id}">{$client_details.display_name}</a></p>
                                    <p><strong>{t}Address{/t}</strong></p>
                                    <p>
                                        {$client_details.address|nl2br|regex_replace:"/[\r\t\n]/":" "}<br>
                                        {$client_details.city}<br>
                                        {$client_details.state}<br>
                                        {$client_details.zip}<br>
                                        {$client_details.country}
                                    </p>
                                </td>

                                <!-- Voucher Details -->
                                <td valign="top" width="50%">
                                    <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                        <tr>
                                            <td><b>{t}Voucher ID{/t}</b></td>
                                            <td>{$voucher_details.voucher_id}</td>
                                        </tr>
                                        <tr>
                                            <td><b>{t}Last Employee{/t}</b></td>
                                            <td>
                                                <a href="index.php?component=user&page_tpl=details&user_id={$voucher_details.employee_id}">{$employee_display_name}</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>{t}Workorder ID{/t}</b></td>
                                            <td><a href="index.php?component=workorder&page_tpl=details&workorder_id={$voucher_details.workorder_id}">{$voucher_details.workorder_id}</a></td>
                                        </tr>
                                        <tr>
                                            <td><b>{t}Invoice ID{/t}</b></td>
                                            <td><a href="index.php?component=invoice&page_tpl=details&invoice_id={$voucher_details.invoice_id}">{$voucher_details.invoice_id}</a></td>
                                        </tr>
                                        <tr>
                                            <td><b>{t}Type{/t}</b></td>
                                            <td>
                                                {section name=s loop=$voucher_types}
                                                    {if $voucher_details.type == $voucher_types[s].type_key}{t}{$voucher_types[s].display_name}{/t}{/if}
                                                {/section}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>{t}Code{/t}</b></td>
                                            <td>{$voucher_details.voucher_code}</td>
                                        </tr>
                                        <tr>
                                            <td><b>{t}Blocked{/t}</b></td>
                                            <td>
                                                {if $voucher_details.blocked == '0'}{t}No{/t}{/if}
                                                {if $voucher_details.blocked == '1'}{t}Yes{/t}{/if}
                                            </td>
                                        </tr>
                                        {if $voucher_details.tax_system != 'no_tax'}
                                            <tr>
                                                <td><b>{t}Net{/t}</b></td>
                                                <td>{$currency_sym}{$voucher_details.unit_net|string_format:"%.2f"}</td>
                                            </tr>
                                            <tr>
                                                <td><b>{if '/^vat_/'|preg_match:$voucher_details.tax_system}{t}VAT{/t}{else}{t}Sales Tax{/t}{/if}</b></td>
                                                <td>{$currency_sym}{$voucher_details.unit_tax|string_format:"%.2f"}</td>
                                            </tr>
                                        {/if}
                                        <tr>
                                            <td><b>{t}Gross{/t}</b></td>
                                            <td>{$currency_sym}{$voucher_details.unit_gross|string_format:"%.2f"}</td>
                                        </tr>
                                        <tr>
                                            <td><b>{t}Opened On{/t}</b></td>
                                            <td>{$voucher_details.opened_on|date_format:$date_format}</td>
                                        </tr>
                                        <tr>
                                            <td><b>{t}Expires On{/t}</b></td>
                                            <td>{$voucher_details.expiry_date|date_format:$date_format}</td>
                                        </tr>
                                        <tr>
                                            <td><b>{t}Closed On{/t}</b></td>
                                            <td>{$voucher_details.closed_on|date_format:$date_format}</td>
                                        </tr>
                                        <tr>
                                            <td><b>{t}Last Active{/t}</b></td>
                                            <td>{$voucher_details.last_active|date_format:$date_format}</td>
                                        </tr>
                                        <tr>
                                            <td><b>{t}Status{/t}</b></td>
                                            <td>
                                                {section name=s loop=$voucher_statuses}
                                                    {if $voucher_details.status == $voucher_statuses[s].status_key}{t}{$voucher_statuses[s].display_name}{/t}{/if}
                                                {/section}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>{t}Redemptions{/t}</b></td>
                                            <td>
                                                {$voucher_details.redemptions|voucher_redemptions}
                                            </td>
                                        </tr>

                                    </table>
                                </td>
                            </tr>
                        </table>
                        <table cellpadding="3" cellspacing="0" border="0" width="100%">
                            <tr>
                                <td><b>{t}Note{/t}:</b></td>
                            </tr>
                            <tr>
                                <td>{$voucher_details.note}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <button type="button" onclick="window.open('index.php?component=voucher&page_tpl=print&voucher_id={$voucher_details.voucher_id}&commContent=voucher&commType=htmlBrowser');">{t}Print HTML{/t}</button>
                        <button type="button" onclick="confirm('Are you sure you want to email this voucher to the client?') && $.ajax( { url:'index.php?component=voucher&page_tpl=email&voucher_id={$voucher_details.voucher_id}&commContent=voucher&commType=pdfEmail', success: function(data) { $('body').append(data); } } );"><img src="{$theme_images_dir}icons/pdf_small.png"  height="14" alt="pdf">{t}Email Voucher{/t}</button>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
