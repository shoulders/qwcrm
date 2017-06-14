<!-- details.tpl -->
<table width="700" border="0" cellpadding="20" cellspacing="5">
    <tr>
        <td>
            <table width="100%" cellpadding="4" cellspacing="0" border="0">
                <tr>
                    <td class="menuhead2" width="80%">{t}Expense Details {/t}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a href="index.php?page=expense:edit&expense_id={$expense_details.EXPENSE_ID}"><img src="{$theme_images_dir}icons/edit.gif"  alt="" height="16" border="0">{t}expense_details_edit{/t}</a>&nbsp;
                        <a>                            
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}EXPENSE_DETAILS_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}EXPENSE_DETAILS_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
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
                                                        <td class="menuhead2">&nbsp;{t}Expense ID{/t} {$expense_details.EXPENSE_ID}</td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="menutd"><b>{t}pAYEE{/t}</b></td>
                                            <td class="menutd">{$expense_details.EXPENSE_PAYEE}</td>
                                            <td class="menutd"><b>{t}Net Amount{/t}</b></td>
                                            <td class="menutd">{$currency_sym} {$expense_details.EXPENSE_NET_AMOUNT}</td>
                                        </tr>
                                        <tr>
                                            <td class="menutd"><b>{t}Date{/t}</b></td>
                                            <td class="menutd" >{$expense_details.EXPENSE_DATE|date_format:$date_format}</td>
                                            <td class="menutd" ><b>{t}Tax Rate{/t}</b></td>
                                            <td class="menutd">&nbsp;&nbsp;&nbsp;{$expense_details.EXPENSE_TAX_RATE} %</td>
                                        </tr>
                                        <tr>
                                            <td class="menutd"><b>{t}Type{/t}</b></td>
                                            <td class="menutd" >
                                                {if $expense_details.EXPENSE_TYPE ==1}{t}EXPENSE_TYPE_1{/t}{/if}
                                                {if $expense_details.EXPENSE_TYPE ==2}{t}EXPENSE_TYPE_2{/t}{/if}
                                                {if $expense_details.EXPENSE_TYPE ==3}{t}EXPENSE_TYPE_3{/t}{/if}
                                                {if $expense_details.EXPENSE_TYPE ==4}{t}EXPENSE_TYPE_4{/t}{/if}
                                                {if $expense_details.EXPENSE_TYPE ==5}{t}EXPENSE_TYPE_5{/t}{/if}
                                                {if $expense_details.EXPENSE_TYPE ==6}{t}EXPENSE_TYPE_6{/t}{/if}
                                                {if $expense_details.EXPENSE_TYPE ==7}{t}EXPENSE_TYPE_7{/t}{/if}
                                                {if $expense_details.EXPENSE_TYPE ==8}{t}EXPENSE_TYPE_8{/t}{/if}
                                                {if $expense_details.EXPENSE_TYPE ==9}{t}EXPENSE_TYPE_9{/t}{/if}
                                                {if $expense_details.EXPENSE_TYPE ==10}{t}EXPENSE_TYPE_10{/t}{/if}
                                                {if $expense_details.EXPENSE_TYPE ==11}{t}EXPENSE_TYPE_11{/t}{/if}
                                                {if $expense_details.EXPENSE_TYPE ==12}{t}EXPENSE_TYPE_12{/t}{/if}
                                                {if $expense_details.EXPENSE_TYPE ==13}{t}EXPENSE_TYPE_13{/t}{/if}
                                                {if $expense_details.EXPENSE_TYPE ==14}{t}EXPENSE_TYPE_14{/t}{/if}
                                                {if $expense_details.EXPENSE_TYPE ==15}{t}EXPENSE_TYPE_15{/t}{/if}
                                                {if $expense_details.EXPENSE_TYPE ==16}{t}EXPENSE_TYPE_16{/t}{/if}
                                                {if $expense_details.EXPENSE_TYPE ==17}{t}EXPENSE_TYPE_17{/t}{/if}
                                                {if $expense_details.EXPENSE_TYPE ==18}{t}EXPENSE_TYPE_18{/t}{/if}
                                                {if $expense_details.EXPENSE_TYPE ==19}{t}EXPENSE_TYPE_19{/t}{/if}
                                                {if $expense_details.EXPENSE_TYPE ==20}{t}EXPENSE_TYPE_20{/t}{/if}
                                                {if $expense_details.EXPENSE_TYPE ==21}{t}EXPENSE_TYPE_21{/t}{/if}
                                            </td>
                                            <td class="menutd"><b>{t}Tax Amount{/t}</b></td>
                                            <td class="menutd">{$currency_sym} {$expense_details.EXPENSE_TAX_AMOUNT}</td>
                                        </tr>                                        
                                        <tr>
                                            <td class="menutd"><b>{t}Payment Method{/t}</b></td>
                                            <td class="menutd">
                                                {if $expense_details.EXPENSE_PAYMENT_METHOD ==1}{t}EXPENSE_PAYMENT_METHOD_1{/t}{/if}
                                                {if $expense_details.EXPENSE_PAYMENT_METHOD ==2}{t}EXPENSE_PAYMENT_METHOD_2{/t}{/if}
                                                {if $expense_details.EXPENSE_PAYMENT_METHOD ==3}{t}EXPENSE_PAYMENT_METHOD_3{/t}{/if}
                                                {if $expense_details.EXPENSE_PAYMENT_METHOD ==4}{t}EXPENSE_PAYMENT_METHOD_4{/t}{/if}
                                                {if $expense_details.EXPENSE_PAYMENT_METHOD ==5}{t}EXPENSE_PAYMENT_METHOD_5{/t}{/if}
                                                {if $expense_details.EXPENSE_PAYMENT_METHOD ==6}{t}EXPENSE_PAYMENT_METHOD_6{/t}{/if}
                                                {if $expense_details.EXPENSE_PAYMENT_METHOD ==7}{t}EXPENSE_PAYMENT_METHOD_7{/t}{/if}
                                                {if $expense_details.EXPENSE_PAYMENT_METHOD ==8}{t}EXPENSE_PAYMENT_METHOD_8{/t}{/if}
                                                {if $expense_details.EXPENSE_PAYMENT_METHOD ==9}{t}EXPENSE_PAYMENT_METHOD_9{/t}{/if}
                                                {if $expense_details.EXPENSE_PAYMENT_METHOD ==10}{t}EXPENSE_PAYMENT_METHOD_10{/t}{/if}
                                                {if $expense_details.EXPENSE_PAYMENT_METHOD ==11}{t}EXPENSE_PAYMENT_METHOD_11{/t}{/if}
                                            </td>
                                            <td class="menutd"><b>{t}Gross Amount{/t}</b></td>
                                            <td class="menutd">{$currency_sym} {$expense_details.EXPENSE_GROSS_AMOUNT}</td>
                                        </tr>
                                        <tr class="row2">
                                            <td class="menutd" colspan="4"></td>
                                        </tr>                                      
                                        <tr>
                                            <td class="menutd"><b>{t}Notes{/t}</b></td>
                                            <td class="menutd" colspan="3"></td>
                                        </tr>
                                        <tr>
                                            <td class="menutd" colspan="3">{$expense_details.EXPENSE_NOTES}</td>
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
                                            <td class="menutd" colspan="3">{$expense_details.EXPENSE_ITEMS}</td>
                                            <td class="menutd"></td>
                                        </tr>
                                        {assign var="expense_id" value=$expense_details.EXPENSE_ID}
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