<!-- details.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<table width="700" border="0" cellpadding="20" cellspacing="5">
    <tr>
        <td>
            <table width="100%" cellpadding="4" cellspacing="0" border="0">
                <tr>
                    <td class="menuhead2" width="80%">{t}Payment Details{/t}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a href="index.php?component=payment&page_tpl=edit&payment_id={$payment_id}"><img src="{$theme_images_dir}icons/edit.gif" alt="" height="16" border="0">{t}Edit{/t}</a>
                        <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}PAYMENT_DETAILS_HELP_TITLE{/t}</strong></div><hr><div>{t escape=js}PAYMENT_DETAILS_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">                        
                    </td>
                </tr>
                <tr>
                    <td class="menutd2" colspan="2">
                        <table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
                            <tr>
                                <td class="menutd">
                                    <table class="olotable" border="0" cellpadding="5" cellspacing="5" width="100%" summary="Client Contact">
                                        <tr>
                                            <td class="olohead" colspan="4">
                                                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                    <tr>
                                                        <td class="menuhead2">&nbsp;{t}Payment ID{/t} {$payment_details.payment_id}</td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="menutd"><b>{t}Payment ID{/t}</b></td>
                                            <td class="menutd">{$payment_details.payment_id}</td>
                                            <td class="menutd"><b>{t}Employee{/t}</b></td>
                                            <td class="menutd"><a href="index.php?component=user&page_tpl=details&user_id={$payment_details.employee_id}">{$employee_display_name}</a></td>
                                        </tr>
                                        <tr>
                                            <td class="menutd"><b>{t}Client{/t}</b></td>
                                            <td class="menutd"><a href="index.php?component=client&page_tpl=details&client_id={$payment_details.client_id}">{$client_display_name}</a></td>
                                            <td class="menutd"><b>{t}Supplier{/t}</b></td>
                                            <td class="menutd"><a href="index.php?component=supplier&page_tpl=details&supplier_id={$payment_details.supplier_id}">{$supplier_display_name}</a></td>
                                            
                                        </tr>
                                        <tr>
                                            <td class="menutd" ><b>{t}Date{/t}</b></td>
                                            <td class="menutd">{$payment_details.date|date_format:$date_format}</td>
                                            <td class="menutd"></td>
                                            <td class="menutd"></td>
                                        </tr>
                                        <tr>
                                            <td class="menutd" ><b>{t}Invoice ID{/t}</b></td>
                                            <td class="menutd"><a href="index.php?component=invoice&page_tpl=details&invoice_id={$payment_details.invoice_id}">{$payment_details.invoice_id}</a></td>
                                            <td class="menutd" ><b>{t}Voucher ID{/t}</b></td>
                                            <td class="menutd"><a href="index.php?component=voucher&page_tpl=details&voucher_id={$payment_details.voucher_id}">{$payment_details.voucher_id}</a></td>
                                        </tr>
                                        <tr>
                                            <td class="menutd" ><b>{t}Refund ID{/t}</b></td>
                                            <td class="menutd"><a href="index.php?component=refund&page_tpl=details&refund_id={$payment_details.refund_id}">{$payment_details.refund_id}</a></td>
                                            <td class="menutd" ><b>{t}Expense ID{/t}</b></td>
                                            <td class="menutd"><a href="index.php?component=expense&page_tpl=details&expense_id={$payment_details.expense_id}">{$payment_details.expense_id}</a></td>
                                        </tr>
                                        <tr>
                                            <td class="menutd" ><b>{t}Otherincome ID{/t}</b></td>
                                            <td class="menutd"><a href="index.php?component=otherincome&page_tpl=details&otherincome_id={$payment_details.otherincome_id}">{$payment_details.otherincome_id}</a></td>
                                            <td class="menutd" >
                                                <b>{t}Creditenote ID{/t}</b><br>
                                                <b>{t}Credit Note Action{/t}<b>
                                            </td>
                                            <td class="menutd">
                                                <a href="index.php?component=creditnote&page_tpl=details&creditnote_id={$payment_details.creditnote_id}">{$payment_details.creditnote_id}</a><br>
                                                {section name=c loop=$payment_creditnote_action_types}    
                                                    {if $payment_details.creditnote_action == $payment_types[c].type_key}{t}{$payment_types[c].display_name}{/t}{/if}                    
                                                {/section}
                                            </td>
                                            
                                        </tr>
                                        <tr>
                                            <td class="menutd"><b>{t}Payment Method{/t}</b></td>
                                            <td class="menutd">
                                                {section name=s loop=$payment_methods}    
                                                    {if $payment_details.method == $payment_methods[s].method_key}{t}{$payment_methods[s].display_name}{/t}{/if}   
                                                {/section}
                                            </td>
                                            <td class="menutd"><b>{t}Type{/t}</b></td>
                                            <td class="menutd">
                                                {section name=t loop=$payment_types}    
                                                    {if $payment_details.type == $payment_types[t].type_key}{t}{$payment_types[t].display_name}{/t}{/if}                    
                                                {/section}
                                            </td>
                                        </tr>                                        
                                        <tr>
                                            <td class="menutd"><b>{t}Status{/t}</b></td>
                                            <td class="menutd">
                                                {section name=t loop=$payment_statuses}    
                                                    {if $payment_details.status == $payment_statuses[t].status_key}{t}{$payment_statuses[t].display_name}{/t}{/if}                    
                                                {/section}
                                            </td>
                                            <td class="menutd"><b>{t}Amount{/t}</b></td>
                                            <td class="menutd">{$currency_sym} {$payment_details.amount|string_format:"%.2f"}</td>
                                        </tr>
                                        <tr>
                                            <td class="menutd"><b>{t}Additional Info{/t}</b></td>
                                            <td class="menutd">{if $payment_details.additional_info|paymentadinfodisplay}{$payment_details.additional_info|paymentadinfodisplay}{else}{t}none{/t}{/if}</td>
                                            <td class="menutd"></td>
                                            <td class="menutd"></td>
                                        </tr> 
                                        <tr>
                                            <td class="menutd"><b>{t}Note{/t}</b></td>
                                            <td class="menutd" colspan="3"></td>
                                        </tr>
                                        <tr>
                                            <td class="menutd" colspan="3">{$payment_details.note}</td>
                                            <td class="menutd"></td>
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