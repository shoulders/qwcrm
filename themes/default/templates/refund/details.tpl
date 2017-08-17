<!-- details.tpl -->
<table width="700" border="0" cellpadding="20" cellspacing="5">
    <tr>
        <td>
            <table width="100%" cellpadding="4" cellspacing="0" border="0">                
                <tr>                    
                    <td class="menuhead2" width="80%">{t}Refund Details {/t}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a href="index.php?page=refund:edit&refund_id={$refund_id}" ><img src="{$theme_images_dir}icons/edit.gif"  alt="" height="16" border="0">{t}Edit{/t}</a>&nbsp;
                        <a>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}REFUND_DETAILS_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}REFUND_DETAILS_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
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
                                                        <td class="menuhead2">&nbsp;{t}Refund ID{/t} {$refund_id}</td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="menutd"><b>{t}Payee{/t}</b></td>
                                            <td class="menutd">{$refund_details.payee}</td>
                                            <td class="menutd"><b>{t}Net Amount{/t}</b></td>
                                            <td class="menutd">{$currency_sym} {$refund_details.net_amount}</td>
                                        </tr>                                        
                                        <tr>
                                            <td class="menutd"><b>{t}Date{/t}</b></td>
                                            <td class="menutd" >{$refund_details.date|date_format:$date_format}</td>
                                            <td class="menutd" ><b>{t}VAT Rate{/t}</b></td>
                                            <td class="menutd">&nbsp;&nbsp;&nbsp;{$refund_details.tax_rate} %</td>
                                        </tr>                                        
                                        <tr>
                                            <td class="menutd"><b>{t}Type{/t}</b></td>
                                            <td class="menutd">
                                                {if $refund_details.type == 1}{t}REFUND_TYPE_1{/t}{/if}
                                                {if $refund_details.type == 2}{t}REFUND_TYPE_2{/t}{/if}
                                                {if $refund_details.type == 3}{t}REFUND_TYPE_3{/t}{/if}
                                                {if $refund_details.type == 4}{t}REFUND_TYPE_4{/t}{/if}
                                                {if $refund_details.type == 5}{t}REFUND_TYPE_5{/t}{/if}
                                            </td>
                                            <td class="menutd"><b>{t}VAT Amount{/t}</b></td>
                                            <td class="menutd">{$currency_sym} {$refund_details.tax_amount}</td>
                                        </tr>                                        
                                        <tr>
                                            <td class="menutd"><b>{t}Refund Payment Method{/t}</b></td>
                                            <td class="menutd">
                                                {if $refund_details.payment_method == 1}{t}REFUND_PAYMENT_METHOD_1{/t}{/if}
                                                {if $refund_details.payment_method == 2}{t}REFUND_PAYMENT_METHOD_2{/t}{/if}
                                                {if $refund_details.payment_method == 3}{t}REFUND_PAYMENT_METHOD_3{/t}{/if}
                                                {if $refund_details.payment_method == 4}{t}REFUND_PAYMENT_METHOD_4{/t}{/if}
                                                {if $refund_details.payment_method == 5}{t}REFUND_PAYMENT_METHOD_5{/t}{/if}
                                                {if $refund_details.payment_method == 6}{t}REFUND_PAYMENT_METHOD_6{/t}{/if}
                                                {if $refund_details.payment_method == 7}{t}REFUND_PAYMENT_METHOD_7{/t}{/if}
                                                {if $refund_details.payment_method == 8}{t}REFUND_PAYMENT_METHOD_8{/t}{/if}
                                                {if $refund_details.payment_method == 9}{t}REFUND_PAYMENT_METHOD_9{/t}{/if}
                                                {if $refund_details.payment_method == 10}{t}REFUND_PAYMENT_METHOD_10{/t}{/if}
                                                {if $refund_details.payment_method == 11}{t}REFUND_PAYMENT_METHOD_11{/t}{/if}
                                            </td>
                                            <td class="menutd"><b>{t}Gross Amount{/t}</b></td>
                                            <td class="menutd">{$currency_sym} {$refund_details.gross_amount}</td>
                                        </tr>
                                        <tr class="row2">
                                            <td class="menutd" colspan="4"></td>
                                        </tr>                                        
                                        <tr>
                                            <td class="menutd"><b>{t}Notes{/t}</b></td>
                                            <td class="menutd" colspan="3"></td>
                                        </tr>
                                        <tr>
                                            <td class="menutd" colspan="3">{$refund_details.notes}</td>
                                            <td class="menutd"></td>
                                        </tr>
                                        <tr class="row2">
                                            <td class="menutd" colspan="4"></td>
                                        </tr>
                                        <tr>
                                            <td class="menutd"><b>{t}Items{/t}</b></td>
                                            <td class="menutd" colspan="3"></td>
                                        </tr>
                                        <tr>
                                            <td class="menutd" colspan="3">{$refund_details.items}</td>
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