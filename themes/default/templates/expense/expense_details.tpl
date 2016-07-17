<!-- Expense Details TPL -->

{include file="expense/javascripts.js"}

            <table width="700" border="0" cellpadding="20" cellspacing="5">
                <tr>
                    <td>
                        <table width="100%" cellpadding="4" cellspacing="0" border="0" >
                            <tr>{section name=i loop=$expense_details}
                                <td class="menuhead2" width="80%">
                                  {$translate_expense_details_title}
                                <td class="menuhead2" width="20%" align="right" valign="middle">
                                    <a href="?page=expense:edit&expenseID={$expense_details[i].EXPENSE_ID}&page_title={$translate_expense_edit_title}" ><img src="{$theme_images_dir}icons/edit.gif"  alt="" height="16" border="0">{$translate_expense_details_edit}</a>
                                    &nbsp;<a><img src="{$theme_images_dir}icons/16x16/help.gif" border="0" alt=""
                                            onMouseOver="ddrivetip('<b>{$translate_expense_details_help_title|nl2br|regex_replace:"/[\r\t\n]/":" "}</b><hr><p>{$translate_expense_details_help_content|nl2br|regex_replace:"/[\r\t\n]/":" "}</p>')"
                                            onMouseOut="hideddrivetip()"></a>
                                </td>
                            </tr>
                            <tr>
                                <td class="menutd2" colspan="2">
                                    <table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
                                        <tr>
                                            <td class="menutd"> {if $error_msg != ""}
                                                <br> {include file="core/error.tpl"}
                                                <br> {/if}

                                                <!-- Main Content -->

                                                <table class="olotable" border="0" cellpadding="5" cellspacing="5" width="100%" summary="Customer Contact">
                                                    <tr>
                                                        <td class="olohead" colspan="4">
                                                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                                <tr>
                                                                    <td class="menuhead2">&nbsp;{$translate_expense_id} {$expense_details[i].EXPENSE_ID}</td>
                                                                </tr>
                                                            </table>

                                                        </td>
                                                    </tr>

                                                    <!-- payee/net row -->
                                                    <tr>
                                                        <td class="menutd"><b>{$translate_expense_payee}</b></td>
                                                        <td class="menutd">{$expense_details[i].EXPENSE_PAYEE}</td>
                                                        <td class="menutd"><b>{$translate_expense_net_amount}</b></td>
                                                        <td class="menutd">{$currency_sym} {$expense_details[i].EXPENSE_NET_AMOUNT}</td>
                                                    </tr>

                                                     <!-- date/tax rate row -->
                                                    <tr>
                                                        <td class="menutd"><b>{$translate_expense_date}</b></td>
                                                        <td class="menutd" >{$expense_details[i].EXPENSE_DATE|date_format:$date_format}</td>
                                                        <td class="menutd" ><b>{$translate_expense_tax_rate}</b></td>
                                                        <td class="menutd">&nbsp;&nbsp;&nbsp;{$expense_details[i].EXPENSE_TAX_RATE} %</td>
                                                    </tr>

                                                    <!-- expense type/tax amount row -->
                                                    <tr>
                                                        <td class="menutd"><b>{$translate_expense_type}</b></td>
                                                        <td class="menutd" >
                                                            {if $expense_details[i].EXPENSE_TYPE ==1}
                                                                    {$translate_expense_type_1}
                                                            {/if}
                                                            {if $expense_details[i].EXPENSE_TYPE ==2}
                                                                    {$translate_expense_type_2}
                                                            {/if}
                                                            {if $expense_details[i].EXPENSE_TYPE ==3}
                                                                    {$translate_expense_type_3}
                                                            {/if}
                                                            {if $expense_details[i].EXPENSE_TYPE ==4}
                                                                    {$translate_expense_type_4}
                                                            {/if}
                                                            {if $expense_details[i].EXPENSE_TYPE ==5}
                                                                    {$translate_expense_type_5}
                                                            {/if}
                                                            {if $expense_details[i].EXPENSE_TYPE ==6}
                                                                    {$translate_expense_type_6}
                                                            {/if}
                                                             {if $expense_details[i].EXPENSE_TYPE ==7}
                                                                    {$translate_expense_type_7}
                                                            {/if}
                                                            {if $expense_details[i].EXPENSE_TYPE ==8}
                                                                    {$translate_expense_type_8}
                                                            {/if}
                                                            {if $expense_details[i].EXPENSE_TYPE ==9}
                                                                    {$translate_expense_type_9}
                                                            {/if}
                                                            {if $expense_details[i].EXPENSE_TYPE ==10}
                                                                    {$translate_expense_type_10}
                                                            {/if}
                                                            {if $expense_details[i].EXPENSE_TYPE ==11}
                                                                    {$translate_expense_type_11}
                                                            {/if}
                                                            {if $expense_details[i].EXPENSE_TYPE ==12}
                                                                    {$translate_expense_type_12}
                                                            {/if}
                                                            {if $expense_details[i].EXPENSE_TYPE ==13}
                                                                    {$translate_expense_type_13}
                                                            {/if}
                                                            {if $expense_details[i].EXPENSE_TYPE ==14}
                                                                    {$translate_expense_type_14}
                                                            {/if}
                                                            {if $expense_details[i].EXPENSE_TYPE ==15}
                                                                    {$translate_expense_type_15}
                                                            {/if}
                                                            {if $expense_details[i].EXPENSE_TYPE ==16}
                                                                    {$translate_expense_type_16}
                                                            {/if}
                                                            {if $expense_details[i].EXPENSE_TYPE ==17}
                                                                    {$translate_expense_type_17}
                                                            {/if}
                                                            {if $expense_details[i].EXPENSE_TYPE ==18}
                                                                    {$translate_expense_type_18}
                                                            {/if}
                                                            {if $expense_details[i].EXPENSE_TYPE ==19}
                                                                    {$translate_expense_type_19}
                                                            {/if}
                                                            {if $expense_details[i].EXPENSE_TYPE ==20}
                                                                    {$translate_expense_type_20}
                                                            {/if}
                                                            {if $expense_details[i].EXPENSE_TYPE ==21}
                                                                    {$translate_expense_type_21}
                                                            {/if}
                                                        </td>
                                                        <td class="menutd"><b>{$translate_expense_tax_amount}</b></td>
                                                        <td class="menutd">{$currency_sym} {$expense_details[i].EXPENSE_TAX_AMOUNT}</td>
                                                    </tr>

                                                    <!-- payment method/gross amount row -->
                                                    <tr>
                                                        <td class="menutd"><b>{$translate_expense_payment_method}</b></td>
                                                        <td class="menutd">

                                                            {if $expense_details[i].EXPENSE_PAYMENT_METHOD ==1}
                                                                    {$translate_expense_payment_method_1}
                                                            {/if}
                                                            {if $expense_details[i].EXPENSE_PAYMENT_METHOD ==2}
                                                                    {$translate_expense_payment_method_2}
                                                            {/if}
                                                            {if $expense_details[i].EXPENSE_PAYMENT_METHOD ==3}
                                                                    {$translate_expense_payment_method_3}
                                                            {/if}
                                                            {if $expense_details[i].EXPENSE_PAYMENT_METHOD ==4}
                                                                    {$translate_expense_payment_method_4}
                                                            {/if}
                                                            {if $expense_details[i].EXPENSE_PAYMENT_METHOD ==5}
                                                                    {$translate_expense_payment_method_5}
                                                            {/if}
                                                            {if $expense_details[i].EXPENSE_PAYMENT_METHOD ==6}
                                                                    {$translate_expense_payment_method_6}
                                                            {/if}
                                                             {if $expense_details[i].EXPENSE_PAYMENT_METHOD ==7}
                                                                    {$translate_expense_payment_method_7}
                                                            {/if}
                                                            {if $expense_details[i].EXPENSE_PAYMENT_METHOD ==8}
                                                                    {$translate_expense_payment_method_8}
                                                            {/if}
                                                            {if $expense_details[i].EXPENSE_PAYMENT_METHOD ==9}
                                                                    {$translate_expense_payment_method_9}
                                                            {/if}
                                                            {if $expense_details[i].EXPENSE_PAYMENT_METHOD ==10}
                                                                    {$translate_expense_payment_method_10}
                                                            {/if}
                                                            {if $expense_details[i].EXPENSE_PAYMENT_METHOD ==11}
                                                                    {$translate_expense_payment_method_11}
                                                            {/if}

                                                        </td>
                                                        <td class="menutd"><b>{$translate_expense_gross_amount}</b></td>
                                                        <td class="menutd">{$currency_sym} {$expense_details[i].EXPENSE_GROSS_AMOUNT}</td>
                                                    </tr>
                                                    <tr class="row2">
                                                        <td class="menutd" colspan="4"></td>
                                                    </tr>

                                                    <!-- notes -->
                                                    <tr>
                                                        <td class="menutd"><b>{$translate_expense_notes}</b></td>
                                                        <td class="menutd" colspan="3"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="menutd" colspan="3">{$expense_details[i].EXPENSE_NOTES}</td>
                                                        <td class="menutd"></td>
                                                    </tr>
                                                    <tr class="row2">
                                                        <td class="menutd" colspan="4"></td>
                                                    </tr>

                                                        <!-- items -->
                                                     <tr>
                                                        <td class="menutd"><b>{$translate_expense_items}</b></td>
                                                        <td class="menutd" colspan="3"></td>
                                                     </tr>

                                                    <tr>
                                                        <td class="menutd" colspan="3">{$expense_details[i].EXPENSE_ITEMS}</td>
                                                        <td class="menutd"></td>
                                                    </tr>
                                                    {assign var="expenseID" value=$expense_details[i].EXPENSE_ID}
                                                    {/section}
                                            </table>

                                       <!-- end of main content -->

                                   </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
                   
