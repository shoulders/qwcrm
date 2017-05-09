<!-- details.tpl -->
<table width="700" border="0" cellpadding="20" cellspacing="5">
    <tr>
        <td>
            <table width="100%" cellpadding="4" cellspacing="0" border="0">                
                <tr>                    
                    <td class="menuhead2" width="80%">{$translate_refund_details_title}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a href="?page=refund:edit&refund_id={$refund_details.REFUND_ID}&page_title={$translate_refund_edit_title}" ><img src="{$theme_images_dir}icons/edit.gif"  alt="" height="16" border="0">{$translate_refund_details_edit}</a>&nbsp;
                        <a>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" alt="" onMouseOver="ddrivetip('<b>{$translate_refund_details_help_title|nl2br|regex_replace:"/[\r\t\n]/":" "}</b><hr><p>{$translate_refund_details_help_content|nl2br|regex_replace:"/[\r\t\n]/":" "}</p>');" onMouseOut="hideddrivetip();">
                        </a>
                    </td>
                </tr>
                <tr>
                    <td class="menutd2" colspan="2">
                        <table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
                            <tr>
                                <td class="menutd">                             
                                    <table class="olotable" border="0" cellpadding="5" cellspacing="5" width="100%" summary="Customer Contact">
                                        <tr>
                                            <td class="olohead" colspan="4">
                                                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                    <tr>
                                                        <td class="menuhead2">&nbsp;{$translate_refund_id} {$refund_details.REFUND_ID}</td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="menutd"><b>{$translate_refund_payee}</b></td>
                                            <td class="menutd">{$refund_details.REFUND_PAYEE}</td>
                                            <td class="menutd"><b>{$translate_refund_net_amount}</b></td>
                                            <td class="menutd">{$currency_sym} {$refund_details.REFUND_NET_AMOUNT}</td>
                                        </tr>                                        
                                        <tr>
                                            <td class="menutd"><b>{$translate_refund_date}</b></td>
                                            <td class="menutd" >{$refund_details.REFUND_DATE|date_format:$date_format}</td>
                                            <td class="menutd" ><b>{$translate_refund_tax_rate}</b></td>
                                            <td class="menutd">&nbsp;&nbsp;&nbsp;{$refund_details.REFUND_TAX_RATE} %</td>
                                        </tr>                                        
                                        <tr>
                                            <td class="menutd"><b>{$translate_refund_type}</b></td>
                                            <td class="menutd">
                                                {if $refund_details.REFUND_TYPE ==1}{$translate_refund_type_1}{/if}
                                                {if $refund_details.REFUND_TYPE ==2}{$translate_refund_type_2}{/if}
                                                {if $refund_details.REFUND_TYPE ==3}{$translate_refund_type_3}{/if}
                                                {if $refund_details.REFUND_TYPE ==4}{$translate_refund_type_4}{/if}
                                                {if $refund_details.REFUND_TYPE ==5}{$translate_refund_type_5}{/if}
                                            </td>
                                            <td class="menutd"><b>{$translate_refund_tax_amount}</b></td>
                                            <td class="menutd">{$currency_sym} {$refund_details.REFUND_TAX_AMOUNT}</td>
                                        </tr>                                        
                                        <tr>
                                            <td class="menutd"><b>{$translate_refund_payment_method}</b></td>
                                            <td class="menutd">
                                                {if $refund_details.REFUND_PAYMENT_METHOD ==1}{$translate_refund_payment_method_1}{/if}
                                                {if $refund_details.REFUND_PAYMENT_METHOD ==2}{$translate_refund_payment_method_2}{/if}
                                                {if $refund_details.REFUND_PAYMENT_METHOD ==3}{$translate_refund_payment_method_3}{/if}
                                                {if $refund_details.REFUND_PAYMENT_METHOD ==4}{$translate_refund_payment_method_4}{/if}
                                                {if $refund_details.REFUND_PAYMENT_METHOD ==5}{$translate_refund_payment_method_5}{/if}
                                                {if $refund_details.REFUND_PAYMENT_METHOD ==6}{$translate_refund_payment_method_6}{/if}
                                                {if $refund_details.REFUND_PAYMENT_METHOD ==7}{$translate_refund_payment_method_7}{/if}
                                                {if $refund_details.REFUND_PAYMENT_METHOD ==8}{$translate_refund_payment_method_8}{/if}
                                                {if $refund_details.REFUND_PAYMENT_METHOD ==9}{$translate_refund_payment_method_9}{/if}
                                                {if $refund_details.REFUND_PAYMENT_METHOD ==10}{$translate_refund_payment_method_10}{/if}
                                                {if $refund_details.REFUND_PAYMENT_METHOD ==11}{$translate_refund_payment_method_11}{/if}
                                            </td>
                                            <td class="menutd"><b>{$translate_refund_gross_amount}</b></td>
                                            <td class="menutd">{$currency_sym} {$refund_details.REFUND_GROSS_AMOUNT}</td>
                                        </tr>
                                        <tr class="row2">
                                            <td class="menutd" colspan="4"></td>
                                        </tr>                                        
                                        <tr>
                                            <td class="menutd"><b>{$translate_refund_notes}</b></td>
                                            <td class="menutd" colspan="3"></td>
                                        </tr>
                                        <tr>
                                            <td class="menutd" colspan="3">{$refund_details.REFUND_NOTES}</td>
                                            <td class="menutd"></td>
                                        </tr>
                                        <tr class="row2">
                                            <td class="menutd" colspan="4"></td>
                                        </tr>
                                        <tr>
                                            <td class="menutd"><b>{$translate_refund_items}</b></td>
                                            <td class="menutd" colspan="3"></td>
                                        </tr>
                                        <tr>
                                            <td class="menutd" colspan="3">{$refund_details.REFUND_ITEMS}</td>
                                            <td class="menutd"></td>
                                        </tr>
                                        {assign var="refund_id" value=$refund_details.REFUND_ID}                    
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