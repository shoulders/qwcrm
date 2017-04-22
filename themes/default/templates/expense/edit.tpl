<!-- edit.tpl -->
<script src="{$theme_js_dir}tinymce/tinymce.min.js"></script>
<script src="{$theme_js_dir}editor-config.js"></script>
<link rel="stylesheet" href="{$theme_js_dir}jscal2/css/jscal2.css" />
<link rel="stylesheet" href="{$theme_js_dir}jscal2/css/steel/steel.css" />
<script src="{$theme_js_dir}jscal2/jscal2.js"></script>
<script src="{$theme_js_dir}jscal2/unicode-letter.js"></script>
<script>{include file="`$theme_js_dir_finc`jscal2/language.js"}</script>

<table width="100%" border="0" cellpadding="20" cellspacing="0">
    <tr>
        <td>
            <table width="700" cellpadding="5" cellspacing="0" border="0" >
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{$translate_expense_edit_title}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" alt="" border="0" onMouseOver="ddrivetip('<b>{$translate_expense_edit_help_title|nl2br|regex_replace:"/[\r\t\n]/":" "}</b><hr><p>{$translate_expense_edit_help_content|nl2br|regex_replace:"/[\r\t\n]/":" "}</p>');" onMouseOut="hideddrivetip();" onClick="window.location">
                        </a>
                    </td>
                </tr>
                <tr>
                    <td class="menutd2" colspan="2">
                        <table width="100%" class="olotable" cellpadding="5" cellspacing="0" border="0" >
                            <tr>
                                <td width="100%" valign="top" >
                                    <table class="menutable" width="100%" border="0" cellpadding="0" cellspacing="0" >
                                     <tr>
                                         <td>
                                            {section name=q loop=$expense_details}                                                
                                                <form method="post" action="index.php?page=expense:edit" name="edit_expense" id="edit_expense">                                                
                                                    <table width="100%" cellpadding="2" cellspacing="2" border="0">                                             
                                                        <tr>
                                                            <td colspan="2" align="left">
                                                        <tr>
                                                            <td><input type="hidden" name="page" value="expense:edit"></td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><b>{$translate_expense_id}</b></td>
                                                            <td colspan="3"><input name="expense_id" value="{$expense_details[q].EXPENSE_ID}" type="hidden">{$expense_details[q].EXPENSE_ID}</td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><b>{$translate_expense_payee}</b><span style="color: #ff0000"> *</span></td>
                                                            <td colspan="3"><input name="expensePayee" class="olotd5" size="50" value="{$expense_details[q].EXPENSE_PAYEE}" type="text" maxlength="50" required onkeydown="return onlyAlphaNumeric(event);"></td>
                                                        </tr><tr>
                                                            <td align="right"><b>{$translate_expense_date}</b><span style="color: #ff0000"> *</span></td>
                                                            <td>
                                                                <input id="expenseDate" name="expenseDate" class="olotd5" size="10" value="{$expense_details[q].EXPENSE_DATE|date_format:$date_format}" type="text" maxlength="10" pattern="{literal}^[0-9]{1,2}(\/|-)[0-9]{1,2}(\/|-)[0-9]{2,2}([0-9]{2,2})?${/literal}" required onkeydown="return onlyDate(event);">
                                                                <input id="expenseDate_button" value="+" type="button">                                                            
                                                                <script>                                                                
                                                                    Calendar.setup( {
                                                                        trigger     : "expenseDate_button",
                                                                        inputField  : "expenseDate",
                                                                        dateFormat  : "{$date_format}"                                                                                            
                                                                    } );                                                                  
                                                                </script>                                                            
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><b>{$translate_expense_type}</b><span style="color: #ff0000"> *</span></td>
                                                            <td>
                                                                <select id="expenseType" name="expenseType" class="olotd5" col="30" style="width: 150px;" value="{$expense_details[q].EXPENSE_TYPE}"/>
                                                                    <option value="1">{$translate_expense_type_1}{if $expense_details[q].EXPENSE_TYPE == '1'} selected{/if}</option>
                                                                    <option value="2">{$translate_expense_type_2}{if $expense_details[q].EXPENSE_TYPE == '2'} selected{/if}</option>
                                                                    <option value="3">{$translate_expense_type_3}{if $expense_details[q].EXPENSE_TYPE == '3'} selected{/if}</option>
                                                                    <option value="4">{$translate_expense_type_4}{if $expense_details[q].EXPENSE_TYPE == '4'} selected{/if}</option>
                                                                    <option value="5">{$translate_expense_type_5}{if $expense_details[q].EXPENSE_TYPE == '5'} selected{/if}</option>
                                                                    <option value="6">{$translate_expense_type_6}{if $expense_details[q].EXPENSE_TYPE == '6'} selected{/if}</option>
                                                                    <option value="7">{$translate_expense_type_7}{if $expense_details[q].EXPENSE_TYPE == '7'} selected{/if}</option>
                                                                    <option value="8">{$translate_expense_type_8}{if $expense_details[q].EXPENSE_TYPE == '8'} selected{/if}</option>
                                                                    <option value="9">{$translate_expense_type_9}{if $expense_details[q].EXPENSE_TYPE == '9'} selected{/if}</option>
                                                                    <option value="10">{$translate_expense_type_10}{if $expense_details[q].EXPENSE_TYPE == '10'} selected{/if}</option>
                                                                    <option value="11">{$translate_expense_type_11}{if $expense_details[q].EXPENSE_TYPE == '11'} selected{/if}</option>
                                                                    <option value="12">{$translate_expense_type_12}{if $expense_details[q].EXPENSE_TYPE == '12'} selected{/if}</option>
                                                                    <option value="13">{$translate_expense_type_13}{if $expense_details[q].EXPENSE_TYPE == '13'} selected{/if}</option>
                                                                    <option value="14">{$translate_expense_type_14}{if $expense_details[q].EXPENSE_TYPE == '14'} selected{/if}</option>
                                                                    <option value="15">{$translate_expense_type_15}{if $expense_details[q].EXPENSE_TYPE == '15'} selected{/if}</option>
                                                                    <option value="16">{$translate_expense_type_16}{if $expense_details[q].EXPENSE_TYPE == '16'} selected{/if}</option>
                                                                    <option value="17">{$translate_expense_type_17}{if $expense_details[q].EXPENSE_TYPE == '17'} selected{/if}</option>
                                                                    <option value="18">{$translate_expense_type_18}{if $expense_details[q].EXPENSE_TYPE == '18'} selected{/if}</option>
                                                                    <option value="19">{$translate_expense_type_19}{if $expense_details[q].EXPENSE_TYPE == '19'} selected{/if}</option>
                                                                    <option value="20">{$translate_expense_type_20}{if $expense_details[q].EXPENSE_TYPE == '20'} selected{/if}</option>
                                                                    <option value="21">{$translate_expense_type_21}{if $expense_details[q].EXPENSE_TYPE == '21'} selected{/if}</option>
                                                                </select>
                                                            </td>                                                            
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><b>{$translate_expense_payment_method}</b><span style="color: #ff0000"> *</span></td>
                                                            <td>
                                                                <select id="expensePaymentMethod" name="expensePaymentMethod" class="olotd5" style="width: 150px;" value="{$expense_details[q].EXPENSE_PAYMENT_METHOD}"/>
                                                                    <option value="1">{$translate_expense_payment_method_1}{if $expense_details[q].EXPENSE_METHOD == '1'} selected{/if}</option>
                                                                    <option value="2">{$translate_expense_payment_method_2}{if $expense_details[q].EXPENSE_METHOD == '2'} selected{/if}</option>
                                                                    <option value="3">{$translate_expense_payment_method_3}{if $expense_details[q].EXPENSE_METHOD == '3'} selected{/if}</option>
                                                                    <option value="4">{$translate_expense_payment_method_4}{if $expense_details[q].EXPENSE_METHOD == '4'} selected{/if}</option>
                                                                    <option value="5">{$translate_expense_payment_method_5}{if $expense_details[q].EXPENSE_METHOD == '5'} selected{/if}</option>
                                                                    <option value="6">{$translate_expense_payment_method_6}{if $expense_details[q].EXPENSE_METHOD == '6'} selected{/if}</option>
                                                                    <option value="7">{$translate_expense_payment_method_7}{if $expense_details[q].EXPENSE_METHOD == '7'} selected{/if}</option>
                                                                    <option value="8">{$translate_expense_payment_method_8}{if $expense_details[q].EXPENSE_METHOD == '8'} selected{/if}</option>
                                                                    <option value="9">{$translate_expense_payment_method_9}{if $expense_details[q].EXPENSE_METHOD == '9'} selected{/if}</option>
                                                                    <option value="10">{$translate_expense_payment_method_10}{if $expense_details[q].EXPENSE_METHOD == '10'} selected{/if}</option>
                                                                    <option value="11">{$translate_expense_payment_method_11}{if $expense_details[q].EXPENSE_METHOD == '11'} selected{/if}</option>
                                                                </select>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><b>{$translate_expense_net_amount}</b></td>
                                                            <td><input name="expenseNetAmount" class="olotd5" size="10" value="{$expense_details[q].EXPENSE_NET_AMOUNT}" type="text" maxlength="10" pattern="{literal}[0-9]{1,7}(.[0-9]{0,2})?{/literal}" required onkeydown="return onlyNumbersPeriod(event);"></td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><span style="color: #ff0000"></span><b>{$translate_expense_tax_rate}</b></td>
                                                            <td><input name="expenseTaxRate" class="olotd5" size="4" value="{$expense_details[q].EXPENSE_TAX_RATE}" type="text" maxlength="5" pattern="{literal}^[0-9]{0,2}(\.[0-9]{0,2})?${/literal}" required onkeydown="return onlyNumbersPeriod(event);"/><b>%</b></td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><b>{$translate_expense_tax_amount}</b></td>
                                                            <td><input name="expenseTaxAmount" class="olotd5" size="10" value="{$expense_details[q].EXPENSE_TAX_AMOUNT}" type="text" maxlength="10" pattern="{literal}[0-9]{1,7}(.[0-9]{0,2})?{/literal}" required onkeydown="return onlyNumbersPeriod(event);"/></td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><b>{$translate_expense_gross_amount}</b><span style="color: #ff0000"> *</span></td>
                                                            <td><input name="expenseGrossAmount" class="olotd5" size="10" value="{$expense_details[q].EXPENSE_GROSS_AMOUNT}" type="text" maxlength="10" pattern="{literal}[0-9]{1,7}(.[0-9]{0,2})?{/literal}" required onkeydown="return onlyNumbersPeriod(event);"/></td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><b>{$translate_expense_notes}</b></td>
                                                            <td><textarea name="expenseNotes" class="olotd5" cols="50" rows="15">{$expense_details[q].EXPENSE_NOTES}</textarea></td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><b>{$translate_expense_items}</b><span style="color: #ff0000"> *</span></td>
                                                            <td><textarea name="expenseItems" class="olotd5 mceCheckForContent" cols="50" rows="15">{$expense_details[q].EXPENSE_ITEMS}</textarea></td>
                                                        </tr>
                                                        <tr>
                                                            <td></td>
                                                            <td><input name="submit" class="olotd5" value="{$translate_expense_update_button}" type="submit"></td>
                                                        </tr>                                        
                                                    </table>
                                                </form>
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
        </td>
    </tr>
</table>